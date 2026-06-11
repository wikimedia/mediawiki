<?php

namespace MediaWiki\Tests\Actions;

use MediaWiki\Actions\ActionEntryPoint;
use MediaWiki\Context\RequestContext;
use MediaWiki\Deferred\DeferredUpdates;
use MediaWiki\Deferred\DeferredUpdatesScopeMediaWikiStack;
use MediaWiki\Deferred\DeferredUpdatesScopeStack;
use MediaWiki\Exception\BadTitleError;
use MediaWiki\MainConfigNames;
use MediaWiki\Page\WikiPage;
use MediaWiki\Request\FauxRequest;
use MediaWiki\Request\FauxResponse;
use MediaWiki\Request\WebRequest;
use MediaWiki\Skin\Skin;
use MediaWiki\SpecialPage\SpecialPage;
use MediaWiki\Tests\MockEnvironment;
use MediaWiki\Tests\Unit\DummyServicesTrait;
use MediaWiki\Tests\Unit\Permissions\MockAuthorityTrait;
use MediaWiki\Tests\User\TempUser\TempUserTestTrait;
use MediaWiki\Title\MalformedTitleException;
use MediaWiki\Title\Title;
use MediaWiki\User\Options\StaticUserOptionsLookup;
use MediaWiki\User\User;
use MediaWikiIntegrationTestCase;
use PHPUnit\Framework\Assert;
use Wikimedia\TestingAccessWrapper;

// phpcs:disable MediaWiki.Usage.SuperGlobalsUsage.SuperGlobals

/**
 * @group Database
 * @covers \MediaWiki\Actions\ActionEntryPoint
 */
class ActionEntryPointTest extends MediaWikiIntegrationTestCase {
	use TempUserTestTrait;
	use DummyServicesTrait;
	use MockAuthorityTrait;

	protected function setUp(): void {
		parent::setUp();

		$this->overrideConfigValues( [
			MainConfigNames::Server => 'http://example.org',
			MainConfigNames::ScriptPath => '/w',
			MainConfigNames::Script => '/w/index.php',
			MainConfigNames::LanguageCode => 'en',
		] );

		// Needed to test redirects to My* special pages as an anonymous user.
		$this->disableAutoCreateTempUser();
	}

	protected function tearDown(): void {
		// Restore a scope stack that will run updates immediately
		DeferredUpdates::setScopeStack( new DeferredUpdatesScopeMediaWikiStack() );
		parent::tearDown();
	}

	/**
	 * @param MockEnvironment|WebRequest|array|null $environment
	 * @param RequestContext|null $context
	 *
	 * @return ActionEntryPoint
	 */
	private function getEntryPoint( $environment = null, ?RequestContext $context = null ) {
		if ( !$environment ) {
			$environment = new MockEnvironment();
		}

		if ( is_array( $environment ) ) {
			$environment = new FauxRequest( $environment );
		}

		if ( $environment instanceof WebRequest ) {
			$environment = new MockEnvironment( $environment );
		}

		$entryPoint = new ActionEntryPoint(
			$context ?? $environment->makeFauxContext(),
			$environment,
			$this->getServiceContainer()
		);
		$entryPoint->enableOutputCapture();

		return $entryPoint;
	}

	public function testInterwikiLookup() {
		$goToInterwiki = SpecialPage::getTitleFor( 'GoToInterwiki', 'nonlocal:abc' );
		$expectations = [
			'local:abc' => 'https://local.invalid/abc',
			'nonlocal:abc' => $goToInterwiki->getFullURL()
		];

		$this->setService( 'InterwikiLookup', $this->getDummyInterwikiLookup( [
			[ 'iw_prefix' => 'local', 'iw_url' => 'https://local.invalid/$1', 'iw_local' => 1 ],
			[ 'iw_prefix' => 'nonlocal', 'iw_url' => 'https://nonlocal.invalid/$1' ]
		] ) );

		foreach ( $expectations as $title => $url ) {

			$title = Title::newFromText( $title );
			$req = new FauxRequest( [
				'title' => $title->getPrefixedDbKey(),
			] );
			$req->setRequestURL( $title->getLinkURL() );

			$env = new MockEnvironment( $req );
			$context = $env->makeFauxContext();
			$context->setTitle( $title );

			$mw = TestingAccessWrapper::newFromObject( $this->getEntryPoint( $env, $context ) );

			$mw->performRequest();
			$this->assertEquals( $url, $context->getOutput()->getRedirect() );
		}
	}

