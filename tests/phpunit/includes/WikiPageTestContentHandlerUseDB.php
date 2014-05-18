<?php

/**
 * @group ContentHandler
 * @group Database
 * ^--- important, causes temporary tables to be used instead of the real database
 */
class WikiPageTestContentHandlerUseDB extends WikiPageTest {

	protected function setUp() {
		parent::setUp();
		$this->setMwGlobals( 'wgContentHandlerUseDB', false );

		$dbw = wfGetDB( DB_MASTER );

		$page_table = $dbw->tableName( 'page' );
		$revision_table = $dbw->tableName( 'revision' );
		$archive_table = $dbw->tableName( 'archive' );

		if ( $dbw->fieldExists( $page_table, 'page_content_model' ) ) {
			$dbw->query( "alter table $page_table drop column page_content_model" );
			$dbw->query( "alter table $revision_table drop column rev_content_model" );
			$dbw->query( "alter table $revision_table drop column rev_content_format" );
			$dbw->query( "alter table $archive_table drop column ar_content_model" );
			$dbw->query( "alter table $archive_table drop column ar_content_format" );
		}
	}

	/**
	 * @covers WikiPage::getContentModel
	 */
	public function testGetContentModel() {
		$page = $this->createPage(
			"WikiPageTest_testGetContentModel",
			"some text",
			CONTENT_MODEL_JAVASCRIPT
		);

		$page = new WikiPage( $page->getTitle() );

		// NOTE: since the content model is not recorded in the database,
		//       we expect to get the default, namely CONTENT_MODEL_WIKITEXT
		$this->assertEquals( CONTENT_MODEL_WIKITEXT, $page->getContentModel() );
	}

	/**
	 * @covers WikiPage::getContentHandler
	 */
	public function testGetContentHandler() {
		$page = $this->createPage(
			"WikiPageTest_testGetContentHandler",
			"some text",
			CONTENT_MODEL_JAVASCRIPT
		);

		// NOTE: since the content model is not recorded in the database,
		//       we expect to get the default, namely CONTENT_MODEL_WIKITEXT
		$page = new WikiPage( $page->getTitle() );
		$this->assertEquals( 'WikitextContentHandler', get_class( $page->getContentHandler() ) );
	}
}
