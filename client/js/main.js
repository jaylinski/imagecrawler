/*
 * ImageCrawler
 *
 * https://github.com/jaylinski/imagecrawler
 * 
 * Last update: 06.12.2012
 */

$(document).ready(function() {

	disableInput("#showcontent");
	disableInput("#stop");
	
	initEventObjects();
});

function initEventObjects() {
	$("#form_settings").submit(function(event) {
		event.preventDefault();
		startDownload();
	});
	$("#stop").click(function() {
		linkarray = [];
		disableInput($(this));
	});
	$("#showconsole").click(function() {
		$("#output").toggle();
		scrollToBottom(1);
	});
	$("#showcontent").click(function() {
		$("#content").toggle();
	});
	$("#showpreview").click(function() {
		$("#preview").toggle();
	});
	$("#showhelp").click(function() {
		$("#help").toggle();
		scrollToTop();
	});
	$('#ui-top input[type="text"]').focus(function() {
		var tmpWidth = $(this).width();
		$(this).animate({ width: tmpWidth + inputExtensionLength }, {duration: 500});
	});
	$('#ui-top input[type="text"]').blur(function() {
		var tmpWidth = $(this).width();
		$(this).animate({ width: tmpWidth - inputExtensionLength }, {duration: 200});
	});
}

function startDownload() {
	$("#help").hide();
	resetLoadBar();
	resetConsole();
	resetPreview();
	setTitle("loading...");
	writeToConsole(getGreatherThanEntity(15)+" STARTING DOWNLOAD PROCESS",1);
	i = 0;
	contenturl = $("input#url").attr("value");
	
	$.ajax({
		type: "GET",
		url: "system/ajax.php?request=checkextensions",
		beforeSend: function(){
			writeToConsole(getGreatherThanEntity(2)+" loading   "+getGreatherThanEntity(2)+" checking extensions");
			scrollToBottom(1);
		},
		success: function(data){
			if(data.success) {
				if(data.notice) {
					writeToConsole(getGreatherThanEntity(2)+" warning   "+getGreatherThanEntity(2)+" "+data.message, 2);
				} else {
					writeToConsole(getGreatherThanEntity(2)+" success   "+getGreatherThanEntity(2)+" "+data.message, 1);
				}
				
				scrollToBottom(1);
				getContents();
				disableInput("#start");
				setValue("#start","LOADING...");				
			} else {
				writeToConsole(getGreatherThanEntity(15)+" "+data.message,0);
				scrollToBottom(1);
				setLoadBar(0,0,1);
				setPercentLoaded(0,0,1);
			}
		},
		error: function(jqXHR, textStatus, errorThrown){
			writeToConsole(getGreatherThanEntity(2)+" ERROR     "+getGreatherThanEntity(2)+" "+textStatus+" "+getGreatherThanEntity(2)+" "+errorThrown,0);
			if(debugMode) writeToConsole(getGreatherThanEntity(2)+" DEBUG     "+getGreatherThanEntity(2)+" "+jqXHR.responseText,0);
			scrollToBottom(1);
		}
	});
}

function getContents() {
	$.ajax({
		type: "POST",
		url: "system/ajax.php?request=getcontents",
		data: {contenturl: contenturl},
		beforeSend: function(){
			writeToConsole(getGreatherThanEntity(2)+" loading   "+getGreatherThanEntity(2)+" "+contenturl);
			scrollToBottom(1);
			showContentLoader();
		},
		success: function(data){
			if(data.success) {
				// update content url to cURL final url
				contenturl = data.info.url;
				// further stuff
				$("#content_iframe").contents().find("body").html(unescape(data.content));
				writeToConsole(
					getGreatherThanEntity(2)+" loaded    "
					+getGreatherThanEntity(2)+" "
					+"<a href='"+data.info.url+"' target='_blank'>"
					+data.info.url+
					"</a> "
					+getGreatherThanEntity(2)+" "
					+formatBytes(data.info.size), 1
				);
				scrollToBottom(1);
				hideContentLoader();
				enableInput("#showcontent");
				var selector = $("#selector").attr("value");
				var selectorAttribute = $("#selector_attribute").attr("value");
				linkarray = $("#content_iframe").contents().find(selector);
				linkarrayLength = linkarray.length;
				consoleElementText = "element";
				if(linkarrayLength > 1) {
					consoleElementText = "elements";
				}
				writeToConsole(getGreatherThanEntity(2)+" searching "+getGreatherThanEntity(2)+" "+linkarrayLength+" "+consoleElementText+" found");
				iterator(i,selectorAttribute);
				enableInput("#stop");
			} else {
				writeToConsole(getGreatherThanEntity(15)+" "+data.message,0);
				scrollToBottom(1);
				setLoadBar(0,0,1);
				hideContentLoader();
				setPercentLoaded(0,0,1);
				enableInput("#start");
				setValue("#start",startbuttonvalue);
			}
		},
		error: function(jqXHR, textStatus, errorThrown){
			writeToConsole(getGreatherThanEntity(15)+" "+contenturl,0);
			writeToConsole(getGreatherThanEntity(2)+" ERROR     "+getGreatherThanEntity(2)+" "+textStatus+" "+getGreatherThanEntity(2)+" "+errorThrown,0);
			if(debugMode) writeToConsole(getGreatherThanEntity(2)+" DEBUG     "+getGreatherThanEntity(2)+" "+jqXHR.responseText,0);
			scrollToBottom(1);
			hideContentLoader();
		}
	});
}

