<?php

namespace MediaWiki\Tests\Rest\Handler;

use Exception;
use MediaWiki\Api\ApiBase;
use MediaWiki\Api\ApiMain;
use MediaWiki\Api\ApiMessage;
use MediaWiki\Api\ApiRestHelper;
use MediaWiki\Api\ApiRestHybrid;
use MediaWiki\Api\ApiUsageException;
use MediaWiki\Api\IApiMessage;
use MediaWiki\Request\FauxResponse;
use MediaWiki\Rest\Handler;
use MediaWiki\Rest\Handler\GenericActionHandler;
use MediaWiki\Rest\RequestData;
use MediaWiki\Rest\Response;
use MediaWiki\Session\Session;
use MediaWiki\Session\Token;
use MediaWiki\User\LoggedOutEditToken;
use MediaWiki\User\User;
use MediaWikiIntegrationTestCase;
use StatusValue;
use Wikimedia\Message\MessageValue;
use Wikimedia\ParamValidator\ParamValidator;
use Wikimedia\TestingAccessWrapper;

/**
 * Tests for GenericActionHandler covering behaviour inherited from
 * ActionModuleBasedHandler. Runs against a real ApiMain with stub action
 * modules registered through the module manager, so the full execute()
 * pipeline is exercised end-to-end.
 *
 * @covers \MediaWiki\Rest\Handler\GenericActionHandler
 * @covers \MediaWiki\Rest\Handler\ActionModuleBasedHandler
 */
class GenericActionHandlerTest extends MediaWikiIntegrationTestCase {
	use HandlerTestTrait;
	use ActionModuleBasedHandlerTestTrait;

	private ApiMain $apiMain;

	protected function setUp(): void {
		parent::setUp();
		$this->apiMain = $this->getApiMain( true );
	}

	/**
	 * Build a GenericActionHandler for testing. All options are optional.
	 *
	 * @param array $options
	 *   - module: ApiBase to wrap. Defaults to a dummy module built from the
	 *     actionName/resultData/throwException options.
	 *   - actionName: module name of the dummy module (default 'test').
	 *   - resultData: result the dummy module returns (default []).
	 *   - throwException: Exception the dummy module throws (default null).
	 *   - init: whether to initialize the handler for direct method calls.
	 *     Execution tests leave this false and initialize via executeHandler()
	 *     instead (default false).
	 *   - request: RequestInterface to initialize with (default null).
	 *   - config: handler config, e.g. [ 'method' => ..., 'path' => ... ] (default []).
	 *   - session: Session to initialize with (default null).
	 */
	private function newHandler( array $options = [] ): GenericActionHandler {
		$options += [
			'module' => null,
			'actionName' => 'test',
			'resultData' => [],
			'throwException' => null,
			'init' => false,
			'request' => null,
			'config' => [],
			'session' => null,
		];

		$module = $options['module'] ?? $this->getDummyApiModule(
			$this->apiMain,
			$options['actionName'],
			[ $options['actionName'] => $options['resultData'] ],
			$options['throwException']
		);

		$this->overrideActionModule(
			$this->apiMain, $module->getModuleName(), 'action', $module
		);

		$handler = new GenericActionHandler( $module->getModuleName() );
		$handler->setApiMain( $this->apiMain );

		if ( $options['init'] ) {
			$this->initHandler(
				$handler, $options['request'], $options['config'], [], null, $options['session']
			);
		}

		return $handler;
	}

