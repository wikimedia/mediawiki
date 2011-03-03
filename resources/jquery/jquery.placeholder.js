/**
 * HTML5 placeholder emulation for jQuery plugin
 *
 * This will automatically use the HTML5 placeholder attribute if supported, or emulate this behavior if not.
 *
 * @author Trevor Parscal <tparscal@wikimedia.org>
 * @author Krinkle <krinklemail@gmail.com>
 * @version 0.2.0
 * @license GPL v2
 */
( function( $ ) {

$.fn.placeholder = function() {

	return this.each( function() {

		// If the HTML5 placeholder attribute is supported, use it
		if ( this.placeholder && 'placeholder' in document.createElement( this.tagName ) ) {
			return;
		}

		var placeholder = this.getAttribute( 'placeholder' );
		var $input = $(this);

		// Show initially, if empty
		if ( this.value === '' || this.value === placeholder ) {
			$input.addClass( 'placeholder' ).val( placeholder );
		}

		$input
			// Show on blur if empty
			.blur( function() {
				if ( this.value === '' ) {
					this.value = placeholder;
					$input.addClass( 'placeholder' );
				}
			} )

			// Hide on focus
			.focus( function() {
				if ( $input.hasClass( 'placeholder' ) ) {
					this.value = '';
					$input.removeClass( 'placeholder' );
				}
			} );

		// Blank on submit -- prevents submitting with unintended value
		if ( this.form ) {
			$( this.form ).submit( function() {
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
} )( jQuery );
