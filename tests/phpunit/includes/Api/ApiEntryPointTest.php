<?php

namespace MediaWiki\Tests\Api;

use MediaWiki\Api\ApiEntryPoint;
use MediaWiki\Request\FauxRequest;
use MediaWiki\Tests\MockEnvironment;
use TestLogger;

/**
 * @group API
 * @group Database
 * @group medium
 *
 * @covers \MediaWiki\Api\ApiEntryPoint
 */
class ApiEntryPointTest extends ApiTestCase {
	/** @var array */
	private $oldServer;

	protected function setUp(): void {
		parent::setUp();
		$this->oldServer = $_SERVER;
	}

	protected function tearDown(): void {
		$_SERVER = $this->oldServer;
		parent::tearDown();
	}

	public function testSimpleRequest() {
		$request = new FauxRequest();
		$request->setRequestURL( '/w/api.php' );

		$env = new MockEnvironment( $request );
		$context = $env->makeFauxContext();

		$entryPoint = new ApiEntryPoint(
			$context,
			$env,
			$this->getServiceContainer()
		);

		$entryPoint->enableOutputCapture();
		$entryPoint->run();

		$output = $entryPoint->getCapturedOutput();
		$this->assertStringContainsString( '<!DOCTYPE html>', $output );
		$this->assertStringContainsString( '<title>(pagetitle: (api-help-title))</title>', $output );

		// TODO: Check caching headers and such.
	}

	/**
	 * Test that request are rejected if query parameters and post body
	 * contain contradictory information (T421287).
	 */
	public function testSpoofProtection() {
		$request = new FauxRequest( [], true );
		$request->setRequestURL( '/w/api.php' );
		$request->setHeader( 'Content-Type', 'application/x-www-form-urlencoded' );
		$request->setParams(
			[ 'action' => 'query', 'meta' => 'siteinfo', 'format' => 'json' ],
			[ 'meta' => 'allmessages', ]
		);

		$env = new MockEnvironment( $request );
		$context = $env->makeFauxContext();

		$entryPoint = new ApiEntryPoint(
			$context,
			$env,
			$this->getServiceContainer()
		);

		$entryPoint->enableOutputCapture();
		$entryPoint->run();

		$output = $entryPoint->getCapturedOutput();
		$response = json_decode( $output, true );

		$this->assertIsArray( $response, 'Expected valid JSON response' );
		$this->assertArrayHasKey( 'error', $response, 'Request should produce an error' );
		$this->assertSame( 'invalidpostparams', $response['error']['code'] );
	}

	/**
	 * Test that request are accepted if query parameters and post body
	 * contain the same (T421287).
	 */
	public function testSpoofProtection_same_value() {
		$request = new FauxRequest( [], true );
		$request->setRequestURL( '/w/api.php' );
		$request->setHeader( 'Content-Type', 'application/x-www-form-urlencoded' );
		$request->setParams(
			[ 'action' => 'query', 'meta' => 'siteinfo', 'format' => 'json' ],
			[ 'action' => 'query', 'meta' => 'siteinfo', 'format' => 'json' ]
		);

		$env = new MockEnvironment( $request );
		$context = $env->makeFauxContext();

		$entryPoint = new ApiEntryPoint(
			$context,
			$env,
			$this->getServiceContainer()
		);

		$entryPoint->enableOutputCapture();
		$entryPoint->run();

		$output = $entryPoint->getCapturedOutput();
		$response = json_decode( $output, true );

		$this->assertIsArray( $response, 'Expected valid JSON response' );
		$this->assertArrayHasKey( 'query', $response, 'Request should produce siteinfo' );
	}

	public function testPostLog() {
		$_SERVER['REQUEST_TIME_FLOAT'] = 1301648400.0123;
		$_SERVER['REMOTE_ADDR'] = '80.100.128.1';
		$_SERVER['HTTP_HOST'] = 'example.org';
		$_SERVER['REQUEST_METHOD'] = 'POST';
		$_SERVER['REQUEST_URI'] = '/w/api.php';
		$request = new class( data: [], wasPosted: true ) extends FauxRequest {
			protected function getRawIP() {
				return $_SERVER['REMOTE_ADDR'];
			}
		};
		$request->setRequestURL( $_SERVER['REQUEST_URI'] );
		$logger = new TestLogger( true, null, true );
		$this->setLogger( 'post', $logger );

		$env = new MockEnvironment( $request );
		$entryPoint = new ApiEntryPoint(
			$env->makeFauxContext(),
			$env,
			$this->getServiceContainer()
		);
		$entryPoint->enableOutputCapture();
		$entryPoint->run();
		$entryPoint->getCapturedOutput();

		$this->assertSame( [ [
			'info',
			'{reqStart} {entrypoint} {method} {url} {user} {ip}',
			[
				'reqStart' => '2011-04-01T09:00:00.012300Z',
				'entrypoint' => 'cli',
				'method' => 'POST',
				'url' => 'http://example.org/w/api.php',
				'user' => '80.100.128.1',
				'ip' => '80.100.128.1',
			]
		] ], $logger->getBuffer() );
	}

}
