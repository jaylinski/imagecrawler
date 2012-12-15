ImageCrawler
============

ImageCrawler is a toolkit for asynchronously pulling images from websites.
It's built with PHP and jQuery.


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


How to use
----------
https://github.com/jaylinski/imagecrawler/wiki


Requirements
------------
* Webserver with at least PHP 5.3
* cURL extension
* Tidy extension (recommended)
* openSSL extension (for https support)


Changelog
---------
https://github.com/jaylinski/imagecrawler/wiki/Changelog


Versioning
----------

Releases will be numbered with the following format:

`<major>.<minor>.<patch>`

And constructed with the following guidelines:

* Breaking backward compatibility bumps the major (and resets the minor and patch)
* New additions without breaking backward compatibility bumps the minor (and resets the patch)
* Bug fixes and misc changes bumps the patch


Bug tracker
-----------

Have a bug? Please create an issue here on GitHub:

https://github.com/jaylinski/imagecrawler/issues


Copyright and license
---------------------

Copyright (c) 2012 Jakob Linskeseder

Permission is hereby granted, free of charge, to any person obtaining a copy of this software and associated documentation files (the "Software"), to deal in the Software without restriction, including without limitation the rights to use, copy, modify, merge, publish, distribute, sublicense, and/or sell copies of the Software, and to permit persons to whom the Software is furnished to do so, subject to the following conditions:

You may not use this work for commercial purposes. The above copyright notice and this permission notice shall be included in all copies or substantial portions of the Software. If you alter, transform, or build upon this work, you may distribute the resulting work only under the MIT License (MIT).

THE SOFTWARE IS PROVIDED "AS IS", WITHOUT WARRANTY OF ANY KIND, EXPRESS OR IMPLIED, INCLUDING BUT NOT LIMITED TO THE WARRANTIES OF MERCHANTABILITY, FITNESS FOR A PARTICULAR PURPOSE AND NONINFRINGEMENT. IN NO EVENT SHALL THE AUTHORS OR COPYRIGHT HOLDERS BE LIABLE FOR ANY CLAIM, DAMAGES OR OTHER LIABILITY, WHETHER IN AN ACTION OF CONTRACT, TORT OR OTHERWISE, ARISING FROM, OUT OF OR IN CONNECTION WITH THE SOFTWARE OR THE USE OR OTHER DEALINGS IN THE SOFTWARE.
