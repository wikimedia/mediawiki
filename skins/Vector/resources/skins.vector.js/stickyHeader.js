/**
 * Functions and variables to implement sticky header.
 */
const
	initSearchToggle = require( './searchToggle.js' ),
	updateWatchIcon = require( './watchstar.js' ).updateWatchIcon,
	STICKY_HEADER_ID = 'vector-sticky-header',
	STICKY_HEADER_APPENDED_ID = '-sticky-header',
	STICKY_HEADER_APPENDED_PARAM = [ 'wvprov', 'sticky-header' ],
	STICKY_HEADER_VISIBLE_CLASS = 'vector-sticky-header-visible',
	STICKY_HEADER_USER_MENU_CONTAINER_SELECTOR = '.vector-sticky-header-icon-end .vector-user-links',
	FIRST_HEADING_ID = 'firstHeading',
	USER_LINKS_DROPDOWN_ID = 'vector-user-links-dropdown',
	ULS_STICKY_CLASS = 'uls-dialog-sticky',
	ULS_HIDE_CLASS = 'uls-dialog-sticky-hide',
	SEARCH_TOGGLE_SELECTOR = '.vector-sticky-header-search-toggle',
	STICKY_HEADER_EXPERIMENT_NAME = 'vector.sticky_header';

/**
 * Copies attribute from an element to another.
 *
 * @param {Element} from
 * @param {Element} to
 * @param {string} attribute
 */
function copyAttribute( from, to, attribute ) {
	const fromAttr = from.getAttribute( attribute );
	if ( fromAttr ) {
		to.setAttribute( attribute, fromAttr );
	}
}

/**
 * Show the sticky header.
 */
function show() {
	document.body.classList.add( STICKY_HEADER_VISIBLE_CLASS );
	document.body.classList.remove( ULS_HIDE_CLASS );
}

/**
 * Hide the sticky header.
 */
function hide() {
	document.body.classList.remove( STICKY_HEADER_VISIBLE_CLASS );
	document.body.classList.add( ULS_HIDE_CLASS );
}

/**
 * Copies attribute from an element to another.
 *
 * @param {Element} from
 * @param {Element} to
 */
function copyButtonAttributes( from, to ) {
	copyAttribute( from, to, 'href' );
	copyAttribute( from, to, 'title' );
	// Copy button labels
	if ( to.lastElementChild && from.lastElementChild ) {
		to.lastElementChild.innerHTML = from.lastElementChild.textContent || '';
	}
}

/**
 * Suffixes an attribute with a value that indicates it
 * relates to the sticky header to support click tracking instrumentation.
 *
 * @param {Element} node
 * @param {string} attribute
 */
function suffixStickyAttribute( node, attribute ) {
	const value = node.getAttribute( attribute );
	if ( value ) {
		node.setAttribute( attribute, value + STICKY_HEADER_APPENDED_ID );
	}
}

/**
 * Suffixes the href attribute of a node with a value that indicates it
 * relates to the sticky header to support tracking instrumentation.
 *
 * Distinct from suffixStickyAttribute as it's intended to support followed
 * links recording their origin.
 *
 * @param {HTMLAnchorElement} node
 */
function suffixStickyHref( node ) {
	const url = new URL( node.href );
	if ( url && !url.searchParams.has( STICKY_HEADER_APPENDED_PARAM[ 0 ] ) ) {
		url.searchParams.append(
			STICKY_HEADER_APPENDED_PARAM[ 0 ], STICKY_HEADER_APPENDED_PARAM[ 1 ]
		);
		node.href = url.toString();
	}
}

/**
 * Undoes the effect of suffixStickyHref
 *
 * @param {HTMLAnchorElement} node
 */
function unsuffixStickyHref( node ) {
	const url = new URL( node.href );
	url.searchParams.delete( STICKY_HEADER_APPENDED_PARAM[ 0 ] );
	node.href = url.toString();
}

/**
 * Makes a node trackable by our click tracking instrumentation.
 *
 * @param {Element} node
 */
function makeNodeTrackable( node ) {
	suffixStickyAttribute( node, 'id' );
	suffixStickyAttribute( node, 'data-event-name' );
}

