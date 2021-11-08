/* eslint-disable no-extend-native, no-bitwise, eqeqeq */

/**
 * Array.prototype.find polyfill
 *
 * Based on the example from the MDN docs, re-written for MW house style.
 * Implementation follows the spec here:
 * https://tc39.github.io/ecma262/#sec-array.prototype.find
 *
 * author Mozilla Contributors
 * license CC0 per https://developer.mozilla.org/en-US/docs/MDN/About#copyrights_and_licenses
 *
 * https://github.com/mdn/content/blob/b81aa721130/files/en-us/web/javascript/reference/global_objects/array/find/index.html#L113
 */
if ( !Array.prototype.find ) {
	Object.defineProperty( Array.prototype, 'find', {
		value: function ( predicate ) {
			var o, len, thisArg, k, kValue;

			// 1. Let O be ? ToObject(this value).
			if ( this == null ) {
				throw new TypeError( '"this" is null or not defined' );
			}

			o = Object( this );

			// 2. Let len be ? ToLength(? Get(O, "length")).
			len = o.length >>> 0;

			// 3. If IsCallable(predicate) is false, throw a TypeError exception.
			if ( typeof predicate !== 'function' ) {
				throw new TypeError( 'predicate must be a function' );
			}

			// 4. If thisArg was supplied, let T be thisArg; else let T be undefined.
			thisArg = arguments[ 1 ];

			// 5. Let k be 0.
			k = 0;

			// 6. Repeat, while k < len
			while ( k < len ) {
				// a. Let Pk be ! ToString(k).
				// b. Let kValue be ? Get(O, Pk).
				// c. Let testResult be ToBoolean(? Call(predicate, T, « kValue, k, O »)).
				// d. If testResult is true, return kValue.
				kValue = o[ k ];
				if ( predicate.call( thisArg, kValue, k, o ) ) {
					return kValue;
				}
				// e. Increase k by 1.
				k++;
			}

			// 7. Return undefined.
			return undefined;
		},
		configurable: true,
		writable: true
	} );
}
