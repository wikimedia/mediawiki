/**
 * MediaWiki legacy wikibits
 */
( function ( mw ) {
	var win = window;

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

}( mediaWiki, jQuery ) );
