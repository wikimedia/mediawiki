<?php

namespace MediaWiki\Tests\Rest\Handler;

use MediaWiki\Rest\Handler\RedirectHandler;
use MediaWiki\Rest\RequestData;
use MediaWiki\Rest\RouteDefinitionException;
use MediaWikiIntegrationTestCase;

/**
 * @covers \MediaWiki\Rest\Handler\RedirectHandler
 */
class RedirectHandlerTest extends MediaWikiIntegrationTestCase {
	use HandlerTestTrait;

	public static function redirectConfigProvider() {
		return [
			[
				[ 'path' => '/v1/other/path/{param}', 'code' => 301 ],
				301,
				'/rest/v1/other/path/value'
			],
			[
				[ 'path' => '/v1/other/path/{param}' ],
				308,
				'/rest/v1/other/path/value'
			],

			// Add more test cases
		];
	}

	public static function provideFailure() {
		return [
			[
				[ 'path' => '', 'code' => 308 ],
				RouteDefinitionException::class
			],
			[
				[ 'code' => 308 ],
				RouteDefinitionException::class
			],
			// Add more test cases
		];
	}

	/**
	 * @dataProvider redirectConfigProvider
	 */
	public function testExecute( $redirectConfig, $expectedCode, $expectedLocation ) {
		$request = new RequestData( [ 'pathParams' => [ 'param' => 'value' ] ] );
		$handler = new RedirectHandler();

		// Execute the handler with configuration
		$response = $this->executeHandler( $handler, $request, [ 'redirect' => $redirectConfig ] );

		// Assertions for the response
		$this->assertEquals( $expectedCode, $response->getStatusCode() ); // Check status code
		$this->assertEquals( $expectedLocation, $response->getHeaderLine( 'Location' ) ); // Check Location header
	}

	/**
	 * @dataProvider provideFailure
	 */
	public function testFailure( $redirectConfig, $expectedException ) {
		$request = new RequestData( [ 'pathParams' => [ 'param' => 'value' ] ] );
		$handler = new RedirectHandler();

		$this->expectException( $expectedException );

		// Execute the handler with configuration
		$this->executeHandler( $handler, $request, [ 'redirect' => $redirectConfig ] );
	}
}
