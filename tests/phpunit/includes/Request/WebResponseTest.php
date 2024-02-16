<?php

use MediaWiki\Request\WebResponse;

/**
 * @covers \MediaWiki\Request\WebResponse
 *
 * @group WebRequest
 */
class WebResponseTest extends MediaWikiIntegrationTestCase {

	/**
	 * Test that no cookies get set post-send.
	 */
	public function testDisableForPostSend() {
		$response = new WebResponse;
		$response->disableForPostSend();

		$hookWasRun = false;
		$this->setTemporaryHook( 'WebResponseSetCookie', static function () use ( &$hookWasRun ) {
			$hookWasRun = true;
			return true;
		} );

		$logger = new TestLogger();
		$logger->setCollect( true );
		$this->setLogger( 'cookie', $logger );
		$this->setLogger( 'header', $logger );

		$response->setCookie( 'TetsCookie', 'foobar' );
		$response->header( 'TestHeader', 'foobar' );

		$this->assertFalse( $hookWasRun, 'The WebResponseSetCookie hook should not run' );

		$this->assertEquals(
			[
				[ 'info', 'ignored post-send cookie {cookie}' ],
				[ 'info', 'ignored post-send header {header}' ],
			],
			$logger->getBuffer()
		);
	}

	public function testStatusCode() {
		$response = new WebResponse();

		$response->statusHeader( 404 );
		$this->assertSame( 404, $response->getStatusCode() );

		$response->header( 'Test', true, 415 );
		$this->assertSame( 415, $response->getStatusCode() );
	}

}
