const checkboxShift = require( './checkboxShift.js' );
const config = require( './config.json' );
const teleportTarget = require( './teleportTarget.js' );
const enableSearchDialog = require( './enableSearchDialog.js' );
const clearAddressBar = require( './clearAddressBar.js' );

// Break out of framesets
if ( mw.config.get( 'wgBreakFrames' ) ) {
	// Note: In IE < 9 strict comparison to window is non-standard (the standard didn't exist yet)
	// it works only comparing to window.self or window.window (https://stackoverflow.com/q/4850978/319266)
	if ( window.top !== window.self ) {
		// Un-trap us from framesets
		window.top.location.href = location.href;
	}
}

mw.hook( 'wikipage.content' ).add( ( $content ) => {
	const modules = [];

	let $collapsible;
	if ( config.collapsible ) {
		$collapsible = $content.find( '.mw-collapsible' );
		if ( $collapsible.length ) {
			modules.push( 'jquery.makeCollapsible' );
		}
	}

	let $sortable;
	if ( config.sortable ) {
		$sortable = $content.find( 'table.sortable' );
		if ( $sortable.length ) {
			modules.push( 'jquery.tablesorter' );
		}
	}

	if ( modules.length ) {
		// Both modules are preloaded by Skin::getDefaultModules()
		mw.loader.using( modules ).then( () => {
			// For tables that are both sortable and collapsible,
			// it must be made sortable first and collapsible second.
			// This is because jquery.tablesorter stumbles on the
			// elements inserted by jquery.makeCollapsible (T64878)
			if ( $sortable && $sortable.length ) {
				$sortable.tablesorter();
			}
			if ( $collapsible && $collapsible.length ) {
				$collapsible.makeCollapsible();
			}
		} );
	}
	if ( $content[ 0 ] && $content[ 0 ].isConnected === false ) {
		mw.log.warn( 'wikipage.content hook should not be fired on unattached content' );
	}

	checkboxShift( $content.find( 'input[type="checkbox"]:not(.noshiftselect)' ) );
} );

// Add toolbox portlet to toggle all collapsibles if there are any
require( './toggleAllCollapsibles.js' );

