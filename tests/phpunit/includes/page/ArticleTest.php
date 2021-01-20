<?php

/**
 * @group Database
 */
class ArticleTest extends \MediaWikiIntegrationTestCase {

	/**
	 * @var Title
	 */
	private $title;
	/**
	 * @var Article
	 */
	private $article;

	/** creates a title object and its article object */
	protected function setUp() : void {
		parent::setUp();
		$this->title = Title::makeTitle( NS_MAIN, 'SomePage' );
		$this->article = new Article( $this->title );
	}

	/**
	 * @covers Article::__get
	 */
	public function testImplementsGetMagic() {
		$this->filterDeprecated( '/Accessing Article::\$mLatest/' );
		$this->assertFalse( $this->article->mLatest, "Article __get magic" );
	}

	/**
	 * @depends testImplementsGetMagic
	 * @covers Article::__set
	 */
	public function testImplementsSetMagic() {
		$this->filterDeprecated( '/Accessing Article::\$mLatest/' );
		$this->filterDeprecated( '/Setting Article::\$mLatest/' );
		$this->article->mLatest = 2;
		$this->assertEquals( 2, $this->article->mLatest, "Article __set magic" );
	}

	/**
	 * @covers Article::__get
	 * @covers Article::__set
	 */
	public function testGetOrSetOnNewProperty() {
		$this->filterDeprecated(
			'/Accessing Article::\$ext_someNewProperty/'
		);
		$this->filterDeprecated(
			'/Setting Article::\$ext_someNewProperty/'
		);
		$this->article->ext_someNewProperty = 12;
		$this->assertEquals( 12, $this->article->ext_someNewProperty,
			"Article get/set magic on new field" );
		$this->assertEquals( 12, $this->article->getPage()->ext_someNewProperty,
			"Article get/set magic on new field" );
		$this->article->ext_someNewProperty = -8;
		$this->assertEquals( -8, $this->article->ext_someNewProperty,
			"Article get/set magic on update to new field" );
		$this->assertEquals( -8, $this->article->getPage()->ext_someNewProperty,
			"Article get/set magic on new field" );
	}

	/**
	 * @covers Article::__sleep
	 */
	public function testSerialization_fails() {
		$this->expectException( LogicException::class );
		serialize( $this->article );
	}

	/**
	 * Tests that missing article page shows parser contents
	 * of the well-known system message for NS_MEDIAWIKI pages
	 * @covers Article::showMissingArticle
	 */
	public function testMissingArticleMessage() {
		// Use a well-known system message
		$title = Title::makeTitle( NS_MEDIAWIKI, 'Uploadedimage' );
		$article = new Article( $title, 0 );
		$article->getContext()->getOutput()->setTitle( $title );
		$article->showMissingArticle();
		$output = $article->getContext()->getOutput();
		$this->assertStringContainsString(
			Message::newFromKey( 'uploadedimage' )->parse(),
			$output->getHTML()
		);
	}
}
