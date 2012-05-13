$(document).ready(function() {

	disableInput("#showcontent");
	disableInput("#stop");
	
	initEventObjects();
});

function initEventObjects() {
	$("#start").click(function() {
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
}

function startDownload() {
	resetLoadBar();
	setTitle("loading...");
	$("#help").hide();
	resetConsole();
	writeToConsole(getGreatherThanEntity(15)+" STARTING DOWNLOAD PROCESS",1);
	i = 0;
	contenturl = $("input#url").attr("value");
	getContents(contenturl);
	disableInput("#start");
	setValue("#start","LOADING...");
}

function getContents(contenturl) {
	//$("#content").html("<iframe src='system/ajax.php?request=getcontents&contenturl="+contenturl+"'></iframe>");
	$.ajax({
		type: 'POST',
		url: "system/ajax.php?request=getcontents",
		data: "contenturl="+contenturl,
		beforeSend: function(){
			writeToConsole(getGreatherThanEntity(2)+" loading   "+getGreatherThanEntity(2)+" "+contenturl);
			scrollToBottom(1);
			showContentLoader();
		},
		success: function(data){
			if(data.success) {
				$("#content_iframe").contents().find("body").html(unescape(data.content));
				writeToConsole(
					getGreatherThanEntity(2)+" loaded    "
					+getGreatherThanEntity(2)+" "
					+contenturl+" "
					+getGreatherThanEntity(2)+" "
					+formatBytes(data.content.length), 1
				);
				scrollToBottom(1);
				hideContentLoader();
				enableInput("#showcontent");
				var selector = $("#selector").attr("value");
				var selectorAttribute = $("#selector_attribute").attr("value");
				linkarray = $("#content_iframe").contents().find(selector);
				linkarrayLength = linkarray.length;
				writeToConsole(getGreatherThanEntity(2)+" searching "+getGreatherThanEntity(2)+" "+linkarrayLength+" elements found");
				iterator(i,selectorAttribute);
				enableInput("#stop");
			} else {
				writeToConsole(getGreatherThanEntity(15)+" ERROR: THE PHP SCRIPT FAILED "+getGreatherThanEntity(2)+" CHECK URL",0);
				scrollToBottom(1);
				setLoadBar(0,0,1);
				hideContentLoader();
				setPercentLoaded(0,0,1);
				enableInput("#start");
				setValue("#start","START DOWNLOAD");
			}
		},
		error: function(){
			writeToConsole("ERROR ON "+getGreatherThanEntity(2)+" "+contenturl, 0);
			scrollToBottom(1);
			hideContentLoader();
		}
	});
}

function iterator(i,selectorAttribute) {
	if(linkarray.length > i) {
		var img = $(linkarray[i]).attr(selectorAttribute);
		saveImage(img,selectorAttribute);
		setLoadBar(i,linkarrayLength);
		setPercentLoaded(i,linkarrayLength);
	} else if(linkarray.length == 0) {
		writeToConsole(getGreatherThanEntity(15)+" ERROR: NO DATA AVAILABLE "+getGreatherThanEntity(2)+" CHECK URL AND SELECTOR",0);
		scrollToBottom(1);
		setLoadBar(0,0,1);
		hideContentLoader();
		setPercentLoaded(0,0,1);
		enableInput("#start");
		setValue("#start","START DOWNLOAD");
	} else {
		writeToConsole(getGreatherThanEntity(15)+" REQUESTS COMPLETED",1);
		scrollToBottom(1);
		setLoadBar(i,linkarrayLength);
		disableInput("#stop");
		setPercentLoaded(i,linkarrayLength);
		enableInput("#start");
		setValue("#start","START DOWNLOAD");
	}
}

function saveImage(img,selectorAttribute) {
	$.ajax({
		type: 'POST',
		url: "system/ajax.php?request=saveimage&image="+img,
		data: "contenturl="+contenturl,
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
			$("#output").append(data.message);
			renderPreview(data);
			scrollToBottom(1);
			i++;
			iterator(i,selectorAttribute);
		},
		error: function(){
			writeToConsole(getGreatherThanEntity(15)+" ERROR ON "+img,0);
			scrollToBottom(1);
		}
	});
}