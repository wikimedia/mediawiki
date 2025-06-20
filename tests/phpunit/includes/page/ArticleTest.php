<?php

use MediaWiki\Context\RequestContext;
use MediaWiki\MainConfigNames;
use MediaWiki\MainConfigSchema;
use MediaWiki\Message\Message;
use MediaWiki\Page\Article;
use MediaWiki\Page\ParserOutputAccess;
use MediaWiki\Page\WikiPage;
use MediaWiki\Parser\ParserOptions;
use MediaWiki\Parser\ParserOutput;
use MediaWiki\Status\Status;
use MediaWiki\Title\Title;
use MediaWiki\User\User;

/**
 * @group Database
 */
class ArticleTest extends \MediaWikiIntegrationTestCase {

	/**
	 * @param Title $title
	 * @param User|null $user
	 *
	 * @return Article
	 */
	private function newArticle( Title $title, ?User $user = null ): Article {
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
	 * @covers \MediaWiki\Page\Article::__sleep
	 */
	public function testSerialization_fails() {
		$article = new Article( Title::newMainPage() );

		$this->expectException( LogicException::class );
		serialize( $article );
	}

	/**
	 * Tests that missing article page shows parser contents
	 * of the well-known system message for NS_MEDIAWIKI pages
	 *
	 * @covers \MediaWiki\Page\Article::showMissingArticle
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
	 *
	 * @covers \MediaWiki\Page\Article::showPatrolFooter
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
	 * @covers \MediaWiki\Page\Article::showPatrolFooter
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
	 * @covers \MediaWiki\Page\Article::generateContentOutput
	 */
	public function testUsesCachedOutput() {
		$title = $this->getExistingTestPage()->getTitle();

		$parserOutputAccess = $this->createNoOpMock( ParserOutputAccess::class, [ 'getCachedParserOutput' ] );
		$parserOutputAccess->method( 'getCachedParserOutput' )
			->willReturn( new ParserOutput( 'Kittens' ) );

		$this->setService( 'ParserOutputAccess', $parserOutputAccess );

		$article = $this->newArticle( $title );
		$article->view();
		$this->assertStringContainsString( 'Kittens', $article->getContext()->getOutput()->getHTML() );
	}

	/**
	 * Ensure that content that is present in the parser cache will be used.
	 *
	 * @covers \MediaWiki\Page\Article::generateContentOutput
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

		$beforePreWarm = true;
		$parserOutputAccess = $this->createNoOpMock(
			ParserOutputAccess::class,
			[ 'getCachedParserOutput', 'getParserOutput', ]
		);
		$parserOutputAccess->method( 'getCachedParserOutput' )
			->willReturn( null );
		$parserOutputAccess
			->expects( $this->exactly( 2 ) ) // This is the key assertion in this test case.
			->method( 'getParserOutput' )
			->with(
				$this->anything(),
				$this->callback( function ( ParserOptions $parserOptions ) use ( &$beforePreWarm ) {
					$expectedReason = $beforePreWarm ? 'page-view' : 'view';
					$this->assertSame( $expectedReason, $parserOptions->getRenderReason() );
					return true;
				} ),
				$this->anything(),
				$this->callback( function ( $options ) use ( &$beforePreWarm ) {
					if ( $beforePreWarm ) {
						$this->assertTrue( $options[ ParserOutputAccess::OPT_NO_CHECK_CACHE ] ?? false,
							"The cache is not checked again" );
						$this->assertTrue( $options[ ParserOutputAccess::OPT_LINKS_UPDATE ] ?? false,
							"WikiPage::triggerOpportunisticLinksUpdate is attempted" );
					}
					return true;
				} )
			)
			->willReturnCallback( static function ( $page, $parserOptions, $revision, $options ) use ( &$beforePreWarm ) {
				$content = $beforePreWarm ? 'Old Kittens' : 'New Kittens';
				return Status::newGood( new ParserOutput( $content ) );
			} );

		$this->setService( 'ParserOutputAccess', $parserOutputAccess );

		$article = $this->newArticle( $title );
		$article->view();

		$beforePreWarm = false;
		$this->runJobs( [ 'minJobs' => 1, 'maxJobs' => 1 ], [ 'type' => 'parsoidCachePrewarm' ] );

		// This is just a sanity check, not the key assertion.
		$this->assertStringContainsString( 'Old Kittens', $article->getContext()->getOutput()->getHTML() );
	}

	/**
	 * Ensure that protection indicators are shown when the page is protected.
	 * @covers \MediaWiki\Page\Article::showProtectionIndicator
	 */
	public function testShowProtectionIndicator() {
		$this->overrideConfigValue(
			MainConfigNames::EnableProtectionIndicators,
			true
		);
		$title = $this->getExistingTestPage()->getTitle();
		$article = $this->newArticle( $title );

		$wikiPage = new WikiPage( $title );
		$cascade = false;
		$wikiPage->doUpdateRestrictions( [
				'edit' => 'autoconfirmed',
			],
			[ 'edit' => 'infinity' ],
			$cascade,
			'Test reason',
			$this->getTestSysop()->getUser()
		);

		$article->showProtectionIndicator();
		$output = $article->getContext()->getOutput();
		$this->assertArrayHasKey( 'protection-autoconfirmed', $output->getIndicators(), 'Protection indicators are shown when a page is protected' );

		$templateTitle = Title::newFromText( 'CascadeProtectionTest', NS_TEMPLATE );
		$this->editPage( $templateTitle, 'Some text here', 'Test', NS_TEMPLATE, $this->getTestSysop()->getUser() );
		$articleTitle = $this->getExistingTestPage()->getTitle();
		$this->editPage( $articleTitle, '{{CascadeProtectionTest}}', 'Test', NS_MAIN, $this->getTestSysop()->getUser() );
		$wikiPage = new WikiPage( $articleTitle );
		$cascade = true;
		$wikiPage->doUpdateRestrictions( [
				'edit' => 'sysop',
			],
			[ 'edit' => 'infinity' ],
			$cascade,
			'Test reason',
			$this->getTestSysop()->getUser()
		);

		$template = $this->newArticle( $templateTitle );

		$template->showProtectionIndicator();
		$output = $template->getContext()->getOutput();
		$this->assertArrayHasKey(
			'protection-sysop-cascade',
			$output->getIndicators(),
			'Protection indicators are shown when a page protected using cascade protection'
		);
	}
}
