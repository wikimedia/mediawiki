<?php
/**
 * Handler to send BMP files uncompressed.
 *
 * Set in your LocalSettings.php:
 *  $wgMediaHandlers['image/x-ms-bmp'] = 'BMPUncompressedHandler';
 *  $wgMediaHandlers['image/x-bmp']    = 'BMPUncompressedHandler';
 *
 * @author Antoine Musso
 */

class BMPUncompressed extends BmpHandler {

	/** Fall back to whatever default, skip BmpHandler forcing rendering */
	function mustRender( $file ) {
		return BitmapHandler::mustRender( $file );
	}

	function getThumbType( $text, $mime, $params = null ) {
		return MediaHandler::getThumbType( $text, $mime, $params );
	}
}
