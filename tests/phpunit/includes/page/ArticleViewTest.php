<?php

use MediaWiki\CommentStore\CommentStoreComment;
use MediaWiki\Content\Content;
use MediaWiki\Content\ContentHandler;
use MediaWiki\Content\WikitextContent;
use MediaWiki\Context\DerivativeContext;
use MediaWiki\Context\RequestContext;
use MediaWiki\MainConfigNames;
use MediaWiki\Output\OutputPage;
use MediaWiki\Page\Article;
use MediaWiki\Page\WikiPage;
use MediaWiki\Parser\ParserOptions;
use MediaWiki\Parser\ParserOutput;
use MediaWiki\Request\FauxRequest;
use MediaWiki\Revision\MutableRevisionRecord;
use MediaWiki\Revision\RevisionRecord;
use MediaWiki\Revision\SlotRecord;
use MediaWiki\Status\Status;
use MediaWiki\Title\Title;
use MediaWiki\User\User;
use MediaWiki\Utils\MWTimestamp;
use PHPUnit\Framework\MockObject\MockObject;
use Wikimedia\TestingAccessWrapper;

/**
 * @covers \MediaWiki\Page\Article
 * @group Database
 */
class ArticleViewTest extends MediaWikiIntegrationTestCase {

	protected function setUp(): void {
		parent::setUp();

		$this->setUserLang( 'qqx' );
	}

	private function getHtml( OutputPage $output ) {
		return preg_replace( '/<!--.*?-->/s', '', $output->getHTML() );
	}

	/**
	 * @param string|Title $title
	 * @param Content[]|string[] $revisionContents Content of the revisions to create
	 *        (as Content or string).
	 * @param RevisionRecord[] &$revisions will be filled with the RevisionRecord for $content.
	 *
	 * @return WikiPage
	 */
	private function getPage( $title, array $revisionContents = [], array &$revisions = [] ) {
		if ( is_string( $title ) ) {
			$title = Title::makeTitle( $this->getDefaultWikitextNS(), $title );
		}

		$page = $this->getServiceContainer()->getWikiPageFactory()->newFromTitle( $title );

		$user = $this->getTestUser()->getUser();

		// Make sure all revision have different timestamps all the time,
		// to make timestamp asserts below deterministic.
		$time = time() - 86400;
		MWTimestamp::setFakeTime( $time );

		foreach ( $revisionContents as $key => $cont ) {
			if ( is_string( $cont ) ) {
				$cont = new WikitextContent( $cont );
			}

			$rev = $page->newPageUpdater( $user )
				->setContent( SlotRecord::MAIN, $cont )
				->saveRevision( CommentStoreComment::newUnsavedComment( 'Rev ' . $key ) );

			$revisions[ $key ] = $rev;
			MWTimestamp::setFakeTime( ++$time );
		}
		MWTimestamp::setFakeTime( false );

		// Clear content model cache to support tests that mock the revision
		$this->getServiceContainer()->getMainWANObjectCache()->clearProcessCache();

		return $page;
	}

	public function testGetOldId() {
		$revisions = [];
		$page = $this->getPage( __METHOD__, [ 1 => 'Test A', 2 => 'Test B' ], $revisions );

		$idA = $revisions[1]->getId();
		$idB = $revisions[2]->getId();

		// oldid in constructor
		$article = new Article( $page->getTitle(), $idA );
		$this->assertSame( $idA, $article->getOldID() );
		$article->fetchRevisionRecord();
		$this->assertSame( $idA, $article->getRevIdFetched() );

		// oldid 0 in constructor
		$article = new Article( $page->getTitle(), 0 );
		$this->assertSame( 0, $article->getOldID() );
		$article->fetchRevisionRecord();
		$this->assertSame( $idB, $article->getRevIdFetched() );

		// oldid in request
		$article = new Article( $page->getTitle() );
		$context = new RequestContext();
		$context->setRequest( new FauxRequest( [ 'oldid' => $idA ] ) );
		$article->setContext( $context );
		$this->assertSame( $idA, $article->getOldID() );
		$article->fetchRevisionRecord();
		$this->assertSame( $idA, $article->getRevIdFetched() );

		// no oldid
		$article = new Article( $page->getTitle() );
		$context = new RequestContext();
		$context->setRequest( new FauxRequest( [] ) );
		$article->setContext( $context );
		$this->assertSame( 0, $article->getOldID() );
		$article->fetchRevisionRecord();
		$this->assertSame( $idB, $article->getRevIdFetched() );
	}

