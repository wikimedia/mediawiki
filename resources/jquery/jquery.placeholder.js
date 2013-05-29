/**
 * HTML5 placeholder emulation for jQuery plugin
 *
 * This will automatically use the HTML5 placeholder attribute if supported, or emulate this behavior if not.
 *
 * @author Trevor Parscal <tparscal@wikimedia.org>, 2012
 * @author Krinkle <krinklemail@gmail.com>, 2012
 * @version 0.2.0
 * @license GPL v2
 */
( function ( $ ) {

	$.fn.placeholder = function () {

		return this.each( function () {
			var placeholder, $input, attributes, $originalInput, $textClone, isPassword,
			// Also listen for other events in case $input was
			// already focused when the events were bound
			focusEvents = 'focus drop keydown paste';

			function handleBlur() {
				// Show on blur if empty
				if ( $input.val() === '' ) {
					if ( isPassword ) {
						switchToText();
					} else {
						$input.val( placeholder );
						$input.addClass( 'placeholder' );
					}
				}
			}

			function handleFocus( e ) {
				var el;

				// Hide on focus
				if ( $input.hasClass( 'placeholder' ) ) {
					if ( isPassword ) {
						switchToPassword();
					} else {
						$input.removeClass( 'placeholder' );
					}

					el = $input[0];

					if ( e.type === 'drop' && e.originalEvent.dataTransfer ) {
						// Support for drag&drop. Instead of inserting the dropped
						// text somewhere in the middle of the placeholder string,
						// we want to set the contents of the box to the
						// dropped text.

						// IE wants getData( 'text' ) but Firefox wants getData( 'text/plain' )
						// Firefox fails gracefully with an empty string, IE barfs with an error
						try {
							// Try the Firefox way
							el.value = e.originalEvent.dataTransfer.getData( 'text/plain' );
						} catch ( exception ) {
							// Got an exception, so use the IE way
							el.value = e.originalEvent.dataTransfer.getData( 'text' );
						}

						// On Firefox, drop fires after the dropped text has been inserted,
						// but on IE it fires before. If we don't prevent the default action,
						// IE will insert the dropped text twice.
						e.preventDefault();
					} else {
						el.value = '';
					}
				}
			}

			// replaceWith does not preserve event bindings.
			// We rebind the ones we need, but this would also be a problem if there was a direct binding to a password field.

			function switchToText() {
				$input.replaceWith( $textClone );
				$input = $textClone;
				$input.on( focusEvents, handleFocus );
			}

			function switchToPassword() {
				$input.replaceWith( $originalInput );
				$input = $originalInput;
				$input.blur( handleBlur );
				// Make sure the new element (replacement) is focused.
				// setTimeout is necessary for IE.
				window.setTimeout( function () {
					$input.focus();
				}, 10 );
			}

			// If the HTML5 placeholder attribute is supported, use it
			if ( this.placeholder && 'placeholder' in document.createElement( this.tagName ) ) {
				return;
			}

			placeholder = this.getAttribute( 'placeholder' );
			$input = $(this);

			// Blank on submit -- prevents submitting with unintended value
			if ( this.form ) {
				$( this.form ).submit( function () {
					// $input.trigger( 'focus' ); would be problematic
					// because it actually focuses $input, leading
					// to nasty behavior in mobile browsers
					if ( $input.hasClass( 'placeholder' ) ) {
						$input
							.val( '' )
							.removeClass( 'placeholder' );
					}
				});
			}

			isPassword = false;
			$originalInput = $input;

			// You can't dynamically switch an input's type in IE.
			// So instead we swap them out.
			if ( $originalInput.attr( 'type' ) === 'password' ) {
				attributes = $originalInput.getAttrs();
				attributes.type = 'text';
				$textClone = $( '<input>' ).attr( attributes );

				// We can just set up the class and val one time, then swap as needed
				$textClone
					.addClass( 'placeholder' )
					.val( placeholder )
					.on( focusEvents, handleFocus );
				isPassword = true;

				$originalInput.blur( handleBlur );
			}

			// Show initially, if empty
			if ( $originalInput.val() === '' || $originalInput.val() === placeholder ) {
				if ( isPassword ) {
					switchToText();
				} else {
					$input.addClass( 'placeholder' );
					$input.val( placeholder );
				}
			}

			$input
				.blur( handleBlur )
				.on( 'focus drop keydown paste', handleFocus );
		});
	};

}( jQuery ) );
