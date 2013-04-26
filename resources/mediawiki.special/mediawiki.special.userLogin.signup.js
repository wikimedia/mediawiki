/**
 * JavaScript for Special:UserLogin/signup
 */

( function ( mw, $ ) {
	$( document ).ready( function () {
		// Always required if checked, otherwise it depends, so we use the original
		var $emailLabel = $( 'label[for="wpEmail"]' ),
			originalText = $emailLabel.text(),
			requiredText = mw.message( 'createacct-emailrequired' ).text();
		$( '#wpCreateaccountMail' )
			.on( 'change', function () {
				var checked = $( this ).attr( 'checked' ) ;
				if ( checked ) {
					$( '.mw-row-password' ).hide();
					$emailLabel.text( requiredText );
				} else {
					$( '.mw-row-password' ).show();
					$emailLabel.text( originalText );
				}
			} )
			.trigger( 'change' );
	} );
}( mediaWiki, jQuery ) );
