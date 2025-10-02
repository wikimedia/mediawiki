<?php
/**
 * @license GPL-2.0-or-later
 * @ingroup Media
 */

/**
 * Fake handler for SVG images.
 */
class MockSvgHandler extends SvgHandler {
	/** @inheritDoc */
	public function doTransform( $image, $dstPath, $dstUrl, $params, $flags = 0 ) {
		return MockImageHandler::doFakeTransform( $this, $image, $dstPath, $dstUrl, $params, $flags );
	}
}
