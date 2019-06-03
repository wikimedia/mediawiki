<?php

use MediaWiki\MediaWikiServices;

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
class ImportLinkCacheIntegrationTest extends MediaWikiTestCase {

	private $importStreamSource;

	protected function setUp() {
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
		$loremIpsum = Title::newFromText( 'Lorem ipsum' );

		$this->assertSame(
			$loremIpsum->getArticleID(),
			$loremIpsum->getArticleID( Title::GAID_FOR_UPDATE )
		);

		$categoryLoremIpsum = Title::newFromText( 'Category:Lorem ipsum' );

		$this->assertSame(
			$categoryLoremIpsum->getArticleID(),
			$categoryLoremIpsum->getArticleID( Title::GAID_FOR_UPDATE )
		);

		$page = new WikiPage( $loremIpsum );
		$page->doDeleteArticle( 'import test: delete page' );

		$page = new WikiPage( $categoryLoremIpsum );
		$page->doDeleteArticle( 'import test: delete page' );
	}

	/**
	 * @depends testImportForImportSource
	 */
	public function testReImportForImportSource() {
		$this->doImport( $this->importStreamSource );

		// ReImported title
		$loremIpsum = Title::newFromText( 'Lorem ipsum' );

		$this->assertSame(
			$loremIpsum->getArticleID(),
			$loremIpsum->getArticleID( Title::GAID_FOR_UPDATE )
		);

		$categoryLoremIpsum = Title::newFromText( 'Category:Lorem ipsum' );

		$this->assertSame(
			$categoryLoremIpsum->getArticleID(),
			$categoryLoremIpsum->getArticleID( Title::GAID_FOR_UPDATE )
		);
	}

	private function doImport( $importStreamSource ) {
		$importer = new WikiImporter(
			$importStreamSource->value,
			MediaWikiServices::getInstance()->getMainConfig()
		);
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

		$result = $reporter->close();

		$this->assertTrue(
			$result->isGood()
		);
	}

}
