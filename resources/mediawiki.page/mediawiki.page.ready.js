jQuery( document ).ready( function( $ ) {

	/* Initialize utilities (messageBoxNew, profile, tooltip access keys, TOC etc.) */
	mw.util.init();

	/* Emulate placeholder if not supported by browser */
	if ( !( 'placeholder' in document.createElement( 'input' ) ) ) {
		$( 'input[placeholder]' ).placeholder();
	}

	/* Enable makeCollapsible */
	$( '.mw-collapsible' ).makeCollapsible();

	/* Lazy load jquery.tablesorter */
	if ( $( 'table.sortable' ).length ) {
		mw.loader.using( 'jquery.tablesorter', function() {
			$( 'table.sortable' ).tablesorter();
		});
	}

	/* Enable CheckboxShiftClick */
	$( 'input[type=checkbox]:not(.noshiftselect)' ).checkboxShiftClick();

	/* Add accesskey hints to the tooltips */
	mw.util.updateTooltipAccessKeys();

} );
