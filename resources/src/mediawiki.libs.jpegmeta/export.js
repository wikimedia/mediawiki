/* global JpegMeta */

/**
 * @module mediawiki.libs.jpegmeta
 */

/**
 * Exposes a single function for extracting meta data from JPEGs.
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
 * @param {string} fileReaderResult Binary string
 * @param {string} fileName
 * @return {Object} A {@link https://github.com/bennoleslie/jsjpegmeta JpegMeta.JpegFile} object
 */
module.exports = function ( fileReaderResult, fileName ) {
	return new JpegMeta.JpegFile( fileReaderResult, fileName );
};
