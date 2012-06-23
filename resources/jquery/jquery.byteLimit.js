/**
 * jQuery byteLimit plugin
 *
 * @author Jan Paul Posma, 2011
 * @author Timo Tijhof, 2011-2012
 */
( function ( $, undefined ) {

	/**
	 * Enforces a byte limit on an input field, so that UTF-8 entries are counted
	 * as well, when, for example, a database field has a byte limit rather than
	 * a character limit.
	 * Plugin rationale: Browser has native maxlength for number of characters,
	 * this plugin exists to limit number of bytes instead.
	 *
	 * Can be called with a custom limit (to use that limit instead of the
	 * maxlength attribute value), a filter function (in case the limit should
	 * apply to something other than the exact input value), or both.
	 * Order of parameters is important!
	 *
	 * @context {jQuery} Instance of jQuery for one or more input elements.
	 * @param limit {Number} [optional] Limit to enforce, fallsback to
	 * maxLength-attribute, called with fetched value as argument.
	 * @param fn {Function} [optional] Function to call on the input string
	 *  before assessing the length.
	 * @return {jQuery} The context.
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
	
			// If there is no (valid) limit passed or found in the property,
			// skip this. The < 0 check is required for Firefox, which returns
			// -1  (instead of undefined) for maxLength if it is not set.
			if ( !elLimit || elLimit < 0 ) {
				return;
			}

			// If there's a callback set, it's possible that the limit being enforced
			// is lower or higher (ie. if the callback would return "Foo" for "User:Foo").
			// Usually this isn't a problem since browsers ignore maxLength when setting
			// the value property through JavaScript, but Safari 4 violates that rule,
			// and makes sense to generally make sure the native browser limit doesn't
			// interfere
			$el.removeProp( 'maxLength' );
	
			// Save function for reference
			$el.data( 'byteLimitCallback', fn );
	
			// Using keyup instead of keypress so that we don't have to account
			// for the inifite number of methods for character insertion (typing,
			// holding down for multiple characters, special characters inserted
			// with shift/alt, backspace, drag/drop, cut/copy/paste, selecting
			// text and replacing.
			// Also using onchange. Usually only triggered when field looses focus,
			// (incl. before submission), which seems redundant. But this is used
			// to allow other javascript libraries (e.g. for custom input methods of
			// special characters) which tend to trigger onchange as conviennce for
			// plugins like these.
			$el.on( 'keyup change', function ( e ) {
				var len,
					$el = $( this ),
					curVal = $el.val(),
					val = curVal;

				// Run any value modifier (e.g. a function to apply the limit to
				// "Foo" in value "User:Foo").
				while ( $.byteLength( fn ? fn( val ) : val ) > elLimit ) {
					val = val.substr( 0, val.length - 1 );
				};

				if ( val !== curVal ) {
					$el.val( val );
				}
			});
		});
	};
}( jQuery ) );
