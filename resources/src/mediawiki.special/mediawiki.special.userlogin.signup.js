/*!
 * JavaScript for signup form.
 */
( function ( mw, $ ) {
	// When sending password by email, mark the email field as required.
	// The password input fields are hidden automatically with the 'hide-if' parameters.
	$( function () {
		// Always required if checked, otherwise it depends, so we use the original
		var
			emailField = OO.ui.TextInputWidget.static.infuse( 'wpEmail' ),
			emailFieldLayout = OO.ui.FieldLayout.static.infuse(
				$( '#wpEmail' ).closest( '.oo-ui-fieldLayout' )
			),
			originalText = emailFieldLayout.getLabel(),
			// FIXME There is no getter/setter for the 'required' config parameter
			originalRequired = emailField.getIndicator() === 'required',
			requiredText = mw.message( 'createacct-emailrequired' ).text(),
			createByMailCheckbox = OO.ui.CheckboxInputWidget.static.infuse( 'wpCreateaccountMail' );

		function updateForCheckbox() {
			var checked = createByMailCheckbox.isSelected();
			if ( checked ) {
				emailFieldLayout.setLabel( requiredText );
				emailField.setIndicator( 'required' );
				// FIXME There is no getter/setter for the 'required' config parameter
				emailField.$input.attr( 'required', 'required' );
				emailField.$input.attr( 'aria-required', 'true' );
			} else {
				emailFieldLayout.setLabel( originalText );
				if ( !originalRequired ) {
					emailField.setIndicator( null );
					// FIXME There is no getter/setter for the 'required' config parameter
					emailField.$input.removeAttr( 'required' );
					emailField.$input.removeAttr( 'aria-required' );
				}
			}
		}

		createByMailCheckbox.on( 'change', updateForCheckbox );
		updateForCheckbox();
	} );

	// Check if the username is invalid or already taken
	$( function () {
		var
			input = OO.ui.TextInputWidget.static.infuse( $( '#wpName2' ) ),
			statusContainer = OO.ui.FieldLayout.static.infuse(
				$( '#wpName2' ).closest( '.oo-ui-fieldLayout' )
			),
			api = new mw.Api(),
			currentRequest;

		// Hide any present status messages.
		function clearStatus() {
			statusContainer.setErrors( [] );
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
					var userinfo = resp.query.users[ 0 ];

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
				username = $.trim( input.getValue() ),
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

					statusContainer.setErrors( [ message ] );
				}
			} ).fail( function () {
				clearStatus();
			} );
		}

		input.on( 'change', $.debounce( 1000, updateUsernameStatus ) );
	} );
}( mediaWiki, jQuery ) );