	public function testView() {
		$page = $this->getPage( __METHOD__, [ 1 => 'Test A', 2 => 'Test B' ] );

		$article = new Article( $page->getTitle(), 0 );
		$article->getContext()->getOutput()->setTitle( $page->getTitle() );
		$article->view();

		$output = $article->getContext()->getOutput();
		$this->assertStringContainsString( 'Test B', $this->getHtml( $output ) );
		$this->assertStringNotContainsString( 'id="mw-revision-info"', $this->getHtml( $output ) );
		$this->assertStringNotContainsString( 'id="mw-revision-nav"', $this->getHtml( $output ) );
	}

	public function testViewCached() {
		$page = $this->getPage( __METHOD__, [ 1 => 'Test A', 2 => 'Test B' ] );

		$po = new ParserOutput( 'Cached Text' );

		$article = new Article( $page->getTitle(), 0 );
		$article->getContext()->getOutput()->setTitle( $page->getTitle() );

		$cache = $this->getServiceContainer()->getParserCache();
		$cache->save( $po, $page, $article->getParserOptions() );

		$article->view();

		$output = $article->getContext()->getOutput();
		$this->assertStringContainsString( 'Cached Text', $this->getHtml( $output ) );
		$this->assertStringNotContainsString( 'Test A', $this->getHtml( $output ) );
		$this->assertStringNotContainsString( 'Test B', $this->getHtml( $output ) );
	}

	/**
	 * @covers \MediaWiki\Page\WikiPage::getRedirectTarget
	 * @covers \MediaWiki\Page\RedirectStore
	 */
	public function testViewRedirect() {
		$target = Title::makeTitle( $this->getDefaultWikitextNS(), 'Test_Target' );
		$redirectText = '#REDIRECT [[' . $target->getPrefixedText() . ']]';

		$page = $this->getPage( __METHOD__, [ $redirectText ] );

		$article = new Article( $page->getTitle(), 0 );
		$article->getContext()->getOutput()->setTitle( $page->getTitle() );
		$article->view();

		$redirectStore = $this->getServiceContainer()->getRedirectStore();
		$titleFormatter = $this->getServiceContainer()->getTitleFormatter();

		$this->assertNotNull(
			$redirectStore->getRedirectTarget( $article->getPage() )
		);
		$this->assertSame(
			$target->getPrefixedDBkey(),
			$titleFormatter->getPrefixedDBkey( $redirectStore->getRedirectTarget( $article->getPage() ) )
		);

		$output = $article->getContext()->getOutput();
		$this->assertStringContainsString( 'class="redirectText"', $this->getHtml( $output ) );
		$this->assertStringContainsString(
			'>' . htmlspecialchars( $target->getPrefixedText() ) . '<',
			$this->getHtml( $output )
		);
	}

