<?php

namespace MediaWiki\Tests\Rest;

use GuzzleHttp\Psr7\Uri;
use MediaWiki\Config\ServiceOptions;
use MediaWiki\MainConfigNames;
use MediaWiki\Rest\BasicAccess\StaticBasicAuthorizer;
use MediaWiki\Rest\CorsUtils;
use MediaWiki\Rest\ErrorFormatterV1;
use MediaWiki\Rest\ErrorFormatterV2;
use MediaWiki\Rest\Handler;
use MediaWiki\Rest\HttpException;
use MediaWiki\Rest\Module\ModuleInfo;
use MediaWiki\Rest\Module\ModuleManager;
use MediaWiki\Rest\Module\ModuleMode;
use MediaWiki\Rest\PathTemplateMatcher\ModuleConfigurationException;
use MediaWiki\Rest\RedirectException;
use MediaWiki\Rest\Reporter\ErrorReporter;
use MediaWiki\Rest\RequestData;
use MediaWiki\Rest\RequestInterface;
use MediaWiki\Rest\ResponseException;
use MediaWiki\Rest\RestbaseCompatErrorFormatter;
use MediaWiki\Rest\Router;
use MediaWiki\Rest\StringStream;
use MediaWiki\Rest\Validator\JsonBodyValidator;
use MediaWiki\Tests\Rest\Handler\HelloHandler;
use MediaWiki\User\UserIdentityValue;
use MediaWiki\Utils\UrlUtils;
use MediaWikiUnitTestCase;
use PHPUnit\Framework\Assert;
use PHPUnit\Framework\MockObject\MockObject;
use RuntimeException;
use Throwable;
use UnexpectedValueException;
use Wikimedia\ObjectCache\HashBagOStuff;
use Wikimedia\ParamValidator\ParamValidator;
use Wikimedia\Stats\StatsFactory;
use Wikimedia\TestingAccessWrapper;
use Wikimedia\Timestamp\ConvertibleTimestamp;

/**
 * @covers \MediaWiki\Rest\Router
 * @covers \MediaWiki\Rest\Handler
 */
class RouterTest extends MediaWikiUnitTestCase {
	use RestTestTrait;

	private const CANONICAL_SERVER = 'https://wiki.example.com';
	private const INTERNAL_SERVER = 'http://api.local:8080';

	/** @var Throwable[] */
	private $reportedErrors = [];

	/** @var HashBagOStuff */
	private $cacheBag;

	protected function setUp(): void {
		parent::setUp();
		$this->cacheBag = new HashBagOStuff();
	}

	private function createRouter(
		RequestInterface $request,
		?string $authError = null,
		array $routeFiles = [ __DIR__ . '/testRoutes.json' ]
	): Router {
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
			MainConfigNames::ScriptPath => '/w'
		];

		$extraRoutes = [
			[ 'path' => '/', 'class' => HelloHandler::class ]
		];

