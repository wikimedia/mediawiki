/*!
 * JavaScript for Special:MovePage
 */
jQuery( function () {
	OO.ui.infuse( 'wpNewTitle' );
	OO.ui.infuse( 'wpReason' ).$input.byteLimit();
} );
