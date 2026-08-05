const config = require( './config.json' );
const clearAddressBar = require( './clearAddressBar.js' );
const searchRoute = new RegExp( /\/search/ );

let tempInput = null;
let searchDialogOpened = false;

/**
 * @param {module:mediawiki.router} router
 * @param {HTMLButtonElement} trigger
 * @ignore
 */
function addRoutes( router, trigger ) {
	clearAddressBar( router, searchRoute );
	router.addRoute( searchRoute, () => {
		const searchModuleName = config.searchModule;
		mw.loader.using( searchModuleName ).then( () => {
			// eslint-disable-next-line security/detect-non-literal-require
			const { init } = require( searchModuleName );
			// If it exports an init function execute that immediately.
			if ( init ) {
				init();
				transferFocusToRealInput();
			}
		} );
	} );

	router.on( 'route', ( ev ) => {
		if ( searchDialogOpened ) {
			const currentlySearchRoute = ev.path.match( searchRoute );

			if ( currentlySearchRoute ) {
				// Transfer focus to the real search input after the dialog has been loaded
				transferFocusToRealInput();
			} else {
				// Return focus to the search button after exiting the search overlay
				searchDialogOpened = false;
				requestAnimationFrame( () => {
					trigger.focus();
				} );
			}
		}
	} );
}

/**
 * Create a temporary input to open the ios virtual keyboard and maintain keyboard context.
 *
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
	tempInput.style.left = '0';
	tempInput.style.top = '0';
	tempInput.style.fontSize = '16px'; // Prevent iOS zoom
	tempInput.style.opacity = '0';
	tempInput.style.pointerEvents = 'none';
	tempInput.setAttribute( 'readonly', true );

	document.body.appendChild( tempInput );
	tempInput.focus();
}

/**
 * Transfer focus from temp input to real search input after it has been loaded by Vue.
 *
 * @return {void}
 * @ignore
 */
function transferFocusToRealInput() {
	requestAnimationFrame( () => {
		const realInput = document.querySelector( '.cdx-typeahead-search .cdx-text-input__input:not(.skin-minerva-search-trigger)' );
		if ( realInput && tempInput ) {
			realInput.focus();
			tempInput.remove();
			tempInput = null;
		}
	} );
}

/**
 * Associates a given element with the display of a search
 * dialog.
 *
 * @param {HTMLButtonElement} trigger that will launch the search dialog.
 * @method enableSearchDialog
 * @memberof module:mediawiki.page.ready
 */
module.exports = function ( trigger ) {
	mw.loader.using( 'mediawiki.router' ).then( () => {
		const router = require( 'mediawiki.router' );
		addRoutes( router, trigger );
	} );

	// Open search dialog when search trigger is clicked
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
		searchDialogOpened = true;
		createTempInput();
		window.location.hash = '/search';
	} );
};
