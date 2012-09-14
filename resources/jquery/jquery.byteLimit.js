/**
 * jQuery byteLimit plugin.
 *
 * @author Jan Paul Posma, 2011
 * @author Timo Tijhof, 2011-2012
 */
( function ( $ ) {

	/**
	 * Enforces a byte limit on an input field, so that UTF-8 entries are counted as well,
	 * when, for example, a database field has a byte limit rather than a character limit.
	 * Plugin rationale: Browser has native maxlength for number of characters, this plugin
	 * exists to limit number of bytes instead.
	 *
	 * Can be called with a custom limit (to use that limit instead of the maxlength attribute
	 * value), a filter function (in case the limit should apply to something other than the
	 * exact input value), or both. Order of parameters is important!
	 *
	 * @context {jQuery} Instance of jQuery for one or more input elements
	 * @param {Number} limit [optional] Limit to enforce, fallsback to maxLength-attribute,
	 *  called with fetched value as argument.
	 * @param {Function} fn [optional] Function to call on the string before assessing the length.
	 * @return {jQuery} The context
	 */
	$.fn.byteLimit = function ( limit, fn ) {
		// If the first argument is the function,
		// set fn to the first argument's value and ignore the second argument.
		if ( $.isFunction( limit ) ) {
			fn = limit;
			limit = undefined;
		// Either way, verify it is a function so we don't have to call
		// isFunction again after this.
		} else if ( !fn || !$.isFunction( fn ) ) {
			fn = undefined;
		}

		// The following is specific to each element in the collection.
		return this.each( function ( i, el ) {
			var $el, elLimit;

			$el = $( el );

			// If no limit was passed to byteLimit(), use the maxlength value.
			// Can't re-use 'limit' variable because it's in the higher scope
			// that would affect the next each() iteration as well.
			// Note that we use attribute to read the value instead of property,
			// because in Chrome the maxLength property by default returns the
			// highest supported value (no indiciation that it is being enforced
			// by choice). We don't want to bind all of this for some redicilous
			// high default number, unless it was explicitly set in the HTML.
			// Also cast to a (primitive) number (most commonly because the maxlength
			// attribute contains a string, but theoratically the limit parameter
			// could be something else as well).
			elLimit = Number( limit === undefined ? $el.attr( 'maxlength' ) : limit );

			// If there is no (valid) limit passed or found in the property,
			// skip this. The < 0 check is required for Firefox, which returns
			// -1  (instead of undefined) for maxLength if it is not set.
			if ( !elLimit || elLimit < 0 ) {
				return;
			}

			if ( fn ) {
				// Save function for reference
				$el.data( 'byteLimit.callback', fn );
			}

			$el
				// Disable the native maxLength (if there is any), because it interferes
				// with the (differently calculated) byte limit.
				// Aside from being differently calculated (average chars with byteLimit
				// is lower), we also support a callback which can make it to allow longer
				// values (e.g. count "Foo" from "User:Foo").
				// maxLength is a strange property. Removing or setting the property to
				// undefined directly doesn't work. Instead, it can only be unset internally
				// by the browser when removing the associated attribute (Firefox/Chrome).
				// http://code.google.com/p/chromium/issues/detail?id=136004
				.removeAttr( 'maxlength' )

				// Safe base value, used to determine the path between the previous state
				// and the state that triggered the event handler below - and enforce the
				// limit approppiately (e.g. don't chop from the end if text was inserted
				// at the beginning of the string).
				.data( 'byteLimit.value', '' )

				// Remove old event handlers (if there are any)
				.off( '.byteLimit' );

			// Use keyup and change instead of keypress,
			// See http://www.w3.org/TR/DOM-Level-3-Events/#events-keyboard-event-order for
			// the order and characteristics of the key events.
			// We need to listen to after the change has already happened because we've
			// learned that trying to guess the new value and canceling the event
			// accordingly doesn't work because the new value is not always as simple as:
			// oldValue + String.fromCharCode( e.which ); because of cut, paste, select-drag
			// replacements, and custom input methods and what not.
			$el.on( 'keyup.byteLimit change.byteLimit', function ( e ) {
				var inpVal, oldVal, newVal,
					matchStart, matchEnd, matchLen, inpParts, insertedMax,
					el = this;

				inpVal = el.value;
				oldVal = $.data( el, 'byteLimit.value' );

				// Run the hook if one was provided, but only on the length
				// assessment. The value itself is not to be affected by the hook.
				if ( $.byteLength( fn ? fn( inpVal ) : inpVal ) <= elLimit ) {
					// Limit was not reached, just remember the value
					// and let the user continue.
					$.data( el, 'byteLimit.value', inpVal );
					return;
				}

				// Current input is longer than the active limit.
				// Figure out what was added and limit the addition.
				matchStart = 0;
				matchEnd = 0;
				// It is important that neither don't exceed either string.
				// Imagine the new value adding content that matches the end of
				// the old value (e.g. "foo" -> "foofoo"). matchStart would be 3.
				// but without limiting both to the Math.min length, then matchEnd
				// would also be 3.
				matchLen = Math.min( inpVal.length, oldVal.length );

				// Count same characters from the left, first.
				// (if "foo" -> "foofoo", assume addition was at the end).
				while (
					matchStart < matchLen &&
					oldVal[matchStart] === inpVal[matchStart]
				) {
					matchStart += 1;
				}

				while (
					matchEnd <  ( matchLen - matchStart ) &&
					oldVal[oldVal.length - 1 - matchEnd] === inpVal[inpVal.length - 1 - matchEnd]
				) {
					matchEnd += 1;
				}

				inpParts = [
					// Same start
					inpVal.substring( 0, matchStart ),
					// Inserted content
					inpVal.substring( matchStart, inpVal.length - matchEnd ),
					// Same end
					inpVal.substring( inpVal.length - matchEnd )
				];

				// Chop off last character until limit is statisfied
				if ( fn ) {
					while ( $.byteLength( fn( inpParts.join( '' ) ) ) > elLimit ) {
						inpParts[1] = inpParts[1].slice( 0, -1 );
					}
				} else {
					while ( $.byteLength( inpParts.join( '' ) ) > elLimit ) {
						inpParts[1] = inpParts[1].slice( 0, -1 );
					}
				}

				newVal = inpParts.join( '' );

				el.value = newVal;
				$.data( el, 'byteLimit.value', newVal );
			} );
		} );
	};
}( jQuery ) );
