<?php

namespace MediaWiki\Tests\Rest\Handler;

use MediaWiki\Rest\ConditionalHeaderUtil;
use MediaWiki\Rest\Handler;
use MediaWiki\Rest\HttpException;
use MediaWiki\Rest\LocalizedHttpException;
use MediaWiki\Rest\RequestData;
use MediaWiki\Rest\Response;
use MediaWiki\Rest\ResponseFactory;
use MediaWiki\Rest\ResponseInterface;
use MediaWiki\Rest\Router;
use MediaWiki\Rest\Validator\BodyValidator;
use MediaWiki\Rest\Validator\Validator;
use PHPUnit\Framework\Assert;
use PHPUnit\Framework\MockObject\MockObject;
use Wikimedia\Message\MessageValue;
use Wikimedia\ParamValidator\ParamValidator;
use Wikimedia\TestingAccessWrapper;
use Wikimedia\Timestamp\ConvertibleTimestamp;

/**
 * @covers \MediaWiki\Rest\Handler\SearchHandler
 */
class HandlerTest extends \MediaWikiUnitTestCase {

	use HandlerTestTrait;

	/**
	 * @param string[] $methods
	 *
	 * @return Handler|MockObject
	 */
	private function newHandler( $methods = [] ) {
		$methods = array_merge( $methods, [ 'execute' ] );
		/** @var Handler|MockObject $handler */
		$handler = $this->getMockBuilder( Handler::class )
			->onlyMethods( $methods )
			->getMock();
		$handler->method( 'execute' )->willReturn( (object)[] );

		return $handler;
	}

	public function testGetRouter() {
		$handler = $this->newHandler();
		$this->initHandler( $handler, new RequestData() );

		$handler = TestingAccessWrapper::newFromObject( $handler );
		$this->assertInstanceOf( Router::class, $handler->getRouter() );
	}

	public function provideGetRouteUrl() {
		yield 'empty' => [
			'/test',
			[],
			[],
			'/test'
		];
		yield 'path params' => [
			'/test/{foo}/{bar}',
			[ 'foo' => 'Kittens', 'bar' => 'mew' ],
			[],
			'/test/Kittens/mew'
		];
		yield 'missing path params' => [
			'/test/{foo}/{bar}',
			[ 'bar' => 'mew' ],
			[],
			'/test/{foo}/mew'
		];
		yield 'path param encoding' => [
			'/test/{foo}',
			[ 'foo' => 'ä/+/&/?/{}/#/%' ],
			[],
			'/test/%C3%A4%2F%2B%2F%26%2F%3F%2F%7B%7D%2F%23%2F%25'
		];
		yield 'recursive path params' => [
			'/test/{foo}/{bar}',
			[ 'foo' => '{bar}', 'bar' => 'mew' ],
			[],
			'/test/%7Bbar%7D/mew'
		];
		yield 'query params' => [
			'/test',
			[],
			[ 'foo' => 'Kittens', 'bar' => 'mew' ],
			'/test?foo=Kittens&bar=mew'
		];
		yield 'query param encoding' => [
			'/test',
			[],
			[ 'foo' => 'ä/+/&/?/{}/#/%' ],
			'/test?foo=%C3%A4%2F%2B%2F%26%2F%3F%2F%7B%7D%2F%23%2F%25'
		];
	}

	/**
	 * @dataProvider provideGetRouteUrl
	 *
	 * @param string $path
	 * @param string[] $pathParams
	 * @param string[] $queryParams
	 * @param string $expected
	 */
	public function testGetRouteUrl( $path, $pathParams, $queryParams, $expected ) {
		$handler = $this->newHandler();
		$request = new RequestData();
		$this->initHandler( $handler, $request, [ 'path' => $path ] );
		$handler = TestingAccessWrapper::newFromObject( $handler );
		$url = $handler->getRouteUrl( $pathParams, $queryParams );
		$this->assertStringEndsWith( $expected, $url );
	}

