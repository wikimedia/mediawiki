/*
 * Legacy emulation for the now depricated skins/common/changepassword.js
 */

( function( $, mw ) {

/* Extension */

$.extend( true, mw.legacy, {
	
	/* Functions */
	
	'onNameChange': function() {
		var state = mw.legacy.wgUserName != $( '#wpName' ).val();
		$( '#wpPassword' ).attr( 'disabled', state );
		$( '#wpComment' ).attr( 'disabled', !state );
	},
	'onNameChangeHook': function() {
		$( '#wpName' ).blur( mw.legacy.onNameChange );
	}
} );

/* Initialization */

$( document ).ready( function() {
	mw.legacy.onNameChangeHook();
} );

} )( jQuery, mediaWiki );