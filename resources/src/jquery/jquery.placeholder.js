/**
 * HTML5 placeholder emulation for jQuery plugin
 *
 * This will automatically use the HTML5 placeholder attribute if supported, or emulate this behavior if not.
 *
 * This is a fork from Mathias Bynens' jquery.placeholder as of this commit
 * https://github.com/mathiasbynens/jquery-placeholder/blob/47f05d400e2dd16b59d144141a2cf54a9a77c502/jquery.placeholder.js
 *
 * @author Mathias Bynens <http://mathiasbynens.be/>
 * @author Trevor Parscal <tparscal@wikimedia.org>, 2012
 * @author Krinkle <krinklemail@gmail.com>, 2012
 * @author Alex Ivanov <alexivanov97@gmail.com>, 2013
 * @version 2.1.0
 * @license MIT
 */
( function ( $ ) {

	var isInputSupported = 'placeholder' in document.createElement( 'input' ),
		isTextareaSupported = 'placeholder' in document.createElement( 'textarea' ),
		prototype = $.fn,
		valHooks = $.valHooks,
		propHooks = $.propHooks,
		hooks,
		placeholder;

	function safeActiveElement() {
		// Avoid IE9 `document.activeElement` of death
		// https://github.com/mathiasbynens/jquery-placeholder/pull/99
		try {
			return document.activeElement;
		} catch ( err ) {}
	}

	function args( elem ) {
		// Return an object of element attributes
		var newAttrs = {},
				rinlinejQuery = /^jQuery\d+$/;
		$.each( elem.attributes, function ( i, attr ) {
			if ( attr.specified && !rinlinejQuery.test( attr.name ) ) {
				newAttrs[ attr.name ] = attr.value;
			}
		} );
		return newAttrs;
	}

	function clearPlaceholder( event, value ) {
		var input = this,
				$input = $( input );
		if ( input.value === $input.attr( 'placeholder' ) && $input.hasClass( 'placeholder' ) ) {
			if ( $input.data( 'placeholder-password' ) ) {
				$input = $input.hide().next().show().attr( 'id', $input.removeAttr( 'id' ).data( 'placeholder-id' ) );
				// If `clearPlaceholder` was called from `$.valHooks.input.set`
				if ( event === true ) {
					$input[ 0 ].value = value;
					return value;
				}
				$input.focus();
			} else {
				input.value = '';
				$input.removeClass( 'placeholder' );
				if ( input === safeActiveElement() ) {
					input.select();
				}
			}
		}
	}

	function setPlaceholder() {
		var $replacement,
				input = this,
				$input = $( input ),
				id = this.id;
		if ( !input.value ) {
			if ( input.type === 'password' ) {
				if ( !$input.data( 'placeholder-textinput' ) ) {
					try {
						$replacement = $input.clone().attr( { type: 'text' } );
					} catch ( e ) {
						$replacement = $( '<input>' ).attr( $.extend( args( this ), { type: 'text' } ) );
					}
					$replacement
							.removeAttr( 'name' )
							.data( {
								'placeholder-password': $input,
								'placeholder-id': id
							} )
							.bind( 'focus.placeholder drop.placeholder', clearPlaceholder );
					$input
							.data( {
								'placeholder-textinput': $replacement,
								'placeholder-id': id
							} )
							.before( $replacement );
				}
				$input = $input.removeAttr( 'id' ).hide().prev().attr( 'id', id ).show();
				// Note: `$input[0] != input` now!
			}
			$input.addClass( 'placeholder' );
			$input[ 0 ].value = $input.attr( 'placeholder' );
		} else {
			$input.removeClass( 'placeholder' );
		}
	}

	function changePlaceholder( text ) {
		var hasArgs = arguments.length,
				$input = this;
		if ( hasArgs ) {
			if ( $input.attr( 'placeholder' ) !== text ) {
				$input.prop( 'placeholder', text );
				if ( $input.hasClass( 'placeholder' ) ) {
					$input[ 0 ].value = text;
				}
			}
		}
	}

	if ( isInputSupported && isTextareaSupported ) {

		placeholder = prototype.placeholder = function ( text ) {
			var hasArgs = arguments.length;

			if ( hasArgs ) {
				changePlaceholder.call( this, text );
			}

			return this;
		};

		placeholder.input = placeholder.textarea = true;

	} else {

		placeholder = prototype.placeholder = function ( text ) {
			var $this = this,
				hasArgs = arguments.length;

			if ( hasArgs ) {
				changePlaceholder.call( this, text );
			}

			$this
				.filter( ( isInputSupported ? 'textarea' : ':input' ) + '[placeholder]' )
				.filter( function () {
					return !$( this ).data( 'placeholder-enabled' );
				} )
				.bind( {
					'focus.placeholder drop.placeholder': clearPlaceholder,
					'blur.placeholder': setPlaceholder
				} )
				.data( 'placeholder-enabled', true )
				.trigger( 'blur.placeholder' );
			return $this;
		};

		placeholder.input = isInputSupported;
		placeholder.textarea = isTextareaSupported;

		hooks = {
			get: function ( element ) {
				var $element = $( element ),
					$passwordInput = $element.data( 'placeholder-password' );
				if ( $passwordInput ) {
					return $passwordInput[ 0 ].value;
				}

				return $element.data( 'placeholder-enabled' ) && $element.hasClass( 'placeholder' ) ? '' : element.value;
			},
			set: function ( element, value ) {
				var $element = $( element ),
					$passwordInput = $element.data( 'placeholder-password' );
				if ( $passwordInput ) {
					$passwordInput[ 0 ].value = value;
					return value;
				}

				if ( !$element.data( 'placeholder-enabled' ) ) {
					element.value = value;
					return value;
				}
				if ( !value ) {
					element.value = value;
					// Issue #56: Setting the placeholder causes problems if the element continues to have focus.
					if ( element !== safeActiveElement() ) {
						// We can't use `triggerHandler` here because of dummy text/password inputs :(
						setPlaceholder.call( element );
					}
				} else if ( $element.hasClass( 'placeholder' ) ) {
					if ( !clearPlaceholder.call( element, true, value ) ) {
						element.value = value;
					}
				} else {
					element.value = value;
				}
				// `set` can not return `undefined`; see http://jsapi.info/jquery/1.7.1/val#L2363
				return $element;
			}
		};

		if ( !isInputSupported ) {
			valHooks.input = hooks;
			propHooks.value = hooks;
		}
		if ( !isTextareaSupported ) {
			valHooks.textarea = hooks;
			propHooks.value = hooks;
		}

		$( function () {
			// Look for forms
			$( document ).delegate( 'form', 'submit.placeholder', function () {
				// Clear the placeholder values so they don't get submitted
				var $inputs = $( '.placeholder', this ).each( clearPlaceholder );
				setTimeout( function () {
					$inputs.each( setPlaceholder );
				}, 10 );
			} );
		} );

		// Clear placeholder values upon page reload
		$( window ).bind( 'beforeunload.placeholder', function () {
			$( '.placeholder' ).each( function () {
				this.value = '';
			} );
		} );

	}
}( jQuery ) );
