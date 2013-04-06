/* JavaScript for Special:Userlogin */

( function ( mw, $ ) {

$( function() {
	var $content = $( '#mw-content-text' ),
		$submit = $content.find( '#wpCreateaccount' ),
		tabIndex,
		$captchaStuff,
		helpMsg = mw.config.get( 'wgCreateacctImgcaptchaHelp' ),
		captchaImage;

	/**
	 * CAPTCHA
	 * The CAPTCHA is in a div style="captcha" at the top of the form.
	 * If it's a FancyCaptcha, then we remove it and insert it lower down,
	 * in a customized div with just what we need (e.g. no
	 * fancycaptcha-createaccount message).
	 */
	if ( !$submit.length) {
		return;
	}
	tabIndex = $submit.prop( 'tabindex' ) - 1;
	$captchaStuff = $content.find ( '.captcha' );

	if ( $captchaStuff.length ) {
	
		// The FancyCaptcha image has this class in the ConfirmEdit extension
		// after 2013-04-18.
		captchaImage = $captchaStuff.find( 'img.fancycaptcha-image' );
		if ( captchaImage.length !== 1 ) {
			return;
		}

		$captchaStuff.remove();

		// Insert another div before the submit button.
		$submit.closest( 'div' )
			.before( [
		'<div>',
			'<label for="wpCaptchaWord">' + mw.message( 'createacct-captcha' ).text() + '</label>',
			'<div class="mw-createacct-captcha-container">',
				'<div class="mw-createacct-captcha-and-reload">',
					'<div class="mw-createacct-captcha-image-container">',
						'<img id="mw-createacct-captcha" alt="PLACEHOLDER">',
					'</div>',
				'</div>',
				'<input id="wpCaptchaWord" name="wpCaptchaWord" type="text" placeholder="' +
					mw.message( 'createacct-imgcaptcha-ph' ).text() +
					'" tabindex="' + tabIndex + '" autocapitalize="off" autocorrect="off">',
				'<small class="mw-createacct-captcha-assisted">' + helpMsg + '</small>',
			'</div>',
		'</div>'
				].join('') );

		// Replace the placeholder img with the img from the old CAPTCHA.
		captchaImage.replaceAll( $content.find( '#mw-createacct-captcha' ) );

		// Append CAPTCHA reload, if any.
		$( '.mw-createacct-captcha-and-reload' ).append( $captchaStuff.find( '.confirmedit-captcha-reload' ) );

		// Find the input field, add the text (if any) of the existing CAPTCHA
		// field (although usually it's blanked out on every redisplay),
		// and after it move over the hidden field that tells the CAPTCHA
		// what to do.
		$content.find( '#wpCaptchaWord' )
			.val( $captchaStuff.find( '#wpCaptchaWord' ).val() )
			.after( $captchaStuff.find( '#wpCaptchaId' ) );
	}

});

}( mediaWiki, jQuery ) );
