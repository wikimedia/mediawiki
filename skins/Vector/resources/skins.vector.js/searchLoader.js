/**
 * Disabling this rule as it's only necessary for
 * combining multiple class names and documenting the output.
 * That doesn't happen in this file but the linter still throws an error.
 * https://github.com/wikimedia/eslint-plugin-mediawiki/blob/master/docs/rules/class-doc.md
 */

/** @interface VectorResourceLoaderVirtualConfig */
/** @interface MediaWikiPageReadyModule */

const /** @type {VectorResourceLoaderVirtualConfig} */
	config = require( /** @type {string} */ ( './config.json' ) ),
	// T251544: Collect search performance metrics to compare Vue search with
	// mediawiki.searchSuggest performance.
	CAN_TEST_SEARCH = !!(
		window.performance &&
		!!performance.mark &&
		!!performance.measure &&
		performance.getEntriesByName ),
	LOAD_START_MARK = 'mwVectorVueSearchLoadStart',
	LOAD_END_MARK = 'mwVectorVueSearchLoadEnd',
	LOAD_MEASURE = 'mwVectorVueSearchLoadStartToLoadEnd',
	SEARCH_LOADING_CLASS = 'search-form__loader';

/**
 * Loads the search module via `mw.loader.using` on the element's
 * focus event. Or, if the element is already focused, loads the
 * search module immediately.
 * After the search module is loaded, executes a function to remove
 * the loading indicator.
 *
 * @param {Element} element search input.
 * @param {string} moduleName resourceLoader module to load.
 * @param {string|null} startMarker
 * @param {null|function(): void} afterLoadFn function to execute after search module loads.
 */
function loadSearchModule( element, moduleName, startMarker, afterLoadFn ) {
	const SHOULD_TEST_SEARCH = CAN_TEST_SEARCH &&
		moduleName === 'skins.vector.search';

	function requestSearchModule() {
		if ( SHOULD_TEST_SEARCH && startMarker !== null && afterLoadFn !== null ) {
			performance.mark( startMarker );
			mw.loader.using( moduleName, afterLoadFn );
		} else {
			mw.loader.load( moduleName );
		}
		element.removeEventListener( 'focus', requestSearchModule );
	}

	if ( document.activeElement === element ) {
		requestSearchModule();
	} else {
		element.addEventListener( 'focus', requestSearchModule );
	}
}

/**
 * Event callback that shows or hides the loading indicator based on the event type.
 * The loading indicator states are:
 * 1. Show on input event (while user is typing)
 * 2. Hide on focusout event (when user removes focus from the input )
 * 3. Show when input is focused, if it contains a query. (in case user re-focuses on input)
 *
 * @param {Event} event
 */
function renderSearchLoadingIndicator( event ) {

	const form = /** @type {HTMLElement} */ ( event.currentTarget ),
		input = /** @type {HTMLInputElement} */ ( event.target );

	if (
		!( event.currentTarget instanceof HTMLElement ) ||
		!( event.target instanceof HTMLInputElement )
	) {
		return;
	}

	if ( !form.dataset.loadingMsg ) {
		form.dataset.loadingMsg = mw.msg( 'vector-search-loader' );
	}

	if ( event.type === 'input' ) {
		form.classList.add( SEARCH_LOADING_CLASS );

	} else if ( event.type === 'focusout' ) {
		form.classList.remove( SEARCH_LOADING_CLASS );

	} else if ( event.type === 'focusin' && input.value.trim() ) {
		form.classList.add( SEARCH_LOADING_CLASS );
	}
}

/**
 * Attaches or detaches the event listeners responsible for activating
 * the loading indicator.
 *
 * @param {Element} element
 * @param {boolean} attach
 * @param {function(Event): void} eventCallback
 */
function setLoadingIndicatorListeners( element, attach, eventCallback ) {

	/** @type { "addEventListener" | "removeEventListener" } */
	const addOrRemoveListener = ( attach ? 'addEventListener' : 'removeEventListener' );

	[ 'input', 'focusin', 'focusout' ].forEach( function ( eventType ) {
		element[ addOrRemoveListener ]( eventType, eventCallback );
	} );

	if ( !attach ) {
		element.classList.remove( SEARCH_LOADING_CLASS );
	}
}

/**
 * Marks when the lazy load has completed.
 *
 * @param {string} startMarker
 * @param {string} endMarker
 * @param {string} measureMarker
 */
function markLoadEnd( startMarker, endMarker, measureMarker ) {
	if ( performance.getEntriesByName( startMarker ).length ) {
		performance.mark( endMarker );
		performance.measure( measureMarker, startMarker, endMarker );
	}
}

/**
 * Initialize the loading of the search module as well as the loading indicator.
 * Only initialize the loading indicator when not using the core search module.
 *
 * @param {Document} document
 */
function initSearchLoader( document ) {
	const searchBoxes = document.querySelectorAll( '.vector-search-box' );

	// Allow developers to defined $wgVectorSearchApiUrl in LocalSettings to target different APIs
	if ( config.VectorSearchApiUrl ) {
		mw.config.set( 'wgVectorSearchApiUrl', config.VectorSearchApiUrl );
	}

	if ( !searchBoxes.length ) {
		return;
	}

	/**
	 * If we are in a browser that doesn't support ES6 fall back to non-JS version.
	 */
	if ( mw.loader.getState( 'skins.vector.search' ) === null ) {
		document.body.classList.remove(
			'skin-vector-search-vue'
		);
		return;
	}

	Array.prototype.forEach.call( searchBoxes, function ( searchBox ) {
		const searchInner = searchBox.querySelector( 'form > div' ),
			searchInput = searchBox.querySelector( 'input[name="search"]' ),
			clearLoadingIndicators = function () {
				setLoadingIndicatorListeners(
					searchInner,
					false,
					renderSearchLoadingIndicator
				);
			},
			isPrimarySearch = searchInput && searchInput.getAttribute( 'id' ) === 'searchInput';

		if ( !searchInput || !searchInner ) {
			return;
		}
		// Remove tooltips while Vue search is still loading
		searchInput.setAttribute( 'autocomplete', 'off' );
		setLoadingIndicatorListeners( searchInner, true, renderSearchLoadingIndicator );
		loadSearchModule(
			searchInput,
			'skins.vector.search',
			isPrimarySearch ? LOAD_START_MARK : null,
			// Make sure we clearLoadingIndicators so that event listeners are removed.
			// Note, loading Vue.js will remove the element from the DOM.
			isPrimarySearch ? function () {
				markLoadEnd( LOAD_START_MARK, LOAD_END_MARK, LOAD_MEASURE );
				clearLoadingIndicators();
			} : clearLoadingIndicators
		);
	} );
}

module.exports = {
	initSearchLoader: initSearchLoader
};