	private function newHybridModule(
		string $actionName = 'test',
		?ApiRestHelper $helper = null,
		?Exception $throwException = null,
		array $resultData = []
	): ApiRestHybrid&ApiBase {
		$helper ??= new class extends ApiRestHelper {
		};

		/** @var ApiRestHybrid & ApiBase $module */
		$module = new class( $this->apiMain, $actionName, $helper, $resultData, $throwException )
			extends ApiBase implements ApiRestHybrid
		{
			public function __construct(
				ApiMain $main,
				string $name,
				private ApiRestHelper $helper,
				private array $resultData,
				private ?Exception $throwException
			) {
				parent::__construct( $main, $name );
			}

			public function execute() {
				if ( $this->throwException ) {
					throw $this->throwException;
				}
				$res = $this->getResult();
				foreach ( $this->resultData as $key => $value ) {
					$res->addValue( $this->getModuleName(), $key, $value );
				}

				$res->addValue(
					$this->getModuleName(),
					'PARAMETERS',
					$this->extractRequestParams()
				);

				$res->addValue(
					$this->getModuleName(),
					'MAIN_PARAMETERS',
					$this->getMain()->extractRequestParams()
				);
			}

			public function getRestHelper(): ApiRestHelper {
				return $this->helper;
			}

			protected function getAllowedParams() {
				// Examples taken from ApiMove
				return [
					'from' => null,
					'fromid' => [
						ParamValidator::PARAM_TYPE => 'integer',
						ApiBase::PARAM_HELP_MSG => 'apihelp-move-param-fromid',
					],
					'to' => [
						ParamValidator::PARAM_TYPE => 'string',
						ParamValidator::PARAM_REQUIRED => true,
						ApiBase::PARAM_HELP_MSG => [ 'apihelp-move-param-to' ],
					],
					'reason' => '',
					'movetalk' => false,
					// A boolean declared with an explicit false default (shorthand above)
					// vs. one declared with no default at all, to exercise both cases.
					'redirect' => [
						ParamValidator::PARAM_TYPE => 'boolean',
					],
					'tags' => [
						ParamValidator::PARAM_TYPE => 'tags',
						ParamValidator::PARAM_ISMULTI => true,
					],
				];
			}
		};

		return $module;
	}

	/**
	 * A session mock with configurable CSRF-safety, user (anon or not) and
	 * session token, for exercising token injection in
	 * getActionModuleParameters().
	 */
	private function newCsrfSession( bool $csrfSafe, bool $anon, ?Token $token ): Session {
		$session = $this->getSession( $csrfSafe );

		$user = $this->createMock( User::class );
		$user->method( 'isAnon' )->willReturn( $anon );

		$session->method( 'getUser' )->willReturn( $user );
		$session->method( 'hasToken' )->willReturn( $token !== null );
		$session->method( 'getToken' )->willReturn( $token );

		return $session;
	}

	public function testPost() {
		$handler = $this->newHandler( [
			'actionName' => 'fakeaction',
			'resultData' => [ 'from' => 'A', 'to' => 'B' ],
		] );
		$request = new RequestData( [ 'method' => 'POST' ] );

		$data = $this->executeHandlerAndGetBodyData( $handler, $request );

		$this->assertSame( [ 'from' => 'A', 'to' => 'B' ], $data );
	}

	public function testGet() {
		$handler = $this->newHandler( [
			'actionName' => 'fakeaction',
			'resultData' => [ 'from' => 'A', 'to' => 'B' ],
		] );
		$request = new RequestData( [ 'method' => 'GET' ] );

		$data = $this->executeHandlerAndGetBodyData( $handler, $request );

		$this->assertSame( [ 'from' => 'A', 'to' => 'B' ], $data );
	}

	public function testExecute_parameters() {
		$handler = $this->newHandler( [
			'module' => $this->newHybridModule( actionName: 'fakeaction', resultData: [
				'from' => 'A', 'to' => 'B',
			] ),
		] );

		$request = new RequestData( [
			'method' => 'POST',
			'queryParams' => [ 'from' => 'SourceOne', 'to' => 'Target', 'maxlag' => 17 ],
			'parsedBody' => [ 'from' => 'SourceTwo', 'extra' => 123 ],
		] );

		$data = $this->executeHandlerAndGetBodyData( $handler, $request );

		// The fake hybrid module has that hard coded
		$params = $data['PARAMETERS'];

		// check supplied module parameters
		$this->assertArrayNotHasKey( 'extra', $params, 'Extra parameter should be ignored' );
		$this->assertSame( 'SourceTwo', $params['from'] );
		$this->assertSame( 'Target', $params['to'] );

		// check injected main parameters
		$mainParams = $data['MAIN_PARAMETERS'];

		// test filtered parameters
		$this->assertNull( $mainParams['maxlag'], 'ApiMain parameter should be suppressed' );
		$this->assertSame( 'fakeaction', $mainParams['action'] );
		$this->assertSame( 'json', $mainParams['format'] );
		$this->assertSame( 'plaintext', $mainParams['errorformat'] );

		// NOTE: formatversion is not in ApiMain but in ApiFormat.
	}

