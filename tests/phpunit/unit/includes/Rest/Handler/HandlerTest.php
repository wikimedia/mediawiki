<?php

namespace MediaWiki\Tests\Rest\Handler;

use GuzzleHttp\Psr7\Uri;
use MediaWiki\Rest\ConditionalHeaderUtil;
use MediaWiki\Rest\Handler;
use MediaWiki\Rest\HttpException;
use MediaWiki\Rest\LocalizedHttpException;
use MediaWiki\Rest\RequestData;
use MediaWiki\Rest\RequestInterface;
use MediaWiki\Rest\Response;
use MediaWiki\Rest\ResponseFactory;
use MediaWiki\Rest\ResponseInterface;
use MediaWiki\Rest\Router;
use MediaWiki\Rest\Validator\BodyValidator;
use MediaWiki\Session\Session;
use MediaWikiUnitTestCase;
use PHPUnit\Framework\Assert;
use PHPUnit\Framework\MockObject\MockObject;
use Wikimedia\Message\MessageValue;
use Wikimedia\ParamValidator\ParamValidator;
use Wikimedia\TestingAccessWrapper;
use Wikimedia\Timestamp\ConvertibleTimestamp;

/**
 * @covers \MediaWiki\Rest\Handler\SearchHandler
 */
class HandlerTest extends MediaWikiUnitTestCase {

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

	private function initHandlerPartially( Handler $handler ) {
		$formatter = $this->getDummyTextFormatter( true );
		$responseFactory = new ResponseFactory( [ 'qqx' => $formatter ] );

		$router = $this->newRouter();
		$module = $this->newModule( [ 'router' => $router ] );

		$authority = $this->mockAnonUltimateAuthority();
		$hookContainer = $this->createHookContainer();

		$session = $this->getSession( true );
		$handler->initContext( $module, [] );
		$handler->initServices( $authority, $responseFactory, $hookContainer );
		$handler->initSession( $session );

		return $handler;
	}

	public function testGetRouter() {
		$handler = $this->newHandler();
		$this->initHandler( $handler, new RequestData() );

		$handler = TestingAccessWrapper::newFromObject( $handler );
		$this->assertInstanceOf( Router::class, $handler->getRouter() );
	}

