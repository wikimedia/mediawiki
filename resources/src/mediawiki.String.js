( function () {

	/**
	 * @class mw.String
	 * @singleton
	 */

	/**
	 * Calculate the byte length of a string (accounting for UTF-8).
	 *
	 * @author Jan Paul Posma, 2011
	 * @author Timo Tijhof, 2012
	 * @author David Chan, 2013
	 *
	 * @param {string} str
	 * @return {number}
	 */
	function byteLength( str ) {
		// This basically figures out how many bytes a UTF-16 string (which is what js sees)
		// will take in UTF-8 by replacing a 2 byte character with 2 *'s, etc, and counting that.
		// Note, surrogate (\uD800-\uDFFF) characters are counted as 2 bytes, since there's two of them
		// and the actual character takes 4 bytes in UTF-8 (2*2=4). Might not work perfectly in
		// edge cases such as illegal sequences, but that should never happen.

		// https://en.wikipedia.org/wiki/UTF-8#Description
		// The mapping from UTF-16 code units to UTF-8 bytes is as follows:
		// > Range 0000-007F: codepoints that become 1 byte of UTF-8
		// > Range 0080-07FF: codepoints that become 2 bytes of UTF-8
		// > Range 0800-D7FF: codepoints that become 3 bytes of UTF-8
		// > Range D800-DFFF: Surrogates (each pair becomes 4 bytes of UTF-8)
		// > Range E000-FFFF: codepoints that become 3 bytes of UTF-8 (continued)

		return str
			.replace( /[\u0080-\u07FF\uD800-\uDFFF]/g, '**' )
			.replace( /[\u0800-\uD7FF\uE000-\uFFFF]/g, '***' )
			.length;
	}

	/**
	 * Calculate the character length of a string (accounting for UTF-16 surrogates).
	 *
	 * @param {string} str
	 * @return {number}
	 */
	function codePointLength( str ) {
		return str
			// Low surrogate + high surrogate pairs represent one character (codepoint) each
			.replace( /[\uD800-\uDBFF][\uDC00-\uDFFF]/g, '*' )
			.length;
	}

	// Like String#charAt, but return the pair of UTF-16 surrogates for characters outside of BMP.
	function codePointAt( string, offset, backwards ) {
		// We don't need to check for offsets at the beginning or end of string,
		// String#slice will simply return a shorter (or empty) substring.
		var maybePair = backwards ?
			string.slice( offset - 1, offset + 1 ) :
			string.slice( offset, offset + 2 );
		if ( /^[\uD800-\uDBFF][\uDC00-\uDFFF]$/.test( maybePair ) ) {
			return maybePair;
		} else {
			return string.charAt( offset );
		}
	}

	function trimLength( safeVal, newVal, length, lengthFn ) {
		var startMatches, endMatches, matchesLen, inpParts, chopOff, oldChar, newChar,
			oldVal = safeVal;

		// Run the hook if one was provided, but only on the length
		// assessment. The value itself is not to be affected by the hook.
		if ( lengthFn( newVal ) <= length ) {
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
		while ( startMatches < matchesLen ) {
			oldChar = codePointAt( oldVal, startMatches, false );
			newChar = codePointAt( newVal, startMatches, false );
			if ( oldChar !== newChar ) {
				break;
			}
			startMatches += oldChar.length;
		}

		while ( endMatches < ( matchesLen - startMatches ) ) {
			oldChar = codePointAt( oldVal, oldVal.length - 1 - endMatches, true );
			newChar = codePointAt( newVal, newVal.length - 1 - endMatches, true );
			if ( oldChar !== newChar ) {
				break;
			}
			endMatches += oldChar.length;
		}

		inpParts = [
			// Same start
			newVal.slice( 0, startMatches ),
			// Inserted content
			newVal.slice( startMatches, newVal.length - endMatches ),
			// Same end
			newVal.slice( newVal.length - endMatches )
		];

		// Chop off characters from the end of the "inserted content" string
		// until the limit is statisfied.
		// Make sure to stop when there is nothing to slice (T43450).
		while ( lengthFn( inpParts.join( '' ) ) > length && inpParts[ 1 ].length > 0 ) {
			// Do not chop off halves of surrogate pairs
			chopOff = /[\uD800-\uDBFF][\uDC00-\uDFFF]$/.test( inpParts[ 1 ] ) ? 2 : 1;
			inpParts[ 1 ] = inpParts[ 1 ].slice( 0, -chopOff );
		}

		return {
			newVal: inpParts.join( '' ),
			// For pathological lengthFn() that always returns a length greater than the limit, we might have
			// ended up not trimming - check for this case to avoid infinite loops
			trimmed: newVal !== inpParts.join( '' )
		};
	}

	/**
	 * Utility function to trim down a string, based on byteLimit
	 * and given a safe start position. It supports insertion anywhere
	 * in the string, so "foo" to "fobaro" if limit is 4 will result in
	 * "fobo", not "foba". Basically emulating the native maxlength by
	 * reconstructing where the insertion occurred.
	 *
	 * @param {string} safeVal Known value that was previously returned by this
	 * function, if none, pass empty string.
	 * @param {string} newVal New value that may have to be trimmed down.
	 * @param {number} byteLimit Number of bytes the value may be in size.
	 * @param {Function} [filterFunction] Function to call on the string before assessing the length.
	 * @return {Object}
	 * @return {string} return.newVal
	 * @return {boolean} return.trimmed
	 */
	function trimByteLength( safeVal, newVal, byteLimit, filterFunction ) {
		var lengthFn;
		if ( filterFunction ) {
			lengthFn = function ( val ) {
				return byteLength( filterFunction( val ) );
			};
		} else {
			lengthFn = byteLength;
		}

		return trimLength( safeVal, newVal, byteLimit, lengthFn );
	}

	/**
	 * Utility function to trim down a string, based on codePointLimit
	 * and given a safe start position. It supports insertion anywhere
	 * in the string, so "foo" to "fobaro" if limit is 4 will result in
	 * "fobo", not "foba". Basically emulating the native maxlength by
	 * reconstructing where the insertion occurred.
	 *
	 * @param {string} safeVal Known value that was previously returned by this
	 * function, if none, pass empty string.
	 * @param {string} newVal New value that may have to be trimmed down.
	 * @param {number} codePointLimit Number of characters the value may be in size.
	 * @param {Function} [filterFunction] Function to call on the string before assessing the length.
	 * @return {Object}
	 * @return {string} return.newVal
	 * @return {boolean} return.trimmed
	 */
	function trimCodePointLength( safeVal, newVal, codePointLimit, filterFunction ) {
		var lengthFn;
		if ( filterFunction ) {
			lengthFn = function ( val ) {
				return codePointLength( filterFunction( val ) );
			};
		} else {
			lengthFn = codePointLength;
		}

		return trimLength( safeVal, newVal, codePointLimit, lengthFn );
	}

	module.exports = {
		byteLength: byteLength,
		codePointLength: codePointLength,
		trimByteLength: trimByteLength,
		trimCodePointLength: trimCodePointLength
	};

}() );
