<?php

class ExifBitmapTest extends MediaWikiTestCase {

	/**
	 * @var ExifBitmapHandler
	 */
	protected $handler;

	protected function setUp() {
		parent::setUp();
		$this->checkPHPExtension( 'exif' );

		$this->setMwGlobals( 'wgShowEXIF', true );

		$this->handler = new ExifBitmapHandler;

	}

	/**
	 * @covers ExifBitmapHandler::isMetadataValid
	 */
	public function testIsOldBroken() {
		$res = $this->handler->isMetadataValid( null, ExifBitmapHandler::OLD_BROKEN_FILE );
		$this->assertEquals( ExifBitmapHandler::METADATA_COMPATIBLE, $res );
	}

	/**
	 * @covers ExifBitmapHandler::isMetadataValid
	 */
	public function testIsBrokenFile() {
		$res = $this->handler->isMetadataValid( null, ExifBitmapHandler::BROKEN_FILE );
		$this->assertEquals( ExifBitmapHandler::METADATA_GOOD, $res );
	}

	/**
	 * @covers ExifBitmapHandler::isMetadataValid
	 */
	public function testIsInvalid() {
		$res = $this->handler->isMetadataValid( null, 'Something Invalid Here.' );
		$this->assertEquals( ExifBitmapHandler::METADATA_BAD, $res );
	}

	/**
	 * @covers ExifBitmapHandler::isMetadataValid
	 */
	public function testGoodMetadata() {
		$meta = 'a:16:{s:10:"ImageWidth";i:20;s:11:"ImageLength";i:20;s:13:"BitsPerSample";a:3:{i:0;i:8;i:1;i:8;i:2;i:8;}s:11:"Compression";i:5;s:25:"PhotometricInterpretation";i:2;s:16:"ImageDescription";s:17:"Created with GIMP";s:12:"StripOffsets";i:8;s:11:"Orientation";i:1;s:15:"SamplesPerPixel";i:3;s:12:"RowsPerStrip";i:64;s:15:"StripByteCounts";i:238;s:11:"XResolution";s:19:"1207959552/16777216";s:11:"YResolution";s:19:"1207959552/16777216";s:19:"PlanarConfiguration";i:1;s:14:"ResolutionUnit";i:2;s:22:"MEDIAWIKI_EXIF_VERSION";i:2;}';
		$res = $this->handler->isMetadataValid( null, $meta );
		$this->assertEquals( ExifBitmapHandler::METADATA_GOOD, $res );
	}

	/**
	 * @covers ExifBitmapHandler::isMetadataValid
	 */
	public function testIsOldGood() {
		$meta = 'a:16:{s:10:"ImageWidth";i:20;s:11:"ImageLength";i:20;s:13:"BitsPerSample";a:3:{i:0;i:8;i:1;i:8;i:2;i:8;}s:11:"Compression";i:5;s:25:"PhotometricInterpretation";i:2;s:16:"ImageDescription";s:17:"Created with GIMP";s:12:"StripOffsets";i:8;s:11:"Orientation";i:1;s:15:"SamplesPerPixel";i:3;s:12:"RowsPerStrip";i:64;s:15:"StripByteCounts";i:238;s:11:"XResolution";s:19:"1207959552/16777216";s:11:"YResolution";s:19:"1207959552/16777216";s:19:"PlanarConfiguration";i:1;s:14:"ResolutionUnit";i:2;s:22:"MEDIAWIKI_EXIF_VERSION";i:1;}';
		$res = $this->handler->isMetadataValid( null, $meta );
		$this->assertEquals( ExifBitmapHandler::METADATA_COMPATIBLE, $res );
	}

	/**
	 * Handle metadata from paged tiff handler (gotten via instant commons) gracefully.
	 * @covers ExifBitmapHandler::isMetadataValid
	 */
	public function testPagedTiffHandledGracefully() {
		$meta = 'a:6:{s:9:"page_data";a:1:{i:1;a:5:{s:5:"width";i:643;s:6:"height";i:448;s:5:"alpha";s:4:"true";s:4:"page";i:1;s:6:"pixels";i:288064;}}s:10:"page_count";i:1;s:10:"first_page";i:1;s:9:"last_page";i:1;s:4:"exif";a:9:{s:10:"ImageWidth";i:643;s:11:"ImageLength";i:448;s:11:"Compression";i:5;s:25:"PhotometricInterpretation";i:2;s:11:"Orientation";i:1;s:15:"SamplesPerPixel";i:4;s:12:"RowsPerStrip";i:50;s:19:"PlanarConfiguration";i:1;s:22:"MEDIAWIKI_EXIF_VERSION";i:1;}s:21:"TIFF_METADATA_VERSION";s:3:"1.4";}';
		$res = $this->handler->isMetadataValid( null, $meta );
		$this->assertEquals( ExifBitmapHandler::METADATA_BAD, $res );
	}

	/**
	 * @covers ExifBitmapHandler::convertMetadataVersion
	 */
	public function testConvertMetadataLatest() {
		$metadata = array(
			'foo' => array( 'First', 'Second', '_type' => 'ol' ),
			'MEDIAWIKI_EXIF_VERSION' => 2
		);
		$res = $this->handler->convertMetadataVersion( $metadata, 2 );
		$this->assertEquals( $metadata, $res );
	}

	/**
	 * @covers ExifBitmapHandler::convertMetadataVersion
	 */
	public function testConvertMetadataToOld() {
		$metadata = array(
			'foo' => array( 'First', 'Second', '_type' => 'ol' ),
			'bar' => array( 'First', 'Second', '_type' => 'ul' ),
			'baz' => array( 'First', 'Second' ),
			'fred' => 'Single',
			'MEDIAWIKI_EXIF_VERSION' => 2,
		);
		$expected = array(
			'foo' => "\n#First\n#Second",
			'bar' => "\n*First\n*Second",
			'baz' => "\n*First\n*Second",
			'fred' => 'Single',
			'MEDIAWIKI_EXIF_VERSION' => 1,
		);
		$res = $this->handler->convertMetadataVersion( $metadata, 1 );
		$this->assertEquals( $expected, $res );
	}

	/**
	 * @covers ExifBitmapHandler::convertMetadataVersion
	 */
	public function testConvertMetadataSoftware() {
		$metadata = array(
			'Software' => array( array( 'GIMP', '1.1' ) ),
			'MEDIAWIKI_EXIF_VERSION' => 2,
		);
		$expected = array(
			'Software' => 'GIMP (Version 1.1)',
			'MEDIAWIKI_EXIF_VERSION' => 1,
		);
		$res = $this->handler->convertMetadataVersion( $metadata, 1 );
		$this->assertEquals( $expected, $res );
	}

	/**
	 * @covers ExifBitmapHandler::convertMetadataVersion
	 */
	public function testConvertMetadataSoftwareNormal() {
		$metadata = array(
			'Software' => array( "GIMP 1.2", "vim" ),
			'MEDIAWIKI_EXIF_VERSION' => 2,
		);
		$expected = array(
			'Software' => "\n*GIMP 1.2\n*vim",
			'MEDIAWIKI_EXIF_VERSION' => 1,
		);
		$res = $this->handler->convertMetadataVersion( $metadata, 1 );
		$this->assertEquals( $expected, $res );
	}
}