/**
 * @param {Element} node
 */
function removeNode( node ) {
	if ( node.parentNode ) {
		node.parentNode.removeChild( node );
	}
}

/**
 * Ensures a sticky header button has the correct attributes
 *
 * @param {Element} watchLink
 * @param {boolean} isWatched The page is watched
 */
function updateStickyWatchlink( watchLink, isWatched ) {
	watchLink.setAttribute( 'data-event-name', isWatched ? 'watch-sticky-header' : 'unwatch-sticky-header' );
}

/**
 * @param {NodeList} nodes
 * @param {string} className
 */
function removeClassFromNodes( nodes, className ) {
	Array.prototype.forEach.call( nodes, function ( node ) {
		node.classList.remove( className );
	} );
}

/**
 * @param {NodeList} nodes
 */
function removeNodes( nodes ) {
	Array.prototype.forEach.call( nodes, function ( node ) {
		node.parentNode.removeChild( node );
	} );
}

/**
 * Callback for watchsar
 *
 * @param {JQuery} $link Watchstar link
 * @param {boolean} isWatched The page is watched
 */
function watchstarCallback( $link, isWatched ) {
	updateStickyWatchlink( /** @type {HTMLAnchorElement} */( $link[ 0 ] ), isWatched );
}

/**
 * Makes sticky header icons functional for modern Vector.
 *
 * @param {Element} header
 * @param {Element|null} history
 * @param {Element|null} talk
 * @param {Element|null} subject
 * @param {Element|null} watch
 */
function prepareIcons( header, history, talk, subject, watch ) {
	const historySticky = header.querySelector( '#ca-history-sticky-header' ),
		talkSticky = header.querySelector( '#ca-talk-sticky-header' ),
		subjectSticky = header.querySelector( '#ca-subject-sticky-header' ),
		watchSticky = header.querySelector( '#ca-watchstar-sticky-header' );

	if ( !historySticky || !talkSticky || !subjectSticky || !watchSticky ) {
		throw new Error( 'Sticky header has unexpected HTML' );
	}

	if ( history ) {
		copyButtonAttributes( history, historySticky );
	} else {
		removeNode( historySticky );
	}

	if ( talk ) {
		copyButtonAttributes( talk, talkSticky );
	} else {
		removeNode( talkSticky );
	}

	if ( subject ) {
		copyButtonAttributes( subject, subjectSticky );
	} else {
		removeNode( subjectSticky );
	}

	if ( watch && watch.parentNode instanceof Element ) {
		const watchContainer = watch.parentNode;
		const isTemporaryWatch = watchContainer.classList.contains( 'mw-watchlink-temp' );
		const isWatched = isTemporaryWatch || watchContainer.getAttribute( 'id' ) === 'ca-unwatch';
		const watchIcon = /** @type {HTMLElement} */ ( watchSticky.querySelector( '.mw-ui-icon' ) );

		// Initialize sticky watchlink
		copyButtonAttributes( watch, watchSticky );
		updateWatchIcon( watchIcon, isWatched, isTemporaryWatch ? '' : 'infinity' );
		updateStickyWatchlink( watchSticky, isWatched );

		const watchLib = require( /** @type {string} */( 'mediawiki.page.watch.ajax' ) );
		watchLib.watchstar( $( watchSticky ), mw.config.get( 'wgRelevantPageName' ), watchstarCallback );
	} else {
		removeNode( watchSticky );
	}
}

/**
 * Render sticky header edit or protected page icons for modern Vector.
 *
 * @param {Element} header
 * @param {Element|null} primaryEdit
 * @param {boolean} isProtected
 * @param {Element|null} secondaryEdit
 * @param {Element|null} addSection
 * @param {Function} disableStickyHeader function to call to disable the sticky
 *  header.
 */
