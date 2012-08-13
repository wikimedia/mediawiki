( function ( mw, $ ) {
	mw.onContentReady( function() {
		/* Emulate placeholder if not supported by browser */
		if ( !( 'placeholder' in document.createElement( 'input' ) ) ) {
			this.find( 'input[placeholder]' ).placeholder();
		}

		/* Enable makeCollapsible */
		this.find( '.mw-collapsible' ).makeCollapsible();

		/* Lazy load jquery.tablesorter */
		if ( this.find( 'table.sortable' ).length ) {
			mw.loader.using( 'jquery.tablesorter', function() {
				this.find( 'table.sortable' ).tablesorter();
			});
		}

		/* Enable CheckboxShiftClick */
		this.find( 'input[type=checkbox]:not(.noshiftselect)' ).checkboxShiftClick();

	} );

	$( document ).ready( function() {

		/* Add accesskey hints to the tooltips */
		mw.util.updateTooltipAccessKeys();

		/* On ready trigger the first mw-content-ready event */
		$( 'body' ).trigger( 'mw-content-ready' );

	} );

}( mediaWiki, jQuery ) );
