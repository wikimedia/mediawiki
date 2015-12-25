<?php

/**
 * Test class for Export methods.
 *
 * @group Database
 *
 * @author Isaac Hutt < mhutti1@gmail.com >
 */
class ExportTest extends MediaWikiLangTestCase {

	protected function setUp() {

		parent::setUp();
		$this->setMwGlobals( array(
		'wgContLang' => Language::factory( 'en' ),
		'wgLanguageCode' => 'en',
		'wgCapitalLinks' => true,
		) );
	}
	protected function tearDown() {
		parent::tearDown();
	}


	/**
	 * @covers WikiExporter::pageByTitle
	 * @dataProvider providePageTitle
	 * @param string $pageTitle
	 */
	public function testPageByTitle( $pageTitle ) {
		global $wgContLang;

		$exporter = new WikiExporter(
			$this->db,
			WikiExporter::FULL
		);

		$title = Title::newFromText( $pageTitle );

		ob_start();
		$exporter->openStream();
		$exporter->pageByTitle( $title );
		$exporter->closeStream();
		$xmlString = ob_get_clean();

		// This throws error if invalid xml output
		$xmlObject = simplexml_load_string( $xmlString );

		// Check Namespaces match to ensure xml isn't valid but meaningless
		$xmlNamespaces = (array) $xmlObject->siteinfo->namespaces->namespace;
		$xmlNamespaces = str_replace( ' ', '_', $xmlNamespaces );
		unset ( $xmlNamespaces[ '@attributes' ] );
		foreach ( $xmlNamespaces as &$namespaceObject ) {
			if ( gettype( $namespaceObject ) === 'object' ) {
				$namespaceObject = '';
			}
		}

		$actualNamespaces = (array) $wgContLang->getNamespaces();
		$actualNamespaces = array_values( $actualNamespaces );
		$this->assertEquals( $actualNamespaces, $xmlNamespaces );

	}

	public function providePageTitle() {
		return array( array( 'UTPage' ) );
	}


}