function prepareEditIcons(
	header,
	primaryEdit,
	isProtected,
	secondaryEdit,
	addSection,
	disableStickyHeader
) {
	/**
	 * @param {string} selector
	 * @return {HTMLAnchorElement|null}
	 */
	const getAnchorElement = ( selector ) => header.querySelector( selector );
	const
		primaryEditSticky = getAnchorElement(
			'#ca-ve-edit-sticky-header'
		),
		protectedSticky = getAnchorElement(
			'#ca-viewsource-sticky-header'
		),
		wikitextSticky = getAnchorElement(
			'#ca-edit-sticky-header'
		),
		addSectionSticky = getAnchorElement(
			'#ca-addsection-sticky-header'
		);

	if ( addSectionSticky ) {
		if ( addSection ) {
			copyButtonAttributes( addSection, addSectionSticky );
			suffixStickyHref( addSectionSticky );
		} else {
			removeNode( addSectionSticky );
		}
	}

	// If no primary edit icon is present the feature is disabled.
	if ( !primaryEditSticky || !wikitextSticky || !protectedSticky ) {
		return;
	}

	if ( !primaryEdit ) {
		removeNode( protectedSticky );
		removeNode( wikitextSticky );
		removeNode( primaryEditSticky );
		return;
	} else if ( isProtected ) {
		removeNode( wikitextSticky );
		removeNode( primaryEditSticky );
		copyButtonAttributes( primaryEdit, protectedSticky );
		suffixStickyHref( protectedSticky );
	} else {
		removeNode( protectedSticky );
		copyButtonAttributes( primaryEdit, primaryEditSticky );
		suffixStickyHref( primaryEditSticky );

		primaryEditSticky.addEventListener( 'click', function ( ev ) {
			const target = ev.target;
			const $ve = $( primaryEdit );
			if ( target && $ve.length ) {
				const link = /** @type {HTMLAnchorElement} */( $ve[ 0 ] );
				const event = $.Event( 'click' );
				suffixStickyHref( link );
				$ve.trigger( event );
				unsuffixStickyHref( link );
				// The link has been progressively enhanced.
				if ( event.isDefaultPrevented() ) {
					disableStickyHeader();
					ev.preventDefault();
				}
			}
		} );
		if ( secondaryEdit ) {
			copyButtonAttributes( secondaryEdit, wikitextSticky );
			suffixStickyHref( wikitextSticky );
			wikitextSticky.addEventListener( 'click', function ( ev ) {
				const target = ev.target;
				if ( target ) {
					const $edit = $( secondaryEdit );
					if ( $edit.length ) {
						const link = /** @type {HTMLAnchorElement} */( $edit[ 0 ] );
						const event = $.Event( 'click' );
						suffixStickyHref( link );
						$edit.trigger( event );
						unsuffixStickyHref( link );
						// The link has been progressively enhanced.
						if ( event.isDefaultPrevented() ) {
							disableStickyHeader();
							ev.preventDefault();
						}
					}
				}
			} );
		} else {
			removeNode( wikitextSticky );
		}
	}
}

/**
 * Check if element is in viewport.
 *
 * @param {Element} element
 * @return {boolean}
 */
function isInViewport( element ) {
	const rect = element.getBoundingClientRect();
	return (
		rect.top >= 0 &&
		rect.left >= 0 &&
		rect.bottom <= ( window.innerHeight || document.documentElement.clientHeight ) &&
		rect.right <= ( window.innerWidth || document.documentElement.clientWidth )
	);
}

/**
 * Add hooks for sticky header when Visual Editor is used.
 *
 * @param {Element} stickyIntersection intersection element
 * @param {IntersectionObserver} observer
 */
function addVisualEditorHooks( stickyIntersection, observer ) {
	// When Visual Editor is activated, hide the sticky header.
	mw.hook( 've.activationStart' ).add( () => {
		hide();
		observer.unobserve( stickyIntersection );
	} );

	// When Visual Editor is deactivated (by clicking "Read" tab at top of page), show sticky header
	// by re-triggering the observer.
	mw.hook( 've.deactivationComplete' ).add( () => {
		// Wait for the next repaint or we might calculate that
		// sticky header should not be visible (T299114)
		requestAnimationFrame( () => {
			observer.observe( stickyIntersection );
		} );
	} );

	// After saving edits, re-apply the sticky header if the target is not in the viewport.
	mw.hook( 'postEdit.afterRemoval' ).add( () => {
		if ( !isInViewport( stickyIntersection ) ) {
			show();
			observer.observe( stickyIntersection );
		}
	} );
}

