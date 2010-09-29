// MediaWiki JavaScript support functions

var clientPC = navigator.userAgent.toLowerCase(); // Get client info
var is_gecko = /gecko/.test( clientPC ) &&
	!/khtml|spoofer|netscape\/7\.0/.test(clientPC);
var webkit_match = clientPC.match(/applewebkit\/(\d+)/);
if (webkit_match) {
	var is_safari = clientPC.indexOf('applewebkit') != -1 &&
		clientPC.indexOf('spoofer') == -1;
	var is_safari_win = is_safari && clientPC.indexOf('windows') != -1;
	var webkit_version = parseInt(webkit_match[1]);
}
// For accesskeys; note that FF3+ is included here!
var is_ff2 = /firefox\/[2-9]|minefield\/3/.test( clientPC );
var ff2_bugs = /firefox\/2/.test( clientPC );
// These aren't used here, but some custom scripts rely on them
var is_ff2_win = is_ff2 && clientPC.indexOf('windows') != -1;
var is_ff2_x11 = is_ff2 && clientPC.indexOf('x11') != -1;
if (clientPC.indexOf('opera') != -1) {
	var is_opera = true;
	var is_opera_preseven = window.opera && !document.childNodes;
	var is_opera_seven = window.opera && document.childNodes;
	var is_opera_95 = /opera\/(9\.[5-9]|[1-9][0-9])/.test( clientPC );
	var opera6_bugs = is_opera_preseven;
	var opera7_bugs = is_opera_seven && !is_opera_95;
	var opera95_bugs = /opera\/(9\.5)/.test( clientPC );
}
// As recommended by <http://msdn.microsoft.com/en-us/library/ms537509.aspx>,
// avoiding false positives from moronic extensions that append to the IE UA
// string (bug 23171)
var ie6_bugs = false;
if ( /msie ([0-9]{1,}[\.0-9]{0,})/.exec( clientPC ) != null
&& parseFloat( RegExp.$1 ) <= 6.0 ) {
	ie6_bugs = true;
}

// Global external objects used by this script.
/*extern ta, stylepath, skin */

// add any onload functions in this hook (please don't hard-code any events in the xhtml source)
var doneOnloadHook;

if (!window.onloadFuncts) {
	var onloadFuncts = [];
}

function addOnloadHook( hookFunct ) {
	// Allows add-on scripts to add onload functions
	if( !doneOnloadHook ) {
		onloadFuncts[onloadFuncts.length] = hookFunct;
	} else {
		hookFunct();  // bug in MSIE script loading
	}
}

function hookEvent( hookName, hookFunct ) {
	addHandler( window, hookName, hookFunct );
}

function importScript( page ) {
	// TODO: might want to introduce a utility function to match wfUrlencode() in PHP
	var uri = wgScript + '?title=' +
		encodeURIComponent(page.replace(/ /g,'_')).replace(/%2F/ig,'/').replace(/%3A/ig,':') +
		'&action=raw&ctype=text/javascript';
	return importScriptURI( uri );
}

var loadedScripts = {}; // included-scripts tracker
function importScriptURI( url ) {
	if ( loadedScripts[url] ) {
		return null;
	}
	loadedScripts[url] = true;
	var s = document.createElement( 'script' );
	s.setAttribute( 'src', url );
	s.setAttribute( 'type', 'text/javascript' );
	document.getElementsByTagName('head')[0].appendChild( s );
	return s;
}

function importStylesheet( page ) {
	return importStylesheetURI( wgScript + '?action=raw&ctype=text/css&title=' + encodeURIComponent( page.replace(/ /g,'_') ) );
}

function importStylesheetURI( url, media ) {
	var l = document.createElement( 'link' );
	l.type = 'text/css';
	l.rel = 'stylesheet';
	l.href = url;
	if( media ) {
		l.media = media;
	}
	document.getElementsByTagName('head')[0].appendChild( l );
	return l;
}

function appendCSS( text ) {
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
}

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


if ( wgBreakFrames ) {
	// Un-trap us from framesets
	if ( window.top != window ) {
		window.top.location = window.location;
	}
}

