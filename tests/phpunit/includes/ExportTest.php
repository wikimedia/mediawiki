<?php

/**
 * Test class for Export methods.
 *
 * @group Database
 *
 * @author Isaac Hutt <mhutti1@gmail.com>
 */
class ExportTest extends MediaWikiLangTestCase {

	protected function setUp() {
		parent::setUp();
		$this->setMwGlobals( [
			'wgCapitalLinks' => true,
		] );
	}

	/**
	 * @covers WikiExporter::pageByTitle
	 */
	public function testPageByTitle() {
		global $wgContLang;
		$pageTitle = 'UTPage';

		$exporter = new WikiExporter(
			$this->db,
			WikiExporter::FULL
		);

		$title = Title::newFromText( $pageTitle );

		$sink = new DumpStringOutput;
		$exporter->setOutputSink( $sink );
		$exporter->openStream();
		$exporter->pageByTitle( $title );
		$exporter->closeStream();

		// This throws error if invalid xml output
		$xmlObject = simplexml_load_string( $sink );

		/**
		 * Check namespaces match xml
		 */
		$xmlNamespaces = (array)$xmlObject->siteinfo->namespaces->namespace;
		$xmlNamespaces = str_replace( ' ', '_', $xmlNamespaces );
		unset( $xmlNamespaces[ '@attributes' ] );
		foreach ( $xmlNamespaces as &$namespaceObject ) {
			if ( is_object( $namespaceObject ) ) {
				$namespaceObject = '';
			}
		}

		$actualNamespaces = (array)$wgContLang->getNamespaces();
		$actualNamespaces = array_values( $actualNamespaces );
		$this->assertEquals( $actualNamespaces, $xmlNamespaces );

		// Check xml page title correct
		$xmlTitle = (array)$xmlObject->page->title;
		$this->assertEquals( $pageTitle, $xmlTitle[0] );

		// Check xml page text is not empty
		$text = (array)$xmlObject->page->revision->text;
		$this->assertNotEquals( '', $text[0] );
	}

}
