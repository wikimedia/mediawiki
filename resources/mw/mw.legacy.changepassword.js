/*
 * Legacy emulation for the now depricated changepassword.js
 * 
 * Ported by: Trevor Parscal
 */

( function( $ ) {

$.extend( mw.legacy, {
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

} )( jQuery );