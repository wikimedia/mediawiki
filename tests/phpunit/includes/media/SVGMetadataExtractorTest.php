<?php

/**
 * @todo covers tags
 */
class SVGMetadataExtractorTest extends MediaWikiTestCase {

	protected function setUp() {
		parent::setUp();
		AutoLoader::loadClass( 'SVGMetadataExtractorTest' );
	}

	/**
	 * @dataProvider provideSvgFiles
	 */
	public function testGetMetadata( $infile, $expected ) {
		$this->assertMetadata( $infile, $expected );
	}

	/**
	 * @dataProvider provideSvgFilesWithXMLMetadata
	 */
	public function testGetXMLMetadata( $infile, $expected ) {
		$r = new XMLReader();
		if ( !method_exists( $r, 'readInnerXML' ) ) {
			$this->markTestSkipped( 'XMLReader::readInnerXML() does not exist (libxml >2.6.20 needed).' );

			return;
		}
		$this->assertMetadata( $infile, $expected );
	}

	function assertMetadata( $infile, $expected ) {
		try {
			$data = SVGMetadataExtractor::getMetadata( $infile );
			$this->assertEquals( $expected, $data, 'SVG metadata extraction test' );
		} catch ( MWException $e ) {
			if ( $expected === false ) {
				$this->assertTrue( true, 'SVG metadata extracted test (expected failure)' );
			} else {
				throw $e;
			}
		}
	}

	public static function provideSvgFiles() {
		$base = __DIR__ . '/../../data/media';

		return array(
			array(
				"$base/Wikimedia-logo.svg",
				array(
					'width' => 1024,
					'height' => 1024,
					'originalWidth' => '1024',
					'originalHeight' => '1024',
				)
			),
			array(
				"$base/QA_icon.svg",
				array(
					'width' => 60,
					'height' => 60,
					'originalWidth' => '60',
					'originalHeight' => '60',
				)
			),
			array(
				"$base/Gtk-media-play-ltr.svg",
				array(
					'width' => 60,
					'height' => 60,
					'originalWidth' => '60.0000000',
					'originalHeight' => '60.0000000',
				)
			),
			array(
				"$base/Toll_Texas_1.svg",
				// This file triggered bug 31719, needs entity expansion in the xmlns checks
				array(
					'width' => 385,
					'height' => 385,
					'originalWidth' => '385',
					'originalHeight' => '385.0004883',
				)
			)
		);
	}

	public static function provideSvgFilesWithXMLMetadata() {
		$base = __DIR__ . '/../../data/media';
		$metadata = '<rdf:RDF xmlns:rdf="http://www.w3.org/1999/02/22-rdf-syntax-ns#">
      <ns4:Work xmlns:ns4="http://creativecommons.org/ns#" rdf:about="">
        <ns5:format xmlns:ns5="http://purl.org/dc/elements/1.1/">image/svg+xml</ns5:format>
        <ns5:type xmlns:ns5="http://purl.org/dc/elements/1.1/" rdf:resource="http://purl.org/dc/dcmitype/StillImage"/>
      </ns4:Work>
    </rdf:RDF>';
		$metadata = str_replace( "\r", '', $metadata ); // Windows compat
		return array(
			array(
				"$base/US_states_by_total_state_tax_revenue.svg",
				array(
					'height' => 593,
					'metadata' => $metadata,
					'width' => 959,
					'originalWidth' => '958.69',
					'originalHeight' => '592.78998',
				)
			),
		);
	}
}
