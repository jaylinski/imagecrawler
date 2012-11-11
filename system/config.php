<?php

// core
define("VERSION",           "0.51");
define("NAME",              "ImageCrawler");
define("JQUERYVERSION",     "1.8.2");
define("CONNECTTIMEOUT",    180);
define("DEBUG",             true);

// default values
define("LINK",              "http://github.com/");
define("SELECTOR",          "a img");
define("SELECTORATTRIBUTE", "src");
define("STARTBUTTON",       "START DOWNLOAD");
define("STOPBUTTON",        "STOP");

// paths & files
define("OUTPUTPATH",        "_images/");
define("FOLDERPATH",        "../");
define("LOGPATH",           "system/log");		
define("IMGPREVIEWSIZE",    1.0);
define("DOCSPATH",          "_doc/");
define("HELPDOC",           "help.html");

// cURL
define("CURLENCODING",      "UTF-8");
define("SSLVERIFYPEER",     0); // FALSE to stop cURL from verifying the peer's certificate.
define("IGNOREHTTPSTATUS",  0); // TRUE to load images from http-error pages (404)

// tidy
define("USETIDY",           true);
define("ALLOWEDTAGS",       "<a><p><div><img><table><td><tr><span><ul><li><ol><b><strong><i><em><u><sup><sub><tt><h1><h2><h3><h4><h5><h6><small><big>");
$tidyConfig =               array('fix-uri' => FALSE);

// messages
define("NOJS",              "Activate JavaScript to use this application.");
define("UNSUPPORTEDIMGTYPE","unsupported filetype");
define("PHPERROR",          "error in php-script &gt;&gt; ");
define("NOPARAMS",          "no params found: check url");
define("CURLERROR",         "cURL error &gt;&gt; ");
define("INVALIDURL",        "invalid URL. provide full URL with only ASCII characters.");
define("URLCOMPILEFAIL",    "could not compile a valid url");

// extensions
// values: 0 = ignore, 1 = required, 2 = recommended
$extensions = array("Tidy"    => USETIDY,
                    "cURL"    => 1,
                    "openssl" => 2);
                    
// other
define("INPUTEXTLENGTH",    100);

?>