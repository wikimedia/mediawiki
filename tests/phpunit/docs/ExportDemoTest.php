<?php
/**
 * Tests for demo xml
 *
 * @group Dump
 */
class ExportDemoTest extends DumpTestCase {

	function testExportDemo() {
		$this->validateXmlFileAgainstXsd( "../../docs/export-demo.xml" );
	}
}