	public static function provideErrorMapping() {
		yield 'badtoken → 401' => [ 'badtoken', 401 ];
		yield 'ratelimited → 429' => [ 'ratelimited', 429 ];
		yield 'maxlag → 429' => [ 'maxlag', 429 ];
		yield 'readonly → 503' => [ 'readonly', 503 ];
		yield 'missingtitle → 404' => [ 'missingtitle', 404 ];
		yield 'nosuchpageid → 404' => [ 'nosuchpageid', 404 ];
		yield 'unknown code falls through to 400' => [ 'some-other-code', 400 ];
	}

	/**
	 * @dataProvider provideErrorMapping
	 */
	public function testExecuteMapsKnownErrorCodes( string $apiCode, int $expectedStatus ) {
		$exception = new ApiUsageException(
			null,
			StatusValue::newFatal( ApiMessage::create( 'apierror-something', $apiCode ) )
		);
		$handler = $this->newHandler( [ 'throwException' => $exception ] );
		$request = new RequestData( [ 'method' => 'POST' ] );

		$ex = $this->executeHandlerAndGetHttpException( $handler, $request );

		$this->assertSame( $expectedStatus, $ex->getCode() );
		$this->assertSame( $apiCode, $ex->getErrorData()['actionModuleErrorCode'] );
	}

	public function testExecuteIncludesApiMessageDataInErrorData() {
		$exception = new ApiUsageException(
			null,
			StatusValue::newFatal( ApiMessage::create(
				'apierror-something',
				'mycode',
				[ 'foo' => 'bar', 'n' => 42 ]
			) )
		);
		$handler = $this->newHandler( [ 'throwException' => $exception ] );
		$request = new RequestData( [ 'method' => 'POST' ] );

		$ex = $this->executeHandlerAndGetHttpException( $handler, $request );

		$data = $ex->getErrorData();
		$this->assertSame( 'bar', $data['foo'] );
		$this->assertSame( 42, $data['n'] );
		$this->assertSame( 'mycode', $data['actionModuleErrorCode'] );
	}

	public function testExecuteUsesHelperForUnmappedCode() {
		$helper = new class extends ApiRestHelper {
			public function getStatusForErrorMessage( IApiMessage $msg ): int {
				return $msg->getApiCode() === 'fileexists-sharedrepo-perm' ? 403 : 0;
			}
		};
		$exception = new ApiUsageException(
			null,
			StatusValue::newFatal( ApiMessage::create(
				'apierror-fileexists',
				'fileexists-sharedrepo-perm'
			) )
		);

		$handler = $this->newHandler( [
			'module' => $this->newHybridModule( helper: $helper, throwException: $exception ),
		] );

		// 'to' is required by the hybrid module's getAllowedParams(); supply
		// it so the module's execute() can throw the prepared exception
		// before validation does.
		$request = new RequestData( [
			'method' => 'POST',
			'parsedBody' => [ 'to' => 'B' ],
		] );

		$ex = $this->executeHandlerAndGetHttpException( $handler, $request );

		$this->assertSame( 403, $ex->getCode() );
	}

	public function testMapActionModuleResponse() {
		$actionResponse = new FauxResponse();
		$actionResponse->statusHeader( 301 );
		$actionResponse->header( 'Location: https://acme.test' );

		$restResponse = new Response();

		TestingAccessWrapper::newFromObject( $this->newHandler() )
			->mapActionModuleResponse( $actionResponse, [], $restResponse );

		$this->assertSame( 301, $restResponse->getStatusCode() );
		$this->assertSame( 'https://acme.test', $restResponse->getHeaderLine( 'location' ) );
	}

	public function testGetActionModuleParameters() {
		// init the handler, so getSupportedPathParams() doesn't fail
		$handler = $this->newHandler( [
			'module' => $this->newHybridModule( actionName: 'test' ),
			'init' => true,
		] );

		$handler = TestingAccessWrapper::newFromObject( $handler );
		$handler->request = new RequestData( [
			'method' => 'POST',
			'queryParams' => [
				'from' => 'X', // should be used
				'to' => 'Y', // should get overwritten by body param
				'extra' => '123' // should be passed through
			],
			'parsedBody' => [
				'to' => 'YY', // should overrid query param
				'reason' => 'test', // should be set
				'maxlag' => '123', // should be stripped
				'format' => 'xml'   // should get forced to 'json'
			],
		] );

		$params = $handler->getActionModuleParameters();

		$this->assertArrayNotHasKey( 'maxlag', $params, 'maxlag should have been stripped' );
		$this->assertSame( 'test', $params['reason'], 'Body param should be used' );
		$this->assertSame( 'YY', $params['to'], 'Body param should win over query param' );
		$this->assertSame( 'X', $params['from'], 'Query param should be used when absent from body' );
		$this->assertArrayNotHasKey( '123', $params, 'Extra param should have been stripped' );
	}

