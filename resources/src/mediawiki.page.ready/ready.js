var checkboxShift = require( './checkboxShift.js' );
var config = require( './config.json' );

mw.hook( 'wikipage.content' ).add( function ( $content ) {
	var $sortable, $collapsible, $sortableAndCollapsible,
		dependencies = [];

	if ( config.sortable && config.collapsible ) {
		$sortableAndCollapsible = $content.find( 'table.sortable.mw-collapsible' );
	}
	if ( config.sortable ) {
		$collapsible = $content.find( '.mw-collapsible' );
		if ( $collapsible.length ) {
			dependencies.push( 'jquery.makeCollapsible' );
		}
	}
	if ( config.collapsible ) {
		$sortable = $content.find( 'table.sortable' );
		if ( $sortable.length ) {
			dependencies.push( 'jquery.tablesorter' );
		}
	}
	if ( dependencies.length ) {
		// Both modules are preloaded by Skin::getDefaultModules()
		mw.loader.using( dependencies ).then( function () {
			// The two scripts only work correctly together when executed in this order (T64878)
			if ( $sortableAndCollapsible && $sortableAndCollapsible.length ) {
				$sortableAndCollapsible.tablesorter().makeCollapsible();
			}
			// These are no-ops when executed on elements that were already handled above
			if ( $collapsible && $collapsible.length ) {
				$collapsible.makeCollapsible();
			}
			if ( $sortable && $sortable.length ) {
				$sortable.tablesorter();
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

	$( '#t-print a' ).on( 'click', function ( e ) {
		window.print();
		e.preventDefault();
	} );

	// Turn logout to a POST action
	$( '#pt-logout a[data-mw="interface"]' ).on( 'click', function ( e ) {
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

// Load the search suggestion module when focus the search box.
function eventListener( e ) {
	if (
		e.target.id === 'searchInput' ||
		/(^|\s)mw-searchInput($|\s)/.test( e.target.className )
	) {
		mw.loader.load( 'mediawiki.searchSuggest' );
		document.removeEventListener( 'focusin', eventListener );
	}
}
document.addEventListener( 'focusin', eventListener );

// Load the search suggestion module when the search box is already focused
// because the user started typing before the JavaScript is loaded.
if (
	document.activeElement && (
		document.activeElement.id === 'searchInput' ||
		/(^|\s)mw-searchInput($|\s)/.test( document.activeElement.className )
	)
) {
	mw.loader.load( 'mediawiki.searchSuggest' );
}

module.exports = {
	checkboxHack: require( './checkboxHack.js' )
};
