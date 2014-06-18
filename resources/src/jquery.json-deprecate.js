( function ( mw, $ ) {
	mw.log.deprecate( $, 'toJSON', $.toJSON, 'Use JSON.stringify instead (module "json" for polyfill).' );
	mw.log.deprecate( $, 'evalJSON', $.evalJSON, 'Use JSON.parse instead (module "json" for polyfill).' );
	mw.log.deprecate( $, 'secureEvalJSON', $.secureEvalJSON, 'Use JSON.parse instead (module "json" for polyfill).' );
	mw.log.deprecate( $, 'quoteString', $.quoteString, 'Use JSON.parse instead (module "json" for polyfill).' );
}( mediaWiki, jQuery ) );
