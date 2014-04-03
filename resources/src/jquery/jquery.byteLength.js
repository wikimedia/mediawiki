/**
 * jQuery.byteLength
 *
 * Calculate the byte length of a string (accounting for UTF-8).
 *
 * @author Jan Paul Posma, 2011
 * @author Timo Tijhof, 2012
 * @author David Chan, 2013
 */
jQuery.byteLength = function ( str ) {

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

};
