/* JavaScript for Special:Userlogin */

jQuery( function( $ ) {
	var $content = $( '#mw-content-text' ),
		$submit = $content.find( '#wpCreateaccount' ),
		tabIndex,
		$captchaStuff,
		helpMsg = mediaWiki.config.get( 'wgCreateacctImgcaptchaHelp' );

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
	$captchaStuff = $content.find ( '.captcha' ).remove();
	if ( $captchaStuff.length ) {
		// Insert another div before the submit button.
		$submit.closest( 'div' )
			.before( [
		'<div>',
			'<label for="wpCaptchaWord">' + mw.message( 'createacct-captcha' ).text() + '</label>',
			'<div class="mw-createacct-captcha-container">',
				'<img id="mw-createacct-captcha" alt="PLACEHOLDER">',
				// arguably mw.util.wikiGetLink() here...
				// Goal is Can\'t see the image? <a href="/wiki/Wikipedia:Request_an_account" title="Wikipedia:Request an account" tabindex="-1">We can create an account for you!</a>
				'<small class="mw-createacct-captcha-assisted">' + helpMsg + '</small>',
				'<input id="wpCaptchaWord" name="wpCaptchaWord" type="text" placeholder="' +
					mw.message( 'createacct-imgcaptcha-ph' ).text() +
					'" name="captcha-text" tabindex="' + tabIndex + '" autocapitalize="off" autocorrect="off">',
			'</div>',
		'</div>'
				].join('') );

		// There are only a few only things we want from the old CAPTCHA.
		// Get the img (we hope only one!) out of the old CAPTCHA,
		// and replace the placeholder img with it, and style it.
		$captchaStuff.find( 'img' )
			.replaceAll( $content.find( '#mw-createacct-captcha' ) )
			.addClass( 'mw-createacct-captcha');

		// Find the input field, add the text (if any) of the existing CAPTCHA
		// field (although usually it's blanked out on every redisplay),
		// and after it move over the hidden field that tells the CAPTCHA
		// what to do.
		$content.find( '#wpCaptchaWord' )
			.val( $captchaStuff.find( '#wpCaptchaWord' ).val() )
			.after( $captchaStuff.find( '#wpCaptchaId' ) );
	}

});
