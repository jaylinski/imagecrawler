<?php

function save_image($image, $imagepath, $contenturl)
{
	// build image url
	$imgPathInfo = pathinfo($image);
	if(empty($imagepath)) {
		$imgPath = build_url($imgPathInfo, $contenturl);
	} else {
		$imgPath = $imagepath.$imgPathInfo['basename'];
	}	
	
	// get image from url
	if(filter_var($imgPath, FILTER_VALIDATE_URL, FILTER_FLAG_PATH_REQUIRED))
	{		
		$size = @getimagesize($imgPath);
		
		// check if image exists
		if(!$size) {
			$return = array(
				"success" => false,
				"message" => FILTENOTFOUND." | ".$imgPath
			);
			return $return;
		}

		$imagetype = exif_imagetype($imgPath);
		
		if($imagetype == IMAGETYPE_GIF) {
			$src = imagecreatefromgif($imgPath);
			$imgFileType = ".gif";
		}
		else if($imagetype == IMAGETYPE_JPEG) {
			$src = imagecreatefromjpeg($imgPath);
			$imgFileType = ".jpg";
		}
		else if($imagetype == IMAGETYPE_PNG) {
			$src = imagecreatefrompng($imgPath);
			$imgFileType = ".png";
		}
		else {
			$return = array(
				"success" => false,
				"message" => UNSUPPORTEDIMGTYPE." | ".image_type_to_mime_type($imagetype)
			);
			return $return;
		}
		
		$dest = imagecreatetruecolor($size[0], $size[1]);
		imagealphablending($dest, false);
		imagesavealpha($dest,true);		
		imagecopy($dest, $src, 0, 0, 0, 0, $size[0], $size[1]);
		
		// output and free from memory
		make_dir(FOLDERPATH.OUTPUTPATH);
		$outputPath = FOLDERPATH.OUTPUTPATH.sanitize_file_name($imgPathInfo['filename'].$imgFileType);
		
		if($imagetype == IMAGETYPE_GIF) {
			imagegif($dest, $outputPath);
		}
		else if($imagetype == IMAGETYPE_JPEG) {
			imagejpeg($dest, $outputPath);
		}
		else if($imagetype == IMAGETYPE_PNG) {
			imagepng($dest, $outputPath);
		}
	
		imagedestroy($dest);
		imagedestroy($src);
		
		$filesize = format_bytes(filesize($outputPath));
		
		$return = array(
			"success" => true,
			"message" => "",
			"filesize" => $filesize,
			"width" => $size[0],
			"height" => $size[1],
			"basename" => sanitize_file_name($imgPathInfo['filename'].$imgFileType),
			"imgpath" => $imgPath
		);		
	}
	else {
		$return = array(
			"success" => false,
			"message" => URLCOMPILEFAIL.": ".$imgPath
		);
	}
	
	return $return;
}

function build_url($imgPathInfo, $contenturl)
{
	$urlPathInfo = parse_url($contenturl);
	
	// handle image with url
	if(filter_var($imgPathInfo['dirname']."/", FILTER_VALIDATE_URL, FILTER_FLAG_PATH_REQUIRED)) {
		$dirname = $imgPathInfo['dirname'];
	}
	else if(filter_var("http:".$imgPathInfo['dirname']."/", FILTER_VALIDATE_URL, FILTER_FLAG_PATH_REQUIRED)) {
		$dirname = "http:".$imgPathInfo['dirname'];
	}
	else if(filter_var("https:".$imgPathInfo['dirname']."/", FILTER_VALIDATE_URL, FILTER_FLAG_PATH_REQUIRED)) {
		$dirname = "https:".$imgPathInfo['dirname'];
	}
	// handle image with absolute/relative paths
	else {
		// handle image with absolute paths
		if(strpos($imgPathInfo['dirname'], '/') === 0) {
			$dirname = $urlPathInfo['scheme']."://".$urlPathInfo['host'].$imgPathInfo['dirname'];
		}
		// handle image with relative paths
		else {
			// handle contenturl with path
			if($urlPathInfo['path'] && strcmp($urlPathInfo['path'],"/") != 0) {
				$urlPathPathInfo = pathinfo($urlPathInfo['path']);
				// handle contenturl with no dirname
				if(strpos($urlPathPathInfo['dirname'], '\\') !== false) {
					// handle contenturl with no dirname and a path
					if(strcmp(substr($urlPathInfo['path'], -1),"/") == 0) {
						$dirname = $urlPathInfo['scheme']."://".$urlPathInfo['host'].$urlPathInfo['path'].$imgPathInfo['dirname'];	
					}
					// handle contenturl with no dirname and no path
					else {
						$dirname = $urlPathInfo['scheme']."://".$urlPathInfo['host']."/".$imgPathInfo['dirname'];	
					}
				// handle contenturl with dirname
				} else {
					$dirname = $urlPathInfo['scheme']."://".$urlPathInfo['host'].$urlPathPathInfo['dirname']."/".$imgPathInfo['dirname'];			
				}
			// handle contenturl without path
			} else {
				$dirname = $contenturl.$imgPathInfo['dirname'];
			}
		}
	}
	
	return $dirname."/".$imgPathInfo['basename'];
}

