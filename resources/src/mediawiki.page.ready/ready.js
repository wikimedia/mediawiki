var checkboxShift = require( './checkboxShift.js' );
var config = require( './config.json' );

// Break out of framesets
if ( mw.config.get( 'wgBreakFrames' ) ) {
	// Note: In IE < 9 strict comparison to window is non-standard (the standard didn't exist yet)
	// it works only comparing to window.self or window.window (https://stackoverflow.com/q/4850978/319266)
	if ( window.top !== window.self ) {
		// Un-trap us from framesets
		window.top.location.href = location.href;
	}
}

mw.hook( 'wikipage.content' ).add( function ( $content ) {
	var modules = [];

	var $collapsible;
	if ( config.collapsible ) {
		$collapsible = $content.find( '.mw-collapsible' );
		if ( $collapsible.length ) {
			modules.push( 'jquery.makeCollapsible' );
		}
	}

	var $sortable;
	if ( config.sortable ) {
		$sortable = $content.find( 'table.sortable' );
		if ( $sortable.length ) {
			modules.push( 'jquery.tablesorter' );
		}
	}

	if ( modules.length ) {
		// Both modules are preloaded by Skin::getDefaultModules()
		mw.loader.using( modules ).then( function () {
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

	checkboxShift( $content.find( 'input[type="checkbox"]:not(.noshiftselect)' ) );
} );

// Handle elements outside the wikipage content
$( function () {
	/**
	 * There is a bug on iPad and maybe other browsers where if initial-scale is not set
	 * the page cannot be zoomed. If the initial-scale is set on the server side, this will result
	 * in an unwanted zoom on mobile devices. To avoid this we check innerWidth and set the initial-scale
	 * on the client where needed. The width must be synced with the value in Skin::initPage.
	 * More information on this bug in [[phab:T311795]].
	 * @ignore
	 */
	function fixViewportForTabletDevices() {
		var $viewport = $( 'meta[name=viewport]' );
		var content = $viewport.attr( 'content' );
		var scale = window.outerWidth / window.innerWidth;
		// This adjustment is limited to tablet devices. It must be a non-zero value to work.
		// (these values correspond to @width-breakpoint-tablet and @width-breakpoint-desktop
		if ( window.innerWidth >= 720 && window.innerWidth <= 1000 && content.indexOf( 'initial-scale' ) === -1 ) {
			// Note: If the value is 1 the font-size adjust feature will not work on iPad
			$viewport.attr( 'content', 'width=1000,initial-scale=' + scale );
		}
	}

	// Add accesskey hints to the tooltips
	$( '[accesskey]' ).updateTooltipAccessKeys();

	var node = document.querySelector( '.mw-indicators' );
	if ( node && node.children.length ) {
		/**
		 * Fired when indicators are being added to the DOM
		 *
		 * @event wikipage_indicators
		 * @member mw.hook
		 * @param {jQuery} $content jQuery object with the elements of the indicators
		 */
		mw.hook( 'wikipage.indicators' ).fire( $( node.children ) );
	}

	var $content = $( '#mw-content-text' );
	// Avoid unusable events, and the errors they cause, for custom skins that
	// do not display any content (T259577).
	if ( $content.length ) {
		/**
		 * Fired when wiki content is being added to the DOM
		 *
		 * It is encouraged to fire it before the main DOM is changed (when $content
		 * is still detached).  However, this order is not defined either way, so you
		 * should only rely on $content itself.
		 *
		 * This includes the ready event on a page load (including post-edit loads)
		 * and when content has been previewed with LivePreview.
		 *
		 * @event wikipage_content
		 * @member mw.hook
		 * @param {jQuery} $content The most appropriate element containing the content,
		 *   such as #mw-content-text (regular content root) or #wikiPreview (live preview
		 *   root)
		 */
		mw.hook( 'wikipage.content' ).fire( $content );
	}

	var $nodes = $( '.catlinks[data-mw="interface"]' );
	if ( $nodes.length ) {
		/**
		 * Fired when categories are being added to the DOM
		 *
		 * It is encouraged to fire it before the main DOM is changed (when $content
		 * is still detached).  However, this order is not defined either way, so you
		 * should only rely on $content itself.
		 *
		 * This includes the ready event on a page load (including post-edit loads)
		 * and when content has been previewed with LivePreview.
		 *
		 * @event wikipage_categories
		 * @member mw.hook
		 * @param {jQuery} $content The most appropriate element containing the content,
		 *   such as .catlinks
		 */
		mw.hook( 'wikipage.categories' ).fire( $nodes );
	}

	$nodes = $( 'table.diff[data-mw="interface"]' );
	if ( $nodes.length ) {
		/**
		 * Fired when the diff is added to a page containing a diff
		 *
		 * Similar to the {@link mw.hook#event-wikipage_content wikipage.content hook}
		 * $diff may still be detached when the hook is fired.
		 *
		 * @event wikipage_diff
		 * @member mw.hook
		 * @param {jQuery} $diff The root element of the MediaWiki diff (`table.diff`).
		 */
		mw.hook( 'wikipage.diff' ).fire( $nodes.eq( 0 ) );
	}

	$( '#t-print a' ).on( 'click', function ( e ) {
		window.print();
		e.preventDefault();
	} );

	// Turn logout to a POST action
	$( config.selectorLogoutLink ).on( 'click', function ( e ) {
		mw.notify(
			mw.message( 'logging-out-notify' ),
			{ tag: 'logout', autoHide: false }
		);
		var api = new mw.Api();
		var url = this.href;
		api.postWithToken( 'csrf', {
			action: 'logout'
		} ).then(
			function () {
				location.href = url;
			},
			function ( err, data ) {
				mw.notify(
					api.getErrorMessage( data ),
					{ type: 'error', tag: 'logout', autoHide: false }
				);
			}
		);
		e.preventDefault();
	} );
	fixViewportForTabletDevices();
} );

/**
 * @class mw.plugin.page.ready
 * @singleton
 */

/**
 * @private
 * @param {HTMLElement} element
 * @return {boolean} Whether the element is a search input.
 */
function isSearchInput( element ) {
	return element.id === 'searchInput' ||
		/(^|\s)mw-searchInput($|\s)/.test( element.className );
}

/**
 * Load a given module when a search input is focused.
 *
 * @param {string} moduleName Name of a module
 */
function loadSearchModule( moduleName ) {
	// T251544: Collect search performance metrics to compare Vue search with
	// mediawiki.searchSuggest performance. Marks and Measures will only be
	// recorded on the Vector skin.
	//
	// Vue search isn't loaded through this function so we are only collecting
	// legacy search performance metrics here.

	/* eslint-disable compat/compat */
	var shouldTestSearch = !!( moduleName === 'mediawiki.searchSuggest' &&
		mw.config.get( 'skin' ) === 'vector' &&
		window.performance &&
		performance.mark &&
		performance.measure &&
		performance.getEntriesByName ),
		loadStartMark = 'mwVectorLegacySearchLoadStart',
		loadEndMark = 'mwVectorLegacySearchLoadEnd';
	/* eslint-enable compat/compat */

	function requestSearchModule() {
		if ( shouldTestSearch ) {
			performance.mark( loadStartMark );
		}
		mw.loader.using( moduleName, function () {
			if ( shouldTestSearch && performance.getEntriesByName( loadStartMark ).length ) {
				performance.mark( loadEndMark );
				performance.measure( 'mwVectorLegacySearchLoadStartToLoadEnd', loadStartMark, loadEndMark );
			}
		} );
	}

	// Load the module once a search input is focussed.
	function eventListener( e ) {
		if ( isSearchInput( e.target ) ) {
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
	loadSearchModule( 'mediawiki.searchSuggest' );
}

module.exports = {
	loadSearchModule: loadSearchModule,
	checkboxHack: require( './checkboxHack.js' )
};
