<?php

class ArticleTest extends MediaWikiTestCase {

	private $title; // holds a Title object
	private $article; // holds an article

	/** creates a title object and its article object */
	function setUp() {
		$this->title   = Title::makeTitle( NS_MAIN, 'somePage' );
		$this->article = new Article( $this->title );

	}

	/** cleanup title object and its article object */
	function tearDown() {
		$this->title   = null;
		$this->article = null;

	}

	function testImplementsGetMagic() {		
		$this->assertEquals( -1, $this->article->mCounter, "Article __get magic" );
	}

	/**
	 * @depends testImplementsGetMagic
	 */
	function testImplementsSetMagic() {

		$this->article->mCounter = 2;
		$this->assertEquals( 2, $this->article->mCounter, "Article __set magic" );
	}

	/**
	 * @depends testImplementsSetMagic
	 */
	function testImplementsCallMagic() {
		$this->article->mCounter = 33;
		$this->assertEquals( 33, $this->article->getCount(), "Article __call magic" );
	}

	function testGetOrSetOnNewProperty() {
		$this->article->ext_someNewProperty = 12;
		$this->assertEquals( 12, $this->article->ext_someNewProperty,
			"Article get/set magic on new field" );
		
		$this->article->ext_someNewProperty = -8;
		$this->assertEquals( -8, $this->article->ext_someNewProperty,
			"Article get/set magic on update to new field" );
	}

	function testStaticFunctions() {
		$this->assertEquals( WikiPage::selectFields(), Article::selectFields(),
			"Article static functions" );
		$this->assertEquals( null, Article::onArticleCreate( $this->title ),
			"Article static functions" );
		$this->assertEquals( null, Article::onArticleDelete( $this->title ),
			"Article static functions" );
		$this->assertEquals( null, ImagePage::onArticleEdit( $this->title ),
			"Article static functions" );
		$this->assertTrue( is_string( CategoryPage::getAutosummary( '', '', 0 ) ),
			"Article static functions" );
	}

	function testWikiPageFactory() {
		$title = Title::makeTitle( NS_FILE, 'Someimage.png' );
		$page = WikiPageFactory::newFromTitle( $title );
		$this->assertEquals( 'WikiFilePage', get_class( $page ) );
		
		$title = Title::makeTitle( NS_CATEGORY, 'SomeCategory' );
		$page = WikiPageFactory::newFromTitle( $title );
		$this->assertEquals( 'WikiCategoryPage', get_class( $page ) );
		
		$title = Title::makeTitle( NS_MAIN, 'SomePage' );
		$page = WikiPageFactory::newFromTitle( $title );
		$this->assertEquals( 'WikiPage', get_class( $page ) );
	}
}
