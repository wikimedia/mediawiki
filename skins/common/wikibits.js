// MediaWiki JavaScript support functions

window.clientPC = navigator.userAgent.toLowerCase(); // Get client info
window.is_gecko = /gecko/.test( clientPC ) &&
	!/khtml|spoofer|netscape\/7\.0/.test(clientPC);

window.is_safari = window.is_safari_win = window.webkit_version =
	window.is_chrome = window.is_chrome_mac = false;
window.webkit_match = clientPC.match(/applewebkit\/(\d+)/);
if (webkit_match) {
	window.is_safari = clientPC.indexOf('applewebkit') != -1 &&
		clientPC.indexOf('spoofer') == -1;
	window.is_safari_win = is_safari && clientPC.indexOf('windows') != -1;
	window.webkit_version = parseInt(webkit_match[1]);
	// Tests for chrome here, to avoid breaking old scripts safari left alone
	// This is here for accesskeys
	window.is_chrome = clientPC.indexOf('chrome') !== -1 &&
		clientPC.indexOf('spoofer') === -1;
	window.is_chrome_mac = is_chrome && clientPC.indexOf('mac') !== -1
}

// For accesskeys; note that FF3+ is included here!
window.is_ff2 = /firefox\/[2-9]|minefield\/3/.test( clientPC );
window.ff2_bugs = /firefox\/2/.test( clientPC );
// These aren't used here, but some custom scripts rely on them
window.is_ff2_win = is_ff2 && clientPC.indexOf('windows') != -1;
window.is_ff2_x11 = is_ff2 && clientPC.indexOf('x11') != -1;

window.is_opera = window.is_opera_preseven = window.is_opera_95 =
	window.opera6_bugs = window.opera7_bugs = window.opera95_bugs = false;
if (clientPC.indexOf('opera') != -1) {
	window.is_opera = true;
	window.is_opera_preseven = window.opera && !document.childNodes;
	window.is_opera_seven = window.opera && document.childNodes;
	window.is_opera_95 = /opera\/(9\.[5-9]|[1-9][0-9])/.test( clientPC );
	window.opera6_bugs = is_opera_preseven;
	window.opera7_bugs = is_opera_seven && !is_opera_95;
	window.opera95_bugs = /opera\/(9\.5)/.test( clientPC );
}
// As recommended by <http://msdn.microsoft.com/en-us/library/ms537509.aspx>,
// avoiding false positives from moronic extensions that append to the IE UA
// string (bug 23171)
window.ie6_bugs = false;
if ( /msie ([0-9]{1,}[\.0-9]{0,})/.exec( clientPC ) != null
&& parseFloat( RegExp.$1 ) <= 6.0 ) {
	ie6_bugs = true;
}

// Global external objects used by this script.
/*extern ta, stylepath, skin */

// add any onload functions in this hook (please don't hard-code any events in the xhtml source)
window.doneOnloadHook = undefined;

if (!window.onloadFuncts) {
	window.onloadFuncts = [];
}

window.addOnloadHook = function( hookFunct ) {
	// Allows add-on scripts to add onload functions
	if( !doneOnloadHook ) {
		onloadFuncts[onloadFuncts.length] = hookFunct;
	} else {
		hookFunct();  // bug in MSIE script loading
	}
};

window.importScript = function( page ) {
	// TODO: might want to introduce a utility function to match wfUrlencode() in PHP
	var uri = wgScript + '?title=' +
		encodeURIComponent(page.replace(/ /g,'_')).replace(/%2F/ig,'/').replace(/%3A/ig,':') +
		'&action=raw&ctype=text/javascript';
	return importScriptURI( uri );
};

window.loadedScripts = {}; // included-scripts tracker
window.importScriptURI = function( url ) {
	if ( loadedScripts[url] ) {
		return null;
	}
	loadedScripts[url] = true;
	var s = document.createElement( 'script' );
	s.setAttribute( 'src', url );
	s.setAttribute( 'type', 'text/javascript' );
	document.getElementsByTagName('head')[0].appendChild( s );
	return s;
};

