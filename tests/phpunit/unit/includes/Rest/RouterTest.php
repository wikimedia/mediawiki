<?php

namespace MediaWiki\Tests\Rest;

use GuzzleHttp\Psr7\Uri;
use MediaWiki\Rest\BasicAccess\StaticBasicAuthorizer;
use MediaWiki\Rest\Handler;
use MediaWiki\Rest\HttpException;
use MediaWiki\Rest\RequestData;
use MediaWiki\Rest\ResponseFactory;
use MediaWiki\Rest\Router;

/**
 * @covers \MediaWiki\Rest\Router
 */
class RouterTest extends \MediaWikiUnitTestCase {
	/** @return Router */
	private function createRouter( $authError = null ) {
		return new Router(
			[ __DIR__ . '/testRoutes.json' ],
			[],
			'/rest',
			new \EmptyBagOStuff(),
			new ResponseFactory(),
			new StaticBasicAuthorizer( $authError ) );
	}

	public function testPrefixMismatch() {
		$router = $this->createRouter();
		$request = new RequestData( [ 'uri' => new Uri( '/bogus' ) ] );
		$response = $router->execute( $request );
		$this->assertSame( 404, $response->getStatusCode() );
	}

	public function testWrongMethod() {
		$router = $this->createRouter();
		$request = new RequestData( [
			'uri' => new Uri( '/rest/user/joe/hello' ),
			'method' => 'OPTIONS'
		] );
		$response = $router->execute( $request );
		$this->assertSame( 405, $response->getStatusCode() );
		$this->assertSame( 'Method Not Allowed', $response->getReasonPhrase() );
		$this->assertSame( 'GET', $response->getHeaderLine( 'Allow' ) );
	}

	public function testNoMatch() {
		$router = $this->createRouter();
		$request = new RequestData( [ 'uri' => new Uri( '/rest/bogus' ) ] );
		$response = $router->execute( $request );
		$this->assertSame( 404, $response->getStatusCode() );
		// TODO: add more information to the response body and test for its presence here
	}

	public static function throwHandlerFactory() {
		return new class extends Handler {
			public function execute() {
				throw new HttpException( 'Mock error', 555 );
			}
		};
	}

	public function testException() {
		$router = $this->createRouter();
		$request = new RequestData( [ 'uri' => new Uri( '/rest/mock/RouterTest/throw' ) ] );
		$response = $router->execute( $request );
		$this->assertSame( 555, $response->getStatusCode() );
		$body = $response->getBody();
		$body->rewind();
		$data = json_decode( $body->getContents(), true );
		$this->assertSame( 'Mock error', $data['message'] );
	}

	public function testBasicAccess() {
		$router = $this->createRouter( 'test-error' );
		// Using the throwing handler is a way to assert that the handler is not executed
		$request = new RequestData( [ 'uri' => new Uri( '/rest/mock/RouterTest/throw' ) ] );
		$response = $router->execute( $request );
		$this->assertSame( 403, $response->getStatusCode() );
		$body = $response->getBody();
		$body->rewind();
		$data = json_decode( $body->getContents(), true );
		$this->assertSame( 'test-error', $data['error'] );
	}
}
