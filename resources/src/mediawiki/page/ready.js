( function ( mw, $ ) {
	var supportsPlaceholder = 'placeholder' in document.createElement( 'input' ),
		wgCookiePrefix;

	// Break out of framesets
	if ( mw.config.get( 'wgBreakFrames' ) ) {
		// Note: In IE < 9 strict comparison to window is non-standard (the standard didn't exist yet)
		// it works only comparing to window.self or window.window (http://stackoverflow.com/q/4850978/319266)
		if ( window.top !== window.self ) {
			// Un-trap us from framesets
			window.top.location.href = location.href;
		}
	}

	// If a user has been autoblocked, two cookies are set.
	// Their values are replicated here in localStorage to guard against cookie-removal.
	// Ref: https://phabricator.wikimedia.org/T5233
	wgCookiePrefix = mw.config.get( 'wgCookiePrefix' );
	if ( !mw.cookie.get( wgCookiePrefix + 'BlockID' ) && mw.storage.get( 'blockID' ) ) {
		// The block ID exists in storage, but not in the cookie.
		mw.cookie.set( wgCookiePrefix + 'BlockID', mw.storage.get( 'blockID' ) );
		mw.cookie.set( wgCookiePrefix + 'BlockHash', mw.storage.get( 'blockHash' ) );
	} else if ( parseInt( mw.cookie.get( wgCookiePrefix + 'BlockID' ), 10 ) > 0 ) {
		// The block ID exists in the cookie, but not in storage.
		// (When a block expires the cookie remains but its value is '', hence the integer check above.)
		mw.storage.set( 'blockID', mw.cookie.get( wgCookiePrefix + 'BlockID' ) );
		mw.storage.set( 'blockHash', mw.cookie.get( wgCookiePrefix + 'BlockHash' ) );
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
		var $nodes;

		if ( !supportsPlaceholder ) {
			// Exclude content to avoid hitting it twice for the (first) wikipage content
			$( 'input[placeholder]' ).not( '#mw-content-text input' ).placeholder();
		}

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
	} );

}( mediaWiki, jQuery ) );
