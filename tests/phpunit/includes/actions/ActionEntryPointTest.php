<?php

namespace MediaWiki\Tests\Action;

use MediaWiki\Actions\ActionEntryPoint;
use MediaWiki\Context\RequestContext;
use MediaWiki\Deferred\DeferredUpdates;
use MediaWiki\Deferred\DeferredUpdatesScopeMediaWikiStack;
use MediaWiki\Deferred\DeferredUpdatesScopeStack;
use MediaWiki\Exception\BadTitleError;
use MediaWiki\MainConfigNames;
use MediaWiki\Request\FauxRequest;
use MediaWiki\Request\FauxResponse;
use MediaWiki\Request\WebRequest;
use MediaWiki\SpecialPage\SpecialPage;
use MediaWiki\Tests\MockEnvironment;
use MediaWiki\Tests\User\TempUser\TempUserTestTrait;
use MediaWiki\Title\MalformedTitleException;
use MediaWiki\Title\Title;
use MediaWikiIntegrationTestCase;
use PHPUnit\Framework\Assert;
use ReflectionMethod;
use Wikimedia\TestingAccessWrapper;
use WikiPage;

// phpcs:disable MediaWiki.Usage.SuperGlobalsUsage.SuperGlobals

/**
 * @group Database
 * @covers \MediaWiki\Actions\ActionEntryPoint
 */
class ActionEntryPointTest extends MediaWikiIntegrationTestCase {
	use TempUserTestTrait;

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
	public function testTryNormaliseRedirect( $url, $query, $title, $expectedRedirect = false ) {
		$environment = new MockEnvironment();
		$environment->setRequestInfo( $url, $query );

		$titleObj = Title::newFromText( $title );

		// Set global context since some involved code paths don't yet have context
		$context = $environment->makeFauxContext();
		$context->setTitle( $titleObj );

		$mw = $this->getEntryPoint( $environment, $context );

		$method = new ReflectionMethod( $mw, 'tryNormaliseRedirect' );
		$method->setAccessible( true );
		$ret = $method->invoke( $mw, $titleObj );

		$this->assertEquals(
			$expectedRedirect !== false,
			$ret,
			'Return true only when redirecting'
		);

		$this->assertEquals(
			$expectedRedirect ?: '',
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
		$mw = $this->getEntryPoint( $req );

		$method = new ReflectionMethod( $mw, 'parseTitle' );
		$method->setAccessible( true );
		$ret = $method->invoke( $mw, $req );

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

}
