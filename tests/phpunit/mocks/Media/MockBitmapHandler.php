<?php
/**
 * @license GPL-2.0-or-later
 * @ingroup Media
 */

/**
 * Fake handler for Bitmap images.
 */
class MockBitmapHandler extends BitmapHandler {
	/** @inheritDoc */
	public function doTransform( $image, $dstPath, $dstUrl, $params, $flags = 0 ) {
		return MockImageHandler::doFakeTransform( $this, $image, $dstPath, $dstUrl, $params, $flags );
	}

	/** @inheritDoc */
	public function doClientImage( $image, $scalerParams ) {
			return $this->getClientScalingThumbnailImage( $image, $scalerParams );
	}
}
