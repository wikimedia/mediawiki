const features = require( './features.js' );
const PINNED_HEADER_CLASS = 'vector-pinnable-header-pinned';
const UNPINNED_HEADER_CLASS = 'vector-pinnable-header-unpinned';
const popupNotification = require( './popupNotification.js' );

/**
 * Callback for matchMedia listener that overrides the pinnable header's stored state
 * at a certain breakpoint and forces it to unpin.
 * Usage of 'e.matches' assumes a `max-width` not `min-width` media query.
 *
 * @param {HTMLElement} header
 * @param {MediaQueryList|MediaQueryListEvent} e
 */
function disablePinningAtBreakpoint( header, e ) {
	const {
		pinnableElementId,
		pinnedContainerId,
		unpinnedContainerId,
		featureName
	} = header.dataset;
	const savedPinnedState = JSON.parse( header.dataset.savedPinnedState || 'false' );

	// (typescript null check)
	if ( !( pinnableElementId && unpinnedContainerId && pinnedContainerId && featureName ) ) {
		return;
	}

	if ( e.matches && savedPinnedState === true ) {
		features.toggleDocClasses( featureName, false );
		movePinnableElement( pinnableElementId, unpinnedContainerId );
	}

	if ( !e.matches && savedPinnedState === true ) {
		features.toggleDocClasses( featureName, true );
		movePinnableElement( pinnableElementId, pinnedContainerId );
	}
}

/**
 * Saves the persistent pinnable state in the element's dataset
 * so that it can be overridden at lower resolutions and the
 * reverted to at wider resolutions.
 *
 * This is not necessarily the elements current state, but it
 * seeks to represent the state of the saved user preference.
 *
 * @param {HTMLElement} header
 */
function setSavedPinnableState( header ) {
	header.dataset.savedPinnedState = String( isPinned( header ) );
}

/**
 * Toggle classes on the body and pinnable element
 *
 * @param {HTMLElement} header pinnable element
 */
function togglePinnableClasses( header ) {
	const featureName = /** @type {string} */ ( header.dataset.featureName );

	// Leverage features.js to toggle the body classes and persist the state
	// for logged-in users.
	features.toggle( featureName );

	// Toggle pinned class
	header.classList.toggle( PINNED_HEADER_CLASS );
	header.classList.toggle( UNPINNED_HEADER_CLASS );
}

/**
 * Create the indicators for the pinnable element
 *
 * @param {string} pinnableElementId
 */
function addPinnableElementIndicator( pinnableElementId ) {
	const dropdownSelector = document.querySelector( `#${pinnableElementId}-dropdown` );
	const container = dropdownSelector && dropdownSelector.parentElement;
	if ( container ) {
		// Possible messages include:
		// * vector-page-tools-unpinned-popup
		// * vector-main-menu-unpinned-popup
		const message = mw.msg( `${pinnableElementId}-unpinned-popup` );
		popupNotification.add( container, message, pinnableElementId )
			.then( ( popupWidget ) => {
				if ( popupWidget ) {
					popupNotification.show( popupWidget );
				}
			} );
	}
}

/**
 * Event handler that toggles the pinnable elements pinned state.
 * Also moves the pinned element when those params are provided
 * (via data attributes).
 *
 * @param {HTMLElement} header PinnableHeader element.
 */
function pinnableElementClickHandler( header ) {
	const {
		pinnableElementId,
		pinnedContainerId,
		unpinnedContainerId
	} = header.dataset;

	togglePinnableClasses( header );

	const isPinnedElement = isPinned( header );
	// Optional functionality of moving the pinnable element in the DOM
	// to different containers based on it's pinned status
	if ( pinnableElementId && pinnedContainerId && unpinnedContainerId ) {
		setSavedPinnableState( header );
		const newContainerId = isPinnedElement ? pinnedContainerId : unpinnedContainerId;
		movePinnableElement( pinnableElementId, newContainerId );
		setFocusAfterToggle( pinnableElementId );
		if ( !isPinnedElement ) {
			addPinnableElementIndicator( pinnableElementId );
		}
	}
}

