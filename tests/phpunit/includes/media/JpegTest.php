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
		// @codingStandardsIgnoreStart Ignore Generic.Files.LineLength.TooLong
		$expected = 'a:7:{s:16:"ImageDescription";s:9:"Test file";s:11:"XResolution";s:4:"72/1";s:11:"YResolution";s:4:"72/1";s:14:"ResolutionUnit";i:2;s:16:"YCbCrPositioning";i:1;s:15:"JPEGFileComment";a:1:{i:0;s:17:"Created with GIMP";}s:22:"MEDIAWIKI_EXIF_VERSION";i:2;}';
		// @codingStandardsIgnoreEnd

		// Unserialize in case serialization format ever changes.
		$this->assertEquals( unserialize( $expected ), unserialize( $res ) );
	}

	/**
	 * @covers JpegHandler::getCommonMetaArray
	 */
	public function testGetIndependentMetaArray() {
		$file = $this->dataFile( 'test.jpg', 'image/jpeg' );
		$res = $this->handler->getCommonMetaArray( $file );
		$expected = array(
			'ImageDescription' => 'Test file',
			'XResolution' => '72/1',
			'YResolution' => '72/1',
			'ResolutionUnit' => 2,
			'YCbCrPositioning' => 1,
			'JPEGFileComment' => array(
				'Created with GIMP',
			),
		);

		$this->assertEquals( $res, $expected );
	}
}