// Handle elements outside the wikipage content
$( () => {
	/**
	 * There is a bug on iPad and maybe other browsers where if initial-scale is not set
	 * the page cannot be zoomed. If the initial-scale is set on the server side, this will result
	 * in an unwanted zoom on mobile devices. To avoid this we check innerWidth and set the
	 * initial-scale on the client where needed. The width must be synced with the value in
	 * Skin::initPage.
	 * More information on this bug in [[phab:T311795]].
	 *
	 * @ignore
	 */
	function fixViewportForTabletDevices() {
		const $viewport = $( 'meta[name=viewport]' );
		const content = $viewport.attr( 'content' );
		const scale = window.outerWidth / window.innerWidth;
		// This adjustment is limited to tablet devices. It must be a non-zero value to work.
		// (these values correspond to @min-width-breakpoint-tablet and @min-width-breakpoint-desktop
		// See https://doc.wikimedia.org/codex/main/design-tokens/breakpoint.html
		if ( window.innerWidth >= 640 && window.innerWidth < 1120 &&
			content && !content.includes( 'initial-scale' )
		) {
			// Note:
			// - The `width` value must be equal to @min-width-breakpoint-desktop above
			// - If `initial-scale` value is 1 the font-size adjust feature will not work on iPad
			$viewport.attr( 'content', 'width=1120,initial-scale=' + scale );
		}
	}

	// Add accesskey hints to the tooltips
	$( '[accesskey]' ).updateTooltipAccessKeys();

	const node = document.querySelector( '.mw-indicators' );
	if ( node && node.children.length ) {
		/**
		 * Fired when a page's status indicators are being added to the DOM.
		 *
		 * @event ~'wikipage.indicators'
		 * @memberof Hooks
		 * @param {jQuery} $content jQuery object with the elements of the indicators
		 * @see https://www.mediawiki.org/wiki/Special:MyLanguage/Help:Page_status_indicators
		 */
		mw.hook( 'wikipage.indicators' ).fire( $( node.children ) );
	}

	const $content = $( '#mw-content-text' );
	// Avoid unusable events, and the errors they cause, for custom skins that
	// do not display any content (T259577).
	if ( $content.length ) {
		/**
		 * Fired when wiki content has been added to the DOM.
		 *
		 * This should only be fired after $content has been attached.
		 *
		 * This includes the ready event on a page load (including post-edit loads)
		 * and when content has been previewed with LivePreview.
		 *
		 * @event ~'wikipage.content'
		 * @memberof Hooks
		 * @param {jQuery} $content The most appropriate element containing the content,
		 *   such as #mw-content-text (regular content root) or #wikiPreview (live preview
		 *   root)
		 */
		mw.hook( 'wikipage.content' ).fire( $content );
	}

	let $nodes = $( '.catlinks[data-mw="interface"]' );
	if ( $nodes.length ) {
		/**
		 * Fired when categories are being added to the DOM.
		 *
		 * It is encouraged to fire it before the main DOM is changed (when $content
		 * is still detached).  However, this order is not defined either way, so you
		 * should only rely on $content itself.
		 *
		 * This includes the ready event on a page load (including post-edit loads)
		 * and when content has been previewed with LivePreview.
		 *
		 * @event ~'wikipage.categories'
		 * @memberof Hooks
		 * @param {jQuery} $content The most appropriate element containing the content,
		 *   such as .catlinks
		 */
		mw.hook( 'wikipage.categories' ).fire( $nodes );
	}

	$nodes = $( 'table.diff[data-mw="interface"]' );
	if ( $nodes.length ) {
		/**
		 * Fired when the diff is added to a page containing a diff.
		 *
		 * Similar to the {@link Hooks~'wikipage.content' wikipage.content hook}
		 * $diff may still be detached when the hook is fired.
		 *
		 * @event ~'wikipage.diff'
		 * @memberof Hooks
		 * @param {jQuery} $diff The root element of the MediaWiki diff (`table.diff`).
		 */
		mw.hook( 'wikipage.diff' ).fire( $nodes.eq( 0 ) );
	}

	$( '#t-print a' ).on( 'click', ( e ) => {
		window.print();
		e.preventDefault();
	} );

	const $permanentLink = $( '#t-permalink a' );
	function updatePermanentLinkHash() {
		if ( mw.util.getTargetFromFragment() ) {
			$permanentLink[ 0 ].hash = location.hash;
		} else {
			$permanentLink[ 0 ].hash = '';
		}
	}
	if ( $permanentLink.length ) {
		$( window ).on( 'hashchange', updatePermanentLinkHash );
		updatePermanentLinkHash();
	}

	/**
	 * Fired when a trusted UI element to perform a logout has been activated.
	 *
	 * This will end the user session, and either redirect to the given URL
	 * on success, or queue an error message via {@link mw.notification}.
	 *
	 * @event ~'skin.logout'
	 * @memberof Hooks
	 * @param {string} href Full URL
	 */
	const LOGOUT_EVENT = 'skin.logout';
	function logoutViaPost( href ) {
		mw.notify(
			mw.message( 'logging-out-notify' ),
			{ tag: 'logout', autoHide: false }
		);
		const api = new mw.Api();
		if ( mw.user.isTemp() ) {
			// Indicate to the success page that the user was previously a temporary account, so that the success
			// message can be customised appropriately.
			const url = new URL( href );
			url.searchParams.append( 'wasTempUser', 1 );
			href = url;
		}
		// Allow hooks to extend data that is sent along with the logout request.
		api.prepareExtensibleApiRequest( 'extendLogout' ).then( ( params ) => {
			// Include any additional params set by implementations of the extendLogout hook
			const logoutParams = Object.assign( {}, params, { action: 'logout' } );
			api.postWithToken( 'csrf', logoutParams ).then(
				() => {
					location.href = href;
				},
				( err, data ) => {
					mw.notify(
						api.getErrorMessage( data ),
						{ type: 'error', tag: 'logout', autoHide: false }
					);
				}
			);
		} );
	}

	// Turn logout to a POST action
	mw.hook( LOGOUT_EVENT ).add( logoutViaPost );
	$( config.selectorLogoutLink ).on( 'click', function ( e ) {
		mw.hook( LOGOUT_EVENT ).fire( this.href );
		e.preventDefault();
	} );
	fixViewportForTabletDevices();

	teleportTarget.attach();
} );

/**
 * @private
 * @param {HTMLElement} element
 * @return {boolean} Whether the element is a search input.
 */
function isSearchInput( element ) {
	return element.id === 'searchInput' ||
		element.classList.contains( 'mw-searchInput' );
}

/**
 * Load a given module when a search input is focused.
 *
 * @memberof module:mediawiki.page.ready
 * @param {string} moduleName Name of a module
 */
function loadSearchModule( moduleName ) {
	function requestSearchModule() {
		mw.loader.using( moduleName );
	}

	// Load the module once a search input is focussed.
	function eventListener( e ) {
		if ( e.target && e.target.nodeType === 1 && isSearchInput( e.target ) ) {
			requestSearchModule();

			document.removeEventListener( 'focusin', eventListener );
		}
	}

	// Load the module now if the search input is already focused,
	// because the user started typing before the JavaScript arrived.
	if ( document.activeElement && isSearchInput( document.activeElement ) ) {
		requestSearchModule();
		return;
	}

	document.addEventListener( 'focusin', eventListener );
}

// Skins may decide to disable this behaviour or use an alternative module.
if ( config.search ) {
	loadSearchModule( config.searchModule );
}

try {
	// Load the post-edit notification module if a notification has been scheduled.
	// Use `sessionStorage` directly instead of 'mediawiki.storage' to minimize dependencies.
	if ( sessionStorage.getItem( 'mw-PostEdit' + mw.config.get( 'wgPageName' ) ) ) {
		mw.loader.load( 'mediawiki.action.view.postEdit' );
	}
} catch ( err ) {}

/**
 * @exports mediawiki.page.ready
 */
module.exports = {
	clearAddressBar,
	enableSearchDialog,
	loadSearchModule,
	/** @type {module:mediawiki.page.ready.CheckboxHack} */
	checkboxHack: require( './checkboxHack.js' ),
	/**
	 * A container for displaying elements that overlay the page, such as dialogs.
	 *
	 * @type {HTMLElement}
	 */
	teleportTarget: teleportTarget.target
};