	public static function provideTryNormaliseRedirect() {
		return [
			[
				// View: Canonical
				'url' => 'http://example.org/wiki/Foo_Bar',
				'query' => [],
				'title' => 'Foo_Bar',
				'redirect' => false,
			],
			[
				// View: Escaped title
				'url' => 'http://example.org/wiki/Foo%20Bar',
				'query' => [],
				'title' => 'Foo_Bar',
				'redirect' => 'http://example.org/wiki/Foo_Bar',
			],
			[
				// View: Script path
				'url' => 'http://example.org/w/index.php?title=Foo_Bar',
				'query' => [ 'title' => 'Foo_Bar' ],
				'title' => 'Foo_Bar',
				'redirect' => false,
			],
			[
				// View: Script path with implicit title from page id
				'url' => 'http://example.org/w/index.php?curid=123',
				'query' => [ 'curid' => '123' ],
				'title' => 'Foo_Bar',
				'redirect' => false,
			],
			[
				// View: Script path with implicit title from revision id
				'url' => 'http://example.org/w/index.php?oldid=123',
				'query' => [ 'oldid' => '123' ],
				'title' => 'Foo_Bar',
				'redirect' => false,
			],
			[
				// View: Script path without title
				'url' => 'http://example.org/w/index.php',
				'query' => [],
				'title' => 'Main_Page',
				'redirect' => 'http://example.org/wiki/Main_Page',
			],
			[
				// View: Script path with empty title
				'url' => 'http://example.org/w/index.php?title=',
				'query' => [ 'title' => '' ],
				'title' => 'Main_Page',
				'redirect' => 'http://example.org/wiki/Main_Page',
			],
			[
				// View: Index with escaped title
				'url' => 'http://example.org/w/index.php?title=Foo%20Bar',
				'query' => [ 'title' => 'Foo Bar' ],
				'title' => 'Foo_Bar',
				'redirect' => 'http://example.org/wiki/Foo_Bar',
			],
			[
				// View: Script path with escaped title
				'url' => 'http://example.org/w/?title=Foo_Bar',
				'query' => [ 'title' => 'Foo_Bar' ],
				'title' => 'Foo_Bar',
				'redirect' => false,
			],
			[
				// View: Root path with escaped title
				'url' => 'http://example.org/?title=Foo_Bar',
				'query' => [ 'title' => 'Foo_Bar' ],
				'title' => 'Foo_Bar',
				'redirect' => false,
			],
			[
				// View: Canonical with redundant query
				'url' => 'http://example.org/wiki/Foo_Bar?action=view',
				'query' => [ 'action' => 'view' ],
				'title' => 'Foo_Bar',
				'redirect' => false,
			],
			[
				// Edit: Canonical view url with action query
				'url' => 'http://example.org/wiki/Foo_Bar?action=edit',
				'query' => [ 'action' => 'edit' ],
				'title' => 'Foo_Bar',
				'redirect' => false,
			],
			[
				// View: Index with action query
				'url' => 'http://example.org/w/index.php?title=Foo_Bar&action=view',
				'query' => [ 'title' => 'Foo_Bar', 'action' => 'view' ],
				'title' => 'Foo_Bar',
				'redirect' => false,
			],
			[
				// Edit: Index with action query
				'url' => 'http://example.org/w/index.php?title=Foo_Bar&action=edit',
				'query' => [ 'title' => 'Foo_Bar', 'action' => 'edit' ],
				'title' => 'Foo_Bar',
				'redirect' => false,
			],
			[
				// Path with double slash prefix (T100782)
				'url' => 'http://example.org//wiki/Double_slash',
				'query' => [],
				'title' => 'Double_slash',
				'redirect' => false,
			],
			[
				// View: Media namespace redirect (T203942)
				'url' => 'http://example.org/w/index.php?title=Media:Foo_Bar',
				'query' => [ 'title' => 'Foo_Bar' ],
				'title' => 'File:Foo_Bar',
				'redirect' => 'http://example.org/wiki/File:Foo_Bar',
			],
		];
	}

	/**
	 * @dataProvider provideTryNormaliseRedirect
	 */
	public function testTryNormaliseRedirect( $url, $query, $title, $redirect = false ) {
		$environment = new MockEnvironment();
		$environment->setRequestInfo( $url, $query );

		$titleObj = Title::newFromText( $title );

		// Set global context since some involved code paths don't yet have context
		$context = $environment->makeFauxContext();
		$context->setTitle( $titleObj );

		/** @var ActionEntryPoint $mw */
		$mw = TestingAccessWrapper::newFromObject( $this->getEntryPoint( $environment, $context ) );

		$ret = $mw->tryNormaliseRedirect( $titleObj );

		$this->assertEquals(
			$redirect !== false,
			$ret,
			'Return true only when redirecting'
		);

		$this->assertEquals(
			$redirect ?: '',
			$context->getOutput()->getRedirect()
		);
	}

	public function testMainPageIsDomainRoot() {
		$this->overrideConfigValue( MainConfigNames::MainPageIsDomainRoot, true );

		$environment = new MockEnvironment();
		$environment->setRequestInfo( '/' );

		// Set global context since some involved code paths don't yet have context
		$context = $environment->makeFauxContext();

		$entryPoint = $this->getEntryPoint( $environment, $context );
		$entryPoint->run();

		$expected = '<title>(pagetitle: Main Page)';
		Assert::assertStringContainsString( $expected, $entryPoint->getCapturedOutput() );
	}

