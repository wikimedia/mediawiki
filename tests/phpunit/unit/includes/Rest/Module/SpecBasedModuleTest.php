<?php

namespace MediaWiki\Tests\Rest\Module;

use GuzzleHttp\Psr7\Uri;
use MediaWiki\MainConfigNames;
use MediaWiki\Rest\BasicAccess\StaticBasicAuthorizer;
use MediaWiki\Rest\Module\SpecBasedModule;
use MediaWiki\Rest\Reporter\ErrorReporter;
use MediaWiki\Rest\RequestData;
use MediaWiki\Rest\RequestInterface;
use MediaWiki\Rest\ResponseFactory;
use MediaWiki\Rest\Validator\Validator;
use MediaWiki\Tests\Rest\RestTestTrait;
use MediaWiki\Tests\Unit\DummyServicesTrait;
use PHPUnit\Framework\MockObject\MockObject;
use RuntimeException;
use Throwable;
use Wikimedia\Stats\StatsFactory;
use Wikimedia\Timestamp\ConvertibleTimestamp;

/**
 * @covers \MediaWiki\Rest\Module\SpecBasedModule
 */
class SpecBasedModuleTest extends \MediaWikiUnitTestCase {
	use RestTestTrait;
	use DummyServicesTrait;

	private const CANONICAL_SERVER = 'https://wiki.example.com';
	private const INTERNAL_SERVER = 'http://api.local:8080';

	/** @var Throwable[] */
	private $reportedErrors = [];

	/**
	 * @param RequestInterface $request
	 * @param string|null $authError
	 * @param bool $deprecated
	 *
	 * @return SpecBasedModule
	 */
	private function createOpenApiModule(
		RequestInterface $request,
		$authError = null,
		$deprecated = false
	) {
		$specFile = __DIR__ . ( $deprecated ? '/deprecatedModuleTestRoutes.json' : '/moduleTestRoutes.json' );

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

		$auth = new StaticBasicAuthorizer( $authError );
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

		$formatter = $this->getDummyTextFormatter( true );
		$responseFactory = new ResponseFactory( [ 'qqx' => $formatter ] );
		$responseFactory->setShowExceptionDetails( true );

		$module = new SpecBasedModule(
			$specFile,
			$router,
			'test.v1',
			$responseFactory,
			$auth,
			$objectFactory,
			$validator,
			$mockErrorReporter,
			$this->createHookContainer()
		);

		return $module;
	}

	public function testHandlerConfig() {
		$request = new RequestData( [
			'uri' => new Uri( '/rest/test.v1/ModuleTest/hello/you' ),
		] );
		$module = $this->createOpenApiModule( $request );

		ConvertibleTimestamp::setFakeTime( '20110401090000' );
		$statsHelper = StatsFactory::newUnitTestingHelper();
		$module->setStats( $statsHelper->getStatsFactory() );

		$response = $module->execute( '/ModuleTest/hello/you', $request );

		$this->assertSame( 200, $response->getStatusCode(), (string)$response->getBody() );

		// "hi!" comes from the route definition, the default is 'Hello!'.
		$data = json_decode( $response->getBody(), true );
		$this->assertSame( 'hi!', $data['message'] );
		$this->assertSame( [
			'mediawiki.rest_api_latency_seconds:1|ms|#path:test_v1_ModuleTest_hello_name,method:GET,status:200',
			'mediawiki.rest_api_modules_hit_total:1|c|#api_type:REST_API,api_module:test_v1,api_endpoint:MediaWiki_Tests_Rest_Handler_HelloHandler,path:test_v1_ModuleTest_hello_name,method:GET,status:200',
			'mediawiki.rest_api_modules_latency:1|ms|#api_type:REST_API,api_module:test_v1,api_endpoint:MediaWiki_Tests_Rest_Handler_HelloHandler,path:test_v1_ModuleTest_hello_name,method:GET,status:200'
		], $statsHelper->consumeAllFormatted() );
	}

	public function testWrongMethod() {
		$request = new RequestData( [
			'uri' => new Uri( '/rest/test.v1/ModuleTest/hello/you' ),
			'method' => 'TRACE'
		] );
		$module = $this->createOpenApiModule( $request );
		$response = $module->execute( '/ModuleTest/hello/you', $request );
		$this->assertSame( 405, $response->getStatusCode() );
		$this->assertSame( 'Method Not Allowed', $response->getReasonPhrase() );
		$this->assertSame( 'HEAD, GET', $response->getHeaderLine( 'Allow' ) );
	}