		return $this->newRouter( [
			'routeFiles' => $routeFiles,
			'extraRoutes' => $extraRoutes,
			'request' => $request,
			'config' => $config,
			'cacheBag' => $this->cacheBag,
			'errorReporter' => $mockErrorReporter,
			'basicAuth' => new StaticBasicAuthorizer( $authError ),
		] );
	}

	public function testEmptyPath() {
		// The URI doesn't contain the "/" suffix, so the relative path is empty.
		$request = new RequestData( [ 'uri' => new Uri( '/rest' ) ] );
		$router = $this->createRouter( $request );
		$response = $router->execute( $request );
		$this->assertSame( 308, $response->getStatusCode() );
		$this->assertSame( '/rest/', $response->getHeaderLine( 'location' ) );
	}

	public function testRootPath() {
		// The URI contains only the "/" suffix.
		// This should be sufficient to be routed to the prefix-less modules.
		// The "/" path is mapped to the HelloHandler in createRouter().
		$request = new RequestData( [ 'uri' => new Uri( '/rest/' ) ] );
		$router = $this->createRouter( $request );
		$response = $router->execute( $request );
		$this->assertSame( 200, $response->getStatusCode(), (string)$response->getBody() );
	}

	public function testPrefixMismatch() {
		$request = new RequestData( [ 'uri' => new Uri( '/bogus' ) ] );
		$router = $this->createRouter( $request );
		$response = $router->execute( $request );
		$this->assertSame( 404, $response->getStatusCode() );
	}

	public function testWrongMethod() {
		$request = new RequestData( [
			'uri' => new Uri( '/rest/mock/v1/RouterTest/hello' ),
			'method' => 'TRACE'
		] );
		$router = $this->createRouter( $request );
		$response = $router->execute( $request );
		$this->assertSame( 405, $response->getStatusCode() );
		$this->assertSame( 'Method Not Allowed', $response->getReasonPhrase() );
		$this->assertSame( 'HEAD, GET', $response->getHeaderLine( 'Allow' ) );
	}

	public function testGetFromUglyPath() {
		$request = new RequestData( [
			'uri' => new Uri( '/w/rest.php/mock/v1/RouterTest/hello' ),
			'method' => 'GET'
		] );
		$router = $this->createRouter( $request );
		$response = $router->execute( $request );
		$this->assertSame( 200, $response->getStatusCode() );
	}

	public function testHeadToGet() {
		$request = new RequestData( [
			'uri' => new Uri( '/rest/mock/v1/RouterTest/hello' ),
			'method' => 'HEAD'
		] );
		$router = $this->createRouter( $request );
		$response = $router->execute( $request );
		$this->assertSame( 200, $response->getStatusCode() );
	}

	public function testCorsPreflight() {
		$cors = $this->getCorsUtils( true );

		$request = new RequestData( [
			'uri' => new Uri( '/rest/mock/v1/RouterTest/hello' ),
			'method' => 'OPTIONS'
		] );
		$router = $this->createRouter( $request );
		$router->setCors( $cors );

		$response = $router->execute( $request );
		$this->assertSame( 204, $response->getStatusCode() );
		// Allow-Methods comes from CorsUtils::createPreflightResponse() (in Module).
		$this->assertSame(
			[ 'HEAD', 'GET', ],
			$response->getHeader( 'Access-Control-Allow-Methods' )
		);
		// Allow-Origin comes from CorsUtils::modifyResponse(), applied in
		// Router::execute(). The preflight response is thrown as a
		// ResponseException from Module and must still reach modifyResponse().
		$this->assertSame(
			'*',
			$response->getHeaderLine( 'Access-Control-Allow-Origin' ),
			'Access-Control-Allow-Origin must be present on every response'
		);
	}

	public static function provideCorsHeadersApplied() {
		// Router::execute() must apply CORS headers to every response it
		// returns, regardless of which layer produced it.
		yield 'normal handler response (module-level 200)' =>
			[ '/rest/mock/v1/RouterTest/hello', 'GET', 200 ];
		yield 'no route match (module-level 404)' =>
			[ '/rest/bogus', 'GET', 404 ];
		yield 'wrong method (module-level 405)' =>
			[ '/rest/mock/v1/RouterTest/hello', 'TRACE', 405 ];
		// Router-level: splitPath() rejects the prefix before any Module runs.
		yield 'prefix mismatch (router-level 404)' =>
			[ '/bogus', 'GET', 404 ];
		// Router-level: doExecute() redirects the empty path before any Module runs.
		yield 'empty path redirect (router-level 308)' =>
			[ '/rest', 'GET', 308 ];
	}

	/**
	 * @dataProvider provideCorsHeadersApplied
	 *
	 * CORS headers are applied in Router::execute() rather than per-Module, so
	 * they cover every response path uniformly: module-level responses (success
	 * and errors) as well as router-level responses (redirects and prefix
	 * mismatches) that never reach a Module.
	 */
	public function testCorsHeadersAppliedToAllResponses(
		string $uri, string $method, int $expectedStatus
	) {
		$request = new RequestData( [ 'uri' => new Uri( $uri ), 'method' => $method ] );
		$router = $this->createRouter( $request );
		$router->setCors( $this->getCorsUtils( true ) );

		$response = $router->execute( $request );

		$this->assertSame(
			$expectedStatus,
			$response->getStatusCode(),
			"Status code should match. Body: " . $response->getBody()
		);

		// The essential assertion: modifyResponse() ran for this response path.
		$this->assertSame(
			'*',
			$response->getHeaderLine( 'Access-Control-Allow-Origin' ),
			'Access-Control-Allow-Origin must be present on every response'
		);
	}

	public function testNoMatch() {
		$request = new RequestData( [ 'uri' => new Uri( '/rest/bogus' ) ] );
		$router = $this->createRouter( $request );
		$response = $router->execute( $request );
		$this->assertSame( 404, $response->getStatusCode() );
		// TODO: add more information to the response body and test for its presence here
	}

	/**
	 * Constructs a handler that throws an HttpException
	 */
	public static function throwHandlerFactory(): Handler {
		return new class extends Handler {
			public function execute() {
				throw new HttpException( 'Mock error', 555 );
			}
		};
	}

	/**
	 * Constructs a handler that throws a RuntimeException with a custom code
	 */
	public static function fatalHandlerFactory(): Handler {
		return new class extends Handler {
			public function execute() {
				throw new RuntimeException( 'Fatal mock error', 12345 );
			}
		};
	}

	/**
	 * Constructs a handler that throws a RedirectException
	 */
	public static function throwRedirectHandlerFactory(): Handler {
		return new class extends Handler {
			public function execute() {
				throw new RedirectException( 301, 'http://example.com' );
			}
		};
	}

	/**
	 * Constructs a handler that throws a ResponseException with status 200
	 */
	public static function throwWrappedHandlerFactory(): Handler {
		return new class extends Handler {
			public function execute() {
				$response = $this->getResponseFactory()->create();
				$response->setStatus( 200 );
				throw new ResponseException( $response );
			}
		};
	}

	public function testHttpException() {
		$request = new RequestData( [ 'uri' => new Uri( '/rest/mock/v1/RouterTest/throw' ) ] );
		$statsHelper = StatsFactory::newUnitTestingHelper();
		$router = $this->createRouter( $request );
		$router->setStats( $statsHelper->getStatsFactory() );

		ConvertibleTimestamp::setFakeTime( '20110401090000' );
		$response = $router->execute( $request );
		$body = $response->getBody();
		$body->rewind();
		$data = json_decode( $body->getContents(), true );
		$this->assertSame( 555, $response->getStatusCode(), (string)$response->getBody() );
		$this->assertSame( 'Mock error', $data['message'] );

		// Metrics
		$metrics = $statsHelper->consumeAllFormatted();
		$this->assertSame(
			'mediawiki.rest_api_errors_total:1|c|#path:mock_v1_RouterTest_throw,method:GET,status:555',
			$metrics[0]
		);

		// Handler class is mocked so we need to allow for a dynamic class name
		$this->assertMatchesRegularExpression(
			'/mediawiki\.rest_api_modules_hit_total:1|c|#api_type:REST_API,api_module:mock_v1,api_endpoint:MediaWiki_Rest_Handler_anonymous_var_www_html_w_tests_phpunit_unit_includes_Rest_MockHandlerFactory_php_(a-Z0-9_)+,path:mock_v1_RouterTest_throw,method:GET,status:555/',
			$metrics[1]
		);
		$this->assertMatchesRegularExpression(
			'/mediawiki\.rest_api_modules_latency:1|ms|#api_type:REST_API,api_module:mock_v1,api_endpoint:MediaWiki_Rest_Handler_anonymous_var_www_html_w_tests_phpunit_unit_includes_Rest_MockHandlerFactory_php_(a-Z0-9_)+,path:mock_v1_RouterTest_throw,method:GET,status:555/',
			$metrics[2]
		);
	}

	public function testFatalException() {
		$request = new RequestData( [ 'uri' => new Uri( '/rest/mock/v1/RouterTest/fatal' ) ] );
		$router = $this->createRouter( $request );
		$response = $router->execute( $request );
		$this->assertSame( 500, $response->getStatusCode(), (string)$response->getBody() );
		$body = $response->getBody();
		$body->rewind();
		$data = json_decode( $body->getContents(), true );
		$this->assertStringContainsString( 'RuntimeException', $data['message'] );
		$this->assertNotEmpty( $this->reportedErrors );
		$this->assertInstanceOf( RuntimeException::class, $this->reportedErrors[0] );
	}

	public function testRedirectException() {
		ConvertibleTimestamp::setFakeTime( '20110401090000' );
		$request = new RequestData( [ 'uri' => new Uri( '/rest/mock/v1/RouterTest/throwRedirect' ) ] );
		$statsHelper = StatsFactory::newUnitTestingHelper();
		$router = $this->createRouter( $request );
		$router->setStats( $statsHelper->getStatsFactory() );

		$response = $router->execute( $request );
		$this->assertSame( 301, $response->getStatusCode(), (string)$response->getBody() );
		$this->assertSame( 'http://example.com', $response->getHeaderLine( 'Location' ) );

		// Metrics
		$metrics = $statsHelper->consumeAllFormatted();
		$this->assertSame(
			'mediawiki.rest_api_latency_seconds:1|ms|#path:mock_v1_RouterTest_throwRedirect,method:GET,status:301',
			$metrics[0]
		);

		// Handler class is mocked so we need to allow for a dynamic class name
		$this->assertMatchesRegularExpression(
			'/mediawiki\.rest_api_modules_hit_total:1|c|#api_type:REST_API,api_module:mock_v1,api_endpoint:MediaWiki_Rest_Handler_anonymous_var_www_html_w_tests_phpunit_unit_includes_Rest_MockHandlerFactory_php_(a-Z0-9_)+,path:mock_v1_RouterTest_throwRedirect,method:GET,status:301/',
			$metrics[1]
		);
		$this->assertMatchesRegularExpression(
			'/mediawiki\.rest_api_modules_latency:1|ms|#api_type:REST_API,api_module:mock_v1,api_endpoint:MediaWiki_Rest_Handler_anonymous_var_www_html_w_tests_phpunit_unit_includes_Rest_MockHandlerFactory_php_(a-Z0-9_)+,path:mock_v1_RouterTest_throwRedirect,method:GET,status:301/',
			$metrics[2]
		);
	}

	public function testRedirectDefinition() {
		// This route is defined in testRoutes.json without specifying a class or factory.
		$request = new RequestData( [ 'uri' => new Uri( '/rest/mock/v1/RouterTest/redirect' ) ] );
		$router = $this->createRouter( $request );
		$response = $router->execute( $request );
		$this->assertSame( 308, $response->getStatusCode(), (string)$response->getBody() );
		$this->assertSame( '/rest/mock/RouterTest/redirectTarget', $response->getHeaderLine( 'Location' ) );
	}

	public function testResponseException() {
		ConvertibleTimestamp::setFakeTime( '20110401090000' );
		$request = new RequestData( [ 'uri' => new Uri( '/rest/mock/v1/RouterTest/throwWrapped' ) ] );
		$statsHelper = StatsFactory::newUnitTestingHelper();
		$router = $this->createRouter( $request );
		$router->setStats( $statsHelper->getStatsFactory() );

		$response = $router->execute( $request );
		$this->assertSame( 200, $response->getStatusCode(), (string)$response->getBody() );

		// Metrics
		$metrics = $statsHelper->consumeAllFormatted();
		$this->assertSame(
			'mediawiki.rest_api_latency_seconds:1|ms|#path:mock_v1_RouterTest_throwWrapped,method:GET,status:200',
			$metrics[0]
		);

		// Handler class is mocked so we need to allow for a dynamic class name
		$this->assertMatchesRegularExpression(
			'/mediawiki\.rest_api_modules_hit_total:1|c|#api_type:REST_API,api_module:mock_v1,api_endpoint:MediaWiki_Rest_Handler_anonymous_var_www_html_w_tests_phpunit_unit_includes_Rest_MockHandlerFactory_php_(a-Z0-9_)+,path:mock_v1_RouterTest_throwWrapped,method:GET,status:200/',
			$metrics[1]
		);
		$this->assertMatchesRegularExpression(
			'/mediawiki\.rest_api_modules_latency:1|ms|#api_type:REST_API,api_module:mock_v1,api_endpoint:MediaWiki_Rest_Handler_anonymous_var_www_html_w_tests_phpunit_unit_includes_Rest_MockHandlerFactory_php_(a-Z0-9_)+,path:mock_v1_RouterTest_throwWrapped,method:GET,status:200/',
			$metrics[2]
		);
	}

	public function testBasicAccess() {
		// Using the throwing handler is a way to assert that the handler is not executed
		$request = new RequestData( [ 'uri' => new Uri( '/rest/mock/v1/RouterTest/throw' ) ] );
		$router = $this->createRouter( $request, 'test-error' );
		$response = $router->execute( $request );
		$this->assertSame( 403, $response->getStatusCode() );
		$body = $response->getBody();
		$body->rewind();
		$data = json_decode( $body->getContents(), true );
		$this->assertSame( 'test-error', $data['error'] );
	}

	public function testAdditionalEndpoints() {
		$request = new RequestData( [
			'uri' => new Uri( '/rest/mock-too/RouterTest/hello/two' )
		] );
		$router = $this->createRouter(
			$request,
			null,
			// NOTE: testAdditionalRoutes uses the old flat format!
			[ __DIR__ . '/testRoutes.json', __DIR__ . '/testAdditionalRoutes.json' ]
		);

		// Routes from flat route files end up on a module that uses the empty prefix.
		$this->assertSame( [ 'mock/v1', '' ], $router->getModuleIds() );

		$response = $router->execute( $request );
		$this->assertSame( 200, $response->getStatusCode() );
	}

	public function testFlatRouteFile() {
		$request = new RequestData( [
			'uri' => new Uri( '/rest/ModuleTest/hello/you' )
		] );
		$router = $this->createRouter(
			$request,
			null,
			[ __DIR__ . '/Module/moduleFlatRoutes.json' ]
		);

		$this->assertSame( [ '' ], $router->getModuleIds() );

		$response = $router->execute( $request );
		$this->assertSame( 200, $response->getStatusCode() );
	}

	public static function providePaths() {
		return [
			[ '/rest/mock/v1/RouterTest/hello' ],
			[ '/rest/mock-too/RouterTest/hello/two' ],
		];
	}

	public function testDuplicateModuleIdsFromSameFile() {
		$request = new RequestData( [
			'uri' => new Uri( '/rest/mock/v1/RouterTest/hello' )
		] );
		$router = $this->createRouter(
			$request,
			null,
			[ __DIR__ . '/testRoutes.json', __DIR__ . '/../Rest/testRoutes.json' ]
		);

		// createRouter will supply a '/' path that ends up with an empty prefix, so we need ''
		$this->assertSame( [ 'mock/v1', '' ], $router->getModuleIds() );
	}

	public function testDuplicateModulesIdsFromDifferentFiles() {
		$request = new RequestData( [
			'uri' => new Uri( '/rest/mock/v1/RouterTest/hello' )
		] );
		$router = $this->createRouter(
			$request,
			null,
			[ __DIR__ . '/testRoutes.json', __DIR__ . '/mock.v1.json' ]
		);

		$this->expectException( ModuleConfigurationException::class );
		$this->assertSame( [ 'mock/v1', '' ], $router->getModuleIds() );
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

	/**
	 * @dataProvider provideGetRouteUrl
	 */
	public function testGetRoutePath( $route, $expectedUrl, $query = [], $path = [] ) {
		$request = new RequestData( [ 'uri' => new Uri( '/rest/mock/v1/route' ) ] );
		$router = $this->createRouter( $request );

		$path = $router->getRoutePath( $route, $path, $query );
		$this->assertStringNotContainsString( self::CANONICAL_SERVER, $path );
		$this->assertStringStartsWith( '/', $path );

		$expected = new Uri( $expectedUrl );
		$actual = new Uri( $path );
		$this->assertStringContainsString( $expected->getPath(), $actual->getPath() );
		$this->assertStringContainsString( $expected->getQuery(), $actual->getQuery() );
	}

	/**
	 * @dataProvider provideGetRouteUrl
	 */
	public function testGetRouteUrl( $route, $expectedUrl, $query = [], $path = [] ) {
		$request = new RequestData( [ 'uri' => new Uri( '/rest/mock/v1/route' ) ] );
		$router = $this->createRouter( $request );

		$url = $router->getRouteUrl( $route, $path, $query );
		$this->assertStringStartsWith( self::CANONICAL_SERVER, $url );

		$uri = new Uri( $url );
		$this->assertStringContainsString( $expectedUrl, $uri );
	}

	/**
	 * @dataProvider provideGetRouteUrl
	 */
	public function testGetPrivateRouteUrl( $route, $expectedUrl, $query = [], $path = [] ) {
		$request = new RequestData( [ 'uri' => new Uri( '/rest/mock/v1/route' ) ] );
		$router = $this->createRouter( $request );

		$url = $router->getPrivateRouteUrl( $route, $path, $query );
		$this->assertStringStartsWith( self::INTERNAL_SERVER, $url );

		$uri = new Uri( $url );
		$this->assertStringContainsString( $expectedUrl, $uri );
	}

	public function testCaching() {
		$request = new RequestData( [ 'uri' => new Uri( '/rest/mock/v1/route' ) ] );
		$router1 = $this->createRouter( $request );
		$router1wrapper = TestingAccessWrapper::newFromObject( $router1 );

		// Ensure the module map is loaded and cached
		$router1->getModule( 'mock' );

		// Create a second router
		$router2 = $this->createRouter( $request );
		$router2wrapper = TestingAccessWrapper::newFromObject( $router2 );

		// Destroy $router2's ability to load modules and routes
		$router2wrapper->routeFiles = [ '/this/does/not/exist' ];

		// Make sure the config hash is set and matches.
		$router2wrapper->configHash = $router1wrapper->configHash;

		// Check that $router2 can return a module based on cached information.
		// Note that this needs both levels of the cache to work.
		$module2 = $router2->getModule( 'mock/v1' );
		$this->assertNotNull( $module2 );

		// Create a third router
		$router3 = $this->createRouter( $request );
		$router3wrapper = TestingAccessWrapper::newFromObject( $router3 );

		// Force a different route file (but don't force the config hash)
		$router3wrapper->routeFiles = [ __DIR__ . '/testAdditionalRoutes.json' ];

		// This should fail, since the router should detect that the config is
		// different, so it can't use cached data.
		$module3 = $router3->getModule( 'mock/v1' );
		$this->assertNull( $module3 );
	}

	public function testHandlerDisablesBodyParsing() {
		// This is valid JSON, but not an object.
		// Automatic parsing will fail, since it requires
		// an array to be returned.
		$payload = '"just a test"';

		$request = new RequestData( [
			'uri' => new Uri( '/rest/mock/v1/RouterTest/stream' ),
			'method' => 'PUT',
			'bodyContents' => $payload,
			'headers' => [ "content-type" => 'application/json' ]
		] );

		$router = $this->createRouter( $request );
		$response = $router->execute( $request );
		$this->assertSame( 200, $response->getStatusCode() );

		$responseStream = $response->getBody();
		$this->assertSame( $payload, "$responseStream" );
	}

	/**
	 * Asserts that handlers can use a custom BodyValidator to add support for
	 * additional mime types, without overriding parseBodyData(). This ensures
	 * backwards compatibility with extensions that are not yet aware of
	 * parseBodyData().
	 */
	public function testCustomBodyValidator() {
		$this->expectDeprecationAndContinue( '/overrides getBodyValidator/' );
		$this->expectDeprecationAndContinue( '/Validator::validateBody/' );
		$this->expectDeprecationAndContinue( '/JsonBodyValidator/' );

		// This is valid JSON, but not an object.
		// Automatic parsing will fail, since it requires
		// an array to be returned.
		$payload = '{ "test": "yes" }';

		$request = new RequestData( [
			'uri' => new Uri( '/rest/mock/v1/RouterTest/old-body-validator' ),
			'method' => 'PUT',
			'bodyContents' => $payload,
			'headers' => [ "content-type" => 'application/json-patch+json' ]
		] );

		$router = $this->createRouter( $request );
		$response = $router->execute( $request );
		$this->assertSame( 200, $response->getStatusCode(), (string)$response->getBody() );
	}

	/**
	 * Constructs a handler that disables body parsing
	 */
	public static function streamHandlerFactory(): Handler {
		return new class extends Handler {
			public function parseBodyData( RequestInterface $request ): ?array {
				// Disable parsing
				return null;
			}

			public function execute() {
				Assert::assertNull( $this->getRequest()->getParsedBody() );
				$body = $this->getRequest()->getBody();
				$response = $this->getResponseFactory()->create();
				$response->setBody( new StringStream( "$body" ) );
				return $response;
			}
		};
	}

	/**
	 * Constructs a handler that uses a BodyValidator object
	 */
	public static function oldBodyValidatorFactory(): Handler {
		return new class extends Handler {
			/** @var bool */
			private $postValidationSetupCalled = false;

			public function getBodyValidator( $contentType ) {
				if ( $contentType !== 'application/json-patch+json' ) {
					throw new HttpException(
						"Unsupported Content-Type",
						415,
					);
				}

				return new JsonBodyValidator( [
					'test' => [
						ParamValidator::PARAM_REQUIRED => true,
						static::PARAM_SOURCE => 'body',
					]
				] );
			}

			public function execute() {
				$body = $this->getValidatedBody();
				Assert::assertIsArray( $body );
				Assert::assertArrayHasKey( 'test', $body );
				Assert::assertTrue( $this->postValidationSetupCalled );
				return "";
			}

			protected function postValidationSetup() {
				$this->postValidationSetupCalled = true;
			}
		};
	}

	/**
	 * Constructs a handler that echos a form data request body
	 */
	public static function formHandlerFactory(): Handler {
		return new class extends Handler {

			public function execute() {
				return $this->getValidatedBody();
			}

			public function getParamSettings(): array {
				return [
					'foo' => [
						Handler::PARAM_SOURCE => 'body'
					]
				];
			}

			public function getSupportedRequestTypes(): array {
				return [
					'application/x-www-form-urlencoded',
					'multipart/form-data'
				];
			}
		};
	}

	/**
	 * Constructs a handler that echos a JSON request body
	 */
	public static function dataHandlerFactory(): Handler {
		return new class extends Handler {

			public function execute() {
				return $this->getValidatedBody();
			}

			public function getParamSettings(): array {
				return [
					'foo' => [
						Handler::PARAM_SOURCE => 'body'
					]
				];
			}
		};
	}

	public function testGetRequestFailsWithBody() {
		$this->markTestSkipped( 'T359509' );
		$request = new RequestData( [
			'uri' => new Uri( '/rest/mock/v1/RouterTest/echo' ),
			'method' => 'GET',
			'bodyContents' => '{"foo":"bar"}',
			'headers' => [ "content-type" => 'application/json' ]
		] );
		$router = $this->createRouter( $request );
		$response = $router->execute( $request );
		$this->assertSame( 400, $response->getStatusCode() );
	}

	public function testGetRequestIgnoresEmptyBody() {
		$request = new RequestData( [
			'uri' => new Uri( '/rest/mock/v1/RouterTest/echo' ),
			'method' => 'GET',
			'bodyContents' => '',
			'headers' => [
				"content-length" => 0,
				"content-type" => 'text/plain'
			]
		] );
		$router = $this->createRouter( $request );
		$response = $router->execute( $request );
		$this->assertSame( 200, $response->getStatusCode() );
	}

	public function testPostRequestFailsWithoutBody() {
		$request = new RequestData( [
			'uri' => new Uri( '/rest/mock/v1/RouterTest/echo' ),
			'method' => 'POST',
		] );
		$router = $this->createRouter( $request );
		$response = $router->execute( $request );
		$this->assertSame( 400, $response->getStatusCode() );
	}

	public function testEmptyBodyWithoutContentTypePasses() {
		$request = new RequestData( [
			'uri' => new Uri( '/rest/mock/v1/RouterTest/echo' ),
			'method' => 'POST',
			'headers' => [ 'content-length' => '0' ],
			'bodyContent' => '',
			// Should pass even without content-type!
		] );

		$router = $this->createRouter( $request );
		$response = $router->execute( $request );
		$this->assertSame( 200, $response->getStatusCode() );
	}

	public function testRequestBodyWithoutContentTypeFails() {
		$request = new RequestData( [
			'uri' => new Uri( '/rest/mock/v1/RouterTest/echo' ),
			'method' => 'POST',
			'bodyContents' => '{"foo":"bar"}', // Request body without content-type
		] );
		$router = $this->createRouter( $request );
		$response = $router->execute( $request );
		$this->assertSame( 415, $response->getStatusCode() );
	}

	public function testDeleteRequestWithoutBody() {
		// Test DELETE request without body
		$requestWithoutBody = new RequestData( [
		'uri' => new Uri( '/rest/mock/v1/RouterTest/echo' ),
		'method' => 'DELETE',
		] );
		$router = $this->createRouter( $requestWithoutBody );
		$responseWithoutBody = $router->execute( $requestWithoutBody );
		$this->assertSame( 200, $responseWithoutBody->getStatusCode() );
	}

	public function testDeleteRequestWithBody() {
			// Test DELETE request with body
			$requestWithBody = new RequestData( [
				'uri' => new Uri( '/rest/mock/v1/RouterTest/echo' ),
				'method' => 'DELETE',
				'bodyContents' => '{"bodyParam":"bar"}',
				'headers' => [ "content-type" => 'application/json' ]
			] );
			$router = $this->createRouter( $requestWithBody );
			$responseWithBody = $router->execute( $requestWithBody );
			$this->assertSame( 200, $responseWithBody->getStatusCode() );
	}

	public function testUnsupportedContentTypeReturns415() {
		$request = new RequestData( [
			'uri' => new Uri( '/rest/mock/v1/RouterTest/echo' ),
			'method' => 'POST',
			'bodyContents' => '{"foo":"bar"}',
			'headers' => [ "content-type" => 'text/plain' ] // Unsupported content type
		] );
		$router = $this->createRouter( $request );
		$response = $router->execute( $request );
		$this->assertSame( 415, $response->getStatusCode() );
	}

	public function testFormDataReturns415() {
		$request = new RequestData( [
			// NOTE: The data handler will fail with form data,
			//       only json is supported per default.
			'uri' => new Uri( '/rest/mock/v1/RouterTest/data-handler' ),
			'method' => 'POST',
			'postParams' => [ 'foo' => 'bar' ],
			'headers' => [ "content-type" => 'application/x-www-form-urlencoded' ]
		] );
		$router = $this->createRouter( $request );
		$response = $router->execute( $request );
		$this->assertSame( 415, $response->getStatusCode() );
	}

	public function testFormDataSupported() {
		// See T362850
		$this->expectDeprecationAndContinue( '/The "post" source is deprecated/' );

		$request = new RequestData( [
			'uri' => new Uri( '/rest/mock/v1/RouterTest/echo_form_data' ),
			'method' => 'POST',
			'postParams' => [ 'foo' => 'bar' ],
			'headers' => [ "content-type" => 'application/x-www-form-urlencoded' ]
		] );
		$router = $this->createRouter( $request );
		$response = $router->execute( $request );
		$this->assertSame( 200, $response->getStatusCode() );

		// Check if the response contains a field called 'parsedBody'
		$body = $response->getBody();
		$body->rewind();
		$data = json_decode( $body->getContents(), true );
		$this->assertSame( [ 'foo' => 'bar' ], $data[ 'parsedBody' ] );
	}

	public function testJsonBody() {
		$request = new RequestData( [
			'uri' => new Uri( '/rest/mock/v1/RouterTest/echo' ),
			'method' => 'POST',
			'bodyContents' => '{"bodyParam":"bar"}',
			'headers' => [ "content-type" => 'application/json' ]
		] );
		$router = $this->createRouter( $request );
		$response = $router->execute( $request );
		$this->assertSame( 200, $response->getStatusCode() );

		$body = $response->getBody();
		$body->rewind();
		$data = json_decode( $body->getContents(), true );

		// Check the value of 'parsedBody' and 'validateBody' fields
		$this->assertEquals( [ 'bodyParam' => 'bar' ], $data['parsedBody'] );
		$this->assertEquals( [ 'bodyParam' => 'bar' ], $data['validatedBody'] );
		$this->assertArrayNotHasKey( 'bodyParam', $data['validatedParams'] );
	}

	public function testFormDataBody() {
		$request = new RequestData( [
			'uri' => new Uri( '/rest/mock/v1/RouterTest/echo' ),
			'method' => 'POST',
			'postParams' => [ 'bodyParam' => 'bar' ],
			'headers' => [
				"content-type" => 'application/x-www-form-urlencoded',
				"content-length" => 123,
			]
		] );
		$router = $this->createRouter( $request );
		$response = $router->execute( $request );
		$this->assertSame( 200, $response->getStatusCode(), (string)$response->getBody() );

		$body = $response->getBody();
		$body->rewind();
		$data = json_decode( $body->getContents(), true );

		// The body parameter should be in parsedBody and validatedBody,
		// but not in validatedParams.
		$this->assertEquals( [ 'bodyParam' => 'bar' ], $data['parsedBody'] );
		$this->assertEquals( [ 'bodyParam' => 'bar' ], $data['validatedBody'] );
		$this->assertArrayNotHasKey( 'bodyParam', $data['validatedParams'] );
	}

	public function testFormDataBody_post() {
		// See T362850
		$this->expectDeprecationAndContinue( '/The "post" source is deprecated/' );

		$request = new RequestData( [
			'uri' => new Uri( '/rest/mock/v1/RouterTest/echo_form_data' ),
			'method' => 'POST',
			'postParams' => [ 'postParam' => 'bar' ],
			'headers' => [
				"content-type" => 'application/x-www-form-urlencoded',
				"content-length" => 123,
			]
		] );
		$router = $this->createRouter( $request );
		$response = $router->execute( $request );
		$this->assertSame( 200, $response->getStatusCode(), (string)$response->getBody() );

		$body = $response->getBody();
		$body->rewind();
		$data = json_decode( $body->getContents(), true );

		// The post parameter should be in parsedBody and validatedParams,
		// but not as in validatedBody.
		$this->assertEquals( [ 'postParam' => 'bar' ], $data['parsedBody'] );
		$this->assertArrayHasKey( 'postParam', $data['validatedParams'] );
		$this->assertArrayNotHasKey( 'postParam', $data['validatedBody'] );
	}

	public function testHandlerCanAccessValidatedParams() {
		$request = new RequestData( [
			'uri' => new Uri( '/rest/mock/v1/RouterTest/echo/bar' ),
			'method' => 'POST',
			'headers' => [ "content-type" => 'application/json' ],
			'bodyContents' => '{}'
		] );
		$router = $this->createRouter( $request );
		$response = $router->execute( $request );
		$this->assertSame( 200, $response->getStatusCode(), (string)$response->getBody() );

		// Check if the response contains a field called 'pathParams'
		$body = $response->getBody();
		$body->rewind();
		$data = json_decode( $body->getContents(), true );
		$this->assertArrayHasKey( 'validatedParams', $data );

		// Check the value of the 'pathParams' field
		$validatedParams = $data['validatedParams'];
		$this->assertEquals( 'bar', $validatedParams[ 'pathParam' ], (string)$response->getBody() );
	}

	public function testGetModuleResponseFactory_missing_schema_version() {
		$request = new RequestData();
		$router = $this->newRouter();
		$wrapper = TestingAccessWrapper::newFromObject( $router );

		// No errorSchemaVersion declared -> reuse the already-injected default ResponseFactory.
		$rf = $wrapper->getModuleResponseFactory( [], $request );
		$formatter = TestingAccessWrapper::newFromObject( $rf )->errorFormatter;
		$this->assertInstanceOf( ErrorFormatterV1::class, $formatter );
	}

	public static function provideGetModuleResponseFactory_use_schema_version() {
		yield [ '1.0', ErrorFormatterV1::class ];
		yield [ '2.0', ErrorFormatterV2::class ];
		yield [ 'restbase', RestbaseCompatErrorFormatter::class ];
	}

	/**
	 * @dataProvider provideGetModuleResponseFactory_use_schema_version
	 */
	public function testGetModuleResponseFactory_use_schema_version( $schemaVersion, $class ) {
		$router = $this->newRouter();
		$request = new RequestData();
		$wrapper = TestingAccessWrapper::newFromObject( $router );

		$rf = $wrapper->getModuleResponseFactory( [ 'errorSchemaVersion' => $schemaVersion ], $request );
		$formatter = TestingAccessWrapper::newFromObject( $rf )->errorFormatter;
		$this->assertInstanceOf( $class, $formatter );
	}

	public function testGetModuleResponseFactory_restbase_compat() {
		$router = $this->newRouter();
		$request = new RequestData( [ 'headers' => [ 'x-restbase-compat' => 'true' ] ] );
		$wrapper = TestingAccessWrapper::newFromObject( $router );

		$rf = $wrapper->getModuleResponseFactory( [], $request );
		$formatter = TestingAccessWrapper::newFromObject( $rf )->errorFormatter;
		$this->assertInstanceOf( RestbaseCompatErrorFormatter::class, $formatter );
	}

	public function testGetModuleResponseFactory_bad_schema_version() {
		$router = $this->newRouter();
		$request = new RequestData();
		$wrapper = TestingAccessWrapper::newFromObject( $router );

		$this->expectException( ModuleConfigurationException::class );
		$wrapper->getModuleResponseFactory( [ 'errorSchemaVersion' => '99.99' ], $request );
	}

	public function testGetModuleResponseFactory_V2() {
		$router = $this->newRouter();
		$request = new RequestData( [
			'uri' => new Uri( '/rest/test' ),
			'method' => 'POST',
		] );
		$wrapper = TestingAccessWrapper::newFromObject( $router );

		$rf = $wrapper->getModuleResponseFactory( [ 'errorSchemaVersion' => '2.0' ], $request );

		$body = $rf->createHttpError( 404 )->getBody();
		$body->rewind();
		$data = json_decode( $body->getContents(), true );

		$this->assertSame( 'https://wiki.example.com/rest/test', $data['tracing']['url'] );
	}

	public function testModuleDeclaringSchemaVersionUsesRegisteredFormatter() {
		$router = $this->newRouter( [
			'routeFiles' => [ __DIR__ . '/mock-schemaver.v1.json' ],
		] );

		$request = new RequestData( [ 'uri' => new Uri( '/rest/mockschemaver/v1' ) ] );
		$module = $router->getModuleForRequest( $request, 'mockschemaver/v1' );
		$handler = $module->getHandlerForPath( '/test', new RequestData( [] ), true );

		$this->assertNotNull( $module );

		// The module must have been constructed with the ResponseFactory
		// that uses the formatter registered for its declared schema version.
		$responseFactory = TestingAccessWrapper::newFromObject( $module )->responseFactory;
		$formatter = TestingAccessWrapper::newFromObject( $responseFactory )->errorFormatter;

		$this->assertInstanceOf( ErrorFormatterV2::class, $formatter );

		// Response factory test
		$responseFactory = TestingAccessWrapper::newFromObject( $handler )->responseFactory;
		$formatter = TestingAccessWrapper::newFromObject( $responseFactory )->errorFormatter;

		$this->assertInstanceOf( ErrorFormatterV2::class, $formatter );
	}

	private function getCorsUtils( bool $allowCrossOrigin = false ): CorsUtils {
		$cors = new CorsUtils(
			new ServiceOptions(
				CorsUtils::CONSTRUCTOR_OPTIONS,
				[
					MainConfigNames::AllowedCorsHeaders => [],
					MainConfigNames::AllowCrossOrigin => $allowCrossOrigin,
					MainConfigNames::RestAllowCrossOriginCookieAuth => [],
					MainConfigNames::CanonicalServer => 'testing',
					MainConfigNames::CrossSiteAJAXdomains => [],
					MainConfigNames::CrossSiteAJAXdomainExceptions => [],
				]
			),
			new UserIdentityValue(
				1,
				'Test'
			)
		);

		return $cors;
	}

	/**
	 * Create a Router instance configured with a mock ModuleManager returning $info.
	 *
	 * @param ModuleInfo $info
	 * @param UrlUtils|null $urlUtils
	 * @return Router
	 */
	private function createRouterWithModuleInfo(
		ModuleInfo $info,
		?UrlUtils $urlUtils = null
	): Router {
		$moduleManager = $this->createMock( ModuleManager::class );
		$moduleManager->method( 'getModuleInfo' )
			->with( $info->getId() )
			->willReturn( $info );

		$params = [ 'moduleManager' => $moduleManager ];
		if ( $urlUtils !== null ) {
			$params['urlUtils'] = $urlUtils;
		}
		return $this->newRouter( $params );
	}

	/**
	 * Test that `Router::ROUTE_MODULE_SPEC` constant matches the expected route template.
	 */
	public function testRouteModuleSpecConstant(): void {
		$this->assertSame( '/specs/v0/module/{module}', Router::ROUTE_MODULE_SPEC );
	}

	/**
	 * Test that `getModuleBaseUrl()` returns an external absolute URL unchanged.
	 */
	public function testGetModuleBaseUrlExternalAbsolute(): void {
		$info = new ModuleInfo(
			'external/v1',
			ModuleMode::PUBLISHED,
			true,
			'External Module',
			null,
			null,
			[],
			'https://example.com/base',
			'https://example.com/spec.json'
		);

		$router = $this->createRouterWithModuleInfo( $info );
		$this->assertSame( 'https://example.com/base', $router->getModuleBaseUrl( 'external/v1' ) );
	}

	/**
	 * Test that `getModuleBaseUrl()` expands an external relative URL using `UrlUtils`.
	 */
	public function testGetModuleBaseUrlExternalRelative(): void {
		$info = new ModuleInfo(
			'external/v1',
			ModuleMode::PUBLISHED,
			true,
			'External Module',
			null,
			null,
			[],
			'/api/rest_v1/c',
			'https://example.com/spec.json'
		);

		$router = $this->createRouterWithModuleInfo( $info );
		$this->assertSame(
			'https://wiki.example.com/api/rest_v1/c',
			$router->getModuleBaseUrl( 'external/v1' )
		);
	}

	/**
	 * Test that `getModuleBaseUrl()` returns null when an external module has no base URL.
	 */
	public function testGetModuleBaseUrlExternalNull(): void {
		$info = new ModuleInfo(
			'external/v1',
			ModuleMode::PUBLISHED,
			true,
			'External Module',
			null,
			null,
			[],
			null,
			'https://example.com/spec.json'
		);

		$router = $this->createRouterWithModuleInfo( $info );
		$this->assertNull( $router->getModuleBaseUrl( 'external/v1' ) );
	}

	/**
	 * Test that `getModuleBaseUrl()` returns null when `UrlUtils` fails to expand a malformed URL.
	 */
	public function testGetModuleBaseUrlExternalUnresolvable(): void {
		$info = new ModuleInfo(
			'external/v1',
			ModuleMode::PUBLISHED,
			true,
			'External Module',
			null,
			null,
			[],
			'invalid-relative-url',
			'https://example.com/spec.json'
		);

		$urlUtils = $this->createMock( UrlUtils::class );
		$urlUtils->expects( $this->once() )
			->method( 'expand' )
			->with( 'invalid-relative-url' )
			->willReturn( null );

		$router = $this->createRouterWithModuleInfo( $info, $urlUtils );
		$this->assertNull( $router->getModuleBaseUrl( 'external/v1' ) );
	}

	/**
	 * Test that `getModuleBaseUrl()` for a local module generates the route URL from module ID.
	 */
	public function testGetModuleBaseUrlLocal(): void {
		$info = new ModuleInfo(
			'local/v1',
			ModuleMode::PUBLISHED,
			false,
			null,
			null,
			null,
			[]
		);

		$router = $this->createRouterWithModuleInfo( $info );
		$this->assertSame(
			'https://wiki.example.com/rest/local/v1',
			$router->getModuleBaseUrl( 'local/v1' )
		);
	}

	/**
	 * Test that `getModuleBaseUrl()` for the prefix-less module ('') generates the root route URL.
	 */
	public function testGetModuleBaseUrlPrefixless(): void {
		$info = new ModuleInfo(
			'',
			ModuleMode::PUBLISHED,
			false,
			null,
			null,
			null,
			[]
		);

		$router = $this->createRouterWithModuleInfo( $info );
		$this->assertSame(
			'https://wiki.example.com/rest/',
			$router->getModuleBaseUrl( '' )
		);
	}

	/**
	 * Test that `getModuleBaseUrl()` returns null when the module does not exist.
	 */
	public function testGetModuleBaseUrlNonExistent(): void {
		$moduleManager = $this->createMock( ModuleManager::class );
		$moduleManager->method( 'getModuleInfo' )
			->with( 'nonexistent' )
			->willReturn( null );

		$router = $this->newRouter( [ 'moduleManager' => $moduleManager ] );
		$this->assertNull( $router->getModuleBaseUrl( 'nonexistent' ) );
	}

	/**
	 * Test that `getModuleSpecUrl()` returns an external absolute spec URL unchanged.
	 */
	public function testGetModuleSpecUrlExternalAbsolute(): void {
		$info = new ModuleInfo(
			'external/v1',
			ModuleMode::PUBLISHED,
			true,
			'External Module',
			null,
			null,
			[],
			'https://example.com/base',
			'https://example.com/spec.json'
		);

		$router = $this->createRouterWithModuleInfo( $info );
		$this->assertSame(
			'https://example.com/spec.json',
			$router->getModuleSpecUrl( 'external/v1' )
		);
	}

	/**
	 * Test that `getModuleSpecUrl()` expands an external relative spec URL `using UrlUtils`.
	 */
	public function testGetModuleSpecUrlExternalRelative(): void {
		$info = new ModuleInfo(
			'external/v1',
			ModuleMode::PUBLISHED,
			true,
			'External Module',
			null,
			null,
			[],
			null,
			'/api/rest_v1/?spec'
		);

		$router = $this->createRouterWithModuleInfo( $info );
		$this->assertSame(
			'https://wiki.example.com/api/rest_v1/?spec',
			$router->getModuleSpecUrl( 'external/v1' )
		);
	}

	/**
	 * Test that `getModuleSpecUrl()` throws `UnexpectedValueException` when an external module
	 * has no spec URL configured.
	 */
	public function testGetModuleSpecUrlExternalNull(): void {
		$info = new ModuleInfo(
			'external/v1',
			ModuleMode::PUBLISHED,
			true,
			'External Module',
			null,
			null,
			[],
			null,
			null
		);

		$router = $this->createRouterWithModuleInfo( $info );
		$this->expectException( UnexpectedValueException::class );
		$this->expectExceptionMessage( "External module 'external/v1' has no spec URL configured" );
		$router->getModuleSpecUrl( 'external/v1' );
	}

	/**
	 * Test that `getModuleSpecUrl()` returns null when `UrlUtils` fails to expand a malformed URL.
	 */
	public function testGetModuleSpecUrlExternalUnresolvable(): void {
		$info = new ModuleInfo(
			'external/v1',
			ModuleMode::PUBLISHED,
			true,
			'External Module',
			null,
			null,
			[],
			null,
			'invalid-spec-url'
		);

		$urlUtils = $this->createMock( UrlUtils::class );
		$urlUtils->expects( $this->once() )
			->method( 'expand' )
			->with( 'invalid-spec-url' )
			->willReturn( null );

		$router = $this->createRouterWithModuleInfo( $info, $urlUtils );
		$this->assertNull( $router->getModuleSpecUrl( 'external/v1' ) );
	}

	/**
	 * Test standard local module spec URL generation using `ROUTE_MODULE_SPEC`.
	 */
	public function testGetModuleSpecUrlLocalDefault(): void {
		$info = new ModuleInfo(
			'local/v1',
			ModuleMode::PUBLISHED,
			false,
			null,
			null,
			null,
			[]
		);

		$router = $this->createRouterWithModuleInfo( $info );
		$this->assertSame(
			'https://wiki.example.com/rest/specs/v0/module/local%2Fv1',
			$router->getModuleSpecUrl( 'local/v1' )
		);
	}

	/**
	 * Test that the prefix-less module ('') generates a spec URL with the '-' placeholder.
	 */
	public function testGetModuleSpecUrlPrefixless(): void {
		$info = new ModuleInfo(
			'',
			ModuleMode::PUBLISHED,
			false,
			null,
			null,
			null,
			[]
		);

		$router = $this->createRouterWithModuleInfo( $info );
		$this->assertSame(
			'https://wiki.example.com/rest/specs/v0/module/-',
			$router->getModuleSpecUrl( '' )
		);
	}

	/**
	 * Test that `getModuleSpecUrl()` returns null when the module does not exist.
	 */
	public function testGetModuleSpecUrlNonExistent(): void {
		$moduleManager = $this->createMock( ModuleManager::class );
		$moduleManager->method( 'getModuleInfo' )
			->with( 'nonexistent' )
			->willReturn( null );

		$router = $this->newRouter( [ 'moduleManager' => $moduleManager ] );
		$this->assertNull( $router->getModuleSpecUrl( 'nonexistent' ) );
	}
}
