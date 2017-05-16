<?php

/**
 * @group Media
 */
class XContentDimensionsTest extends MediaWikiMediaTestCase {
	/**
	 * @param string $filename
	 * @param string $expectedXContentDimensions
	 * @dataProvider provideGetContentHeaders
	 * @covers File::getContentHeaders
	 */
	public function testGetContentHeaders( $filename, $expectedXContentDimensions ) {
		$file = $this->dataFile( $filename );
		$headers = $file->getContentHeaders();
		$this->assertEquals( true, isset( $headers['X-Content-Dimensions'] ) );
		$this->assertEquals( $headers['X-Content-Dimensions'], $expectedXContentDimensions );
	}

	public static function provideGetContentHeaders() {
		return [
			[ '80x60-2layers.xcf', '80x60:1' ],
			[ 'animated.gif', '45x30:1' ],
			[ 'landscape-plain.jpg', '1024x768:1' ],
			[ 'portrait-rotated.jpg', '768x1024:1' ],
			[ 'Wikimedia-logo.svg', '1024x1024:1' ],
			[ 'webp_animated.webp', '300x225:1' ],
			[ 'test.tiff', '20x20:1' ],
		];
	}
}
