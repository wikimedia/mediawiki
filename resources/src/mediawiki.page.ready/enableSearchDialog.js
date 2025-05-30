const config = require( './config.json' );
const clearAddressBar = require( './clearAddressBar.js' );
const searchRoute = new RegExp( /\/search/ );

/**
 * @param {module:mediawiki.router} router
 * @ignore
 */
function addRoutes( router ) {
	clearAddressBar( router, searchRoute );
	router.addRoute( searchRoute, () => {
		const searchModuleName = config.searchModule;
		mw.loader.using( searchModuleName ).then( () => {
			const { init } = require( searchModuleName );
			// If it exports an init function execute that immediately.
			if ( init ) {
				init();
			}
		} );
	} );
}

/**
 * Associates a given element with the display of a search
 * dialog.
 *
 * @param {Element} trigger that will launch the search dialog.
 * @namespace loadSearchModule
 * @memberof module:mediawiki.page.ready
 */
module.exports = function ( trigger ) {
	mw.loader.using( 'mediawiki.router' ).then( () => {
		const router = require( 'mediawiki.router' );
		addRoutes( router );
	} );
	trigger.addEventListener( 'click', ( ev ) => {
		ev.preventDefault();
		window.location.hash = '/search';
	} );
};