	public static function provideCsrfTokenInjection() {
		// csrfSafe, anon, hasSessionToken, expected outcome
		// A CSRF-safe session (e.g. OAuth) never needs a fabricated token.
		yield 'safe session: no token injected' => [ true, true, false, 'none' ];
		// An unsafe (cookie) session must supply a token to satisfy the action
		// module: the logged-out edit token for anonymous users...
		yield 'unsafe + anonymous: logged-out edit token' => [ false, true, false, 'loggedout' ];
		// ...and the session's own CSRF token for logged-in users.
		yield 'unsafe + logged-in: session token' => [ false, false, true, 'session' ];
	}

	/**
	 * @dataProvider provideCsrfTokenInjection
	 */
	public function testCsrfTokenInjection(
		bool $csrfSafe,
		bool $anon,
		bool $hasToken,
		string $expected
	) {
		// A module that requires a CSRF token, like most write actions.
		$module = new class( $this->apiMain, 'tokentest' ) extends ApiBase {
			public function execute() {
			}

			public function needsToken() {
				return 'csrf';
			}

			protected function getAllowedParams() {
				return [ 'to' => null ];
			}
		};

		$token = $hasToken ? $this->createMock( Token::class ) : null;
		$session = $this->newCsrfSession( $csrfSafe, $anon, $token );

		$handler = $this->newHandler( [
			'module' => $module,
			'init' => true,
			'request' => new RequestData( [ 'method' => 'POST', 'parsedBody' => [ 'to' => 'B' ] ] ),
			'session' => $session,
		] );

		$params = TestingAccessWrapper::newFromObject( $handler )
			->getActionModuleParameters();

		switch ( $expected ) {
			case 'none':
				// The essential assertion: a CSRF-safe session must NOT have a
				// token fabricated for it.
				$this->assertArrayNotHasKey( 'token', $params );
				break;

			case 'loggedout':
				$this->assertInstanceOf( LoggedOutEditToken::class, $params['token'] );
				break;

			case 'session':
				$this->assertSame( $token, $params['token'] );
				break;
		}
	}

	public function testPathParameter() {
		$module = $this->newHybridModule();

		// The route declares {from} as a path placeholder; the client also
		// (wrongly) passes 'from' as a query parameter.
		$request = new RequestData( [
			'method' => 'GET',
			'pathParams' => [ 'from' => 'FromPath' ],
			'queryParams' => [ 'from' => 'FromQuery', 'to' => 'X' ],
		] );

		$handler = $this->newHandler( [
			'module' => $module,
			'init' => true,
			'request' => $request,
			'config' => [ 'method' => 'GET', 'path' => '/test/{from}' ],
		] );

		$wrapper = TestingAccessWrapper::newFromObject( $handler );

		// A path parameter must not also be offered as a query parameter.
		$this->assertNotContains(
			'from',
			$wrapper->getSupportedQueryParams(),
			'Path parameter must not be exposed as a query parameter'
		);

		// It is declared with the 'path' source, while a genuine query
		// parameter keeps the 'query' source.
		$settings = $handler->getParamSettings();
		$this->assertSame( 'path', $settings['from'][Handler::PARAM_SOURCE] );
		$this->assertSame( 'query', $settings['to'][Handler::PARAM_SOURCE] );

		// The value from the path is the one passed to the action module; the
		// same-named query parameter is ignored.
		$params = $wrapper->getActionModuleParameters();
		$this->assertSame( 'FromPath', $params['from'], 'Path value must win over query param' );
	}

