// Live preview

function openXMLHttpRequest() {
	if( window.XMLHttpRequest ) {
		return new XMLHttpRequest();
	} else if( window.ActiveXObject ) {
		return new ActiveXObject("Microsoft.XMLHTTP");
	} else {
		return null;
	}
}

/**
 * Returns true if could open the request,
 * false otherwise (eg no browser support).
 */
function livePreview(target, text, postUrl) {
	prevTarget = target;
	if( !target ) {
		window.alert('crash and burn');
	}
	prevReq = openXMLHttpRequest();
	if( !prevReq ) return false;
	
	prevReq.onreadystatechange = updatePreviewText;
	prevReq.open("POST", postUrl, true);
	
	var postData = 'wpTextbox1=' + encodeURIComponent(text);
	prevReq.setRequestHeader("Content-Type", "application/x-www-form-urlencoded");
	prevReq.send(postData);
	return true;
}

function updatePreviewText() {
	if( prevReq.readyState != 4 ) {
		return;
	}
	if( prevReq.status != 200 ) {
		window.alert('Failed to connect: ' + prevReq.status +
			' "' + prevReq.statusText + '"');
		return;
	}
	prevTarget.innerHTML = prevReq.responseText;
}
