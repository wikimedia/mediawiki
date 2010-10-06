/**
 * HTML5 placeholder emulation for jQuery plugin
 * 
 * This will automatically use the HTML5 placeholder attribute if supported, or emulate this behavior if not.
 * 
 * @author Trevor Parscal <tparscal@wikimedia.org>
 * @version 0.1.0
 * @license GPL v2
 */

jQuery.fn.placeholder = function( text ) {
	// If the HTML5 placeholder attribute is supported, use it
	if ( 'placeholder' in document.createElement( 'input' ) ) {
		jQuery(this).attr( 'placeholder', text );
	}
	// Otherwise, use a combination of blur and focus event handlers and a placeholder class
	else {
		jQuery(this).each( function() {
			var $input = jQuery(this);
			$input
				// Show on blur if empty
				.bind( 'blur', function() {
					if ( $input.val().length == 0 ) {
						$input
							.val( text )
							.addClass( 'placeholder' );
						}
					} )
				// Hide on focus
				.bind( 'focus', function() {
					if ( $input.hasClass( 'placeholder' ) ) {
						$input
							.val( '' )
							.removeClass( 'placeholder' );
					}
				} )
				// Blank on submit -- prevents submitting with unintended value
				.parents( 'form' )
					.bind( 'submit', function() {
						$input.trigger( 'focus' );
					} );
			// Show initially, if empty
			if ( $input.val() == '' ) {
				$input.trigger( 'blur' );
			}
		} );
	}
};