function showTocToggle() {
	if ( document.createTextNode ) {
		// Uses DOM calls to avoid document.write + XHTML issues

		var linkHolder = document.getElementById( 'toctitle' );
		var existingLink = document.getElementById( 'togglelink' );
		if ( !linkHolder || existingLink ) {
			// Don't add the toggle link twice
			return;
		}

		var outerSpan = document.createElement( 'span' );
		outerSpan.className = 'toctoggle';

		var toggleLink = document.createElement( 'a' );
		toggleLink.id = 'togglelink';
		toggleLink.className = 'internal';
		toggleLink.href = 'javascript:toggleToc()';
		toggleLink.appendChild( document.createTextNode( tocHideText ) );

		outerSpan.appendChild( document.createTextNode( '[' ) );
		outerSpan.appendChild( toggleLink );
		outerSpan.appendChild( document.createTextNode( ']' ) );

		linkHolder.appendChild( document.createTextNode( ' ' ) );
		linkHolder.appendChild( outerSpan );

		var cookiePos = document.cookie.indexOf( "hidetoc=" );
		if ( cookiePos > -1 && document.cookie.charAt( cookiePos + 8 ) == 1 ) {
			toggleToc();
		}
	}
}

function changeText( el, newText ) {
	// Safari work around
	if ( el.innerText ) {
		el.innerText = newText;
	} else if ( el.firstChild && el.firstChild.nodeValue ) {
		el.firstChild.nodeValue = newText;
	}
}

function toggleToc() {
	var tocmain = document.getElementById( 'toc' );
	var toc = document.getElementById('toc').getElementsByTagName('ul')[0];
	var toggleLink = document.getElementById( 'togglelink' );

	if ( toc && toggleLink && toc.style.display == 'none' ) {
		changeText( toggleLink, tocHideText );
		toc.style.display = 'block';
		document.cookie = "hidetoc=0";
		tocmain.className = 'toc';
	} else {
		changeText( toggleLink, tocShowText );
		toc.style.display = 'none';
		document.cookie = "hidetoc=1";
		tocmain.className = 'toc tochidden';
	}
}

var mwEditButtons = [];
var mwCustomEditButtons = []; // eg to add in MediaWiki:Common.js

function escapeQuotes( text ) {
	var re = new RegExp( "'", "g" );
	text = text.replace( re, "\\'" );
	re = new RegExp( "\\n", "g" );
	text = text.replace( re, "\\n" );
	return escapeQuotesHTML( text );
}

function escapeQuotesHTML( text ) {
	var re = new RegExp( '&', "g" );
	text = text.replace( re, "&amp;" );
	re = new RegExp( '"', "g" );
	text = text.replace( re, "&quot;" );
	re = new RegExp( '<', "g" );
	text = text.replace( re, "&lt;" );
	re = new RegExp( '>', "g" );
	text = text.replace( re, "&gt;" );
	return text;
}

/**
 * Set the accesskey prefix based on browser detection.
 */
var tooltipAccessKeyPrefix = 'alt-';
if ( is_opera ) {
	tooltipAccessKeyPrefix = 'shift-esc-';
} else if ( !is_safari_win && is_safari && webkit_version > 526 ) {
	tooltipAccessKeyPrefix = 'ctrl-alt-';
} else if ( !is_safari_win && ( is_safari
		|| clientPC.indexOf('mac') != -1
		|| clientPC.indexOf('konqueror') != -1 ) ) {
	tooltipAccessKeyPrefix = 'ctrl-';
} else if ( is_ff2 ) {
	tooltipAccessKeyPrefix = 'alt-shift-';
}
var tooltipAccessKeyRegexp = /\[(ctrl-)?(alt-)?(shift-)?(esc-)?(.)\]$/;

/**
 * Add the appropriate prefix to the accesskey shown in the tooltip.
 * If the nodeList parameter is given, only those nodes are updated;
 * otherwise, all the nodes that will probably have accesskeys by
 * default are updated.
 *
 * @param Array nodeList -- list of elements to update
 */