function iterator(i,selectorAttribute) {
	if(linkarray.length > i) {
		var img = $(linkarray[i]).attr(selectorAttribute);
		if(img != "" && img !== undefined) {
			saveImage(img,selectorAttribute);
		} else {
			var debugHtml = $(linkarray[i]).prop('outerHTML');
			debugHtml = $('<div />').text(debugHtml).html();
			writeToConsole(getGreatherThanEntity(2)+" INFO      "+getGreatherThanEntity(2)+" SKIPPED EMPTY IMG "+getGreatherThanEntity(2)+" "+debugHtml,0);
			i++;
			iterator(i,selectorAttribute);
		}		
		setLoadBar(i,linkarrayLength);
		setPercentLoaded(i,linkarrayLength);
	} else if(linkarray.length == 0) {
		writeToConsole(getGreatherThanEntity(15)+" ERROR: NO DATA AVAILABLE "+getGreatherThanEntity(2)+" CHECK URL AND SELECTOR",0);
		scrollToBottom(1);
		setLoadBar(0,0,1);
		hideContentLoader();
		setPercentLoaded(0,0,1);
		enableInput("#start");
		setValue("#start",startbuttonvalue);
	} else {
		writeToConsole(getGreatherThanEntity(15)+" REQUESTS COMPLETED",1);
		scrollToBottom(1);
		setLoadBar(i,linkarrayLength);
		disableInput("#stop");
		setPercentLoaded(i,linkarrayLength);
		enableInput("#start");
		setValue("#start",startbuttonvalue);
	}
}

function saveImage(img,selectorAttribute) {
	$.ajax({
		type: "POST",
		url: "system/ajax.php?request=saveimage",
		data: {contenturl: contenturl, image: img},
		beforeSend: function(){
			writeToConsole(
				getGreatherThanEntity(2)+" saving    "
				+getGreatherThanEntity(2)+" "
				+img+" "
				+getGreatherThanEntity(2)+" "
				+(i+1)+"/"+linkarrayLength
			);
			scrollToBottom(1);
		},
		success: function(data){
			if(data.success) {
				writeToConsole(data.message,1);
				renderPreview(data);
				scrollToBottom(1);
				i++;
				iterator(i,selectorAttribute);
			} else {
				writeToConsole(getGreatherThanEntity(2)+" error     "+getGreatherThanEntity(2)+" "+data.message+" | skipping image",0);
				scrollToBottom(1);
				i++;
				iterator(i,selectorAttribute);
			}
		},
		error: function(jqXHR, textStatus, errorThrown){
			setLoadBar(0,0,1);
			writeToConsole(getGreatherThanEntity(15)+" "+img,0);
			writeToConsole(getGreatherThanEntity(2)+" ERROR     "+getGreatherThanEntity(2)+" "+textStatus+" "+getGreatherThanEntity(2)+" "+errorThrown,0);
			if(debugMode) writeToConsole(getGreatherThanEntity(2)+" DEBUG     "+getGreatherThanEntity(2)+" "+jqXHR.responseText,0);	
			scrollToBottom(1);
		}
	});
}