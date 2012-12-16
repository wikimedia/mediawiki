/**
 * JavaScript for Special:UserLogin/signup
 */
jQuery( document ).ready( function ( $ ) {
	$( '#wpCreateaccountMail' )
		.on( 'change', function() {
			$( '.mw-row-password' ).toggle( !$( this ).attr( 'checked' ) );
		} )
		.trigger( 'change' );
} );
