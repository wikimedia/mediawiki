/*!
 * JavaScript for Special:SignUpPageMouseTrack
 */
( function ( mw, $ ) {
	$( '#wpCreateaccount' ).click( function ( e ) {
		mw.track( 'event.CaptchaMouseData', [ { MouseClickX: e.pageX }, { MouseClickY: e.pageY } ] );
	} );

}( mediaWiki, jQuery ) );
