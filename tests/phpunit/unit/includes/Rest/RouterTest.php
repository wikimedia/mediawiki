<?php

namespace MediaWiki\Tests\Rest;

use GuzzleHttp\Psr7\Uri;
use MediaWiki\Permissions\PermissionManager;
use MediaWiki\Rest\BasicAccess\StaticBasicAuthorizer;
use MediaWiki\Rest\Handler;
use MediaWiki\Rest\HttpException;
use MediaWiki\Rest\RedirectException;
use MediaWiki\Rest\RequestData;
use MediaWiki\Rest\RequestInterface;
use MediaWiki\Rest\ResponseException;
use MediaWiki\Rest\ResponseFactory;
use MediaWiki\Rest\Router;
use MediaWiki\Rest\Validator\Validator;
use Psr\Container\ContainerInterface;
use User;
use Wikimedia\ObjectFactory;

/**
 * @covers \MediaWiki\Rest\Router
 */
class RouterTest extends \MediaWikiUnitTestCase {
	/** @return Router */
	private function createRouter(
		RequestInterface $request,
		$authError = null,
		$additionalRouteFiles = []
	) {
		$objectFactory = new ObjectFactory(
			$this->getMockForAbstractClass( ContainerInterface::class )
		);
		$permissionManager = $this->createMock( PermissionManager::class );
		$routeFiles = array_merge( [ __DIR__ . '/testRoutes.json' ], $additionalRouteFiles );
		return new Router(
			$routeFiles,
			[],
			'http://wiki.example.com',
			'/rest',
			new \EmptyBagOStuff(),
			new ResponseFactory( [] ),
			new StaticBasicAuthorizer( $authError ),
			$objectFactory,
			new Validator( $objectFactory, $permissionManager, $request, new User ),
			$this->createHookContainer()
		);
	}

	public function testPrefixMismatch() {
		$request = new RequestData( [ 'uri' => new Uri( '/bogus' ) ] );
		$router = $this->createRouter( $request );
		$response = $router->execute( $request );
		$this->assertSame( 404, $response->getStatusCode() );
	}

	public function testWrongMethod() {
		$request = new RequestData( [
			'uri' => new Uri( '/rest/mock/RouterTest/hello' ),
			'method' => 'TRACE'
		] );
		$router = $this->createRouter( $request );
		$response = $router->execute( $request );
		$this->assertSame( 405, $response->getStatusCode() );
		$this->assertSame( 'Method Not Allowed', $response->getReasonPhrase() );
		$this->assertSame( 'HEAD, GET', $response->getHeaderLine( 'Allow' ) );
	}

	public function testHeadToGet() {
		$request = new RequestData( [
			'uri' => new Uri( '/rest/mock/RouterTest/hello' ),
			'method' => 'HEAD'
		] );
		$router = $this->createRouter( $request );
		$response = $router->execute( $request );
		$this->assertSame( 200, $response->getStatusCode() );
	}

	public function testNoMatch() {
		$request = new RequestData( [ 'uri' => new Uri( '/rest/bogus' ) ] );
		$router = $this->createRouter( $request );
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

	public static function throwRedirectHandlerFactory() {
		return new class extends Handler {
			public function execute() {
				throw new RedirectException( 301, 'http://example.com' );
			}
		};
	}

	public static function throwWrappedHandlerFactory() {
		return new class extends Handler {
			public function execute() {
				$response = $this->getResponseFactory()->create();
				$response->setStatus( 200 );
				throw new ResponseException( $response );
			}
		};
	}

	public function testException() {
		$request = new RequestData( [ 'uri' => new Uri( '/rest/mock/RouterTest/throw' ) ] );
		$router = $this->createRouter( $request );
		$response = $router->execute( $request );
		$this->assertSame( 555, $response->getStatusCode() );
		$body = $response->getBody();
		$body->rewind();
		$data = json_decode( $body->getContents(), true );
		$this->assertSame( 'Mock error', $data['message'] );
	}

	public function testRedirectException() {
		$request = new RequestData( [ 'uri' => new Uri( '/rest/mock/RouterTest/throwRedirect' ) ] );
		$router = $this->createRouter( $request );
		$response = $router->execute( $request );
		$this->assertSame( 301, $response->getStatusCode() );
		$this->assertSame( 'http://example.com', $response->getHeaderLine( 'Location' ) );
	}

	public function testResponseException() {
		$request = new RequestData( [ 'uri' => new Uri( '/rest/mock/RouterTest/throwWrapped' ) ] );
		$router = $this->createRouter( $request );
		$response = $router->execute( $request );
		$this->assertSame( 200, $response->getStatusCode() );
	}

	public function testBasicAccess() {
		// Using the throwing handler is a way to assert that the handler is not executed
		$request = new RequestData( [ 'uri' => new Uri( '/rest/mock/RouterTest/throw' ) ] );
		$router = $this->createRouter( $request, 'test-error', [] );
		$response = $router->execute( $request );
		$this->assertSame( 403, $response->getStatusCode() );
		$body = $response->getBody();
		$body->rewind();
		$data = json_decode( $body->getContents(), true );
		$this->assertSame( 'test-error', $data['error'] );
	}

	/**
	 * @dataProvider providePaths
	 */
	public function testAdditionalEndpoints( $path ) {
		$request = new RequestData( [
			'uri' => new Uri( $path )
		] );
		$router = $this->createRouter(
			$request,
			null,
			[ __DIR__ . '/testAdditionalRoutes.json' ]
		);
		$response = $router->execute( $request );
		$this->assertSame( 200, $response->getStatusCode() );
	}

	public static function providePaths() {
		return [
			[ '/rest/mock/RouterTest/hello' ],
			[ '/rest/mock/RouterTest/hello/two' ],
		];
	}

	public function provideGetRouteUrl() {
		yield 'empty' => [ '', '', [], [] ];
		yield 'simple route' => [ '/foo/bar', '/foo/bar' ];
		yield 'simple route with query' =>
			[ '/foo/bar', '/foo/bar?x=1&y=2', [ 'x' => '1', 'y' => '2' ] ];
		yield 'simple route with strange query chars' =>
			[ '/foo+bar', '/foo+bar?x=%23&y=%25&z=%2B', [ 'x' => '#', 'y' => '%', 'z' => '+' ] ];
		yield 'route with simple path params' =>
			[ '/foo/{test}/baz', '/foo/bar/baz', [], [ 'test' => 'bar' ] ];
		yield 'route with strange path params' =>
			[ '/foo/{test}/baz', '/foo/b%25%2F%2Bz/baz', [], [ 'test' => 'b%/+z' ] ];
		yield 'space in path does not become a plus' =>
			[ '/foo/{test}/baz', '/foo/b%20z/baz', [], [ 'test' => 'b z' ] ];
		yield 'route with simple path params and query' =>
			[ '/foo/{test}/baz', '/foo/bar/baz?x=1', [ 'x' => '1' ], [ 'test' => 'bar' ] ];
	}

	/**
	 * @dataProvider provideGetRouteUrl
	 */
	public function testGetRouteUrl( $route, $expectedUrl, $query = [], $path = [] ) {
		$request = new RequestData( [ 'uri' => new Uri( '/rest/mock/route' ) ] );
		$router = $this->createRouter( $request );

		$url = $router->getRouteUrl( $route, $path, $query );
		$this->assertRegExp( '!^https?://[\w.]+/!', $url );

		$uri = new Uri( $url );
		$this->assertStringContainsString( $expectedUrl, $uri );
	}

}
