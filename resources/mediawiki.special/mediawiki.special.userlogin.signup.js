/**
 * JavaScript for signup form.
 */
( function ( mw, $ ) {
	// When sending password by email, hide the password input fields.
	$( function () {
		// Always required if checked, otherwise it depends, so we use the original
		var $emailLabel = $( 'label[for="wpEmail"]' ),
			originalText = $emailLabel.text(),
			requiredText = mw.message( 'createacct-emailrequired' ).text(),
			$createByMailCheckbox = $( '#wpCreateaccountMail' ),
			$beforePwds = $( '.mw-row-password:first' ).prev(),
			$pwds;

		function updateForCheckbox() {
			var checked = $createByMailCheckbox.prop( 'checked' );
			if ( checked ) {
				$pwds = $( '.mw-row-password' ).detach();
				$emailLabel.text( requiredText );
			} else {
				if ( $pwds ) {
					$beforePwds.after( $pwds );
					$pwds = null;
				}
				$emailLabel.text( originalText );
			}
		}

		$createByMailCheckbox.on( 'change', updateForCheckbox );
		updateForCheckbox();
	} );

	// Check if the username is invalid or already taken
	$( function () {
		var
			// All of these are apparently required to be sure we detect all changes.
			events = 'keyup keydown change mouseup cut paste focus blur',
			$input = $( '#wpName2' ),
			$statusContainer = $( '#mw-createacct-status-area' ),
			api = new mw.Api(),
			currentRequest;

		// Hide any present status messages.
		function cleanup() {
			$statusContainer.slideUp( function () {
				$statusContainer
					.removeAttr( 'class' )
					.empty();
			} );
		}

		function updateUsernameStatus() {
			var
				username = $.trim( $input.val() ),
				currentRequestInternal;

			// Abort any pending requests.
			if ( currentRequest ) {
				currentRequest.abort();
			}

			if ( username === '' ) {
				cleanup();
				return;
			}

			currentRequest = currentRequestInternal = api.get( {
				action: 'query',
				list: 'users',
				ususers: username // '|' in usernames is handled below
			} ).done( function ( resp ) {
				var userinfo, state, message;

				// Another request was fired in the meantime, the result we got here is no longer current.
				// This shouldn't happen as we abort pending requests, but you never know.
				if ( currentRequest !== currentRequestInternal ) {
					return;
				}

				userinfo = resp.query.users[0];

				if ( resp.query.users.length !== 1 ) {
					// Happens if the user types '|' into the field
					state = 'invalid';
				} else if ( userinfo.invalid !== undefined ) {
					state = 'invalid';
				} else if ( userinfo.userid !== undefined ) {
					state = 'taken';
				} else {
					state = 'ok';
				}

				if ( state === 'ok' ) {
					cleanup();
				} else {
					if ( state === 'invalid' ) {
						message = mw.message( 'noname' ).text();
					} else if ( state === 'taken' ) {
						message = mw.message( 'userexists' ).text();
					}

					$statusContainer
						.attr( 'class', 'errorbox' )
						.empty()
						.append(
							// Ugh…
							// @todo Change the HTML structure in includes/templates/Usercreate.php
							$( '<strong>' ).text( mw.message( 'createacct-error' ).text() ),
							$( '<br>' ),
							document.createTextNode( message )
						)
						.slideDown();
				}
			} ).fail( function () {
				cleanup();
			} );
		}

		$input.on( events, $.debounce( 250, updateUsernameStatus ) );
	} );
}( mediaWiki, jQuery ) );
