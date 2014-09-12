<?php

/**
 * @covers Exif
 */
class ExifTest extends MediaWikiTestCase {

	/** @var string */
	protected $mediaPath;

	protected function setUp() {
		parent::setUp();
		$this->checkPHPExtension( 'exif' );

		$this->mediaPath = __DIR__ . '/../../data/media/';

		$this->setMwGlobals( 'wgShowEXIF', true );
	}

	public function testGPSExtraction() {
		$filename = $this->mediaPath . 'exif-gps.jpg';
		$seg = JpegMetadataExtractor::segmentSplitter( $filename );
		$exif = new Exif( $filename, $seg['byteOrder'] );
		$data = $exif->getFilteredData();
		$expected = array(
			'GPSLatitude' => 88.5180555556,
			'GPSLongitude' => -21.12357,
			'GPSAltitude' => -3.141592653,
			'GPSDOP' => '5/1',
			'GPSVersionID' => '2.2.0.0',
		);
		$this->assertEquals( $expected, $data, '', 0.0000000001 );
	}

	public function testUnicodeUserComment() {
		$filename = $this->mediaPath . 'exif-user-comment.jpg';
		$seg = JpegMetadataExtractor::segmentSplitter( $filename );
		$exif = new Exif( $filename, $seg['byteOrder'] );
		$data = $exif->getFilteredData();

		$expected = array(
			'UserComment' => 'test⁔comment'
		);
		$this->assertEquals( $expected, $data );
	}
}
