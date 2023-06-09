const
	HEADER_CONTAINER_CLASS = 'vector-header-container',
	SEARCH_BOX_SELECTOR = '.vector-search-box',
	SEARCH_VISIBLE_CLASS = 'vector-header-search-toggled';

/**
 * Binds event handlers necessary for the searchBox to disappear when the user
 * clicks outside the searchBox.
 *
 * @param {HTMLElement} searchBox
 * @param {HTMLElement} header
 */
function bindSearchBoxHandler( searchBox, header ) {
	/**
	 * @param {Event} ev
	 * @ignore
	 */
	const clickHandler = ( ev ) => {
		if (
			ev.target instanceof HTMLElement &&
			// Check if the click target was a suggestion link. Codex clears the
			// suggestion elements from the DOM when a suggestion is clicked so we
			// can't test if the suggestion is a child of the searchBox.
			//
			// Note: The .closest API is feature detected in `initSearchToggle`.
			!ev.target.closest( '.cdx-typeahead-search .cdx-menu-item__content' ) &&
			!searchBox.contains( ev.target )
		) {
			header.classList.remove( SEARCH_VISIBLE_CLASS );

			document.removeEventListener( 'click', clickHandler );
		}
	};

	document.addEventListener( 'click', clickHandler );
}

/**
 * Binds event handlers necessary for the searchBox to show when the toggle is
 * clicked.
 *
 * @param {HTMLElement} searchBox
 * @param {HTMLElement} header
 * @param {Element} searchToggle
 */
function bindToggleClickHandler( searchBox, header, searchToggle ) {
	/**
	 * @param {Event} ev
	 * @ignore
	 */
	const handler = ( ev ) => {
		// The toggle is an anchor element. Prevent the browser from navigating away
		// from the page when clicked.
		ev.preventDefault();

		header.classList.add( SEARCH_VISIBLE_CLASS );

		// Defer binding the search box handler until after the event bubbles to the
		// top of the document so that the handler isn't called when the user clicks
		// the search toggle. Event bubbled callbacks execute within the same task
		// in the event loop.
		//
		// Also, defer focusing the input to another task in the event loop. At the time
		// of this writing, Safari 14.0.3 has trouble changing the visibility of the
		// element and focusing the input within the same task.
		setTimeout( () => {
			bindSearchBoxHandler( searchBox, header );

			const searchInput = /** @type {HTMLInputElement|null} */ ( searchBox.querySelector( 'input[type="search"]' ) );

			if ( searchInput ) {
				const beforeScrollX = window.scrollX;
				const beforeScrollY = window.scrollY;
				searchInput.focus();
				// For some reason, Safari 14,15 tends to undesirably change the scroll
				// position of `input` elements inside fixed position elements.
				// While an Internet search suggests similar problems with mobile Safari
				// it didn't yield any results for desktop Safari.
				// This line resets any unexpected scrolling that occurred while the
				// input received focus.
				// If you are in the future with a modern version of Safari, where 14 and 15
				// receive a low amount of page views, please reference T297636 and test
				// to see whether this line of code can be removed.
				// Additionally, these lines might become unnecessary when/if Safari
				// supports the `preventScroll` focus option [1] in the future:
				// https://developer.mozilla.org/en-US/docs/Web/API/HTMLElement/focus#parameters
				if ( beforeScrollX !== undefined && beforeScrollY !== undefined ) {
					window.scroll( beforeScrollX, beforeScrollY );
				}
			}
		} );
	};

	searchToggle.addEventListener( 'click', handler );
}

/**
 * Enables search toggling behavior in a header given a toggle element (e.g.
 * search icon).  When the toggle element is clicked, a class,
 * `SEARCH_VISIBLE_CLASS`, will be applied to a header matching the selector
 * `HEADER_SELECTOR` and the input inside the element, SEARCH_BOX_SELECTOR, will
 * be focused.  This class can be used in CSS to show/hide the necessary
 * elements. When the user clicks outside of SEARCH_BOX_SELECTOR, the class will
 * be removed.
 *
 * @param {HTMLElement|Element} searchToggle
 */
module.exports = function initSearchToggle( searchToggle ) {
	const headerContainer =
		/** @type {HTMLElement|null} */ ( searchToggle.closest( `.${HEADER_CONTAINER_CLASS}` ) );
	const header =
		/** @type {HTMLElement|null} */ ( headerContainer && headerContainer.firstElementChild );

	if ( !header ) {
		return;
	}

	const searchBox =
	/** @type {HTMLElement|null} */ ( header.querySelector( SEARCH_BOX_SELECTOR ) );

	if ( !searchBox ) {
		return;
	}

	bindToggleClickHandler( searchBox, header, searchToggle );
};
