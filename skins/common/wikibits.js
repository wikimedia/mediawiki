/**
 * MediaWiki legacy wikibits
 */
( function ( mw, $ ) {
	var msg,
		win = window,
		ua = navigator.userAgent.toLowerCase(),
		isIE6 = ( /msie ([0-9]{1,}[\.0-9]{0,})/.exec( ua ) && parseFloat( RegExp.$1 ) <= 6.0 ),
		isGecko = /gecko/.test( ua ) && !/khtml|spoofer|netscape\/7\.0/.test( ua ),
		onloadFuncts = [];

if ( mw.config.get( 'wgBreakFrames' ) ) {
	// Note: In IE < 9 strict comparison to window is non-standard (the standard didn't exist yet)
	// it works only comparing to window.self or window.window (http://stackoverflow.com/q/4850978/319266)
	if ( win.top !== win.self ) {
		// Un-trap us from framesets
		win.top.location = win.location;
	}
}

/**
 * Legacy function to scroll to an id while viewing the page over a redirect.
 * Superseeded by module 'mediawiki.action.view.redirectToFragment' in version 1.23.
 * Kepted because cache can contain still inline script calls to this function.
 * Should be removed in version 1.24.
 * @deprecated since 1.23 Use mediawiki.action.view.redirectToFragment instead
 */
mw.log.deprecate( win, 'redirectToFragment', function ( fragment ) {
	var webKitVersion,
		match = navigator.userAgent.match( /AppleWebKit\/(\d+)/ );
	if ( match ) {
		webKitVersion = parseInt( match[1], 10 );
		if ( webKitVersion < 420 ) {
			// Released Safari w/ WebKit 418.9.1 messes up horribly
			// Nightlies of 420+ are ok
			return;
		}
	}
	if ( !win.location.hash ) {
		win.location.hash = fragment;

		// Mozilla needs to wait until after load, otherwise the window doesn't
		// scroll.  See <https://bugzilla.mozilla.org/show_bug.cgi?id=516293>.
		// There's no obvious way to detect this programmatically, so we use
		// version-testing.  If Firefox fixes the bug, they'll jump twice, but
		// better twice than not at all, so make the fix hit future versions as
		// well.
		if ( isGecko ) {
			$( function () {
				if ( win.location.hash === fragment ) {
					win.location.hash = fragment;
				}
			} );
		}
	}
}, 'Use the module mediawiki.action.view.redirectToFragment instead.' );

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
		onloadFuncts.push(hookFunct);
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
		functs[i]();
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
mw.log.deprecate( win, 'jsMsg', mw.util.jsMessage, 'Use mediawiki.notify instead.' );

/**
 * Misc. utilities
 *
 * @deprecated since 1.17 Use mediawiki.util instead
 */
msg = 'Use mediawiki.util instead.';
mw.log.deprecate( win, 'tooltipAccessKeyPrefix', 'alt-', msg );
mw.log.deprecate( win, 'tooltipAccessKeyRegexp', /\[(alt-)?(.)\]$/, msg );
mw.log.deprecate( win, 'updateTooltipAccessKeys', mw.util.updateTooltipAccessKeys, msg );
mw.log.deprecate( win, 'addPortletLink', mw.util.addPortletLink, msg );
mw.log.deprecate( win, 'appendCSS', mw.util.addCSS, msg );

/**
 * Wikipage import methods
 */

// included-scripts tracker
win.loadedScripts = {};

win.importScript = function ( page ) {
	var uri = mw.config.get( 'wgScript' ) + '?title=' +
		mw.util.wikiUrlencode( page ) +
		'&action=raw&ctype=text/javascript';
	return win.importScriptURI( uri );
};

win.importScriptURI = function ( url ) {
	if ( win.loadedScripts[url] ) {
		return null;
	}
	win.loadedScripts[url] = true;
	var s = document.createElement( 'script' );
	s.setAttribute( 'src', url );
	s.setAttribute( 'type', 'text/javascript' );
	document.getElementsByTagName( 'head' )[0].appendChild( s );
	return s;
};

win.importStylesheet = function ( page ) {
	var uri = mw.config.get( 'wgScript' ) + '?title=' +
		mw.util.wikiUrlencode( page ) +
		'&action=raw&ctype=text/css';
	return win.importStylesheetURI( uri );
};

win.importStylesheetURI = function ( url, media ) {
	var l = document.createElement( 'link' );
	l.rel = 'stylesheet';
	l.href = url;
	if ( media ) {
		l.media = media;
	}
	document.getElementsByTagName('head')[0].appendChild( l );
	return l;
};

if ( isIE6 ) {
	win.importScriptURI( mw.config.get( 'stylepath' ) + '/common/IEFixes.js' );
}

}( mediaWiki, jQuery ) );
