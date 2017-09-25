/*!
 * JavaScript for Special:SignUpPageMouseTrack
 */
( function ( mw, $ ) {
	var pageX;
	var pageY;
	
	$('#wpCreateaccount').click(function(e) {
        e = e || window.event;

     pageX = e.pageX;
     pageY = e.pageY;

    // IE 8
    if (pageX === undefined) {
        pageX = e.clientX + document.body.scrollLeft + document.documentElement.scrollLeft;
        pageY = e.clientY + document.body.scrollTop + document.documentElement.scrollTop;
    }
    mw.track('mediawiki.special.userlogin.signup.js',pageX);
    mw.track('mediawiki.special.userlogin.signup.js',pageY);
    console.log(pageX, pageY);
    });

}( mediaWiki, jQuery ) );