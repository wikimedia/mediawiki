<?php

/**
 * @group Media
 */
class FormatMetadataTest extends MediaWikiMediaTestCase {

	protected function setUp() {
		parent::setUp();

		$this->checkPHPExtension( 'exif' );
		$this->setMwGlobals( 'wgShowEXIF', true );
	}

	/**
	 * @covers File::formatMetadata
	 */
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

	/**
	 * @param string $filename
	 * @param int $expected Total image area
	 * @dataProvider provideFlattenArray
	 * @covers FormatMetadata::flattenArray
	 */
	public function testFlattenArray( $vals, $type, $noHtml, $ctx, $expected ) {
		$actual = FormatMetadata::flattenArray( $vals, $type, $noHtml, $ctx );
		$this->assertEquals( $expected, $actual );
	}

	public static function provideFlattenArray() {
		return array(
			array(
				array( 1, 2, 3 ), 'ul', false, false,
				"<ul><li>1</li>\n<li>2</li>\n<li>3</li></ul>",
			),
			array(
				array( 1, 2, 3 ), 'ol', false, false,
				"<ol><li>1</li>\n<li>2</li>\n<li>3</li></ol>",
			),
			array(
				array( 1, 2, 3 ), 'ul', true, false,
				"\n*1\n*2\n*3",
			),
			array(
				array( 1, 2, 3 ), 'ol', true, false,
				"\n#1\n#2\n#3",
			),
			// TODO: more test cases
		);
	}
}
