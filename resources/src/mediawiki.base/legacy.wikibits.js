/**
 * MediaWiki legacy wikibits
 *
 * See https://www.mediawiki.org/wiki/ResourceLoader/Legacy_JavaScript#wikibits.js
 */

/**
 * @since 1.5.8
 * @deprecated since 1.17 Use jQuery instead
 */
mw.log.deprecate( window, 'addOnloadHook', function ( fn ) {
	$( function () {
		fn();
	} );
}, 'Use jQuery instead.' );

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