function updateTooltipAccessKeys( nodeList ) {
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
}

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
 * @param String portlet -- id of the target portlet ("p-cactions", "p-personal", "p-navigation" or "p-tb")
 * @param String href -- link URL
 * @param String text -- link text (will be automatically lowercased by CSS for p-cactions in Monobook)
 * @param String id -- id of the new item, should be unique and preferably have the appropriate prefix ("ca-", "pt-", "n-" or "t-")
 * @param String tooltip -- text to show when hovering over the link, without accesskey suffix
 * @param String accesskey -- accesskey to activate this link (one character, try to avoid conflicts)
 * @param Node nextnode -- the DOM node before which the new item should be added, should be another item in the same list
 *
 * @return Node -- the DOM node of the new item (an LI element) or null
 */
function addPortletLink( portlet, href, text, id, tooltip, accesskey, nextnode ) {
	var root = document.getElementById( portlet );
	if ( !root ) {
		return null;
	}
	var node = root.getElementsByTagName( 'ul' )[0];
	if ( !node ) {
		return null;
	}

	// unhide portlet if it was hidden before
	root.className = root.className.replace( /(^| )emptyPortlet( |$)/, "$2" );

	var span = document.createElement( 'span' );
	span.appendChild( document.createTextNode( text ) );

	var link = document.createElement( 'a' );
	link.appendChild( span );
	link.href = href;

	var item = document.createElement( 'li' );
	item.appendChild( link );
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
}

function getInnerText( el ) {
	if ( typeof el == 'string' ) {
		return el;
	}
	if ( typeof el == 'undefined' ) {
		return el;
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
				str += ts_getInnerText( cs[i] );
				break;
			case 3:	// TEXT_NODE
				str += cs[i].nodeValue;
				break;
		}
	}
	return str;
}

/* Dummy for deprecated function */
window.ta = [];
function akeytt( doId ) {
}

var checkboxes;
var lastCheckbox;

function setupCheckboxShiftClick() {
	checkboxes = [];
	lastCheckbox = null;
	var inputs = document.getElementsByTagName( 'input' );
	addCheckboxClickHandlers( inputs );
}

function addCheckboxClickHandlers( inputs, start ) {
	if ( !start ) {
		start = 0;
	}

	var finish = start + 250;
	if ( finish > inputs.length ) {
		finish = inputs.length;
	}

	for ( var i = start; i < finish; i++ ) {
		var cb = inputs[i];
		if ( !cb.type || cb.type.toLowerCase() != 'checkbox' ) {
			continue;
		}
		var end = checkboxes.length;
		checkboxes[end] = cb;
		cb.index = end;
		cb.onclick = checkboxClickHandler;
	}

	if ( finish < inputs.length ) {
		setTimeout( function() {
			addCheckboxClickHandlers( inputs, finish );
		}, 200 );
	}
}

function checkboxClickHandler( e ) {
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
}


/*
	Written by Jonathan Snook, http://www.snook.ca/jonathan
	Add-ons by Robert Nyman, http://www.robertnyman.com
	Author says "The credit comment is all it takes, no license. Go crazy with it!:-)"
	From http://www.robertnyman.com/2005/11/07/the-ultimate-getelementsbyclassname/
*/
function getElementsByClassName( oElm, strTagName, oClassNames ) {
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
}

function redirectToFragment( fragment ) {
	var match = navigator.userAgent.match(/AppleWebKit\/(\d+)/);
	if ( match ) {
		var webKitVersion = parseInt( match[1] );
		if ( webKitVersion < 420 ) {
			// Released Safari w/ WebKit 418.9.1 messes up horribly
			// Nightlies of 420+ are ok
			return;
		}
	}
	if ( is_gecko ) {
		// Mozilla needs to wait until after load, otherwise the window doesn't scroll
		addOnloadHook(function() {
			if ( window.location.hash == '' ) {
				window.location.hash = fragment;
			}
		});
	} else {
		if ( window.location.hash == '' ) {
			window.location.hash = fragment;
		}
	}
}

