<?php

/**
 * @group Media
 * @covers SVGReader
 */
class SVGReaderTest extends \MediaWikiIntegrationTestCase {

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
		$this->assertMetadata( $infile, $expected );
	}

	/**
	 * @dataProvider provideSvgUnits
	 */
	public function testScaleSVGUnit( $inUnit, $expected ) {
		$this->assertEquals(
			$expected,
			SVGReader::scaleSVGUnit( $inUnit ),
			'SVG unit conversion and scaling failure'
		);
	}

	function assertMetadata( $infile, $expected ) {
		try {
			$svgReader = new SVGReader( $infile );
			$data = $svgReader->getMetadata();

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

		return [
			[
				"$base/Wikimedia-logo.svg",
				[
					'width' => 1024,
					'height' => 1024,
					'originalWidth' => '1024',
					'originalHeight' => '1024',
					'translations' => [],
				]
			],
			[
				"$base/QA_icon.svg",
				[
					'width' => 60,
					'height' => 60,
					'originalWidth' => '60',
					'originalHeight' => '60',
					'translations' => [],
				]
			],
			[
				"$base/Gtk-media-play-ltr.svg",
				[
					'width' => 60,
					'height' => 60,
					'originalWidth' => '60.0000000',
					'originalHeight' => '60.0000000',
					'translations' => [],
				]
			],
			[
				"$base/Toll_Texas_1.svg",
				// This file triggered T33719, needs entity expansion in the xmlns checks
				[
					'width' => 385,
					'height' => 385,
					'originalWidth' => '385',
					'originalHeight' => '385.0004883',
					'translations' => [],
				]
			],
			[
				"$base/Tux.svg",
				[
					'width' => 512,
					'height' => 594,
					'originalWidth' => '100%',
					'originalHeight' => '100%',
					'title' => 'Tux',
					'translations' => [],
					'description' => 'For more information see: http://commons.wikimedia.org/wiki/Image:Tux.svg',
				]
			],
			[
				"$base/Speech_bubbles.svg",
				[
					'width' => 627,
					'height' => 461,
					'originalWidth' => '17.7cm',
					'originalHeight' => '13cm',
					'translations' => [
						'de' => SVGReader::LANG_FULL_MATCH,
						'fr' => SVGReader::LANG_FULL_MATCH,
						'nl' => SVGReader::LANG_FULL_MATCH,
						'tlh-ca' => SVGReader::LANG_FULL_MATCH,
						'tlh' => SVGReader::LANG_PREFIX_MATCH
					],
				]
			],
			[
				"$base/Soccer_ball_animated.svg",
				[
					'width' => 150,
					'height' => 150,
					'originalWidth' => '150',
					'originalHeight' => '150',
					'animated' => true,
					'translations' => []
				],
			],
			[
				"$base/comma_separated_viewbox.svg",
				[
					'width' => 512,
					'height' => 594,
					'originalWidth' => '100%',
					'originalHeight' => '100%',
					'translations' => []
				],
			],
		];
	}

	public static function provideSvgFilesWithXMLMetadata() {
		$base = __DIR__ . '/../../data/media';
		// phpcs:disable Generic.Files.LineLength
		$metadata = '<rdf:RDF xmlns:rdf="http://www.w3.org/1999/02/22-rdf-syntax-ns#">
      <ns4:Work xmlns:ns4="http://creativecommons.org/ns#" rdf:about="">
        <ns5:format xmlns:ns5="http://purl.org/dc/elements/1.1/">image/svg+xml</ns5:format>
        <ns5:type xmlns:ns5="http://purl.org/dc/elements/1.1/" rdf:resource="http://purl.org/dc/dcmitype/StillImage"/>
      </ns4:Work>
    </rdf:RDF>';
		// phpcs:enable

		$metadata = str_replace( "\r", '', $metadata ); // Windows compat
		return [
			[
				"$base/US_states_by_total_state_tax_revenue.svg",
				[
					'height' => 593,
					'metadata' => $metadata,
					'width' => 959,
					'originalWidth' => '958.69',
					'originalHeight' => '592.78998',
					'translations' => [],
				]
			],
		];
	}

	public static function provideSvgUnits() {
		return [
			[ '1' , 1 ],
			[ '1.1' , 1.1 ],
			[ '0.1' , 0.1 ],
			[ '.1' , 0.1 ],
			[ '1e2' , 100 ],
			[ '1E2' , 100 ],
			[ '+1' , 1 ],
			[ '-1' , -1 ],
			[ '-1.1' , -1.1 ],
			[ '1e+2' , 100 ],
			[ '1e-2' , 0.01 ],
			[ '10px' , 10 ],
			[ '10pt' , 10 * 1.25 ],
			[ '10pc' , 10 * 15 ],
			[ '10mm' , 10 * 3.543307 ],
			[ '10cm' , 10 * 35.43307 ],
			[ '10in' , 10 * 90 ],
			[ '10em' , 10 * 16 ],
			[ '10ex' , 10 * 12 ],
			[ '10%' , 51.2 ],
			[ '10 px' , 10 ],
			// Invalid values
			[ '1e1.1', 10 ],
			[ '10bp', 10 ],
			[ 'p10', null ],
		];
	}
}
