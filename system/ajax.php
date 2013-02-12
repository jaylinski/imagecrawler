<?php

require_once('config.php');
require_once('utils.php');

set_time_limit(CONNECTTIMEOUT); // for big websites

if(isset($_GET['request']))
{	
	// process request
	$request = $_GET['request'];
	
	if($request == 'checkextensions')
	{		
		// check if extensions are loaded
		$extension_check = check_extensions();
		
		if($extension_check['success'])
		{
			$outputArray = array(
				"success" => 1,
				"notice" => $extension_check['notice'],
				"message" => $extension_check['message'],
			);			
		}
		else {
			$outputArray = array(
				"success" => 0,
				"message" => $extension_check['message']
			);
		}
		output_array_as_json($outputArray);	
	}
	
	if($request == 'saveimage')
	{		
		if(isset($_POST['image']) && isset($_POST['contenturl']))
		{			
			$result = save_image($_POST['image'], $_POST['contenturl']);
			
			if($result['success'])
			{
				$message = build_message($result);
				$image = build_image($result);
				
				$outputArray = array(
					"success" => 1,
					"message" => $message,
					"image" => $image
				);				
			}
			else {
				$outputArray = array(
					"success" => 0,
					"message" => $result['message']
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
	
	if($request == 'getcontents')
	{		
		if(isset($_REQUEST['contenturl']) && $_REQUEST['contenturl'] != "")
		{			
			$contenturl = $_REQUEST['contenturl'];
			
			if(filter_var($contenturl, FILTER_VALIDATE_URL, FILTER_FLAG_PATH_REQUIRED))
			{				
				$result = get_content_from_url($contenturl);
				
				// check if website was loaded
				if($result['success'])
				{
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
						"info" => $result['info'],
						"content" => $output
					);					
				}
				else {
					$outputArray = array(
						"success" => 0,
						"info" => $result['info'],
						"message" => CURLERROR.$result['message'],
						"messagedescription" => $result['messagedescription']
					);
				}
			}
			else {
				$outputArray = array(
					"success" => 0,
					"message" => INVALIDURL
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