/*
 * Table sorting script based on one (c) 1997-2006 Stuart Langridge and Joost
 * de Valk:
 * http://www.joostdevalk.nl/code/sortable-table/
 * http://www.kryogenix.org/code/browser/sorttable/
 *
 * @todo don't break on colspans/rowspans (bug 8028)
 * @todo language-specific digit grouping/decimals (bug 8063)
 * @todo support all accepted date formats (bug 8226)
 */

var ts_image_path = stylepath + '/common/images/';
var ts_image_up = 'sort_up.gif';
var ts_image_down = 'sort_down.gif';
var ts_image_none = 'sort_none.gif';
var ts_europeandate = wgContentLanguage != 'en'; // The non-American-inclined can change to "true"
var ts_alternate_row_colors = false;
var ts_number_transform_table = null;
var ts_number_regex = null;

function sortables_init() {
	var idnum = 0;
	// Find all tables with class sortable and make them sortable
	var tables = getElementsByClassName( document, 'table', 'sortable' );
	for ( var ti = 0; ti < tables.length ; ti++ ) {
		if ( !tables[ti].id ) {
			tables[ti].setAttribute( 'id', 'sortable_table_id_' + idnum );
			++idnum;
		}
		ts_makeSortable( tables[ti] );
	}
}

function ts_makeSortable( table ) {
	var firstRow;
	if ( table.rows && table.rows.length > 0 ) {
		if ( table.tHead && table.tHead.rows.length > 0 ) {
			firstRow = table.tHead.rows[table.tHead.rows.length-1];
		} else {
			firstRow = table.rows[0];
		}
	}
	if ( !firstRow ) {
		return;
	}

	// We have a first row: assume it's the header, and make its contents clickable links
	for ( var i = 0; i < firstRow.cells.length; i++ ) {
		var cell = firstRow.cells[i];
		if ( (' ' + cell.className + ' ').indexOf(' unsortable ') == -1 ) {
			cell.innerHTML += '<a href="#" class="sortheader" '
				+ 'onclick="ts_resortTable(this);return false;">'
				+ '<span class="sortarrow">'
				+ '<img src="'
				+ ts_image_path
				+ ts_image_none
				+ '" alt="&darr;"/></span></a>';
		}
	}
	if ( ts_alternate_row_colors ) {
		ts_alternate( table );
	}
}

function ts_getInnerText( el ) {
	return getInnerText( el );
}

