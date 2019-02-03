<?php

class MediaWikiTest extends MediaWikiTestCase {
	private $oldServer, $oldGet, $oldPost;

	protected function setUp() {
		parent::setUp();

		$this->setMwGlobals( [
			'wgServer' => 'http://example.org',
			'wgScriptPath' => '/w',
			'wgScript' => '/w/index.php',
			'wgArticlePath' => '/wiki/$1',
			'wgActionPaths' => [],
		] );

		// phpcs:disable MediaWiki.Usage.SuperGlobalsUsage.SuperGlobals
		$this->oldServer = $_SERVER;
		$this->oldGet = $_GET;
		$this->oldPost = $_POST;
	}

	protected function tearDown() {
		parent::tearDown();
		$_SERVER = $this->oldServer;
		$_GET = $this->oldGet;
		$_POST = $this->oldPost;
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
		DeferredUpdates::addCallableUpdate( function () use ( $response, &$jobHasRun ) {
			$jobHasRun = true;
			$response->setCookie( 'JobCookie', 'yes' );
			$response->header( 'Foo: baz' );
		} );

		$hookWasRun = false;
		$this->setTemporaryHook( 'WebResponseSetCookie', function () use ( &$hookWasRun ) {
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
}
