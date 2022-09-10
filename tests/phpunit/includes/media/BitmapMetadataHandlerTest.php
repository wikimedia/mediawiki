<?php

use MediaWiki\MainConfigNames;

/**
 * @group Media
 */
class BitmapMetadataHandlerTest extends MediaWikiIntegrationTestCase {

	protected function setUp(): void {
		parent::setUp();

		$this->overrideConfigValue( MainConfigNames::ShowEXIF, false );

		$this->filePath = __DIR__ . '/../../data/media/';
	}

	/**
	 * Test if having conflicting metadata values from different
	 * types of metadata, that the right one takes precedence.
	 *
	 * Basically the file has IPTC and XMP metadata, the
	 * IPTC should override the XMP, except for the multilingual
	 * translation (to en) where XMP should win.
	 * @covers BitmapMetadataHandler::Jpeg
	 */
	public function testMultilingualCascade() {
		$this->checkPHPExtension( 'exif' );
		$this->checkPHPExtension( 'xml' );

		$this->overrideConfigValue( MainConfigNames::ShowEXIF, true );

		$meta = BitmapMetadataHandler::Jpeg( $this->filePath .
			'/Xmp-exif-multilingual_test.jpg' );

		$expected = [
			'x-default' => 'right(iptc)',
			'en' => 'right translation',
			'_type' => 'lang'
		];

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
	 * @covers BitmapMetadataHandler::Jpeg
	 */
	public function testJpegComment() {
		$meta = BitmapMetadataHandler::Jpeg( $this->filePath .
			'jpeg-comment-utf.jpg' );

		$this->assertEquals( 'UTF-8 JPEG Comment — ¼',
			$meta['JPEGFileComment'][0] );
	}

	/**
	 * Make sure a bad iptc block doesn't stop the other metadata
	 * from being extracted.
	 * @covers BitmapMetadataHandler::Jpeg
	 */
	public function testBadIPTC() {
		$meta = BitmapMetadataHandler::Jpeg( $this->filePath .
			'iptc-invalid-psir.jpg' );
		$this->assertEquals( 'Created with GIMP', $meta['JPEGFileComment'][0] );
	}

	/**
	 * @covers BitmapMetadataHandler::Jpeg
	 */
	public function testIPTCDates() {
		$meta = BitmapMetadataHandler::Jpeg( $this->filePath .
			'iptc-timetest.jpg' );

		// raw date is 2020:07:13 14:04:05+11:32
		$this->assertEquals( '2020:07:14 01:36:05', $meta['DateTimeDigitized'] );
		// raw date is 1997:03:02 03:01:02-03:00
		$this->assertEquals( '1997:03:02 00:01:02', $meta['DateTimeOriginal'] );

		$meta = BitmapMetadataHandler::Jpeg( $this->filePath .
			'iptc-timetest-invalid.jpg' );

		// raw date is 1845:03:02 03:01:02-03:00
		$this->assertEquals( '1845:03:02 00:01:02', $meta['DateTimeOriginal'] );
		// raw date is 1942:07:13 25:05:02+00:00
		$this->assertSame( '1942:07:14 01:05:02', $meta['DateTimeDigitized'] );
	}

	/**
	 * XMP data should take priority over iptc data
	 * when hash has been updated, but not when
	 * the hash is wrong.
	 * @covers BitmapMetadataHandler::addMetadata
	 * @covers BitmapMetadataHandler::getMetadataArray
	 */
	public function testMerging() {
		$merger = new BitmapMetadataHandler();
		$merger->addMetadata( [ 'foo' => 'xmp' ], 'xmp-general' );
		$merger->addMetadata( [ 'bar' => 'xmp' ], 'xmp-general' );
		$merger->addMetadata( [ 'baz' => 'xmp' ], 'xmp-general' );
		$merger->addMetadata( [ 'fred' => 'xmp' ], 'xmp-general' );
		$merger->addMetadata( [ 'foo' => 'iptc (hash)' ], 'iptc-good-hash' );
		$merger->addMetadata( [ 'bar' => 'iptc (bad hash)' ], 'iptc-bad-hash' );
		$merger->addMetadata( [ 'baz' => 'iptc (bad hash)' ], 'iptc-bad-hash' );
		$merger->addMetadata( [ 'fred' => 'iptc (no hash)' ], 'iptc-no-hash' );
		$merger->addMetadata( [ 'baz' => 'exif' ], 'exif' );

		$actual = $merger->getMetadataArray();
		$expected = [
			'foo' => 'xmp',
			'bar' => 'iptc (bad hash)',
			'baz' => 'exif',
			'fred' => 'xmp',
		];
		$this->assertEquals( $expected, $actual );
	}

	/**
	 * @covers BitmapMetadataHandler::png
	 */
	public function testPNGXMP() {
		$this->checkPHPExtension( 'xml' );

		$result = BitmapMetadataHandler::PNG( $this->filePath . 'xmp.png' );
		$expected = [
			'width' => 50,
			'height' => 50,
			'frameCount' => 0,
			'loopCount' => 1,
			'duration' => 0,
			'bitDepth' => 1,
			'colorType' => 'index-coloured',
			'metadata' => [
				'SerialNumber' => '123456789',
				'_MW_PNG_VERSION' => 1,
			],
		];
		$this->assertEquals( $expected, $result );
	}

	/**
	 * @covers BitmapMetadataHandler::png
	 */
	public function testPNGNative() {
		$result = BitmapMetadataHandler::PNG( $this->filePath . 'Png-native-test.png' );
		$expected = 'http://example.com/url';
		$this->assertEquals( $expected, $result['metadata']['Identifier']['x-default'] );
	}

	/**
	 * @covers BitmapMetadataHandler::getTiffByteOrder
	 */
	public function testTiffByteOrder() {
		$res = BitmapMetadataHandler::getTiffByteOrder( $this->filePath . 'test.tiff' );
		$this->assertEquals( 'LE', $res );
	}
}
