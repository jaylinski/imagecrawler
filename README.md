ImageCrawler
============

AJAX Image Crawler, based on PHP and jQuery.

###Features:

* Saves images via cURL and JavaScript.
* Images can be selected with jQuery selectors.
* Graphical display of download progress and current pictures.
* Optimized for Apache directory listings.
* Can  handle JPG, PNG and GIF
* Preserves alpha information of images
* Logs cURL requests in `system/log/`
* Neat config-File: `system/config.php`
* Supports HTTPS

###Requirements:
* Webserver with at least PHP 5.3
* cURL extension
* Tidy extension (recommended)
* openSSL extension (for https support)
