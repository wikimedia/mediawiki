<?php

/**
 * @group Media
 * @covers SVGMetadataExtractor
 */
class SVGMetadataExtractorTest extends MediaWikiTestCase {

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
					'translations' => array(),
				)
			),
			array(
				"$base/QA_icon.svg",
				array(
					'width' => 60,
					'height' => 60,
					'originalWidth' => '60',
					'originalHeight' => '60',
					'translations' => array(),
				)
			),
			array(
				"$base/Gtk-media-play-ltr.svg",
				array(
					'width' => 60,
					'height' => 60,
					'originalWidth' => '60.0000000',
					'originalHeight' => '60.0000000',
					'translations' => array(),
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
					'translations' => array(),
				)
			),
			array(
				"$base/Tux.svg",
				array(
					'width' => 512,
					'height' => 594,
					'originalWidth' => '100%',
					'originalHeight' => '100%',
					'title' => 'Tux',
					'translations' => array(),
					'description' => 'For more information see: http://commons.wikimedia.org/wiki/Image:Tux.svg',
				)
			),
			array(
				"$base/Speech_bubbles.svg",
				array(
					'width' => 627,
					'height' => 461,
					'originalWidth' => '17.7cm',
					'originalHeight' => '13cm',
					'translations' => array(
						'de' => SVGReader::LANG_FULL_MATCH,
						'fr' => SVGReader::LANG_FULL_MATCH,
						'nl' => SVGReader::LANG_FULL_MATCH,
						'tlh-ca' => SVGReader::LANG_FULL_MATCH,
						'tlh' => SVGReader::LANG_PREFIX_MATCH
					),
				)
			),
			array(
				"$base/Soccer_ball_animated.svg",
				array(
					'width' => 150,
					'height' => 150,
					'originalWidth' => '150',
					'originalHeight' => '150',
					'animated' => true,
					'translations' => array()
				),
			),
		);
	}

	public static function provideSvgFilesWithXMLMetadata() {
		$base = __DIR__ . '/../../data/media';
		// @codingStandardsIgnoreStart Ignore Generic.Files.LineLength.TooLong
		$metadata = '<rdf:RDF xmlns:rdf="http://www.w3.org/1999/02/22-rdf-syntax-ns#">
      <ns4:Work xmlns:ns4="http://creativecommons.org/ns#" rdf:about="">
        <ns5:format xmlns:ns5="http://purl.org/dc/elements/1.1/">image/svg+xml</ns5:format>
        <ns5:type xmlns:ns5="http://purl.org/dc/elements/1.1/" rdf:resource="http://purl.org/dc/dcmitype/StillImage"/>
      </ns4:Work>
    </rdf:RDF>';
		// @codingStandardsIgnoreEnd

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
					'translations' => array(),
				)
			),
		);
	}
}
