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
	 * @param $file
	 *
	 * @return bool
	 */
	function canRender( $file ) {
		global $wgTiffThumbnailType;
		return (bool)$wgTiffThumbnailType;
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
		if ( $wgShowEXIF && file_exists( $filename ) ) {
			$exif = new Exif( $filename );
			$data = $exif->getFilteredData();
			if ( $data ) {
				$data['MEDIAWIKI_EXIF_VERSION'] = Exif::version();
				return serialize( $data );
			} else {
				return ExifBitmapHandler::BROKEN_FILE;
			}
		} else {
			return '';
		}
	}
}
