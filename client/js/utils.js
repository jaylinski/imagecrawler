/*
 * ImageCrawler
 *
 * https://github.com/jaylinski/imagecrawler
 * 
 * Last update: 02.12.2012
 */

function renderPreview(data) {
	if($("#showpreview").attr("checked") == "checked") {
		$("#preview").prepend(data.image);
		$("#preview").prepend("<div class='clear'></div>");
		var preview_images = $("#preview img");
		var temp_height = $(preview_images[0]).attr("height");
		$(preview_images[0]).attr("height","0");
		$(preview_images[0]).animate({height:temp_height}, 400, function() {
			$(preview_images[0]).animate({top:'0'}, 500, function() {
				if(preview_images.length > 1) {
					$(preview_images[previewLength]).fadeOut(function() {
						$(this).remove();
						$("#preview div").last().remove();
					});
				}
			});
		});
	}
}

function writeToConsole(text,status) {
	if(typeof status != "undefined") {
		if(status == 1) {
			$("#output").append("<span class='success'>"+getTime()+" "+text+"</span><br />");
		} else if(status == 0) {
			$("#output").append("<span class='error'>"+getTime()+" "+text+"</span><br />");
		} else if(status == 2) {
			$("#output").append("<span class='warning'>"+getTime()+" "+text+"</span><br />");
		} else {
			$("#output").append(getTime()+" UNKOWN STATUS "+text);
		}
	} else {
		$("#output").append(getTime()+" "+text+"<br />");
	}
}
function resetConsole() {
	$("#output").html("");
}

function resetPreview() {
	$("#preview").html("");
}

function showContentLoader() {
	$("input#url").addClass("active");
}
function hideContentLoader() {
	$("input#url").removeClass("active");
}

function setTitle(text) {
	document.title = projectName+" | "+text;
}

function setPercentLoaded(imageNumber,linkArrayLength,error) {
	var percent = formatPercentLoaded(imageNumber,linkArrayLength);
	if(percent == 100) {
		setTitle("100%");
		$("#ui-bottom .ui-left h1").html("REQUESTS COMPLETED");
	} else {
		setTitle(percent+"%");
		$("#ui-bottom .ui-left h1").html(percent+"% ("+imageNumber+"/"+linkArrayLength+")");
	}	
	if(typeof error != "undefined") {
		setTitle("ERROR");
		$("#ui-bottom .ui-left h1").html("ERROR");
	}
}

function setLoadBar(imageNumber,linkArrayLength,error) {
	var percentage = formatPercentLoaded(imageNumber,linkArrayLength);
	var backgroundColor = $("#status").css("background-color");
	var backgroundColorError = $(".error").css("color");
	$("#status").css({"background-color":backgroundColor});
	$("#status").animate({width: percentage+"%"}, 200);
	if(typeof error != "undefined") {
		$("#status").css({"background-color":backgroundColorError,"width":"100%"});
	}
}
function resetLoadBar() {
	$("#status").removeAttr("style");
	$("#ui-bottom .ui-left h1").html("LOADING...");
}

function setValue(selector,value) {
	$(selector).attr("value",value);
}

function enableInput(selector) {
	$(selector).removeAttr("disabled");
}
function disableInput(selector) {
	$(selector).attr("disabled","disabled");
}

function isChecked(selector) {
	if($(selector).attr("checked") == "checked") {
		return true;
	} else {
		return false;
	}
}

function scrollToTop() {
	window.scroll(0,0);
}
function scrollToBottom(error) {
	if(typeof error != "undefined") {
		if(isChecked("#showconsole")) {
			window.scroll(0,$(document).height());
		}
	} else {
		window.scroll(0,$(document).height());
	}
}

function getTime() {
	var currentDate = new Date();
	var hours = currentDate.getHours();
	if(hours < 10) hours = "0"+hours;
	var minutes = currentDate.getMinutes();
	if(minutes < 10) minutes = "0"+minutes;
	var seconds = currentDate.getSeconds();
	if(seconds < 10) seconds = "0"+seconds;
	return hours+":"+minutes+":"+seconds;
}

function formatBytes(bytes) {
	if (bytes < 1024) return bytes+' bytes';
	else if (bytes < 1048576) return round((bytes / 1024))+' KB';
	else if (bytes < 1073741824) return round((bytes / 1048576))+' MB';
	else if (bytes < 1099511627776) return round((bytes / 1073741824))+' GB';
	else return "insanely big";
}

function formatPercentLoaded(imageNumber,linkArrayLength) {
	return round((imageNumber/linkArrayLength)*100);
}

function round(x) {
	var k = (Math.round(x * 100) / 100).toString();
	k += (k.indexOf('.') == -1)? '.00' : '00';
	return k.substring(0, k.indexOf('.') + 3);
}

function getGreatherThanEntity(count) {
	var string = "";
	var j = 0;
	for(j=0; j < count; j++) {
		string += "&gt;";
	}
	return string;
}