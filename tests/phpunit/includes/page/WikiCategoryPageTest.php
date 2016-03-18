<?php

class WikiCategoryPageTest extends MediaWikiLangTestCase {

	public function provideCategoryContent() {
		return [
			[ "Hidden. __HIDDENCAT__", true ],
			[ "Not Hidden.", false ],
		];
	}

	/**
	 * @dataProvider provideCategoryContent
	 * @covers RecentChange::newForCategorization
	 */
	public function testHiddenCategoryChange( $content, $isHidden ) {
		$title = Title::makeTitle( NS_CATEGORY, 'CategoryPage' );
		$categoryPage = WikiPage::factory( $title );
		$content = ContentHandler::makeContent(
			$content,
			$title,
			CONTENT_MODEL_WIKITEXT
		);
		$categoryPage->doEditContent( $content, '' );

		$this->assertEquals( $isHidden, WikiCategoryPage::isHidden( $categoryPage ) );
	}
}