	public static function provideParseTitle() {
		return [
			"No title means main page" => [
				'query' => [],
				'expected' => 'Main Page',
			],
			"Empty title also means main page" => [
				'query' => wfCgiToArray( '?title=' ),
				'expected' => 'Main Page',
			],
			"Valid title" => [
				'query' => wfCgiToArray( '?title=Foo' ),
				'expected' => 'Foo',
			],
			"Invalid title" => [
				'query' => wfCgiToArray( '?title=[INVALID]' ),
				'expected' => false,
			],
			"Invalid 'oldid'… means main page? (we show an error elsewhere)" => [
				'query' => wfCgiToArray( '?oldid=9999999' ),
				'expected' => 'Main Page',
			],
			"Invalid 'diff'… means main page? (we show an error elsewhere)" => [
				'query' => wfCgiToArray( '?diff=9999999' ),
				'expected' => 'Main Page',
			],
			"Invalid 'curid'" => [
				'query' => wfCgiToArray( '?curid=9999999' ),
				'expected' => false,
			],
			"'search' parameter with no title provided forces Special:Search" => [
				'query' => wfCgiToArray( '?search=foo' ),
				'expected' => 'Special:Search',
			],
			"'action=revisiondelete' forces Special:RevisionDelete even with title" => [
				'query' => wfCgiToArray( '?action=revisiondelete&title=Unused' ),
				'expected' => 'Special:RevisionDelete',
			],
			"'action=historysubmit&revisiondelete=1' forces Special:RevisionDelete even with title" => [
				'query' => wfCgiToArray( '?action=historysubmit&revisiondelete=1&title=Unused' ),
				'expected' => 'Special:RevisionDelete',
			],
			"'action=editchangetags' forces Special:EditTags even with title" => [
				'query' => wfCgiToArray( '?action=editchangetags&title=Unused' ),
				'expected' => 'Special:EditTags',
			],
			"'action=historysubmit&editchangetags=1' forces Special:EditTags even with title" => [
				'query' => wfCgiToArray( '?action=historysubmit&editchangetags=1&title=Unused' ),
				'expected' => 'Special:EditTags',
			],
			"No title with 'action' still means main page" => [
				'query' => wfCgiToArray( '?action=history' ),
				'expected' => 'Main Page',
			],
			"No title with 'action=delete' does not mean main page, because we want to discourage deleting it by accident :D" => [
				'query' => wfCgiToArray( '?action=delete' ),
				'expected' => false,
			],
		];
	}

	private function doTestParseTitle( array $query, $expected ): void {
		if ( $expected === false ) {
			$this->expectException( MalformedTitleException::class );
		}

		$req = new FauxRequest( $query );
		/** @var ActionEntryPoint $mw */
		$mw = TestingAccessWrapper::newFromObject( $this->getEntryPoint( $req ) );

		$ret = $mw->parseTitle( $req );

		$this->assertEquals(
			$expected,
			$ret->getPrefixedText()
		);
	}

	/**
	 * @dataProvider provideParseTitle
	 */
	public function testParseTitle( $query, $expected ) {
		$this->doTestParseTitle( $query, $expected );
	}

	public static function provideParseTitleExistingPage(): array {
		return [
			"Valid 'oldid'" => [
				static fn ( WikiPage $page ): array => wfCgiToArray( '?oldid=' . $page->getRevisionRecord()->getId() ),
			],
			"Valid 'diff'" => [
				static fn ( WikiPage $page ): array => wfCgiToArray( '?diff=' . $page->getRevisionRecord()->getId() ),
			],
			"Valid 'curid'" => [
				static fn ( WikiPage $page ): array => wfCgiToArray( '?curid=' . $page->getId() ),
			],
		];
	}

	/**
	 * @dataProvider provideParseTitleExistingPage
	 */
	public function testParseTitle__existingPage( callable $queryBuildCallback ) {
		$pageTitle = 'TestParseTitle test page';
		$page = $this->getExistingTestPage( $pageTitle );
		$query = $queryBuildCallback( $page );
		$this->doTestParseTitle( $query, $pageTitle );
	}

	/**
	 * Test a post-send update cannot set cookies (T191537).
	 * @coversNothing
	 */
	public function testPostSendJobDoesNotSetCookie() {
		// Prevent updates from running immediately by setting
		// a plain DeferredUpdatesScopeStack which doesn't allow
		// opportunistic updates.
		DeferredUpdates::setScopeStack( new DeferredUpdatesScopeStack() );

		$mw = TestingAccessWrapper::newFromObject( $this->getEntryPoint() );

		/** @var FauxResponse $response */
		$response = $mw->getResponse();

		// A update that attempts to set a cookie
		$jobHasRun = false;
		DeferredUpdates::addCallableUpdate( static function () use ( $response, &$jobHasRun ) {
			$jobHasRun = true;
			$response->setCookie( 'JobCookie', 'yes' );
			$response->header( 'Foo: baz' );
		} );

		$mw->doPostOutputShutdown();

		// restInPeace() might have been registered to a callback of
		// register_postsend_function() and thus cannot be triggered from
		// PHPUnit.
		if ( $jobHasRun === false ) {
			$mw->restInPeace();
		}

		$this->assertTrue( $jobHasRun, 'post-send job has run' );
		$this->assertNull( $response->getCookie( 'JobCookie' ) );
		$this->assertNull( $response->getHeader( 'Foo' ) );
	}

