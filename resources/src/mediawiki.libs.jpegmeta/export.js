/* global JpegMeta */

// Export as module
module.exports = function ( fileReaderResult, fileName ) {
	return new JpegMeta.JpegFile( fileReaderResult, fileName );
};