window.importStylesheet = function( page ) {
	return importStylesheetURI( wgScript + '?action=raw&ctype=text/css&title=' + encodeURIComponent( page.replace(/ /g,'_') ) );
};

window.importStylesheetURI = function( url, media ) {
	var l = document.createElement( 'link' );
	l.type = 'text/css';
	l.rel = 'stylesheet';
	l.href = url;
	if( media ) {
		l.media = media;
	}
	document.getElementsByTagName('head')[0].appendChild( l );
	return l;
};

window.appendCSS = function( text ) {
	var s = document.createElement( 'style' );
	s.type = 'text/css';
	s.rel = 'stylesheet';
	if ( s.styleSheet ) {
		s.styleSheet.cssText = text; // IE
	} else {
		s.appendChild( document.createTextNode( text + '' ) ); // Safari sometimes borks on null
	}
	document.getElementsByTagName('head')[0].appendChild( s );
	return s;
};

// Special stylesheet links for Monobook only (see bug 14717)
if ( typeof stylepath != 'undefined' && skin == 'monobook' ) {
	if ( opera6_bugs ) {
		importStylesheetURI( stylepath + '/' + skin + '/Opera6Fixes.css' );
	} else if ( opera7_bugs ) {
		importStylesheetURI( stylepath + '/' + skin + '/Opera7Fixes.css' );
	} else if ( opera95_bugs ) {
		importStylesheetURI( stylepath + '/' + skin + '/Opera9Fixes.css' );
	} else if ( ff2_bugs ) {
		importStylesheetURI( stylepath + '/' + skin + '/FF2Fixes.css' );
	}
}


if ( 'wgBreakFrames' in window && window.wgBreakFrames ) {
	// Un-trap us from framesets
	if ( window.top != window ) {
		window.top.location = window.location;
	}
}

window.changeText = function( el, newText ) {
	// Safari work around
	if ( el.innerText ) {
		el.innerText = newText;
	} else if ( el.firstChild && el.firstChild.nodeValue ) {
		el.firstChild.nodeValue = newText;
	}
};

window.killEvt = function( evt ) {
	evt = evt || window.event || window.Event; // W3C, IE, Netscape
	if ( typeof ( evt.preventDefault ) != 'undefined' ) {
		evt.preventDefault(); // Don't follow the link
		evt.stopPropagation();
	} else {
		evt.cancelBubble = true; // IE
	}
	return false; // Don't follow the link (IE)
};

window.mwEditButtons = [];
window.mwCustomEditButtons = []; // eg to add in MediaWiki:Common.js

window.escapeQuotes = function( text ) {
	var re = new RegExp( "'", "g" );
	text = text.replace( re, "\\'" );
	re = new RegExp( "\\n", "g" );
	text = text.replace( re, "\\n" );
	return escapeQuotesHTML( text );
};

window.escapeQuotesHTML = function( text ) {
	var re = new RegExp( '&', "g" );
	text = text.replace( re, "&amp;" );
	re = new RegExp( '"', "g" );
	text = text.replace( re, "&quot;" );
	re = new RegExp( '<', "g" );
	text = text.replace( re, "&lt;" );
	re = new RegExp( '>', "g" );
	text = text.replace( re, "&gt;" );
	return text;
};

/**
 * Set the accesskey prefix based on browser detection.
 */
window.tooltipAccessKeyPrefix = 'alt-';
if ( is_opera ) {
	tooltipAccessKeyPrefix = 'shift-esc-';
} else if ( is_chrome ) {
	tooltipAccessKeyPrefix = is_chrome_mac ? 'ctrl-option-' : 'alt-';
} else if ( !is_safari_win && is_safari && webkit_version > 526 ) {
	tooltipAccessKeyPrefix = 'ctrl-alt-';
} else if ( !is_safari_win && ( is_safari
		|| clientPC.indexOf('mac') != -1
		|| clientPC.indexOf('konqueror') != -1 ) ) {
	tooltipAccessKeyPrefix = 'ctrl-';
} else if ( is_ff2 ) {
	tooltipAccessKeyPrefix = 'alt-shift-';
}
window.tooltipAccessKeyRegexp = /\[(ctrl-)?(alt-)?(shift-)?(esc-)?(.)\]$/;