	public function testInvalidRedirectingOnSpecialPageWithPersonallyIdentifiableTarget() {
		$this->overrideConfigValue( MainConfigNames::HideIdentifiableRedirects, true );

		$specialTitle = SpecialPage::getTitleFor( 'Mypage', 'in<valid' );
		$req = new FauxRequest( [
			'title' => $specialTitle->getPrefixedDbKey(),
		] );
		$req->setRequestURL( $specialTitle->getLinkURL() );

		$env = new MockEnvironment( $req );
		$context = $env->makeFauxContext();
		$context->setTitle( $specialTitle );

		$mw = TestingAccessWrapper::newFromObject( $this->getEntryPoint( $env, $context ) );

		$this->expectException( BadTitleError::class );
		$this->expectExceptionMessage( 'The requested page title contains invalid characters: "<".' );
		$mw->performRequest();
	}

	public function testForceSafeMode() {
		$skin = Skin::normalizeKey( $this->getServiceContainer()->getMainConfig()->get( MainConfigNames::DefaultSkin ) );
		$this->setService( 'UserOptionsLookup', new StaticUserOptionsLookup(
			[ 'FsmUser' => [ 'forcesafemode' => 1 ] ],
			[
				'forcesafemode' => 0,
				'skin' => $skin
			]
		) );

		$page = $this->getExistingTestPage();
		$title = $page->getTitle();

		$request = new FauxRequest( [ 'title' => $title->getPrefixedDBkey() ] );
		$env = new MockEnvironment( $request );
		$context = $env->makeFauxContext();
		$context->setTitle( $title );
		$mw = TestingAccessWrapper::newFromObject( $this->getEntryPoint( $env, $context ) );
		$mw->performRequest();
		$this->assertSame( null, $request->getRawVal( 'safemode' ) );
		$this->assertSame( '', $context->getOutput()->getRedirect() );

		$fsmRequest = new FauxRequest( [ 'title' => $title->getPrefixedDBkey() ] );
		$fsmEnv = new MockEnvironment( $fsmRequest );
		$fsmContext = $fsmEnv->makeFauxContext();
		$fsmContext->setTitle( $title );
		$fsmUser = $this->createMock( User::class );
		$fsmUser->method( 'isRegistered' )->willReturn( true );
		$fsmUser->method( 'getName' )->willReturn( 'FsmUser' );
		$fsmContext->setUser( $fsmUser );
		$fsmContext->setAuthority(
			$this->mockUserAuthorityWithPermissions( $fsmUser, [ 'read' ] )
		);
		$fsmMw = TestingAccessWrapper::newFromObject( $this->getEntryPoint( $fsmEnv, $fsmContext ) );
		$fsmMw->performRequest();
		$this->assertSame( '1', $fsmRequest->getRawVal( 'safemode' ) );
		$this->assertSame( '', $fsmContext->getOutput()->getRedirect() );
	}

	public function testForceSafeModeTryNormaliseRedirect() {
		$skin = Skin::normalizeKey( $this->getServiceContainer()->getMainConfig()->get( MainConfigNames::DefaultSkin ) );
		$this->setService( 'UserOptionsLookup', new StaticUserOptionsLookup(
			[ 'FsmUser' => [ 'forcesafemode' => 1 ] ],
			[
				'forcesafemode' => 0,
				'skin' => $skin
			]
		) );

		$page = $this->getExistingTestPage();
		$title = $page->getTitle();

		$request = new FauxRequest( [ 'title' => '_' . $title->getPrefixedDBkey() . '_' ] );
		$env = new MockEnvironment( $request );
		$context = $env->makeFauxContext();
		$context->setTitle( $title );
		$mw = TestingAccessWrapper::newFromObject( $this->getEntryPoint( $env, $context ) );
		$mw->performRequest();
		$this->assertSame( null, $request->getRawVal( 'safemode' ) );
		$this->assertSame( $page->getTitle()->getFullURL(), $context->getOutput()->getRedirect() );

		$fsmRequest = new FauxRequest( [ 'title' => '_' . $title->getPrefixedDBkey() . '_' ] );
		$fsmEnv = new MockEnvironment( $fsmRequest );
		$fsmContext = $fsmEnv->makeFauxContext();
		$fsmContext->setTitle( $title );
		$fsmUser = $this->createMock( User::class );
		$fsmUser->method( 'isRegistered' )->willReturn( true );
		$fsmUser->method( 'getName' )->willReturn( 'FsmUser' );
		$fsmContext->setUser( $fsmUser );
		$fsmContext->setAuthority(
			$this->mockUserAuthorityWithPermissions( $fsmUser, [ 'read' ] )
		);
		$fsmMw = TestingAccessWrapper::newFromObject( $this->getEntryPoint( $fsmEnv, $fsmContext ) );
		$fsmMw->performRequest();
		$this->assertSame( '1', $fsmRequest->getRawVal( 'safemode' ) );
		$this->assertSame( $page->getTitle()->getFullURL(), $fsmContext->getOutput()->getRedirect() );
	}

