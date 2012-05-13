<?php

set_time_limit(180); // for big websites

require_once('../config/config.inc.php');
require_once('utils.inc.php');

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
		//$contentstart = $_POST['contentstart'];
		//$contentend = $_POST['contentend'];
		
		if($contenturl != "") {
			
			// get website
			$f = fopen('curl_log.txt', 'w');
			$curl_options = array(
				CURLOPT_URL => $contenturl,
				CURLOPT_HEADER => 0,
                CURLOPT_CONNECTTIMEOUT => 180,
				CURLOPT_RETURNTRANSFER => true,
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
				// take a substring of the loaded content
				/*if(is_int($contentstart) && $contentend == "") {
					$output = substr($output,$contentstart);
				} else if (is_int($contentstart) && is_int($contentend)) {
					$output = substr($output,$contentstart,$contentend);
				}*/
				
				// tidy the html
				$tidy = new tidy;
				$tidy->parseString($output);
				$tidy->cleanRepair();
				$output = $tidy->html();
				$output = $output->value;
				//$output = strip_tags($output,TAGSTOSTRIP);
				
				// return it
				//$outputArray = array(
					//"success" => 1,
					//"content" => $output
				//);
				//output_array_as_json($outputArray);
				echo $output;
			}
		}
		else {
			$outputArray = array("success" => 0);
			output_array_as_json($outputArray);
		}
	}
	
}

?>