<?php

function save_image($image, $contenturl) {
	$pathinfos = pathinfo($image);
	$basename = $pathinfos['basename'];
	$filename = $pathinfos['filename'];
	$extension = $pathinfos['extension'];
	$size = getimagesize($contenturl.$image);
	
	$imagetype = exif_imagetype($contenturl.$image);
	if($imagetype == IMAGETYPE_GIF){
		$src = imagecreatefromgif($contenturl.$image);
	}
	else if($imagetype == IMAGETYPE_JPEG){
		$src = imagecreatefromjpeg($contenturl.$image);
	}
	else if($imagetype == IMAGETYPE_PNG){
		$src = imagecreatefrompng($contenturl.$image);
	}
	else {
		$return = array(
			"success" => false,
			"message" => UNSUPPORTEDIMGTYPE
		);
		return $return;
	}

	$dest = imagecreatetruecolor($size[0], $size[1]);
	imagecopy($dest, $src, 0, 0, 0, 0, $size[0], $size[1]);
	
	// output and free from memory
	make_output_dir();
	
	if($imagetype == IMAGETYPE_GIF){
		imagegif($dest, FOLDERPATH.OUTPUTPATH.$basename);
	}
	else if($imagetype == IMAGETYPE_JPEG){
		imagejpeg($dest, FOLDERPATH.OUTPUTPATH.$basename);
	}
	else if($imagetype == IMAGETYPE_PNG){
		imagepng($dest, FOLDERPATH.OUTPUTPATH.$basename);
	}

	imagedestroy($dest);
	imagedestroy($src);
	
	$filesize = format_bytes(filesize(FOLDERPATH.OUTPUTPATH.$basename));
	
	$return = array(
		"success" => true,
		"message" => "",
		"filesize" => $filesize,
		"width" => $size[0],
		"height" => $size[1],
		"basename" => $basename
	);
	
	return $return;
}

function build_message($resultArray) {
	$message = "&gt;&gt; finished  &gt;&gt; ";
	$message.= "<a href=".OUTPUTPATH.$resultArray['basename']." target=\"blank\">".$resultArray['basename']."</a> &gt;&gt; ".$resultArray['width']."x".$resultArray['height']." | ".$resultArray['filesize'];
	return $message;		
}

function build_image($resultArray) {
	$image = "<img src=\"".$_POST['contenturl'].$_GET['image']."\" alt=\"\" width=\"".$resultArray['width']*IMGPREVIEWSIZE."\" height=\"".$resultArray['height']*IMGPREVIEWSIZE."\" />";
	return $image;			
}

function get_content_from_url($url) {
	$f = fopen('log/curl_log.txt', 'w');
	$curl_options = array(
		CURLOPT_URL => $url,
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
	$output = curl_exec($curl);
	
	if($output === false) {
		$return = array(
			"success" => false,
			"message" => curl_error($curl)
		);
	} else {
		$return = array(
			"success" => true,
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

?>