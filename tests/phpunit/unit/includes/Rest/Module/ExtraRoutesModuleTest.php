<?php

namespace MediaWiki\Tests\Rest\Module;

use GuzzleHttp\Psr7\Uri;
use MediaWiki\MainConfigNames;
use MediaWiki\Rest\BasicAccess\StaticBasicAuthorizer;
use MediaWiki\Rest\HttpException;
use MediaWiki\Rest\Module\ExtraRoutesModule;
use MediaWiki\Rest\Module\Module;
use MediaWiki\Rest\Reporter\ErrorReporter;
use MediaWiki\Rest\RequestData;
use MediaWiki\Rest\RequestInterface;
use MediaWiki\Rest\ResponseFactory;
use MediaWiki\Rest\Validator\Validator;
use MediaWiki\Tests\Rest\Handler\HelloHandler;
use MediaWiki\Tests\Rest\RestTestTrait;
use MediaWiki\Tests\Unit\DummyServicesTrait;
use PHPUnit\Framework\MockObject\MockObject;
use RuntimeException;
use Throwable;
use Wikimedia\Stats\StatsFactory;
use Wikimedia\TestingAccessWrapper;
use Wikimedia\Timestamp\ConvertibleTimestamp;

/**
 * @covers \MediaWiki\Rest\Module\ExtraRoutesModule
 * @covers \MediaWiki\Rest\Module\MatcherBasedModule
 */
class ExtraRoutesModuleTest extends \MediaWikiUnitTestCase {
	use RestTestTrait;
	use DummyServicesTrait;

	private const CANONICAL_SERVER = 'https://wiki.example.com';
	private const INTERNAL_SERVER = 'http://api.local:8080';

	/** @var Throwable[] */
	private $reportedErrors = [];

	/**
	 * @param RequestInterface $request
	 * @param array $constructorOverrides Supported keys: basicAuth, extraRoutes, hookContainer
	 *
	 * @return ExtraRoutesModule
	 */
	private function createRouteFileModule(
		RequestInterface $request,
		$constructorOverrides = []
	) {
		$routeFiles = [
			__DIR__ . '/moduleFlatRoutes.json', // old, flat format
		];

		/** @var MockObject|ErrorReporter $mockErrorReporter */
		$mockErrorReporter = $this->createNoOpMock( ErrorReporter::class, [ 'reportError' ] );
		$mockErrorReporter->method( 'reportError' )
			->willReturnCallback( function ( $e ) {
				$this->reportedErrors[] = $e;
			} );

		$config = [
			MainConfigNames::CanonicalServer => self::CANONICAL_SERVER,
			MainConfigNames::InternalServer => self::INTERNAL_SERVER,
			MainConfigNames::RestPath => '/rest',
		];

		$auth = $constructorOverrides['basicAuth'] ?? new StaticBasicAuthorizer();
		$objectFactory = $this->getDummyObjectFactory();

		$authority = $this->mockAnonUltimateAuthority();
		$validator = new Validator( $objectFactory, $request, $authority );

		$router = $this->newRouter( [
			'routeFiles' => [],
			'request' => $request,
			'config' => $config,
			'errorReporter' => $mockErrorReporter,
			'basicAuth' => $auth,
			'validator' => $validator
		] );

		$responseFactory = new ResponseFactory( [] );
		$responseFactory->setShowExceptionDetails( true );

		$module = new ExtraRoutesModule(
			$routeFiles,
			$constructorOverrides['extraRoutes'] ?? [],
			$router,
			$responseFactory,
			$auth,
			$objectFactory,
			$validator,
			$mockErrorReporter,
			$constructorOverrides['hookContainer'] ?? $this->createHookContainer()
		);

		return $module;
	}

	public function testWrongMethod() {
		$request = new RequestData( [
			'uri' => new Uri( '/rest/ModuleTest/hello/dude' ),
			'method' => 'TRACE'
		] );
		$module = $this->createRouteFileModule( $request );
		$response = $module->execute( '/ModuleTest/hello/dude', $request );
		$this->assertSame( 405, $response->getStatusCode(), (string)$response->getBody() );
		$this->assertSame( 'Method Not Allowed', $response->getReasonPhrase() );
		$this->assertSame( 'HEAD, GET', $response->getHeaderLine( 'Allow' ) );
	}