	public function testViewNonText() {
		$dummy = $this->getPage( __METHOD__, [ 'Dummy' ] );
		$dummyRev = $dummy->getRevisionRecord();
		$title = $dummy->getTitle();

		/** @var MockObject|ContentHandler $mockHandler */
		$mockHandler = $this->getMockBuilder( ContentHandler::class )
			->onlyMethods(
				[
					'isParserCacheSupported',
					'serializeContent',
					'unserializeContent',
					'makeEmptyContent',
					'getParserOutput',
				]
			)
			->setConstructorArgs( [ 'NotText', [ 'application/frobnitz' ] ] )
			->getMock();

		$mockHandler->method( 'isParserCacheSupported' )
			->willReturn( false );
		$mockHandler->method( 'getParserOutput' )
			->willReturn( new ParserOutput( 'Structured Output' ) );

		$this->setTemporaryHook(
			'ContentHandlerForModelID',
			static function ( $id, &$handler ) use ( $mockHandler ) {
				$handler = $mockHandler;
			}
		);

		/** @var MockObject|Content $content */
		$content = $this->createMock( Content::class );
		$content->method( 'getModel' )
			->willReturn( 'NotText' );
		$content->expects( $this->never() )->method( 'getNativeData' );
		$content->method( 'copy' )->willReturnSelf();

		$rev = new MutableRevisionRecord( $title );
		$rev->setId( $dummyRev->getId() );
		$rev->setPageId( $title->getArticleID() );
		$rev->setUser( $dummyRev->getUser() );
		$rev->setComment( $dummyRev->getComment() );
		$rev->setTimestamp( $dummyRev->getTimestamp() );

		$rev->setContent( SlotRecord::MAIN, $content );

		/** @var MockObject|WikiPage $page */
		$page = $this->getMockBuilder( WikiPage::class )
			->onlyMethods( [ 'getRevisionRecord', 'getLatest', 'getContentHandler' ] )
			->setConstructorArgs( [ $title ] )
			->getMock();

		$page->method( 'getRevisionRecord' )
			->willReturn( $rev );
		$page->method( 'getLatest' )
			->willReturn( $rev->getId() );
		$page->method( 'getContentHandler' )
			->willReturn( $mockHandler );

		$article = Article::newFromWikiPage( $page, RequestContext::getMain() );
		$article->getContext()->getOutput()->setTitle( $page->getTitle() );
		$article->view();

		$output = $article->getContext()->getOutput();
		$this->assertStringContainsString( 'Structured Output', $this->getHtml( $output ) );
		$this->assertStringNotContainsString( 'Dummy', $this->getHtml( $output ) );
	}

	public function testViewOfOldRevision() {
		$revisions = [];
		$page = $this->getPage( __METHOD__, [ 1 => 'Test A', 2 => 'Test B' ], $revisions );
		$idA = $revisions[1]->getId();

		$article = new Article( $page->getTitle(), $idA );
		$article->getContext()->getOutput()->setTitle( $page->getTitle() );
		$article->view();

		$output = $article->getContext()->getOutput();
		$this->assertStringContainsString( 'Test A', $this->getHtml( $output ) );
		$this->assertStringContainsString( 'id="mw-revision-info"', $output->getSubtitle() );
		$this->assertStringContainsString( 'id="mw-revision-nav"', $output->getSubtitle() );

		$this->assertStringNotContainsString( 'id="revision-info-current"', $output->getSubtitle() );
		$this->assertStringNotContainsString( 'Test B', $this->getHtml( $output ) );
		$this->assertSame( $idA, $output->getRevisionId() );
		$this->assertSame( $revisions[1]->getTimestamp(), $output->getRevisionTimestamp() );
	}

