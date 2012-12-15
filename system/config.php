<?php

// core
define("VERSION",           "1.0.4");
define("NAME",              "ImageCrawler");
define("JQUERYVERSION",     "1.8.3");
define("CONNECTTIMEOUT",    180);
define("DEBUG",             true);

// default values
define("LINK",              "http://github.com/");
define("SELECTOR",          "img");
define("SELECTORATTRIBUTE", "src");
define("STARTBUTTON",       "START DOWNLOAD");
define("STOPBUTTON",        "STOP");

// paths & files
define("OUTPUTPATH",        "_images/");
define("FOLDERPATH",        "../");
define("LOGPATH",           "system/log");		
define("DOCSPATH",          "_doc/");
define("HELPDOC",           "help.html");

// cURL
define("USERAGENT",         "Mozilla/4.0 (compatible; MSIE 5.01; Windows NT 5.0)"); // some websites require http requests with useragents 
define("SSLVERIFYPEER",     0); // FALSE to stop cURL from verifying the peer's certificate.
define("IGNOREHTTPSTATUS",  0); // TRUE to ignore 'allowedHttpCodes'
$allowedHttpCodes =         array(200); // add http codes to load images from error pages (404,403,...)

// tidy
define("USETIDY",           true);
define("ALLOWEDTAGS",       "<a><p><div><img><table><td><tr><span><ul><li><ol><b><strong><i><em><u><sup><sub><tt><h1><h2><h3><h4><h5><h6><small><big>");
$tidyConfig =               array('fix-uri' => FALSE);
                    
// other
define("INPUTEXTLENGTH",    100);
define("IMGPREVIEWSIZE",    1.0);
define("IMGPREVIEWLENGTH",  3);

// messages
define("NOJS",              "Activate JavaScript to use this application.");
define("UNSUPPORTEDIMGTYPE","unsupported filetype");
define("FILTENOTFOUND",     "image not found");
define("PHPERROR",          "error in php-script ");
define("NOPARAMS",          "no params found: check url");
define("CURLERROR",         "cURL error &gt;&gt; ");
define("CURLHTTPNOTALLOWED","check out 'system/config.php' and enable 'IGNOREHTTPSTATUS' if you want to download images from all pages");
define("INVALIDURL",        "invalid URL. provide full URL with only ASCII characters.");
define("URLCOMPILEFAIL",    "could not compile a valid url");
define("CHANGEDEBUG",       "Change debug settings in 'system/config.php'.");

// extensions
// values: 0 = ignore, 1 = required, 2 = recommended
$extensions = array("Tidy"    => USETIDY,
                    "cURL"    => 1,
                    "openssl" => 2);

?>