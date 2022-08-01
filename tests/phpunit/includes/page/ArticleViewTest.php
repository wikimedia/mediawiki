<?php

use MediaWiki\MainConfigNames;
use MediaWiki\Revision\MutableRevisionRecord;
use MediaWiki\Revision\RevisionRecord;
use MediaWiki\Revision\SlotRecord;
use PHPUnit\Framework\MockObject\MockObject;
use Wikimedia\TestingAccessWrapper;

/**
 * @covers \Article::view()
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
	 * @throws MWException
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

	/**
	 * @covers Article::getOldId()
	 * @covers Article::getRevIdFetched()
	 */
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
	 * @covers Article::getPage
	 * @covers WikiPage::getRedirectTarget
	 * @covers \Mediawiki\Page\RedirectLookup::getRedirectTarget
	 */
	public function testViewRedirect() {
		$target = Title::makeTitle( $this->getDefaultWikitextNS(), 'Test_Target' );
		$redirectText = '#REDIRECT [[' . $target->getPrefixedText() . ']]';

		$page = $this->getPage( __METHOD__, [ $redirectText ] );

		$article = new Article( $page->getTitle(), 0 );
		$article->getContext()->getOutput()->setTitle( $page->getTitle() );
		$article->view();

		$redirectStore = $this->getServiceContainer()->getRedirectStore();

		$this->assertNotNull(
			$redirectStore->getRedirectTarget( $article->getPage() )->getPrefixedDBkey()
		);
		$this->assertSame(
			$target->getPrefixedDBkey(),
			$redirectStore->getRedirectTarget( $article->getPage() )->getPrefixedDBkey()
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
		$content->method( 'copy' )
			->willReturn( $content );

		$rev = new MutableRevisionRecord( $title );
		$rev->setId( $dummyRev->getId() );
		$rev->setPageId( $title->getArticleID() );
		$rev->setUser( $dummyRev->getUser() );
		$rev->setComment( $dummyRev->getComment() );
		$rev->setTimestamp( $dummyRev->getTimestamp() );

		$rev->setContent( SlotRecord::MAIN, $content );

		/** @var MockObject|WikiPage $page */
		$page = $this->getMockBuilder( WikiPage::class )
			->onlyMethods( [ 'getRevisionRecord', 'getLatest' ] )
			->setConstructorArgs( [ $title ] )
			->getMock();

		$page->method( 'getRevisionRecord' )
			->willReturn( $rev );
		$page->method( 'getLatest' )
			->willReturn( $rev->getId() );

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
			MainConfigNames::MainWANCache => 'main',
			MainConfigNames::WANObjectCaches => [
				'main' => [
					'class' => WANObjectCache::class,
					'cacheId' => 'hash',
				],
			],
		] );

		$revisions = [];
		$page = $this->getPage( __METHOD__, [ 1 => 'Test A', 2 => 'Test B' ], $revisions );
		$idA = $revisions[1]->getId();
		$this->setMwGlobals( 'wgTitle', $page->getTitle() );

		// View the revision once (to get it into the cache)
		$article = new Article( $page->getTitle(), $idA );
		$article->view();

		// Reset the output page and view the revision again (from ParserCache)
		$article = new Article( $page->getTitle(), $idA );
		$context = RequestContext::getMain();
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
		$this->assertSame( 1, substr_count( $output->getSubtitle(), 'class="mw-message-box-warning mw-revision mw-message-box"' ) );
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
			'value' => [ RevisionRecord::DELETED_TEXT => 1 ],
			'comment' => "Testing",
		] );

		$article = new Article( $page->getTitle(), $idA );
		$context = new DerivativeContext( $article->getContext() );
		$article->setContext( $context );
		$context->getOutput()->setTitle( $page->getTitle() );
		$context->getRequest()->setVal( 'unhide', 1 );
		$context->setUser( $this->getTestUser( [ 'sysop' ] )->getUser() );
		$article->view();

		$output = $article->getContext()->getOutput();
		$this->assertStringContainsString( 'rev-deleted-text-view', $this->getHtml( $output ) );

		$this->assertStringContainsString( 'Test A', $this->getHtml( $output ) );
		$this->assertStringNotContainsString( 'Test B', $this->getHtml( $output ) );
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

				$articlePage->getContext()->getOutput()->addParserOutput( $outputDone );
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

	/**
	 * @covers \Article::showViewError()
	 */
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

	/**
	 * @covers \Article::showViewError()
	 */
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
		return new RevDelRevisionList(
			RequestContext::getMain(),
			$title,
			[ $revisionId ],
			$services->getDBLoadBalancerFactory(),
			$services->getHookContainer(),
			$services->getHtmlCacheUpdater(),
			$services->getRevisionStore(),
			$services->getMainWANObjectCache()
		);
	}
}