	public function testViewOfOldRevisionFromCache() {
		$this->overrideConfigValues( [
			MainConfigNames::OldRevisionParserCacheExpireTime => 100500,
			MainConfigNames::MainCacheType => CACHE_HASH,
		] );

		$revisions = [];
		$page = $this->getPage( __METHOD__, [ 1 => 'Test A', 2 => 'Test B' ], $revisions );
		$idA = $revisions[1]->getId();
		$context = RequestContext::getMain();
		$context->setTitle( $page->getTitle() );

		// View the revision once (to get it into the cache)
		$article = new Article( $page->getTitle(), $idA );
		$article->view();

		// Reset the output page and view the revision again (from ParserCache)
		$article = new Article( $page->getTitle(), $idA );
		$context->setOutput( new OutputPage( $context ) );
		$article->setContext( $context );

		$outputPageBeforeHTMLRevisionId = null;
		$this->setTemporaryHook( 'OutputPageBeforeHTML',
			static function ( OutputPage $out ) use ( &$outputPageBeforeHTMLRevisionId ) {
				$outputPageBeforeHTMLRevisionId = $out->getRevisionId();
			}
		);

		$article->view();
		$output = $article->getContext()->getOutput();
		$this->assertStringContainsString( 'Test A', $this->getHtml( $output ) );
		$this->assertSame( 1, substr_count( $output->getSubtitle(), 'cdx-message--warning' ) );
		$this->assertSame( $idA, $output->getRevisionId() );
		$this->assertSame( $idA, $outputPageBeforeHTMLRevisionId );
		$this->assertSame( $revisions[1]->getTimestamp(), $output->getRevisionTimestamp() );
	}

	public function testViewOfCurrentRevision() {
		$revisions = [];
		$page = $this->getPage( __METHOD__, [ 1 => 'Test A', 2 => 'Test B' ], $revisions );
		$idB = $revisions[2]->getId();

		$article = new Article( $page->getTitle(), $idB );
		$article->getContext()->getOutput()->setTitle( $page->getTitle() );
		$article->view();

		$output = $article->getContext()->getOutput();
		$this->assertStringContainsString( 'Test B', $this->getHtml( $output ) );
		$this->assertStringContainsString( 'id="mw-revision-info-current"', $output->getSubtitle() );
		$this->assertStringContainsString( 'id="mw-revision-nav"', $output->getSubtitle() );
	}

	/**
	 * Make sure that when we fallback to known-stale content from ParserCache
	 * (by page ID), that any other metadata and interface text on the page
	 * refers to the same almost-latest revision ID, and not the "latest" rev ID
	 * from the replica DB that we tried first.
	 *
	 * @covers MediaWiki\Output\OutputPage
	 * @see T339164
	 * @see T341013
	 */
	public function testViewOfCurrentRevisionDirty() {
		// Simulate full PoolCounter queue, to trigger stale fallback in ParserOutputAccess
		$this->overrideConfigValue(
			MainConfigNames::PoolCounterConf,
			[
				'ArticleView' => [
					'class' => MockPoolCounterFailing::class,
				]
			]
		);

		// Revision A, warm up ParserCache.
		$revisions = [];
		$page = $this->getPage( __METHOD__, [ 1 => 'Test A' ], $revisions );
		$idA = $revisions[1]->getId();

		// Revision B, without ParserCache, to simulate a race with a stale cache
		$parserCacheFactory = $this->getServiceContainer()->getParserCacheFactory();
		$this->overrideConfigValue( MainConfigNames::ParserCacheType, CACHE_NONE );
		$latestEditStatus = $this->editPage( $page, 'Test B' );
		// Restore the previous cache with the now-stale cache entry
		$this->setService( 'ParserCacheFactory', $parserCacheFactory );

		// View the article, calling ParserOutputAccess
		$article = new Article( $page->getTitle() );
		$article->getContext()->getOutput()->setTitle( $page->getTitle() );
		$article->view();

		// Expect previous HTML, with consistent previous-revision metadata
		$output = $article->getContext()->getOutput();
		$this->assertStringContainsString( 'Test A', $this->getHtml( $output ) );
		$this->assertSame( $idA, $output->getRevisionId() );
		$this->assertSame( $revisions[1]->getTimestamp(), $output->getRevisionTimestamp() );
	}

