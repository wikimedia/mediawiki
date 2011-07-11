/**
 * jQuery byteLimit
 *
 * @author Jan Paul Posma
 */
( function( $ ) {

	/**
	 * Enforces a byte limit to a textbox, so that UTF-8 entries are not arbitrarily truncated.
	 */
	$.fn.byteLimit = function( limit ) {

		// Default to current attribute value
		if ( limit == null ) {
			limit = this.attr( 'maxLength' );

		// If passed, update/set attribute value instead
		} else {
			this.attr( 'maxLength', limit );
		}

		// Nothing passed and/or empty attribute, return without binding an event.
		if ( limit == null ) {
			return this;
		}

		// We've got something, go for it:
		return this.keypress( function( e ) {
			// First check to see if this is actually a character key
			// being pressed.
			// Based on key-event info from http://unixpapa.com/js/key.html
			// jQuery should also normalize e.which to be consistent cross-browser,
			// however the same check is still needed regardless of jQuery.
	
			// Note: At the moment, for some older opera versions (~< 10.5)
			// some special keys won't be recognized (aka left arrow key).
			// Backspace will be, so not big issue.
	
			if ( e.which === 0 || e.charCode === 0 || e.which === 8 ||
				e.ctrlKey || e.altKey || e.metaKey )
			{
				return true; //a special key (backspace, etc) so don't interfere.
			}
	
			var len = $.byteLength( this.value );
			// Note that keypress returns a character code point, not a keycode.
			// However, this may not be super reliable depending on how keys come in...
			var charLen = $.byteLength( String.fromCharCode( e.which ) );

			if ( ( len + charLen ) > limit ) {
				e.preventDefault();
			}
		});
	};

} )( jQuery );
