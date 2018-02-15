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

}
