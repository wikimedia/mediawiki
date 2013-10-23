<?php
/**
 * @todo covers tags
 */
class JpegTest extends MediaWikiTestCase {

	protected function setUp() {
		parent::setUp();
		if ( !extension_loaded( 'exif' ) ) {
			$this->markTestSkipped( "This test needs the exif extension." );
		}

		$this->filePath = __DIR__ . '/../../data/media/';


		$this->setMwGlobals( 'wgShowEXIF', true );
	}

	public function testInvalidFile() {
		$jpeg = new JpegHandler;
		$res = $jpeg->getMetadata( null, $this->filePath . 'README' );
		$this->assertEquals( ExifBitmapHandler::BROKEN_FILE, $res );
	}

	public function testJpegMetadataExtraction() {
		$h = new JpegHandler;
		$res = $h->getMetadata( null, $this->filePath . 'test.jpg' );
		$expected = 'a:7:{s:16:"ImageDescription";s:9:"Test file";s:11:"XResolution";s:4:"72/1";s:11:"YResolution";s:4:"72/1";s:14:"ResolutionUnit";i:2;s:16:"YCbCrPositioning";i:1;s:15:"JPEGFileComment";a:1:{i:0;s:17:"Created with GIMP";}s:22:"MEDIAWIKI_EXIF_VERSION";i:2;}';

		// Unserialize in case serialization format ever changes.
		$this->assertEquals( unserialize( $expected ), unserialize( $res ) );
	}
}
