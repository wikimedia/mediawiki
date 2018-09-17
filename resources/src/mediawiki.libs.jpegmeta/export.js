/* global JpegMeta */
( function () {

	// Export as module
	module.exports = function ( fileReaderResult, fileName ) {
		return new JpegMeta.JpegFile( fileReaderResult, fileName );
	};

	// Back-compat: Also expose via mw.lib
	// @deprecated since 1.31
	mw.log.deprecate( mw.libs, 'jpegmeta', module.exports );
}() );
