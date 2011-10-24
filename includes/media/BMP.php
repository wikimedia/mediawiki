<?php
/**
 * Handler for Microsoft's bitmap format
 *
 * @file
 * @ingroup Media
 */

/**
 * Handler for Microsoft's bitmap format; getimagesize() doesn't
 * support these files
 *
 * @ingroup Media
 */
class BmpHandler extends BitmapHandler {

	/**
	 * @param $file
	 * @return bool
	 */
	function mustRender( $file ) {
		return true;
	}

	/**
	 * Render files as PNG
	 *
	 * @param $text
	 * @param $mime
	 * @param $params
	 * @return array
	 */
	function getThumbType( $text, $mime, $params = null ) {
		return array( 'png', 'image/png' );
	}

	/**
	 * Get width and height from the bmp header.
	 *
	 * @param $image
	 * @param $filename
	 * @return array
	 */
	function getImageSize( $image, $filename ) {
		$f = fopen( $filename, 'rb' );
		if( !$f ) {
			return false;
		}
		$header = fread( $f, 54 );
		fclose($f);

		// Extract binary form of width and height from the header
		$w = substr( $header, 18, 4);
		$h = substr( $header, 22, 4);

		// Convert the unsigned long 32 bits (little endian):
		try {
			$w = wfUnpack( 'V', $w, 4 );
			$h = wfUnpack( 'V', $h, 4 );
		} catch ( MWException $e ) {
			return false;
		}
		return array( $w[1], $h[1] );
	}
}
