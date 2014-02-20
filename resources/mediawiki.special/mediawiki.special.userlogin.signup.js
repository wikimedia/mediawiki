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

	// Show username normalisation warning
	$( function () {
		var
			// All of these are apparently required to be sure we detect any changes.
			events = 'keyup keydown change mouseup cut paste focus blur',
			$input = $( '#wpName2' ),
			$warningContainer = $( '#mw-createacct-status-area' ),
			api = new mw.Api(),
			currentRequest,
			tweakedUsername;

		// Hide any warnings / errors.
		function cleanup() {
			$warningContainer.slideUp( function () {
				$warningContainer
					.removeAttr( 'class' )
					.empty();
			} );
		}

		function updateUsernameStatus() {
			var
				// Leading/trailing/multiple whitespace characters are never accepted in usernames and users
				// know that, don't warn if someone accidentally types it. We do warn about underscores.
				username = $.trim( $input.val().replace( /\s+/g, ' ' ) ),
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
				var userinfo, state;

				// Another request was fired in the meantime, the result we got here is no longer current.
				// This shouldn't happen as we abort pending requests, but you never know.
				if ( currentRequest !== currentRequestInternal ) {
					return;
				}

				tweakedUsername = undefined;

				userinfo = resp.query.users[0];

				if ( resp.query.users.length !== 1 ) {
					// Happens if the user types '|' into the field
					state = 'invalid';
				} else if ( userinfo.invalid !== undefined ) {
					state = 'invalid';
				} else if ( userinfo.userid !== undefined ) {
					state = 'taken';
				} else if ( username !== userinfo.name ) {
					state = 'tweaked';
				} else {
					state = 'ok';
				}

				if ( state === 'ok' ) {
					cleanup();
				} else if ( state === 'tweaked' ) {
					$warningContainer
						.attr( 'class', 'warningbox' )
						.text( mw.message( 'createacct-normalization', username, userinfo.name ).text() )
						.slideDown();

					tweakedUsername = userinfo.name;
				} else {
					$warningContainer
						.attr( 'class', 'errorbox' )
						.empty()
						.append(
							$( '<strong>' ).text( mw.message( 'createacct-error' ).text() ),
							$( '<br>' ) // Ugh
						);

					if ( state === 'invalid' ) {
						$warningContainer
							.attr( 'class', 'errorbox' )
							.append( document.createTextNode( mw.message( 'noname' ).text() ) )
							.slideDown();
					} else if ( state === 'taken' ) {
						$warningContainer
							.attr( 'class', 'errorbox' )
							.append( document.createTextNode( mw.message( 'userexists' ).text() ) )
							.slideDown();
					}

					$warningContainer.slideDown();
				}
			} ).fail( function () {
				cleanup();
			} );
		}

		$input.on( events, $.debounce( 250, updateUsernameStatus ) );

		$input.closest( 'form' ).on( 'submit', function () {
			// If the username has to be adjusted before it's accepted, server-side check will force the
			// form to be resubmitted. Let's prevent that.
			if ( tweakedUsername !== undefined ) {
				$input.val( tweakedUsername );
			}
		} );
	} );
}( mediaWiki, jQuery ) );