	public function testView() {
		$page = $this->getExistingTestPage();

		$request = new FauxRequest( [ 'title' => $page->getTitle()->getPrefixedDBkey() ] );
		$env = new MockEnvironment( $request );

		$entryPoint = $this->getEntryPoint( $env );
		$entryPoint->run();

		$expected = '<title>(pagetitle: ' . $page->getTitle()->getPrefixedText();
		Assert::assertStringContainsString( $expected, $entryPoint->getCapturedOutput() );
	}

	public function testViewViaRedirect() {
		$page = $this->getExistingTestPage( 'Origin_' . __METHOD__ );
		$target = $this->getExistingTestPage( 'Target_' . __METHOD__ );
		$link = $this->getServiceContainer()->getTitleFormatter()
			->getPrefixedText( $target );

		$this->editPage( $page, "#REDIRECT [[$link]]\n\nRedirect Footer" );
		$this->editPage( $target, "Redirect Target" );

		$request = new FauxRequest( [ 'title' => $page->getTitle()->getPrefixedDBkey() ] );
		$env = new MockEnvironment( $request );

		$entryPoint = $this->getEntryPoint( $env );
		$entryPoint->run();
		$output = $entryPoint->getCapturedOutput();

		Assert::assertStringContainsString( '<title>(pagetitle: Target', $output );
		Assert::assertStringContainsString( '(redirectedfrom: ', $output );
		Assert::assertStringContainsString( '>Origin', $output );
		Assert::assertStringContainsString( 'Target', $output );
	}

	public function testViewRedirectPage() {
		$page = $this->getExistingTestPage( 'Origin_' . __METHOD__ );
		$target = $this->getExistingTestPage( 'Target_' . __METHOD__ );
		$link = $this->getServiceContainer()->getTitleFormatter()
			->getPrefixedText( $target );

		$this->editPage( $page, "#REDIRECT [[$link]]\n\nRedirect Footer" );
		$this->editPage( $target, "Redirect Target" );

		$request = new FauxRequest( [
			'title' => $page->getTitle()->getPrefixedDBkey(),
			'redirect' => 'no'
		] );

		$env = new MockEnvironment( $request );

		$entryPoint = $this->getEntryPoint( $env );
		$entryPoint->run();
		$output = $entryPoint->getCapturedOutput();

		Assert::assertStringContainsString( '<title>(pagetitle: Origin', $output );
		Assert::assertStringContainsString( 'Redirect to:', $output );
		Assert::assertStringContainsString( 'Footer', $output );
	}

	public function testViewRedirectNonExistingViewablePage() {
		$this->overrideConfigValue( MainConfigNames::LanguageCode, 'en' );
		$page = $this->getExistingTestPage( 'Origin_' . __METHOD__ );
		$target = $this->getNonexistingTestPage( 'MediaWiki:Mainpage' );
		$link = $this->getServiceContainer()->getTitleFormatter()
			->getPrefixedText( $target );

		$this->editPage( $page, "#REDIRECT [[$link]]\n\nRedirect Footer" );
		$this->editPage( $target, "Redirect Target" );

		$request = new FauxRequest( [
			'title' => $page->getTitle()->getPrefixedDBkey(),
		] );

		$env = new MockEnvironment( $request );

		$entryPoint = $this->getEntryPoint( $env );
		$entryPoint->run();
		$output = $entryPoint->getCapturedOutput();

		Assert::assertStringContainsString( '<title>(pagetitle: MediaWiki:Mainpage', $output );
		Assert::assertStringContainsString( 'Mainpage', $output );
	}

	public function testViewRedirectNonExistingViewablePageInFrLanguageCode() {
		$this->overrideConfigValue( MainConfigNames::LanguageCode, 'fr' );
		$page = $this->getExistingTestPage( 'Origin_' . __METHOD__ );
		$target = $this->getNonexistingTestPage( 'MediaWiki:Mainpage' );
		$link = $this->getServiceContainer()->getTitleFormatter()
			->getPrefixedText( $target );

		$this->editPage( $page, "#REDIRECT [[$link]]\n\nRedirect Footer" );
		$this->editPage( $target, "Redirect Target" );

		$request = new FauxRequest( [
			'title' => $page->getTitle()->getPrefixedDBkey(),
		] );

		$env = new MockEnvironment( $request );

		$entryPoint = $this->getEntryPoint( $env );
		$entryPoint->run();
		$output = $entryPoint->getCapturedOutput();

		Assert::assertStringContainsString( '<title>(pagetitle: MediaWiki:Mainpage', $output );
		Assert::assertStringContainsString( 'Accueil', $output );
	}

