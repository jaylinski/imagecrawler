<?php

/**
 * @author Jakob Linskeseder
 * 
 * DESCRIPTION:
 * Saves images via cURL and JavaScript (jQuery + AJAX).
 * Optimized for Apache directory listings.
 *
 */

require_once('config/config.inc.php');

?>
<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html xmlns="http://www.w3.org/1999/xhtml" xml:lang="en" lang="en">
	<head>
		<meta http-equiv="Content-Type" content="text/html; charset=utf-8" />
		<title>ImageCrawler</title>
		<link rel="stylesheet" type="text/css" href="css/styles.css" media="screen" />
		<link rel="shortcut icon" href="img/favicon.ico" type="image/x-icon" />
		<link rel="icon" href="img/favicon.ico" type="image/x-icon" />
		<script type="text/javascript" src="https://ajax.googleapis.com/ajax/libs/jquery/1.7.2/jquery.min.js"></script>
		<script type="text/javascript" src="js/utils.js"></script>
		<script type="text/javascript" src="js/script.js"></script>
	</head>
	<body>
		<div id="ui">
			<div id="ui-top">
				<div>
					<div class="ui-left">
						<label for="url">URL</label>
						<input type="text" id="url" value="<?php echo LINK; ?>" />
						<label for="selector">SELECTOR</label>
						<input type="text" id="selector" value="<?php echo SELECTOR; ?>" />
						<label for="selector_attribute">ATTRIBUTE</label>
						<input type="text" id="selector_attribute" value="<?php echo SELECTOR_ATTRIBUTE; ?>" />
					</div>
					<div class="ui-right">
						<input type="button" value="START DOWNLOAD" id="start" />
						<input type="button" value="STOP" id="stop" />
					</div>
					<div class="clear"></div>
				</div>
			</div>
			<div id="ui-bottom">
				<div>
					<div class="ui-left">
						<h1>IMAGE DOWNLOADER v<?php echo VERSION; ?></h1>
					</div>
					<div class="ui-right">
						<label for="showconsole">SHOW CONSOLE</label>
						<input type="checkbox" id="showconsole" checked="checked" />
						<label for="showpreview">SHOW PREVIEW</label>
						<input type="checkbox" id="showpreview" checked="checked" />
						<label for="showcontent">SHOW CONTENT</label>
						<input type="checkbox" id="showcontent" />
						&nbsp;
						<input type="button" value="HELP" id="showhelp" />
					</div>
					<div class="clear"></div>
				</div>
			</div>
		</div>
		<div id="status"></div>
		<div id="help">
			<p>
				<?php echo nl2br(file_get_contents(DOCSPATH.HELPDOC)); ?>
			</p>
		</div>
		<pre id="output"></pre>
		<div id="preview"></div>
		<div id="content"></div>
		<noscript id="noscript">
			<p>Activate JavaScript to use this application.</p>
		</noscript>
	</body>
</html>