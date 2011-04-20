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
class TiffHandler extends JpegOrTiffHandler {

	/**
	 * Conversion to PNG for inline display can be disabled here...
	 * Note scaling should work with ImageMagick, but may not with GD scaling.
	 */
	function canRender( $file ) {
		global $wgTiffThumbnailType;
		return (bool)$wgTiffThumbnailType;
	}

	/**
	 * Browsers don't support TIFF inline generally...
	 * For inline display, we need to convert to PNG.
	 */
	function mustRender( $file ) {
		return true;
	}

	function getThumbType( $ext, $mime, $params = null ) {
		global $wgTiffThumbnailType;
		return $wgTiffThumbnailType;
	}

        function getMetadata( $image, $filename ) {
                global $wgShowEXIF;
                if ( $wgShowEXIF && file_exists( $filename ) ) {
                        $exif = new Exif( $filename );
                        $data = $exif->getFilteredData();
                        if ( $data ) {
                                $data['MEDIAWIKI_EXIF_VERSION'] = Exif::version();
                                return serialize( $data );
                        } else {
                                return JpegOrTiffHandler::BROKEN_FILE;
                        }
                } else {
                        return '';
                }
        }
}