	public function testGetParamSettings() {
		$module = $this->newHybridModule();
		$handler = $this->newHandler( [ 'module' => $module, 'init' => true ] );

		$moduleParams = $module->getFinalParams();
		$handlerParams = $handler->getParamSettings();

		// All parameters must be looped through
		foreach ( array_keys( $moduleParams ) as $name ) {
			$this->assertArrayHasKey(
				$name,
				$handlerParams,
				"Hybrid module param '$name' must be exposed in REST param handlerParams"
			);

			$handlerParamSpec = $handlerParams[ $name ];
			$this->assertIsArray( $handlerParamSpec, "Setting for $name must be an array" );

			// check that the help message got converted correctly
			$this->assertArrayNotHasKey( ApiBase::PARAM_HELP_MSG, $handlerParamSpec );
			$this->assertArrayHasKey( Handler::PARAM_DESCRIPTION, $handlerParamSpec );
			$this->assertInstanceOf(
				MessageValue::class,
				$handlerParamSpec[ Handler::PARAM_DESCRIPTION ]
			);

			$this->assertArrayHasKey(
				Handler::PARAM_SOURCE,
				$handlerParamSpec,
				"Setting for $name must declare a source"
			);
		}

		// The module declares 'from' => null, 'reason' => '', and
		// 'movetalk' => false in scalar shorthand form. The handler must
		// normalize them to array form before returning.
		$this->assertNull( $handlerParams['from'][ParamValidator::PARAM_DEFAULT] );
		$this->assertSame( '', $handlerParams['reason'][ParamValidator::PARAM_DEFAULT] );
		$this->assertFalse( $handlerParams['movetalk'][ParamValidator::PARAM_DEFAULT] );
	}

	/**
	 * A handler whose action-module param specs are fixed to $specs, so
	 * makeParamSettings() can be exercised directly without a real ApiMain.
	 * The action name is fixed to 'testaction' for a deterministic default
	 * description message key.
	 */
	private function newHandlerForParamSpecs( array $specs ): GenericActionHandler {
		return new class( 'testaction', $specs ) extends GenericActionHandler {
			public function __construct( string $actionName, private array $specs ) {
				parent::__construct( $actionName );
			}

			protected function getActionModuleParamSpecs(): array {
				return $this->specs;
			}
		};
	}

	public static function provideMakeParamSettings() {
		$defaultDesc = new MessageValue( 'apihelp-testaction-param-myparam' );

		// Scalar-shorthand specs: just a default value, no array. PARAM_TYPE is
		// derived from the default via gettype(), so falsy values must keep
		// their real type (false => boolean, 0 => integer, '' / '0' => string)
		// rather than collapsing to a null/absent type.
		yield 'shorthand false' => [
			false,
			[ ParamValidator::PARAM_DEFAULT => false, ParamValidator::PARAM_TYPE => 'boolean' ],
			$defaultDesc,
		];
		yield 'shorthand zero (int)' => [
			0,
			[ ParamValidator::PARAM_DEFAULT => 0, ParamValidator::PARAM_TYPE => 'integer' ],
			$defaultDesc,
		];
		yield 'shorthand empty string' => [
			'',
			[ ParamValidator::PARAM_DEFAULT => '', ParamValidator::PARAM_TYPE => 'string' ],
			$defaultDesc,
		];
		yield 'shorthand string "0"' => [
			'0',
			[ ParamValidator::PARAM_DEFAULT => '0', ParamValidator::PARAM_TYPE => 'string' ],
			$defaultDesc,
		];
		yield 'shorthand zero (float)' => [
			0.0,
			[ ParamValidator::PARAM_DEFAULT => 0.0, ParamValidator::PARAM_TYPE => 'double' ],
			$defaultDesc,
		];
		// The only falsy shorthand with no scalar type: gettype( null ) is the
		// string 'NULL', which ParamValidator (and the REST Validator) register
		// as a real type (StringDef, empty allowed). So a null default resolves
		// to a permissive string param; this mirrors
		// ParamValidator::normalizeSettingsInternal().
		yield 'shorthand null' => [
			null,
			[ ParamValidator::PARAM_DEFAULT => null, ParamValidator::PARAM_TYPE => 'NULL' ],
			$defaultDesc,
		];
		yield 'shorthand true' => [
			true,
			[ ParamValidator::PARAM_DEFAULT => true, ParamValidator::PARAM_TYPE => 'boolean' ],
			$defaultDesc,
		];
		yield 'shorthand non-empty string' => [
			'hello',
			[ ParamValidator::PARAM_DEFAULT => 'hello', ParamValidator::PARAM_TYPE => 'string' ],
			$defaultDesc,
		];

		// Array specs: an explicit PARAM_TYPE is kept as-is...
		yield 'array with explicit type, no default' => [
			[ ParamValidator::PARAM_TYPE => 'integer' ],
			[ ParamValidator::PARAM_TYPE => 'integer' ],
			$defaultDesc,
		];
		// ...otherwise the type is derived from the default (falsy included).
		yield 'array with default, type derived' => [
			[ ParamValidator::PARAM_DEFAULT => 7 ],
			[ ParamValidator::PARAM_DEFAULT => 7, ParamValidator::PARAM_TYPE => 'integer' ],
			$defaultDesc,
		];
		yield 'array with falsy default, type derived' => [
			[ ParamValidator::PARAM_DEFAULT => false ],
			[ ParamValidator::PARAM_DEFAULT => false, ParamValidator::PARAM_TYPE => 'boolean' ],
			$defaultDesc,
		];
		// PARAM_HELP_MSG (a bare key) becomes the description; the raw key is dropped.
		yield 'array with help message key' => [
			[ ParamValidator::PARAM_TYPE => 'string', ApiBase::PARAM_HELP_MSG => 'apihelp-move-param-reason' ],
			[ ParamValidator::PARAM_TYPE => 'string' ],
			new MessageValue( 'apihelp-move-param-reason' ),
		];
		// PARAM_HELP_MSG as [ key, params... ] carries the params into the description.
		yield 'array with help message key and params' => [
			[ ParamValidator::PARAM_TYPE => 'string', ApiBase::PARAM_HELP_MSG => [ 'apihelp-move-param-to', 'p1' ] ],
			[ ParamValidator::PARAM_TYPE => 'string' ],
			new MessageValue( 'apihelp-move-param-to', [ 'p1' ] ),
		];
	}

