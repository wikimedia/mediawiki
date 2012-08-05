<?php
/**
 * Test for the demo xml
 *
 * @group Dump
 */
class ExportDemoTest extends DumpTestCase {

	/**
	 * @group large
	 */
	function testExportDemo() {
		$this->validateXmlFileAgainstXsd( "../../docs/export-demo.xml" );
	}

	/**
	 * Validates a xml file against the xsd.
	 *
	 * The validation is slow, because php has to read the xsd on each call.
	 *
	 * @param $fname string: name of file to validate
	 */
	protected function validateXmlFileAgainstXsd( $fname ) {
		$version = WikiExporter::schemaVersion();

		$dom = new DomDocument();
		$dom->load( $fname );

		try {
			$this->assertTrue( $dom->schemaValidate( "../../docs/export-" . $version . ".xsd" ),
				"schemaValidate has found an error" );
		} catch( Exception $e ) {
			$this->fail( "xml not valid against xsd: " . $e->getMessage() );
		}
	}
}