	public function testHeadToGet() {
		$request = new RequestData( [
			'uri' => new Uri( '/rest/ModuleTest/hello/dude' ),
			'method' => 'HEAD'
		] );
		$module = $this->createRouteFileModule( $request );
		$response = $module->execute( '/ModuleTest/hello/dude', $request );
		$this->assertSame( 200, $response->getStatusCode(), (string)$response->getBody() );
	}

	public function testFlatRouteFile() {
		$request = new RequestData( [
			'uri' => new Uri( '/rest/ModuleTest/hello/dude' ),
			'method' => 'HEAD'
		] );
		$module = $this->createRouteFileModule( $request );

		ConvertibleTimestamp::setFakeTime( '20110401090000' );
		$statsHelper = StatsFactory::newUnitTestingHelper();
		$module->setStats( $statsHelper->getStatsFactory() );

		$response = $module->execute( '/ModuleTest/hello/two', $request );
		$this->assertSame( 200, $response->getStatusCode() );
		$this->assertSame(
			[ 'mediawiki.rest_api_latency_seconds:1|ms|#path:ModuleTest_hello_name,method:HEAD,status:200' ],
			$statsHelper->consumeAllFormatted()
		);
	}

	public function testNoMatch() {
		// The /hello path requires a path parameter.
		$request = new RequestData( [ 'uri' => new Uri( '/rest/ModuleTest/hello' ) ] );
		$module = $this->createRouteFileModule( $request );
		$response = $module->execute( '/ModuleTest/hello', $request );
		$this->assertSame( 404, $response->getStatusCode() );
		// TODO: add more information to the response body and test for its presence here
	}

	public function testHttpException() {
		$request = new RequestData( [ 'uri' => new Uri( '/rest/ModuleTest/throw' ) ] );
		$module = $this->createRouteFileModule( $request );

		$statsHelper = StatsFactory::newUnitTestingHelper();
		$module->setStats( $statsHelper->getStatsFactory() );

		$response = $module->execute( '/ModuleTest/throw', $request );
		$this->assertSame( 555, $response->getStatusCode() );
		$body = $response->getBody();
		$body->rewind();
		$data = json_decode( $body->getContents(), true );
		$this->assertSame( 'Mock error', $data['message'] );
		$this->assertSame(
			[ 'mediawiki.rest_api_errors_total:1|c|#path:ModuleTest_throw,method:GET,status:555' ],
			$statsHelper->consumeAllFormatted()
		);
	}

	public function testFatalException() {
		$request = new RequestData( [ 'uri' => new Uri( '/rest/ModuleTest/fatal' ) ] );
		$module = $this->createRouteFileModule( $request );
		$response = $module->execute( '/ModuleTest/fatal', $request );
		$this->assertSame( 500, $response->getStatusCode() );
		$body = $response->getBody();
		$body->rewind();
		$data = json_decode( $body->getContents(), true );
		$this->assertStringContainsString( 'RuntimeException', $data['message'] );
		$this->assertNotEmpty( $this->reportedErrors );
		$this->assertInstanceOf( RuntimeException::class, $this->reportedErrors[0] );
	}

	public function testRedirectException() {
		$request = new RequestData( [ 'uri' => new Uri( '/rest/ModuleTest/throwRedirect' ) ] );
		$module = $this->createRouteFileModule( $request );
		$response = $module->execute( '/ModuleTest/throwRedirect', $request );
		$this->assertSame( 301, $response->getStatusCode() );
		$this->assertSame( 'http://example.com', $response->getHeaderLine( 'Location' ) );
	}

	public function testResponseException() {
		$request = new RequestData( [ 'uri' => new Uri( '/rest/ModuleTest/throwWrapped' ) ] );
		$module = $this->createRouteFileModule( $request );
		$response = $module->execute( '/ModuleTest/throwWrapped', $request );
		$this->assertSame( 200, $response->getStatusCode() );
	}

