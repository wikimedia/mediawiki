<?php

namespace MediaWiki\Tests\Rest;

use GuzzleHttp\Psr7\Uri;
use HashBagOStuff;
use MediaWiki\Config\ServiceOptions;
use MediaWiki\MainConfigNames;
use MediaWiki\Rest\BasicAccess\StaticBasicAuthorizer;
use MediaWiki\Rest\CorsUtils;
use MediaWiki\Rest\Handler;
use MediaWiki\Rest\Reporter\ErrorReporter;
use MediaWiki\Rest\RequestData;
use MediaWiki\Rest\RequestInterface;
use MediaWiki\Rest\ResponseFactory;
use MediaWiki\Rest\Router;
use MediaWiki\Rest\StringStream;
use MediaWiki\Rest\Validator\JsonBodyValidator;
use MediaWiki\User\UserIdentityValue;
use MediaWikiUnitTestCase;
use PHPUnit\Framework\Assert;
use PHPUnit\Framework\MockObject\MockObject;
use RuntimeException;
use Throwable;
use Wikimedia\ParamValidator\ParamValidator;
use Wikimedia\TestingAccessWrapper;

/**
 * @covers \MediaWiki\Rest\Router
 */
class RouterTest extends MediaWikiUnitTestCase {
	use RestTestTrait;

	private const CANONICAL_SERVER = 'https://wiki.example.com';
	private const INTERNAL_SERVER = 'http://api.local:8080';

	/** @var Throwable[] */
	private $reportedErrors = [];

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
			MainConfigNames::RestPath => '/rest'
		];

		return $this->newRouter( [
			'routeFiles' => $routeFiles,
			'request' => $request,
			'config' => $config,
			'cacheBag' => $this->cacheBag,
			'errorReporter' => $mockErrorReporter,
			'basicAuth' => new StaticBasicAuthorizer( $authError ),
		] );
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

	public function testHttpException() {
		$request = new RequestData( [ 'uri' => new Uri( '/rest/mock/v1/RouterTest/throw' ) ] );
		$router = $this->createRouter( $request );
		$response = $router->execute( $request );
		$this->assertSame( 555, $response->getStatusCode() );
		$body = $response->getBody();
		$body->rewind();
		$data = json_decode( $body->getContents(), true );
		$this->assertSame( 'Mock error', $data['message'] );
	}

	public function testFatalException() {
		$request = new RequestData( [ 'uri' => new Uri( '/rest/mock/v1/RouterTest/fatal' ) ] );
		$router = $this->createRouter( $request );
		$response = $router->execute( $request );
		$this->assertSame( 500, $response->getStatusCode() );
		$body = $response->getBody();
		$body->rewind();
		$data = json_decode( $body->getContents(), true );
		$this->assertStringContainsString( 'RuntimeException', $data['message'] );
		$this->assertNotEmpty( $this->reportedErrors );
		$this->assertInstanceOf( RuntimeException::class, $this->reportedErrors[0] );
	}

	public function testRedirectException() {
		$request = new RequestData( [ 'uri' => new Uri( '/rest/mock/v1/RouterTest/throwRedirect' ) ] );
		$router = $this->createRouter( $request );
		$response = $router->execute( $request );
		$this->assertSame( 301, $response->getStatusCode() );
		$this->assertSame( 'http://example.com', $response->getHeaderLine( 'Location' ) );
	}

	public function testRedirectDefinition() {
		// This route is defined in testRoutes.json without specifying a class or factory.
		$request = new RequestData( [ 'uri' => new Uri( '/rest/mock/v1/RouterTest/redirect' ) ] );
		$router = $this->createRouter( $request );
		$response = $router->execute( $request );
		$this->assertSame( 308, $response->getStatusCode() );
		$this->assertSame( '/rest/mock/RouterTest/redirectTarget', $response->getHeaderLine( 'Location' ) );
	}

	public function testResponseException() {
		$request = new RequestData( [ 'uri' => new Uri( '/rest/mock/v1/RouterTest/throwWrapped' ) ] );
		$router = $this->createRouter( $request );
		$response = $router->execute( $request );
		$this->assertSame( 200, $response->getStatusCode() );
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
		$this->assertSame( [ 'mock/v1', '' ], $router->getModuleNames() );

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

	public static function streamHandlerFactory() {
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

	public static function oldBodyValidatorFactory() {
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

	public function testHandlerCanAccessParsedBodyForJsonRequest() {
		$request = new RequestData( [
			'uri' => new Uri( '/rest/mock/v1/RouterTest/echo' ),
			'method' => 'POST',
			'bodyContents' => '{"bodyParam":"bar"}',
			'headers' => [ "content-type" => 'application/json' ]
		] );
		$router = $this->createRouter( $request );
		$response = $router->execute( $request );
		$this->assertSame( 200, $response->getStatusCode() );

		// Check if the response contains a field called 'parsedBody'
		$body = $response->getBody();
		$body->rewind();
		$data = json_decode( $body->getContents(), true );
		$this->assertArrayHasKey( 'parsedBody', $data );

		// Check the value of the 'parsedBody' field
		$parsedBody = $data['parsedBody'];
		$this->assertEquals( [ 'bodyParam' => 'bar' ], $parsedBody );
	}

	public function testHandlerCanAccessValidatedBodyForJsonRequest() {
		$request = new RequestData( [
			'uri' => new Uri( '/rest/mock/v1/RouterTest/echo' ),
			'method' => 'POST',
			'bodyContents' => '{"bodyParam":"bar"}',
			'headers' => [ "content-type" => 'application/json' ]
		] );
		$router = $this->createRouter( $request );
		$response = $router->execute( $request );
		$this->assertSame( 200, $response->getStatusCode(), (string)$response->getBody() );

		// Check if the response contains a field called 'validatedBody'
		$body = $response->getBody();
		$body->rewind();
		$data = json_decode( $body->getContents(), true );
		$this->assertArrayHasKey( 'validatedBody', $data );

		// Check the value of the 'validatedBody' field
		$validatedBody = $data['validatedBody'];
		$this->assertEquals( [ 'bodyParam' => 'bar' ], $validatedBody );

		// Check the value of the 'validatedParams' field.
		// It should not contain bodyParam.
		$validatedParams = $data['validatedParams'];
		$this->assertArrayNotHasKey( 'bodyParam', $validatedParams );
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

	/**
	 * @return CorsUtils
	 */
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
