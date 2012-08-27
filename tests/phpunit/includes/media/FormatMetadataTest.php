<?php
class FormatMetadataTest extends MediaWikiTestCase {
	public function setUp() {
		if ( !wfDl( 'exif' ) ) {
			$this->markTestSkipped( "This test needs the exif extension." );
		}
		$filePath = __DIR__ .  '/../../data/media';
		$this->backend = new FSFileBackend( array(
			'name'           => 'localtesting',
			'lockManager'    => 'nullLockManager',
			'containerPaths' => array( 'data' => $filePath )
		) );
		$this->repo = new FSRepo( array(
			'name'    => 'temp',
			'url'     => 'http://localhost/thumbtest',
			'backend' => $this->backend
		) );
		global $wgShowEXIF;
		$this->show = $wgShowEXIF;
		$wgShowEXIF = true;
	}
	public function tearDown() {
		global $wgShowEXIF;
		$wgShowEXIF = $this->show;
	}

	public function testInvalidDate() {
		$file = $this->dataFile( 'broken_exif_date.jpg', 'image/jpeg' );
		
		// Throws an error if bug hit
		$meta = $file->formatMetadata();
		$this->assertNotEquals( false, $meta, 'Valid metadata extracted' );
		
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

	private function dataFile( $name, $type ) {
		return new UnregisteredLocalFile( false, $this->repo,
			"mwstore://localtesting/data/$name", $type );
	}
}
