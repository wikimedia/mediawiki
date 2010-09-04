/*
 * Legacy emulation for the now depricated skins/common/wikibits.js
 * 
 * MediaWiki JavaScript support functions
 * 
 * Global external objects used by this script: ta, stylepath, skin
 */

( function( $, mw ) {

/* Extension */

/*
 * Scary user-agent detection stuff
 */
mw.legacy.clientPC = navigator.userAgent.toLowerCase(); // Get client info
mw.legacy.is_gecko = /gecko/.test( mw.legacy.clientPC ) && !/khtml|spoofer|netscape\/7\.0/.test(mw.legacy.clientPC);
mw.legacy.webkit_match = mw.legacy.clientPC.match(/applewebkit\/(\d+)/);
if (mw.legacy.webkit_match) {
	mw.legacy.is_safari = mw.legacy.clientPC.indexOf('applewebkit') != -1 &&
		mw.legacy.clientPC.indexOf('spoofer') == -1;
	mw.legacy.is_safari_win = mw.legacy.is_safari && mw.legacy.clientPC.indexOf('windows') != -1;
	mw.legacy.webkit_version = parseInt(mw.legacy.webkit_match[1]);
	// Tests for chrome here, to avoid breaking old scripts safari left alone
	// This is here for accesskeys
	mw.legacy.is_chrome = mw.legacy.clientPC.indexOf('chrome') !== -1 &&
		mw.legacy.clientPC.indexOf('spoofer') === -1;
	mw.legacy.is_chrome_mac = mw.legacy.is_chrome && mw.legacy.clientPC.indexOf('mac') !== -1
}
// For accesskeys; note that FF3+ is included here!
mw.legacy.is_ff2 = /firefox\/[2-9]|minefield\/3/.test( mw.legacy.clientPC );
mw.legacy.ff2_bugs = /firefox\/2/.test( mw.legacy.clientPC );
// These aren't used here, but some custom scripts rely on them
mw.legacy.is_ff2_win = mw.legacy.is_ff2 && mw.legacy.clientPC.indexOf('windows') != -1;
mw.legacy.is_ff2_x11 = mw.legacy.is_ff2 && mw.legacy.clientPC.indexOf('x11') != -1;
if (mw.legacy.clientPC.indexOf('opera') != -1) {
	mw.legacy.is_opera = true;
	mw.legacy.is_opera_preseven = window.opera && !document.childNodes;
	mw.legacy.is_opera_seven = window.opera && document.childNodes;
	mw.legacy.is_opera_95 = /opera\/(9\.[5-9]|[1-9][0-9])/.test( mw.legacy.clientPC );
	mw.legacy.opera6_bugs = mw.legacy.is_opera_preseven;
	mw.legacy.opera7_bugs = mw.legacy.is_opera_seven && !mw.legacy.is_opera_95;
	mw.legacy.opera95_bugs = /opera\/(9\.5)/.test( mw.legacy.clientPC );
}
// As recommended by <http://msdn.microsoft.com/en-us/library/ms537509.aspx>,
// avoiding false positives from moronic extensions that append to the IE UA
// string (bug 23171)
mw.legacy.ie6_bugs = false;
if ( /MSIE ([0-9]{1,}[\.0-9]{0,})/.exec( mw.legacy.clientPC ) != null && parseFloat( RegExp.$1 ) <= 6.0 ) {
	mw.legacy.ie6_bugs = true;
}

$.extend( true, mw.legacy, {

	/*
	 * Events
	 * 
	 * Add any onload functions in this hook (please don't hard-code any events in the xhtml source)
	 */
	
	/* Global Variables */
	
	'doneOnloadHook': null,
	'onloadFuncts': [],
	
	/* Functions */
	
 	'addOnloadHook': function( hookFunct ) {
 		// Allows add-on scripts to add onload functions
 		if( !mw.legacy.doneOnloadHook ) {
 			mw.legacy.onloadFuncts[mw.legacy.onloadFuncts.length] = hookFunct;
 		} else {
 			hookFunct();  // bug in MSIE script loading
 		}
 	},
 	'hookEvent': function( hookName, hookFunct ) {
 		addHandler( window, hookName, hookFunct );
 	},
	'killEvt': function( evt ) {
		evt = evt || window.event || window.Event; // W3C, IE, Netscape
		if ( typeof ( evt.preventDefault ) != 'undefined' ) {
			evt.preventDefault(); // Don't follow the link
			evt.stopPropagation();
		} else {
			evt.cancelBubble = true; // IE
		}
		return false; // Don't follow the link (IE)
	},
 	
	/*
	 * Dynamic loading
	 */
	
	/* Global Variables */
	
	'loadedScripts': {},
	
	/* Functions */
	
	'importScript': function( page ) {
		// TODO: might want to introduce a utility function to match wfUrlencode() in PHP
		var uri = wgScript + '?title=' +
			encodeURIComponent(page.replace(/ /g,'_')).replace(/%2F/ig,'/').replace(/%3A/ig,':') +
			'&action=raw&ctype=text/javascript';
		return importScriptURI( uri );
	},
	'importScriptURI': function( url ) {
		if ( mw.legacy.loadedScripts[url] ) {
			return null;
		}
		mw.legacy.loadedScripts[url] = true;
		var s = document.createElement( 'script' );
		s.setAttribute( 'src', url );
		s.setAttribute( 'type', 'text/javascript' );
		document.getElementsByTagName('head')[0].appendChild( s );
		return s;
	},
	'importStylesheet': function( page ) {
		return importStylesheetURI( wgScript + '?action=raw&ctype=text/css&title=' + encodeURIComponent( page.replace(/ /g,'_') ) );
	},
	'importStylesheetURI': function( url, media ) {
		var l = document.createElement( 'link' );
		l.type = 'text/css';
		l.rel = 'stylesheet';
		l.href = url;
		if( media ) {
			l.media = media;
		}
		document.getElementsByTagName('head')[0].appendChild( l );
		return l;
	},
	'appendCSS': function( text ) {
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
	},
	'runOnloadHook': function() {
		// don't run anything below this for non-dom browsers
		if ( mw.legacy.doneOnloadHook || !( document.getElementById && document.getElementsByTagName ) ) {
			return;
		}
		// set this before running any hooks, since any errors below
		// might cause the function to terminate prematurely
		mw.legacy.doneOnloadHook = true;
		updateTooltipAccessKeys( null );
		setupCheckboxShiftClick();
		sortables_init();
		// Run any added-on functions
		for ( var i = 0; i < mw.legacy.onloadFuncts.length; i++ ) {
			mw.legacy.onloadFuncts[i]();
		}
	},
	/**
	 * Add an event handler to an element
	 *
	 * @param Element element Element to add handler to
	 * @param String attach Event to attach to
	 * @param callable handler Event handler callback
	 */
	'addHandler': function( element, attach, handler ) {
		if( window.addEventListener ) {
			element.addEventListener( attach, handler, false );
		} else if( window.attachEvent ) {
			element.attachEvent( 'on' + attach, handler );
		}
	},
	/**
	 * Add a click event handler to an element
	 *
	 * @param Element element Element to add handler to
	 * @param callable handler Event handler callback
	 */
	'addClickHandler': function( element, handler ) {
		addHandler( element, 'click', handler );
	},
	/**
	 * Removes an event handler from an element
	 *
	 * @param Element element Element to remove handler from
	 * @param String remove Event to remove
	 * @param callable handler Event handler callback to remove
	 */
	'removeHandler': function( element, remove, handler ) {
		if( window.removeEventListener ) {
			element.removeEventListener( remove, handler, false );
		} else if( window.detachEvent ) {
			element.detachEvent( 'on' + remove, handler );
		}
	},
	
	/*
	 * Toolbar
	 */
	
	/* Global Variables */
	
	'mwEditButtons': [],
	'mwCustomEditButtons': [],
	
	/**
	 * Tooltips and access-keys
	 */
	
	/* Global Variables */
	
	/**
	 * Set the accesskey prefix based on browser detection.
	 */
	'tooltipAccessKeyPrefix': ( function() {
		if ( mw.legacy.is_opera ) {
			return 'shift-esc-';
		} else if ( mw.legacy.is_chrome ) {
			return mw.legacy.is_chrome_mac ? 'ctrl-option-' : 'alt-';
		} else if ( !mw.legacy.is_safari_win && mw.legacy.is_safari && mw.legacy.webkit_version > 526 ) {
			return 'ctrl-alt-';
		} else if (
			!mw.legacy.is_safari_win &&
			(
				mw.legacy.is_safari ||
				mw.legacy.clientPC.indexOf( 'mac' ) != -1 ||
				mw.legacy.clientPC.indexOf( 'konqueror' ) != -1
			)
		) {
			return 'ctrl-';
		} else if ( mw.legacy.is_ff2 ) {
			return 'alt-shift-';
		}
		return 'alt-';
	} )(),
	'tooltipAccessKeyRegexp': /\[(ctrl-)?(alt-)?(shift-)?(esc-)?(.)\]$/,
	// Dummy for deprecated function
	'ta': [],
	
	/* Functions */
	
	/**
	 * Add the appropriate prefix to the accesskey shown in the tooltip.
	 * If the nodeList parameter is given, only those nodes are updated;
	 * otherwise, all the nodes that will probably have accesskeys by
	 * default are updated.
	 *
	 * @param Array nodeList -- list of elements to update
	 */
	'updateTooltipAccessKeys': function( nodeList ) {
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
			if ( tip && mw.legacy.tooltipAccessKeyRegexp.exec( tip ) ) {
				tip = tip.replace(mw.legacy.tooltipAccessKeyRegexp,
						  '[' + mw.legacy.tooltipAccessKeyPrefix + '$5]');
				element.setAttribute( 'title', tip );
			}
		}
	},
	// Dummy function for depricated feature
	'akeytt': function( doId ) { },
	
	/*
	 * Checkboxes
	 */
	
	/* Global Varibles */
	
	'checkboxes': null,
	'lastCheckbox': null,
	
	/* Functions */
	
	'setupCheckboxShiftClick': function() {
		mw.legacy.checkboxes = [];
		mw.legacy.lastCheckbox = null;
		var inputs = document.getElementsByTagName( 'input' );
		addCheckboxClickHandlers( inputs );
	},
	'addCheckboxClickHandlers': function( inputs, start ) {
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
			var end = mw.legacy.checkboxes.length;
			mw.legacy.checkboxes[end] = cb;
			cb.index = end;
			addClickHandler( cb, checkboxClickHandler );
		}
		if ( finish < inputs.length ) {
			setTimeout( function() {
				addCheckboxClickHandlers( inputs, finish );
			}, 200 );
		}
	},
	'checkboxClickHandler': function( e ) {
		if ( typeof e == 'undefined' ) {
			e = window.event;
		}
		if ( !e.shiftKey || mw.legacy.lastCheckbox === null ) {
			mw.legacy.lastCheckbox = this.index;
			return true;
		}
		var endState = this.checked;
		var start, finish;
		if ( this.index < mw.legacy.lastCheckbox ) {
			start = this.index + 1;
			finish = mw.legacy.lastCheckbox;
		} else {
			start = mw.legacy.lastCheckbox;
			finish = this.index - 1;
		}
		for ( var i = start; i <= finish; ++i ) {
			mw.legacy.checkboxes[i].checked = endState;
			if( i > start && typeof mw.legacy.checkboxes[i].onchange == 'function' ) {
				mw.legacy.checkboxes[i].onchange(); // fire triggers
			}
		}
		mw.legacy.lastCheckbox = this.index;
		return true;
	},
	
	/*
	 * Table of contents
	 */
	
	/* Functions */
	
	'showTocToggle': function() {
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
			toggleLink.href = '#';
			addClickHandler( toggleLink, function( evt ) { toggleToc(); return killEvt( evt ); } );
			toggleLink.appendChild( document.createTextNode( tocHideText ) );
			outerSpan.appendChild( document.createTextNode( '[' ) );
			outerSpan.appendChild( toggleLink );
			outerSpan.appendChild( document.createTextNode( ']' ) );
			linkHolder.appendChild( document.createTextNode( ' ' ) );
			linkHolder.appendChild( outerSpan );
			var cookiePos = document.cookie.indexOf( 'hidetoc=' );
			if ( cookiePos > -1 && document.cookie.charAt( cookiePos + 8 ) == 1 ) {
				toggleToc();
			}
		}
	},
	'toggleToc': function() {
		var tocmain = document.getElementById( 'toc' );
		var toc = document.getElementById('toc').getElementsByTagName('ul')[0];
		var toggleLink = document.getElementById( 'togglelink' );

		if ( toc && toggleLink && toc.style.display == 'none' ) {
			changeText( toggleLink, tocHideText );
			toc.style.display = 'block';
			document.cookie = 'hidetoc=0';
			tocmain.className = 'toc';
		} else {
			changeText( toggleLink, tocShowText );
			toc.style.display = 'none';
			document.cookie = 'hidetoc=1';
			tocmain.className = 'toc tochidden';
		}
		return false;
	},
	
	/*
	 * Table sorting
	 * 
	 * Script based on one (c) 1997-2006 Stuart Langridge and Joost de Valk:
	 * http://www.joostdevalk.nl/code/sortable-table/
	 * http://www.kryogenix.org/code/browser/sorttable/
	 *
	 * @todo don't break on colspans/rowspans (bug 8028)
	 * @todo language-specific digit grouping/decimals (bug 8063)
	 * @todo support all accepted date formats (bug 8226)
	 */
	
	/* Global Variables */
	
	'ts_image_path': mw.legacy.stylepath + '/common/images/',
	'ts_image_up': 'sort_up.gif',
	'ts_image_down': 'sort_down.gif',
	'ts_image_none': 'sort_none.gif',
	// The non-American-inclined can change to "true"
	'ts_europeandate': mw.legacy.wgContentLanguage != 'en',
	'ts_alternate_row_colors': false,
	'ts_number_transform_table': null,
	'ts_number_regex': null,
	
	/* Functions */
	
	'sortables_init': function() {
		var idnum = 0;
		// Find all tables with class sortable and make them sortable
		var tables = getElementsByClassName( document, 'table', 'sortable' );
		for ( var ti = 0; ti < tables.length ; ti++ ) {
			if ( !tables[ti].id ) {
				tables[ti].setAttribute( 'id', 'sortable_table_id_' + idnum );
				++idnum;
			}
			mw.legacy.ts_makeSortable( tables[ti] );
		}
	},
	'ts_makeSortable': function( table ) {
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
					+ 'onclick="mw.legacy.ts_resortTable(this);return false;">'
					+ '<span class="sortarrow">'
					+ '<img src="'
					+ mw.legacy.ts_image_path
					+ mw.legacy.ts_image_none
					+ '" alt="&darr;"/></span></a>';
			}
		}
		if ( mw.legacy.ts_alternate_row_colors ) {
			mw.legacy.ts_alternate( table );
		}
	},
	'ts_getInnerText': function( el ) {
		return getInnerText( el );
	},
	'ts_resortTable': function( lnk ) {
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
		if ( mw.legacy.ts_number_transform_table === null ) {
			mw.legacy.ts_initTransformTable();
		}
		// Work out a type for the column
		// Skip the first row if that's where the headings are
		var rowStart = ( table.tHead && table.tHead.rows.length > 0 ? 0 : 1 );
		var bodyRows = 0;
		if (rowStart == 0 && table.tBodies) {
			for (var i=0; i < table.tBodies.length; i++ ) {
				bodyRows += table.tBodies[i].rows.length;
			}
			if (bodyRows < table.rows.length)
				rowStart = 1;
		}
		var itm = '';
		for ( var i = rowStart; i < table.rows.length; i++ ) {
			if ( table.rows[i].cells.length > column ) {
				itm = mw.legacy.ts_getInnerText(table.rows[i].cells[column]);
				itm = itm.replace(/^[\s\xa0]+/, '').replace(/[\s\xa0]+$/, '');
				if ( itm != '' ) {
					break;
				}
			}
		}
		// TODO: bug 8226, localised date formats
		var sortfn = mw.legacy.ts_sort_generic;
		var preprocessor = mw.legacy.ts_toLowerCase;
		if ( /^\d\d[\/. -][a-zA-Z]{3}[\/. -]\d\d\d\d$/.test( itm ) ) {
			preprocessor = mw.legacy.ts_dateToSortKey;
		} else if ( /^\d\d[\/.-]\d\d[\/.-]\d\d\d\d$/.test( itm ) ) {
			preprocessor = mw.legacy.ts_dateToSortKey;
		} else if ( /^\d\d[\/.-]\d\d[\/.-]\d\d$/.test( itm ) ) {
			preprocessor = mw.legacy.ts_dateToSortKey;
			// (minus sign)([pound dollar euro yen currency]|cents)
		} else if ( /(^([-\u2212] *)?[\u00a3$\u20ac\u00a4\u00a5]|\u00a2$)/.test( itm ) ) {
			preprocessor = mw.legacy.ts_currencyToSortKey;
		} else if ( mw.legacy.ts_number_regex.test( itm ) ) {
			preprocessor = mw.legacy.ts_parseFloat;
		}
		var reverse = ( span.getAttribute( 'sortdir' ) == 'down' );
		var newRows = new Array();
		var staticRows = new Array();
		for ( var j = rowStart; j < table.rows.length; j++ ) {
			var row = table.rows[j];
			if( (' ' + row.className + ' ').indexOf(' unsortable ') < 0 ) {
				var keyText = mw.legacy.ts_getInnerText( row.cells[column] );
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
			arrowHTML = '<img src="' + mw.legacy.ts_image_path + mw.legacy.ts_image_down + '" alt="&darr;"/>';
			newRows.reverse();
			span.setAttribute( 'sortdir', 'up' );
		} else {
			arrowHTML = '<img src="' + mw.legacy.ts_image_path + mw.legacy.ts_image_up + '" alt="&uarr;"/>';
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
			spans[i].innerHTML = '<img src="' + mw.legacy.ts_image_path + mw.legacy.ts_image_none + '" alt="&darr;"/>';
		}
		span.innerHTML = arrowHTML;
	
		if ( mw.legacy.ts_alternate_row_colors ) {
			mw.legacy.ts_alternate( table );
		}
	},
	'ts_initTransformTable': function() {
		if ( typeof wgSeparatorTransformTable == 'undefined'
				|| ( wgSeparatorTransformTable[0] == '' && wgDigitTransformTable[2] == '' ) )
		{
			var digitClass = '[0-9,.]';
			mw.legacy.ts_number_transform_table = false;
		} else {
			mw.legacy.ts_number_transform_table = {};
			// Unpack the transform table
			// Separators
			var ascii = wgSeparatorTransformTable[0].split('\t');
			var localised = wgSeparatorTransformTable[1].split('\t');
			for ( var i = 0; i < ascii.length; i++ ) {
				mw.legacy.ts_number_transform_table[localised[i]] = ascii[i];
			}
			// Digits
			ascii = wgDigitTransformTable[0].split('\t');
			localised = wgDigitTransformTable[1].split('\t');
			for ( var i = 0; i < ascii.length; i++ ) {
				mw.legacy.ts_number_transform_table[localised[i]] = ascii[i];
			}
			// Construct regex for number identification
			var digits = ['0', '1', '2', '3', '4', '5', '6', '7', '8', '9', ',', '\\.'];
			var maxDigitLength = 1;
			for ( var digit in mw.legacy.ts_number_transform_table ) {
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
				var digitClass = '[' + digits.join( '', digits ) + ']';
			} else {
				var digitClass = '(' + digits.join( '|', digits ) + ')';
			}
		}
		// We allow a trailing percent sign, which we just strip.  This works fine
		// if percents and regular numbers aren't being mixed.
		mw.legacy.ts_number_regex = new RegExp(
			'^(' +
				'[-+\u2212]?[0-9][0-9,]*(\\.[0-9,]*)?(E[-+\u2212]?[0-9][0-9,]*)?' + // Fortran-style scientific
				'|' +
				'[-+\u2212]?' + digitClass + '+%?' + // Generic localised
			')$', 'i'
		);
	},
	'ts_toLowerCase': function( s ) {
		return s.toLowerCase();
	},
	'ts_dateToSortKey': function( date ) {
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
			if ( mw.legacy.ts_europeandate == false ) {
				return date.substr( 6, 4 ) + date.substr( 0, 2 ) + date.substr( 3, 2 );
			} else {
				return date.substr( 6, 4 ) + date.substr( 3, 2 ) + date.substr( 0, 2 );
			}
		} else if ( date.length == 8 ) {
			var yr = date.substr( 6, 2 );
			if ( parseInt( yr ) < 50 ) {
				yr = '20' + yr;
			} else {
				yr = '19' + yr;
			}
			if ( mw.legacy.ts_europeandate == true ) {
				return yr + date.substr( 3, 2 ) + date.substr( 0, 2 );
			} else {
				return yr + date.substr( 0, 2 ) + date.substr( 3, 2 );
			}
		}
		return '00000000';
	},
	'ts_parseFloat': function( s ) {
		if ( !s ) {
			return 0;
		}
		if ( mw.legacy.ts_number_transform_table != false ) {
			var newNum = '', c;
	
			for ( var p = 0; p < s.length; p++ ) {
				c = s.charAt( p );
				if ( c in mw.legacy.ts_number_transform_table ) {
					newNum += mw.legacy.ts_number_transform_table[c];
				} else {
					newNum += c;
				}
			}
			s = newNum;
		}
		var num = parseFloat( s.replace(/[, ]/g, '').replace('\u2212', '-') );
		return ( isNaN( num ) ? -Infinity : num );
	},
	'ts_currencyToSortKey': function( s ) {
		return mw.legacy.ts_parseFloat(s.replace(/[^-\u22120-9.,]/g,''));
	},
	'ts_sort_generic': function( a, b ) {
		return a[1] < b[1] ? -1 : a[1] > b[1] ? 1 : a[2] - b[2];
	},
	'ts_alternate': function( table ) {
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
	},
	
	/*
	 * Skins
	 */
	
	/* Functions */
	
	'changeText': function( el, newText ) {
		// Safari work around
		if ( el.innerText ) {
			el.innerText = newText;
		} else if ( el.firstChild && el.firstChild.nodeValue ) {
			el.firstChild.nodeValue = newText;
		}
	},
	'escapeQuotes': function( text ) {
		var re = new RegExp( '\'', 'g' );
		text = text.replace( re, '\\\'' );
		re = new RegExp( '\\n', 'g' );
		text = text.replace( re, '\\n' );
		return escapeQuotesHTML( text );
	},
	'escapeQuotesHTML': function( text ) {
		var re = new RegExp( '&', 'g' );
		text = text.replace( re, '&amp;' );
		re = new RegExp( '\'', 'g' );
		text = text.replace( re, '&quot;' );
		re = new RegExp( '<', 'g' );
		text = text.replace( re, '&lt;' );
		re = new RegExp( '>', 'g' );
		text = text.replace( re, '&gt;' );
		return text;
	},
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
	'addPortletLink': function( portlet, href, text, id, tooltip, accesskey, nextnode ) {
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
		root.className = root.className.replace( /(^| )emptyPortlet( |$)/, '$2' );
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
	},
	/**
	 * Add a cute little box at the top of the screen to inform the user of
	 * something, replacing any preexisting message.
	 *
	 * @param String -or- Dom Object message HTML to be put inside the right div
	 * @param String className   Used in adding a class; should be different for each
	 *   call to allow CSS/JS to hide different boxes.  null = no class used.
	 * @return Boolean       True on success, false on failure
	 */
	'jsMsg': function( message, className ) {
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
	},
	/**
	 * Inject a cute little progress spinner after the specified element
	 *
	 * @param element Element to inject after
	 * @param id Identifier string (for use with removeSpinner(), below)
	 */
	'injectSpinner': function( element, id ) {
		var spinner = document.createElement( 'img' );
		spinner.id = 'mw-spinner-' + id;
		spinner.src = mw.legacy.stylepath + '/common/images/spinner.gif';
		spinner.alt = spinner.title = '...';
		if( element.nextSibling ) {
			element.parentNode.insertBefore( spinner, element.nextSibling );
		} else {
			element.parentNode.appendChild( spinner );
		}
	},
	/**
	 * Remove a progress spinner added with injectSpinner()
	 *
	 * @param id Identifier string
	 */
	'removeSpinner': function( id ) {
		var spinner = document.getElementById( 'mw-spinner-' + id );
		if( spinner ) {
			spinner.parentNode.removeChild( spinner );
		}
	},
	
	/*
	 * DOM manipulation and traversal
	 */
	
	/* Functions */
	
	'getInnerText': function( el ) {
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
					str += mw.legacy.ts_getInnerText( cs[i] );
					break;
				case 3:	// TEXT_NODE
					str += cs[i].nodeValue;
					break;
			}
		}
		return str;
	},
	/**
	 * Written by Jonathan Snook, http://www.snook.ca/jonathan
	 * Add-ons by Robert Nyman, http://www.robertnyman.com
	 * Author says "The credit comment is all it takes, no license. Go crazy with it!:-)"
	 * From http://www.robertnyman.com/2005/11/07/the-ultimate-getelementsbyclassname/
	 */
	'getElementsByClassName': function( oElm, strTagName, oClassNames ) {
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
					new RegExp('(^|\\s)' + oClassNames[i].replace(/\-/g, '\\-') + '(\\s|$)');
			}
		} else {
			arrRegExpClassNames[arrRegExpClassNames.length] =
				new RegExp('(^|\\s)' + oClassNames.replace(/\-/g, '\\-') + '(\\s|$)');
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
	},
	'redirectToFragment': function( fragment ) {
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
			if ( mw.legacy.is_gecko ) {
				addOnloadHook(function() {
					if ( window.location.hash == fragment ) {
						window.location.hash = fragment;
					}
				});
			}
		}
	}
} );

