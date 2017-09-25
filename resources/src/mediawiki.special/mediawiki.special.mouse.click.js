/*!
 * JavaScript for Special:SignUpPageMouseTrack
 */
( function ( mw, $ ) {
	$('#wpCreateaccount').click(function(e) {
    var coordinates = {
  		"data": [
    		{ "X_coor": e.pageX, "Y_coor": e.pageY }
  		]
	};
	
    mw.track('mediawiki.special.userlogin.signup.js',coordinates);
    });

}( mediaWiki, jQuery ) );
