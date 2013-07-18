( function ( mw , $ ) {
	var supportsPlaceholder = 'placeholder' in document.createElement( 'input' );

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

		if ( !supportsPlaceholder ) {
			// Exclude content to avoid hitting it twice for the (first) wikipage content
			$( 'input[placeholder]' ).not( '#mw-content-text input' ).placeholder();
		}

		// Add accesskey hints to the tooltips
		mw.util.updateTooltipAccessKeys();

	} );

}( mediaWiki, jQuery ) );
