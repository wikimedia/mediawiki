<?php

use MediaWiki\MainConfigNames;
use MediaWiki\Request\FauxRequest;
use MediaWiki\Request\WebRequest;
use MediaWiki\Request\WebResponse;
use MediaWiki\SpecialPage\SpecialPage;
use MediaWiki\Title\MalformedTitleException;
use MediaWiki\Title\Title;
use Wikimedia\TestingAccessWrapper;

/**
 * @group Database
 */
class MediaWikiTest extends MediaWikiIntegrationTestCase {
	private $oldServer, $oldGet, $oldPost;

	protected function setUp(): void {
		parent::setUp();

		$this->overrideConfigValues( [
			MainConfigNames::Server => 'http://example.org',
			MainConfigNames::ScriptPath => '/w',
			MainConfigNames::Script => '/w/index.php',
			MainConfigNames::ArticlePath => '/wiki/$1',
			MainConfigNames::ActionPaths => [],
			MainConfigNames::LanguageCode => 'en',
		] );

		// phpcs:disable MediaWiki.Usage.SuperGlobalsUsage.SuperGlobals
		$this->oldServer = $_SERVER;
		$this->oldGet = $_GET;
		$this->oldPost = $_POST;
	}

	protected function tearDown(): void {
		$_SERVER = $this->oldServer;
		$_GET = $this->oldGet;
		$_POST = $this->oldPost;
		// The MediaWiki class writes to $wgTitle. Revert any writes done in this test to make
		// sure that they don't leak into other tests (T341951)
		$GLOBALS['wgTitle'] = null;
		parent::tearDown();
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
	 * @covers MediaWiki::tryNormaliseRedirect
	 */
	public function testTryNormaliseRedirect( $url, $query, $title, $expectedRedirect = false ) {
		// Set SERVER because interpolateTitle() doesn't use getRequestURL(),
		// whereas tryNormaliseRedirect does(). Also, using WebRequest allows
		// us to test some quirks in that class.
		$_SERVER['REQUEST_URI'] = $url;
		$_POST = [];
		$_GET = $query;
		$req = new WebRequest;

		// This adds a virtual 'title' query parameter. Normally called from Setup.php
		$req->interpolateTitle();

		$titleObj = Title::newFromText( $title );

		// Set global context since some involved code paths don't yet have context
		$context = RequestContext::getMain();
		$context->setRequest( $req );
		$context->setTitle( $titleObj );

		$mw = new MediaWiki( $context );

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
		$mw = new MediaWiki();

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
	 * @covers MediaWiki::parseTitle
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
	 * @covers MediaWiki::parseTitle
	 */
	public function testParseTitle__existingPage( callable $queryBuildCallback ) {
		$pageTitle = 'TestParseTitle test page';
		$page = $this->getExistingTestPage( $pageTitle );
		$query = $queryBuildCallback( $page );
		$this->doTestParseTitle( $query, $pageTitle );
	}

	/**
	 * Test a post-send job can not set cookies (T191537).
	 * @coversNothing
	 */
	public function testPostSendJobDoesNotSetCookie() {
		// Prevent updates from running
		$this->setMwGlobals( 'wgCommandLineMode', false );

		$response = new WebResponse;

		// A job that attempts to set a cookie
		$jobHasRun = false;
		DeferredUpdates::addCallableUpdate( static function () use ( $response, &$jobHasRun ) {
			$jobHasRun = true;
			$response->setCookie( 'JobCookie', 'yes' );
			$response->header( 'Foo: baz' );
		} );

		$hookWasRun = false;
		$this->setTemporaryHook( 'WebResponseSetCookie', static function () use ( &$hookWasRun ) {
			$hookWasRun = true;
			return true;
		} );

		$logger = new TestLogger();
		$logger->setCollect( true );
		$this->setLogger( 'cookie', $logger );
		$this->setLogger( 'header', $logger );

		$mw = new MediaWiki();
		$mw->doPostOutputShutdown();
		// restInPeace() might have been registered to a callback of
		// register_postsend_function() and thus can not be triggered from
		// PHPUnit.
		if ( $jobHasRun === false ) {
			$mw->restInPeace();
		}

		$this->assertTrue( $jobHasRun, 'post-send job has run' );
		$this->assertFalse( $hookWasRun,
			'post-send job must not trigger WebResponseSetCookie hook' );
		$this->assertEquals(
			[
				[ 'info', 'ignored post-send cookie {cookie}' ],
				[ 'info', 'ignored post-send header {header}' ],
			],
			$logger->getBuffer()
		);
	}

	/**
	 * @covers MediaWiki::performRequest
	 */
	public function testInvalidRedirectingOnSpecialPageWithPersonallyIdentifiableTarget() {
		$this->overrideConfigValue( MainConfigNames::HideIdentifiableRedirects, true );

		$specialTitle = SpecialPage::getTitleFor( 'Mypage', 'in<valid' );
		$req = new FauxRequest( [
			'title' => $specialTitle->getPrefixedDbKey(),
		] );
		$req->setRequestURL( $specialTitle->getFullUrl() );

		$context = new RequestContext();
		$context->setRequest( $req );
		$context->setTitle( $specialTitle );

		$mw = TestingAccessWrapper::newFromObject( new MediaWiki( $context ) );

		$this->expectException( BadTitleError::class );
		$this->expectExceptionMessage( 'The requested page title contains invalid characters: "<".' );
		$mw->performRequest();
	}
}
