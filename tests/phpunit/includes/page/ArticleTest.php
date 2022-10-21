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

	protected $tablesUsed = [
		'revision',
		'recentchanges',
	];

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
		$testPage = $this->getNonexistingTestPage( $title );
		$user1 = $this->getTestUser( $group )->getUser();
		$user2 = $this->getTestUser()->getUser();
		if ( $editPageText !== null ) {
			$editedUser = $isEditedBySameUser ? $user1 : $user2;
			$editIsGood = $this->editPage( $testPage, $editPageText, '', NS_MAIN, $editedUser )->isGood();
			$this->assertTrue( $editIsGood, 'edited a page' );
		}

		$context = new RequestContext();
		$context->setUser( $user1 );
		$context->setTitle( $title );
		$article = new Article( $title );
		$article->setContext( $context );
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

	/**
	 * Show patrol footer even if the page was moved (T162871).
	 *
	 * @covers Article::showPatrolFooter
	 */
	public function testShowPatrolFooterMovedPage() {
		$oldTitle = Title::makeTitle( NS_USER, 'NewDraft' );
		$newTitle = Title::makeTitle( NS_MAIN, 'NewDraft' );
		$editor = $this->getTestUser()->getUser();

		$editIsGood = $this->editPage( $oldTitle, 'Content', '', NS_USER, $editor )->isGood();
		$this->assertTrue( $editIsGood, 'edited a page' );

		$status = $this->getServiceContainer()
			->getMovePageFactory()
			->newMovePage( $oldTitle, $newTitle )
			->move( $this->getTestUser()->getUser() );
		$this->assertTrue( $status->isOK() );

		$context = new RequestContext();
		$article = new Article( $newTitle );
		$sysop = $this->getTestUser( 'sysop' )->getUser();
		$context->setUser( $sysop );
		$context->setTitle( $newTitle );
		$article->setContext( $context );

		$this->assertTrue( $article->showPatrolFooter() );
	}

}
