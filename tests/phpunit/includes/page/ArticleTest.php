<?php

use MediaWiki\MainConfigNames;
use MediaWiki\MainConfigSchema;
use MediaWiki\Page\ParserOutputAccess;
use MediaWiki\Parser\Parsoid\ParsoidOutputAccess;
use MediaWiki\Status\Status;
use MediaWiki\Title\Title;
use MediaWiki\User\User;

/**
 * @group Database
 */
class ArticleTest extends \MediaWikiIntegrationTestCase {

	protected $tablesUsed = [
		'revision',
		'recentchanges',
	];

	/**
	 * @param Title $title
	 * @param User|null $user
	 *
	 * @return Article
	 */
	private function newArticle( Title $title, User $user = null ): Article {
		if ( !$user ) {
			$user = $this->getTestUser()->getUser();
		}

		$context = new RequestContext();
		$article = new Article( $title );
		$context->setUser( $user );
		$context->setTitle( $title );
		$article->setContext( $context );

		return $article;
	}

	/**
	 * @covers Article::__get
	 * @covers Article::__set
	 */
	public function testGetOrSetOnNewProperty() {
		$article = new Article( Title::newMainPage() );

		$this->filterDeprecated(
			'/Accessing Article::\$ext_someNewProperty/'
		);
		$this->filterDeprecated(
			'/Setting Article::\$ext_someNewProperty/'
		);
		$article->ext_someNewProperty = 12;
		$this->assertEquals( 12, $article->ext_someNewProperty,
			"Article get/set magic on new field" );
		$this->assertEquals( 12, $article->getPage()->ext_someNewProperty,
			"Article get/set magic on new field" );
		$article->ext_someNewProperty = -8;
		$this->assertEquals( -8, $article->ext_someNewProperty,
			"Article get/set magic on update to new field" );
		$this->assertEquals( -8, $article->getPage()->ext_someNewProperty,
			"Article get/set magic on new field" );
	}

	/**
	 * @covers Article::__sleep
	 */
	public function testSerialization_fails() {
		$article = new Article( Title::newMainPage() );

		$this->expectException( LogicException::class );
		serialize( $article );
	}

	/**
	 * Tests that missing article page shows parser contents
	 * of the well-known system message for NS_MEDIAWIKI pages
	 * @covers Article::showMissingArticle
	 */
	public function testMissingArticleMessage() {
		// Use a well-known system message
		$title = Title::makeTitle( NS_MEDIAWIKI, 'Uploadedimage' );
		$article = $this->newArticle( $title );

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

		$article = $this->newArticle( $title, $user1 );
		$this->assertSame( $expectedResult, $article->showPatrolFooter() );
	}

	public static function provideShowPatrolFooter() {
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

		$sysop = $this->getTestUser( 'sysop' )->getUser();
		$article = $this->newArticle( $newTitle, $sysop );

		$this->assertTrue( $article->showPatrolFooter() );
	}

	/**
	 * Ensure that content that is present in the parser cache will be used.
	 *
	 * @covers Article::generateContentOutput
	 */
	public function testUsesCachedOutput() {
		$title = $this->getExistingTestPage()->getTitle();

		$parserOutputAccess = $this->createNoOpMock( ParserOutputAccess::class, [ 'getCachedParserOutput' ] );
		$parserOutputAccess->method( 'getCachedParserOutput' )
			->willReturn( new ParserOutput( 'Kittens' ) );

		$parsoidOutputAccess = $this->createNoOpMock( ParsoidOutputAccess::class );

		$this->setService( 'ParserOutputAccess', $parserOutputAccess );
		$this->setService( 'ParsoidOutputAccess', $parsoidOutputAccess );

		$article = $this->newArticle( $title );
		$article->view();
		$this->assertStringContainsString( 'Kittens', $article->getContext()->getOutput()->getHTML() );
	}

	/**
	 * Ensure that content that is present in the parser cache will be used.
	 *
	 * @covers Article::generateContentOutput
	 */
	public function testOutputIsCached() {
		$this->overrideConfigValue(
			MainConfigNames::ParsoidCacheConfig,
			[ 'WarmParsoidParserCache' => true ]
			+ MainConfigSchema::getDefaultValue( MainConfigNames::ParsoidCacheConfig )
		);
		$title = $this->getExistingTestPage()->getTitle();
		// Run any jobs enqueued by the creation of the test page
		$this->runJobs( [ 'minJobs' => 0 ] );

		$parserOutputAccess = $this->createNoOpMock(
			ParserOutputAccess::class,
			[ 'getCachedParserOutput', 'getParserOutput', ]
		);
		$parserOutputAccess->method( 'getCachedParserOutput' )
			->willReturn( null );
		$parserOutputAccess
			->expects( $this->once() ) // This is the key assertion in this test case.
			->method( 'getParserOutput' )
			->with(
				$this->anything(),
				$this->callback( function ( ParserOptions $parserOptions ) {
					$this->assertSame( 'page-view', $parserOptions->getRenderReason() );
					return true;
				} ),
				$this->anything(),
				$this->callback( function ( $options ) {
					$this->assertTrue( (bool)( $options & ParserOutputAccess::OPT_NO_CHECK_CACHE ),
						"The cache is not checked again" );
					$this->assertTrue( (bool)( $options & ParserOutputAccess::OPT_LINKS_UPDATE ),
						"WikiPage::triggerOpportunisticLinksUpdate is attempted" );
					return true;
				} )
			)
			->willReturn( Status::newGood( new ParserOutput( 'Old Kittens' ) ) );

		$parsoidOutputAccess = $this->createNoOpMock(
			ParsoidOutputAccess::class,
			[ 'getParserOutput', 'supportsContentModel' ]
		);
		$parsoidOutputAccess->method( 'supportsContentModel' )
			->willReturn( true );
		$parsoidOutputAccess
			->expects( $this->once() ) // This is the key assertion in this test case.
			->method( 'getParserOutput' )
			->willReturn( Status::newGood( new ParserOutput( 'New Kittens' ) ) );

		$this->setService( 'ParserOutputAccess', $parserOutputAccess );
		$this->setService( 'ParsoidOutputAccess', $parsoidOutputAccess );

		$article = $this->newArticle( $title );
		$article->view();

		$this->runJobs( [ 'minJobs' => 1, 'maxJobs' => 1 ], [ 'type' => 'parsoidCachePrewarm' ] );

		// This is just a sanity check, not the key assertion.
		$this->assertStringContainsString( 'Old Kittens', $article->getContext()->getOutput()->getHTML() );
	}

}
