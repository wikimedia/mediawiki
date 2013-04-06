/* JavaScript for Special:Userlogin */

jQuery( function( $ ) {
	var $content = $( '#mw-content-text' ),
		$captchaStuff;

	/**
	 * CAPTCHA
	 * The CAPTCHA is in a div style="captcha" along with a ton of
	 * irrelevant text from MediaWiki:fancycaptcha-create-account
	 * Strategy: remove it, then repopulate just what we need.
	 * Adds an empty Security check if there's no CAPTCHA.
	 */
	$captchaStuff = $content.find ( '.captcha' ).remove();
	if ( $captchaStuff.length ) {
		// insert another li before the submit button.
		$content.find( '#wpCreateaccount' ).parent().parent()
			.before( [
		'<div>',
			'<label class="acux-label" for="wpCaptchaWord">Security check</label>',
			'<div class="acux-captcha-container">',
				'<img id="acux-captcha" alt="PLACEHOLDER">',
				// arguably mw.util.wikiGetLink() here...
				'<br />', // XXX
				'<small>Can\'t see the image? <a href="/wiki/Wikipedia:Request_an_account" title="Wikipedia:Request an account" tabindex="-1">We can create an account for you!</a></small>',
				// Same tabindex as Remember me checkbox , but we removed that.
				'<input id="wpCaptchaWord" name="wpCaptchaWord" type="text" placeholder="Enter the text you see above" name="captcha-text" tabindex="8" autocapitalize="off" autocorrect="off">',
			'</div>',
		'</div>'
				].join('') );

		// There are only a few only things we want from the old CAPTCHA.
		// Get the img (we hope only one!) out of the old CAPTCHA,
		// and replace the placeholder img with it, and style it.
		$captchaStuff.find( 'img' )
			.replaceAll( $content.find( '#acux-captcha' ) )
			.addClass( 'acux-captcha');

		// Find the input field, add the text (if any) of the existing CAPTCHA
		// field (although usually it's blanked out on every redisplay),
		// and after it move over the hidden field that tells the CAPTCHA
		// what to do.
		$content.find( '#wpCaptchaWord' )
			.val( $captchaStuff.find( '#wpCaptchaWord' ).val() )
			.after( $captchaStuff.find( '#wpCaptchaId' ) );
	}

});
