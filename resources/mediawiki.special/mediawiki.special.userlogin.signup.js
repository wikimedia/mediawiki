/**
 * JavaScript for Special:UserLogin/signup
 */

jQuery( function( $ ) {
	var update = function() {
		$( '.mw-row-password' ).toggle( !$( this ).attr( 'checked' ) );
	};
	update.call( $( '#wpCreateaccountMail' ).click( update ) );
} );
