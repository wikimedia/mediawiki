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
		MediaWiki\suppressWarnings();
		$this->article->getPage()->mLatest = 2;
		$this->assertSame( 2, $this->article->mLatest, "Article __get magic" );
		MediaWiki\suppressWarnings( 'end' );
	}

	/**
	 * @depends testImplementsGetMagic
	 * @covers Article::__set
	 */
	public function testImplementsSetMagic() {
		MediaWiki\suppressWarnings();
		$this->article->mLatest = 2;
		$this->assertSame( 2, $this->article->getPage()->mLatest, "Article __set magic" );
		MediaWiki\suppressWarnings( 'end' );
	}

	/**
	 * @covers Article::__get
	 * @covers Article::__set
	 */
	public function testGetOrSetOnNewProperty() {
		MediaWiki\suppressWarnings();

		$this->article->ext_someNewProperty = 12;
		$this->assertEquals( 12, $this->article->ext_someNewProperty,
			"Article get/set magic on new field" );

		$this->article->ext_someNewProperty = -8;
		$this->assertEquals( -8, $this->article->ext_someNewProperty,
			"Article get/set magic on update to new field" );

		MediaWiki\suppressWarnings( 'end' );
	}
}