/**
 * Add the appropriate prefix to the accesskey shown in the tooltip.
 * If the nodeList parameter is given, only those nodes are updated;
 * otherwise, all the nodes that will probably have accesskeys by
 * default are updated.
 *
 * @param nodeList Array list of elements to update
 */
window.updateTooltipAccessKeys = function( nodeList ) {
	if ( !nodeList ) {
		// Rather than scan all links on the whole page, we can just scan these
		// containers which contain the relevant links. This is really just an
		// optimization technique.
		var linkContainers = [
			'column-one', // Monobook and Modern
			'mw-head', 'mw-panel', 'p-logo' // Vector
		];
		for ( var i in linkContainers ) {
			var linkContainer = document.getElementById( linkContainers[i] );
			if ( linkContainer ) {
				updateTooltipAccessKeys( linkContainer.getElementsByTagName( 'a' ) );
			}
		}
		// these are rare enough that no such optimization is needed
		updateTooltipAccessKeys( document.getElementsByTagName( 'input' ) );
		updateTooltipAccessKeys( document.getElementsByTagName( 'label' ) );
		return;
	}

	for ( var i = 0; i < nodeList.length; i++ ) {
		var element = nodeList[i];
		var tip = element.getAttribute( 'title' );
		if ( tip && tooltipAccessKeyRegexp.exec( tip ) ) {
			tip = tip.replace(tooltipAccessKeyRegexp,
					  '[' + tooltipAccessKeyPrefix + "$5]");
			element.setAttribute( 'title', tip );
		}
	}
};

/**
 * Add a link to one of the portlet menus on the page, including:
 *
 * p-cactions: Content actions (shown as tabs above the main content in Monobook)
 * p-personal: Personal tools (shown at the top right of the page in Monobook)
 * p-navigation: Navigation
 * p-tb: Toolbox
 *
 * This function exists for the convenience of custom JS authors.  All
 * but the first three parameters are optional, though providing at
 * least an id and a tooltip is recommended.
 *
 * By default the new link will be added to the end of the list.  To
 * add the link before a given existing item, pass the DOM node of
 * that item (easily obtained with document.getElementById()) as the
 * nextnode parameter; to add the link _after_ an existing item, pass
 * the node's nextSibling instead.
 *
 * @param portlet String id of the target portlet ("p-cactions", "p-personal", "p-navigation" or "p-tb")
 * @param href String link URL
 * @param text String link text (will be automatically lowercased by CSS for p-cactions in Monobook)
 * @param id String id of the new item, should be unique and preferably have the appropriate prefix ("ca-", "pt-", "n-" or "t-")
 * @param tooltip String text to show when hovering over the link, without accesskey suffix
 * @param accesskey String accesskey to activate this link (one character, try to avoid conflicts)
 * @param nextnode Node the DOM node before which the new item should be added, should be another item in the same list
 *
 * @return Node -- the DOM node of the new item (an LI element) or null
 */
