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
		window.alert(i18n(wgLivepreviewMessageFailed));
		showFallback();
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

	if (prevReq.readyState > 0 && prevReq.readyState < 4) {
		notify(i18n(wgLivepreviewMessageLoading));
	}

	if(prevReq.readyState != 4) {
		return;
	}

	dismissNotify(i18n(wgLivepreviewMessageReady), 750);

	if( prevReq.status != 200 ) {
		var keys = new Array();
		keys[0] = prevReq.status;
		keys[1] = prevReq.statusText;
		window.alert(i18n(wgLivepreviewMessageError, keys));
		showFallback();
		return;
	}

	var xmlObject = prevReq.responseXML.documentElement;
	var previewElement = xmlObject.getElementsByTagName('preview')[0];
	prevTarget.innerHTML = previewElement.firstChild.data;

	/* Hide the active diff if it exists */
	var diff = document.getElementById('wikiDiff');
	if ( diff ) { diff.style.display = 'none'; }
}

function showFallback() {
	var fallback = document.getElementById('wpPreview');
	if ( fallback ) { fallback.style.display = 'inline'; }
}


// TODO: move elsewhere
/* Small non-intrusive popup which can be used for example to notify the user
 * about completed AJAX action
 */
function notify(message) {
	var notifyElement = document.getElementById('mw-js-notify');
	if ( !notifyElement ) {
		createNotify();
		var notifyElement = document.getElementById('mw-js-notify');
	}
	notifyElement.style.display = 'block';
	notifyElement.innerHTML = message;
}

function dismissNotify(message, timeout) {
	var notifyElement = document.getElementById('mw-js-notify');
	if ( notifyElement ) {
		if ( timeout == 0 ) {
			notifyElement.style.display = 'none';
		} else {
			notify(message);
			setTimeout("dismissNotify('', 0)", timeout);
		}
	}
}

function createNotify() {
	var div = document.createElement("div");
	var txt = '###PLACEHOLDER###'
	var txtNode = document.createTextNode(txt);
	div.appendChild(txtNode);
	div.id = 'mw-js-notify';
	// TODO: move styles to css
	div.setAttribute('style',
		'display: none; position: fixed; bottom: 0px; right: 0px; color: white; background-color: DarkRed; z-index: 5; padding: 0.1em 1em 0.1em 1em; font-size: 120%;');
	var body = document.getElementsByTagName('body')[0];
	body.appendChild(div);
}



/* Helper function similar to wfMsgReplaceArgs() */
function i18n(message, keys) {
	var localMessage = message;
	if ( !keys ) { return localMessage; }
	for( var i = 0; i < keys.length; i++) {
		var myregexp = new RegExp("\\$"+(i+1), 'g');
		localMessage = localMessage.replace(myregexp, keys[i]);
	}
	return localMessage;
}