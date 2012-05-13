<?php

set_time_limit(180); // for big websites

require_once('config.php');
require_once('utils.php');

if(isset($_GET['request'])) {

	if($_GET['request'] == 'saveimage') {
		$contenturl = $_POST['contenturl'];
		$result = save_image($_GET['image'], $contenturl);
		
		$message = "<span class=\"success\">";
		$message.= date('H:i:s')." &gt;&gt; finished  &gt;&gt; ";
		$message.= "<a href=".OUTPUTPATH.$result['basename']." target=\"blank\">".$result['basename']."</a> &gt;&gt; ".$result['width']."x".$result['height']." | ".$result['filesize'];
		$message.= "</span><br />";
		$image = "<img src=\"".$contenturl.$_GET['image']."\" alt=\"\" width=\"".$result['width']*IMGPREVIEWSIZE."\" height=\"".$result['height']*IMGPREVIEWSIZE."\" />";
		$outputArray = array(
			"message" => $message,
			"image" => $image
		);
		header('Content-type: application/json');
		echo json_encode($outputArray);
	}
	
	if($_GET['request'] == 'getcontents') {
	
		$contenturl = $_REQUEST['contenturl'];
		
		if($contenturl != "") {
			
			// get website
			$f = fopen('log/curl_log.txt', 'w');
			$curl_options = array(
				CURLOPT_URL => $contenturl,
				CURLOPT_HEADER => 0,
                CURLOPT_CONNECTTIMEOUT => 180,
				CURLOPT_RETURNTRANSFER => true,
				CURLOPT_ENCODING => 'UTF-8',				
				CURLOPT_HTTP_VERSION => CURL_HTTP_VERSION_1_0,
				CURLOPT_VERBOSE => 1,
				CURLOPT_STDERR => $f
			);
			$curl = curl_init();
			curl_setopt_array($curl, $curl_options);
			if(curl_exec($curl) === false) {
				$output = "";
			} else {
				$output = curl_exec($curl);
			}
			
			// check if website was loaded
			if (empty($output)) {
				$outputArray = array(
					"success" => 0,
					"content" => $output
				);
				output_array_as_json($outputArray);
			}
			else {
				if(USETIDY) {
					// tidy the html
					$tidy = new tidy;
					$tidy->parseString($output,$tidyConfig,"UTF8");
					$tidy->cleanRepair();
					$output = $tidy->body();
					$output = $output->value;
				}				
				$output = strip_tags($output,ALLOWEDTAGS);
				
				// return it
				$outputArray = array(
					"success" => 1,
					"content" => $output
				);
				output_array_as_json($outputArray);
			}
		}
		else {
			$outputArray = array("success" => 0);
			output_array_as_json($outputArray);
		}
	}
	
}

?>