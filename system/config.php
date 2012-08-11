<?php

// core
define("VERSION",			"0.31");
define("NAME",				"ImageCrawler");
define("JQUERYVERSION",		"1.8.0");

// default values
define("LINK",				"http://help.github.com/");
define("SELECTOR",			"a img");
define("SELECTORATTRIBUTE",	"src");
define("STARTBUTTON", 		"START DOWNLOAD");
define("STOPBUTTON", 		"STOP");

// paths & files
define("OUTPUTPATH",		"_images/");
define("FOLDERPATH",		"../");
define("IMGPREVIEWSIZE",	1.0);
define("DOCSPATH",			"_doc/");
define("HELPDOC",			"help.html");

// tidy
define("USETIDY", 			true);
define("ALLOWEDTAGS",		"<a><p><div><img><table><td><tr><span><ul><li><ol><b><strong><i><em><u><sup><sub><tt><h1><h2><h3><h4><h5><h6><small><big>");
$tidyConfig = 				array('fix-uri' => FALSE);

// messages
define("NOJS",				"Activate JavaScript to use this application.");
define("UNSUPPORTEDIMGTYPE","unsupported filetype");
define("PHPERROR",			"error in php-script &gt;&gt; ");
define("NOPARAMS",			"no params found: check url");
define("CURLERROR",			"cURL error &gt;&gt; ");

?>