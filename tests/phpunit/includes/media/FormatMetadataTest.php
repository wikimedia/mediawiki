<?php
class FormatMetadataTest extends MediaWikiTestCase {
	public function testInvalidDate() {
		$file = UnregisteredLocalFile::newFromPath( dirname( __FILE__ ) . 
			'/broken_exif_date.jpg', 'image/jpeg' );
		
		// Throws an error if bug hit
		$meta = $file->formatMetadata();
		
		// Find date exif entry
		$this->assertArrayHasKey( 'visible', $meta );
		$dateIndex = null;
		foreach ( $meta['visible'] as $i => $data ) {
			if ( $data['id'] == 'exif-datetimeoriginal' ) {
				$dateIndex = $i;
			}
		}
		$this->assertNotNull( $dateIndex, 'Date entry exists in metadata' );
		$this->assertEquals( '0000:01:00 00:02:27', 
			$meta['visible'][$dateIndex]['value'],
			'File with invalid date metadata (bug 29471)' );
	}
}