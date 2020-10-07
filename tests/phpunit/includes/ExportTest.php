<?php

use MediaWiki\MediaWikiServices;

/**
 * Test class for Export methods.
 *
 * @group Database
 *
 * @author Isaac Hutt <mhutti1@gmail.com>
 */
class ExportTest extends MediaWikiLangTestCase {

	protected function setUp() : void {
		parent::setUp();
		$this->setMwGlobals( [
			'wgCapitalLinks' => true,
		] );
	}

	/**
	 * @covers WikiExporter::pageByTitle
	 */
	public function testPageByTitle() {
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

		$oldDisable = libxml_disable_entity_loader( true );

		// This throws error if invalid xml output
		$xmlObject = simplexml_load_string( $sink );

		libxml_disable_entity_loader( $oldDisable );

		/**
		 * Check namespaces match xml
		 */
		foreach ( $xmlObject->siteinfo->namespaces->children() as $namespace ) {
			// Get the text content of the SimpleXMLElement
			$xmlNamespaces[] = (string)$namespace;
		}
		$xmlNamespaces = str_replace( ' ', '_', $xmlNamespaces );

		$actualNamespaces = (array)MediaWikiServices::getInstance()->getContentLanguage()->
			getNamespaces();
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
