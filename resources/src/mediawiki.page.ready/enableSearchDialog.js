const config = require( './config.json' );
const clearAddressBar = require( './clearAddressBar.js' );
const searchRoute = new RegExp( /\/search/ );

let tempInput = null;

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

				if ( tempInput ) {
					transferFocusToRealInput();
				}
			}
		} );
	} );
}

/**
 * Create a temporary input to open the ios virtual keyboard and maintain keyboard context
 * @return {void}
 * @ignore
 */
function createTempInput() {
	if ( tempInput ) {
		tempInput.remove();
	}

	tempInput = document.createElement( 'input' );
	tempInput.type = 'text';
	tempInput.style.position = 'absolute';
	tempInput.style.left = '-9999px';
	tempInput.style.opacity = '0';
	tempInput.style.pointerEvents = 'none';
	tempInput.setAttribute( 'readonly', true );

	document.body.appendChild( tempInput );
	tempInput.focus();
}

/**
 * Transfer focus from temp input to real search input after it has been loaded by vue
 * @return {void}
 * @ignore
 */
function transferFocusToRealInput() {
	const realInput = document.querySelector( '.cdx-typeahead-search .cdx-text-input__input' );

	if ( realInput && tempInput ) {
		requestAnimationFrame( () => {
			realInput.focus();
			tempInput.remove();
			tempInput = null;
		} );
	}
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

		/**
		 * On-screen keyboard on iOS only opens when `focus()` is called from a "user context event".
		 * http://stackoverflow.com/questions/6837543/show-virtual-keyboard-on-mobile-phones-in-javascript
		 *
		 * This route callback triggers the TypeaheadSearch overlay to be loaded in by Vue
		 * but because the search input isn't yet available, the on-screen keyboard on iOS
		 * can't be triggered immediately. To work around this, we create a temporary input
		 * to trigger the virtual keyboard and maintain the keyboard context. Then after
		 * TAHS is finished loading, we transfer focus to the real search input.
		 */
		window.location.hash = '/search';
		createTempInput();
	} );
};
