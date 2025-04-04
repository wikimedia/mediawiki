<?php

namespace MediaWiki\Tests\Rest;

use GuzzleHttp\Psr7\Uri;
use MediaWiki\Config\ServiceOptions;
use MediaWiki\MainConfigNames;
use MediaWiki\Rest\BasicAccess\StaticBasicAuthorizer;
use MediaWiki\Rest\CorsUtils;
use MediaWiki\Rest\Handler;
use MediaWiki\Rest\HttpException;
use MediaWiki\Rest\RedirectException;
use MediaWiki\Rest\Reporter\ErrorReporter;
use MediaWiki\Rest\RequestData;
use MediaWiki\Rest\RequestInterface;
use MediaWiki\Rest\ResponseException;
use MediaWiki\Rest\ResponseFactory;
use MediaWiki\Rest\Router;
use MediaWiki\Rest\StringStream;
use MediaWiki\Rest\Validator\JsonBodyValidator;
use MediaWiki\Tests\Rest\Handler\HelloHandler;
use MediaWiki\User\UserIdentityValue;
use MediaWikiUnitTestCase;
use PHPUnit\Framework\Assert;
use PHPUnit\Framework\MockObject\MockObject;
use RuntimeException;
use Throwable;
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
		$cors = $this->getCorsUtils();

		$request = new RequestData( [
			'uri' => new Uri( '/rest/mock/v1/RouterTest/hello' ),
			'method' => 'OPTIONS'
		] );
		$router = $this->createRouter( $request );
		$router->setCors( $cors );

		$response = $router->execute( $request );
		$this->assertSame( 204, $response->getStatusCode() );
		$this->assertSame(
			[ 'HEAD', 'GET', ],
			$response->getHeader( 'Access-Control-Allow-Methods' )
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

		$response = $router->execute( $request );
		$body = $response->getBody();
		$body->rewind();
		$data = json_decode( $body->getContents(), true );
		$this->assertSame( 555, $response->getStatusCode(), (string)$response->getBody() );
		$this->assertSame( 'Mock error', $data['message'] );
		$this->assertSame(
			[ 'mediawiki.rest_api_errors_total:1|c|#path:mock_v1_RouterTest_throw,method:GET,status:555' ],
			$statsHelper->consumeAllFormatted()
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
		$this->assertSame(
			[ 'mediawiki.rest_api_latency_seconds:1|ms|#path:mock_v1_RouterTest_throwRedirect,method:GET,status:301' ],
			$statsHelper->consumeAllFormatted()
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
		$this->assertSame(
			[ 'mediawiki.rest_api_latency_seconds:1|ms|#path:mock_v1_RouterTest_throwWrapped,method:GET,status:200' ],
			$statsHelper->consumeAllFormatted()
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
		// Automatic parsing will fail, since it re	requires
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
		// Automatic parsing will fail, since it re	requires
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
		$this->assertSame( 411, $response->getStatusCode() );
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

	private function getCorsUtils(): CorsUtils {
		$cors = new CorsUtils(
			new ServiceOptions(
				CorsUtils::CONSTRUCTOR_OPTIONS,
				[
					MainConfigNames::AllowedCorsHeaders => [],
					MainConfigNames::AllowCrossOrigin => [],
					MainConfigNames::RestAllowCrossOriginCookieAuth => [],
					MainConfigNames::CanonicalServer => 'testing',
					MainConfigNames::CrossSiteAJAXdomains => [],
					MainConfigNames::CrossSiteAJAXdomainExceptions => [],
				]
			),
			new ResponseFactory( [] ),
			new UserIdentityValue(
				1,
				'Test'
			)
		);

		return $cors;
	}
}
