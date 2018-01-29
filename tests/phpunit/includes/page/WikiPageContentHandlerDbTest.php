<?php

/**
 * @group ContentHandler
 * @group Database
 * @group medium
 */
class WikiPageContentHandlerDbTest extends WikiPageDbTestBase {

	protected function getContentHandlerUseDB() {
		return true;
	}

	/**
	 * @covers WikiPage::getContentModel
	 */
	public function testGetContentModel() {
		$page = $this->createPage(
			__METHOD__,
			"some text",
			CONTENT_MODEL_JAVASCRIPT
		);

		$page = new WikiPage( $page->getTitle() );
		$this->assertEquals( CONTENT_MODEL_JAVASCRIPT, $page->getContentModel() );
	}

	/**
	 * @covers WikiPage::getContentHandler
	 */
	public function testGetContentHandler() {
		$page = $this->createPage(
			__METHOD__,
			"some text",
			CONTENT_MODEL_JAVASCRIPT
		);

		$page = new WikiPage( $page->getTitle() );
		$this->assertEquals( JavaScriptContentHandler::class, get_class( $page->getContentHandler() ) );
	}

	public function provideMigrations() {
		yield 'MIGRATION_OLD' => [ MIGRATION_OLD ];
		yield 'MIGRATION_WRITE_BOTH' => [ MIGRATION_WRITE_BOTH ];
		yield 'MIGRATION_WRITE_NEW' => [ MIGRATION_WRITE_NEW ];
		yield 'MIGRATION_NEW' => [ MIGRATION_NEW ];
	}

}
