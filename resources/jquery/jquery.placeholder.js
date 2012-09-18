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
			var placeholder, $input;

			// If the HTML5 placeholder attribute is supported, use it
			if ( this.placeholder && 'placeholder' in document.createElement( this.tagName ) ) {
				return;
			}

			placeholder = this.getAttribute( 'placeholder' );
			$input = $(this);

			// Show initially, if empty
			if ( this.value === '' || this.value === placeholder ) {
				$input.addClass( 'placeholder' ).val( placeholder );
			}

			$input
				// Show on blur if empty
				.blur( function () {
					if ( this.value === '' ) {
						this.value = placeholder;
						$input.addClass( 'placeholder' );
					}
				} )

				// Hide on focus
				// Also listen for other events in case $input was
				// already focused when the events were bound
				.on( 'focus drop keydown paste', function ( e ) {
					if ( $input.hasClass( 'placeholder' ) ) {
						if ( e.type === 'drop' && e.originalEvent.dataTransfer ) {
							// Support for drag&drop. Instead of inserting the dropped
							// text somewhere in the middle of the placeholder string,
							// we want to set the contents of the search box to the
							// dropped text.

							// IE wants getData( 'text' ) but Firefox wants getData( 'text/plain' )
							// Firefox fails gracefully with an empty string, IE barfs with an error
							try {
								// Try the Firefox way
								this.value = e.originalEvent.dataTransfer.getData( 'text/plain' );
							} catch ( exception ) {
								// Got an exception, so use the IE way
								this.value = e.originalEvent.dataTransfer.getData( 'text' );
							}

							// On Firefox, drop fires after the dropped text has been inserted,
							// but on IE it fires before. If we don't prevent the default action,
							// IE will insert the dropped text twice.
							e.preventDefault();
						} else {
							this.value = '';
						}
						$input.removeClass( 'placeholder' );
					}
				} );

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

		});
	};

}( jQuery ) );
