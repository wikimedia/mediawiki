/*!
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

	// A class to wrap the logic for validating a field in a non-OOUI HTMLForm.
	function HtmlFormChecker( $element, validator ) {
		this.validator = validator;
		this.$element = $element;

		this.$errorBox = $element.next( 'span.error' );
		if ( !this.$errorBox.length ) {
			this.$errorBox = $( '<span>' );
			$element.after( this.$errorBox );
		}
	}

	HtmlFormChecker.prototype.attach = function ( $extraElements ) {
		var $e,
			// We need to hook to all of these events to be sure we are
			// notified of all changes to the value of an <input type=text>
			// field.
			events = 'keyup keydown change mouseup cut paste focus blur';

		$e = this.$element;
		if ( $extraElements ) {
			$e = $e.add( $extraElements );
		}
		$e.on( events, $.debounce( 1000, this.validate.bind( this ) ) );
	};

	HtmlFormChecker.prototype.validate = function () {
		var currentRequestInternal,
			that = this,
			value = $.trim( this.$element.val() );

		// Abort any pending requests.
		if ( this.currentRequest ) {
			this.currentRequest.abort();
		}

		if ( value === '' ) {
			this.setErrors( [] );
			return;
		}

		this.currentRequest = currentRequestInternal = this.validator( value )
			.done( function ( info ) {
				// Another request was fired in the meantime, the result we got here is no longer current.
				// This shouldn't happen as we abort pending requests, but you never know.
				if ( that.currentRequest !== currentRequestInternal ) {
					return;
				}
				// If we're here, then the current request has finished, avoid calling .abort() needlessly.
				that.currentRequest = undefined;

				if ( info.valid ) {
					that.setErrors( [] );
				} else {
					that.setErrors( info.messages );
				}
			} ).fail( function () {
				that.setErrors( [] );
			} );
	};

	HtmlFormChecker.prototype.setErrors = function ( errors ) {
		var $errorBox = this.$errorBox;

		if ( errors.length === 0 ) {
			$errorBox.slideUp( function () {
				$errorBox
					.removeAttr( 'class' )
					.empty();
			} );
		} else {
			$errorBox
				.attr( 'class', 'error' )
				.empty()
				.append( errors.map( function ( e ) {
					return $( '<div>' ).append( e );
				} ) )
				.slideDown();
		}
	};

	// Check if the username is invalid or already taken
	mw.hook( 'htmlform.enhance' ).add( function ( $root ) {
		var $usernameInput = $root.find( '#wpName2' ),
			$passwordInput = $root.find( '#wpPassword2' ),
			$emailInput = $root.find( '#wpEmail' ),
			$realNameInput = $root.find( '#wpRealName' ),
			api = new mw.Api(),
			usernameChecker, passwordChecker;

		function checkUsername( username ) {
			// We could just use .then() if we didn't have to pass on .abort()…
			var d, apiPromise;

			d = $.Deferred();
			apiPromise = api.get( {
				action: 'query',
				list: 'users',
				ususers: username,
				usprop: 'cancreate',
				formatversion: 2,
				errorformat: 'html',
				errorsuselocal: true,
				uselang: mw.config.get( 'wgUserLanguage' )
			} )
				.done( function ( resp ) {
					var userinfo = resp.query.users[ 0 ];

					if ( resp.query.users.length !== 1 || userinfo.invalid ) {
						d.resolve( { valid: false, messages: [ mw.message( 'noname' ).parseDom() ] } );
					} else if ( userinfo.userid !== undefined ) {
						d.resolve( { valid: false, messages: [ mw.message( 'userexists' ).parseDom() ] } );
					} else if ( !userinfo.cancreate ) {
						d.resolve( {
							valid: false,
							messages: userinfo.cancreateerror ? userinfo.cancreateerror.map( function ( m ) {
								return m.html;
							} ) : []
						} );
					} else {
						d.resolve( { valid: true, messages: [] } );
					}
				} )
				.fail( d.reject );

			return d.promise( { abort: apiPromise.abort } );
		}

		function checkPassword() {
			// We could just use .then() if we didn't have to pass on .abort()…
			var d, apiPromise;

			d = $.Deferred();
			apiPromise = api.post( {
				action: 'validatepassword',
				user: $usernameInput.val(),
				password: $passwordInput.val(),
				email: $emailInput.val() || '',
				realname: $realNameInput.val() || '',
				formatversion: 2,
				errorformat: 'html',
				errorsuselocal: true,
				uselang: mw.config.get( 'wgUserLanguage' )
			} )
				.done( function ( resp ) {
					var pwinfo = resp.validatepassword || {};

					d.resolve( {
						valid: pwinfo.validity === 'Good',
						messages: pwinfo.validitymessages ? pwinfo.validitymessages.map( function ( m ) {
							return m.html;
						} ) : []
					} );
				} )
				.fail( d.reject );

			return d.promise( { abort: apiPromise.abort } );
		}

		usernameChecker = new HtmlFormChecker( $usernameInput, checkUsername );
		usernameChecker.attach();

		passwordChecker = new HtmlFormChecker( $passwordInput, checkPassword );
		passwordChecker.attach( $usernameInput.add( $emailInput ).add( $realNameInput ) );
	} );
}( mediaWiki, jQuery ) );
