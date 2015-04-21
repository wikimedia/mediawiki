/*global OO */
jQuery( function ( $ ) {

	// Infuse everything with JavaScript widgets
	$( '.mw-htmlform-ooui [data-ooui]' ).each( function () {
		OO.ui.infuse( this.id );
	} );

} );
