<?php
error_reporting(0);

header('Access-Control-Allow-Origin: *');
header('Access-Control-Allow-Methods: GET, POST');

$ozalid_tstore = dirname(__FILE__);
$ozalid_dir = dirname(dirname(__FILE__));
$tconnect_dir = dirname(dirname($ozalid_dir));
$ktbsStore_dir = $tconnect_dir."/ktbs-store";
$ozalid_tstore_php = $ozalid_tstore."/php";

require_once $ozalid_tstore_php."/OzaTStore.php";
require_once $ozalid_tstore_php."/OzaQuery.php";

//$ktbs_uri = "https://dsi-liris-silex.univ-lyon1.fr/ozalid/ktbs/";

if($_SERVER['PATH_INFO']=="/users" && $_SERVER['REQUEST_METHOD']=="GET"){

	if(isset($_GET["userid"])){
		
		$user_id = $_GET["userid"];
	
		$store = new OzaTStore();

		$users = $store->getUserById($user_id);
	
		echo json_encode($users);
	
		exit;
	}
	
	// get all

	$store = new OzaTStore();

	$users = $store->getUsers();

	echo json_encode($users);
	
	exit;
}

if($_SERVER['PATH_INFO']=="/users" && $_SERVER['REQUEST_METHOD']=="POST"){
	
	$user = $_POST["user"];
	
	$user_infos["id"] = $user;
	
	$ktbs = new KtbsStore();
	
	$ok = $ktbs->addUser($user_infos);

	echo $ok;

	exit;
}

if($_SERVER['PATH_INFO']=="/traces" && $_SERVER['REQUEST_METHOD']=="GET"){

	if(isset($_GET["userid"])){
		
		$user_id = $_GET["userid"];
	
		$ktbs = new KtbsStore();
	
		$traces = $ktbs->getTracesByUserId($user_id);
	
		echo json_encode($traces);
	
		exit;
	}
	
	if(isset($_GET["traceid"])){
	
		$trace_id = $_GET["traceid"];
	
		$store = new OzaTStore();
	
		$trace = $store->getCompleteTraceById($trace_id);
	
		echo json_encode($trace);
	
		exit;
	}
	
	if(isset($_GET["docids"])&&isset($_GET["userids"])){
	
		$docids = $_GET["docids"] == "1" ? true : explode(",", $_GET["docids"]);
		$userids = $_GET["userids"] == "1" ? true : explode(",", $_GET["userids"]);
	
		$store = new OzaTStore();
	
		$trace = $store->getFusedTraceByIds($userids,$docids);
	
		echo json_encode($trace);
	
		exit;
	}
	
	// get all

	$store = new OzaTStore();

	$traces = $store->getTraces();

	echo json_encode($traces);
	
	exit;
}

if($_SERVER['PATH_INFO']=="/traces" && $_SERVER['REQUEST_METHOD']=="POST"){

	$user = $_POST["user"];

	// get all
	$user_id = $_GET["userid"];

	$ktbs = new KtbsStore();

	$traces = $ktbs->getTraces();

	echo json_encode($traces);

	exit;
}

if($_SERVER['PATH_INFO']=="/docs" && $_SERVER['REQUEST_METHOD']=="GET"){

	if(isset($_GET["docid"])){

		$doc_id = $_GET["docid"];

		$store = new OzaTStore();

		$docs = $store->getDocById($doc_id);

		echo json_encode($docs);

		exit;
	}

	// get all

	$store = new OzaTStore();

	$docs = $store->getDocs();

	echo json_encode($docs);

	exit;
}

if($_SERVER['PATH_INFO']=="/words" && $_SERVER['REQUEST_METHOD']=="GET"){

	if(isset($_GET["docid"])){

		$doc_id = $_GET["docid"];

		$query = new OzaQuery();

		$words = $query->getWordsByDocId("t_all",$doc_id);

		echo json_encode($words);

		exit;
	}
	
	if(isset($_GET["docids"])){
	
		$doc_ids = explode(",",$_GET["docids"]);
	
		$query = new OzaQuery();
	
		$words = $query->getWordsByDocIds("t_all",$doc_ids);
	
		echo json_encode($words);
	
		exit;
	}
	

	// get all

	$query= new OzaQuery();

	$words = $query->getWords();

	echo json_encode($words);

	exit;
}

if($_SERVER['PATH_INFO']=="/query" && $_SERVER['REQUEST_METHOD']=="GET"){

	if(isset($_GET["docids"])&&isset($_GET["wordids"])){

		$doc_ids = explode(",",$_GET["docids"]);
		
		$word_ids = explode(",",$_GET["wordids"]);

		$query = new OzaQuery();

		$obsels = $query->getObselsByDocWordIds("t_all",$doc_ids, $word_ids);

		echo json_encode($obsels);

		exit;
	}


	// get all

	$query= new OzaQuery();

	$words = $query->getWords();

	echo json_encode($words);

	exit;
}