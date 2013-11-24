<?php

class ArticleTest extends MediaWikiTestCase {

	/**
	 * @var Title
	 */
	private $title;
	/**
	 * @var Article
	 */
	private $article;

	/** creates a title object and its article object */
	protected function setUp() {
		parent::setUp();
		$this->title = Title::makeTitle( NS_MAIN, 'SomePage' );
		$this->article = new Article( $this->title );
	}

	/** cleanup title object and its article object */
	protected function tearDown() {
		parent::tearDown();
		$this->title = null;
		$this->article = null;
	}

	/**
	 * @covers Article::__get
	 */
	public function testImplementsGetMagic() {
		$this->assertEquals( false, $this->article->mLatest, "Article __get magic" );
	}

	/**
	 * @depends testImplementsGetMagic
	 * @covers Article::__set
	 */
	public function testImplementsSetMagic() {
		$this->article->mLatest = 2;
		$this->assertEquals( 2, $this->article->mLatest, "Article __set magic" );
	}

	/**
	 * @depends testImplementsSetMagic
	 * @covers Article::__call
	 */
	public function testImplementsCallMagic() {
		$this->article->mLatest = 33;
		$this->article->mDataLoaded = true;
		$this->assertEquals( 33, $this->article->getLatest(), "Article __call magic" );
	}

	/**
	 * @covers Article::__get
	 * @covers Article::__set
	 */
	public function testGetOrSetOnNewProperty() {
		$this->article->ext_someNewProperty = 12;
		$this->assertEquals( 12, $this->article->ext_someNewProperty,
			"Article get/set magic on new field" );

		$this->article->ext_someNewProperty = -8;
		$this->assertEquals( -8, $this->article->ext_someNewProperty,
			"Article get/set magic on update to new field" );
	}

	/**
	 * Checks for the existence of the backwards compatibility static functions (forwarders to WikiPage class)
	 * @covers Article::selectFields
	 * @covers Article::onArticleCreate
	 * @covers Article::onArticleDelete
	 * @covers Article::onArticleEdit
	 * @covers Article::getAutosummary
	 */
	public function testStaticFunctions() {
		$this->hideDeprecated( 'Article::getAutosummary' );
		$this->hideDeprecated( 'WikiPage::getAutosummary' );
		$this->hideDeprecated( 'CategoryPage::getAutosummary' ); // Inherited from Article

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
}
