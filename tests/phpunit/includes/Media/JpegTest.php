<?php

use MediaWiki\MainConfigNames;

/**
 * @group Media
 * @covers \JpegHandler
 * @requires extension exif
 */
class JpegTest extends MediaWikiMediaTestCase {
	/** @var JpegHandler */
	private $handler;

	protected function setUp(): void {
		parent::setUp();

		$this->overrideConfigValue( MainConfigNames::ShowEXIF, true );

		$this->handler = new JpegHandler;
	}

	public function testInvalidFile() {
		$file = $this->dataFile( 'README', 'image/jpeg' );
		$res = $this->handler->getSizeAndMetadataWithFallback( $file, $this->filePath . 'README' );
		$this->assertEquals( [ '_error' => ExifBitmapHandler::BROKEN_FILE ], $res['metadata'] );
	}

	public function testJpegMetadataExtraction() {
		$file = $this->dataFile( 'test.jpg', 'image/jpeg' );
		$res = $this->handler->getSizeAndMetadataWithFallback( $file, $this->filePath . 'test.jpg' );
		$expected = [
			'ImageDescription' => 'Test file',
			'XResolution' => '72/1',
			'YResolution' => '72/1',
			'ResolutionUnit' => 2,
			'YCbCrPositioning' => 1,
			'JPEGFileComment' => [
				0 => 'Created with GIMP',
			],
			'MEDIAWIKI_EXIF_VERSION' => 2,
		];

		// Unserialize in case serialization format ever changes.
		$this->assertEquals( $expected, $res['metadata'] );
	}

	/**
	 * @covers \JpegHandler::getCommonMetaArray
	 */
	public function testGetIndependentMetaArray() {
		$file = $this->dataFile( 'test.jpg', 'image/jpeg' );
		$res = $this->handler->getCommonMetaArray( $file );
		$expected = [
			'ImageDescription' => 'Test file',
			'XResolution' => '72/1',
			'YResolution' => '72/1',
			'ResolutionUnit' => 2,
			'YCbCrPositioning' => 1,
			'JPEGFileComment' => [
				'Created with GIMP',
			],
		];

		$this->assertEquals( $expected, $res );
	}

	/**
	 * @dataProvider provideSwappingICCProfile
	 * @covers \JpegHandler::swapICCProfile
	 */
	public function testSwappingICCProfile(
		$sourceFilename, $controlFilename, $newProfileFilename, $oldProfileName
	) {
		global $wgExiftool;

		if ( !$wgExiftool || !is_file( $wgExiftool ) ) {
			$this->markTestSkipped( "Exiftool not installed, cannot test ICC profile swapping" );
		}

		$this->overrideConfigValue( MainConfigNames::UseTinyRGBForJPGThumbnails, true );

		$sourceFilepath = $this->filePath . $sourceFilename;
		$controlFilepath = $this->filePath . $controlFilename;
		$profileFilepath = $this->filePath . $newProfileFilename;
		$filepath = $this->getNewTempFile();

		copy( $sourceFilepath, $filepath );

		$this->handler->swapICCProfile(
			$filepath,
			[ 'sRGB', '-' ],
			[ $oldProfileName ],
			$profileFilepath
		);

		$this->assertEquals(
			sha1( file_get_contents( $filepath ) ),
			sha1( file_get_contents( $controlFilepath ) )
		);
	}

	public static function provideSwappingICCProfile() {
		return [
			// File with sRGB should end up with TinyRGB
			[
				'srgb.jpg',
				'tinyrgb.jpg',
				'tinyrgb.icc',
				'sRGB IEC61966-2.1'
			],
			// File with TinyRGB should be left unchanged
			[
				'tinyrgb.jpg',
				'tinyrgb.jpg',
				'tinyrgb.icc',
				'sRGB IEC61966-2.1'
			],
			// File without profile should end up with TinyRGB
			[
				'missingprofile.jpg',
				'tinyrgb.jpg',
				'tinyrgb.icc',
				'sRGB IEC61966-2.1'
			],
			// Non-sRGB file should be left untouched
			[
				'adobergb.jpg',
				'adobergb.jpg',
				'tinyrgb.icc',
				'sRGB IEC61966-2.1'
			]
		];
	}
}
