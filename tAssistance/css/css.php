<?php

header("Content-type: text/css");

$debug = true;

$files = array(	"OzaTextObsel.css",
				"OzaTraceList.css",
				"OzaObselSearch.css",
				"OzaTraceModel.css",
				"OzaBarChart.css",
				"OzaDashBoard.css",
				"OzaZoom.css",
				"OzaDoc.css",
				"Popup.css",
				"OzaTraceBtnGroup.css",
				
				"../lib/treeview/css/bootstrap-treeview.css",
				"../lib/imgcorrect/imgcorrect.min.css",
				"../lib/slider/css/slider.css",
				"../lib/jsonviewer/jsonviewer.css",
				"../lib/jasny/css/jasny-bootstrap.css",
				//"../lib/datetimepicker/css/bootstrap-datetimepicker.css",
				"../lib/daterangepicker/daterangepicker-bs3.css",
				"../lib/datatables/media/css/jquery.dataTables.css"
		
				);

$css = "";
foreach($files as $f)
  $css .= file_get_contents($f);

if($debug) {
	echo $css;
} else {
	
}