/**
 * MediaWiki legacy wikibits
 */
( function ( mw, $ ) {
	var msg,
		win = window,
		ua = navigator.userAgent.toLowerCase(),
		onloadFuncts = [],
		loadedScripts = {};

	/**
	 * User-agent sniffing.
	 *
	 * @deprecated since 1.17 Use jquery.client instead
	 */

	msg = 'Use feature detection or module jquery.client instead.';

	mw.log.deprecate( win, 'clientPC', ua, msg );

	// Ignored dummy values
	mw.log.deprecate( win, 'is_gecko', false, msg );
	mw.log.deprecate( win, 'is_chrome_mac', false, msg );
	mw.log.deprecate( win, 'is_chrome', false, msg );
	mw.log.deprecate( win, 'webkit_version', false, msg );
	mw.log.deprecate( win, 'is_safari_win', false, msg );
	mw.log.deprecate( win, 'is_safari', false, msg );
	mw.log.deprecate( win, 'webkit_match', false, msg );
	mw.log.deprecate( win, 'is_ff2', false, msg );
	mw.log.deprecate( win, 'ff2_bugs', false, msg );
	mw.log.deprecate( win, 'is_ff2_win', false, msg );
	mw.log.deprecate( win, 'is_ff2_x11', false, msg );
	mw.log.deprecate( win, 'opera95_bugs', false, msg );
	mw.log.deprecate( win, 'opera7_bugs', false, msg );
	mw.log.deprecate( win, 'opera6_bugs', false, msg );
	mw.log.deprecate( win, 'is_opera_95', false, msg );
	mw.log.deprecate( win, 'is_opera_preseven', false, msg );
	mw.log.deprecate( win, 'is_opera', false, msg );
	mw.log.deprecate( win, 'ie6_bugs', false, msg );

	/**
	 * DOM utilities for handling of events, text nodes and selecting elements
	 *
	 * @deprecated since 1.17 Use jQuery instead
	 */
	msg = 'Use jQuery instead.';

	// Ignored dummy values
	mw.log.deprecate( win, 'doneOnloadHook', undefined, msg );
	mw.log.deprecate( win, 'onloadFuncts', [], msg );
	mw.log.deprecate( win, 'runOnloadHook', $.noop, msg );
	mw.log.deprecate( win, 'changeText', $.noop, msg );
	mw.log.deprecate( win, 'killEvt', $.noop, msg );
	mw.log.deprecate( win, 'addHandler', $.noop, msg );
	mw.log.deprecate( win, 'hookEvent', $.noop, msg );
	mw.log.deprecate( win, 'addClickHandler', $.noop, msg );
	mw.log.deprecate( win, 'removeHandler', $.noop, msg );
	mw.log.deprecate( win, 'getElementsByClassName', function () { return []; }, msg );
	mw.log.deprecate( win, 'getInnerText', function () { return ''; }, msg );

	// Run a function after the window onload event is fired
	mw.log.deprecate( win, 'addOnloadHook', function ( hookFunct ) {
		if ( onloadFuncts ) {
			onloadFuncts.push( hookFunct );
		} else {
			// If func queue is gone the event has happened already,
			// run immediately instead of queueing.
			hookFunct();
		}
	}, msg );

	$( win ).on( 'load', function () {
		var i, functs;

		// Don't run twice
		if ( !onloadFuncts ) {
			return;
		}

		// Deference and clear onloadFuncts before running any
		// hooks to make sure we don't miss any addOnloadHook
		// calls.
		functs = onloadFuncts.slice();
		onloadFuncts = undefined;

		// Execute the queued functions
		for ( i = 0; i < functs.length; i++ ) {
			functs[ i ]();
		}
	} );

	/**
	 * Toggle checkboxes with shift selection
	 *
	 * @deprecated since 1.17 Use jquery.checkboxShiftClick instead
	 */
	msg = 'Use jquery.checkboxShiftClick instead.';
	mw.log.deprecate( win, 'checkboxes', [], msg );
	mw.log.deprecate( win, 'lastCheckbox', null, msg );
	mw.log.deprecate( win, 'setupCheckboxShiftClick', $.noop, msg );
	mw.log.deprecate( win, 'addCheckboxClickHandlers', $.noop, msg );
	mw.log.deprecate( win, 'checkboxClickHandler', $.noop, msg );

	/**
	 * Add a button to the default editor toolbar
	 *
	 * @deprecated since 1.17 Use mw.toolbar instead
	 */
	mw.log.deprecate( win, 'mwEditButtons', [], 'Use mw.toolbar instead.' );
	mw.log.deprecate( win, 'mwCustomEditButtons', [], 'Use mw.toolbar instead.' );

	/**
	 * Spinner creation, injection and removal
	 *
	 * @deprecated since 1.18 Use jquery.spinner instead
	 */
	mw.log.deprecate( win, 'injectSpinner', $.noop, 'Use jquery.spinner instead.' );
	mw.log.deprecate( win, 'removeSpinner', $.noop, 'Use jquery.spinner instead.' );

	/**
	 * Escape utilities
	 *
	 * @deprecated since 1.18 Use mw.html instead
	 */
	mw.log.deprecate( win, 'escapeQuotes', $.noop, 'Use mw.html instead.' );
	mw.log.deprecate( win, 'escapeQuotesHTML', $.noop, 'Use mw.html instead.' );

	/**
	 * Display a message to the user
	 *
	 * @deprecated since 1.17 Use mediawiki.notify instead
	 * @param {string|HTMLElement} message To be put inside the message box
	 */
	mw.log.deprecate( win, 'jsMsg', function ( message ) {
		if ( !arguments.length || message === '' || message === null ) {
			return true;
		}
		if ( typeof message !== 'object' ) {
			message = $.parseHTML( message );
		}
		mw.notify( message, { autoHide: true, tag: 'legacy' } );
		return true;
	}, 'Use mediawiki.notify instead.' );

	/**
	 * Misc. utilities
	 *
	 * @deprecated since 1.17 Use mediawiki.util or jquery.accessKeyLabel instead
	 */
	msg = 'Use mediawiki.util instead.';
	mw.log.deprecate( win, 'addPortletLink', mw.util.addPortletLink, msg );
	mw.log.deprecate( win, 'appendCSS', mw.util.addCSS, msg );
	msg = 'Use jquery.accessKeyLabel instead.';
	mw.log.deprecate( win, 'tooltipAccessKeyPrefix', 'alt-', msg );
	mw.log.deprecate( win, 'tooltipAccessKeyRegexp', /\[(alt-)?(.)\]$/, msg );
	// mw.util.updateTooltipAccessKeys already generates a deprecation message.
	win.updateTooltipAccessKeys = function () {
		return mw.util.updateTooltipAccessKeys.apply( null, arguments );
	};

	/**
	 * Wikipage import methods
	 *
	 * See https://www.mediawiki.org/wiki/ResourceLoader/Legacy_JavaScript#wikibits.js
	 */

	/**
	 * @deprecated since 1.17 Use mw.loader instead. Warnings added in 1.25.
	 */
	function importScriptURI( url ) {
		if ( loadedScripts[ url ] ) {
			return null;
		}
		loadedScripts[ url ] = true;
		var s = document.createElement( 'script' );
		s.setAttribute( 'src', url );
		document.getElementsByTagName( 'head' )[ 0 ].appendChild( s );
		return s;
	}

	function importScript( page ) {
		var uri = mw.config.get( 'wgScript' ) + '?title=' +
			mw.util.wikiUrlencode( page ) +
			'&action=raw&ctype=text/javascript';
		return importScriptURI( uri );
	}

	/**
	 * @deprecated since 1.17 Use mw.loader instead. Warnings added in 1.25.
	 */
	function importStylesheetURI( url, media ) {
		var l = document.createElement( 'link' );
		l.rel = 'stylesheet';
		l.href = url;
		if ( media ) {
			l.media = media;
		}
		document.getElementsByTagName( 'head' )[ 0 ].appendChild( l );
		return l;
	}

	function importStylesheet( page ) {
		var uri = mw.config.get( 'wgScript' ) + '?title=' +
			mw.util.wikiUrlencode( page ) +
			'&action=raw&ctype=text/css';
		return importStylesheetURI( uri );
	}

	msg = 'Use mw.loader instead.';
	mw.log.deprecate( win, 'loadedScripts', loadedScripts, msg );
	mw.log.deprecate( win, 'importScriptURI', importScriptURI, msg );
	mw.log.deprecate( win, 'importStylesheetURI', importStylesheetURI, msg );
	// Not quite deprecated yet.
	win.importScript = importScript;
	win.importStylesheet = importStylesheet;

	// Replace document.write/writeln with basic html parsing that appends
	// to the <body> to avoid blanking pages. Added JavaScript will not run.
	$.each( [ 'write', 'writeln' ], function ( idx, method ) {
		mw.log.deprecate( document, method, function () {
			$( 'body' ).append( $.parseHTML( Array.prototype.join.call( arguments, '' ) ) );
		}, 'Use jQuery or mw.loader.load instead.' );
	} );

}( mediaWiki, jQuery ) );
