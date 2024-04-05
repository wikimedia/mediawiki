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
	protected function setUp(): void {
		parent::setUp();
		$this->title = Title::makeTitle( NS_MAIN, 'SomePage' );
		$this->article = new Article( $this->title );
	}

	/**
	 * @covers Article::__get
	 * @covers Article::__set
	 */
	public function testGetOrSetOnNewProperty() {
		// Removed in 1.42, skip test on newer PHP
		$this->markTestSkippedIfPhp( '>=', '8.2' );

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

	/**
	 * Test if patrol footer is possible to show
	 * @covers Article::showPatrolFooter
	 * @dataProvider provideShowPatrolFooter
	 */
	public function testShowPatrolFooter( $group, $title, $editPageText, $isEditedBySameUser, $expectedResult ) {
		$context = new RequestContext();
		$article = new Article( $title );
		$user1 = $this->getTestUser( $group )->getUser();
		$user2 = $this->getTestUser()->getUser();
		$context->setUser( $user1 );
		$article->setContext( $context );
		if ( $editPageText !== null ) {
			$editedUser = $isEditedBySameUser ? $user1 : $user2;
			$editIsGood = $this->editPage( $article->getPage(), $editPageText, '', NS_MAIN, $editedUser )->isGood();
			$this->assertTrue( $editIsGood, 'edited a page' );
		}
		$this->assertSame( $expectedResult, $article->showPatrolFooter() );
	}

	public function provideShowPatrolFooter() {
		yield 'UserAllowedRevExist' => [
			'sysop',
			Title::makeTitle( NS_MAIN, 'Page1' ),
			'EditPage1',
			false,
			true
		];

		yield 'UserNotAllowedRevExist' => [
			null,
			Title::makeTitle( NS_MAIN, 'Page2' ),
			'EditPage2',
			false,
			false
		];

		yield 'UserAllowedNoRev' => [
			'sysop',
			Title::makeTitle( NS_MAIN, 'Page3' ),
			null,
			false,
			false
		];

		yield 'UserAllowedRevExistBySameUser' => [
			'sysop',
			Title::makeTitle( NS_MAIN, 'Page4' ),
			'EditPage4',
			true,
			false
		];
	}
}
