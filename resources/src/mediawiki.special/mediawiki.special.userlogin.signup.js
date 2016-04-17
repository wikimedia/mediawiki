/*!
 * JavaScript for signup form.
 */
( function ( mw, $ ) {
	// Check if the username is invalid or already taken
	$( function () {
		var
			// We need to hook to all of these events to be sure we are notified of all changes to the
			// value of an <input type=text> field.
			events = 'keyup keydown change mouseup cut paste focus blur',
			$input = $( '#wpName2' ),
			$statusContainer = $( '#userlogin2' ).find( '.mw-htmlform-status-area' ),
			api = new mw.Api(),
			currentRequest;

		// Hide any present status messages.
		function clearStatus() {
			$statusContainer.slideUp( function () {
				$statusContainer.empty();
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
				ususers: username, // '|' in usernames is handled below
				usprop: 'cancreate',
				uselang: mw.config.get( 'wgUserLanguage' )
			} ).done( function ( resp ) {
					var userinfo = resp.query.users[ 0 ];

					if ( resp.query.users.length !== 1 ) {
						// Happens if the user types '|' into the field
						d.resolve( { state: 'invalid', username: null } );
					} else if ( userinfo.invalid !== undefined ) {
						d.resolve({state: 'invalid', username: null});
					} else if ( userinfo.userid !== undefined ) {
						d.resolve( { state: 'taken', username: null } );
					} else if ( userinfo['cancreate-error'] !== undefined ) {
						// some authentication provider is blocking this name
						d.resolve( { state: 'cantcreate', username: null,
							message: userinfo['cancreate-error'] } );
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
					} else if ( info.state === 'cantcreate' ) {
						message = info.message;
					}

					$statusContainer
						.empty()
						.append(
							// Ugh…
							$( '<strong>' ).text( mw.message( 'createacct-error' ).text() ),
							$( '<br>' ),
							document.createTextNode( message )
						)
						.wrapInner( '<div class="error">' )
						.slideDown();
				}
			} ).fail( function () {
				clearStatus();
			} );
		}

		$input.on( events, $.debounce( 1000, updateUsernameStatus ) );
	} );
}( mediaWiki, jQuery ) );
