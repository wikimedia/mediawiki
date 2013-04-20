/* JavaScript for Special:Userlogin */

( function ( mw, $ ) {

$( function() {
	var $content = $( '#mw-content-text' ),
		$submit = $content.find( '#wpCreateaccount' ),
		tabIndex,
		$captchaStuff;

	/**
	 * CAPTCHA
	 * Minor cleanup of captcha.
	 */
	if ( !$submit.length) {
		return;
	}
	tabIndex = $submit.prop( 'tabindex' ) - 1;
	$captchaStuff = $content.find ( '.captcha' );
	if ( $captchaStuff.length ) {
		// Move  CAPTCHA reload, if any.
		$( '.mw-createacct-captcha-and-reload' ).append( $captchaStuff.find( '.confirmedit-captcha-reload' ) );
	}

});

}( mediaWiki, jQuery ) );