	public function testHeadToGet() {
		$request = new RequestData( [
			'uri' => new Uri( '/rest/test.v1/ModuleTest/hello/you' ),
			'method' => 'HEAD'
		] );
		$module = $this->createOpenApiModule( $request );
		$response = $module->execute( '/ModuleTest/hello/you', $request );
		$this->assertSame( 200, $response->getStatusCode() );
	}

	public function testRedirect() {
		$request = new RequestData( [
			'uri' => new Uri( '/rest/test.v1/ModuleTest/HELLO/you' )
		] );
		$module = $this->createOpenApiModule( $request );
		$response = $module->execute( '/ModuleTest/HELLO/you', $request );
		$this->assertSame(
			308,
			$response->getStatusCode(),
			(string)$response->getBody()
		);
	}

	public function testNoMatch() {
		$request = new RequestData( [ 'uri' => new Uri( '/rest/test.v1/Bogus' ) ] );
		$module = $this->createOpenApiModule( $request );
		$response = $module->execute( '/Bogus', $request );
		$this->assertSame( 404, $response->getStatusCode() );
		// TODO: add more information to the response body and test for its presence here
	}

	public function testHttpException() {
		$request = new RequestData( [ 'uri' => new Uri( '/rest/test.v1/ModuleTest/throw' ) ] );
		$module = $this->createOpenApiModule( $request );

		$statsHelper = StatsFactory::newUnitTestingHelper();
		$module->setStats( $statsHelper->getStatsFactory() );

		ConvertibleTimestamp::setFakeTime( '20110401090000' );
		$response = $module->execute( '/ModuleTest/throw', $request );
		$body = $response->getBody();
		$body->rewind();
		$data = json_decode( $body->getContents(), true );
		ConvertibleTimestamp::setFakeTime( '20110401090000' );

		$this->assertSame( 555, $response->getStatusCode() );
		$this->assertSame( 'Mock error', $data['message'] );

		// Metrics
		$metrics = $statsHelper->consumeAllFormatted();
		$this->assertSame(
			'mediawiki.rest_api_errors_total:1|c|#path:test_v1_ModuleTest_throw,method:GET,status:555',
			$metrics[0]
		);

		// Handler class is mocked so we need to allow for a dynamic class name
		$this->assertMatchesRegularExpression(
			'/mediawiki\.rest_api_modules_hit_total:1|c|#api_type:REST_API,api_module:test_v1,api_endpoint:MediaWiki_Rest_Handler_anonymous_var_www_html_w_tests_phpunit_unit_includes_Rest_MockHandlerFactory_php_(a-Z0-9_)+,path:test_v1_ModuleTest_throw,method:GET,status:555/',
			$metrics[1]
		);
		$this->assertMatchesRegularExpression(
			'/mediawiki\.rest_api_modules_latency:1|ms|#api_type:REST_API,api_module:test_v1,api_endpoint:MediaWiki_Rest_Handler_anonymous_var_www_html_w_tests_phpunit_unit_includes_Rest_MockHandlerFactory_php_(a-Z0-9_)+,path:test_v1_ModuleTest_throw,method:GET,status:555/',
			$metrics[2]
		);
	}

	public function testFatalException() {
		$request = new RequestData( [ 'uri' => new Uri( '/rest/test.v1/ModuleTest/fatal' ) ] );
		$module = $this->createOpenApiModule( $request );
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
		$request = new RequestData( [ 'uri' => new Uri( '/rest/test.v1/ModuleTest/throwRedirect' ) ] );
		$module = $this->createOpenApiModule( $request );
		$response = $module->execute( '/ModuleTest/throwRedirect', $request );
		$this->assertSame( 301, $response->getStatusCode() );
		$this->assertSame( 'http://example.com', $response->getHeaderLine( 'Location' ) );
	}

