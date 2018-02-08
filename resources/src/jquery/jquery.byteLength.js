/**
 * @class jQuery.plugin.byteLength
 */

/**
 * Calculate the byte length of a string, assuming UTF-8 encoding.
 *
 * @method byteLength
 * @deprecated Use `require( 'mediawiki.String' ).byteLength` instead.
 * @static
 * @inheritable
 * @param {string} str
 * @return {number}
 */
mediaWiki.log.deprecate( jQuery, 'byteLength', require( 'mediawiki.String' ).byteLength,
	'Use require( \'mediawiki.String\' ).byteLength instead.', '$.byteLength' );

/**
 * @class jQuery
 * @mixins jQuery.plugin.byteLength
 */
