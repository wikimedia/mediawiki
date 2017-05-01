/*!
 * No-op for compatibility with code from before we used
 * native placeholder in all supported browsers.
 */

( function ( $ ) {
	var placeholder;

	placeholder = $.fn.placeholder = function ( text ) {
		if ( arguments.length ) {
			this.prop( 'placeholder', text );
		}
		return this;
	};

	placeholder.input = placeholder.textarea = true;

}( jQuery ) );
