<?php

use MediaWiki\Page\PageProps;
use MediaWiki\Title\Title;

class WikiCategoryPageTest extends MediaWikiLangTestCase {

	/**
	 * @covers \WikiCategoryPage::isHidden
	 */
	public function testHiddenCategory_PropertyNotSet() {
		$title = Title::makeTitle( NS_CATEGORY, 'CategoryPage' );
		$title->resetArticleID( 42 );
		$categoryPage = $this->getServiceContainer()->getWikiPageFactory()->newFromTitle( $title );

		$pageProps = $this->createMock( PageProps::class );
		$pageProps->expects( $this->once() )
			->method( 'getProperties' )
			->with( $title, 'hiddencat' )
			->willReturn( [] );

		$this->setService( 'PageProps', $pageProps );

		$this->assertFalse( $categoryPage->isHidden() );
	}

	public static function provideCategoryContent() {
		return [
			[ true ],
			[ false ],
		];
	}

	/**
	 * @dataProvider provideCategoryContent
	 * @covers \WikiCategoryPage::isHidden
	 */
	public function testHiddenCategory_PropertyIsSet( $isHidden ) {
		$categoryPageID = 42;
		$categoryTitle = Title::makeTitle( NS_CATEGORY, 'CategoryPage' );
		$categoryTitle->resetArticleID( $categoryPageID );
		$categoryPage = $this->getServiceContainer()->getWikiPageFactory()->newFromTitle( $categoryTitle );

		$pageProps = $this->createMock( PageProps::class );
		$pageProps->expects( $this->once() )
			->method( 'getProperties' )
			->with( $categoryTitle, 'hiddencat' )
			->willReturn( $isHidden ? [ $categoryPageID => '' ] : [] );

		$this->setService( 'PageProps', $pageProps );

		$this->assertEquals( $isHidden, $categoryPage->isHidden() );
	}

	/**
	 * @covers \WikiCategoryPage::isExpectedUnusedCategory
	 */
	public function testExpectUnusedCategory_PropertyNotSet() {
		$title = Title::makeTitle( NS_CATEGORY, 'CategoryPage' );
		$title->resetArticleID( 42 );
		$categoryPage = $this->getServiceContainer()->getWikiPageFactory()->newFromTitle( $title );

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
	 * @covers \WikiCategoryPage::isExpectedUnusedCategory
	 */
	public function testExpectUnusedCategory_PropertyIsSet( $isExpectedUnusedCategory ) {
		$categoryPageID = 42;
		$categoryTitle = Title::makeTitle( NS_CATEGORY, 'CategoryPage' );
		$categoryTitle->resetArticleID( $categoryPageID );
		$categoryPage = $this->getServiceContainer()->getWikiPageFactory()->newFromTitle( $categoryTitle );
		$returnValue = $isExpectedUnusedCategory ? [ $categoryPageID => '' ] : [];

		$pageProps = $this->createMock( PageProps::class );
		$pageProps->expects( $this->once() )
			->method( 'getProperties' )
			->with( $categoryTitle, 'expectunusedcategory' )
			->willReturn( $returnValue );

		$this->setService( 'PageProps', $pageProps );

		$this->assertEquals( $isExpectedUnusedCategory, $categoryPage->isExpectedUnusedCategory() );
	}
}
