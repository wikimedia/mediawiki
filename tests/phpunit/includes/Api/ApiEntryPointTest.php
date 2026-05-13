<?php

namespace MediaWiki\Tests\Api;

use MediaWiki\Api\ApiEntryPoint;
use MediaWiki\Request\FauxRequest;
use MediaWiki\Tests\MockEnvironment;

/**
 * @group API
 * @group Database
 * @group medium
 *
 * @covers \MediaWiki\Api\ApiEntryPoint
 */
class ApiEntryPointTest extends ApiTestCase {

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

}