/**
 * Clones the existing user menu (excluding items added by gadgets) and adds to the sticky header
 * ensuring it is not focusable and that elements are no longer collapsible (since the sticky header
 * itself collapses at low resolutions) and updates click tracking event names. Also wires up the
 * logout link so it works in a single click.
 *
 * @param {Element} userLinksDropdown
 * @return {Element} cloned userLinksDropdown
 */
function prepareUserLinksDropdown( userLinksDropdown ) {
	const
		// Type declaration needed because of https://github.com/Microsoft/TypeScript/issues/3734#issuecomment-118934518
		userLinksDropdownClone = /** @type {Element} */( userLinksDropdown.cloneNode( true ) ),
		userLinksDropdownStickyElementsWithIds = userLinksDropdownClone.querySelectorAll( '[ id ], [ data-event-name ]' );
	// Update all ids of the cloned user menu to make them unique.
	makeNodeTrackable( userLinksDropdownClone );
	userLinksDropdownStickyElementsWithIds.forEach( makeNodeTrackable );
	// Remove portlet links added by gadgets using mw.util.addPortletLink, T291426
	removeNodes( userLinksDropdownClone.querySelectorAll( '.mw-list-item-js' ) );
	removeClassFromNodes(
		userLinksDropdownClone.querySelectorAll( '.user-links-collapsible-item' ),
		'user-links-collapsible-item'
	);
	// Prevents user menu from being focusable, T290201
	const userLinksDropdownCheckbox = userLinksDropdownClone.querySelector( 'input' );
	if ( userLinksDropdownCheckbox ) {
		userLinksDropdownCheckbox.setAttribute( 'tabindex', '-1' );
	}

	// Make the logout go through the API (T324638)
	const logoutLink = /** @type {HTMLAnchorElement} */( userLinksDropdownClone.querySelector( '#pt-logout-sticky-header a' ) );
	if ( logoutLink ) {
		logoutLink.addEventListener( 'click', function ( ev ) {
			ev.preventDefault();
			mw.hook( 'skin.logout' ).fire( logoutLink.href );
		} );
	}
	return userLinksDropdownClone;
}

/**
 * Makes sticky header functional for modern Vector.
 *
 * @param {Element} header
 * @param {Element} userLinksDropdown
 * @param {IntersectionObserver} stickyObserver
 * @param {Element} stickyIntersection
 */
function makeStickyHeaderFunctional(
	header,
	userLinksDropdown,
	stickyObserver,
	stickyIntersection
) {
	const userLinksDropdownStickyContainer = document.querySelector(
		STICKY_HEADER_USER_MENU_CONTAINER_SELECTOR
	);

	// Clone the updated user menu to the sticky header.
	if ( userLinksDropdownStickyContainer ) {
		const clonedUserLinksDropdown = prepareUserLinksDropdown( userLinksDropdown );
		userLinksDropdownStickyContainer.appendChild( clonedUserLinksDropdown );
	}

	let namespaceName = mw.config.get( 'wgCanonicalNamespace' );
	const namespaceNumber = mw.config.get( 'wgNamespaceNumber' );
	if ( namespaceNumber >= 0 && namespaceNumber % 2 === 1 ) {
		// Remove '_talk' to get subject namespace
		namespaceName = namespaceName.slice( 0, -5 );
	}
	// Title::getNamespaceKey()
	let namespaceKey = namespaceName.toLowerCase() || 'main';
	if ( namespaceKey === 'file' ) {
		namespaceKey = 'image';
	}
	const namespaceTabId = 'ca-nstab-' + namespaceKey;

	prepareIcons( header,
		document.querySelector( '#ca-history a' ),
		document.querySelector( '#ca-talk:not( .selected ) a' ),
		document.querySelector( '#' + namespaceTabId + ':not( .selected ) a' ),
		document.querySelector( '#ca-watch a, #ca-unwatch a' )
	);

	const veEdit = document.querySelector( '#ca-ve-edit a' );
	const ceEdit = document.querySelector( '#ca-edit a' );
	const protectedEdit = document.querySelector( '#ca-viewsource a' );
	const isProtected = !!protectedEdit;
	// For sticky header edit A/B test, conditionally remove the edit icon by setting null.
	// Otherwise, use either protected, ve, or source edit (in that order).
	const primaryEdit = protectedEdit || veEdit || ceEdit;
	const secondaryEdit = veEdit ? ceEdit : null;
	const disableStickyHeader = () => {
		document.body.classList.remove( STICKY_HEADER_VISIBLE_CLASS );
		stickyObserver.unobserve( stickyIntersection );
	};
	// When VectorPromoteAddTopic is set, #ca-addsection is the link itself
	const addSection = document.querySelector( '#ca-addsection a' ) || document.querySelector( 'a#ca-addsection' );

	prepareEditIcons(
		header,
		primaryEdit,
		isProtected,
		secondaryEdit,
		addSection,
		disableStickyHeader
	);

	stickyObserver.observe( stickyIntersection );
}