	public function testGetPath() {
		$handler = $this->newHandler();
		$request = new RequestData();
		$this->initHandler( $handler, $request, [ 'path' => 'just/some/path' ] );
		$this->assertSame( 'just/some/path', $handler->getPath() );
	}

	public function testGetResponseFactory() {
		$handler = $this->newHandler();
		$this->initHandler( $handler, new RequestData() );

		$this->assertInstanceOf( ResponseFactory::class, $handler->getResponseFactory() );
	}

	public function testGetConditionalHeaderUtil() {
		$handler = $this->newHandler();
		$this->initHandler( $handler, new RequestData() );

		$handler = TestingAccessWrapper::newFromObject( $handler );
		$this->assertInstanceOf( ConditionalHeaderUtil::class, $handler->getConditionalHeaderUtil() );
	}

	public function provideCheckPreconditions() {
		yield 'no status' => [ null ];
		yield 'a status' => [ 444 ];
	}

	/**
	 * @dataProvider provideCheckPreconditions
	 */
	public function testCheckPreconditions( $status ) {
		$request = new RequestData();

		$util = $this->createNoOpMock( ConditionalHeaderUtil::class, [ 'checkPreconditions' ] );
		$util->method( 'checkPreconditions' )->with( $request )->willReturn( $status );

		$handler = $this->newHandler( [ 'getConditionalHeaderUtil' ] );
		$handler->method( 'getConditionalHeaderUtil' )->willReturn( $util );

		$this->initHandler( $handler, $request );
		$resp = $handler->checkPreconditions();

		$responseStatus = $resp ? $resp->getStatusCode() : null;
		$this->assertSame( $status, $responseStatus );
	}

	public function testApplyConditionalResponseHeaders() {
		$util = $this->createNoOpMock( ConditionalHeaderUtil::class, [ 'applyResponseHeaders' ] );
		$util->method( 'applyResponseHeaders' )->willReturnCallback(
			static function ( ResponseInterface $response ) {
				$response->setHeader( 'Testing', 'foo' );
			}
		);

		$handler = $this->newHandler( [ 'getConditionalHeaderUtil' ] );
		$handler->method( 'getConditionalHeaderUtil' )->willReturn( $util );

		$this->initHandler( $handler, new RequestData() );
		$response = $handler->getResponseFactory()->create();
		$handler->applyConditionalResponseHeaders( $response );

		$this->assertSame( 'foo', $response->getHeaderLine( 'Testing' ) );
	}

	public function provideValidate() {
		yield 'empty' => [ [], new RequestData(), [] ];

		yield 'parameter' => [
			[
				'foo' => [
					ParamValidator::PARAM_TYPE => 'string',
					ParamValidator::PARAM_REQUIRED => true,
					Handler::PARAM_SOURCE => 'query',
				]
			],
			new RequestData( [ 'queryParams' => [ 'foo' => 'kittens' ] ] ),
			[ 'foo' => 'kittens' ]
		];
	}

	/**
	 * @dataProvider provideValidate
	 */
	public function testValidate( $paramSettings, $request, $expected ) {
		$handler = $this->newHandler( [ 'getParamSettings' ] );
		$handler->method( 'getParamSettings' )->willReturn( $paramSettings );

		$this->initHandler( $handler, $request );
		$this->validateHandler( $handler );

		$params = $handler->getValidatedParams();
		$this->assertSame( $expected, $params );
	}

	public function provideValidate_invalid() {
		$paramSettings = [
			'foo' => [
				ParamValidator::PARAM_TYPE => 'string',
				ParamValidator::PARAM_REQUIRED => true,
				Handler::PARAM_SOURCE => 'query',
			]
		];

		$request = new RequestData( [ 'queryParams' => [ 'bar' => 'kittens' ] ] );

		$handler = $this->newHandler( [ 'getParamSettings' ] );
		$handler->method( 'getParamSettings' )->willReturn( $paramSettings );

		try {
			$this->initHandler( $handler, $request );
			$this->validateHandler( $handler );
			$this->fail( 'Expected LocalizedHttpException' );
		} catch ( LocalizedHttpException $ex ) {
			$this->assertSame( 'paramvalidator-missingparam', $ex->getMessageValue()->getKey() );
		}
	}

