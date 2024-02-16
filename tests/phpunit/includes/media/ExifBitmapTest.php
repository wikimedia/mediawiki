<?php

use MediaWiki\MainConfigNames;

/**
 * @group Media
 * @covers \ExifBitmapHandler
 * @requires extension exif
 */
class ExifBitmapTest extends MediaWikiMediaTestCase {

	/**
	 * @var ExifBitmapHandler
	 */
	protected $handler;

	protected function setUp(): void {
		parent::setUp();

		$this->overrideConfigValue( MainConfigNames::ShowEXIF, true );

		$this->handler = new ExifBitmapHandler;
	}

	public static function provideIsFileMetadataValid() {
		return [
			'old broken' => [
				ExifBitmapHandler::OLD_BROKEN_FILE,
				ExifBitmapHandler::METADATA_COMPATIBLE
			],
			'broken' => [
				ExifBitmapHandler::BROKEN_FILE,
				ExifBitmapHandler::METADATA_GOOD
			],
			'invalid' => [
				'Something Invalid Here.',
				ExifBitmapHandler::METADATA_BAD
			],
			'good' => [
				'a:16:{s:10:"ImageWidth";i:20;s:11:"ImageLength";i:20;s:13:"BitsPerSample";a:3:{i:0;i:8;i:1;i:8;i:2;i:8;}s:11:"Compression";i:5;s:25:"PhotometricInterpretation";i:2;s:16:"ImageDescription";s:17:"Created with GIMP";s:12:"StripOffsets";i:8;s:11:"Orientation";i:1;s:15:"SamplesPerPixel";i:3;s:12:"RowsPerStrip";i:64;s:15:"StripByteCounts";i:238;s:11:"XResolution";s:19:"1207959552/16777216";s:11:"YResolution";s:19:"1207959552/16777216";s:19:"PlanarConfiguration";i:1;s:14:"ResolutionUnit";i:2;s:22:"MEDIAWIKI_EXIF_VERSION";i:2;}',
				ExifBitmapHandler::METADATA_GOOD
			],
			'old good' => [
				'a:16:{s:10:"ImageWidth";i:20;s:11:"ImageLength";i:20;s:13:"BitsPerSample";a:3:{i:0;i:8;i:1;i:8;i:2;i:8;}s:11:"Compression";i:5;s:25:"PhotometricInterpretation";i:2;s:16:"ImageDescription";s:17:"Created with GIMP";s:12:"StripOffsets";i:8;s:11:"Orientation";i:1;s:15:"SamplesPerPixel";i:3;s:12:"RowsPerStrip";i:64;s:15:"StripByteCounts";i:238;s:11:"XResolution";s:19:"1207959552/16777216";s:11:"YResolution";s:19:"1207959552/16777216";s:19:"PlanarConfiguration";i:1;s:14:"ResolutionUnit";i:2;s:22:"MEDIAWIKI_EXIF_VERSION";i:1;}',
				ExifBitmapHandler::METADATA_COMPATIBLE
			],
			// Handle metadata from paged tiff handler (gotten via instant commons) gracefully.
			'paged tiff' => [
				'a:6:{s:9:"page_data";a:1:{i:1;a:5:{s:5:"width";i:643;s:6:"height";i:448;s:5:"alpha";s:4:"true";s:4:"page";i:1;s:6:"pixels";i:288064;}}s:10:"page_count";i:1;s:10:"first_page";i:1;s:9:"last_page";i:1;s:4:"exif";a:9:{s:10:"ImageWidth";i:643;s:11:"ImageLength";i:448;s:11:"Compression";i:5;s:25:"PhotometricInterpretation";i:2;s:11:"Orientation";i:1;s:15:"SamplesPerPixel";i:4;s:12:"RowsPerStrip";i:50;s:19:"PlanarConfiguration";i:1;s:22:"MEDIAWIKI_EXIF_VERSION";i:1;}s:21:"TIFF_METADATA_VERSION";s:3:"1.4";}',
				ExifBitmapHandler::METADATA_BAD
			],

		];
	}

	/** @dataProvider provideIsFileMetadataValid */
	public function testIsFileMetadataValid( $serializedMetadata, $expected ) {
		$file = $this->getMockFileWithMetadata( $serializedMetadata );
		$res = $this->handler->isFileMetadataValid( $file );
		$this->assertEquals( $expected, $res );
	}

	public function testConvertMetadataLatest() {
		$metadata = [
			'foo' => [ 'First', 'Second', '_type' => 'ol' ],
			'MEDIAWIKI_EXIF_VERSION' => 2
		];
		$res = $this->handler->convertMetadataVersion( $metadata, 2 );
		$this->assertEquals( $metadata, $res );
	}

	public function testConvertMetadataToOld() {
		$metadata = [
			'foo' => [ 'First', 'Second', '_type' => 'ol' ],
			'bar' => [ 'First', 'Second', '_type' => 'ul' ],
			'baz' => [ 'First', 'Second' ],
			'fred' => 'Single',
			'MEDIAWIKI_EXIF_VERSION' => 2,
		];
		$expected = [
			'foo' => "\n#First\n#Second",
			'bar' => "\n*First\n*Second",
			'baz' => "\n*First\n*Second",
			'fred' => 'Single',
			'MEDIAWIKI_EXIF_VERSION' => 1,
		];
		$res = $this->handler->convertMetadataVersion( $metadata, 1 );
		$this->assertEquals( $expected, $res );
	}

	public function testConvertMetadataSoftware() {
		$metadata = [
			'Software' => [ [ 'GIMP', '1.1' ] ],
			'MEDIAWIKI_EXIF_VERSION' => 2,
		];
		$expected = [
			'Software' => 'GIMP (Version 1.1)',
			'MEDIAWIKI_EXIF_VERSION' => 1,
		];
		$res = $this->handler->convertMetadataVersion( $metadata, 1 );
		$this->assertEquals( $expected, $res );
	}

	public function testConvertMetadataSoftwareNormal() {
		$metadata = [
			'Software' => [ "GIMP 1.2", "vim" ],
			'MEDIAWIKI_EXIF_VERSION' => 2,
		];
		$expected = [
			'Software' => "\n*GIMP 1.2\n*vim",
			'MEDIAWIKI_EXIF_VERSION' => 1,
		];
		$res = $this->handler->convertMetadataVersion( $metadata, 1 );
		$this->assertEquals( $expected, $res );
	}
}
