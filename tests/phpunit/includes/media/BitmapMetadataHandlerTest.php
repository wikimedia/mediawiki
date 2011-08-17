<?php
class BitmapMetadataHandlerTest extends MediaWikiTestCase {

	public function setUp() {
		$this->filePath = dirname( __FILE__ ) . '/../../data/media/';
	}

	/**
	 * Test if having conflicting metadata values from different
	 * types of metadata, that the right one takes precedence.
	 *
	 * Basically the file has IPTC and XMP metadata, the
	 * IPTC should override the XMP, except for the multilingual
	 * translation (to en) where XMP should win.
	 */
	public function testMultilingualCascade() {
		if ( !wfDl( 'exif' ) ) {
			$this->markTestIncomplete( "This test needs the exif extension." );
		}

		$meta = BitmapMetadataHandler::Jpeg( $this->filePath .
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