/**
 * @param {Element} header
 */
function setupSearchIfNeeded( header ) {
	const
		searchToggle = header.querySelector( SEARCH_TOGGLE_SELECTOR );

	if ( !document.body.classList.contains( 'skin-vector-search-vue' ) ) {
		return;
	}

	if ( searchToggle ) {
		initSearchToggle( searchToggle );
	}
}

/**
 * Determines if sticky header should be visible for a given namespace.
 *
 * @param {number} namespaceNumber
 * @return {boolean}
 */
function isAllowedNamespace( namespaceNumber ) {
	// Corresponds to Main, User, Wikipedia, Template, Help, Category, Portal, Module.
	const allowedNamespaceNumbers = [ 0, 2, 4, 10, 12, 14, 100, 828 ];
	// Also allow on all talk namespaces (compare NamespaceInfo::isTalk()).
	const isAllowedTalk = namespaceNumber > 0 && namespaceNumber % 2 !== 0;
	return isAllowedTalk || allowedNamespaceNumbers.indexOf( namespaceNumber ) > -1;
}

/**
 * Determines if sticky header should be visible for a given action.
 *
 * @param {string} action
 * @return {boolean}
 */
function isAllowedAction( action ) {
	const disallowedActions = [ 'history', 'edit' ],
		hasDiffId = mw.config.get( 'wgDiffOldId' );
	return disallowedActions.indexOf( action ) < 0 && !hasDiffId;
}

/**
 * @typedef {Object} StickyHeaderProps
 * @property {Element} header
 * @property {Element} userLinksDropdown
 * @property {IntersectionObserver} observer
 * @property {Element} stickyIntersection
 */

/**
 * @param {StickyHeaderProps} props
 */
function initStickyHeader( props ) {
	makeStickyHeaderFunctional(
		props.header,
		props.userLinksDropdown,
		props.observer,
		props.stickyIntersection
	);

	setupSearchIfNeeded( props.header );
	addVisualEditorHooks( props.stickyIntersection, props.observer );

	// Make sure ULS outside sticky header disables the sticky header behaviour.
	mw.hook( 'mw.uls.compact_language_links.open' ).add( function ( $trigger ) {
		const trigger = $trigger[ 0 ];
		if ( trigger.id !== 'p-lang-btn-sticky-header' ) {
			const bodyClassList = document.body.classList;
			bodyClassList.remove( ULS_HIDE_CLASS );
			bodyClassList.remove( ULS_STICKY_CLASS );
		}
	} );

	// Make sure ULS dialog is sticky.
	const langBtn = props.header.querySelector( '#p-lang-btn-sticky-header' );
	if ( langBtn ) {
		langBtn.addEventListener( 'click', function () {
			const bodyClassList = document.body.classList;
			bodyClassList.remove( ULS_HIDE_CLASS );
			bodyClassList.add( ULS_STICKY_CLASS );
		} );
	}
}

module.exports = {
	show,
	hide,
	prepareUserLinksDropdown,
	isAllowedNamespace,
	isAllowedAction,
	initStickyHeader,
	STICKY_HEADER_ID,
	FIRST_HEADING_ID,
	USER_LINKS_DROPDOWN_ID,
	STICKY_HEADER_EXPERIMENT_NAME
};
