// dependencies:
// * ajax.js:
  /*extern sajax_init_object, sajax_do_call */
// * wikibits.js:
  /*extern changeText, akeytt, hookEvent */

// These should have been initialized in the generated js
/*extern wgAjaxWatch, wgArticleId */

if(typeof wgAjaxWatch === "undefined" || !wgAjaxWatch) {
	var wgAjaxWatch = {
		watchMsg: "Watch",
		unwatchMsg: "Unwatch",
		watchingMsg: "Watching...",
		unwatchingMsg: "Unwatching..."
	};
}

wgAjaxWatch.supported = true; // supported on current page and by browser
wgAjaxWatch.watching = false; // currently watching page
wgAjaxWatch.inprogress = false; // ajax request in progress
wgAjaxWatch.timeoutID = null; // see wgAjaxWatch.ajaxCall
wgAjaxWatch.watchLink1 = null; // "watch"/"unwatch" link
wgAjaxWatch.watchLink2 = null; // second one, for (some?) non-Monobook-based
wgAjaxWatch.oldHref = null; // url for action=watch/action=unwatch

wgAjaxWatch.setLinkText = function(newText) {
	changeText(wgAjaxWatch.watchLink1, newText);
	if (wgAjaxWatch.watchLink2) {
		changeText(wgAjaxWatch.watchLink2, newText);
	}
};

wgAjaxWatch.setLinkID = function(newId) {
	wgAjaxWatch.watchLink1.id = newId;
	akeytt(newId); // update tooltips for Monobook
};

wgAjaxWatch.ajaxCall = function() {
	if(!wgAjaxWatch.supported || wgAjaxWatch.inprogress) {
		return;
	}
	wgAjaxWatch.inprogress = true;
	wgAjaxWatch.setLinkText(wgAjaxWatch.watching ? wgAjaxWatch.unwatchingMsg : wgAjaxWatch.watchingMsg);
	sajax_do_call("wfAjaxWatch", [wgArticleId, (wgAjaxWatch.watching ? "u" : "w")], wgAjaxWatch.processResult);
	// if the request isn't done in 10 seconds, allow user to try again
	wgAjaxWatch.timeoutID = window.setTimeout(function() { wgAjaxWatch.inprogress = false; }, 10000);
	return;
};

wgAjaxWatch.processResult = function(request) {
	if(!wgAjaxWatch.supported) {
		return;
	}
	var response = request.responseText;
	if(response == "<err#>") {
		window.location.href = wgAjaxWatch.oldHref;
		return;
	} else if(response == "<w#>") {
		wgAjaxWatch.watching = true;
		wgAjaxWatch.setLinkText(wgAjaxWatch.unwatchMsg);
		wgAjaxWatch.setLinkID("ca-unwatch");
		wgAjaxWatch.oldHref = wgAjaxWatch.oldHref.replace(/action=watch/, "action=unwatch");
	} else if(response == "<u#>") {
		wgAjaxWatch.watching = false;
		wgAjaxWatch.setLinkText(wgAjaxWatch.watchMsg);
		wgAjaxWatch.setLinkID("ca-watch");
		wgAjaxWatch.oldHref = wgAjaxWatch.oldHref.replace(/action=unwatch/, "action=watch");
	}
	wgAjaxWatch.inprogress = false;
	if(wgAjaxWatch.timeoutID) {
		window.clearTimeout(wgAjaxWatch.timeoutID);
	}
	return;
};

wgAjaxWatch.onLoad = function() {
	var el1 = document.getElementById("ca-unwatch");
	var el2 = null;
	if (!el1) {
		el1 = document.getElementById("mw-unwatch-link1");
		el2 = document.getElementById("mw-unwatch-link2");
	}
	if(el1) {
		wgAjaxWatch.watching = true;
	} else {
		wgAjaxWatch.watching = false;
		el1 = document.getElementById("ca-watch");
		if (!el1) {
			el1 = document.getElementById("mw-watch-link1");
			el2 = document.getElementById("mw-watch-link2");
		}
		if(!el1) {
			wgAjaxWatch.supported = false;
			return;
		}
	}

	if(!wfSupportsAjax()) {
		wgAjaxWatch.supported = false;
		return;
	}

	// The id can be either for the parent (Monobook-based) or the element
	// itself (non-Monobook)
	wgAjaxWatch.watchLink1 = el1.tagName.toLowerCase() == "a" ? el1 : el1.firstChild;
	wgAjaxWatch.watchLink2 = el2 ? el2 : null;

	wgAjaxWatch.oldHref = wgAjaxWatch.watchLink1.getAttribute("href");
	wgAjaxWatch.watchLink1.setAttribute("href", "javascript:wgAjaxWatch.ajaxCall()");
	if (wgAjaxWatch.watchLink2) {
		wgAjaxWatch.watchLink2.setAttribute("href", "javascript:wgAjaxWatch.ajaxCall()");
	}
	return;
};

hookEvent("load", wgAjaxWatch.onLoad);

/**
 * @return boolean whether the browser supports XMLHttpRequest
 */
function wfSupportsAjax() {
	var request = sajax_init_object();
	var supportsAjax = request ? true : false;
	delete request;
	return supportsAjax;
}