	public function testViewOfMissingRevision() {
		$revisions = [];
		$page = $this->getPage( __METHOD__, [ 1 => 'Test A' ], $revisions );
		$badId = $revisions[1]->getId() + 100;

		$article = new Article( $page->getTitle(), $badId );
		$article->getContext()->getOutput()->setTitle( $page->getTitle() );
		$article->view();

		$output = $article->getContext()->getOutput();
		$this->assertStringContainsString( 'missing-revision: ' . $badId, $this->getHtml( $output ) );

		$this->assertStringNotContainsString( 'Test A', $this->getHtml( $output ) );
	}

	public function testViewOfDeletedRevision() {
		$revisions = [];
		$page = $this->getPage( __METHOD__, [ 1 => 'Test A', 2 => 'Test B' ], $revisions );
		$idA = $revisions[1]->getId();

		$revDelList = $this->getRevDelRevisionList( $page->getTitle(), $idA );
		$revDelList->setVisibility( [
			'value' => [ RevisionRecord::DELETED_TEXT => 1 ],
			'comment' => "Testing",
		] );

		$article = new Article( $page->getTitle(), $idA );
		$article->getContext()->getOutput()->setTitle( $page->getTitle() );
		$article->view();

		$output = $article->getContext()->getOutput();
		$this->assertStringContainsString( 'rev-deleted-text-permission', $this->getHtml( $output ) );

		$this->assertStringNotContainsString( 'Test A', $this->getHtml( $output ) );
		$this->assertStringNotContainsString( 'Test B', $this->getHtml( $output ) );
	}

	public function testUnhiddenViewOfDeletedRevision() {
		$revisions = [];
		$page = $this->getPage( __METHOD__, [ 1 => 'Test A', 2 => 'Test B' ], $revisions );
		$idA = $revisions[1]->getId();

		$revDelList = $this->getRevDelRevisionList( $page->getTitle(), $idA );
		$revDelList->setVisibility( [
			'value' => [
				RevisionRecord::DELETED_TEXT => 1,
				RevisionRecord::DELETED_COMMENT => 1,
				RevisionRecord::DELETED_USER => 1,
			],
			'comment' => "Testing",
		] );

		$realContext = RequestContext::getMain();
		$oldUser = $realContext->getUser();
		$oldLanguage = $realContext->getLanguage();

		$article = new Article( $page->getTitle(), $idA );
		$context = new DerivativeContext( $realContext );
		$article->setContext( $context );
		$context->getOutput()->setTitle( $page->getTitle() );
		$context->getRequest()->setVal( 'unhide', 1 );
		$context->setUser( $this->getTestUser( [ 'sysop' ] )->getUser() );

		// Need global user set to sysop, global state in Linker::revUserTools/Linker::revComment (T309479)
		$realContext->setUser( $context->getUser() );
		// Language is resetted in setUser
		$this->setUserLang( $oldLanguage );

		$article->view();

		$output = $article->getContext()->getOutput();
		$subtitle = $output->getSubtitle();
		$html = $this->getHtml( $output );

		// Test that oldid is select, not the current version
		$this->assertStringNotContainsString( 'Test B', $html );

		// Warning about rev-del must exists
		$this->assertStringContainsString( 'rev-deleted-text-view', $html );

		// Test for the hidden values
		$this->assertStringContainsString( 'Test A', $html );
		$this->assertStringContainsString( $revisions[1]->getUser()->getName(), $subtitle );
		$this->assertStringContainsString( '(parentheses: Rev 1)', $subtitle );

		// Should not contain the rev-del messages
		$this->assertStringNotContainsString( '(rev-deleted-user)', $subtitle );
		$this->assertStringNotContainsString( '(rev-deleted-comment)', $subtitle );

		$realContext->setUser( $oldUser );
	}

