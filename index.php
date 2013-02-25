<?php

/**
 * ImageCrawler
 *
 * @author  Jakob Linskeseder
 * @version 1.0.5
 * @see     https://github.com/jaylinski/imagecrawler
 * @license https://github.com/jaylinski/imagecrawler#copyright-and-license
 */

require_once('system/config.php');

?>
<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html xmlns="http://www.w3.org/1999/xhtml" xml:lang="en" lang="en">
	<head>
		<meta http-equiv="Content-Type" content="text/html; charset=utf-8" />
		<title><?php echo NAME; ?></title>
		<link rel="stylesheet" type="text/css" href="client/css/styles.css" media="screen" />
		<link rel="shortcut icon" href="client/img/favicon.ico" type="image/x-icon" />
		<link rel="icon" href="client/img/favicon.ico" type="image/x-icon" />
		<script type="text/javascript" src="https://ajax.googleapis.com/ajax/libs/jquery/<?php echo JQUERYVERSION; ?>/jquery.min.js"></script>
		<script type="text/javascript">
			var projectName = "<?php echo NAME; ?>";
			var debugMode = <?php echo DEBUG; ?>;
			var startbuttonvalue = "<?php echo STARTBUTTON; ?>";
			var stopbuttonvalue = "<?php echo STOPBUTTON; ?>";
			var inputExtensionLength = <?php echo INPUTEXTLENGTH; ?>;
			var previewLength = <?php echo IMGPREVIEWLENGTH; ?>;
			var contenturl;
		</script>
		<script type="text/javascript" src="client/js/utils.js"></script>
		<script type="text/javascript" src="client/js/main.js"></script>
	</head>
	<body>
		<div id="ui">
			<div id="ui-top">
				<div>
					<form action="#" id="form_settings">
						<div class="ui-left">
							<label for="url">URL</label>
							<input type="text" id="url" value="<?php echo LINK; ?>" />
							<label for="selector">SELECTOR</label>
							<input type="text" id="selector" value="<?php echo SELECTOR; ?>" />
							<label for="selector_attribute">ATTRIBUTE</label>
							<input type="text" id="selector_attribute" value="<?php echo SELECTORATTRIBUTE; ?>" />
						</div>
						<div class="ui-right">
							<input type="submit" value="<?php echo STARTBUTTON; ?>" id="start" />
							<input type="button" value="<?php echo STOPBUTTON; ?>" id="stop" />
						</div>
						<div class="clear"></div>
					</form>
				</div>
			</div>
			<div id="ui-bottom">
				<div>
					<div class="ui-left">
						<h1>
							<?php echo NAME; ?> <?php echo VERSION; ?>
							<?php if(DEBUG) { ?>
								&nbsp;&nbsp;&nbsp;
								<abbr title="<?php echo CHANGEDEBUG; ?>"><span class="debug">DEBUG</span></abbr>
							<?php } ?>
						</h1>
					</div>
					<div class="ui-right">
						<label for="showconsole">SHOW CONSOLE</label>
						<input type="checkbox" id="showconsole" checked="checked" />
						<label for="showpreview" title="REQUIRES A LOT OF COMPUTING POWER!">SHOW PREVIEW</label>
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
		<noscript id="noscript">
			<p><?php echo NOJS; ?></p>
		</noscript>
		<div id="help">
			<p><?php echo nl2br(file_get_contents(DOCSPATH.HELPDOC)); ?></p>
		</div>
		<pre id="output"></pre>
		<div id="preview"></div>
		<div id="content">
			<iframe src="client/iframe.html" id="content_iframe"></iframe>
		</div>
	</body>
</html>
