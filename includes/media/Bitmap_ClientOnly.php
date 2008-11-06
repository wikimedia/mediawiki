<?php

class BitmapHandler_ClientOnly extends BitmapHandler {
	function normaliseParams( $image, &$params ) {
		return ImageHandler::normaliseParams( $image, $params );
	}

	function doTransform( $image, $dstPath, $dstUrl, $params, $flags = 0 ) {
		if ( !$this->normaliseParams( $image, $params ) ) {
			return new TransformParameterError( $params );
		}
		return new ThumbnailImage( $image, $image->getURL(), $params['width'], 
			$params['height'], $image->getPath() );
	}
}
