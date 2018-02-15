<?php

/**
 * @group Media
 * @covers JpegHandler
 */
class JpegTest extends MediaWikiMediaTestCase {

	protected function setUp() {
		parent::setUp();
		$this->checkPHPExtension( 'exif' );

		$this->setMwGlobals( 'wgShowEXIF', true );

		$this->handler = new JpegHandler;
	}

	public function testInvalidFile() {
		$file = $this->dataFile( 'README', 'image/jpeg' );
		$res = $this->handler->getMetadata( $file, $this->filePath . 'README' );
		$this->assertEquals( ExifBitmapHandler::BROKEN_FILE, $res );
	}

	public function testJpegMetadataExtraction() {
		$file = $this->dataFile( 'test.jpg', 'image/jpeg' );
		$res = $this->handler->getMetadata( $file, $this->filePath . 'test.jpg' );
		// phpcs:ignore Generic.Files.LineLength
		$expected = 'a:7:{s:16:"ImageDescription";s:9:"Test file";s:11:"XResolution";s:4:"72/1";s:11:"YResolution";s:4:"72/1";s:14:"ResolutionUnit";i:2;s:16:"YCbCrPositioning";i:1;s:15:"JPEGFileComment";a:1:{i:0;s:17:"Created with GIMP";}s:22:"MEDIAWIKI_EXIF_VERSION";i:2;}';

		// Unserialize in case serialization format ever changes.
		$this->assertEquals( unserialize( $expected ), unserialize( $res ) );
	}

	/**
	 * @covers JpegHandler::getCommonMetaArray
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

		$this->assertEquals( $res, $expected );
	}

	/**
	 * @dataProvider provideSwappingICCProfile
	 * @covers JpegHandler::swapICCProfile
	 */
	public function testSwappingICCProfile(
		$sourceFilename, $controlFilename, $newProfileFilename, $oldProfileName
	) {
		global $wgExiftool;

		if ( !$wgExiftool || !is_file( $wgExiftool ) ) {
			$this->markTestSkipped( "Exiftool not installed, cannot test ICC profile swapping" );
		}

		$this->setMwGlobals( 'wgUseTinyRGBForJPGThumbnails', true );

		$sourceFilepath = $this->filePath . $sourceFilename;
		$controlFilepath = $this->filePath . $controlFilename;
		$profileFilepath = $this->filePath . $newProfileFilename;
		$filepath = $this->getNewTempFile();

		copy( $sourceFilepath, $filepath );

		$file = $this->dataFile( $sourceFilename, 'image/jpeg' );
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

	public function provideSwappingICCProfile() {
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
