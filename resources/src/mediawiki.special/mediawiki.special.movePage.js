/*!
 * JavaScript for Special:MovePage
 */
jQuery( function () {
	OO.ui.infuse( 'wpNewTitle' ).title.$input.byteLimit( 255 );
	OO.ui.infuse( 'wpReason' ).$input.byteLimit();
} );
