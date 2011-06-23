<?php
class BitmapMetadataHandlerTest extends MediaWikiTestCase {
	/**
	 * Test if having conflicting metadata values from different
	 * types of metadata, that the right one takes precedence.
	 *
	 * Basically the file has IPTC and XMP metadata, the
	 * IPTC should override the XMP, except for the multilingual
	 * translation (to en) where XMP should win.
	 */
	public function testMultilingualCascade() {
		
		$meta = BitmapMetadataHandler::Jpeg( dirname( __FILE__ ) .
			'/Xmp-exif-multilingual_test.jpg' );

		$expected = array(
			'x-default' => 'right(iptc)',
			'en'        => 'right translation',
			'_type'     => 'lang'
		);
		
		$this->assertArrayHasKey( 'ImageDescription', $meta,
			'Did not extract any ImageDescription info?!' );

		$this->assertEquals( $expected, $meta['ImageDescription'] );
	}
}
