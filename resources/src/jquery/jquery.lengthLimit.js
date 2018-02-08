/**
 * @class jQuery.plugin.lengthLimit
 */
( function ( $, mw ) {

	var
		eventKeys = [
			'keyup.lengthLimit',
			'keydown.lengthLimit',
			'change.lengthLimit',
			'mouseup.lengthLimit',
			'cut.lengthLimit',
			'paste.lengthLimit',
			'focus.lengthLimit',
			'blur.lengthLimit'
		].join( ' ' ),
		trimByteLength = require( 'mediawiki.String' ).trimByteLength,
		trimCodePointLength = require( 'mediawiki.String' ).trimCodePointLength;

	/**
	 * Utility function to trim down a string, based on byteLimit
	 * and given a safe start position. It supports insertion anywhere
	 * in the string, so "foo" to "fobaro" if limit is 4 will result in
	 * "fobo", not "foba". Basically emulating the native maxlength by
	 * reconstructing where the insertion occurred.
	 *
	 * @method trimByteLength
	 * @deprecated Use `require( 'mediawiki.String' ).trimByteLength` instead.
	 * @static
	 * @param {string} safeVal Known value that was previously returned by this
	 * function, if none, pass empty string.
	 * @param {string} newVal New value that may have to be trimmed down.
	 * @param {number} byteLimit Number of bytes the value may be in size.
	 * @param {Function} [filterFn] See jQuery#byteLimit.
	 * @return {Object}
	 * @return {string} return.newVal
	 * @return {boolean} return.trimmed
	 */
	mw.log.deprecate( $, 'trimByteLength', trimByteLength,
		'Use require( \'mediawiki.String\' ).trimByteLength instead.', '$.trimByteLength' );

	function lengthLimit( trimFn, limit, filterFn ) {
		var allowNativeMaxlength = trimFn === trimByteLength;

		// If the first argument is the function,
		// set filterFn to the first argument's value and ignore the second argument.
		if ( $.isFunction( limit ) ) {
			filterFn = limit;
			limit = undefined;
		// Either way, verify it is a function so we don't have to call
		// isFunction again after this.
		} else if ( !filterFn || !$.isFunction( filterFn ) ) {
			filterFn = undefined;
		}

		// The following is specific to each element in the collection.
		return this.each( function ( i, el ) {
			var $el, elLimit, prevSafeVal;

			$el = $( el );

			// If no limit was passed to lengthLimit(), use the maxlength value.
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

			if ( filterFn ) {
				// Save function for reference
				$el.data( 'lengthLimit.callback', filterFn );
			}

			// Remove old event handlers (if there are any)
			$el.off( '.lengthLimit' );

			if ( filterFn || !allowNativeMaxlength ) {
				// Disable the native maxLength (if there is any), because it interferes
				// with the (differently calculated) character/byte limit.
				// Aside from being differently calculated,
				// we also support a callback which can make it to allow longer
				// values (e.g. count "Foo" from "User:Foo").
				// maxLength is a strange property. Removing or setting the property to
				// undefined directly doesn't work. Instead, it can only be unset internally
				// by the browser when removing the associated attribute (Firefox/Chrome).
				// https://bugs.chromium.org/p/chromium/issues/detail?id=136004
				$el.removeAttr( 'maxlength' );

			} else {
				// For $.byteLimit only, if we don't have a callback,
				// the byteLimit can only be lower than the native maxLength limit
				// (that is, there are no characters less than 1 byte in size). So lets (re-)enforce
				// the native limit for efficiency when possible (it will make the while-loop below
				// faster by there being less left to interate over). This does not work for $.codePointLimit
				// (code units for surrogates represent half a character each).
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
			// See https://www.w3.org/TR/DOM-Level-3-Events/#events-keyboard-event-order for
			// the order and characteristics of the key events.
			$el.on( eventKeys, function () {
				var res = trimFn(
					prevSafeVal,
					this.value,
					elLimit,
					filterFn
				);

				// Only set value property if it was trimmed, because whenever the
				// value property is set, the browser needs to re-initiate the text context,
				// which moves the cursor at the end the input, moving it away from wherever it was.
				// This is a side-effect of limiting after the fact.
				if ( res.trimmed === true ) {
					this.value = res.newVal;
					// Trigger a 'change' event to let other scripts attached to this node know that the value
					// was changed. This will also call ourselves again, but that's okay, it'll be a no-op.
					$el.trigger( 'change' );
				}
				// Always adjust prevSafeVal to reflect the input value. Not doing this could cause
				// trimFn to compare the new value to an empty string instead of the
				// old value, resulting in trimming always from the end (T42850).
				prevSafeVal = res.newVal;
			} );
		} );
	}

	/**
	 * Enforces a byte limit on an input field, assuming UTF-8 encoding, for situations
	 * when, for example, a database field has a byte limit rather than a character limit.
	 * Plugin rationale: Browser has native maxlength for number of characters (technically,
	 * UTF-16 code units), this plugin exists to limit number of bytes instead.
	 *
	 * Can be called with a custom limit (to use that limit instead of the maxlength attribute
	 * value), a filter function (in case the limit should apply to something other than the
	 * exact input value), or both. Order of parameters is important!
	 *
	 * @param {number} [limit] Limit to enforce, fallsback to maxLength-attribute,
	 *  called with fetched value as argument.
	 * @param {Function} [filterFn] Function to call on the string before assessing the length.
	 * @return {jQuery}
	 * @chainable
	 */
	$.fn.byteLimit = function ( limit, filterFn ) {
		return lengthLimit.call( this, trimByteLength, limit, filterFn );
	};

	/**
	 * Enforces a codepoint (character) limit on an input field.
	 *
	 * For unfortunate historical reasons, browsers' native maxlength counts [the number of UTF-16
	 * code units rather than Unicode codepoints] [1], which means that codepoints outside the Basic
	 * Multilingual Plane (e.g. many emojis) count as 2 characters each. This plugin exists to
	 * correct this.
	 *
	 * [1]: https://www.w3.org/TR/html5/sec-forms.html#limiting-user-input-length-the-maxlength-attribute
	 *
	 * Can be called with a custom limit (to use that limit instead of the maxlength attribute
	 * value), a filter function (in case the limit should apply to something other than the
	 * exact input value), or both. Order of parameters is important!
	 *
	 * @param {number} [limit] Limit to enforce, fallsback to maxLength-attribute,
	 *  called with fetched value as argument.
	 * @param {Function} [filterFn] Function to call on the string before assessing the length.
	 * @return {jQuery}
	 * @chainable
	 */
	$.fn.codePointLimit = function ( limit, filterFn ) {
		return lengthLimit.call( this, trimCodePointLength, limit, filterFn );
	};

	/**
	 * @class jQuery
	 * @mixins jQuery.plugin.lengthLimit
	 */
}( jQuery, mediaWiki ) );