window.addPortletLink = function( portlet, href, text, id, tooltip, accesskey, nextnode ) {
	var root = document.getElementById( portlet );
	if ( !root ) {
		return null;
	}
	var uls = root.getElementsByTagName( 'ul' );
	var node;
	if ( uls.length > 0 ) {
		node = uls[0];
	} else {
		node = document.createElement( 'ul' );
		var lastElementChild = null;
		for ( var i = 0; i < root.childNodes.length; ++i ) { /* get root.lastElementChild */
			if ( root.childNodes[i].nodeType == 1 ) {
				lastElementChild = root.childNodes[i];
			}
		}
		if ( lastElementChild && lastElementChild.nodeName.match( /div/i ) ) {
			/* Insert into the menu divs */
			lastElementChild.appendChild( node );
		} else {
			root.appendChild( node );
		}
	}
	if ( !node ) {
		return null;
	}

	// unhide portlet if it was hidden before
	root.className = root.className.replace( /(^| )emptyPortlet( |$)/, "$2" );

	var link = document.createElement( 'a' );
	link.appendChild( document.createTextNode( text ) );
	link.href = href;

	// Wrap in a span - make it work with vector tabs and has no effect on any other portlets
	var span = document.createElement( 'span' );
	span.appendChild( link );

	var item = document.createElement( 'li' );
	item.appendChild( span );
	if ( id ) {
		item.id = id;
	}

	if ( accesskey ) {
		link.setAttribute( 'accesskey', accesskey );
		tooltip += ' [' + accesskey + ']';
	}
	if ( tooltip ) {
		link.setAttribute( 'title', tooltip );
	}
	if ( accesskey && tooltip ) {
		updateTooltipAccessKeys( new Array( link ) );
	}

	if ( nextnode && nextnode.parentNode == node ) {
		node.insertBefore( item, nextnode );
	} else {
		node.appendChild( item );  // IE compatibility (?)
	}

	return item;
};

window.getInnerText = function( el ) {
	if ( typeof el == 'string' ) {
		return el;
	}
	if ( typeof el == 'undefined' ) {
		return el;
	}
	// Custom sort value through 'data-sort-value' attribute
	// (no need to prepend hidden text to change sort value)
	if ( el.nodeType && el.getAttribute( 'data-sort-value' ) !== null ) {
		// Make sure it's a valid DOM element (.nodeType) and that the attribute is set (!null)
		return el.getAttribute( 'data-sort-value' );
	}
	if ( el.textContent ) {
		return el.textContent; // not needed but it is faster
	}
	if ( el.innerText ) {
		return el.innerText; // IE doesn't have textContent
	}
	var str = '';

	var cs = el.childNodes;
	var l = cs.length;
	for ( var i = 0; i < l; i++ ) {
		switch ( cs[i].nodeType ) {
			case 1: // ELEMENT_NODE
				str += getInnerText( cs[i] );
				break;
			case 3:	// TEXT_NODE
				str += cs[i].nodeValue;
				break;
		}
	}
	return str;
};

/* Dummy for deprecated function */
window.ta = [];
window.akeytt = function( doId ) {
};

window.checkboxes = undefined;
window.lastCheckbox = undefined;

window.setupCheckboxShiftClick = function() {
	checkboxes = [];
	lastCheckbox = null;
	var inputs = document.getElementsByTagName( 'input' );
	addCheckboxClickHandlers( inputs );
};

window.addCheckboxClickHandlers = function( inputs, start ) {
	if ( !start ) {
		start = 0;
	}

	var finish = start + 250;
	if ( finish > inputs.length ) {
		finish = inputs.length;
	}

	for ( var i = start; i < finish; i++ ) {
		var cb = inputs[i];
		if ( !cb.type || cb.type.toLowerCase() != 'checkbox' || ( ' ' + cb.className + ' ' ).indexOf( ' noshiftselect ' )  != -1 ) {
			continue;
		}
		var end = checkboxes.length;
		checkboxes[end] = cb;
		cb.index = end;
		addClickHandler( cb, checkboxClickHandler );
	}

	if ( finish < inputs.length ) {
		setTimeout( function() {
			addCheckboxClickHandlers( inputs, finish );
		}, 200 );
	}
};

window.checkboxClickHandler = function( e ) {
	if ( typeof e == 'undefined' ) {
		e = window.event;
	}
	if ( !e.shiftKey || lastCheckbox === null ) {
		lastCheckbox = this.index;
		return true;
	}
	var endState = this.checked;
	var start, finish;
	if ( this.index < lastCheckbox ) {
		start = this.index + 1;
		finish = lastCheckbox;
	} else {
		start = lastCheckbox;
		finish = this.index - 1;
	}
	for ( var i = start; i <= finish; ++i ) {
		checkboxes[i].checked = endState;
		if( i > start && typeof checkboxes[i].onchange == 'function' ) {
			checkboxes[i].onchange(); // fire triggers
		}
	}
	lastCheckbox = this.index;
	return true;
};


