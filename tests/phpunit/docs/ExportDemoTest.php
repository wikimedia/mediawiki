<?php

/**
 * Test making sure the demo export xml is valid.
 * This is NOT a unit test
 *
 * @group Dump
 * @group large
 * @coversNothing
 */
class ExportDemoTest extends DumpTestCase {

	public function testExportDemo() {
		$fname = "../../docs/export-demo.xml";
		$version = WikiExporter::schemaVersion();
		$dom = new DomDocument();
		$dom->load( $fname );

		// Ensure, the demo is for the current version
		$this->assertEquals(
			$dom->documentElement->getAttribute( 'version' ),
			$version,
			'export-demo.xml should have the current version'
		);

		$this->assertTrue(
			$dom->schemaValidate( "../../docs/export-" . $version . ".xsd" ),
			"schemaValidate has found an error"
		);
	}

}