	public function testGetValidatedBody() {
		$validator = $this->createMock( Validator::class );
		$validator->method( 'validateBody' )->willReturn( 'VALIDATED BODY' );

		$handler = $this->newHandler();
		$this->initHandler( $handler, new RequestData() );
		$handler->validate( $validator );

		$body = $handler->getValidatedBody();
		$this->assertSame( 'VALIDATED BODY', $body );
	}

	public function testGetRequest() {
		$handler = $this->newHandler();
		$request = new RequestData();
		$this->initHandler( $handler, $request );

		$this->assertSame( $request, $handler->getRequest() );
	}

	public function testGetConfig() {
		$handler = $this->newHandler();
		$config = [ 'foo' => 'bar' ];
		$this->initHandler( $handler, new RequestData(), $config );

		$this->assertSame( $config, $handler->getConfig() );
	}

	public function testGetBodyValidator() {
		$handler = $this->newHandler();
		$this->assertInstanceOf(
			BodyValidator::class,
			$handler->getBodyValidator( 'unknown/unknown' )
		);
	}

	public function testThatGetParamSettingsReturnsNothingPerDefault() {
		$handler = $this->newHandler();
		$this->assertSame( [], $handler->getParamSettings() );
	}

	public function testThatGetLastModifiedReturnsNullPerDefault() {
		$handler = $this->newHandler();

		$handler = TestingAccessWrapper::newFromObject( $handler );
		$this->assertNull( $handler->getLastModified() );
	}

	public function testThatGetETagReturnsNullPerDefault() {
		$handler = $this->newHandler();

		$handler = TestingAccessWrapper::newFromObject( $handler );
		$this->assertNull( $handler->getETag() );
	}

	public function testThatHasRepresentationReturnsNullPerDefault() {
		$handler = $this->newHandler();

		$handler = TestingAccessWrapper::newFromObject( $handler );
		$this->assertNull( $handler->hasRepresentation() );
	}

	public function testThatNeedsReadAccessReturnsTruePerDefault() {
		$handler = $this->newHandler();

		$handler = TestingAccessWrapper::newFromObject( $handler );
		$this->assertTrue( $handler->needsReadAccess() );
	}

	public function testThatNeedsWriteAccessReturnsTruePerDefault() {
		$handler = $this->newHandler();

		$handler = TestingAccessWrapper::newFromObject( $handler );
		$this->assertTrue( $handler->needsWriteAccess() );
	}

	public function testBodyValidation_extraneousToken() {
		$requestData = [
			'method' => 'POST',
			'pathParams' => [ 'title' => 'Foo' ],
			'headers' => [
				'Content-Type' => 'application/json',
			],
			'bodyContents' => json_encode( [
				'title' => 'Foo',
				'token' => 'TOKEN',
				'comment' => 'Testing',
				'source' => 'Lorem Ipsum',
				'content_model' => 'wikitext'
			] ),
		];

		$request = new RequestData( $requestData );

		$handler = $this->newHandler();
		$this->initHandler( $handler, $request, [], [], null, $this->getSession( true ) );

		$validator = $this->getMockValidator( [], [ 'token' => 'TOKEN' ] );
		$handler->validate( $validator );

		try {
			$handler->checkSession();
			Assert::fail( 'Expected a LocalizedHttpException to be thrown' );
		} catch ( HttpException $ex ) {
		}

		$this->assertSame( 400, $ex->getCode(), 'HTTP status' );
		$this->assertInstanceOf( LocalizedHttpException::class, $ex );

		$expectedMessage = new MessageValue( 'rest-extraneous-csrf-token' );
		$this->assertEquals( $expectedMessage, $ex->getMessageValue() );

		$this->assertTrue( $handler->getSession()->getProvider()->safeAgainstCsrf() );
	}

