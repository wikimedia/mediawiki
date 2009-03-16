<?php
/**
 * @file
 * @ingroup Media
 */

/**
 * @ingroup Media
 */
class TiffHandler extends BitmapHandler {

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

	function getThumbType( $ext, $mime ) {
		global $wgTiffThumbnailType;
		return $wgTiffThumbnailType;
	}
}
