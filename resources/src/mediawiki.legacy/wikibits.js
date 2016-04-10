/**
 * MediaWiki legacy wikibits
 */
( function ( mw, $ ) {
	var msg,
		win = window,
		loadedScripts = {};

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
		mw.loader.using( 'mediawiki.util' ).then( function () {
			var uri = mw.config.get( 'wgScript' ) + '?title=' +
				mw.util.wikiUrlencode( page ) +
				'&action=raw&ctype=text/javascript';
			importScriptURI( uri );
		} );
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
		mw.loader.using( 'mediawiki.util' ).then( function () {
			var uri = mw.config.get( 'wgScript' ) + '?title=' +
				mw.util.wikiUrlencode( page ) +
				'&action=raw&ctype=text/css';
			importStylesheetURI( uri );
		} );
	}

	msg = 'Use mw.loader instead.';
	mw.log.deprecate( win, 'loadedScripts', loadedScripts, msg );
	mw.log.deprecate( win, 'importScriptURI', importScriptURI, msg );
	mw.log.deprecate( win, 'importStylesheetURI', importStylesheetURI, msg );
	// Not quite deprecated yet.
	win.importScript = importScript;
	win.importStylesheet = importStylesheet;

	/**
	 * Replace document.write/writeln with basic html parsing that appends
	 * to the <body> to avoid blanking pages. Added JavaScript will not run.
	 *
	 * @deprecated since 1.26
	 */
	$.each( [ 'write', 'writeln' ], function ( idx, method ) {
		mw.log.deprecate( document, method, function () {
			$( 'body' ).append( $.parseHTML( Array.prototype.join.call( arguments, '' ) ) );
		}, 'Use jQuery or mw.loader.load instead.' );
	} );

}( mediaWiki, jQuery ) );