/**
 * Sets focus on the correct toggle button depending on the pinned state.
 * Also opens the dropdown containing the unpinned element.
 *
 * @param {string} pinnableElementId
 */
function setFocusAfterToggle( pinnableElementId ) {
	let focusElement;
	const pinnableElement = document.getElementById( pinnableElementId );
	const header = /** @type {HTMLElement|null} */ ( pinnableElement && pinnableElement.querySelector( '.vector-pinnable-header' ) );
	if ( !pinnableElement || !header ) {
		return;
	}
	if ( isPinned( header ) ) {
		focusElement = /** @type {HTMLElement|null} */ ( pinnableElement.querySelector( '.vector-pinnable-header-unpin-button' ) );
	} else {
		const dropdown = pinnableElement.closest( '.vector-dropdown' );
		focusElement = /** @type {HTMLInputElement|null} */ ( dropdown && dropdown.querySelector( '.vector-menu-checkbox' ) );
	}
	if ( focusElement ) {
		focusElement.focus();
	}
}

/**
 * Binds all the toggle buttons in a pinnableElement
 * to the click handler that enables pinnability.
 *
 * @param {HTMLElement} header
 */
function bindPinnableToggleButtons( header ) {
	const toggleButtons = header.querySelectorAll( '.vector-pinnable-header-toggle-button' );
	toggleButtons.forEach( function ( button ) {
		button.addEventListener( 'click', pinnableElementClickHandler.bind( null, header ) );
	} );
}

/**
 * Binds pinnable breakpoint to allow automatic unpinning
 * of pinnable elements with pinnedContainerId and unpinnedContainerId defined
 *
 * @param {HTMLElement} header
 */
function bindPinnableBreakpoint( header ) {
	const { pinnedContainerId, unpinnedContainerId } = header.dataset;
	if ( !unpinnedContainerId || !pinnedContainerId ) {
		return;
	}

	const pinnableBreakpoint = window.matchMedia( '(max-width: 999px)' );
	// Set saved pinned state for narrow breakpoint behaviour.
	setSavedPinnableState( header );
	// Check the breakpoint in case an override is needed on pageload.
	disablePinningAtBreakpoint( header, pinnableBreakpoint );

	// Add match media handler.
	if ( pinnableBreakpoint.addEventListener ) {
		pinnableBreakpoint.addEventListener( 'change', disablePinningAtBreakpoint.bind( null, header ) );
	} else {
		// Before Safari 14, MediaQueryList is based on EventTarget,
		// so you must use addListener() and removeListener() to observe media query lists.
		pinnableBreakpoint.addListener( disablePinningAtBreakpoint.bind( null, header ) );
	}
}

/**
 * @param {HTMLElement} header
 * @return {boolean} Returns true if the element is pinned and false otherwise.
 */
function isPinned( header ) {
	const featureName = /** @type {string} */ ( header.dataset.featureName );
	return features.isEnabled( featureName );
}

/**
 * @param {string} pinnableElementId
 * @param {string} newContainerId
 */
function movePinnableElement( pinnableElementId, newContainerId ) {
	const pinnableElem = document.getElementById( pinnableElementId );
	const newContainer = document.getElementById( newContainerId );
	const currContainer = /** @type {HTMLElement} */ ( pinnableElem && pinnableElem.parentElement );

	if ( !pinnableElem || !newContainer || !currContainer ) {
		return;
	}

	// Avoid moving element if unnecessary
	if ( currContainer.id !== newContainerId ) {
		newContainer.insertAdjacentElement( 'beforeend', pinnableElem );
	}

	popupNotification.hideAll();
}

function initPinnableElement() {
	const pinnableHeader = /** @type {NodeListOf<HTMLElement>} */ ( document.querySelectorAll( '.vector-pinnable-header' ) );
	pinnableHeader.forEach( ( header ) => {
		if ( header.dataset.featureName && header.dataset.pinnableElementId ) {
			bindPinnableToggleButtons( header );
			bindPinnableBreakpoint( header );
		}
	} );
}

module.exports = {
	initPinnableElement,
	movePinnableElement,
	setFocusAfterToggle,
	isPinned,
	PINNED_HEADER_CLASS,
	UNPINNED_HEADER_CLASS
};
