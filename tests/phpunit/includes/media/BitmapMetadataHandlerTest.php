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
		global $wgShowEXIF;
		if ( !$wgShowEXIF ) {
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

	/**
	 * Test for jpeg comments are being handled by
	 * BitmapMetadataHandler correctly.
	 *
	 * There's more extensive tests of comment extraction in
	 * JpegMetadataExtractorTests.php
	 */
	public function testJpegComment() {
		$meta = BitmapMetadataHandler::Jpeg( $this->filePath .
			'jpeg-comment-utf.jpg' );

		$this->assertEquals( 'UTF-8 JPEG Comment — ¼',
			$meta['JPEGFileComment'][0] );
	}

	public function testIPTCDates() {
		$meta = BitmapMetadataHandler::Jpeg( $this->filePath .
			'iptc-timetest.jpg' );

		$this->assertEquals( '2020:07:14 01:36:05', $meta['DateTimeDigitized'] );
		$this->assertEquals( '1997:03:02 00:01:02', $meta['DateTimeOriginal'] );
	}
	/* File has an invalid time (+ one valid but really weird time)
	 * that shouldn't be included
	 */
	public function testIPTCDatesInvalid() {
		$meta = BitmapMetadataHandler::Jpeg( $this->filePath .
			'iptc-timetest-invalid.jpg' );

		$this->assertEquals( '1845:03:02 00:01:02', $meta['DateTimeOriginal'] );
		$this->assertFalse( isset( $meta['DateTimeDigitized'] ) );
	}

	/**
	 * XMP data should take priority over iptc data
	 * when hash has been updated, but not when
	 * the hash is wrong.
	 */
	public function testMerging() {
		$merger = new BitmapMetadataHandler();
		$merger->addMetadata( array( 'foo' => 'xmp' ), 'xmp-general' );
		$merger->addMetadata( array( 'bar' => 'xmp' ), 'xmp-general' );
		$merger->addMetadata( array( 'baz' => 'xmp' ), 'xmp-general' );
		$merger->addMetadata( array( 'fred' => 'xmp' ), 'xmp-general' );
		$merger->addMetadata( array( 'foo' => 'iptc (hash)' ), 'iptc-good-hash' );
		$merger->addMetadata( array( 'bar' => 'iptc (bad hash)' ), 'iptc-bad-hash' );
		$merger->addMetadata( array( 'baz' => 'iptc (bad hash)' ), 'iptc-bad-hash' );
		$merger->addMetadata( array( 'fred' => 'iptc (no hash)' ), 'iptc-no-hash' );
		$merger->addMetadata( array( 'baz' => 'exif' ), 'exif' );

		$actual = $merger->getMetadataArray();
		$expected = array(
			'foo' => 'xmp',
			'bar' => 'iptc (bad hash)',
			'baz' => 'exif',
			'fred' => 'xmp',
		);
		$this->assertEquals( $expected, $actual );
	}

	public function testPNGXMP() {
		$handler = new BitmapMetadataHandler();
		$result = $handler->png( $this->filePath . 'xmp.png' );
		$expected = array (
			'frameCount' => 0,
			'loopCount' => 1,
			'duration' => 0,
			'bitDepth' => 1,
			'colorType' => 'index-coloured',
			'metadata' => array (
				'SerialNumber' => '123456789',
				'_MW_PNG_VERSION' => 1,
			),
		);
		$this->assertEquals( $expected, $result ); 
	}
	public function testPNGNative() {
		$handler = new BitmapMetadataHandler();
		$result = $handler->png( $this->filePath . 'Png-native-test.png' );
		$expected = 'http://example.com/url';
		$this->assertEquals( $expected, $result['metadata']['Identifier']['x-default'] ); 
	}
	public function testTiffByteOrder() {
		$handler = new BitmapMetadataHandler();
		$res = $handler->getTiffByteOrder( $this->filePath . 'test.tiff' );
		$this->assertEquals( 'LE', $res );
	}

}