/*
	Written by Jonathan Snook, http://www.snook.ca/jonathan
	Add-ons by Robert Nyman, http://www.robertnyman.com
	Author says "The credit comment is all it takes, no license. Go crazy with it!:-)"
	From http://www.robertnyman.com/2005/11/07/the-ultimate-getelementsbyclassname/
*/
window.getElementsByClassName = function( oElm, strTagName, oClassNames ) {
	var arrReturnElements = new Array();
	if ( typeof( oElm.getElementsByClassName ) == 'function' ) {
		/* Use a native implementation where possible FF3, Saf3.2, Opera 9.5 */
		var arrNativeReturn = oElm.getElementsByClassName( oClassNames );
		if ( strTagName == '*' ) {
			return arrNativeReturn;
		}
		for ( var h = 0; h < arrNativeReturn.length; h++ ) {
			if( arrNativeReturn[h].tagName.toLowerCase() == strTagName.toLowerCase() ) {
				arrReturnElements[arrReturnElements.length] = arrNativeReturn[h];
			}
		}
		return arrReturnElements;
	}
	var arrElements = ( strTagName == '*' && oElm.all ) ? oElm.all : oElm.getElementsByTagName( strTagName );
	var arrRegExpClassNames = new Array();
	if( typeof oClassNames == 'object' ) {
		for( var i = 0; i < oClassNames.length; i++ ) {
			arrRegExpClassNames[arrRegExpClassNames.length] =
				new RegExp("(^|\\s)" + oClassNames[i].replace(/\-/g, "\\-") + "(\\s|$)");
		}
	} else {
		arrRegExpClassNames[arrRegExpClassNames.length] =
			new RegExp("(^|\\s)" + oClassNames.replace(/\-/g, "\\-") + "(\\s|$)");
	}
	var oElement;
	var bMatchesAll;
	for( var j = 0; j < arrElements.length; j++ ) {
		oElement = arrElements[j];
		bMatchesAll = true;
		for( var k = 0; k < arrRegExpClassNames.length; k++ ) {
			if( !arrRegExpClassNames[k].test( oElement.className ) ) {
				bMatchesAll = false;
				break;
			}
		}
		if( bMatchesAll ) {
			arrReturnElements[arrReturnElements.length] = oElement;
		}
	}
	return ( arrReturnElements );
};

window.redirectToFragment = function( fragment ) {
	var match = navigator.userAgent.match(/AppleWebKit\/(\d+)/);
	if ( match ) {
		var webKitVersion = parseInt( match[1] );
		if ( webKitVersion < 420 ) {
			// Released Safari w/ WebKit 418.9.1 messes up horribly
			// Nightlies of 420+ are ok
			return;
		}
	}
	if ( window.location.hash == '' ) {
		window.location.hash = fragment;

		// Mozilla needs to wait until after load, otherwise the window doesn't
		// scroll.  See <https://bugzilla.mozilla.org/show_bug.cgi?id=516293>.
		// There's no obvious way to detect this programmatically, so we use
		// version-testing.  If Firefox fixes the bug, they'll jump twice, but
		// better twice than not at all, so make the fix hit future versions as
		// well.
		if ( is_gecko ) {
			addOnloadHook(function() {
				if ( window.location.hash == fragment ) {
					window.location.hash = fragment;
				}
			});
		}
	}
};

/**
 * Add a cute little box at the top of the screen to inform the user of
 * something, replacing any preexisting message.
 *
 * @param message String -or- Dom Object  HTML to be put inside the right div
 * @param className String   Used in adding a class; should be different for each
 *   call to allow CSS/JS to hide different boxes.  null = no class used.
 * @return Boolean       True on success, false on failure
 */
