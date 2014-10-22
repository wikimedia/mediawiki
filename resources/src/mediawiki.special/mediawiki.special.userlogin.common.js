/*!
 * JavaScript for login and signup forms.
 */
( function ( mw, $ ) {
	// Move the FancyCaptcha image into a more attractive container.
	// The CAPTCHA is in a <div class="captcha"> at the top of the form. If it's a FancyCaptcha,
	// then we remove it and insert it lower down, in a customized div with just what we need (e.g.
	// no 'fancycaptcha-createaccount' message).
	function adjustFancyCaptcha( $content, buttonSubmit ) {
		var $el, data, $captchaStuff, $captchaImageContainer,
			$submit = $content.find( buttonSubmit ),
			// JavaScript can't yet parse the message 'createacct-imgcaptcha-help' when it
			// contains a MediaWiki transclusion, so PHP parses it and sends the HTML.
			// This is only set for the signup form (and undefined for login).
			helpMsg = mw.config.get( 'wgCreateacctImgcaptchaHelp' );

		if ( !$submit.length ) {
			return;
		}
		$captchaStuff = $content.find( '.captcha' );

		if ( $captchaStuff.length ) {
			// The FancyCaptcha has this class in the ConfirmEdit extension since 2013-04-18.
			$captchaImageContainer = $captchaStuff.find( '.fancycaptcha-image-container' );
			if ( $captchaImageContainer.length === 1 ) {
				$captchaStuff.remove();
				// Insert another div before the submit button that will include the
				// repositioned FancyCaptcha div, an input field, and possible help.
				data = {
					label: mw.msg( 'createacct-captcha' ),
					tabIndex: $submit.prop( 'tabIndex' ) - 1,
					placeholder: mw.msg( 'createacct-imgcaptcha-ph' ),
					// Find the input field, add the text (if any) of the existing CAPTCHA
					// field (although usually it's blanked out on every redisplay),
					value: $captchaStuff.find( '#wpCaptchaWord' ).val(),
					captchaId: $captchaStuff.find( '#wpCaptchaId' ).val(),
					htmlMessage: helpMsg
				};

				$submit.closest( 'div' ).before(
					mw.template.get( 'mediawiki.special.userlogin.common.js', 'captcha.mustache' ).render( data ) );
				$el.find( '.mw-createacct-captcha-and-reload' ).append( $captchaImageContainer );
			}
		}
	}

	$( function () {
		// Work with both login and signup form
		adjustFancyCaptcha( $( '#mw-content-text' ), '#wpCreateaccount, #wpLoginAttempt' );
	} );
}( mediaWiki, jQuery ) );