	private function getOutputFromParams( $params ) {
		$request = new FauxRequest( $params );

		$env = new MockEnvironment( $request );
		$context = $env->makeFauxContext();

		$entryPoint = $this->getEntryPoint( $env );
		$entryPoint->run();
		return $entryPoint->getCapturedOutput();
	}

	private function getOutputFromParamsAsAdmin( $params ) {
		$request = new FauxRequest( $params );

		$env = new MockEnvironment( $request );
		$context = $env->makeFauxContext();
		$context->setUser( $this->getTestSysop()->getUser() );
		$entryPoint = $this->getEntryPoint( $env, $context );
		$entryPoint->run();
		return $entryPoint->getCapturedOutput();
	}

	// These tests also test some code in Article.php and DifferenceEngine.php; they are put here because isolating all of the different
	// bits from each other is tricky and not worth it
	public function testBogusOldid() {
		$this->overrideConfigValue( MainConfigNames::LanguageCode, 'qqx' );
		$revid = $this->editPage( 'Existing', 'Example2' )->getNewRevision()->getId();
		// Only oldid
		$this->assertStringContainsString( '(missing-revision-nolog: 99999)', $this->getOutputFromParams(
			[ 'oldid' => 99999 ]
		) );
		// Oldid and title
		$this->assertStringContainsString( '(missing-revision: 99999)', $this->getOutputFromParams(
			[ 'oldid' => 99999, 'title' => 'Example' ]
		) );
		// Oldid and diff
		$this->assertStringContainsString( '(difference-missing-revision-nolog: 99999, 1)', $this->getOutputFromParams(
			[ 'oldid' => 99999, 'diff' => 'cur' ]
		) );
		$this->assertStringContainsString( '(difference-missing-revision-nolog: 99999, 1)', $this->getOutputFromParams(
			[ 'oldid' => 99999, 'diff' => 'prev' ]
		) );
		$this->assertStringContainsString( '(difference-missing-revision-nolog: 99999(and)(word-separator)99998, 2)', $this->getOutputFromParams(
			[ 'oldid' => 99999, 'diff' => 99998 ]
		) );
		// These cases below are all identical in expected behavior to each other, listing for completeness
		// Oldid and diff and title (non-existing)
		$this->assertStringContainsString( '(difference-missing-revision: 99999, 1)', $this->getOutputFromParams(
			[ 'oldid' => 99999, 'title' => 'Example', 'diff' => 'cur' ]
		) );
		$this->assertStringContainsString( '(difference-missing-revision: 99999, 1)', $this->getOutputFromParams(
			[ 'oldid' => 99999, 'title' => 'Example', 'diff' => 'prev' ]
		) );
		$this->assertStringContainsString( '(difference-missing-revision: 99999(and)(word-separator)99998, 2)', $this->getOutputFromParams(
			[ 'oldid' => 99999, 'title' => 'Example', 'diff' => 99998 ]
		) );
		// Oldid and diff and title (existing)
		$this->assertStringContainsString( '(difference-missing-revision: 99999, 1)', $this->getOutputFromParams(
			[ 'oldid' => 99999, 'title' => 'Existing', 'diff' => 'cur' ]
		) );
		$this->assertStringContainsString( '(difference-missing-revision: 99999, 1)', $this->getOutputFromParams(
			[ 'oldid' => 99999, 'title' => 'Existing', 'diff' => 'prev' ]
		) );
		$this->assertStringContainsString( '(difference-missing-revision: 99999(and)(word-separator)99998, 2)', $this->getOutputFromParams(
			[ 'oldid' => 99999, 'title' => 'Existing', 'diff' => 99998 ]
		) );
		$this->assertStringContainsString( '(difference-missing-revision: 99999(and)(word-separator)99998, 2)', $this->getOutputFromParams(
			[ 'oldid' => 99999, 'title' => 'Existing', 'diff' => 99998 ]
		) );
		$this->deletePage( 'existing' );
		// Oldid and diff and title (deleted)
		$this->assertStringContainsString( '(difference-missing-revision: 99999, 1)', $this->getOutputFromParams(
			[ 'oldid' => 99999, 'title' => 'Existing', 'diff' => 'cur' ]
		) );
		$this->assertStringContainsString( '(difference-missing-revision: 99999, 1)', $this->getOutputFromParams(
			[ 'oldid' => 99999, 'title' => 'Existing', 'diff' => 'prev' ]
		) );
		$this->assertStringContainsString( '(difference-missing-revision: 99999(and)(word-separator)99998, 2)', $this->getOutputFromParams(
			[ 'oldid' => 99999, 'title' => 'Existing', 'diff' => 99998 ]
		) );
		$this->assertStringContainsString( '(difference-missing-revision: 99999(and)(word-separator)99998, 2)', $this->getOutputFromParams(
			[ 'oldid' => 99999, 'title' => 'Existing', 'diff' => 99998 ]
		) );
		// Test that a diff between a bogus and a deleted revision shows the undelete for the deleted revision
		$this->assertStringContainsString( 'Special:Undelete', $this->getOutputFromParamsAsAdmin(
			[ 'oldid' => 99999, 'title' => 'Existing', 'diff' => $revid ]
		) );
		$this->assertStringContainsString( 'Special:Undelete', $this->getOutputFromParamsAsAdmin(
			[ 'oldid' => 99999, 'diff' => $revid ]
		) );
	}

