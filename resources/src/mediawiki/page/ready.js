( function ( mw, $ ) {
	var supportsPlaceholder = 'placeholder' in document.createElement( 'input' );

	// Break out of framesets
	if ( mw.config.get( 'wgBreakFrames' ) ) {
		// Note: In IE < 9 strict comparison to window is non-standard (the standard didn't exist yet)
		// it works only comparing to window.self or window.window (http://stackoverflow.com/q/4850978/319266)
		if ( window.top !== window.self ) {
			// Un-trap us from framesets
			window.top.location.href = location.href;
		}
	}

	mw.hook( 'wikipage.content' ).add( function ( $content ) {
		var $sortableTables;

		// Run jquery.placeholder polyfill if placeholder is not supported
		if ( !supportsPlaceholder ) {
			$content.find( 'input[placeholder]' ).placeholder();
		}

		// Run jquery.makeCollapsible
		$content.find( '.mw-collapsible' ).makeCollapsible();

		// Lazy load jquery.tablesorter
		$sortableTables = $content.find( 'table.sortable' );
		if ( $sortableTables.length ) {
			mw.loader.using( 'jquery.tablesorter', function () {
				$sortableTables.tablesorter();
			} );
		}

		// Run jquery.checkboxShiftClick
		$content.find( 'input[type="checkbox"]:not(.noshiftselect)' ).checkboxShiftClick();
	} );

	// Things outside the wikipage content
	$( function () {
		var $nodes, $oouiNodes;

		if ( !supportsPlaceholder ) {
			// Exclude content to avoid hitting it twice for the (first) wikipage content
			$( 'input[placeholder]' ).not( '#mw-content-text input' ).placeholder();
		}

		// Add accesskey hints to the tooltips
		if ( document.querySelectorAll ) {
			// If we're running on a browser where we can do this efficiently,
			// just find all elements that have accesskeys. We can't use jQuery's
			// polyfill for the selector since looping over all elements on page
			// load might be too slow.
			$nodes = $( document.querySelectorAll( '[accesskey]' ) );
		} else {
			// Otherwise go through some elements likely to have accesskeys rather
			// than looping over all of them. Unfortunately this will not fully
			// work for custom skins with different HTML structures. Input, label
			// and button should be rare enough that no optimizations are needed.
			$nodes = $( '#column-one a, #mw-head a, #mw-panel a, #p-logo a, input, label, button' );
		}
		$nodes.updateTooltipAccessKeys();

		// Infuse OOUI widgets, if any are present
		$oouiNodes = $( '[data-ooui]' );
		if ( $oouiNodes.length ) {
			// FIXME: We should only load the widgets that are being infused
			mw.loader.using( [
				'mediawiki.widgets',
				'mediawiki.widgets.UserInputWidget',
				'mediawiki.widgets.SearchInputWidget'
			] ).done( function () {
				$oouiNodes.each( function () {
					OO.ui.infuse( this );
				} );
			} );
		}

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
	} );

}( mediaWiki, jQuery ) );