	public static function provideGetRouteUrl() {
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

	public function testSupportedPathparams() {
		$handler = $this->newHandler();
		$request = new RequestData();
		$this->initHandler( $handler, $request, [ 'path' => 'some/path/{foo}/{bar}' ] );
		$this->assertSame( [ 'foo', 'bar' ], $handler->getSupportedPathParams() );
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

	public static function provideCheckPreconditions() {
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

	public static function provideValidate() {
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

	public static function provideValidateBodyParams() {
		yield 'no body parameter' => [
			[
				'pathfoo' => [
					ParamValidator::PARAM_TYPE => 'string',
					ParamValidator::PARAM_REQUIRED => false,
					Handler::PARAM_SOURCE => 'path',
				],
			],
			new RequestData( [ 'parsedBody' => [ 'foo' => 'kittens' ] ] ),
			[] // extra parameter should be ignored if no body param is defined
		];

		yield 'parameter' => [
			[
				'foo' => [
					ParamValidator::PARAM_TYPE => 'string',
					ParamValidator::PARAM_REQUIRED => true,
					Handler::PARAM_SOURCE => 'body',
				],
				'pathfoo' => [
					ParamValidator::PARAM_TYPE => 'string',
					ParamValidator::PARAM_REQUIRED => false,
					Handler::PARAM_SOURCE => 'path',
				],
			],
			new RequestData( [ 'parsedBody' => [ 'foo' => 'kittens' ] ] ),
			[ 'foo' => 'kittens' ]
		];
	}

	/**
	 * @dataProvider provideValidateBodyParams
	 */
	public function testValidateBodyParams( $paramSettings, $request, $expected ) {
		$handler = $this->newHandler( [ 'getParamSettings' ] );
		$handler->method( 'getParamSettings' )->willReturn( $paramSettings );

		$this->initHandler( $handler, $request );
		$this->validateHandler( $handler );

		$params = $handler->getValidatedBody();
		$this->assertSame( $expected, $params );
	}

	public function testGetBodyParamSettings() {
		$paramSettings = [
			'pathfoo' => [
				ParamValidator::PARAM_TYPE => 'string',
				Handler::PARAM_SOURCE => 'path',
			],
			'bodyfoo' => [
				ParamValidator::PARAM_TYPE => 'string',
				Handler::PARAM_SOURCE => 'body',
			],
			'queryfoo' => [
				ParamValidator::PARAM_TYPE => 'string',
				Handler::PARAM_SOURCE => 'query',
			],
		];

		$handler = $this->newHandler( [ 'getParamSettings' ] );
		$handler->method( 'getParamSettings' )->willReturn( $paramSettings );

		$bodyParamSettings = $handler->getBodyParamSettings();

		$expected = [
			'bodyfoo' => $paramSettings['bodyfoo']
		];

		$this->assertSame( $expected, $bodyParamSettings );
	}

	public function testOverrideGetBodyParamSettings() {
		$paramSettings =

		$handler = new class() extends Handler {

			public function execute() {
				return [];
			}

			public function getBodyParamSettings(): array {
				return [
					'x' => [
						ParamValidator::PARAM_TYPE => 'string',
						Handler::PARAM_SOURCE => 'body',
					],
					'y' => [
						ParamValidator::PARAM_TYPE => 'string',
						Handler::PARAM_SOURCE => 'body',
					],
				];
			}

			public function getParamSettings(): array {
				return [
					'pathfoo' => [
						ParamValidator::PARAM_TYPE => 'string',
						ParamValidator::PARAM_DEFAULT => 'foo',
						Handler::PARAM_SOURCE => 'path',
					],
					'bodyfoo' => [ // should not be used for validation
						ParamValidator::PARAM_TYPE => 'string',
						ParamValidator::PARAM_DEFAULT => 'foo',
						Handler::PARAM_SOURCE => 'body',
					],
					'queryfoo' => [
						ParamValidator::PARAM_TYPE => 'string',
						ParamValidator::PARAM_DEFAULT => 'foo',
						Handler::PARAM_SOURCE => 'query',
					],
				];
			}
		};

		$request = new RequestData( [
			'parsedBody' => [
				'x' => 'test 1',
				'y' => 'test 2',
			],
		] );

		$this->initHandler( $handler, $request );
		$this->validateHandler( $handler );

		// The validated body should contain x and y, but not bodyfoo
		$expectedBody = [
			'x' => 'test 1',
			'y' => 'test 2',
		];
		$body = $handler->getValidatedBody();
		$this->assertSame( $expectedBody, $body );

		// The validated params should contain pathfoo and queryfoo, but not bodyfoo
		$expectedParams = [
			'pathfoo' => 'foo',
			'queryfoo' => 'foo',
		];
		$params = $handler->getValidatedParams();
		$this->assertSame( $expectedParams, $params );
	}

	public function provideValidateBodyParams_invalid() {
		$paramSettings = [
			'foo' => [
				ParamValidator::PARAM_TYPE => 'string',
				ParamValidator::PARAM_REQUIRED => true,
				Handler::PARAM_SOURCE => 'body',
			]
		];

		$request = new RequestData(
			[
				'parsedBody' => [
					'foo' => 'kittens',
					'xyzzy' => 'lizzards',
				],
			]
		);

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

	public function testBodyValidator() {
		$request = new RequestData( [
			'method' => 'POST',
			'headers' => [
				'Content-Type' => 'test/test'
			],
			'bodyContents' => 'test test test'
		] );

		$bodyValidator = new class () implements BodyValidator {

			public function validateBody( RequestInterface $request ) {
				return 'VALIDATED BODY';
			}
		};

		$handler = $this->newHandler( [ 'getBodyValidator' ] );
		$handler->method( 'getBodyValidator' )->willReturn( $bodyValidator );

		$this->initHandler( $handler, $request );
		$this->validateHandler( $handler );

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

	public static function provideBodyValidationFailure() {
		yield 'extraneous token (bodyContents)' => [
			[
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
			],
			new MessageValue( 'rest-extraneous-csrf-token' )
		];
		yield 'extraneous token (parsedBody)' => [
			[
				'method' => 'POST',
				'pathParams' => [ 'title' => 'Foo' ],
				'headers' => [
					'Content-Type' => 'application/json',
				],
				'parsedBody' => [
					'title' => 'Foo',
					'token' => 'TOKEN',
					'comment' => 'Testing',
					'source' => 'Lorem Ipsum',
					'content_model' => 'wikitext'
				],
			],
			new MessageValue( 'rest-extraneous-csrf-token' )
		];
	}

	/**
	 * @dataProvider provideBodyValidationFailure
	 */
	public function testBodyValidationFailure( $requestData, $expectedMessage ) {
		$request = new RequestData( $requestData );

		$handler = $this->newHandler();
		$this->initHandler( $handler, $request, [], [], null, $this->getSession( true ) );

		// NOTE: initHandler() should be setting the parsed body if needed!
		$validator = $this->getMockValidator( [], $request->getParsedBody() );
		$handler->validate( $validator );

		// sanity check
		$this->assertTrue( $handler->getSession()->getProvider()->safeAgainstCsrf() );

		try {
			$handler->checkSession();
			Assert::fail( 'Expected a LocalizedHttpException to be thrown' );
		} catch ( HttpException $ex ) {
			$this->assertSame( 400, $ex->getCode(), 'HTTP status' );
			$this->assertInstanceOf( LocalizedHttpException::class, $ex );

			$this->assertEquals( $expectedMessage, $ex->getMessageValue() );
		}
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

	public static function provideCacheControl() {
		yield 'nothing' => [
			'GET',
			[],
			false, // no persistent session
			''
		];

		yield 'set-cookie in response' => [
			'GET',
			[
				'Set-Cookie' => 'foo=bar',
				'Cache-Control' => 'max-age=123'
			],
			false, // no persistent session
			'private,must-revalidate,s-maxage=0'
		];

		yield 'POST with cache control' => [
			'POST',
			[
				'Cache-Control' => 'max-age=123'
			],
			false, // no persistent session
			'max-age=123'
		];

		yield 'POST use default cache control' => [
			'POST',
			[],
			false, // no persistent session
			'private,no-cache,s-maxage=0'
		];

		yield 'persistent session' => [
			'GET',
			[ 'Cache-Control' => 'max-age=123' ],
			true, // persistent session
			'private,must-revalidate,s-maxage=0'
		];
	}

	/**
	 * @dataProvider provideCacheControl
	 */
	public function testCacheControl(
		string $method,
		array $headers,
		bool $hasPersistentSession,
		$expected
	) {
		$response = new Response();

		foreach ( $headers as $name => $value ) {
			$response->setHeader( $name, $value );
		}

		$session = $this->createMock( Session::class );
		$session->method( 'isPersistent' )->willReturn( $hasPersistentSession );

		$handler = $this->newHandler( [ 'getRequest', 'getSession' ] );
		$handler->method( 'getRequest' )->willReturn( new RequestData( [ 'method' => $method ] ) );
		$handler->method( 'getSession' )->willReturn( $session );

		$handler->applyCacheControl( $response );

		$this->assertSame( $expected, $response->getHeaderLine( 'Cache-Control' ) );
	}

	public static function provideParseBodyData() {
		return [
			'no body type with non-empty body' => [
				new RequestData( [
					'method' => 'POST',
					'bodyContents' => '{"foo":"bar"}',
				] ),
				new LocalizedHttpException(
					new MessageValue( 'rest-unsupported-content-type', [ '' ] ),
					415
				)
			],
			'no body type with empty body' => [
				new RequestData( [
					'headers' => [ 'content-length' => '0' ],
					'bodyContent' => '',
				] ),
				null
			],
			'json body' => [
				new RequestData( [
					'bodyContents' => '{"foo":"bar"}',
					'headers' => [ 'Content-Type' => 'application/json' ]
				] ),
				[ 'foo' => 'bar' ]
			],
			'unknown body type' => [
				new RequestData( [
					'headers' => [ 'Content-Type' => 'unknown/type' ]
				] ),
				new LocalizedHttpException(
					new MessageValue( 'rest-unsupported-content-type', [ '' ] ),
					415
				)
			],
			'form data not supported' => [
				new RequestData( [
					'headers' => [ 'Content-Type' => 'application/x-www-form-urlencoded' ],
					'postParams' => [ 'test' => 'foo' ]
				] ),
				new LocalizedHttpException(
					new MessageValue( 'rest-unsupported-content-type', [ '' ] ),
					415
				)
			],
			'malformed json body' => [
				new RequestData( [
					'bodyContents' => '{"foo":"bar"',
					'headers' => [ 'Content-Type' => 'application/json' ]
				] ),
				new LocalizedHttpException(
					new MessageValue( 'rest-json-body-parse-error', [ '' ] ),
					400
				)
			],
			'empty json body' => [
				new RequestData( [
					'bodyContents' => '',
					'headers' => [ 'Content-Type' => 'application/json' ]
				] ),
				new LocalizedHttpException(
					new MessageValue( 'rest-json-body-parse-error', [ '' ] ),
					400
				)
			],
			'non-array json body' => [
				new RequestData( [
					'bodyContents' => '"foo"',
					'headers' => [ 'Content-Type' => 'application/json' ]
				] ),
				new LocalizedHttpException(
					new MessageValue( 'rest-json-body-parse-error', [ '' ] ),
					400
				)
			],
		];
	}

	/** @dataProvider provideParseBodyData */
	public function testParseBodyData( $requestData, $expectedResult ) {
		$handler = $this->newHandler();
		if ( $expectedResult instanceof LocalizedHttpException ) {
			$this->expectException( LocalizedHttpException::class );
			$this->expectExceptionCode( $expectedResult->getCode() );
			$this->expectExceptionMessage( $expectedResult->getMessage() );
			$handler->parseBodyData( $requestData );
		} else {
			$parsedBody = $handler->parseBodyData( $requestData );
			$this->assertEquals( $expectedResult, $parsedBody );
		}
	}

	public function testGetRequestFailsWithBody() {
		$this->markTestSkipped( 'T359509' );
		$request = new RequestData( [
			'uri' => new Uri( '/rest/mock/v1/RouterTest/echo' ),
			'method' => 'GET',
			'bodyContents' => '{"foo":"bar"}',
			'headers' => [ "content-type" => 'application/json' ]
		] );
		$handler = new EchoHandler();
		$this->initHandlerPartially( $handler );

		$this->expectExceptionCode( 400 );
		$handler->initForExecute( $request );
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
		$handler = new EchoHandler();
		$this->initHandlerPartially( $handler );
		$handler->initForExecute( $request );
		$this->addToAssertionCount( 1 );
	}

	public function testPostRequestFailsWithoutBody() {
		$request = new RequestData( [
			'uri' => new Uri( '/rest/mock/v1/RouterTest/echo' ),
			'method' => 'POST',
		] );
		$handler = new EchoHandler();
		$this->initHandlerPartially( $handler );

		$this->expectExceptionCode( 411 );
		$handler->initForExecute( $request );
	}

	public function testEmptyBodyWithoutContentTypePasses() {
		$request = new RequestData( [
			'uri' => new Uri( '/rest/mock/v1/RouterTest/echo' ),
			'method' => 'POST',
			'headers' => [ 'content-length' => '0' ],
			'bodyContent' => '',
			// Should pass even without content-type!
		] );

		$handler = new EchoHandler();
		$this->initHandlerPartially( $handler );
		$handler->initForExecute( $request );
		$this->addToAssertionCount( 1 );
	}

	public function testRequestBodyWithoutContentTypeFails() {
		$request = new RequestData( [
			'uri' => new Uri( '/rest/mock/v1/RouterTest/echo' ),
			'method' => 'POST',
			'bodyContents' => '{"foo":"bar"}', // Request body without content-type
		] );
		$handler = new EchoHandler();
		$this->initHandlerPartially( $handler );

		$this->expectExceptionCode( 415 );
		$handler->initForExecute( $request );
	}

	public function testDeleteRequestWithoutBody() {
		// Test DELETE request without body
		$request = new RequestData( [
			'uri' => new Uri( '/rest/mock/v1/RouterTest/echo' ),
			'method' => 'DELETE',
		] );
		$handler = new EchoHandler();
		$this->initHandlerPartially( $handler );
		$handler->initForExecute( $request );
		$this->addToAssertionCount( 1 );
	}

	public function testDeleteRequestWithBody() {
		// Test DELETE request with body
		$request = new RequestData( [
			'uri' => new Uri( '/rest/mock/v1/RouterTest/echo' ),
			'method' => 'DELETE',
			'bodyContents' => '{"bodyParam":"bar"}',
			'headers' => [ "content-type" => 'application/json' ]
		] );
		$handler = new EchoHandler();
		$this->initHandlerPartially( $handler );
		$handler->initForExecute( $request );
		$this->addToAssertionCount( 1 );
	}

	public function testUnsupportedContentTypeReturns415() {
		$request = new RequestData( [
			'uri' => new Uri( '/rest/mock/v1/RouterTest/echo' ),
			'method' => 'POST',
			'bodyContents' => '{"foo":"bar"}',
			'headers' => [ "content-type" => 'text/plain' ] // Unsupported content type
		] );
		$handler = new EchoHandler();
		$this->initHandlerPartially( $handler );

		$this->expectExceptionCode( 415 );
		$handler->initForExecute( $request );
	}

	public function testHandlerCanAccessParsedBodyForJsonRequest() {
		$request = new RequestData( [
			'uri' => new Uri( '/rest/mock/v1/RouterTest/echo' ),
			'method' => 'POST',
			'bodyContents' => '{"bodyParam":"bar"}',
			'headers' => [ "content-type" => 'application/json' ]
		] );
		$handler = new EchoHandler();
		$this->initHandler( $handler, $request );
		$this->validateHandler( $handler );
		$data = $handler->execute();

		// Check if the response contains a field called 'parsedBody'
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
		$handler = new EchoHandler();
		$this->initHandler( $handler, $request );
		$this->validateHandler( $handler );
		$data = $handler->execute();

		// Check if the response contains a field called 'validatedBody'
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
			'pathParams' => [ 'pathParam' => 'bar' ],
			'method' => 'POST',
			'headers' => [ "content-type" => 'application/json' ],
			'bodyContents' => '{}'
		] );
		$handler = new EchoHandler();
		$this->initHandler( $handler, $request );
		$this->validateHandler( $handler );
		$data = $handler->execute();

		// Check if the response contains a field called 'pathParams'
		$this->assertArrayHasKey( 'validatedParams', $data );

		// Check the value of the 'pathParams' field
		$validatedParams = $data['validatedParams'];
		$this->assertEquals( 'bar', $validatedParams[ 'pathParam' ] );
	}

	/**
	 * Assert that getSupportedRequestTypes() will detect that "post" parameters
	 * are declared, so form data is allowed.
	 */
	public function testGetSupportedRequestTypes_post() {
		$paramSettings = [
			'test' => [
				Handler::PARAM_SOURCE => 'post',
			]
		];
		$handler = $this->newHandler( [ 'getParamSettings' ] );
		$handler->method( 'getParamSettings' )->willReturn( $paramSettings );

		$supportedTypes = $handler->getSupportedRequestTypes();

		$this->assertContains( 'application/x-www-form-urlencoded', $supportedTypes );
		$this->assertContains( 'multipart/form-data', $supportedTypes );
		$this->assertContains( 'application/json', $supportedTypes );

		// Should accept form data
		$request = new RequestData( [
			'headers' => [ 'Content-Type' => 'application/x-www-form-urlencoded' ],
			'postParams' => [ 'test' => 'foo' ]
		] );
		$handler->parseBodyData( $request );

		// The "post" parameter should be processed as "parameter", not as "body".
		$this->initHandler( $handler, $request );
		$this->validateHandler( $handler );

		$this->assertArrayNotHasKey( 'test', $handler->getValidatedBody() );
		$this->assertArrayHasKey( 'test', $handler->getValidatedParams() );
	}

	/**
	 * Assert that getSupportedRequestTypes() can be overwritten
	 * to allow form data.
	 */
	public function testGetSupportedRequestTypes_body() {
		$paramSettings = [
			'test' => [
				Handler::PARAM_SOURCE => 'body',
			]
		];

		$supportedTypes = [
			'application/x-www-form-urlencoded'
		];

		$handler = $this->newHandler( [ 'getParamSettings', 'getSupportedRequestTypes' ] );
		$handler->method( 'getParamSettings' )->willReturn( $paramSettings );
		$handler->method( 'getSupportedRequestTypes' )->willReturn( $supportedTypes );

		// Should accept form data
		$request = new RequestData( [
			'headers' => [ 'Content-Type' => 'application/x-www-form-urlencoded' ],
			'postParams' => [ 'test' => 'foo' ]
		] );
		$handler->parseBodyData( $request );

		// The "body" parameter should be processed as "body", not as "parameter".
		$this->initHandler( $handler, $request );
		$this->validateHandler( $handler );

		$this->assertArrayHasKey( 'test', $handler->getValidatedBody() );
		$this->assertArrayNotHasKey( 'test', $handler->getValidatedParams() );
	}

}
