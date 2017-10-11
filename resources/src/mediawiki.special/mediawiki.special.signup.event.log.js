/*
    Event logging on button click
*/
( function ( mw, $ ) {
	$( function () {
		$( '#wpCreateaccount' ).click( function ( e ) {
			var info = {
				userId: mw.user.sessionId(),
				mouseX: e.clientX,
				mouseY: e.clientY
			};
			mw.track( 'event.EventLogging', info );
		} );
	} );
}( mediaWiki, jQuery ) );
