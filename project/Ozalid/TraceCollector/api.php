<?php
/*
	 
 	This class is used to read and process the obsels in the trace repository of Urbilog

*/

$ozalid_dir = dirname(dirname(__FILE__));
$collector_dir = dirname(__FILE__);
$correct_dir = $ozalid_dir."/CorrectionServer";
require_once $correct_dir."/OzaTraceRepoClient.php";
require_once $collector_dir."/php/OzaTCollector.php";
require_once $collector_dir."/php/Console.php";


//$server_uri = "http://rd.urbiloglabs.fr/oz_trace_repository/ws/";
//$server_uri = "https://ozalid.orange-labs.fr/oztraces/oz_trace_repository/ws/";
$number_of_packages = 100;
$number_of_obsels = 100;
$model_uri = "http://liris.cnrs.fr/silex/2011/simple-trace-model/";
//$base_uri = "http://localhost:8001/base1/";
$ktbs_uri = "https://dsi-liris-silex.univ-lyon1.fr/ozalid/ktbs/";

$console = new Console("$collector_dir/data/update.log");

// api for updating the obsels in the Urbilog server

if($_SERVER['PATH_INFO']=="/update" && $_SERVER[REQUEST_METHOD]=="GET"){
		
	$iObsel = 0;
	
	while($iObsel<$number_of_obsels){
		
		// get the obsels in the trace repository
		$oza_trace_repo = new OzaTraceRepoClient();
		$obselset = $oza_trace_repo->getObselSet($number_of_packages);
		
		$request_id = $obselset->model->id;
		
		$console->log("the package ".$request_id." is received ");
		
		// convert the obsels into the ktbs obsel form
		
		$collector = new OzaTCollector();
		
		$all_ok = true;
		
		foreach($obselset->model->obsels as $obsel){
			$iObsel++;
			$ok = $collector->receiveObsel($obsel);
			if($ok==false){
				$all_ok=false;
			}
		}
		
		if($all_ok){
			$ok = $oza_trace_repo->deleteObselSet($request_id);
			if($ok){
				$console->log($request_id." is deleted");
			}
			else{
				$console->log("The deletion of ".$request_id." is failed");
			}
		}
		
		echo "1";
		
		sleep(10);
		
	}
	exit;
}

// should produce the error obsels and the success obsels in the trace service
// it should save the obsels of the package 


// TODO