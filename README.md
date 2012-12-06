ImageCrawler
============

AJAX Image Crawler, based on PHP and jQuery.


Features
--------

* Saves images via cURL and JavaScript.
* Images can be selected with jQuery selectors.
* Graphical display of download progress and current pictures.
* Optimized for Apache directory listings.
* Can  handle `JPG`, `PNG` and `GIF`
* Preserves alpha information of images
* Logs cURL requests in `system/log/`
* Neat config-File: `system/config.php`
* Supports HTTPS


Requirements
------------
* Webserver with at least PHP 5.3
* cURL extension
* Tidy extension (recommended)
* openSSL extension (for https support)

FAQ / Wiki / Changelog
----
https://github.com/jaylinski/imagecrawler/wiki


Versioning
----------

Releases will be numbered with the following format:

`<major>.<minor>.<patch>`

And constructed with the following guidelines:

* Breaking backward compatibility bumps the major (and resets the minor and patch)
* New additions without breaking backward compatibility bumps the minor (and resets the patch)
* Bug fixes and misc changes bumps the patch

Changelogs can be found here:

https://github.com/jaylinski/imagecrawler/wiki


Bug tracker
-----------

Have a bug? Please create an issue here on GitHub:

https://github.com/jaylinski/imagecrawler/issues


Copyright and license
---------------------

This work is licensed under a Creative Commons Attribution-ShareAlike 3.0 Unported License:

http://creativecommons.org/licenses/by-sa/3.0/
