<?php

namespace MediaWiki\Tests\Rest\Handler;

use GuzzleHttp\Psr7\Uri;
use MediaWiki\ParamValidator\TypeDef\ArrayDef;
use MediaWiki\Rest\ConditionalHeaderUtil;
use MediaWiki\Rest\Handler;
use MediaWiki\Rest\HttpException;
use MediaWiki\Rest\LocalizedHttpException;
use MediaWiki\Rest\Module\Module;
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
 * @covers \MediaWiki\Rest\Handler
 */
class HandlerTest extends MediaWikiUnitTestCase {

	use HandlerTestTrait;

	/**
	 * @param string[] $methods
	 *
	 * @return Handler&MockObject
	 */
	private function newHandler( $methods = [] ) {
		$methods = array_merge( $methods, [ 'execute' ] );
		/** @var Handler&MockObject $handler */
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
		$handler->initContext( $module, 'test', [] );
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

		$util = $this->createNoOpMock(
			ConditionalHeaderUtil::class,
			[ 'checkPreconditions', 'applyResponseHeaders', ]
		);
		$util->method( 'checkPreconditions' )->with( $request )->willReturn( $status );
		$util->method( 'applyResponseHeaders' )->willReturnCallback(
			static function ( ResponseInterface $response ) {
				$response->setHeader( 'etag', '"the-etag"' );
			}
		);

		$handler = $this->newHandler( [ 'getConditionalHeaderUtil' ] );
		$handler->method( 'getConditionalHeaderUtil' )->willReturn( $util );

		$this->initHandler( $handler, $request );
		$resp = $handler->checkPreconditions();

		if ( $status ) {
			$this->assertNotNull( $resp );
			$responseStatus = $resp->getStatusCode();
			$this->assertSame( $status, $responseStatus );

			// T357603: Response must include the Etag as specified in
			// https://datatracker.ietf.org/doc/html/rfc9110#name-304-not-modified
			$this->assertSame( '"the-etag"', $resp->getHeaderLine( 'etag' ) );
		} else {
			$this->assertNull( $resp );
		}
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
		yield 'empty' => [ [], [], new RequestData(), [], [] ];

		yield 'query parameter' => [
			[
				'foo' => [
					ParamValidator::PARAM_TYPE => 'string',
					ParamValidator::PARAM_REQUIRED => true,
					Handler::PARAM_SOURCE => 'query',
				]
			],
			[],
			new RequestData( [ 'queryParams' => [ 'foo' => 'kittens' ] ] ),
			[ 'foo' => 'kittens' ],
			[]
		];

		yield 'body parameter' => [
			[],
			[
				'foo' => [
					ParamValidator::PARAM_TYPE => 'string',
					ParamValidator::PARAM_REQUIRED => true,
					Handler::PARAM_SOURCE => 'body',
				]
			],
			new RequestData( [ 'parsedBody' => [ 'foo' => 'kittens' ] ] ),
			[],
			[ 'foo' => 'kittens' ]
		];
	}

	/**
	 * @dataProvider provideValidate
	 */
	public function testValidate( $paramSettings, $bodyParamSettings, $request, $expectedParams, $expectedBody ) {
		$handler = $this->newHandler( [ 'getParamSettings', 'getBodyParamSettings' ] );
		$handler->method( 'getParamSettings' )->willReturn( $paramSettings );
		$handler->method( 'getBodyParamSettings' )->willReturn( $bodyParamSettings );

		$this->initHandler( $handler, $request );
		$this->validateHandler( $handler );

		$this->assertSame( $expectedParams, $handler->getValidatedParams() );
		$this->assertSame( $expectedBody, $handler->getValidatedBody() );
	}

	public function testValidate_post() {
		$this->expectDeprecationAndContinue( '/The "post" source is deprecated/' );

		$paramSettings = [
			'foo' => [
				ParamValidator::PARAM_TYPE => 'string',
				ParamValidator::PARAM_REQUIRED => true,
				Handler::PARAM_SOURCE => 'post',
			]
		];

		$request = new RequestData( [ 'postParams' => [ 'foo' => 'kittens' ] ] );

		$handler = $this->newHandler( [ 'getParamSettings' ] );
		$handler->method( 'getParamSettings' )->willReturn( $paramSettings );

		$this->initHandler( $handler, $request );
		$this->validateHandler( $handler );

		$params = $handler->getValidatedParams();
		$this->assertSame( [ 'foo' => 'kittens' ], $params );
	}