function ts_resortTable( lnk ) {
	// get the span
	var span = lnk.getElementsByTagName('span')[0];

	var td = lnk.parentNode;
	var tr = td.parentNode;
	var column = td.cellIndex;

	var table = tr.parentNode;
	while ( table && !( table.tagName && table.tagName.toLowerCase() == 'table' ) ) {
		table = table.parentNode;
	}
	if ( !table ) {
		return;
	}

	if ( table.rows.length <= 1 ) {
		return;
	}

	// Generate the number transform table if it's not done already
	if ( ts_number_transform_table === null ) {
		ts_initTransformTable();
	}

	// Work out a type for the column
	// Skip the first row if that's where the headings are
	var rowStart = ( table.tHead && table.tHead.rows.length > 0 ? 0 : 1 );

	var itm = '';
	for ( var i = rowStart; i < table.rows.length; i++ ) {
		if ( table.rows[i].cells.length > column ) {
			itm = ts_getInnerText(table.rows[i].cells[column]);
			itm = itm.replace(/^[\s\xa0]+/, '').replace(/[\s\xa0]+$/, '');
			if ( itm != '' ) {
				break;
			}
		}
	}

	// TODO: bug 8226, localised date formats
	var sortfn = ts_sort_generic;
	var preprocessor = ts_toLowerCase;
	if ( /^\d\d[\/. -][a-zA-Z]{3}[\/. -]\d\d\d\d$/.test( itm ) ) {
		preprocessor = ts_dateToSortKey;
	} else if ( /^\d\d[\/.-]\d\d[\/.-]\d\d\d\d$/.test( itm ) ) {
		preprocessor = ts_dateToSortKey;
	} else if ( /^\d\d[\/.-]\d\d[\/.-]\d\d$/.test( itm ) ) {
		preprocessor = ts_dateToSortKey;
		// (minus sign)([pound dollar euro yen currency]|cents)
	} else if ( /(^([-\u2212] *)?[\u00a3$\u20ac\u00a4\u00a5]|\u00a2$)/.test( itm ) ) {
		preprocessor = ts_currencyToSortKey;
	} else if ( ts_number_regex.test( itm ) ) {
		preprocessor = ts_parseFloat;
	}

	var reverse = ( span.getAttribute( 'sortdir' ) == 'down' );

	var newRows = new Array();
	var staticRows = new Array();
	for ( var j = rowStart; j < table.rows.length; j++ ) {
		var row = table.rows[j];
		if( (' ' + row.className + ' ').indexOf(' unsortable ') < 0 ) {
			var keyText = ts_getInnerText( row.cells[column] );
			if( keyText === undefined ) {
				keyText = ''; 
			}
			var oldIndex = ( reverse ? -j : j );
			var preprocessed = preprocessor( keyText.replace(/^[\s\xa0]+/, '').replace(/[\s\xa0]+$/, '') );

			newRows[newRows.length] = new Array( row, preprocessed, oldIndex );
		} else {
			staticRows[staticRows.length] = new Array( row, false, j-rowStart );
		}
	}

	newRows.sort( sortfn );

	var arrowHTML;
	if ( reverse ) {
		arrowHTML = '<img src="' + ts_image_path + ts_image_down + '" alt="&darr;"/>';
		newRows.reverse();
		span.setAttribute( 'sortdir', 'up' );
	} else {
		arrowHTML = '<img src="' + ts_image_path + ts_image_up + '" alt="&uarr;"/>';
		span.setAttribute( 'sortdir', 'down' );
	}

	for ( var i = 0; i < staticRows.length; i++ ) {
		var row = staticRows[i];
		newRows.splice( row[2], 0, row );
	}

	// We appendChild rows that already exist to the tbody, so it moves them rather than creating new ones
	// don't do sortbottom rows
	for ( var i = 0; i < newRows.length; i++ ) {
		if ( ( ' ' + newRows[i][0].className + ' ').indexOf(' sortbottom ') == -1 ) {
			table.tBodies[0].appendChild( newRows[i][0] );
		}
	}
	// do sortbottom rows only
	for ( var i = 0; i < newRows.length; i++ ) {
		if ( ( ' ' + newRows[i][0].className + ' ').indexOf(' sortbottom ') != -1 ) {
			table.tBodies[0].appendChild( newRows[i][0] );
		}
	}

	// Delete any other arrows there may be showing
	var spans = getElementsByClassName( tr, 'span', 'sortarrow' );
	for ( var i = 0; i < spans.length; i++ ) {
		spans[i].innerHTML = '<img src="' + ts_image_path + ts_image_none + '" alt="&darr;"/>';
	}
	span.innerHTML = arrowHTML;

	if ( ts_alternate_row_colors ) {
		ts_alternate( table );
	}
}

