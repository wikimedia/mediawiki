/**
 * @class jQuery.plugin.byteLength
 */

/**
 * Calculate the byte length of a string (accounting for UTF-8).
 *
 * @method byteLength
 * @deprecated Use `require( 'stringLength' ).byteLength` instead.
 * @static
 * @inheritable
 * @param {string} str
 * @return {number}
 */
mediaWiki.log.deprecate( jQuery, 'byteLength', require( 'stringLength' ).byteLength,
	'Use require( \'stringLength\' ).byteLength instead.', '$.byteLength' );

/**
 * @class jQuery
 * @mixins jQuery.plugin.byteLength
 */
