ImageCrawler
============

ImageCrawler is a toolkit for asynchronously pulling images from websites.
It's built with PHP and jQuery.


Features
--------

* Asynchronously saves images from websites via cURL and JavaScript
* Images can be selected with jQuery selectors
* Graphical display of download progress and current pictures
* Optimized for Apache directory listings
* Can  handle `JPG`, `PNG` and `GIF`
* Preserves alpha information of images
* Logs cURL requests in `system/log/`
* Neat config-File: `system/config.php`
* Supports HTTPS, XML


How to use
----------
https://github.com/jaylinski/imagecrawler/wiki


Requirements
------------
* Webserver with at least PHP 5.3
* cURL extension (7.19.4 or higher)
* exif, gd extension
* Tidy extension (recommended)
* openSSL extension (for https support)


Changelog
---------
https://github.com/jaylinski/imagecrawler/wiki/Changelog


Versioning
----------

This software is maintained under the [Semantic Versioning guidelines](http://semver.org/).


Bug tracker
-----------

Have a bug? Please create an issue here on GitHub:

https://github.com/jaylinski/imagecrawler/issues


Copyright and license
---------------------

Copyright &copy; Jakob Linskeseder

imagecrawler is licensed under the MIT License - see the `LICENSE` file for details.