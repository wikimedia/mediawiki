<?php

use MediaWiki\MainConfigNames;

/**
 * @group Media
 * @covers \Exif
 * @requires extension exif
 */
class ExifTest extends MediaWikiIntegrationTestCase {
	private const FILE_PATH = __DIR__ . '/../../data/media/';

	protected function setUp(): void {
		parent::setUp();

		$this->overrideConfigValue( MainConfigNames::ShowEXIF, true );
	}

	public function testGPSExtraction() {
		$filename = self::FILE_PATH . 'exif-gps.jpg';
		$seg = JpegMetadataExtractor::segmentSplitter( $filename );
		$exif = new Exif( $filename, $seg['byteOrder'] );
		$data = $exif->getFilteredData();
		$expected = [
			'GPSLatitude' => 88.5180555556,
			'GPSLongitude' => -21.12357,
			'GPSAltitude' => -3.141592653,
			'GPSDOP' => '5/1',
			'GPSVersionID' => '2.2.0.0',
		];
		$this->assertEqualsWithDelta( $expected, $data, 0.0000000001 );
	}

	public function testUnicodeUserComment() {
		$filename = self::FILE_PATH . 'exif-user-comment.jpg';
		$seg = JpegMetadataExtractor::segmentSplitter( $filename );
		$exif = new Exif( $filename, $seg['byteOrder'] );
		$data = $exif->getFilteredData();

		$expected = [
			'UserComment' => 'test⁔comment',
		];
		$this->assertEquals( $expected, $data );
	}
}
