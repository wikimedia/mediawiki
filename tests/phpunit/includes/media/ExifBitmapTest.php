<?php

class ExifBitmapTest extends MediaWikiTestCase {

	public function testIsOldBroken() {
		if ( wfDl( 'exif' ) ) {
			$this->markTestIncomplete( "This test needs the exif extension." );
		}
		$handler = new ExifBitmapHandler;
		$res = $handler->isMetadataValid( null, ExifBitmapHandler::OLD_BROKEN_FILE );
		$this->assertEquals( ExifBitmapHandler::METADATA_COMPATIBLE, $res );
	}
	public function testIsBrokenFile() {
		if ( !wfDl( 'exif' ) ) {
			$this->markTestIncomplete( "This test needs the exif extension." );
		}
		$handler = new ExifBitmapHandler;
		$res = $handler->isMetadataValid( null, ExifBitmapHandler::BROKEN_FILE );
		$this->assertEquals( ExifBitmapHandler::METADATA_GOOD, $res );
	}
	public function testIsInvalid() {
		if ( !wfDl( 'exif' ) ) {
			$this->markTestIncomplete( "This test needs the exif extension." );
		}
		$handler = new ExifBitmapHandler;
		$res = $handler->isMetadataValid( null, 'Something Invalid Here.' );
		$this->assertEquals( ExifBitmapHandler::METADATA_BAD, $res );
	}
	public function testGoodMetadata() {
		if ( !wfDl( 'exif' ) ) {
			$this->markTestIncomplete( "This test needs the exif extension." );
		}
		$handler = new ExifBitmapHandler;
		$meta = 'a:16:{s:10:"ImageWidth";i:20;s:11:"ImageLength";i:20;s:13:"BitsPerSample";a:3:{i:0;i:8;i:1;i:8;i:2;i:8;}s:11:"Compression";i:5;s:25:"PhotometricInterpretation";i:2;s:16:"ImageDescription";s:17:"Created with GIMP";s:12:"StripOffsets";i:8;s:11:"Orientation";i:1;s:15:"SamplesPerPixel";i:3;s:12:"RowsPerStrip";i:64;s:15:"StripByteCounts";i:238;s:11:"XResolution";s:19:"1207959552/16777216";s:11:"YResolution";s:19:"1207959552/16777216";s:19:"PlanarConfiguration";i:1;s:14:"ResolutionUnit";i:2;s:22:"MEDIAWIKI_EXIF_VERSION";i:2;}';
		$res = $handler->isMetadataValid( null, $meta );
		$this->assertEquals( ExifBitmapHandler::METADATA_GOOD, $res );
	}
	public function testIsOldGood() {
		if ( !wfDl( 'exif' ) ) {
			$this->markTestIncomplete( "This test needs the exif extension." );
		}
		$handler = new ExifBitmapHandler;
		$meta = 'a:16:{s:10:"ImageWidth";i:20;s:11:"ImageLength";i:20;s:13:"BitsPerSample";a:3:{i:0;i:8;i:1;i:8;i:2;i:8;}s:11:"Compression";i:5;s:25:"PhotometricInterpretation";i:2;s:16:"ImageDescription";s:17:"Created with GIMP";s:12:"StripOffsets";i:8;s:11:"Orientation";i:1;s:15:"SamplesPerPixel";i:3;s:12:"RowsPerStrip";i:64;s:15:"StripByteCounts";i:238;s:11:"XResolution";s:19:"1207959552/16777216";s:11:"YResolution";s:19:"1207959552/16777216";s:19:"PlanarConfiguration";i:1;s:14:"ResolutionUnit";i:2;s:22:"MEDIAWIKI_EXIF_VERSION";i:1;}';
		$res = $handler->isMetadataValid( null, $meta );
		$this->assertEquals( ExifBitmapHandler::METADATA_COMPATIBLE, $res );
	}
	// Handle metadata from paged tiff handler (gotten via instant commons)
	// gracefully.
	public function testPagedTiffHandledGracefully() {
		if ( !wfDl( 'exif' ) ) {
			$this->markTestIncomplete( "This test needs the exif extension." );
		}
		$handler = new ExifBitmapHandler;
		$meta = 'a:6:{s:9:"page_data";a:1:{i:1;a:5:{s:5:"width";i:643;s:6:"height";i:448;s:5:"alpha";s:4:"true";s:4:"page";i:1;s:6:"pixels";i:288064;}}s:10:"page_count";i:1;s:10:"first_page";i:1;s:9:"last_page";i:1;s:4:"exif";a:9:{s:10:"ImageWidth";i:643;s:11:"ImageLength";i:448;s:11:"Compression";i:5;s:25:"PhotometricInterpretation";i:2;s:11:"Orientation";i:1;s:15:"SamplesPerPixel";i:4;s:12:"RowsPerStrip";i:50;s:19:"PlanarConfiguration";i:1;s:22:"MEDIAWIKI_EXIF_VERSION";i:1;}s:21:"TIFF_METADATA_VERSION";s:3:"1.4";}';
		$res = $handler->isMetadataValid( null, $meta );
		$this->assertEquals( ExifBitmapHandler::METADATA_BAD, $res );
	}
}
