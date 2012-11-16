<?php

function save_image($image, $contenturl) {
	
	$imgPathInfo = pathinfo($image);
	$urlPathInfo = parse_url($contenturl);
	
	//print_r($imgPathInfo);
	//print_r($urlPathInfo);
	
	// generate url
	if(filter_var($imgPathInfo['dirname']."/", FILTER_VALIDATE_URL, FILTER_FLAG_PATH_REQUIRED)) {
		$dirname = $imgPathInfo['dirname'];
	} else if(filter_var("http:".$imgPathInfo['dirname']."/", FILTER_VALIDATE_URL, FILTER_FLAG_PATH_REQUIRED)) {
		$dirname = "http:".$imgPathInfo['dirname'];
	} else if(filter_var("https:".$imgPathInfo['dirname']."/", FILTER_VALIDATE_URL, FILTER_FLAG_PATH_REQUIRED)) {
		$dirname = "https:".$imgPathInfo['dirname'];
	} else {
		if($urlPathInfo['path']) {
			$urlPathPathInfo = pathinfo($urlPathInfo['path']);
			if(strpos($urlPathPathInfo['dirname'], '\\') !== FALSE) {
				$dirname = $urlPathInfo['scheme']."://".$urlPathInfo['host']."/".$imgPathInfo['dirname'];
			} else {
				$dirname = $urlPathInfo['scheme']."://".$urlPathInfo['host'].$urlPathPathInfo['dirname']."/".$imgPathInfo['dirname'];
			}			
		} else {
			$dirname = $contenturl.$imgPathInfo['dirname'];
		}
	}
	
	$imgPath = $dirname."/".$imgPathInfo['basename'];
	
	// get image from url
	if(filter_var($imgPath, FILTER_VALIDATE_URL, FILTER_FLAG_PATH_REQUIRED)) {
		
		$size = getimagesize($imgPath);

		$imagetype = exif_imagetype($imgPath);
		
		if($imagetype == IMAGETYPE_GIF){
			$src = imagecreatefromgif($imgPath);
			$imgFileType = ".gif";
		}
		else if($imagetype == IMAGETYPE_JPEG){
			$src = imagecreatefromjpeg($imgPath);
			$imgFileType = ".jpg";
		}
		else if($imagetype == IMAGETYPE_PNG){
			$src = imagecreatefrompng($imgPath);
			$imgFileType = ".png";
		}
		else {
			$return = array(
				"success" => false,
				"message" => UNSUPPORTEDIMGTYPE
			);
			return $return;
		}
		
		$dest = imagecreatetruecolor($size[0], $size[1]);
		imagealphablending($dest, false);
		imagesavealpha($dest,true);		
		imagecopy($dest, $src, 0, 0, 0, 0, $size[0], $size[1]);
		
		// output and free from memory
		make_output_dir();
		$outputPath = FOLDERPATH.OUTPUTPATH.sanitize_file_name($imgPathInfo['filename'].$imgFileType);
		
		if($imagetype == IMAGETYPE_GIF){
			imagegif($dest, $outputPath);
		}
		else if($imagetype == IMAGETYPE_JPEG){
			imagejpeg($dest, $outputPath);
		}
		else if($imagetype == IMAGETYPE_PNG){
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
		
	} else {
		$return = array(
			"success" => false,
			"message" => URLCOMPILEFAIL.": ".$imgPath
		);
	}
	
	return $return;
}

function build_message($resultArray) {
	$message = "&gt;&gt; finished  &gt;&gt; ";
	$message.= "<a href=".OUTPUTPATH.$resultArray['basename']." target=\"blank\">".$resultArray['basename']."</a> &gt;&gt; ".$resultArray['width']."x".$resultArray['height']." | ".$resultArray['filesize'];
	return $message;		
}

function build_image($resultArray) {
	$image = "<img src=\"".$resultArray['imgpath']."\" alt=\"\" width=\"".$resultArray['width']*IMGPREVIEWSIZE."\" height=\"".$resultArray['height']*IMGPREVIEWSIZE."\" />";
	return $image;			
}

function get_content_from_url($url) {
	make_log_dir();
	$curl_log = fopen('log/curl.log', 'w');
	$curl_options = array(
		CURLOPT_URL => $url,
		CURLOPT_HEADER => 0,
		CURLOPT_CONNECTTIMEOUT => CONNECTTIMEOUT,
		CURLOPT_RETURNTRANSFER => true,
		CURLOPT_ENCODING => CURLENCODING,
		CURLOPT_SSL_VERIFYPEER => SSLVERIFYPEER,
		CURLOPT_FOLLOWLOCATION => true,
		CURLOPT_VERBOSE => 1,
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
			"message" => curl_error($curl)
		);
	} else if($output_info["http_code"] == 404 && !IGNOREHTTPSTATUS) {
		$return = array(
			"success" => false,
			"info" => array(
				"url" => $output_info["url"],
				"size" => 0
			),
			"message" => $output_info["http_code"]." site not found",
		);
	} else {
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

function output_array_as_json($outputArray) {
	header('Content-type: application/json');
	echo json_encode($outputArray);
}

function format_bytes($bytes) {
   if ($bytes < 1024) return $bytes.' Bytes';
   else if ($bytes < 1048576) return round($bytes / 1024, 2).' KB';
   else if ($bytes < 1073741824) return round($bytes / 1048576, 2).' MB';
   else if ($bytes < 1099511627776) return round($bytes / 1073741824, 2).' GB';
   else return "insanely big";
}

function make_output_dir() {
	if(!file_exists(FOLDERPATH.OUTPUTPATH)) {
		mkdir(FOLDERPATH.OUTPUTPATH);
	}
}
function make_log_dir() {
	if(!file_exists(FOLDERPATH.LOGPATH)) {
		mkdir(FOLDERPATH.LOGPATH);
	}
}

function sanitize_file_name($str) {
	return preg_replace('/([^[:alnum:]\._-\s]*)/','',$str);
}

function check_extensions() {
	
	global $extensions;
	$outputArray = array("success" => 1, "notice" => 0, "message" => "");
	
	foreach($extensions as $extension => $priority) {
		if (!extension_loaded($extension)) {
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