	public function testBogusDiff() {
		$this->overrideConfigValue( MainConfigNames::LanguageCode, 'qqx' );
		$revid = $this->editPage( 'Existing', 'Example2' )->getNewRevision()->getId();
		// Only diff
		$this->assertStringContainsString( '(difference-missing-revision-nolog: 99999, 1)', $this->getOutputFromParams(
			[ 'diff' => 99999 ]
		) );
		// Diff and title
		$this->assertStringContainsString( '(difference-missing-revision: 99999, 1)', $this->getOutputFromParams(
			[ 'diff' => 99999, 'title' => 'Example' ]
		) );
		// Existing oldid and diff
		$this->assertStringContainsString( '(difference-missing-revision: 99999, 1)', $this->getOutputFromParams(
			[ 'oldid' => $revid, 'diff' => 99999 ]
		) );
		$this->deletePage( 'Existing' );
		// Deleted oldid and diff
		$text = $this->getOutputFromParams(
			[ 'oldid' => $revid, 'diff' => 99999 ]
		);
		$this->assertStringContainsString( "(difference-missing-revision-nolog: $revid(and)(word-separator)99999, 2)", $text );
		$this->assertStringNotContainsString( "Special:Undelete", $text );
		$text = $this->getOutputFromParamsAsAdmin(
			[ 'oldid' => $revid, 'diff' => 99999 ]
		);
		$this->assertStringContainsString( "(difference-missing-revision-nolog: ", $text );
		$this->assertStringContainsString( "$revid</a>(and)(word-separator)99999, 2)", $text );
		$this->assertStringContainsString( "Special:Undelete", $text );

		// Deleted oldid and diff and title
		$this->assertStringContainsString( "(difference-missing-revision-nolog: $revid(and)(word-separator)99999, 2)", $this->getOutputFromParams(
			[ 'oldid' => $revid, 'diff' => 99999 ]
		) );
	}

	public function testDeletedDiff() {
		$this->overrideConfigValue( MainConfigNames::LanguageCode, 'qqx' );
		$revid1 = $this->editPage( 'Page1', 'Example2' )->getNewRevision()->getId();
		$revid2 = $this->editPage( 'Page1', 'Example3' )->getNewRevision()->getId();
		$revid3 = $this->editPage( 'Page2', 'Example4' )->getNewRevision()->getId();
		$this->deletePage( 'Page1' );
		// Two deleted revs from same title
		$text = $this->getOutputFromParams(
			[ 'oldid' => $revid1, 'diff' => $revid2 ]
		);
		$this->assertStringContainsString( "(difference-missing-revision-nolog: $revid1(and)(word-separator)$revid2, 2)", $text );
		$this->assertStringNotContainsString( "Special:Undelete", $text );
		$text = $this->getOutputFromParamsAsAdmin(
			[ 'oldid' => $revid1, 'diff' => $revid2 ]
		);
		$this->assertStringContainsString( "(difference-missing-revision-nolog:", $text );
		$this->assertStringContainsString( "$revid1</a>(and)(word-separator)", $text );
		$this->assertStringContainsString( "$revid2</a>, 2)", $text );
		$this->assertEquals( 2, substr_count( $text, 'Special:Undelete' ) );
		// One revision deleted, one existing
		$text = $this->getOutputFromParams(
			[ 'oldid' => $revid1, 'diff' => $revid3 ]
		);
		$this->assertStringContainsString( "(difference-missing-revision-nolog: $revid1, 1)", $text );
		$this->assertStringNotContainsString( "Special:Undelete", $text );
		$text = $this->getOutputFromParamsAsAdmin(
			[ 'oldid' => $revid1, 'diff' => $revid3 ]
		);
		$this->assertStringContainsString( "(difference-missing-revision-nolog: ", $text );
		$this->assertStringContainsString( "$revid1</a>, 1)", $text );
		$this->assertSame( 1, substr_count( $text, 'Special:Undelete' ) );
		// One revision deleted, one bogus
		$text = $this->getOutputFromParams(
			[ 'oldid' => $revid1, 'diff' => 99999 ]
		);
		$this->assertStringContainsString( "(difference-missing-revision-nolog: $revid1(and)(word-separator)99999, 2)", $text );
		$this->assertStringNotContainsString( "Special:Undelete", $text );
		$text = $this->getOutputFromParamsAsAdmin(
			[ 'oldid' => $revid1, 'diff' => 99999 ]
		);
		$this->assertStringContainsString( "(difference-missing-revision-nolog: ", $text );
		$this->assertStringContainsString( "$revid1</a>(and)(word-separator)99999, 2)", $text );
		$this->assertSame( 1, substr_count( $text, 'Special:Undelete' ) );
		// Two deleted revs from different titles
		$this->deletePage( 'Page2' );
		$text = $this->getOutputFromParams(
			[ 'oldid' => $revid1, 'diff' => $revid3 ]
		);
		$this->assertStringContainsString( "(difference-missing-revision-nolog: $revid1(and)(word-separator)$revid3, 2)", $text );
		$this->assertStringNotContainsString( "Special:Undelete", $text );
		$text = $this->getOutputFromParamsAsAdmin(
			[ 'oldid' => $revid1, 'diff' => $revid3 ]
		);
		$this->assertStringContainsString( "(difference-missing-revision-nolog: ", $text );
		$this->assertStringContainsString( "$revid1</a>(and)(word-separator)", $text );
		$this->assertStringContainsString( "$revid3</a>, 2)", $text );
		$this->assertEquals( 2, substr_count( $text, 'Special:Undelete' ) );
	}

