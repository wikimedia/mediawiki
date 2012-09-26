( function ( mw, $ ) {
	$( function () {
		var $sortableTables;

		/* Emulate placeholder if not supported by browser */
		if ( !( 'placeholder' in document.createElement( 'input' ) ) ) {
			$( 'input[placeholder]' ).placeholder();
		}

		/* Enable makeCollapsible */
		$( '.mw-collapsible' ).makeCollapsible();

		/* Lazy load jquery.tablesorter */
		$sortableTables = $( 'table.sortable' );
		if ( $sortableTables.length ) {
			mw.loader.using( 'jquery.tablesorter', function () {
				$sortableTables.tablesorter();
			});
		}

		/* Enable CheckboxShiftClick */
		$( 'input[type=checkbox]:not(.noshiftselect)' ).checkboxShiftClick();

		/* Add accesskey hints to the tooltips */
		mw.util.updateTooltipAccessKeys();

	} );
}( mediaWiki, jQuery ) );
