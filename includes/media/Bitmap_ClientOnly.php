<?php
/**
 * Handler for bitmap images that will be resized by clients
 *
 * @file
 * @ingroup Media
 */

/**
 * Handler for bitmap images that will be resized by clients.
 *
 * This is not used by default but can be assigned to some image types
 * using $wgMediaHandlers.
 *
 * @ingroup Media
 */
class BitmapHandler_ClientOnly extends BitmapHandler {

	/**
	 * @param $image File
	 * @param  $params
	 * @return bool
	 */
	function normaliseParams( $image, &$params ) {
		return ImageHandler::normaliseParams( $image, $params );
	}

	/**
	 * @param $image File
	 * @param  $dstPath
	 * @param  $dstUrl
	 * @param  $params
	 * @param int $flags
	 * @return ThumbnailImage|TransformParameterError
	 */
	function doTransform( $image, $dstPath, $dstUrl, $params, $flags = 0 ) {
		if ( !$this->normaliseParams( $image, $params ) ) {
			return new TransformParameterError( $params );
		}
		return new ThumbnailImage( $image, $image->getURL(), $params['width'], 
			$params['height'], $image->getPath() );
	}
}
