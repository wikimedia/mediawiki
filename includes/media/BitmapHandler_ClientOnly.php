<?php
/**
 * Handler for bitmap images that will be resized by clients.
 *
 * @license GPL-2.0-or-later
 * @file
 * @ingroup Media
 */

use MediaWiki\FileRepo\File\File;

/**
 * Handler for bitmap images that will be resized by clients.
 *
 * This is not used by default but can be assigned to some image types
 * using $wgMediaHandlers.
 *
 * @ingroup Media
 */
// phpcs:ignore Squiz.Classes.ValidClassName.NotCamelCaps
class BitmapHandler_ClientOnly extends BitmapHandler {

	/**
	 * @param File $image
	 * @param array &$params
	 * @return bool
	 */
	public function normaliseParams( $image, &$params ) {
		return ImageHandler::normaliseParams( $image, $params );
	}

	/**
	 * @param File $image
	 * @param string $dstPath
	 * @param string $dstUrl
	 * @param array $params
	 * @param int $flags
	 * @return ThumbnailImage|TransformParameterError
	 */
	public function doTransform( $image, $dstPath, $dstUrl, $params, $flags = 0 ) {
		if ( !$this->normaliseParams( $image, $params ) ) {
			return new TransformParameterError( $params );
		}

		return new ThumbnailImage( $image, $image->getUrl(), $image->getLocalRefPath(), $params );
	}
}
