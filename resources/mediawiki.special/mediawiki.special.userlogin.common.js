/**
 * JavaScript for login and signup forms.
 */
( function ( mw, $ ) {
	// Move the FancyCaptcha image into a more attractive container.
	// The CAPTCHA is in a <div class="captcha"> at the top of the form. If it's a FancyCaptcha,
	// then we remove it and insert it lower down, in a customized div with just what we need (e.g.
	// no 'fancycaptcha-createaccount' message).
	function adjustFancyCaptcha( $content, buttonSubmit ) {
		var $submit = $content.find( buttonSubmit ),
			tabIndex,
			$captchaStuff,
			$captchaImageContainer,
			// JavaScript can't yet parse the message 'createacct-imgcaptcha-help' when it
			// contains a MediaWiki transclusion, so PHP parses it and sends the HTML.
			// This is only set for the signup form (and undefined for login).
			helpMsg = mw.config.get( 'wgCreateacctImgcaptchaHelp' ),
			helpHtml = '';

		if ( !$submit.length ) {
			return;
		}
		tabIndex = $submit.prop( 'tabindex' ) - 1;
		$captchaStuff = $content.find( '.captcha' );

		if ( $captchaStuff.length ) {
			// The FancyCaptcha has this class in the ConfirmEdit extension since 2013-04-18.
			$captchaImageContainer = $captchaStuff.find( '.fancycaptcha-image-container' );
			if ( $captchaImageContainer.length !== 1 ) {
				return;
			}

			$captchaStuff.remove();

			if ( helpMsg ) {
				helpHtml = '<small class="mw-createacct-captcha-assisted">' + helpMsg + '</small>';
			}

			// Insert another div before the submit button that will include the
			// repositioned FancyCaptcha div, an input field, and possible help.
			$submit.closest( 'div' ).before( [
				'<div>',
					'<label for="wpCaptchaWord">' + mw.message( 'createacct-captcha' ).escaped() + '</label>',
					'<div class="mw-createacct-captcha-container">',
						'<div class="mw-createacct-captcha-and-reload" />',
						'<input id="wpCaptchaWord" name="wpCaptchaWord" type="text" placeholder="' +
							mw.message( 'createacct-imgcaptcha-ph' ).escaped() +
							'" tabindex="' + tabIndex + '" autocapitalize="off" autocorrect="off">',
							helpHtml,
					'</div>',
				'</div>'
			].join( '' ) );

			// Stick the FancyCaptcha container inside our bordered and framed parents.
			$captchaImageContainer
				.prependTo( $content.find( '.mw-createacct-captcha-and-reload' ) );

			// Find the input field, add the text (if any) of the existing CAPTCHA
			// field (although usually it's blanked out on every redisplay),
			// and after it move over the hidden field that tells the CAPTCHA
			// what to do.
			$content.find( '#wpCaptchaWord' )
				.val( $captchaStuff.find( '#wpCaptchaWord' ).val() )
				.after( $captchaStuff.find( '#wpCaptchaId' ) );
		}
	}

	$( function () {
		// Work with both login and signup form
		adjustFancyCaptcha( $( '#mw-content-text' ), '#wpCreateaccount, #wpLoginAttempt' );
	} );
}( mediaWiki, jQuery ) );
