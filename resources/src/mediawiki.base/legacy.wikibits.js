/**
 * MediaWiki legacy wikibits
 *
 * See https://www.mediawiki.org/wiki/ResourceLoader/Legacy_JavaScript#wikibits.js
 */
var loadedScripts = {};

/**
 * @deprecated since 1.17 Use jQuery instead
 */
mw.log.deprecate( window, 'addOnloadHook', function ( fn ) {
	$( function () {
		fn();
	} );
}, 'Use jQuery instead.' );

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
	s.src = url;
	document.head.appendChild( s );
	return s;
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

mw.log.deprecate( window, 'loadedScripts', loadedScripts, 'Use mw.loader instead.' );
mw.log.deprecate( window, 'importScriptURI', importScriptURI, 'Use mw.loader instead.' );
mw.log.deprecate( window, 'importStylesheetURI', importStylesheetURI, 'Use mw.loader instead.' );

/**
 * Replace document.write/writeln with basic html parsing that appends
 * to the <body> to avoid blanking pages. Added JavaScript will not run.
 *
 * @deprecated since 1.26
 */
[ 'write', 'writeln' ].forEach( function ( method ) {
	mw.log.deprecate( document, method, function () {
		$( document.body ).append( $.parseHTML( Array.prototype.join.call( arguments, '' ) ) );
	}, 'Use jQuery or mw.loader.load instead.', 'document.' + method );
} );
