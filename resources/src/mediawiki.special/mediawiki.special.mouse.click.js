/*!
 * JavaScript for Special:SignUpPageMouseTrack
 */
( function ( mw, $ ) {
	$( '#wpCreateaccount' ).click( function ( e ) {
		var coordinates = {
			data: [
				{ XCoor: e.pageX, YCoor: e.pageY }
			]
		};
		mw.track( 'mediawiki.special.userlogin.signup.js', coordinates );
	} );

}( mediaWiki, jQuery ) );
