<?php

class WikiCategoryPageTest extends MediaWikiLangTestCase {

	/**
	 * @covers WikiCategoryPage::isHidden
	 */
	public function testHiddenCategory_PropertyNotSet() {
		$title = Title::makeTitle( NS_CATEGORY, 'CategoryPage' );
		$categoryPage = WikiCategoryPage::factory( $title );

		$pageProps = $this->createMock( PageProps::class );
		$pageProps->expects( $this->once() )
			->method( 'getProperties' )
			->with( $title, 'hiddencat' )
			->willReturn( [] );

		$this->setService( 'PageProps', $pageProps );

		$this->assertFalse( $categoryPage->isHidden() );
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

		$pageProps = $this->createMock( PageProps::class );
		$pageProps->expects( $this->once() )
			->method( 'getProperties' )
			->with( $categoryTitle, 'hiddencat' )
			->willReturn( $isHidden ? [ $categoryTitle->getArticleID() => '' ] : [] );

		$this->setService( 'PageProps', $pageProps );

		$this->assertEquals( $isHidden, $categoryPage->isHidden() );
	}

	/**
	 * @covers WikiCategoryPage::isExpectedUnusedCategory
	 */
	public function testExpectUnusedCategory_PropertyNotSet() {
		$title = Title::makeTitle( NS_CATEGORY, 'CategoryPage' );
		$categoryPage = WikiCategoryPage::factory( $title );

		$pageProps = $this->createMock( PageProps::class );
		$pageProps->expects( $this->once() )
			->method( 'getProperties' )
			->with( $title, 'expectunusedcategory' )
			->willReturn( [] );

		$this->setService( 'PageProps', $pageProps );

		$this->assertFalse( $categoryPage->isExpectedUnusedCategory() );
	}

	/**
	 * @dataProvider provideCategoryContent
	 * @covers WikiCategoryPage::isExpectedUnusedCategory
	 */
	public function testExpectUnusedCategory_PropertyIsSet( $isExpectedUnusedCategory ) {
		$categoryTitle = Title::makeTitle( NS_CATEGORY, 'CategoryPage' );
		$categoryPage = WikiCategoryPage::factory( $categoryTitle );
		$returnValue = $isExpectedUnusedCategory ? [ $categoryTitle->getArticleID() => '' ] : [];

		$pageProps = $this->createMock( PageProps::class );
		$pageProps->expects( $this->once() )
			->method( 'getProperties' )
			->with( $categoryTitle, 'expectunusedcategory' )
			->willReturn( $returnValue );

		$this->setService( 'PageProps', $pageProps );

		$this->assertEquals( $isExpectedUnusedCategory, $categoryPage->isExpectedUnusedCategory() );
	}
}
