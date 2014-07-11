( function ( mw, $ ) {
	// @deprecated since 1.24.  The 'jquery.json' module will be removed in MW 1.25.  Use the 'json' module.

	mw.log.deprecate( $, 'toJSON', $.toJSON, 'Use JSON.stringify instead (module "json" for polyfill).' );
	mw.log.deprecate( $, 'evalJSON', $.evalJSON, 'Use JSON.parse instead (module "json" for polyfill).' );
	mw.log.deprecate( $, 'secureEvalJSON', $.secureEvalJSON, 'Use JSON.parse instead (module "json" for polyfill).' );
	mw.log.deprecate( $, 'quoteString', $.quoteString, 'Use JSON.stringify instead (module "json" for polyfill).' );
}( mediaWiki, jQuery ) );
