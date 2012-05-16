<?php

set_time_limit(180); // for big websites

require_once('config.php');
require_once('utils.php');

if(isset($_GET['request'])) {

	$request = $_GET['request'];
	
	if($request == 'saveimage') {
		
		if(isset($_GET['image']) && isset($_POST['contenturl'])) {
			
			$result = save_image($_GET['image'], $_POST['contenturl']);
			
			if($result['success']) {
				$message = build_message($result);
				$image = build_image($result);
				
				$outputArray = array(
					"success" => 1,
					"message" => $message,
					"image" => $image
				);
				
			} else {
				$outputArray = array(
					"success" => 0,
					"message" => PHPERROR.$result['message']
				);
			}
		}
		else {
			$outputArray = array(
				"success" => 0,
				"message" => NOPARAMS
			);
		}
		
		// output
		output_array_as_json($outputArray);
	}
	
	if($request == 'getcontents') {		
		
		if(isset($_REQUEST['contenturl']) && $_REQUEST['contenturl'] != "") {
			
			$contenturl = $_REQUEST['contenturl'];
			$result = get_content_from_url($contenturl);
			
			// check if website was loaded
			if($result['success']) {
				$output = $result['output'];
				
				if(USETIDY) {
					// tidy the html
					$tidy = new tidy;
					$tidy->parseString($output,$tidyConfig,"UTF8");
					$tidy->cleanRepair();
					$output = $tidy->body();
					$output = $output->value;
				}
				
				$output = strip_tags($output,ALLOWEDTAGS);
				
				$outputArray = array(
					"success" => 1,
					"content" => $output
				);
				
			}
			else {
				$outputArray = array(
					"success" => 0,
					"message" => CURLERROR.$result['message']
				);
			}
		}
		else {
			$outputArray = array(
				"success" => 0,
				"message" => NOPARAMS
			);			
		}
		
		// output
		output_array_as_json($outputArray);
	}
	
}

?>