function ts_initTransformTable() {
	if ( typeof wgSeparatorTransformTable == 'undefined'
			|| ( wgSeparatorTransformTable[0] == '' && wgDigitTransformTable[2] == '' ) )
	{
		digitClass = "[0-9,.]";
		ts_number_transform_table = false;
	} else {
		ts_number_transform_table = {};
		// Unpack the transform table
		// Separators
		ascii = wgSeparatorTransformTable[0].split("\t");
		localised = wgSeparatorTransformTable[1].split("\t");
		for ( var i = 0; i < ascii.length; i++ ) {
			ts_number_transform_table[localised[i]] = ascii[i];
		}
		// Digits
		ascii = wgDigitTransformTable[0].split("\t");
		localised = wgDigitTransformTable[1].split("\t");
		for ( var i = 0; i < ascii.length; i++ ) {
			ts_number_transform_table[localised[i]] = ascii[i];
		}

		// Construct regex for number identification
		digits = ['0', '1', '2', '3', '4', '5', '6', '7', '8', '9', ',', '\\.'];
		maxDigitLength = 1;
		for ( var digit in ts_number_transform_table ) {
			// Escape regex metacharacters
			digits.push(
				digit.replace( /[\\\\$\*\+\?\.\(\)\|\{\}\[\]\-]/,
					function( s ) { return '\\' + s; } )
			);
			if ( digit.length > maxDigitLength ) {
				maxDigitLength = digit.length;
			}
		}
		if ( maxDigitLength > 1 ) {
			digitClass = '[' + digits.join( '', digits ) + ']';
		} else {
			digitClass = '(' + digits.join( '|', digits ) + ')';
		}
	}

	// We allow a trailing percent sign, which we just strip.  This works fine
	// if percents and regular numbers aren't being mixed.
	ts_number_regex = new RegExp(
		"^(" +
			"[-+\u2212]?[0-9][0-9,]*(\\.[0-9,]*)?(E[-+\u2212]?[0-9][0-9,]*)?" + // Fortran-style scientific
			"|" +
			"[-+\u2212]?" + digitClass + "+%?" + // Generic localised
		")$", "i"
	);
}

function ts_toLowerCase( s ) {
	return s.toLowerCase();
}

function ts_dateToSortKey( date ) {
	// y2k notes: two digit years less than 50 are treated as 20XX, greater than 50 are treated as 19XX
	if ( date.length == 11 ) {
		switch ( date.substr( 3, 3 ).toLowerCase() ) {
			case 'jan':
				var month = '01';
				break;
			case 'feb':
				var month = '02';
				break;
			case 'mar':
				var month = '03';
				break;
			case 'apr':
				var month = '04';
				break;
			case 'may':
				var month = '05';
				break;
			case 'jun':
				var month = '06';
				break;
			case 'jul':
				var month = '07';
				break;
			case 'aug':
				var month = '08';
				break;
			case 'sep':
				var month = '09';
				break;
			case 'oct':
				var month = '10';
				break;
			case 'nov':
				var month = '11';
				break;
			case 'dec':
				var month = '12';
				break;
			// default: var month = '00';
		}
		return date.substr( 7, 4 ) + month + date.substr( 0, 2 );
	} else if ( date.length == 10 ) {
		if ( ts_europeandate == false ) {
			return date.substr( 6, 4 ) + date.substr( 0, 2 ) + date.substr( 3, 2 );
		} else {
			return date.substr( 6, 4 ) + date.substr( 3, 2 ) + date.substr( 0, 2 );
		}
	} else if ( date.length == 8 ) {
		yr = date.substr( 6, 2 );
		if ( parseInt( yr ) < 50 ) {
			yr = '20' + yr;
		} else {
			yr = '19' + yr;
		}
		if ( ts_europeandate == true ) {
			return yr + date.substr( 3, 2 ) + date.substr( 0, 2 );
		} else {
			return yr + date.substr( 0, 2 ) + date.substr( 3, 2 );
		}
	}
	return '00000000';
}

function ts_parseFloat( s ) {
	if ( !s ) {
		return 0;
	}
	if ( ts_number_transform_table != false ) {
		var newNum = '', c;

		for ( var p = 0; p < s.length; p++ ) {
			c = s.charAt( p );
			if ( c in ts_number_transform_table ) {
				newNum += ts_number_transform_table[c];
			} else {
				newNum += c;
			}
		}
		s = newNum;
	}
	num = parseFloat( s.replace(/[, ]/g, '').replace("\u2212", '-') );
	return ( isNaN( num ) ? -Infinity : num );
}

function ts_currencyToSortKey( s ) {
	return ts_parseFloat(s.replace(/[^-\u22120-9.,]/g,''));
}

function ts_sort_generic( a, b ) {
	return a[1] < b[1] ? -1 : a[1] > b[1] ? 1 : a[2] - b[2];
}

