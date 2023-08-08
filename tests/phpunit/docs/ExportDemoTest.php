<?php

use MediaWiki\Tests\Maintenance\DumpTestCase;

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
		$fname = __DIR__ . "/../../../docs/export-demo.xml";
		$version = WikiExporter::schemaVersion();
		$dom = new DomDocument();
		$dom->load( $fname );

		// Ensure, the demo is for the current version
		$this->assertEquals(
			$version,
			$dom->documentElement->getAttribute( 'version' ),
			'export-demo.xml should have the current version'
		);

		$this->assertTrue(
			$dom->schemaValidate( __DIR__ . "/../../../docs/export-" . $version . ".xsd" ),
			"schemaValidate has found an error"
		);
	}

}