	public function testHiddenViewOfDeletedRevision() {
		$revisions = [];
		$page = $this->getPage( __METHOD__, [ 1 => 'Test A', 2 => 'Test B' ], $revisions );
		$idA = $revisions[1]->getId();

		$revDelList = $this->getRevDelRevisionList( $page->getTitle(), $idA );
		$revDelList->setVisibility( [
			'value' => [
				RevisionRecord::DELETED_TEXT => 1,
				RevisionRecord::DELETED_COMMENT => 1,
				RevisionRecord::DELETED_USER => 1,
			],
			'comment' => "Testing",
		] );

		$realContext = RequestContext::getMain();
		$oldUser = $realContext->getUser();
		$oldLanguage = $realContext->getLanguage();

		$article = new Article( $page->getTitle(), $idA );
		$context = new DerivativeContext( $realContext );
		$article->setContext( $context );
		$context->getOutput()->setTitle( $page->getTitle() );
		// No unhide=1 is set in this test case
		$context->setUser( $this->getTestUser( [ 'sysop' ] )->getUser() );

		// Need global user set to sysop, global state in Linker::revUserTools/Linker::revComment (T309479)
		$realContext->setUser( $context->getUser() );
		// Language is resetted in setUser
		$this->setUserLang( $oldLanguage );

		$article->view();

		$output = $article->getContext()->getOutput();
		$subtitle = $output->getSubtitle();
		$html = $this->getHtml( $output );

		// Test that oldid is select, not the current version
		$this->assertStringNotContainsString( 'Test B', $html );

		// Warning about rev-del must exists
		$this->assertStringContainsString( 'rev-deleted-text-unhide', $html );

		// Test for the rev-del messages
		$this->assertStringContainsString( '(rev-deleted-user)', $subtitle );
		$this->assertStringContainsString( '(rev-deleted-comment)', $subtitle );

		// Should not contain the hidden values
		$this->assertStringNotContainsString( 'Test A', $html );
		$this->assertStringNotContainsString( $revisions[1]->getUser()->getName(), $subtitle );
		$this->assertStringNotContainsString( '(parentheses: Rev 1)', $subtitle );

		$realContext->setUser( $oldUser );
	}

	public function testViewMissingPage() {
		$page = $this->getPage( __METHOD__ );

		$article = new Article( $page->getTitle() );
		$article->getContext()->getOutput()->setTitle( $page->getTitle() );
		$article->view();

		$output = $article->getContext()->getOutput();
		$this->assertStringContainsString( '(noarticletextanon)', $this->getHtml( $output ) );
	}

	public function testViewDeletedPage() {
		$page = $this->getPage( __METHOD__, [ 1 => 'Test A', 2 => 'Test B' ] );
		$this->deletePage( $page );

		$article = new Article( $page->getTitle() );
		$article->getContext()->getOutput()->setTitle( $page->getTitle() );
		$article->view();

		$output = $article->getContext()->getOutput();
		$this->assertStringContainsString( 'moveddeleted', $this->getHtml( $output ) );
		$this->assertStringContainsString( 'logentry-delete-delete', $this->getHtml( $output ) );
		$this->assertStringContainsString( '(noarticletextanon)', $this->getHtml( $output ) );

		$this->assertStringNotContainsString( 'Test A', $this->getHtml( $output ) );
		$this->assertStringNotContainsString( 'Test B', $this->getHtml( $output ) );
	}

	public function testViewMessagePage() {
		$title = Title::makeTitle( NS_MEDIAWIKI, 'Mainpage' );
		$page = $this->getPage( $title );

		$article = new Article( $page->getTitle() );
		$article->getContext()->getOutput()->setTitle( $page->getTitle() );
		$article->view();

		$output = $article->getContext()->getOutput();
		$this->assertStringContainsString(
			wfMessage( 'mainpage' )->inContentLanguage()->parse(),
			$this->getHtml( $output )
		);
		$this->assertStringNotContainsString( '(noarticletextanon)', $this->getHtml( $output ) );
	}

