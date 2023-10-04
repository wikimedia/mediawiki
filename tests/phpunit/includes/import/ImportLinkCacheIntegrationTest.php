<?php

/**
 * Integration test that checks import success and
 * LinkCache integration.
 *
 * @group large
 * @group Database
 * @covers ImportStreamSource
 * @covers ImportReporter
 *
 * @author mwjames
 */
class ImportLinkCacheIntegrationTest extends MediaWikiIntegrationTestCase {

	private $importStreamSource;

	protected function setUp(): void {
		parent::setUp();

		$file = dirname( __DIR__ ) . '/../data/import/ImportLinkCacheIntegrationTest.xml';

		$this->importStreamSource = ImportStreamSource::newFromFile( $file );

		if ( !$this->importStreamSource->isGood() ) {
			throw new Exception( "Import source for {$file} failed" );
		}
	}

	public function testImportForImportSource() {
		$this->doImport( $this->importStreamSource );

		// Imported title
		$loremIpsum = Title::makeTitle( NS_MAIN, 'Lorem ipsum' );

		$this->assertSame(
			$loremIpsum->getArticleID(),
			$loremIpsum->getArticleID( Title::READ_LATEST )
		);

		$categoryLoremIpsum = Title::makeTitle( NS_CATEGORY, 'Lorem ipsum' );

		$this->assertSame(
			$categoryLoremIpsum->getArticleID(),
			$categoryLoremIpsum->getArticleID( Title::READ_LATEST )
		);
	}

	/**
	 * @depends testImportForImportSource
	 */
	public function testReImportForImportSource() {
		$this->doImport( $this->importStreamSource );

		// ReImported title
		$loremIpsum = Title::makeTitle( NS_MAIN, 'Lorem ipsum' );

		$this->assertSame(
			$loremIpsum->getArticleID(),
			$loremIpsum->getArticleID( Title::READ_LATEST )
		);

		$categoryLoremIpsum = Title::makeTitle( NS_CATEGORY, 'Lorem ipsum' );

		$this->assertSame(
			$categoryLoremIpsum->getArticleID(),
			$categoryLoremIpsum->getArticleID( Title::READ_LATEST )
		);
	}

	private function doImport( $importStreamSource ) {
		$importer = $this->getServiceContainer()
			->getWikiImporterFactory()
			->getWikiImporter( $importStreamSource->value );
		$importer->setDebug( true );

		$reporter = new ImportReporter(
			$importer,
			false,
			'',
			false
		);

		$reporter->setContext( new RequestContext() );
		$reporter->open();
		$importer->doImport();
		$this->assertStatusGood( $reporter->close() );
	}

}
