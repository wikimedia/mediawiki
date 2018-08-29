/**
 * MediaWiki legacy wikibits
 */
( function () {
	var msg,
		loadedScripts = {};

	function wikiUrlencode( page ) {
		return encodeURIComponent( String( page ) )
			.replace( /'/g, '%27' )
			.replace( /%20/g, '_' )
			// wfUrlencode replacements
			.replace( /%3B/g, ';' )
			.replace( /%40/g, '@' )
			.replace( /%24/g, '$' )
			.replace( /%21/g, '!' )
			.replace( /%2A/g, '*' )
			.replace( /%28/g, '(' )
			.replace( /%29/g, ')' )
			.replace( /%2C/g, ',' )
			.replace( /%2F/g, '/' )
			.replace( /%7E/g, '~' )
			.replace( /%3A/g, ':' );
	}

	/**
	 * @deprecated since 1.17 Use jQuery instead
	 */
	mw.log.deprecate( window, 'addOnloadHook', function ( fn ) {
		$( function () { fn(); } );
	}, 'Use jQuery instead.' );

	/**
	 * Wikipage import methods
	 *
	 * See https://www.mediawiki.org/wiki/ResourceLoader/Legacy_JavaScript#wikibits.js
	 */

	/**
	 * @deprecated since 1.17 Use mw.loader instead. Warnings added in 1.25.
	 * @param {string} url
	 * @return {HTMLElement} Script tag
	 */
	function importScriptURI( url ) {
		var s;
		if ( loadedScripts[ url ] ) {
			return null;
		}
		loadedScripts[ url ] = true;
		s = document.createElement( 'script' );
		s.setAttribute( 'src', url );
		document.head.appendChild( s );
		return s;
	}

	function importScript( page ) {
		var uri = mw.config.get( 'wgScript' ) + '?title=' + wikiUrlencode( page ) +
			'&action=raw&ctype=text/javascript';
		return importScriptURI( uri );
	}

	/**
	 * @deprecated since 1.17 Use mw.loader instead. Warnings added in 1.25.
	 * @param {string} url
	 * @param {string} media
	 * @return {HTMLElement} Link tag
	 */
	function importStylesheetURI( url, media ) {
		var l = document.createElement( 'link' );
		l.rel = 'stylesheet';
		l.href = url;
		if ( media ) {
			l.media = media;
		}
		document.head.appendChild( l );
		return l;
	}

	function importStylesheet( page ) {
		var uri = mw.config.get( 'wgScript' ) + '?title=' + wikiUrlencode( page ) +
			'&action=raw&ctype=text/css';
		return importStylesheetURI( uri );
	}

	msg = 'Use mw.loader instead.';
	mw.log.deprecate( window, 'loadedScripts', loadedScripts, msg );
	mw.log.deprecate( window, 'importScriptURI', importScriptURI, msg );
	mw.log.deprecate( window, 'importStylesheetURI', importStylesheetURI, msg );
	// Not quite deprecated yet.
	window.importScript = importScript;
	window.importStylesheet = importStylesheet;

	/**
	 * Replace document.write/writeln with basic html parsing that appends
	 * to the <body> to avoid blanking pages. Added JavaScript will not run.
	 *
	 * @deprecated since 1.26
	 */
	[ 'write', 'writeln' ].forEach( function ( method ) {
		mw.log.deprecate( document, method, function () {
			$( 'body' ).append( $.parseHTML( Array.prototype.join.call( arguments, '' ) ) );
		}, 'Use jQuery or mw.loader.load instead.', 'document.' + method );
	} );

}() );
