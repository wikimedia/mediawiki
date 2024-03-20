/* global JpegMeta */

/**
 * Exposes a method for extracting metadata from JPEGs.
 *
 * @module mediawiki.libs.jpegmeta
 */

/**
 * Extract metadata from a JPEG.
 *
 * @example
 * const binReader = new FileReader();
 * binReader.onload = function () {
 *   const binStr = binReader.result;
 *   const jpegmeta = require( 'mediawiki.libs.jpegmeta' );
 *   const meta = jpegmeta( binStr, 'foo.jpg' );
 * }
 * binReader.readAsBinaryString( upload.file );
 *
 * @method (require("mediawiki.libs.jpegmeta"))
 * @param {string} fileReaderResult Binary string
 * @param {string} fileName
 * @return {Object} A {@link https://github.com/bennoleslie/jsjpegmeta JpegMeta.JpegFile} object
 */
module.exports = function ( fileReaderResult, fileName ) {
	return new JpegMeta.JpegFile( fileReaderResult, fileName );
};
