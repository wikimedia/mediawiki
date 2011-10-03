<?php
/**
 * Handler for Tiff images.
 *
 * @file
 * @ingroup Media
 */

/**
 * Handler for Tiff images.
 *
 * @ingroup Media
 */
class TiffHandler extends ExifBitmapHandler {

	/**
	 * Conversion to PNG for inline display can be disabled here...
	 * Note scaling should work with ImageMagick, but may not with GD scaling.
	 *
	 * Files pulled from an another MediaWiki instance via ForeignAPIRepo /
	 * InstantCommons will have thumbnails managed from the remote instance,
	 * so we can skip this check.
	 *
	 * @param $file
	 *
	 * @return bool
	 */
	function canRender( $file ) {
		global $wgTiffThumbnailType;
		return (bool)$wgTiffThumbnailType
			|| ($file->getRepo() instanceof ForeignAPIRepo);
	}

	/**
	 * Browsers don't support TIFF inline generally...
	 * For inline display, we need to convert to PNG.
	 *
	 * @param $file
	 *
	 * @return bool
	 */
	function mustRender( $file ) {
		return true;
	}

	/**
	 * @param $ext
	 * @param $mime
	 * @param $params
	 * @return bool
	 */
	function getThumbType( $ext, $mime, $params = null ) {
		global $wgTiffThumbnailType;
		return $wgTiffThumbnailType;
	}

	/**
	 * @param $image
	 * @param $filename
	 * @return string
	 */
	function getMetadata( $image, $filename ) {
		global $wgShowEXIF;
		if ( $wgShowEXIF ) {
			try {
				$meta = BitmapMetadataHandler::Tiff( $filename );
				if ( !is_array( $meta ) ) {
					// This should never happen, but doesn't hurt to be paranoid.
					throw new MWException('Metadata array is not an array');
				}
				$meta['MEDIAWIKI_EXIF_VERSION'] = Exif::version();
				return serialize( $meta );
			}
			catch ( MWException $e ) {
				// BitmapMetadataHandler throws an exception in certain exceptional
				// cases like if file does not exist.
				wfDebug( __METHOD__ . ': ' . $e->getMessage() . "\n" );
				return ExifBitmapHandler::BROKEN_FILE;
			}
		} else {
			return '';
		}
	}
}
