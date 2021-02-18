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
	var $sortable, $collapsible,
		dependencies = [];
	if ( config.collapsible ) {
		$collapsible = $content.find( '.mw-collapsible' );
		if ( $collapsible.length ) {
			dependencies.push( 'jquery.makeCollapsible' );
		}
	}
	if ( config.sortable ) {
		$sortable = $content.find( 'table.sortable' );
		if ( $sortable.length ) {
			dependencies.push( 'jquery.tablesorter' );
		}
	}
	if ( dependencies.length ) {
		// Both modules are preloaded by Skin::getDefaultModules()
		mw.loader.using( dependencies ).then( function () {
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
	var $nodes;

	// Add accesskey hints to the tooltips
	$( '[accesskey]' ).updateTooltipAccessKeys();

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
	mw.hook( 'wikipage.content' ).fire( $( '#mw-content-text' ) );

	$nodes = $( '.catlinks[data-mw="interface"]' );
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
		var api = new mw.Api(),
			url = this.href;
		mw.notify(
			mw.message( 'logging-out-notify' ),
			{ tag: 'logout', autoHide: false }
		);
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