	private function getOutputFromParamsEnglish( $params ) {
		$request = new FauxRequest( $params );

		$env = new MockEnvironment( $request );
		$context = $env->makeFauxContext();
		$context->setLanguage( 'en' );
		$entryPoint = $this->getEntryPoint( $env, $context );
		$entryPoint->run();
		return $entryPoint->getCapturedOutput();
	}

	private function getOutputFromParamsAsAdminEnglish( $params ) {
		$request = new FauxRequest( $params );

		$env = new MockEnvironment( $request );
		$context = $env->makeFauxContext();
		$context->setLanguage( 'en' );
		$context->setUser( $this->getTestSysop()->getUser() );
		$entryPoint = $this->getEntryPoint( $env, $context );
		$entryPoint->run();
		return $entryPoint->getCapturedOutput();
	}

	public function testTitleContext() {
		// From the deletion log URL, confirming that the title is passed through as-is
		$this->assertStringContainsString( 'page=Example', $this->getOutputFromParamsEnglish(
			[ 'oldid' => 99999, 'title' => 'Example' ]
		) );
		$this->assertStringContainsString( 'page=Example', $this->getOutputFromParamsEnglish(
			[ 'oldid' => 99999, 'diff' => 99998, 'title' => 'Example' ]
		) );
		$revid = $this->editPage( 'Existing', 'Example2' )->getNewRevision()->getId();
		// If 'oldid' is given then the deletion log for the page associated with that oldid is shown
		$this->assertStringContainsString( 'page=Existing', $this->getOutputFromParamsEnglish(
			[ 'oldid' => $revid, 'diff' => 99999, 'title' => 'Example' ]
		) );
		// But not the other way around
		$this->assertStringContainsString( 'page=Existing', $this->getOutputFromParamsEnglish(
			[ 'oldid' => $revid, 'diff' => 99999, 'title' => 'Example' ]
		) );
		// An existing oldid takes priority over the title
		$this->assertStringContainsString( 'page=Existing', $this->getOutputFromParamsEnglish(
			[ 'oldid' => $revid, 'diff' => 99999, 'title' => 'Example' ]
		) );
		$this->deletePage( 'Existing' );
		// If it's deleted then it falls back to the title parameter
		$this->assertStringContainsString( 'page=Example', $this->getOutputFromParamsEnglish(
			[ 'oldid' => $revid, 'diff' => 99999, 'title' => 'Example' ]
		) );
		// But not the Main Page
		$this->assertStringNotContainsString( 'deletion log', $this->getOutputFromParamsEnglish(
			[ 'oldid' => $revid, 'diff' => 99999 ]
		) );
		// The "diff" param doesn't set title context for bogus diffs, even as an admin
		$text = $this->getOutputFromParamsAsAdminEnglish(
			[ 'diff' => $revid ]
		);
		$this->assertStringContainsString( 'Special:Undelete', $text );
		$this->assertStringNotContainsString( 'deletion log', $text );
		$text = $this->getOutputFromParamsAsAdminEnglish(
			[ 'diff' => $revid, 'title' => 'Example' ]
		);
		$this->assertStringContainsString( 'page=Example', $text );
		$this->assertStringContainsString( 'Special:Undelete', $text );
	}

}