	public static function provideValidate_invalid() {
		yield 'missing required' => [
			[
				'foo' => [
					ParamValidator::PARAM_TYPE => 'string',
					ParamValidator::PARAM_REQUIRED => true,
					Handler::PARAM_SOURCE => 'query',
				]
			],
			[ 'queryParams' => [ 'bar' => 'kittens' ] ],
			[
				'error' => 'parameter-validation-failed',
				'failureCode' => 'missingparam'
			]
		];
	}

	/**
	 * @dataProvider provideValidate_invalid
	 */
	public function testValidate_invalid( $paramSettings, $requestData, $expectedError ) {
		$request = new RequestData( $requestData );

		$handler = $this->newHandler( [ 'getParamSettings' ] );
		$handler->method( 'getParamSettings' )->willReturn( $paramSettings );

		try {
			$this->initHandler( $handler, $request );
			$this->validateHandler( $handler );
			$this->fail( 'Expected LocalizedHttpException' );
		} catch ( LocalizedHttpException $ex ) {
			$data = $ex->getErrorData();

			foreach ( $expectedError as $field => $value ) {
				$this->assertSame( $value, $data[$field] ?? null );
			}
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
				]
			],
			new RequestData( [ 'parsedBody' => [ 'foo' => 'kittens' ] ] ),
			[ 'foo' => 'kittens' ]
		];

		yield 'body parameter with type coercion' => [
			[
				'foo' => [
					ParamValidator::PARAM_TYPE => 'integer',
					ParamValidator::PARAM_REQUIRED => true,
					Handler::PARAM_SOURCE => 'body',
				]
			],
			new RequestData( [
				'headers' => [
					// Form data automatically enabled type coercion
					'Content-Type' => RequestInterface::FORM_URLENCODED_CONTENT_TYPE
				],
				'parsedBody' => [ 'foo' => '1234' ]
			] ),
			[ 'foo' => 1234 ]
		];

		yield 'multivalue string in form data' => [
			[
				'foo' => [
					ParamValidator::PARAM_TYPE => 'string',
					ParamValidator::PARAM_REQUIRED => true,
					ParamValidator::PARAM_ISMULTI => true,
					Handler::PARAM_SOURCE => 'body',
				]
			],
			new RequestData( [
				'headers' => [
					'Content-Type' => RequestInterface::FORM_URLENCODED_CONTENT_TYPE
				],
				'parsedBody' => [ 'foo' => 'x|y|z' ]
			] ),
			[ 'foo' => [ 'x', 'y', 'z' ] ]
		];

		yield 'multivalue string in JSON' => [
			[
				'foo' => [
					ParamValidator::PARAM_TYPE => 'string',
					ParamValidator::PARAM_REQUIRED => true,
					ParamValidator::PARAM_ISMULTI => true,
					Handler::PARAM_SOURCE => 'body',
				]
			],
			new RequestData( [
				'headers' => [
					'Content-Type' => RequestInterface::JSON_CONTENT_TYPE
				],
				'parsedBody' => [ 'foo' => [ 'x', 'y', 'z' ] ]
			] ),
			[ 'foo' => [ 'x', 'y', 'z' ] ]
		];
	}

	/**
	 * @dataProvider provideValidateBodyParams
	 */
	public function testValidateBodyParams( $paramSettings, $request, $expected ) {
		$handler = $this->newHandler( [ 'getBodyParamSettings' ] );
		$handler->method( 'getBodyParamSettings' )->willReturn( $paramSettings );

		$this->initHandler( $handler, $request );
		$this->validateHandler( $handler );

		$params = $handler->getValidatedBody();
		$this->assertSame( $expected, $params );
	}

	public function testGetBodyParamSettings() {
		$bodyParamSettings = [
			'bodyfoo' => [
				ParamValidator::PARAM_TYPE => 'string',
				Handler::PARAM_SOURCE => 'body',
			]
		];
		$handler = $this->newHandler( [ 'getBodyParamSettings' ] );
		$handler->method( 'getBodyParamSettings' )->willReturn( $bodyParamSettings );

		$bodyParams = $handler->getBodyParamSettings();

		$expected = [
			'bodyfoo' => $bodyParamSettings['bodyfoo']
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

	public static function provideValidateBodyParams_invalid() {
		$paramDefintions = [
			'foo' => [
				ParamValidator::PARAM_TYPE => 'timestamp',
				ParamValidator::PARAM_REQUIRED => true,
				Handler::PARAM_SOURCE => 'body',
			]
		];

		yield 'missing param' => [
			$paramDefintions,
			new RequestData( [ 'parsedBody' => [], ] ),
			'missingparam'
		];

		yield 'extra param' => [
			$paramDefintions,
			new RequestData(
				[
					'parsedBody' => [
						'foo' => 23,
						'xyzzy' => 'lizzards',
					],
				]
			),
			'extraneous-body-fields'
		];

		yield 'bad param' => [
			$paramDefintions,
			new RequestData(
				[
					'parsedBody' => [
						'foo' => 'kittens',
					],
				]
			),
			'badtimestamp'
		];

		yield 'body parameter without type coercion' => [
			[
				'foo' => [
					ParamValidator::PARAM_TYPE => 'integer',
					ParamValidator::PARAM_REQUIRED => true,
					Handler::PARAM_SOURCE => 'body',
				]
			],
			new RequestData( [
				// JSON doesn't enable type coercion
				'headers' => [ 'Content-Type' => 'application/json', ],
				'parsedBody' => [ 'foo' => '1234' ]
			] ),
			'badinteger-type'
		];

		yield 'multivalue body parameter as string in JSON' => [
			[
				'foo' => [
					ParamValidator::PARAM_TYPE => 'string',
					ParamValidator::PARAM_REQUIRED => true,
					ParamValidator::PARAM_ISMULTI => true,
					Handler::PARAM_SOURCE => 'body',
				]
			],
			new RequestData( [
				'headers' => [ 'Content-Type' => 'application/json', ],
				'parsedBody' => [ 'foo' => 'x|y|z' ]
			] ),
			'multivalue-must-be-array'
		];

		yield 'multivalue integer as array of strings in JSON' => [
			[
				'foo' => [
					ParamValidator::PARAM_TYPE => 'integer',
					ParamValidator::PARAM_REQUIRED => true,
					ParamValidator::PARAM_ISMULTI => true,
					Handler::PARAM_SOURCE => 'body',
				]
			],
			new RequestData( [
				'headers' => [ 'Content-Type' => 'application/json' ],
				'parsedBody' => [ 'foo' => [ '1', '2', '3' ] ]
			] ),
			'badinteger-type'
		];
	}

	/**
	 * @dataProvider provideValidateBodyParams_invalid
	 */
	public function testValidateBodyParams_invalid( $paramSettings, $request, $expectedError ) {
		$handler = $this->newHandler( [ 'getBodyParamSettings' ] );
		$handler->method( 'getBodyParamSettings' )->willReturn( $paramSettings );

		try {
			$this->initHandler( $handler, $request );
			$this->validateHandler( $handler );
			$this->fail( 'Expected LocalizedHttpException' );
		} catch ( LocalizedHttpException $ex ) {
			$data = $ex->getErrorData();
			$this->assertSame( 'parameter-validation-failed', $data['error'] ?? null );
			$this->assertSame( $expectedError, $data['failureCode'] ?? null );
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

		$this->expectDeprecationAndContinue( '/overrides getBodyValidator/' );
		$this->expectDeprecationAndContinue( '/Validator::validateBody/' );
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
			'json patch body' => [
				new RequestData( [
					'bodyContents' => '{"patch":[]}',
					'headers' => [ 'Content-Type' => 'application/json-patch+json' ]
				] ),
				[ 'patch' => [] ]
			],
			'form data' => [
				new RequestData( [
					'postParams' => [ 'foo' => 'bar' ],
					'headers' => [ 'Content-Type' => 'application/x-www-form-urlencoded' ]
				] ),
				[ 'foo' => 'bar' ]
			],
			'multipart form data' => [
				new RequestData( [
					'postParams' => [ 'foo' => 'bar' ],
					'headers' => [ 'Content-Type' => 'multipart/form-data' ]
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
			'json body with normalization' => [
				new RequestData( [
					'bodyContents' => json_encode( [ 'param' => "L\u{0061}\u{0308}rm" ], JSON_UNESCAPED_UNICODE ),
					'headers' => [ 'Content-Type' => 'application/json' ]
				] ),
				[ 'param' => "L\u{00E4}rm" ]
			],
			'form data with normalization' => [
				new RequestData( [
					'postParams' => [ 'param' => "L\u{0061}\u{0308}rm" ],
					'headers' => [ 'Content-Type' => 'application/x-www-form-urlencoded' ]
				] ),
				[ 'param' => "L\u{00E4}rm" ]
			],
			'multipart form data with normalization' => [
				new RequestData( [
					'postParams' => [ 'param' => "L\u{0061}\u{0308}rm" ],
					'headers' => [ 'Content-Type' => 'multipart/form-data' ]
				] ),
				[ 'param' => "L\u{00E4}rm" ]
			]
		];
	}

	/** @dataProvider provideParseBodyData */
	public function testParseBodyData( $requestData, $expectedResult ) {
		$handler = $this->newHandler( [ 'getSupportedRequestTypes' ] );
		$handler->method( 'getSupportedRequestTypes' )->willReturn( [
			'application/json',
			'application/json-patch+json',
			'application/x-www-form-urlencoded',
			'multipart/form-data'
		] );

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

	public function testPostRequestFailsWithFormData() {
		$handler = $this->newHandler();
		$requestData = new RequestData( [
			'headers' => [ 'Content-Type' => 'application/x-www-form-urlencoded' ],
			'postParams' => [ 'test' => 'foo' ]
		] );

		$expectedResult = new LocalizedHttpException(
			new MessageValue( 'rest-unsupported-content-type', [ '' ] ),
			415
		);

		$this->expectException( LocalizedHttpException::class );
		$this->expectExceptionCode( $expectedResult->getCode() );
		$this->expectExceptionMessage( $expectedResult->getMessage() );
		$handler->parseBodyData( $requestData );
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
		$this->expectDeprecationAndContinue( '/The "post" source is deprecated/' );

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

		// The "post" parameter should be processed as "parameter" but not as "body".
		$this->initHandler( $handler, $request );
		$this->validateHandler( $handler );

		$this->assertArrayHasKey( 'test', $handler->getValidatedParams() );
		$this->assertArrayNotHasKey( 'test', $handler->getValidatedBody() );
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

		$handler = $this->newHandler( [ 'getBodyParamSettings', 'getSupportedRequestTypes' ] );
		$handler->method( 'getBodyParamSettings' )->willReturn( $paramSettings );
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

	private static function assertWellFormedOAS( array $spec, array $required ) {
		foreach ( $required as $key ) {
			Assert::assertArrayHasKey( $key, $spec );
		}
	}

	private static function makeMap( array $list, string $key ): array {
		$map = [];
		foreach ( $list as $obj ) {
			$val = $obj[$key];
			$map[$val] = $obj;
		}
		return $map;
	}

	public static function provideGetOpenApiSpec() {
		yield 'defaults' => [
			'$paramSettings' => [],
			'$bodySettings' => [],
			'$requestTypes' => [ 'application/json' ],
			'$responseBodySchema' => null,
			'$routeConfig' => [ 'path' => '/test' ],
			'$openApiSpec' => [],
			'$method' => 'GET',
			'$assertions' =>
				static function ( array $spec ) {
					self::assertWellFormedOAS( $spec, [ 'responses' ] );
					$resp = $spec['responses'];

					Assert::assertArrayHasKey( 200, $resp );
					Assert::assertArrayHasKey( 'default', $resp );
				},
		];

		yield 'path parameters' => [
			'$paramSettings' => [
				'a' => [
					Handler::PARAM_SOURCE => 'path',
					ParamValidator::PARAM_TYPE => 'integer',
					ParamValidator::PARAM_REQUIRED => true,
				],
				'b' => [
					Handler::PARAM_SOURCE => 'path',
					ParamValidator::PARAM_TYPE => [ 'x', 'y', 'z' ],
					ParamValidator::PARAM_REQUIRED => false,
					Handler::PARAM_DESCRIPTION => "param b"
				],
				'c' => [
					Handler::PARAM_SOURCE => 'path',
					ParamValidator::PARAM_TYPE => 'string',
					ParamValidator::PARAM_REQUIRED => false,
				],
				'd' => [
					Handler::PARAM_SOURCE => 'path',
					ParamValidator::PARAM_TYPE => 'string',
					ParamValidator::PARAM_REQUIRED => false,
					Handler::PARAM_DESCRIPTION => new MessageValue( 'rest-param-desc-mock-desc' )
				],
			],
			'$bodySettings' => [],
			'$requestTypes' => [ 'application/json' ],
			'$responseBodySchema' => null,
			'$routeConfig' => [ 'path' => '/test/{a}/{b}/{d}' ],
			'$openApiSpec' => [],
			'$method' => 'GET',
			'$assertions' =>
				static function ( array $spec ) {
					self::assertWellFormedOAS( $spec, [ 'parameters' ] );
					$params = self::makeMap( $spec['parameters'], 'name' );

					// The parameter "c" is optional and not in the declared path.
					Assert::assertArrayHasKey( 'a', $params, 'required path param' );
					Assert::assertArrayHasKey( 'b', $params, 'used optional path param' );
					Assert::assertArrayNotHasKey( 'c', $params, 'unused optional path param' );

					Assert::assertSame( 'path', $params['a']['in'] );
					Assert::assertSame( 'path', $params['b']['in'] );

					// All path params must be required in OAS, even if they
					// were declared as optional (T359652) in getParamSettings.
					Assert::assertTrue( $params['a']['required'] );
					Assert::assertTrue( $params['b']['required'] );

					Assert::assertSame( 'a parameter', $params['a']['description'] );
					Assert::assertSame( 'param b', $params['b']['description'] );
					Assert::assertSame( '<message key="rest-param-desc-mock-desc"></message>',
						$params['d']['description']
					);

					Assert::assertSame( 'integer', $params['a']['schema']['type'] );
					Assert::assertSame( 'string', $params['b']['schema']['type'] );
					Assert::assertSame( [ 'x', 'y', 'z' ], $params['b']['schema']['enum'] );
				},
		];

		yield 'query parameters' => [
			'$paramSettings' => [
				'a' => [
					Handler::PARAM_SOURCE => 'query',
					ParamValidator::PARAM_TYPE => 'integer',
					ParamValidator::PARAM_REQUIRED => true,
				],
				'b' => [
					Handler::PARAM_SOURCE => 'query',
					ParamValidator::PARAM_TYPE => [ 'x', 'y', 'z' ],
					ParamValidator::PARAM_REQUIRED => false,
					Handler::PARAM_DESCRIPTION => "param b"
				],
				'c' => [
					Handler::PARAM_SOURCE => 'query',
					ParamValidator::PARAM_TYPE => 'string',
					ParamValidator::PARAM_REQUIRED => false,
					Handler::PARAM_DESCRIPTION => new MessageValue( 'rest-param-desc-mock-desc' )
				],
			],
			'$bodySettings' => [],
			'$requestTypes' => [ 'application/json' ],
			'$responseBodySchema' => null,
			'$routeConfig' => [ 'path' => '/test' ],
			'$openApiSpec' => [],
			'$method' => 'GET',
			'$assertions' =>
				static function ( array $spec ) {
					self::assertWellFormedOAS( $spec, [ 'parameters' ] );
					$params = self::makeMap( $spec['parameters'], 'name' );

					Assert::assertArrayHasKey( 'a', $params );
					Assert::assertArrayHasKey( 'b', $params );

					Assert::assertSame( 'query', $params['a']['in'] );
					Assert::assertSame( 'query', $params['b']['in'] );

					Assert::assertTrue( $params['a']['required'] );
					Assert::assertFalse( $params['b']['required'] );

					Assert::assertSame( 'a parameter', $params['a']['description'] );
					Assert::assertSame( 'param b', $params['b']['description'] );
					Assert::assertSame( '<message key="rest-param-desc-mock-desc"></message>',
						$params['c']['description']
					);

					Assert::assertSame( 'integer', $params['a']['schema']['type'] );
					Assert::assertSame( 'string', $params['b']['schema']['type'] );
					Assert::assertSame( [ 'x', 'y', 'z' ], $params['b']['schema']['enum'] );
				},
		];

		yield 'request body' => [
			'$paramSettings' => [],
			'$bodySettings' => [
				'a' => [
					Handler::PARAM_SOURCE => 'body',
					ParamValidator::PARAM_TYPE => 'array',
					ArrayDef::PARAM_SCHEMA => [
						'type' => 'object',
						'required' => [ 'x', 'y', 'z' ]
					],
					ParamValidator::PARAM_REQUIRED => true,
				],
				'b' => [
					Handler::PARAM_SOURCE => 'body',
					ParamValidator::PARAM_REQUIRED => false,
					Handler::PARAM_DESCRIPTION => "param b"
				],
				'c' => [
					Handler::PARAM_SOURCE => 'body',
					ParamValidator::PARAM_REQUIRED => false,
					Handler::PARAM_DESCRIPTION => new MessageValue( 'rest-param-desc-mock-desc' )
				],
				'p' => [
					Handler::PARAM_SOURCE => 'post',
				],
			],
			'$requestTypes' => [ 'application/foo+json', 'application/bar+json' ],
			'$responseBodySchema' => null,
			'$routeConfig' => [ 'path' => '/test' ],
			'$openApiSpec' => [],
			'$method' => 'PUT',
			'$assertions' =>
				static function ( array $spec ) {
					self::assertWellFormedOAS( $spec, [ 'requestBody' ] );
					Assert::assertTrue( $spec['requestBody']['required'] );

					Assert::assertArrayHasKey(
						'application/foo+json',
						$spec['requestBody']['content']
					);

					Assert::assertSame(
						$spec['requestBody']['content']['application/foo+json'],
						$spec['requestBody']['content']['application/bar+json']
					);

					$schema = $spec['requestBody']['content']
						['application/foo+json']['schema'];

					Assert::assertSame( 'object', $schema['type'] );

					// Do not include "post" params if the request type
					// is not application/x-www-form-urlencoded or multipart/form-data.
					Assert::assertArrayHasKey( 'a', $schema['properties'] );
					Assert::assertArrayHasKey( 'b', $schema['properties'] );
					Assert::assertArrayNotHasKey( 'p', $schema['properties'] );

					Assert::assertContains( 'a', $schema['required'] );
					Assert::assertNotContains( 'b', $schema['required'] );

					Assert::assertSame( 'a parameter', $schema['properties']['a']['description'] );
					Assert::assertSame( 'param b', $schema['properties']['b']['description'] );
					Assert::assertSame( '<message key="rest-param-desc-mock-desc"></message>',
						$schema['properties']['c']['description']
					);

					// Nested schema, from ArrayDef
					$aSchema = $schema['properties']['a'];
					Assert::assertSame( 'object', $aSchema['type'] );
					Assert::assertSame( [ 'x', 'y', 'z' ], $aSchema['required'] );
				},
		];

		yield 'form data' => [
			'$paramSettings' => [],
			'$bodySettings' => [
				'a' => [
					Handler::PARAM_SOURCE => 'body',
				],
				'p' => [
					Handler::PARAM_SOURCE => 'post',
				],
			],
			'$requestTypes' => [ 'application/x-www-form-urlencoded' ],
			'$responseBodySchema' => null,
			'$routeConfig' => [ 'path' => '/test' ],
			'$openApiSpec' => [],
			'$method' => 'POST',
			'$assertions' =>
				static function ( array $spec ) {
					self::assertWellFormedOAS( $spec, [ 'requestBody' ] );
					Assert::assertTrue( $spec['requestBody']['required'] );

					Assert::assertArrayHasKey(
						'application/x-www-form-urlencoded',
						$spec['requestBody']['content']
					);
					$schema = $spec['requestBody']['content']
						['application/x-www-form-urlencoded']['schema'];

					Assert::assertSame( 'object', $schema['type'] );

					// Include both "post" and "body" params, because the
					// request type is application/x-www-form-urlencoded.
					Assert::assertArrayHasKey( 'a', $schema['properties'] );
					Assert::assertArrayHasKey( 'p', $schema['properties'] );
				},
		];

		yield 'no request body for GET' => [
			'$paramSettings' => [],
			'$bodySettings' => [
				'a' => [
					Handler::PARAM_SOURCE => 'body',
					ParamValidator::PARAM_TYPE => 'integer',
					ParamValidator::PARAM_REQUIRED => true,
				],
			],
			'$requestTypes' => [ 'application/json' ],
			'$responseBodySchema' => null,
			'$routeConfig' => [ 'path' => '/test' ],
			'$openApiSpec' => [],
			'$method' => 'GET',
			'$assertions' =>
				static function ( array $spec ) {
					self::assertWellFormedOAS( $spec, [] );

					// When generating a spec for GET, don't include the request body.
					Assert::assertArrayNotHasKey( 'requestBody', $spec );
				},
		];

		yield 'optional body for DELETE' => [
			'$paramSettings' => [],
			'$bodySettings' => [
				'a' => [
					Handler::PARAM_SOURCE => 'body',
					ParamValidator::PARAM_TYPE => 'integer',
					ParamValidator::PARAM_REQUIRED => false,
				],
			],
			'$requestTypes' => [ 'application/json' ],
			'$responseBodySchema' => null,
			'$routeConfig' => [ 'path' => '/test' ],
			'$openApiSpec' => [],
			'$method' => 'DELETE',
			'$assertions' =>
				static function ( array $spec ) {
					self::assertWellFormedOAS( $spec, [ 'requestBody' ] );

					// When generating a spec for GET, don't include the request body.
					// FIXME: check if there are required body params!
					Assert::assertFalse( $spec['requestBody']['required'] );
				},
		];

		yield 'optional path params' => [
			'$paramSettings' => [
				'p' => [
					Handler::PARAM_SOURCE => 'path',
					ParamValidator::PARAM_REQUIRED => false,
				],
				'r' => [ // not in declared path, will be ignored
					Handler::PARAM_SOURCE => 'path',
					ParamValidator::PARAM_REQUIRED => false,
				],
			],
			'$bodySettings' => [],
			'$requestTypes' => [ 'application/json' ],
			'$responseBodySchema' => null,
			'$routeConfig' => [ 'path' => '/test/{p}' ],
			'$openApiSpec' => [],
			'$method' => 'GET',
			'$assertions' =>
				static function ( array $spec ) {
					self::assertWellFormedOAS( $spec, [ 'parameters' ] );
					$params = self::makeMap( $spec['parameters'], 'name' );

					Assert::assertArrayHasKey(
						'p',
						$params,
						'used optional path parameter should be listed'
					);
					Assert::assertArrayNotHasKey(
						'r',
						$params,
						'unused optional path parameter should not be listed'
					);

					Assert::assertSame( 'path', $params['p']['in'] );
					Assert::assertTrue(
						$params['p']['required'],
						'used optional path parameter should be marked as required'
					);
				},
		];

		yield 'response body schema' => [
			'$paramSettings' => [],
			'$bodySettings' => [],
			'$requestTypes' => [ 'application/json' ],
			'$responseBodySchema' => [
				'x-i18n-description' => 'rest-schema-desc-mock-desc',
				'properties' => [
					'a' => [
						'type' => 'string'
					],
					'b' => [
						'type' => 'string',
						'description' => 'plaintext description'
					],
					'c' => [
						'type' => 'string',
						'x-i18n-description' => 'rest-property-desc-mock-desc',
					],
					'd' => [
						'type' => 'object',
						'description' => "mock description",
						'properties' => [
							'example' => [
								'type' => 'string',
								'x-i18n-description' => 'rest-property-desc-mock-desc',
							]
						]
					],
				]
			],
			'$routeConfig' => [ 'path' => 'test' ],
			'$openApiSpec' => [],
			'$method' => 'GET',
			'$assertions' =>
				static function ( array $spec ) {
					self::assertWellFormedOAS( $spec, [ 'responses' ] );

					$schema = $spec['responses'][200]['content']['application/json']['schema'];
					// Body
					Assert::assertArrayHasKey( 'description', $schema );
					Assert::assertSame(
						'<message key="rest-schema-desc-mock-desc"></message>',
						$schema['description']
					);
					// First level properties
					$props = $schema['properties'];
					Assert::assertArrayNotHasKey( 'description', $props['a'] );
					Assert::assertArrayHasKey( 'description', $props['b'] );
					Assert::assertSame( 'plaintext description', $props['b']['description'] );
					Assert::assertArrayHasKey( 'description', $props['c'] );
					Assert::assertSame(
						'<message key="rest-property-desc-mock-desc"></message>',
						$schema['properties']['c']['description']
					);
					// Nested properties
					Assert::assertArrayHasKey( 'description', $props['d']['properties']['example'] );
					Assert::assertSame(
						'<message key="rest-property-desc-mock-desc"></message>',
						$props['d']['properties']['example']['description'] );
				},
		];

		yield 'OAS info' => [
			'$paramSettings' => [
				'p' => [
					Handler::PARAM_SOURCE => 'path',
				],
			],
			'$bodySettings' => [],
			'$requestTypes' => [ 'application/json' ],
			'$responseBodySchema' => null,
			'$routeConfig' => [
				'path' => 'test/{p}',
			],
			'$openApiSpec' => [
				'summary' => 'just a test',
				'parameters' => 'will be ignored',
			],
			'$method' => 'GET',
			'$assertions' =>
				static function ( array $spec ) {
					self::assertWellFormedOAS( $spec, [ 'summary', 'parameters' ] );
					Assert::assertArrayHasKey( 'summary', $spec );
					Assert::assertSame( 'just a test', $spec['summary'] );

					$params = self::makeMap( $spec['parameters'], 'name' );
					Assert::assertArrayHasKey( 'p', $params );
				},
		];
	}

	/**
	 * @dataProvider provideGetOpenApiSpec
	 */
	public function testGetOpenApiSpec(
		$paramSettings,
		$bodySettings,
		$requestTypes,
		$responseBodySchema,
		$routeConfig,
		$openApiSpec,
		$method,
		$assertions
	) {
		$handler = $this->newHandler( [
				'getParamSettings',
				'getBodyParamSettings',
				'getSupportedRequestTypes',
				'getResponseBodySchema'
		] );
		$handler->method( 'getParamSettings' )->willReturn( $paramSettings );
		$handler->method( 'getBodyParamSettings' )->willReturn( $bodySettings );
		$handler->method( 'getSupportedRequestTypes' )->willReturn( $requestTypes );
		$handler->method( 'getResponseBodySchema' )->willReturn( $responseBodySchema );

		// The "body" parameter should be processed as "body", not as "parameter".
		$module = $this->createNoOpMock( Module::class );
		$handler->initContext(
			$module,
			$routeConfig['path'],
			$routeConfig,
			$openApiSpec
		);

		// Because the dummy text formatter uses MessageValue::dump(), translated message keys
		// will contain html. This html is a testing artifact and not representative of the spec
		// presented to users at runtime.
		$formatter = $this->getDummyTextFormatter( true );

		$responseFactory = new ResponseFactory( [ 'qqx' => $formatter ] );
		$authority = $this->mockAnonUltimateAuthority();
		$hookContainer = $this->createHookContainer();
		$handler->initServices( $authority, $responseFactory, $hookContainer );

		$spec = $handler->getOpenApiSpec( $method );

		$assertions( $spec );
	}

}
