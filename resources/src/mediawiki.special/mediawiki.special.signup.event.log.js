/*
    Event logging on button click
*/
( function ( mw, $ ) {
	$( function () {
		$( '#wpCreateaccount' ).click( function () {
			var info = {
				userId: mw.user.sessionId( )
			};
			mw.track( 'CaptchaFormData', info );
		} );
	} );
}( mediaWiki, jQuery ) );