function ts_alternate( table ) {
	// Take object table and get all it's tbodies.
	var tableBodies = table.getElementsByTagName( 'tbody' );
	// Loop through these tbodies
	for ( var i = 0; i < tableBodies.length; i++ ) {
		// Take the tbody, and get all it's rows
		var tableRows = tableBodies[i].getElementsByTagName( 'tr' );
		// Loop through these rows
		// Start at 1 because we want to leave the heading row untouched
		for ( var j = 0; j < tableRows.length; j++ ) {
			// Check if j is even, and apply classes for both possible results
			var oldClasses = tableRows[j].className.split(' ');
			var newClassName = '';
			for ( var k = 0; k < oldClasses.length; k++ ) {
				if ( oldClasses[k] != '' && oldClasses[k] != 'even' && oldClasses[k] != 'odd' ) {
					newClassName += oldClasses[k] + ' ';
				}
			}
			tableRows[j].className = newClassName + ( j % 2 == 0 ? 'even' : 'odd' );
		}
	}
}

/*
 * End of table sorting code
 */


/**
 * Add a cute little box at the top of the screen to inform the user of
 * something, replacing any preexisting message.
 *
 * @param String -or- Dom Object message HTML to be put inside the right div
 * @param String className   Used in adding a class; should be different for each
 *   call to allow CSS/JS to hide different boxes.  null = no class used.
 * @return Boolean       True on success, false on failure
 */
function jsMsg( message, className ) {
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
}

/**
 * Inject a cute little progress spinner after the specified element
 *
 * @param element Element to inject after
 * @param id Identifier string (for use with removeSpinner(), below)
 */
function injectSpinner( element, id ) {
	var spinner = document.createElement( 'img' );
	spinner.id = 'mw-spinner-' + id;
	spinner.src = stylepath + '/common/images/spinner.gif';
	spinner.alt = spinner.title = '...';
	if( element.nextSibling ) {
		element.parentNode.insertBefore( spinner, element.nextSibling );
	} else {
		element.parentNode.appendChild( spinner );
	}
}

/**
 * Remove a progress spinner added with injectSpinner()
 *
 * @param id Identifier string
 */
function removeSpinner( id ) {
	var spinner = document.getElementById( 'mw-spinner-' + id );
	if( spinner ) {
		spinner.parentNode.removeChild( spinner );
	}
}

function runOnloadHook() {
	// don't run anything below this for non-dom browsers
	if ( doneOnloadHook || !( document.getElementById && document.getElementsByTagName ) ) {
		return;
	}

	// set this before running any hooks, since any errors below
	// might cause the function to terminate prematurely
	doneOnloadHook = true;

	updateTooltipAccessKeys( null );
	setupCheckboxShiftClick();
	sortables_init();

	// Run any added-on functions
	for ( var i = 0; i < onloadFuncts.length; i++ ) {
		onloadFuncts[i]();
	}
}

/**
 * Add an event handler to an element
 *
 * @param Element element Element to add handler to
 * @param String attach Event to attach to
 * @param callable handler Event handler callback
 */
function addHandler( element, attach, handler ) {
	if( window.addEventListener ) {
		element.addEventListener( attach, handler, false );
	} else if( window.attachEvent ) {
		element.attachEvent( 'on' + attach, handler );
	}
}

/**
 * Add a click event handler to an element
 *
 * @param Element element Element to add handler to
 * @param callable handler Event handler callback
 */
function addClickHandler( element, handler ) {
	addHandler( element, 'click', handler );
}

/**
 * Removes an event handler from an element
 *
 * @param Element element Element to remove handler from
 * @param String remove Event to remove
 * @param callable handler Event handler callback to remove
 */
function removeHandler( element, remove, handler ) {
	if( window.removeEventListener ) {
		element.removeEventListener( remove, handler, false );
	} else if( window.detachEvent ) {
		element.detachEvent( 'on' + remove, handler );
	}
}
// note: all skins should call runOnloadHook() at the end of html output,
//      so the below should be redundant. It's there just in case.
hookEvent( 'load', runOnloadHook );

if ( ie6_bugs ) {
	importScriptURI( stylepath + '/common/IEFixes.js' );
}

// For future use.
mw = {};


