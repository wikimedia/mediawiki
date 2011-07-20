<?php
class TiffTest extends MediaWikiTestCase {

	public function setUp() {
		global $wgShowEXIF;
		$this->showExif = $wgShowEXIF;
		$wgShowEXIF = true;
	}

	public function tearDown() {
		global $wgShowEXIF;
		$wgShowEXIF = $this->showExif;
	}

	public function testInvalidFile() {
		if ( !wfDl( 'exif' ) ) {
			$this->markTestIncomplete( "This test needs the exif extension." );
		}
		$tiff = new TiffHandler;
		$res = $tiff->getMetadata( null, dirname( __FILE__ ) . '/README' );
		$this->assertEquals( ExifBitmapHandler::BROKEN_FILE, $res );
	}

	public function testTiffFile() {
		if ( !wfDl( 'exif' ) ) {
			$this->markTestIncomplete( "This test needs the exif extension." );
		}
		$tiff = new TiffHandler;
		$res = $tiff->getMetadata( null, dirname( __FILE__ ) . '/test.tiff' );
		$expected = 'a:16:{s:10:"ImageWidth";i:20;s:11:"ImageLength";i:20;s:13:"BitsPerSample";a:3:{i:0;i:8;i:1;i:8;i:2;i:8;}s:11:"Compression";i:5;s:25:"PhotometricInterpretation";i:2;s:16:"ImageDescription";s:17:"Created with GIMP";s:12:"StripOffsets";i:8;s:11:"Orientation";i:1;s:15:"SamplesPerPixel";i:3;s:12:"RowsPerStrip";i:64;s:15:"StripByteCounts";i:238;s:11:"XResolution";s:19:"1207959552/16777216";s:11:"YResolution";s:19:"1207959552/16777216";s:19:"PlanarConfiguration";i:1;s:14:"ResolutionUnit";i:2;s:22:"MEDIAWIKI_EXIF_VERSION";i:2;}';
		// Hopefully php always serializes things in the same order.
		$this->assertEquals( $expected, $res );
	}
}
