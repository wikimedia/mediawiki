/** @interface CheckboxHack */

const
	checkboxHack = /** @type {CheckboxHack} */ require( /** @type {string} */( 'mediawiki.page.ready' ) ).checkboxHack,
	CHECKBOX_HACK_CONTAINER_SELECTOR = '.vector-menu-dropdown',
	CHECKBOX_HACK_CHECKBOX_SELECTOR = '.vector-menu-checkbox',
	CHECKBOX_HACK_BUTTON_SELECTOR = '.vector-menu-heading',
	CHECKBOX_HACK_TARGET_SELECTOR = '.vector-menu-content';

/**
 * Enhance dropdownMenu functionality and accessibility using core's checkboxHack.
 */
function bind() {
	// Search for all dropdown containers using the CHECKBOX_HACK_CONTAINER_SELECTOR.
	const containers = document.querySelectorAll( CHECKBOX_HACK_CONTAINER_SELECTOR );

	Array.prototype.forEach.call( containers, function ( container ) {
		const
			checkbox = container.querySelector( CHECKBOX_HACK_CHECKBOX_SELECTOR ),
			button = container.querySelector( CHECKBOX_HACK_BUTTON_SELECTOR ),
			target = container.querySelector( CHECKBOX_HACK_TARGET_SELECTOR );

		if ( !( checkbox && button && target ) ) {
			return;
		}

		checkboxHack.bind( window, checkbox, button, target );
	} );
}

/**
 * Create an icon element to be appended inside the anchor tag.
 *
 * @param {HTMLElement|null} menuElement
 * @param {HTMLElement|null} parentElement
 * @param {string|null} id
 *
 * @return {HTMLElement|undefined}
 */
function createIconElement( menuElement, parentElement, id ) {
	// Only the p-personal menu in the user links dropdown supports icons
	const isIconCapable = menuElement &&
		[
			'p-personal',
			'p-personal-sticky-header'
		].indexOf( menuElement.getAttribute( 'id' ) || 'p-unknown' ) > -1;

	if ( !isIconCapable || !parentElement ) {
		return;
	}

	const iconElement = document.createElement( 'span' );
	iconElement.classList.add( 'mw-ui-icon' );

	if ( id ) {
		// The following class allows gadgets developers to style or hide an icon.
		// * mw-ui-icon-vector-gadget-<id>
		// The class is considered stable and should not be removed without
		// a #user-notice.
		iconElement.classList.add( 'mw-ui-icon-vector-gadget-' + id );
	}

	return iconElement;
}

/**
 * Calculate the available width for adding links in the veiws menu,
 * i.e. the remaining space in the toolbar between the right-navigation
 * and left-navigation elements.
 *
 * @return {number} remaining available pixels in page toolbar or Zero
 *                  if remaining space is negative.
 */
function getAvailableViewMenuWidth() {
	const
		// Vector toolbar containing namespace, views, more menu etc.
		toolbar = document.querySelector( '.vector-page-toolbar-container' ),
		// Assumes all left-side menus are wrapped in a single nav element.
		// Need to get child width since this node is flex-grow: 1;
		leftToolbarItems = document.querySelector( '#left-navigation > nav' ),
		// Right side elements are flex-grow:0 so top-level width is sufficient.
		rightToolbarItems = document.getElementById( 'right-navigation' );

	// Views menu collapses into "more" menu at this resolution.
	// Move the link from views to actions menu in this situation.
	if ( window.innerWidth < 720 ) {
		return 0;
	}

	// If any of our assumption about the DOM are wrong, return 0
	// in order to place the link in a known menu instead.
	if ( !( toolbar && leftToolbarItems && rightToolbarItems ) ) {
		return 0;
	}

	// returning zero instead of negative number makes boolean conversion easier.
	return Math.max( 0,
		toolbar.clientWidth - leftToolbarItems.clientWidth - rightToolbarItems.clientWidth
	);
}

const /** @type {Array<HTMLElement>} */handledLinks = [];

/**
 * Adds icon placeholder for gadgets to use.
 *
 * @typedef {Object} PortletLinkData
 * @property {string|null} id
 */
/**
 * @param {HTMLElement} item
 * @param {PortletLinkData} data
 */
function addPortletLinkHandler( item, data ) {
	const linkIsHandled = handledLinks.indexOf( item );
	let iconElement;

	if ( linkIsHandled >= 0 ) {
		return;
	} else {
		handledLinks.push( item );
	}

	// assign variables after early return.
	const link = item.querySelector( 'a' );
	const menuElement = /** @type {HTMLElement} */(
		item.closest( '.vector-menu' )
	);
	if ( !menuElement ) {
		return;
	}

	if ( data.id ) {
		iconElement = createIconElement( menuElement, link, data.id );
	}

	// The views menu has limited space so we need to decide whether there is space
	// to accommodate the new item and if not to redirect to the more dropdown.
	if ( menuElement.id === 'p-views' ) {
		const availableWidth = getAvailableViewMenuWidth();
		const moreDropdown = document.querySelector( '#p-cactions ul' );

		if ( moreDropdown && !availableWidth ) {
			moreDropdown.appendChild( item );
			// reveal if hidden
			mw.util.showPortlet( 'p-cactions' );
		}
	}

	if ( link && iconElement ) {
		link.prepend( iconElement );
	}
}

// Enhance previously added items.
Array.prototype.forEach.call(
	document.querySelectorAll( '.mw-list-item-js' ),
	function ( item ) {
		addPortletLinkHandler( item, {
			id: item.getAttribute( 'id' )
		} );
	}
);

mw.hook( 'util.addPortletLink' ).add( addPortletLinkHandler );

module.exports = {
	dropdownMenus: function dropdownMenus() { bind(); },
	addPortletLinkHandler: addPortletLinkHandler
};
