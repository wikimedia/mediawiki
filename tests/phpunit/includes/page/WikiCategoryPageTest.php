<?php

use PHPUnit\Framework\MockObject\MockObject;
use Wikimedia\ScopedCallback;

class WikiCategoryPageTest extends MediaWikiLangTestCase {

	/**
	 * @return MockObject|PageProps
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

	/**
	 * @covers WikiCategoryPage::isExpectedUnusedCategory
	 */
	public function testExpectUnusedCategory_PropertyNotSet() {
		$title = Title::makeTitle( NS_CATEGORY, 'CategoryPage' );
		$categoryPage = WikiCategoryPage::factory( $title );

		$pageProps = $this->getMockPageProps();
		$pageProps->expects( $this->once() )
			->method( 'getProperties' )
			->with( $title, 'expectunusedcategory' )
			->will( $this->returnValue( [] ) );

		$scopedOverride = PageProps::overrideInstance( $pageProps );

		$this->assertFalse( $categoryPage->isExpectedUnusedCategory() );

		ScopedCallback::consume( $scopedOverride );
	}

	/**
	 * @dataProvider provideCategoryContent
	 * @covers WikiCategoryPage::isExpectedUnusedCategory
	 */
	public function testExpectUnusedCategory_PropertyIsSet( $isExpectedUnusedCategory ) {
		$categoryTitle = Title::makeTitle( NS_CATEGORY, 'CategoryPage' );
		$categoryPage = WikiCategoryPage::factory( $categoryTitle );
		$returnValue = $isExpectedUnusedCategory ? [ $categoryTitle->getArticleID() => '' ] : [];

		$pageProps = $this->getMockPageProps();
		$pageProps->expects( $this->once() )
			->method( 'getProperties' )
			->with( $categoryTitle, 'expectunusedcategory' )
			->will( $this->returnValue( $returnValue ) );

		$scopedOverride = PageProps::overrideInstance( $pageProps );

		$this->assertEquals( $isExpectedUnusedCategory, $categoryPage->isExpectedUnusedCategory() );

		ScopedCallback::consume( $scopedOverride );
	}
}
