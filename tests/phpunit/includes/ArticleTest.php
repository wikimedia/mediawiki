<?php

class ArticleTest extends MediaWikiTestCase {

	private $title; // holds a Title object
	private $article; // holds an article

	/** creates a title object and its article object */
	function setUp() {
		$this->title   = Title::makeTitle( NS_MAIN, 'SomePage' );
		$this->article = new Article( $this->title );

	}

	/** cleanup title object and its article object */
	function tearDown() {
		$this->title   = null;
		$this->article = null;

	}

	function testImplementsGetMagic() {
		$this->assertEquals( false, $this->article->mLatest, "Article __get magic" );
	}

	/**
	 * @depends testImplementsGetMagic
	 */
	function testImplementsSetMagic() {
		$this->article->mLatest = 2;
		$this->assertEquals( 2, $this->article->mLatest, "Article __set magic" );
	}

	/**
	 * @depends testImplementsSetMagic
	 */
	function testImplementsCallMagic() {
		$this->article->mLatest = 33;
		$this->article->mDataLoaded = true;
		$this->assertEquals( 33, $this->article->getLatest(), "Article __call magic" );
	}

	function testGetOrSetOnNewProperty() {
		$this->article->ext_someNewProperty = 12;
		$this->assertEquals( 12, $this->article->ext_someNewProperty,
			"Article get/set magic on new field" );
		
		$this->article->ext_someNewProperty = -8;
		$this->assertEquals( -8, $this->article->ext_someNewProperty,
			"Article get/set magic on update to new field" );
	}

	/**
	 * Checks for the existence of the backwards compatibility static functions (forwarders to WikiPage class)
	 */
	function testStaticFunctions() {
		$this->assertEquals( WikiPage::selectFields(), Article::selectFields(),
			"Article static functions" );
		$this->assertEquals( true, is_callable( "Article::onArticleCreate" ),
			"Article static functions" );
		$this->assertEquals( true, is_callable( "Article::onArticleDelete" ),
			"Article static functions" );
		$this->assertEquals( true, is_callable( "ImagePage::onArticleEdit" ),
			"Article static functions" );
		$this->assertTrue( is_string( CategoryPage::getAutosummary( '', '', 0 ) ),
			"Article static functions" );
	}

	function testWikiPageFactory() {
		$title = Title::makeTitle( NS_FILE, 'Someimage.png' );
		$page = WikiPage::factory( $title );
		$this->assertEquals( 'WikiFilePage', get_class( $page ) );
		
		$title = Title::makeTitle( NS_CATEGORY, 'SomeCategory' );
		$page = WikiPage::factory( $title );
		$this->assertEquals( 'WikiCategoryPage', get_class( $page ) );
		
		$title = Title::makeTitle( NS_MAIN, 'SomePage' );
		$page = WikiPage::factory( $title );
		$this->assertEquals( 'WikiPage', get_class( $page ) );
	}
}
