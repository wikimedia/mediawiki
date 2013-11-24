/**
 * jQuery byteLimit plugin.
 *
 * @author Jan Paul Posma, 2011
 * @author Timo Tijhof, 2011-2012
 */
( function ( $ ) {

	/**
	 * Utility function to trim down a string, based on byteLimit
	 * and given a safe start position. It supports insertion anywhere
	 * in the string, so "foo" to "fobaro" if limit is 4 will result in
	 * "fobo", not "foba". Basically emulating the native maxlength by
	 * reconstructing where the insertion occured.
	 *
	 * @param {string} safeVal Known value that was previously returned by this
	 * function, if none, pass empty string.
	 * @param {string} newVal New value that may have to be trimmed down.
	 * @param {number} byteLimit Number of bytes the value may be in size.
	 * @param {Function} fn [optional] See $.fn.byteLimit.
	 * @return {Object} Object with:
	 *  - {string} newVal
	 *  - {boolean} trimmed
	 */
	function trimValForByteLength( safeVal, newVal, byteLimit, fn ) {
		var startMatches, endMatches, matchesLen, inpParts,
			oldVal = safeVal;

		// Run the hook if one was provided, but only on the length
		// assessment. The value itself is not to be affected by the hook.
		if ( $.byteLength( fn ? fn( newVal ) : newVal ) <= byteLimit ) {
			// Limit was not reached, just remember the new value
			// and let the user continue.
			return {
				newVal: newVal,
				trimmed: false
			};
		}

		// Current input is longer than the active limit.
		// Figure out what was added and limit the addition.
		startMatches = 0;
		endMatches = 0;

		// It is important that we keep the search within the range of
		// the shortest string's length.
		// Imagine a user adds text that matches the end of the old value
		// (e.g. "foo" -> "foofoo"). startMatches would be 3, but without
		// limiting both searches to the shortest length, endMatches would
		// also be 3.
		matchesLen = Math.min( newVal.length, oldVal.length );

		// Count same characters from the left, first.
		// (if "foo" -> "foofoo", assume addition was at the end).
		while (
			startMatches < matchesLen &&
			oldVal.charAt( startMatches ) === newVal.charAt( startMatches )
		) {
			startMatches += 1;
		}

		while (
			endMatches < ( matchesLen - startMatches ) &&
			oldVal.charAt( oldVal.length - 1 - endMatches ) === newVal.charAt( newVal.length - 1 - endMatches )
		) {
			endMatches += 1;
		}

		inpParts = [
			// Same start
			newVal.substring( 0, startMatches ),
			// Inserted content
			newVal.substring( startMatches, newVal.length - endMatches ),
			// Same end
			newVal.substring( newVal.length - endMatches )
		];

		// Chop off characters from the end of the "inserted content" string
		// until the limit is statisfied.
		if ( fn ) {
			// stop, when there is nothing to slice - bug 41450
			while ( $.byteLength( fn( inpParts.join( '' ) ) ) > byteLimit && inpParts[1].length > 0 ) {
				inpParts[1] = inpParts[1].slice( 0, -1 );
			}
		} else {
			while ( $.byteLength( inpParts.join( '' ) ) > byteLimit ) {
				inpParts[1] = inpParts[1].slice( 0, -1 );
			}
		}

		newVal = inpParts.join( '' );

		return {
			newVal: newVal,
			trimmed: true
		};
	}

	var eventKeys = [
		'keyup.byteLimit',
		'keydown.byteLimit',
		'change.byteLimit',
		'mouseup.byteLimit',
		'cut.byteLimit',
		'paste.byteLimit',
		'focus.byteLimit',
		'blur.byteLimit'
	].join( ' ' );

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
			var $el, elLimit, prevSafeVal;

			$el = $( el );

			// If no limit was passed to byteLimit(), use the maxlength value.
			// Can't re-use 'limit' variable because it's in the higher scope
			// that would affect the next each() iteration as well.
			// Note that we use attribute to read the value instead of property,
			// because in Chrome the maxLength property by default returns the
			// highest supported value (no indication that it is being enforced
			// by choice). We don't want to bind all of this for some ridiculously
			// high default number, unless it was explicitly set in the HTML.
			// Also cast to a (primitive) number (most commonly because the maxlength
			// attribute contains a string, but theoretically the limit parameter
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

			// Remove old event handlers (if there are any)
			$el.off( '.byteLimit' );

			if ( fn ) {
				// Disable the native maxLength (if there is any), because it interferes
				// with the (differently calculated) byte limit.
				// Aside from being differently calculated (average chars with byteLimit
				// is lower), we also support a callback which can make it to allow longer
				// values (e.g. count "Foo" from "User:Foo").
				// maxLength is a strange property. Removing or setting the property to
				// undefined directly doesn't work. Instead, it can only be unset internally
				// by the browser when removing the associated attribute (Firefox/Chrome).
				// http://code.google.com/p/chromium/issues/detail?id=136004
				$el.removeAttr( 'maxlength' );

			} else {
				// If we don't have a callback the bytelimit can only be lower than the charlimit
				// (that is, there are no characters less than 1 byte in size). So lets (re-)enforce
				// the native limit for efficiency when possible (it will make the while-loop below
				// faster by there being less left to interate over).
				$el.attr( 'maxlength', elLimit );
			}


			// Safe base value, used to determine the path between the previous state
			// and the state that triggered the event handler below - and enforce the
			// limit approppiately (e.g. don't chop from the end if text was inserted
			// at the beginning of the string).
			prevSafeVal = '';

			// We need to listen to after the change has already happened because we've
			// learned that trying to guess the new value and canceling the event
			// accordingly doesn't work because the new value is not always as simple as:
			// oldValue + String.fromCharCode( e.which ); because of cut, paste, select-drag
			// replacements, and custom input methods and what not.
			// Even though we only trim input after it was changed (never prevent it), we do
			// listen on events that input text, because there are cases where the text has
			// changed while text is being entered and keyup/change will not be fired yet
			// (such as holding down a single key, fires keydown, and after each keydown,
			// we can trim the previous one).
			// See http://www.w3.org/TR/DOM-Level-3-Events/#events-keyboard-event-order for
			// the order and characteristics of the key events.
			$el.on( eventKeys, function () {
				var res = trimValForByteLength(
					prevSafeVal,
					this.value,
					elLimit,
					fn
				);

				// Only set value property if it was trimmed, because whenever the
				// value property is set, the browser needs to re-initiate the text context,
				// which moves the cursor at the end the input, moving it away from wherever it was.
				// This is a side-effect of limiting after the fact.
				if ( res.trimmed === true ) {
					this.value = res.newVal;
				}
				// Always adjust prevSafeVal to reflect the input value. Not doing this could cause
				// trimValForByteLength to compare the new value to an empty string instead of the
				// old value, resulting in trimming always from the end (bug 40850).
				prevSafeVal = res.newVal;
			} );
		} );
	};
}( jQuery ) );
