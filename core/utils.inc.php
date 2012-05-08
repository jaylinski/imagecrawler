<?php

function save_image($image, $contenturl) {
	$pathinfos = pathinfo($image);
	$basename = $pathinfos['basename'];
	$filename = $pathinfos['filename'];
	$extension = $pathinfos['extension'];
	$size = getimagesize($contenturl.$image);
	
	$src = imagecreatefromjpeg($contenturl.$image);
	$dest = imagecreatetruecolor($size[0], $size[1]);
	imagecopy($dest, $src, 0, 0, 0, 0, $size[0], $size[1]);
	
	// Output and free from memory
	imagejpeg($dest, FOLDERPATH.OUTPUTPATH.$basename);

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

?>