/* Initialization */

$( document ).ready( function() {
	if ( wgBreakFrames ) {
		// Un-trap us from framesets
		if ( window.top != window ) {
			window.top.location = window.location;
		}
	}
	// Special stylesheet links for Monobook only (see bug 14717)
	if ( typeof stylepath != 'undefined' && skin == 'monobook' ) {
		if ( mw.legacy.opera6_bugs ) {
			importStylesheetURI( stylepath + '/' + skin + '/Opera6Fixes.css' );
		} else if ( mw.legacy.opera7_bugs ) {
			importStylesheetURI( stylepath + '/' + skin + '/Opera7Fixes.css' );
		} else if ( mw.legacy.opera95_bugs ) {
			importStylesheetURI( stylepath + '/' + skin + '/Opera9Fixes.css' );
		} else if ( mw.legacy.ff2_bugs ) {
			importStylesheetURI( stylepath + '/' + skin + '/FF2Fixes.css' );
		}
	}
	if ( mw.legacy.ie6_bugs ) {
		importScriptURI( mw.legacy.stylepath + '/common/IEFixes.js' );
	}
	// NOTE: All skins should call runOnloadHook() at the end of html output, so this should be redundant - it's here
	// just in case
	runOnloadHook();
} );

} )( jQuery, mediaWiki );