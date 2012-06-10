<?php
/**
 * Tests for demo xml
 *
 * @group Dump
 */
class ExportDemoTest extends DumpTestCase {

	function testExportDemo() {
		$this->validateXmlAgainstXsd( "../../docs/export-demo.xml" );
	}
}