	public function testResponseException() {
		$request = new RequestData( [ 'uri' => new Uri( '/rest/test.v1/ModuleTest/throwWrapped' ) ] );
		$module = $this->createOpenApiModule( $request );
		$response = $module->execute( '/ModuleTest/throwWrapped', $request );
		$this->assertSame( 200, $response->getStatusCode() );
	}

	public function testBasicAccess() {
		// Using the throwing handler is a way to assert that the handler is not executed
		$request = new RequestData( [ 'uri' => new Uri( '/rest/test.v1/ModuleTest/throw' ) ] );
		$module = $this->createOpenApiModule( $request, 'test-error', [] );
		$response = $module->execute( '/ModuleTest/throw', $request );
		$this->assertSame( 403, $response->getStatusCode() );
		$body = $response->getBody();
		$body->rewind();
		$data = json_decode( $body->getContents(), true );
		$this->assertSame( 'test-error', $data['error'] );
	}

	public function testOpenApiInfo() {
		$request = new RequestData( [ 'uri' => new Uri( '/rest/test.v1/ModuleTest/hello/world' ) ] );
		$module = $this->createOpenApiModule( $request );

		$info = $module->getOpenApiInfo();
		$this->assertSame( 'test', $info['title'] );
		$this->assertSame( '1.0', $info['version'] );

		$handler = $module->getHandlerForPath( '/ModuleTest/hello/world', $request );
		$oas = $handler->getOpenApiSpec( 'GET' );

		$this->assertSame( 'hello summary', $oas['summary'] );
		$this->assertSame( '<message key="rest-endpoint-desc-mock-desc"></message>', $oas['description'] );
	}

	public function testEndpointDeprecationOpenAPISpec() {
		$request = new RequestData( [ 'uri' => new Uri( '/rest/test.v1/ModuleTest/deprecated' ) ] );
		$module = $this->createOpenApiModule( $request );

		$handler = $module->getHandlerForPath( '/ModuleTest/deprecated', $request );
		$oas = $handler->getOpenApiSpec( 'GET' );

		$this->assertSame( true, $oas['deprecated'] );
	}

	public function testEndpointDeprecationHeader() {
		$request = new RequestData( [ 'uri' => new Uri( '/rest/test.v1/ModuleTest/deprecated' ) ] );
		$module = $this->createOpenApiModule( $request );
		$response = $module->execute( '/ModuleTest/deprecated', $request );

		$this->assertSame( 200, $response->getStatusCode(), (string)$response->getBody() );
		$responseHeaders = $response->getHeaders();
		$this->assertArrayHasKey( 'Deprecation', $responseHeaders );
		$this->assertSame( '@1735689600', $responseHeaders['Deprecation'][0] );
	}

	public function testModuleDeprecationOpenAPISpec() {
		$request = new RequestData( [ 'uri' => new Uri( '/rest/test.v1/ModuleTest/hello/you' ) ] );
		$module = $this->createOpenApiModule( $request, null, true );

		$handler = $module->getHandlerForPath( '/ModuleTest/hello/you', $request );
		$oas = $handler->getOpenApiSpec( 'GET' );

		$this->assertSame( true, $oas['deprecated'] );
	}

	public function testModuleDeprecationHeaders() {
		$request = new RequestData( [ 'uri' => new Uri( '/rest/test.v1/ModuleTest/hello/you' ) ] );
		$module = $this->createOpenApiModule( $request, null, true );
		$response = $module->execute( '/ModuleTest/hello/you', $request );

		$this->assertSame( 200, $response->getStatusCode(), (string)$response->getBody() );
		$responseHeaders = $response->getHeaders();
		$this->assertArrayHasKey( 'Deprecation', $responseHeaders );
		$this->assertSame( '@1735689600', $responseHeaders['Deprecation'][0] );
	}

	public function testLoadModuleDefinition() {
		$specFile = __DIR__ . '/moduleTestRoutes.json';
		$formatter = $this->getDummyTextFormatter( true );
		$responseFactory = new ResponseFactory( [ 'qqx' => $formatter ] );

		$moduleDef = SpecBasedModule::loadModuleDefinition( $specFile, $responseFactory );

		$this->assertSame( 'test.v1', $moduleDef['moduleId'] );
		$this->assertSame( 'test', $moduleDef['info']['title'] );
		$this->assertArrayHasKey( 'paths', $moduleDef );
	}
}
