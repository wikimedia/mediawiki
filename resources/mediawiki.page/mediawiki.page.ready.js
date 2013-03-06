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

		/* Fix display of legacy .editsection links on cached HTML page renders */
		$( '.editsection' )
			.removeClass( 'editsection' ).addClass( 'mw-editsection' )
			.each( function () {
				/* Move to the end of parent element (a heading), with a space inbetween */
				$( this ).parent().append( ' ', this );
			} );

		/* Add accesskey hints to the tooltips */
		mw.util.updateTooltipAccessKeys();

	} );
}( mediaWiki, jQuery ) );
