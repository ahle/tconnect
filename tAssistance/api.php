<?php
error_reporting(0);

header('Access-Control-Allow-Origin: *');
header('Access-Control-Allow-Methods: GET, POST');

$dir = dirname(__FILE__);
require_once $dir.'/php/global.php';
	
if($_GET["o"]=="session" && $_SERVER[REQUEST_METHOD]=="GET"){
	
	function oza_encript($string){
		$textToEncrypt = $string;
		$encryptionMethod = "AES-256-CBC";  // AES is used by the U.S. gov't to encrypt top secret documents.
		$secretHash = "25c6c7ff35b9979b151f2136cd13b0ff";

		//To encrypt
		$encryptedMessage = openssl_encrypt($textToEncrypt, $encryptionMethod, $secretHash);
		return $encryptedMessage;
	}
	
	function oza_decript($encryptedMessage,$secretHash){
		$encryptionMethod = "AES-256-CBC";  // AES is used by the U.S. gov't to encrypt top secret documents.
		//$secretHash = "25c6c7ff35b9979b151f2136cd13b0ff";
		
		//To Decrypt
		$decryptedMessage = openssl_decrypt($encryptedMessage, $encryptionMethod, $secretHash);
		return $decryptedMessage;
	}
	
	//$assistant_prikey = "silex_ozalid2013";
	$app_id = $_GET["p1"];
	$encrypted_ticket = $_GET["ticket"];
	$returnUrl = $_GET["returnUrl"];
	
	function get_private_key($app_id){
		global $db_server,$db_user,$db_pwd,$db_name;
		$con = mysqli_connect($db_server,$db_user,$db_pwd,$db_name);
		// Check connection
		if (mysqli_connect_errno())
		{
			echo "Failed to connect to MySQL: ".mysqli_connect_error();
			exit;
		}
		
		$select_sql = "SELECT * FROM ta_key WHERE remote_app = '".$app_id."' ";
		
		if ($result = mysqli_query($con,$select_sql))
		{
			$row = mysqli_fetch_object($result);
	
			return $row->priv_key;
		}
		
	}
	$priv_key = get_private_key($app_id);
	
	$ticket_str = oza_decript($encrypted_ticket, $priv_key);
	$ticket = json_decode($ticket_str);
	
	// update session
	
	$con = mysqli_connect($db_server,$db_user,$db_pwd,$db_name);
	// Check connection
	if (mysqli_connect_errno())
	{
		echo "Failed to connect to MySQL: ".mysqli_connect_error();
		exit;
	}
	$app_id = $ticket->app_id;
	$remote_session = $ticket->session_id;
	$user_id = $ticket->user_id;		
	
	$local_session = session_id();
	
	$select_sql = "SELECT 1 FROM ta_session WHERE remote_app = '".$app_id."' and remote_session = '".$remote_session."'";
	
	if ($result = mysqli_query($con,$select_sql))
	{
		$row_num = mysqli_num_rows($result);
		if($row_num==0){
			$insert_sql = "INSERT INTO ta_session (remote_app, remote_session, user, local_session, modified_date) VALUES ('".$app_id."', '".$remote_session."', '".$user_id."', '".$local_session."', NOW())";
	
			if (!mysqli_query($con,$insert_sql))
			{
				echo 'Error: ' . mysqli_error($con);
				exit;
			}
		}
		else{
			$update_sql="UPDATE ta_session SET user = '".$user_id."', local_session='".$local_session."' , modified_date=NOW() WHERE remote_app = '".$app_id."' and remote_session = '".$remote_session."'";
	
			if (!mysqli_query($con,$update_sql))
			{
				echo 'Error: ' . mysqli_error($con);
				exit;
			}
		}
	}
	else{
		die('Error: ' . mysqli_error($con));
	}
	
	
	// synchonize user
	// verify that session_start is set in global;
	$_SESSION["user"] = $user_id;
	
	// redirect 
	//$url = $_SERVER['REQUEST_URI'];
	//$url = str_replace("service.php", "index.php", $url); // TODO
	
	header('Location: '.$returnUrl);
	exit;
}	

if($_GET["o"]=="session" && $_SERVER[REQUEST_METHOD]=="PUT"){
	$app_id = $_GET["p1"];
	$remote_session = $_GET["p2"];
	
	parse_str(file_get_contents("php://input"),$session);
	
	//$user_id = $session["user_id"];
	echo json_encode($session);
	update_session($session);
	
	// success
	exit;			
}
else if($_GET["o"]=="key" && $_SERVER[REQUEST_METHOD]=="GET"){
	
	$app_id = $_GET["p1"];
	
	get_new_key($app_id);
	exit;
}	


if($_SERVER['PATH_INFO']=="/users" && $_SERVER[REQUEST_METHOD]=="GET"){

	if($_GET["o"]=="filter"){
	
		$user_id = $_GET["userid"];
	
		$velement_id = $_GET["velement_id"];
				
		$store = new TAssistant();
	
		$filter = $store->getFilter($user_id, $velement_id);
	
		echo json_encode($filter);
	
		exit;
	}
	
	if(isset($_GET["userid"])){

		$user_id = $_GET["userid"];

		$store = new TAssistant();

		$users = $store->getUserById($user_id);

		echo json_encode($users);

		exit;
	}

	// get all

	$store = new TAssistant();

	$users = $store->getUsers();

	echo json_encode($users);

	exit;
}

if($_SERVER['PATH_INFO']=="/users" && $_SERVER[REQUEST_METHOD]=="PUT"){

	if($_GET["o"]=="pfilter" && $_GET["fn"]=="replace"){

		$user_id = $_GET["userid"];
		
		$velement_id = $_GET["velement_id"];
		
		$pfilter_id = $_GET["pfilter_id"];
		
		$pfilter_str = file_get_contents("php://input");
		
		$pfilter = json_decode($pfilter_str);

		$store = new TAssistant();

		$users = $store->replacePFilter($user_id, $velement_id, $pfilter_id, $pfilter);
		
		echo "ok"; 

		exit;
	}
	else if($_GET["o"]=="config"){
			
		$updates_str = file_get_contents("php://input");
	
		$user = json_decode($updates_str);
	
		$store = new TAssistant();
	
		$users = $store->updateUser($user);
	
		echo "ok";
	
		exit;
	}
	exit;
}

if($_SERVER['PATH_INFO']=="/users" && $_SERVER[REQUEST_METHOD]=="POST"){

	$user = $_POST["user"];

	$user_infos["id"] = $user;

	$ktbs = new KtbsStore();

	$ok = $ktbs->addUser($user_infos);

	echo $ok;

	exit;
}

if($_SERVER['PATH_INFO']=="/images" && $_SERVER[REQUEST_METHOD]=="POST"){

	//echo json_encode($_FILES);
	
	$file = $_FILES["image"];

	$uploader = new TAssistance\common\FileUploader($file);
	
	$$ok = $uploader->saveFile();

	echo $ok;

	exit;
}

require_once $dir."/php/oza-api.php";