	public function testCsrfUnsafeSessionProviderRejection() {
		$handler = $this->newHandler( [ 'requireSafeAgainstCsrf' ] );
		$handler->method( 'requireSafeAgainstCsrf' )->willReturn( true );
		$this->initHandler( $handler, new RequestData(), [], [], null, $this->getSession( false ) );

		try {
			$handler->checkSession();
			Assert::fail( 'Expected a LocalizedHttpException to be thrown' );
		} catch ( HttpException $ex ) {
		}

		$this->assertSame( 400, $ex->getCode(), 'HTTP status' );
		$this->assertInstanceOf( LocalizedHttpException::class, $ex );

		$expectedMessage = new MessageValue( 'rest-requires-safe-against-csrf' );
		$this->assertEquals( $expectedMessage, $ex->getMessageValue() );

		$this->assertFalse( $handler->getSession()->getProvider()->safeAgainstCsrf() );
	}

	public function testThatVerifierHeadersAreLoopedThroughForGet() {
		$handler = $this->newHandler( [ 'getETag', 'getLastModified' ] );
		$handler->method( 'getETag' )->willReturn( '"TEST"' );
		$handler->method( 'getLastModified' )->willReturn( '20220101223344' );

		$params = [ 'method' => 'GET' ];
		$this->initHandler( $handler, new RequestData( $params ) );
		$handler->checkPreconditions();

		$response = new Response();
		$handler->applyConditionalResponseHeaders( $response );
		$this->assertSame( '"TEST"', $response->getHeaderLine( 'ETag' ) );

		$lastModified = ConvertibleTimestamp::convert( TS_MW, $response->getHeaderLine( 'Last-Modified' ) );
		$this->assertSame( '20220101223344', $lastModified );
	}

	public function testThatVerifierHeadersAreNotLoopedThroughForPost() {
		$handler = $this->newHandler( [ 'getETag', 'getLastModified' ] );
		$handler->method( 'getETag' )->willReturn( '"TEST"' );
		$handler->method( 'getLastModified' )->willReturn( '20220101223344' );

		$params = [ 'method' => 'POST' ];
		$this->initHandler( $handler, new RequestData( $params ) );
		$handler->checkPreconditions();

		$response = new Response();
		$handler->applyConditionalResponseHeaders( $response );
		$this->assertSame( '', $response->getHeaderLine( 'ETag' ) );
		$this->assertSame( '', $response->getHeaderLine( 'Last-Modified' ) );
	}

	public function provideCacheControl() {
		yield 'nothing' => [
			'GET',
			[],
			''
		];

		yield 'cookie' => [
			'GET',
			[
				'Set-Cookie' => 'foo=bar',
				'Cache-Control' => 'max-age=123'
			],
			'private,no-cache,s-maxage=0'
		];

		yield 'POST with cache control' => [
			'POST',
			[
				'Cache-Control' => 'max-age=123'
			],
			'max-age=123'
		];

		yield 'POST use default cache control' => [
			'POST',
			[],
			'private,no-cache,s-maxage=0'
		];
	}

	/**
	 * @dataProvider provideCacheControl
	 */
	public function testCacheControl( string $method, array $headers, $expected ) {
		$response = new Response();

		foreach ( $headers as $name => $value ) {
			$response->setHeader( $name, $value );
		}

		$handler = $this->newHandler( [ 'getRequest' ] );
		$handler->method( 'getRequest' )->willReturn( new RequestData( [ 'method' => $method ] ) );

		$handler->applyCacheControl( $response );

		$this->assertSame( $expected, $response->getHeaderLine( 'Cache-Control' ) );
	}

}