window.jsMsg = function( message, className ) {
	if ( !document.getElementById ) {
		return false;
	}
	// We special-case skin structures provided by the software.  Skins that
	// choose to abandon or significantly modify our formatting can just define
	// an mw-js-message div to start with.
	var messageDiv = document.getElementById( 'mw-js-message' );
	if ( !messageDiv ) {
		messageDiv = document.createElement( 'div' );
		if ( document.getElementById( 'column-content' )
		&& document.getElementById( 'content' ) ) {
			// MonoBook, presumably
			document.getElementById( 'content' ).insertBefore(
				messageDiv,
				document.getElementById( 'content' ).firstChild
			);
		} else if ( document.getElementById( 'content' )
		&& document.getElementById( 'article' ) ) {
			// Non-Monobook but still recognizable (old-style)
			document.getElementById( 'article').insertBefore(
				messageDiv,
				document.getElementById( 'article' ).firstChild
			);
		} else {
			return false;
		}
	}

	messageDiv.setAttribute( 'id', 'mw-js-message' );
	messageDiv.style.display = 'block';
	if( className ) {
		messageDiv.setAttribute( 'class', 'mw-js-message-' + className );
	}

	if ( typeof message === 'object' ) {
		while ( messageDiv.hasChildNodes() ) { // Remove old content
			messageDiv.removeChild( messageDiv.firstChild );
		}
		messageDiv.appendChild( message ); // Append new content
	} else {
		messageDiv.innerHTML = message;
	}
	return true;
};

/**
 * Inject a cute little progress spinner after the specified element
 *
 * @param element Element to inject after
 * @param id Identifier string (for use with removeSpinner(), below)
 */
window.injectSpinner = function( element, id ) {
	var spinner = document.createElement( 'img' );
	spinner.id = 'mw-spinner-' + id;
	spinner.src = stylepath + '/common/images/spinner.gif';
	spinner.alt = spinner.title = '...';
	if( element.nextSibling ) {
		element.parentNode.insertBefore( spinner, element.nextSibling );
	} else {
		element.parentNode.appendChild( spinner );
	}
};

/**
 * Remove a progress spinner added with injectSpinner()
 *
 * @param id Identifier string
 */
window.removeSpinner = function( id ) {
	var spinner = document.getElementById( 'mw-spinner-' + id );
	if( spinner ) {
		spinner.parentNode.removeChild( spinner );
	}
};

window.runOnloadHook = function() {
	// don't run anything below this for non-dom browsers
	if ( doneOnloadHook || !( document.getElementById && document.getElementsByTagName ) ) {
		return;
	}

	// set this before running any hooks, since any errors below
	// might cause the function to terminate prematurely
	doneOnloadHook = true;

	// Run any added-on functions
	for ( var i = 0; i < onloadFuncts.length; i++ ) {
		onloadFuncts[i]();
	}
};

/**
 * Add an event handler to an element
 *
 * @param element Element to add handler to
 * @param attach String Event to attach to
 * @param handler callable Event handler callback
 */
window.addHandler = function( element, attach, handler ) {
	if( element.addEventListener ) {
		element.addEventListener( attach, handler, false );
	} else if( element.attachEvent ) {
		element.attachEvent( 'on' + attach, handler );
	}
};

window.hookEvent = function( hookName, hookFunct ) {
	addHandler( window, hookName, hookFunct );
};

/**
 * Add a click event handler to an element
 *
 * @param element Element to add handler to
 * @param handler callable Event handler callback
 */
window.addClickHandler = function( element, handler ) {
	addHandler( element, 'click', handler );
};

/**
 * Removes an event handler from an element
 *
 * @param element Element to remove handler from
 * @param remove String Event to remove
 * @param handler callable Event handler callback to remove
 */
window.removeHandler = function( element, remove, handler ) {
	if( window.removeEventListener ) {
		element.removeEventListener( remove, handler, false );
	} else if( window.detachEvent ) {
		element.detachEvent( 'on' + remove, handler );
	}
};
// note: all skins should call runOnloadHook() at the end of html output,
//      so the below should be redundant. It's there just in case.
hookEvent( 'load', runOnloadHook );

if ( ie6_bugs ) {
	importScriptURI( stylepath + '/common/IEFixes.js' );
}