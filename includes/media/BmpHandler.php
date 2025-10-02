<?php
/**
 * Handler for Microsoft's bitmap format.
 *
 * @license GPL-2.0-or-later
 * @file
 * @ingroup Media
 */

use MediaWiki\FileRepo\File\File;
use Wikimedia\StringUtils\StringUtils;
use Wikimedia\UnpackFailedException;

/**
 * Handler for Microsoft's bitmap format; getimagesize() doesn't
 * support these files
 *
 * @ingroup Media
 */
class BmpHandler extends BitmapHandler {
	/**
	 * @param File $file
	 * @return bool
	 */
	public function mustRender( $file ) {
		return true;
	}

	/**
	 * Render files as PNG
	 *
	 * @param string $ext
	 * @param string $mime
	 * @param array|null $params
	 * @return array
	 */
	public function getThumbType( $ext, $mime, $params = null ) {
		return [ 'png', 'image/png' ];
	}

	/**
	 * Get width and height from the bmp header.
	 *
	 * @param MediaHandlerState $state
	 * @param string $filename
	 * @return array
	 */
	public function getSizeAndMetadata( $state, $filename ) {
		$f = fopen( $filename, 'rb' );
		if ( !$f ) {
			return [];
		}
		$header = fread( $f, 54 );
		fclose( $f );

		// Extract binary form of width and height from the header
		$w = substr( $header, 18, 4 );
		$h = substr( $header, 22, 4 );

		// Convert the unsigned long 32 bits (little endian):
		try {
			$w = StringUtils::unpack( 'V', $w, 4 );
			$h = StringUtils::unpack( 'V', $h, 4 );
		} catch ( UnpackFailedException ) {
			return [];
		}

		return [
			'width' => $w[1],
			'height' => $h[1]
		];
	}
}
