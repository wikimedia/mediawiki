/**
 * @class jQuery.plugin.byteLength
 */

/**
 * Calculate the byte length of a string (accounting for UTF-8).
 *
 * @method byteLength
 * @deprecated Use `require( 'mediawiki.String' ).byteLength` instead.
 * @static
 * @inheritable
 * @param {string} str
 * @return {number}
 */
mw.log.deprecate( $, 'byteLength', require( 'mediawiki.String' ).byteLength,
	'Use require( \'mediawiki.String\' ).byteLength instead.', '$.byteLength' );

/**
 * @class jQuery
 * @mixins jQuery.plugin.byteLength
 */
