<?php
wfDl('exif');

class JpegTest extends MediaWikiTestCase {

	public function testInvalidFile() {
		if ( !wfDl( 'exif' ) ) {
			$this->markTestIncomplete( "This test needs the exif extension." );
		}
		$jpeg = new JpegHandler;
		$res = $jpeg->getMetadata( null, dirname( __FILE__ ) . '/README' );
		$this->assertEquals( ExifBitmapHandler::BROKEN_FILE, $res );
	}
	public function testTiffFile() {
		if ( !wfDl( 'exif' ) ) {
			$this->markTestIncomplete( "This test needs the exif extension." );
		}
		$h = new JpegHandler;
		$res = $h->getMetadata( null, dirname( __FILE__ ) . '/test.jpg' );
		$expected = 'a:7:{s:16:"ImageDescription";s:9:"Test file";s:11:"XResolution";s:4:"72/1";s:11:"YResolution";s:4:"72/1";s:14:"ResolutionUnit";i:2;s:16:"YCbCrPositioning";i:1;s:15:"JPEGFileComment";a:1:{i:0;s:17:"Created with GIMP";}s:22:"MEDIAWIKI_EXIF_VERSION";i:2;}';
		// Hopefully php always serializes things in the same order.
		$this->assertEquals( $expected, $res );
	}
}
