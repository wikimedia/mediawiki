<?php

use MediaWiki\MainConfigNames;

/**
 * @group Media
 */
class TiffTest extends MediaWikiIntegrationTestCase {

	/** @var TiffHandler */
	protected $handler;
	/** @var string */
	protected $filePath;

	protected function setUp(): void {
		parent::setUp();
		$this->checkPHPExtension( 'exif' );

		$this->overrideConfigValue( MainConfigNames::ShowEXIF, true );

		$this->filePath = __DIR__ . '/../../data/media/';
		$this->handler = new TiffHandler;
	}

	/**
	 * @covers TiffHandler::getSizeAndMetadata
	 */
	public function testInvalidFile() {
		$res = $this->handler->getSizeAndMetadata( null, $this->filePath . 'README' );
		$this->assertEquals( [ 'metadata' => [ '_error' => ExifBitmapHandler::BROKEN_FILE ] ], $res );
	}

	/**
	 * @covers TiffHandler::getSizeAndMetadata
	 */
	public function testTiffMetadataExtraction() {
		$res = $this->handler->getSizeAndMetadata( null, $this->filePath . 'test.tiff' );

		$expected = [
			'width' => 20,
			'height' => 20,
			'metadata' => [
				'ImageWidth' => 20,
				'ImageLength' => 20,
				'BitsPerSample' => [
					0 => 8,
					1 => 8,
					2 => 8,
				],
				'Compression' => 5,
				'PhotometricInterpretation' => 2,
				'ImageDescription' => 'Created with GIMP',
				'StripOffsets' => 8,
				'Orientation' => 1,
				'SamplesPerPixel' => 3,
				'RowsPerStrip' => 64,
				'StripByteCounts' => 238,
				'XResolution' => '1207959552/16777216',
				'YResolution' => '1207959552/16777216',
				'PlanarConfiguration' => 1,
				'ResolutionUnit' => 2,
				'MEDIAWIKI_EXIF_VERSION' => 2,
			]
		];

		// Re-unserialize in case there are subtle differences between how versions
		// of php serialize stuff.
		$this->assertEquals( $expected, $res );
	}
}
