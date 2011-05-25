<?php

class SVGMetadataExtractorTest extends MediaWikiTestCase {

	function setUp() {
		AutoLoader::loadClass( 'SVGMetadataExtractorTest' );
	}

	/**
	 * @dataProvider providerSvgFiles
	 */
	function testGetMetadata( $infile, $expected ) {
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

	function providerSvgFiles() {
		$base = dirname( __FILE__ );
		return array(
			array(
				"$base/QA_icon.svg",
				array(
					'width' => 60,
					'height' => 60
				)
			),
			array(
				"$base/Gtk-media-play-ltr.svg",
				array(
					'width' => 60,
					'height' => 60
				)
			),
			array(
				"$base/US_states_by_total_state_tax_revenue.svg",
				array(
					'width' => 593,
					'height' => 959
				)
			),
		);
	}
}