	/**
	 * @dataProvider provideMakeParamSettings
	 */
	public function testMakeParamSettings( $inputSpec, array $expectedCore, MessageValue $expectedDescription ) {
		$handler = $this->newHandlerForParamSpecs( [ 'myparam' => $inputSpec ] );

		$result = TestingAccessWrapper::newFromObject( $handler )
			->makeParamSettings( [ 'myparam' ], 'query' );

		$this->assertArrayHasKey( 'myparam', $result );
		$spec = $result['myparam'];

		// The source is always the one passed in.
		$this->assertSame( 'query', $spec[Handler::PARAM_SOURCE], 'source' );

		// The help message is turned into a description, and the raw
		// PARAM_HELP_MSG key is removed.
		$this->assertArrayNotHasKey( ApiBase::PARAM_HELP_MSG, $spec );
		$this->assertEquals( $expectedDescription, $spec[Handler::PARAM_DESCRIPTION], 'description' );

		// Default and derived type. assertSame keeps
		// falsy defaults distinct (false vs 0 vs '' vs '0' vs null).
		unset( $spec[Handler::PARAM_SOURCE], $spec[Handler::PARAM_DESCRIPTION] );
		$this->assertSame( $expectedCore, $spec );
	}

	public static function provideBooleanParameters() {
		// Value as it arrives from the client => boolean the wrapped module
		// should see after REST-style (BooleanDef) interpretation. Under the
		// action API's PresenceBooleanDef, every one of these would be true,
		// since the parameter is present regardless of value.
		yield 'literal true' => [ true, true ];
		yield 'string "true"' => [ 'true', true ];
		yield 'string "1"' => [ '1', true ];
		yield 'string "yes"' => [ 'yes', true ];
		yield 'literal false' => [ false, false ];
		yield 'string "false"' => [ 'false', false ];
		yield 'string "0"' => [ '0', false ];
		yield 'empty string' => [ '', false ];
	}

	/**
	 * @dataProvider provideBooleanParameters
	 */
	public function testBooleanParameterInterpretation( $sentValue, bool $expected ) {
		$handler = $this->newHandler( [
			'module' => $this->newHybridModule( actionName: 'fakeaction' ),
		] );
		$request = new RequestData( [
			'method' => 'POST',
			// 'to' is required by the hybrid module.
			'parsedBody' => [ 'to' => 'B', 'movetalk' => $sentValue ],
		] );

		$data = $this->executeHandlerAndGetBodyData( $handler, $request );

		// The essential assertion: presence no longer implies true; the value
		// decides, and the module sees a real PHP boolean.
		$this->assertSame( $expected, $data['PARAMETERS']['movetalk'] );
	}

