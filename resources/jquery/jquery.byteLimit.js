/**
 * jQuery byteLimit plugin
 *
 * @author Jan Paul Posma, 2011
 * @author Timo Tijhof, 2011-2012
 */
( function ( $, undefined ) {

	/**
	 * Enforces a byte limit to a textbox, so that UTF-8 entries are counted as well, when, for example,
	 * a database field has a byte limit rather than a character limit.
	 * Plugin rationale: Browser has native maxlength for number of characters, this plugin exists to
	 * limit number of bytes instead.
	 *
	 * Can be called with a custom limit (to use that limit instead of the maxlength attribute value),
	 * a filter function (in case the limit should apply to something other than the exact input value),
	 * or both. Order of arguments is important!
	 *
	 * @context {jQuery} Instance of jQuery for one or more input elements
	 * @param limit {Number} [optional] Limit to enforce, fallsback to maxLength-attribute,
	 * called with fetched value as argument.
	 * @param fn {Function} [optional] Function to call on the input string before assessing the length
	 * @return {jQuery} The context
	 */
	$.fn.byteLimit = function ( limit, fn ) {
		// If the first argument is the function,
		// set fn to the first argument's value and ignore the second argument.
		if ( $.isFunction( limit ) ) {
			fn = limit;
			limit = undefined;
		}

		// The following is specific to each element in the collection
		return this.each( function ( i, el ) {
			var $el, elLimit;

			$el = $( el );

			// Default limit to current attribute value
			// Can't re-use 'limit' variable because it's in the higher scope
			// that affects the next each() iteration as well.
			elLimit = limit === undefined ? $el.prop( 'maxLength' ) : limit;
	
			// Update/set attribute value, but only if there is no callback set.
			// If there's a callback set, it's possible that the limit being enforced
			// is too low (ie. if the callback would return "Foo" for "User:Foo").
			// Usually this isn't a problem since browsers ignore maxLength when setting
			// the value property through JavaScript, but Safari 4 violates that rule, so
			// we have to remove or not set the property if we have a callback.
			if ( fn === undefined ) {
				$el.prop( 'maxLength', elLimit );
			} else {
				$el.removeProp( 'maxLength' );
			}
	
			// Nothing passed and/or empty attribute, return without binding an event.
			if ( elLimit === undefined ) {
				return;
			}
	
			// Save function for reference
			$el.data( 'byteLimit-callback', fn );
	
			// We've got something, go for it:
			$el.keypress( function ( e ) {
				var val, len, charLen;
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
					// A special key (backspace, etc) so don't interfere
					return true;
				}
	
				val = fn !== undefined ? fn( $( this ).val() ): $( this ).val();
				len = $.byteLength( val );
				// Note that keypress returns a character code point, not a keycode.
				// However, this may not be super reliable depending on how keys come in...
				charLen = $.byteLength( String.fromCharCode( e.which ) );
	
				if ( ( len + charLen ) > elLimit ) {
					e.preventDefault();
				}
			});
		});
	};
}( jQuery ) );