	public function testBasicAccess() {
		// Using the throwing handler is a way to assert that the handler is not executed
		$request = new RequestData( [ 'uri' => new Uri( '/rest/ModuleTest/throw' ) ] );
		$module = $this->createRouteFileModule( $request, [
			'basicAuth' => new StaticBasicAuthorizer( 'test-error' ),
		] );
		$response = $module->execute( '/ModuleTest/throw', $request );
		$this->assertSame( 403, $response->getStatusCode() );
		$body = $response->getBody();
		$body->rewind();
		$data = json_decode( $body->getContents(), true );
		$this->assertSame( 'test-error', $data['error'] );
	}

	public function testAdditionalEndpoints() {
		$request = new RequestData( [
			'uri' => new Uri( '/rest/ModuleTest/hello-again' )
		] );
		$module = $this->createRouteFileModule( $request, [
			'extraRoutes' => [ [
				'path' => '/ModuleTest/hello-again',
				'class' => 'MediaWiki\\Tests\\Rest\\Handler\\HelloHandler'
			] ],
		] );
		$response = $module->execute( '/ModuleTest/hello-again', $request );
		$this->assertSame( 200, $response->getStatusCode() );
	}

	public static function provideGetRouteUrl() {
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

	public function testCacheData() {
		$request = new RequestData( [ 'uri' => new Uri( '/rest/route' ) ] );
		$module1 = $this->createRouteFileModule( $request );
		$module1wrapper = TestingAccessWrapper::newFromObject( $module1 );

		$cacheData = $module1->getCacheData();

		// Create a second module
		$module2 = $this->createRouteFileModule( $request );
		$module2wrapper = TestingAccessWrapper::newFromObject( $module2 );

		// Destroy module2's ability to load routes
		$module2wrapper->routeFiles = [ '/this/does/not/exist' ];

		// Make sure the config hash is set and matches.
		$module2wrapper->configHash = $module1wrapper->configHash;

		// Check that initFromCacheData() succeeds.
		$this->assertTrue( $module2->initFromCacheData( $cacheData ) );

		// Check that the matcher tree is deep-equal after initFromCacheData().
		$this->assertEquals( $module1wrapper->getMatchers(), $module2wrapper->getMatchers() );

		// Invalidate the cache data
		$cacheData[ Module::CACHE_CONFIG_HASH_KEY ] = 'foobar';

		// Check that initFromCacheData() fails.
		$this->assertFalse( $module2->initFromCacheData( $cacheData ) );

		// Check that the matcher tree is still deep-equal.
		$this->assertEquals( $module1wrapper->getMatchers(), $module2wrapper->getMatchers() );
	}

	public function testRestCheckCanExecuteHook() {
		$request = new RequestData( [ 'uri' => new Uri( '/rest/ModuleTest/hello/foo' ) ] );
		$module = $this->createRouteFileModule( $request, [
			'hookContainer' => $this->createHookContainer( [
				'RestCheckCanExecute' =>
					function ( $module1, $handler, $path, $request1, &$error ) use ( $request, &$module ) {
						$this->assertSame( $module, $module1 );
						$this->assertInstanceOf( HelloHandler::class, $handler );
						$this->assertSame( '/ModuleTest/hello/foo', $path );
						$this->assertSame( $request, $request1 );
						$this->assertSame( null, $error );
						$error = new HttpException( 'Denied by hook', 403 );
						return false;
					},
			] ),
		] );

		$response = $module->execute( '/ModuleTest/hello/foo', $request );

		$this->assertSame( 403, $response->getStatusCode() );
		$body = $response->getBody();
		$body->rewind();
		$data = json_decode( $body->getContents(), true );
		$this->assertSame( 'Denied by hook', $data['message'] );
	}

	public function testOpenApiInfo() {
		$request = new RequestData( [ 'uri' => new Uri( '/rest/test.v1/ModuleTest/hello/world' ) ] );
		$module = $this->createRouteFileModule( $request );

		$info = $module->getOpenApiInfo();
		$this->assertSame( 'rest-module-extra-routes-title', $info['title'] );
		$this->assertSame( 'undefined', $info['version'] );

		$handler = $module->getHandlerForPath( '/ModuleTest/hello/world', $request );
		$oas = $handler->getOpenApiSpec( 'GET' );

		$this->assertSame( 'hello summary', $oas['summary'] );
	}

}
