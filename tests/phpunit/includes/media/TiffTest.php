<?php
class TiffTest extends MediaWikiTestCase {

	public function setUp() {
		global $wgShowEXIF;
		$this->showExif = $wgShowEXIF;
		$wgShowEXIF = true;
		$this->filePath = __DIR__ . '/../../data/media/';
		$this->handler = new TiffHandler;
	}

	public function tearDown() {
		global $wgShowEXIF;
		$wgShowEXIF = $this->showExif;
	}

	public function testInvalidFile() {
		if ( !wfDl( 'exif' ) ) {
			$this->markTestIncomplete( "This test needs the exif extension." );
		}
		$res = $this->handler->getMetadata( null, $this->filePath . 'README' );
		$this->assertEquals( ExifBitmapHandler::BROKEN_FILE, $res );
	}

	public function testTiffMetadataExtraction() {
		if ( !wfDl( 'exif' ) ) {
			$this->markTestIncomplete( "This test needs the exif extension." );
		}
		$res = $this->handler->getMetadata( null, $this->filePath . 'test.tiff' );
		$expected = 'a:16:{s:10:"ImageWidth";i:20;s:11:"ImageLength";i:20;s:13:"BitsPerSample";a:3:{i:0;i:8;i:1;i:8;i:2;i:8;}s:11:"Compression";i:5;s:25:"PhotometricInterpretation";i:2;s:16:"ImageDescription";s:17:"Created with GIMP";s:12:"StripOffsets";i:8;s:11:"Orientation";i:1;s:15:"SamplesPerPixel";i:3;s:12:"RowsPerStrip";i:64;s:15:"StripByteCounts";i:238;s:11:"XResolution";s:19:"1207959552/16777216";s:11:"YResolution";s:19:"1207959552/16777216";s:19:"PlanarConfiguration";i:1;s:14:"ResolutionUnit";i:2;s:22:"MEDIAWIKI_EXIF_VERSION";i:2;}';
		// Re-unserialize in case there are subtle differences between how versions
		// of php serialize stuff.
		$this->assertEquals( unserialize( $expected ), unserialize( $res ) );
	}
}
