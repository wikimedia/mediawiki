/*
    Event logging on button click
*/

( function ( mw, $ ) {
	$( function () {
		$( '#wpCreateaccount' ).click( function ( e ) {
			var info = {

				userIp: client.Ip
			};
			mw.track( 'CaptchaFormData', info );
		} );
	} );
}( mediaWiki, jQuery ) );

