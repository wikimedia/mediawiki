/*!
 * JavaScript for Special:MovePage
 */
jQuery( function ( $ ) {
	// Infuse for pretty dropdown
	OO.ui.infuse( 'wpNewTitle' );
	// Limit to 255 bytes, not characters
	OO.ui.infuse( 'wpReason' ).$input.byteLimit();
	// Infuse for nicer "help" popup
	if ( $( '#wpMovetalk-field' ).length ) {
		OO.ui.infuse( 'wpMovetalk-field' );
	}
} );
