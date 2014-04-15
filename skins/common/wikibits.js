/**
 * MediaWiki legacy wikibits
 */
( function ( mw, $ ) {
	var win = window,
		ua = navigator.userAgent.toLowerCase(),
		isIE6 = ( /msie ([0-9]{1,}[\.0-9]{0,})/.exec( ua ) && parseFloat( RegExp.$1 ) <= 6.0 ),
		isGecko = /gecko/.test( ua ) && !/khtml|spoofer|netscape\/7\.0/.test( ua );

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
