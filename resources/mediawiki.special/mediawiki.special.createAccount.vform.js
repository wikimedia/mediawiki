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
	 * The CAPTCHA is in a div style="captcha" along with a ton of
	 * irrelevant text from MediaWiki:fancycaptcha-create-account
	 * Strategy: remove it, then repopulate just what we need.
	 * Adds an empty Security check if there's no CAPTCHA.
	 */
	if ( !$submit.length) {
		return;
	}
	tabIndex = $submit.prop( 'tabindex' ) - 1;
	$captchaStuff = $content.find ( '.captcha' );
	if ( $captchaStuff.length ) {
		// There are only a few only things we want from the old CAPTCHA.
		// Get the img (we assume only one, and we ignore reload images) out of the old CAPTCHA,
		// and replace the placeholder img with it, and style it.
		captchaImage = $captchaStuff.find( 'img' )
			.not( '.confirmedit-captcha-reload img' );

		if ( captchaImage.length === 0 ) {
			// For text-based CAPTCHAs, we do nothing.
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
				// arguably mw.util.wikiGetLink() here...
				'<input id="wpCaptchaWord" name="wpCaptchaWord" type="text" placeholder="' +
					mw.message( 'createacct-imgcaptcha-ph' ).text() +
					'" name="captcha-text" tabindex="' + tabIndex + '" autocapitalize="off" autocorrect="off">',
					'<small class="mw-createacct-captcha-assisted">' + helpMsg + '</small>',
			'</div>',
		'</div>'
				].join('') );

		captchaImage.replaceAll( $content.find( '#mw-createacct-captcha' ) )
			.addClass( 'mw-createacct-captcha');

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