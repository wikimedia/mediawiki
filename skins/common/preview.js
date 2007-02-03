// Live preview

function openXMLHttpRequest() {
	if( window.XMLHttpRequest ) {
		return new XMLHttpRequest();
	} else if( window.ActiveXObject && navigator.platform != 'MacPPC' ) {
		// IE/Mac has an ActiveXObject but it doesn't work.
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
		window.alert('Live preview failed!\nTry normal preview.');
		var fallback = document.getElementById('wpPreview');
		if ( fallback ) { fallback.style.display = 'inline'; }
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
		var fallback = document.getElementById('wpPreview');
		if ( fallback ) { fallback.style.display = 'inline'; }
		return;
	}
	prevTarget.innerHTML = prevReq.responseText;

	/* Hide the active diff if it exists */
	var diff = document.getElementById('wikiDiff');
	if ( diff ) { diff.style.display = 'none'; }
}