	public function testMissingBooleanWithDefaultIsFalse() {
		// 'movetalk' is declared with an explicit default of false, so an
		// absent value resolves to false even under BooleanDef.
		$handler = $this->newHandler( [
			'module' => $this->newHybridModule( actionName: 'fakeaction' ),
		] );
		$request = new RequestData( [
			'method' => 'POST',
			'parsedBody' => [ 'to' => 'B' ], // movetalk omitted
		] );

		$data = $this->executeHandlerAndGetBodyData( $handler, $request );

		$this->assertFalse( $data['PARAMETERS']['movetalk'] );
	}

	public function testMissingBooleanWithoutDefaultIsFalse() {
		// 'redirect' is a boolean declared with NO explicit default. Plain
		// BooleanDef would leave an absent value as null; BinaryBooleanDef
		// supplies a false default (like the action API's PresenceBooleanDef),
		// so an omitted boolean resolves to false rather than null.
		$handler = $this->newHandler( [
			'module' => $this->newHybridModule( actionName: 'fakeaction' ),
		] );
		$request = new RequestData( [
			'method' => 'POST',
			'parsedBody' => [ 'to' => 'B' ], // redirect omitted
		] );

		$data = $this->executeHandlerAndGetBodyData( $handler, $request );

		$this->assertFalse( $data['PARAMETERS']['redirect'] );
	}

	public function testGetOpenApiSpec() {
		static $bodySchema = [
			'type' => 'object',
			'properties' => [
				'from' => [ 'type' => 'string' ],
				'to' => [ 'type' => 'string' ],
			],
		];

		$helper = new class( $bodySchema ) extends ApiRestHelper {
			public function __construct( private array $bodySchema ) {
			}

			public function getResponseBodySchema(): ?array {
				return $this->bodySchema;
			}
		};

		$module = $this->newHybridModule( helper: $helper );
		$handler = $this->newHandler( [
			'module' => $module,
			'init' => true,
			'config' => [ 'method' => 'GET' ],
		] );

		$spec = $handler->getOpenApiSpec( 'get' );

		// Every param the hybrid module declared in getAllowedParams() must
		// appear in the OpenAPI spec's parameters list.
		$paramNames = array_column( $spec['parameters'], 'name' );
		foreach ( array_keys( $module->getFinalParams() ) as $expected ) {
			$this->assertContains(
				$expected,
				$paramNames,
				"OpenAPI spec must include action module param '$expected'"
			);
		}

		// The helper's getResponseBodySchema() must be the schema used for
		// the 200 response body.
		$this->assertSame(
			$bodySchema,
			$spec['responses']['200']['content']['application/json']['schema']
		);
	}

	public function testGetOpenApiSpec_post_and_schema_file() {
		$expectedSchema = json_decode( file_get_contents(
			__DIR__ . '/GenericActionHandlerTest.schema.json'
		), true );

		$helper = new class() extends ApiRestHelper {
			public function getResponseBodySchemaFileName(): string {
				return __DIR__ . '/GenericActionHandlerTest.schema.json';
			}
		};

		$module = $this->newHybridModule( helper: $helper );
		$handler = $this->newHandler( [
			'module' => $module,
			'init' => true,
			'config' => [ 'method' => 'POST' ],
		] );

		$spec = $handler->getOpenApiSpec( 'post' );

		// Every param the hybrid module declared in getAllowedParams() must
		// appear in the OpenAPI spec's request body schema (because it's a POST route).
		$this->assertEquals( [], $spec['parameters'], 'No parameters expected for POST route' );
		$paramNames = array_keys( $spec['requestBody']['content']['application/json']['schema']['properties'] );
		foreach ( array_keys( $module->getFinalParams() ) as $expected ) {
			$this->assertContains(
				$expected,
				$paramNames,
				"OpenAPI spec must include action module param '$expected'"
			);
		}

		// The helper's getResponseBodySchema() must be the schema used for
		// the 200 response body.
		$this->assertSame(
			$expectedSchema,
			$spec['responses']['200']['content']['application/json']['schema']
		);
	}
}
