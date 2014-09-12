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
			// We need to hook to all of these events to be sure we are notified of all changes to the
			// value of an <input type=text> field.
			events = 'keyup keydown change mouseup cut paste focus blur',
			$input = $( '#wpName2' ),
			$statusContainer = $( '#mw-createacct-status-area' ),
			api = new mw.Api(),
			currentRequest;

		// Hide any present status messages.
		function clearStatus() {
			$statusContainer.slideUp( function () {
				$statusContainer
					.removeAttr( 'class' )
					.empty();
			} );
		}

		// Returns a promise receiving a { state:, username: } object, where:
		// * 'state' is one of 'invalid', 'taken', 'ok'
		// * 'username' is the validated username if 'state' is 'ok', null otherwise (if it's not
		//   possible to register such an account)
		function checkUsername( username ) {
			// We could just use .then() if we didn't have to pass on .abort()…
			var d, apiPromise;

			d = $.Deferred();
			apiPromise = api.get( {
				action: 'query',
				list: 'users',
				ususers: username // '|' in usernames is handled below
			} )
				.done( function ( resp ) {
					var userinfo = resp.query.users[0];

					if ( resp.query.users.length !== 1 ) {
						// Happens if the user types '|' into the field
						d.resolve( { state: 'invalid', username: null } );
					} else if ( userinfo.invalid !== undefined ) {
						d.resolve( { state: 'invalid', username: null } );
					} else if ( userinfo.userid !== undefined ) {
						d.resolve( { state: 'taken', username: null } );
					} else {
						d.resolve( { state: 'ok', username: username } );
					}
				} )
				.fail( d.reject );

			return d.promise( { abort: apiPromise.abort } );
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
				clearStatus();
				return;
			}

			currentRequest = currentRequestInternal = checkUsername( username ).done( function ( info ) {
				var message;

				// Another request was fired in the meantime, the result we got here is no longer current.
				// This shouldn't happen as we abort pending requests, but you never know.
				if ( currentRequest !== currentRequestInternal ) {
					return;
				}
				// If we're here, then the current request has finished, avoid calling .abort() needlessly.
				currentRequest = undefined;

				if ( info.state === 'ok' ) {
					clearStatus();
				} else {
					if ( info.state === 'invalid' ) {
						message = mw.message( 'noname' ).text();
					} else if ( info.state === 'taken' ) {
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
				clearStatus();
			} );
		}

		$input.on( events, $.debounce( 250, updateUsernameStatus ) );
	} );
}( mediaWiki, jQuery ) );
