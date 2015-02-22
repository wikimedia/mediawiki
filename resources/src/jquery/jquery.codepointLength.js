/**
 * @class jQuery.plugin.codepointLength
 */

/**
 * Calculate the number of unicode code points in a string.
 *
 * Roughly equivalent to (UTF-16) string length, except that astral
 * characters count as 1 not 2. Note that since data is not garunteed
 * to be in NFC, the answer might be longer than what the normalized
 * string would be.
 *
 * @static
 * @inheritable
 * @param {string} str
 * @return {string}
 */
jQuery.codepointLength = function ( str ) {

	// Remove low surrogates (DC00–DFFF)
	return str
		.replace( /[\uDC00-\uDFFF]/g, '' )
		.length;
};

/**
 * @class jQuery
 * @mixins jQuery.plugin.codepointLength
 */
