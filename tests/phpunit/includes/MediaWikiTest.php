<?php

use MediaWiki\Deferred\DeferredUpdates;
use MediaWiki\MainConfigNames;
use MediaWiki\Request\WebResponse;

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

	/**
	 * Test a post-send job can not set cookies (T191537).
	 * @coversNothing
	 */
	public function testPostSendJobDoesNotSetCookie() {
		// Prevent updates from running
		$cleanup = DeferredUpdates::preventOpportunisticUpdates();

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
}