function build_message($resultArray)
{
	$message = "&gt;&gt; finished  &gt;&gt; ";
	$message.= "<a href=".OUTPUTPATH.$resultArray['basename']." target=\"blank\">".$resultArray['basename']."</a> &gt;&gt; ".$resultArray['width']."x".$resultArray['height']." | ".$resultArray['filesize'];
	return $message;		
}
function build_image($resultArray)
{
	$image = "<img src=\"".$resultArray['imgpath']."\" alt=\"\" width=\"".$resultArray['width']*IMGPREVIEWSIZE."\" height=\"".$resultArray['height']*IMGPREVIEWSIZE."\" />";
	return $image;			
}

function get_content_from_url($url)
{
	global $allowedHttpStatusCodes;
	
	make_dir(FOLDERPATH.LOGPATH);
	$curl_log = fopen('log/curl.log', 'w');
	$curl_options = array(
		CURLOPT_URL => $url,
		CURLOPT_HEADER => false,
		CURLOPT_CONNECTTIMEOUT => CONNECTTIMEOUT,
		CURLOPT_RETURNTRANSFER => true,
		CURLOPT_SSL_VERIFYPEER => SSLVERIFYPEER,
		CURLOPT_FOLLOWLOCATION => true,
		CURLOPT_REDIR_PROTOCOLS => REDIRPROTOCOLS,
		CURLOPT_USERAGENT => USERAGENT,
		CURLOPT_VERBOSE => true,
		CURLOPT_STDERR => $curl_log
	);
	$curl = curl_init();
	curl_setopt_array($curl, $curl_options);
	$output = curl_exec($curl);
	$output_info = curl_getinfo($curl);
	
	if($output === false) {
		$return = array(
			"success" => false,
			"info" => array(
				"url" => 0,
				"size" => 0
			),
			"message" => curl_error($curl),
			"messagedescription" => false
		);
	}
	else if(!in_array($output_info["http_code"], $allowedHttpStatusCodes, true) && !IGNOREHTTPSTATUS) {
		$return = array(
			"success" => false,
			"info" => array(
				"url" => $output_info["url"],
				"size" => 0
			),
			"message" => "HTTP Status Code <a href=\"http://en.wikipedia.org/wiki/List_of_HTTP_status_codes\" target=\"_blank\">".$output_info["http_code"]."</a>",
			"messagedescription" => CURLHTTPNOTALLOWED
		);
	}
	else {
		$return = array(
			"success" => true,
			"info" => array(
				"url" => $output_info["url"],
				"size" => $output_info["size_download"]
			),
			"output" => $output
		);
	}
	return $return;
}

function output_array_as_json($outputArray)
{
	header('Content-type: application/json');
	echo json_encode($outputArray);
}

function format_bytes($bytes)
{
	if ($bytes < 1024) return $bytes.' Bytes';
	else if ($bytes < 1048576) return round($bytes / 1024, 2).' KB';
	else if ($bytes < 1073741824) return round($bytes / 1048576, 2).' MB';
	else if ($bytes < 1099511627776) return round($bytes / 1073741824, 2).' GB';
	else return "insanely big";
}

function make_dir($dirpath)
{
	if(!file_exists($dirpath)) {
		mkdir($dirpath);
	}
}

function sanitize_file_name($str)
{
	return preg_replace('/([^[:alnum:]\._-\s]*)/','',$str);
}

function check_extensions()
{
	global $extensions;
	$outputArray = array("success" => 1, "notice" => 0, "message" => "");
	
	foreach($extensions as $extension => $priority)
	{
		if (!extension_loaded($extension))
		{
			if($priority == 1) {
				$outputArray['success'] = 0;
				$outputArray['message'] .= "ERROR: ".$extension." extension not loaded. ";
			}
			else if($priority == 2) {
				$outputArray['notice']  = 1;
				$outputArray['message'] .= "NOTICE: ".$extension." extension should be activated. ";
			}
		}
	}
	if(empty($outputArray["message"])) {
		$outputArray["message"] = "all extensions loaded";
	}
	
	return $outputArray;
}

?>