	public function testViewMissingUserPage() {
		$user = $this->getTestUser()->getUser();
		$user->addToDatabase();

		$title = Title::makeTitle( NS_USER, $user->getName() );

		$page = $this->getPage( $title );

		$article = new Article( $page->getTitle() );
		$article->getContext()->getOutput()->setTitle( $page->getTitle() );
		$article->view();

		$output = $article->getContext()->getOutput();
		$this->assertStringContainsString( '(noarticletextanon)', $this->getHtml( $output ) );
		$this->assertStringNotContainsString(
			'(userpage-userdoesnotexist-view)',
			$this->getHtml( $output )
		);
	}

	public function testViewUserPageOfNonexistingUser() {
		$user = User::newFromName( 'Testing ' . __METHOD__ );

		$title = Title::makeTitle( NS_USER, $user->getName() );

		$page = $this->getPage( $title );

		$article = new Article( $page->getTitle() );
		$article->getContext()->getOutput()->setTitle( $page->getTitle() );
		$article->view();

		$output = $article->getContext()->getOutput();
		$this->assertStringContainsString( '(noarticletextanon)', $this->getHtml( $output ) );
		$this->assertStringContainsString(
			'(userpage-userdoesnotexist-view:',
			$this->getHtml( $output )
		);
	}

	public function testArticleViewHeaderHook() {
		$page = $this->getPage( __METHOD__, [ 1 => 'Test A' ] );

		$article = new Article( $page->getTitle(), 0 );
		$article->getContext()->getOutput()->setTitle( $page->getTitle() );

		$this->setTemporaryHook(
			'ArticleViewHeader',
			function ( Article $articlePage, &$outputDone, &$useParserCache ) use ( $article ) {
				$this->assertSame( $article, $articlePage, '$articlePage' );

				$outputDone = new ParserOutput( 'Hook Text' );
				$outputDone->setTitleText( 'Hook Title' );

				$context = $articlePage->getContext();
				$parserOptions = ParserOptions::newFromContext( $context );
				$context->getOutput()->addParserOutput( $outputDone, $parserOptions );
			}
		);

		$article->view();

		$output = $article->getContext()->getOutput();
		$this->assertStringNotContainsString( 'Test A', $this->getHtml( $output ) );
		$this->assertStringContainsString( 'Hook Text', $this->getHtml( $output ) );
		$this->assertSame( 'Hook Title', $output->getPageTitle() );
	}

	public function testArticleRevisionViewCustomHook() {
		$page = $this->getPage( __METHOD__, [ 1 => 'Test A' ] );

		$article = new Article( $page->getTitle(), 0 );
		$article->getContext()->getOutput()->setTitle( $page->getTitle() );

		// use ArticleViewHeader hook to bypass the parser cache
		$this->setTemporaryHook(
			'ArticleViewHeader',
			static function ( Article $articlePage, &$outputDone, &$useParserCache ) {
				$useParserCache = false;
			}
		);

		$this->setTemporaryHook(
			'ArticleRevisionViewCustom',
			function ( RevisionRecord $rev, Title $title, $oldid, OutputPage $output ) use ( $page ) {
				$content = $rev->getContent( SlotRecord::MAIN );
				$this->assertSame( $page->getTitle(), $title, '$title' );
				$this->assertSame( 'Test A', $content->getText(), '$content' );

				$output->addHTML( 'Hook Text' );
				return false;
			}
		);

		$article->view();

		$output = $article->getContext()->getOutput();
		$this->assertStringNotContainsString( 'Test A', $this->getHtml( $output ) );
		$this->assertStringContainsString( 'Hook Text', $this->getHtml( $output ) );
	}

	public function testShowMissingArticleHook() {
		$page = $this->getPage( __METHOD__ );

		$article = new Article( $page->getTitle() );
		$article->getContext()->getOutput()->setTitle( $page->getTitle() );

		$this->setTemporaryHook(
			'ShowMissingArticle',
			function ( Article $articlePage ) use ( $article ) {
				$this->assertSame( $article, $articlePage, '$articlePage' );

				$articlePage->getContext()->getOutput()->addHTML( 'Hook Text' );
			}
		);

		$article->view();

		$output = $article->getContext()->getOutput();
		$this->assertStringContainsString( '(noarticletextanon)', $this->getHtml( $output ) );
		$this->assertStringContainsString( 'Hook Text', $this->getHtml( $output ) );
	}

