/**
 * jQuery byteLimit
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

		// Nothing passed and/or empty attribute, return this for further chaining.
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
	
			// This basically figures out how many bytes a UTF-16 string (which is what js sees)
			// will take in UTF-8 by replacing a 2 byte character with 2 *'s, etc, and counting that.
			// Note, surrogate (\uD800-\uDFFF) characters are counted as 2 bytes, since there's two of them
			// and the actual character takes 4 bytes in UTF-8 (2*2=4). Might not work perfectly in
			// edge cases such as illegal sequences, but that should never happen.
	
			var len = this.value
				.replace( /[\u0080-\u07FF\uD800-\uDFFF]/g, '**' )
				.replace( /[\u0800-\uD7FF\uE000-\uFFFF]/g, '***' )
				.length;

			// limit-3 as this doesn't count the character about to be inserted.
			if ( len > ( limit-3 ) ) {
				e.preventDefault();
			}
		});
	};

} )( jQuery );
