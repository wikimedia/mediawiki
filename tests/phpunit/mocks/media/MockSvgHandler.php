<?php

class MockSvgHandler extends SvgHandler {
	function doTransform( $image, $dstPath, $dstUrl, $params, $flags = 0 ) {
		return MockImageHandler::doFakeTransform( $this, $image, $dstPath, $dstUrl, $params, $flags );
	}
}