	public function testViewLatestError() {
		$page = $this->getPage( __METHOD__, [ 1 => 'Test A' ] );

		$article = new Article( $page->getTitle(), 0 );
		$output = $article->getContext()->getOutput();
		$output->setTitle( $page->getTitle() );

		// use ArticleViewHeader hook to bypass the parser cache
		$this->setTemporaryHook(
			'ArticleViewHeader',
			static function ( Article $articlePage, &$outputDone, &$useParserCache ) {
				$useParserCache = false;
			}
		);

		$article = TestingAccessWrapper::newFromObject( $article );
		$article->fetchResult = Status::newFatal(
			'rev-deleted-text-permission',
			$page->getTitle()->getPrefixedDBkey()
		);

		$article->view();

		$this->assertStringContainsString(
			'rev-deleted-text-permission: ArticleViewTest::testViewLatestError',
			$this->getHtml( $output )
		);
	}

	public function testViewOldError() {
		$revisions = [];
		$page = $this->getPage( __METHOD__, [ 1 => 'Test A', 2 => 'Test B' ], $revisions );
		$idA = $revisions[1]->getId();

		$article = new Article( $page->getTitle(), $idA );
		$output = $article->getContext()->getOutput();
		$output->setTitle( $page->getTitle() );

		$article = TestingAccessWrapper::newFromObject( $article );
		$article->fetchResult = Status::newFatal(
			'rev-deleted-text-permission',
			$page->getTitle()->getPrefixedDBkey()
		);

		$article->view();

		$this->assertStringContainsString(
			'rev-deleted-text-permission: ArticleViewTest::testViewOldError',
			$this->getHtml( $output )
		);
	}

	private function getRevDelRevisionList( $title, $revisionId ) {
		$services = $this->getServiceContainer();
		$context = new DerivativeContext( RequestContext::getMain() );
		$context->setUser(
			$this->getTestUser( [ 'sysop' ] )->getUser()
		);
		return new RevDelRevisionList(
			$context,
			$title,
			[ $revisionId ],
			$services->getConnectionProvider(),
			$services->getHookContainer(),
			$services->getHtmlCacheUpdater(),
			$services->getRevisionStore()
		);
	}

	/**
	 * Test the "useParsoid" parser option and the ArticleParserOptions
	 * hook.
	 */
	public function testUseParsoid() {
		// Create an appropriate test page.
		$title = Title::makeTitle( NS_MAIN, 'UseParsoidTest' );
		$article = new Article( $title );
		$page = $this->getExistingTestPage( $title );
		$page->doUserEditContent(
			ContentHandler::makeContent(
				'[[Foo]]',
				$title,
				// Force this page to be wikitext
				CONTENT_MODEL_WIKITEXT
			),
			$this->getTestSysop()->getUser(),
			'TestUseParsoid Summary',
			EDIT_SUPPRESS_RC
		);
		$article->view();
		$html = $this->getHtml( $article->getContext()->getOutput() );
		// Confirm that this is NOT parsoid-generated HTML
		$this->assertStringNotContainsString(
			'rel="mw:WikiLink"',
			$html
		);

		// Now enable Parsoid via the ArticleParserOptions hook
		$article = new Article( $title );
		$this->setTemporaryHook( 'ArticleParserOptions', static function ( $article, $popts ) {
			$popts->setUseParsoid();
		} );
		$article->view();
		$html = $this->getHtml( $article->getContext()->getOutput() );
		// Look for a marker that this is Parsoid-generated HTML
		$this->assertStringContainsString(
			'rel="mw:WikiLink"',
			$html
		);
	}
}
