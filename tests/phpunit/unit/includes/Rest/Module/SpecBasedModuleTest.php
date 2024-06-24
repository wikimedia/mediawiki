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
use Psr\Log\NullLogger;
use RuntimeException;
use Throwable;
use UDPTransport;
use Wikimedia\Stats\OutputFormats;
use Wikimedia\Stats\StatsCache;
use Wikimedia\Stats\StatsFactory;

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
	 *
	 * @return SpecBasedModule
	 */
	private function createOpenApiModule(
		RequestInterface $request,
		$authError = null
	) {
		$specFile = __DIR__ . '/moduleTestRoutes.json';

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

		$responseFactory = new ResponseFactory( [] );
		$responseFactory->setShowExceptionDetails( true );

		$module = new SpecBasedModule(
			$specFile,
			$router,
			'test.v1',
			$responseFactory,
			$auth,
			$objectFactory,
			$validator,
			$mockErrorReporter
		);

		return $module;
	}

	private function createMockStatsFactory( string $expectedPattern ): StatsFactory {
		$statsCache = new StatsCache();
		$emitter = OutputFormats::getNewEmitter(
			'mediawiki',
			$statsCache,
			OutputFormats::getNewFormatter( OutputFormats::DOGSTATSD )
		);

		$transport = $this->createMock( UDPTransport::class );

		$transport->expects( $this->once() )->method( "emit" )
			->with( $this->matchesRegularExpression( $expectedPattern ) );

		$emitter = $emitter->withTransport( $transport );
		return new StatsFactory( $statsCache, $emitter, new NullLogger );
	}

	public function testHandlerConfig() {
		$request = new RequestData( [
			'uri' => new Uri( '/rest/test.v1/ModuleTest/hello/you' ),
		] );
		$module = $this->createOpenApiModule( $request );

		$stats = $this->createMockStatsFactory(
			'/^mediawiki\.rest_api_latency_seconds:\d+\.\d+\|ms\|#path:test_v1_ModuleTest_hello_name,method:GET,status:200\n/'
		);
		$module->setStats( $stats );

		$response = $module->execute( '/ModuleTest/hello/you', $request );
		$stats->flush();

		$this->assertSame( 200, $response->getStatusCode(), (string)$response->getBody() );

		// "hi!" comes from the rout definition, the default is 'Hello!'.
		$data = json_decode( $response->getBody(), true );
		$this->assertSame( 'hi!', $data['message'] );
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

		$stats = $this->createMockStatsFactory(
			'/^mediawiki\.rest_api_errors_total:1|ms\|#path:test_v1_ModuleTest_throw,method:GET,status:555\n/'
		);
		$module->setStats( $stats );

		$response = $module->execute( '/ModuleTest/throw', $request );
		$stats->flush();

		$this->assertSame( 555, $response->getStatusCode() );
		$body = $response->getBody();
		$body->rewind();
		$data = json_decode( $body->getContents(), true );
		$this->assertSame( 'Mock error', $data['message'] );
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

}
