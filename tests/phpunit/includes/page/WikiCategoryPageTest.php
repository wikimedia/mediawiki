<?php

class WikiCategoryPageTest extends MediaWikiLangTestCase {

	/**
	 * @return PHPUnit_Framework_MockObject_MockObject|PageProps
	 */
	private function getMockPageProps() {
		return $this->getMockBuilder( PageProps::class )
			->disableOriginalConstructor()
			->getMock();
	}

	/**
	 * @covers WikiCategoryPage::isHidden
	 */
	public function testHiddenCategory_PropertyNotSet() {
		$title = Title::makeTitle( NS_CATEGORY, 'CategoryPage' );
		$categoryPage = WikiCategoryPage::factory( $title );

		$pageProps = $this->getMockPageProps();
		$pageProps->expects( $this->once() )
			->method( 'getProperties' )
			->with( $title, 'hiddencat' )
			->will( $this->returnValue( [] ) );

		$scopedOverride = PageProps::overrideInstance( $pageProps );

		$this->assertFalse( $categoryPage->isHidden() );

		ScopedCallback::consume( $scopedOverride );
	}

	public function provideCategoryContent() {
		return [
			[ true ],
			[ false ],
		];
	}

	/**
	 * @dataProvider provideCategoryContent
	 * @covers WikiCategoryPage::isHidden
	 */
	public function testHiddenCategory_PropertyIsSet( $isHidden ) {
		$categoryTitle = Title::makeTitle( NS_CATEGORY, 'CategoryPage' );
		$categoryPage = WikiCategoryPage::factory( $categoryTitle );

		$pageProps = $this->getMockPageProps();
		$pageProps->expects( $this->once() )
			->method( 'getProperties' )
			->with( $categoryTitle, 'hiddencat' )
			->will( $this->returnValue( $isHidden ? [ $categoryTitle->getArticleID() => '' ] : [] ) );

		$scopedOverride = PageProps::overrideInstance( $pageProps );

		$this->assertEquals( $isHidden, $categoryPage->isHidden() );

		ScopedCallback::consume( $scopedOverride );
	}
}
