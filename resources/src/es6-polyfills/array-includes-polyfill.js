/* eslint-disable no-extend-native, no-bitwise, eqeqeq */

/**
 * Array.prototype.includes polyfill
 *
 * Based on the example from the MDN docs, re-written for MW house style.
 * Implementation follows the spec here:
 * https://tc39.github.io/ecma262/#sec-array.prototype.includes
 *
 * author Mozilla Contributors
 * license CC0 per https://developer.mozilla.org/en-US/docs/MDN/About#copyrights_and_licenses
 *
 * https://developer.mozilla.org/en-US/docs/Web/JavaScript/Reference/Global_Objects/Array/includes
 */
if ( !Array.prototype.includes ) {
	Object.defineProperty( Array.prototype, 'includes', {
		value: function ( searchElement, fromIndex ) {
			var o, len, n, k;

			// 1. Let O be ? ToObject(this value).
			if ( this == null ) {
				throw new TypeError( '"this" is null or not defined' );
			}

			o = Object( this );

			// 2. Let len be ? ToLength(? Get(O, "length")).
			len = o.length >>> 0;

			// 3. If len is 0, return false.
			if ( len === 0 ) {
				return false;
			}

			// 4. Let n be ? ToInteger(fromIndex).
			//    (If fromIndex is undefined, this step produces the value 0.)
			n = fromIndex | 0;

			// 5. If n ≥ 0, then
			//  a. Let k be n.
			// 6. Else n < 0,
			//  a. Let k be len + n.
			//  b. If k < 0, let k be 0.
			k = Math.max( n >= 0 ? n : len - Math.abs( n ), 0 );

			function sameValueZero( x, y ) {
				return x === y || ( typeof x === 'number' && typeof y === 'number' && isNaN( x ) && isNaN( y ) );
			}

			// 7. Repeat, while k < len
			while ( k < len ) {
				// a. Let elementK be the result of ? Get(O, ! ToString(k)).
				// b. If SameValueZero(searchElement, elementK) is true, return true.
				// c. Increase k by 1.
				if ( sameValueZero( o[ k ], searchElement ) ) {
					return true;
				}
				k++;
			}

			// 8. Return false
			return false;
		},
		configurable: true,
		writable: true
	} );
}
