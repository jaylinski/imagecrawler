<?php

// core
define("VERSION",			"0.3");
define("NAME",				"ImageCrawler");
define("JQUERYVERSION",		"1.7.2");

// default values
define("LINK",				"http://help.github.com/");
define("SELECTOR",			"a img");
define("SELECTORATTRIBUTE",	"src");

// paths & files
define("OUTPUTPATH",		"_images/");
define("FOLDERPATH",		"../");
define("IMGPREVIEWSIZE",	1.0);
define("DOCSPATH",			"_doc/");
define("HELPDOC",			"help.html");

// tidy
define("USETIDY", 			true);
define("ALLOWEDTAGS",		"<a><p><div><img><table><td><tr><span><ul><li><ol><b><strong>");
$tidyConfig = 				array('fix-uri' => FALSE);

// messages
define("NOJS",				"Activate JavaScript to use this application.");

?>