<?php

namespace MediaWiki\Tests\Rest\Handler;

use Closure;
use Exception;
use LogicException;
use MediaWiki\Api\ApiBase;
use MediaWiki\Api\ApiMain;
use MediaWiki\Api\ApiMessage;
use MediaWiki\Api\ApiRestHelper;
use MediaWiki\Api\ApiRestHelperContext;
use MediaWiki\Api\ApiRestHybrid;
use MediaWiki\Api\ApiUsageException;
use MediaWiki\Api\IApiMessage;
use MediaWiki\MainConfigNames;
use MediaWiki\Request\FauxResponse;
use MediaWiki\Rest\Handler;
use MediaWiki\Rest\Handler\GenericActionHandler;
use MediaWiki\Rest\LocalizedHttpException;
use MediaWiki\Rest\RequestData;
use MediaWiki\Rest\Response;
use MediaWiki\Session\Session;
use MediaWiki\Session\Token;
use MediaWiki\User\LoggedOutEditToken;
use MediaWiki\User\User;
use MediaWikiIntegrationTestCase;
use StatusValue;
use Wikimedia\Message\MessageValue;
use Wikimedia\Message\ParamType;
use Wikimedia\Message\ScalarParam;
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
	 *   - adapterConfig: the route's adapter spec, e.g. a 'suppressedParams'
	 *     list (default []).
	 *   - queryOnlyParams: names to report from getQueryOnlyParams(), which
	 *     doesn't report any by default (default []).
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
			'adapterConfig' => [],
			'queryOnlyParams' => [],
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

		$handler = new class(
			$module->getModuleName(),
			$options['adapterConfig'],
			$options['queryOnlyParams']
		) extends GenericActionHandler {
			public function __construct( string $actionName, array $adapterConfig, private array $queryOnlyParams ) {
				parent::__construct( $actionName, $adapterConfig );
			}

			protected function getQueryOnlyParams(): array {
				return $this->queryOnlyParams;
			}
		};
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
		array $resultData = [],
		?Closure $onInitRestMode = null
	): ApiRestHybrid&ApiBase {
		$helper ??= new ApiRestHelper( new ApiRestHelperContext( [], [] ) );

		/** @var ApiRestHybrid & ApiBase $module */
		$module = new class( $this->apiMain, $actionName, $helper, $resultData, $throwException, $onInitRestMode )
			extends ApiBase implements ApiRestHybrid
		{
			private ?ApiRestHelperContext $context = null;

			public function __construct(
				ApiMain $main,
				string $name,
				private ApiRestHelper $helper,
				private array $resultData,
				private ?Exception $throwException,
				private ?Closure $onInitRestMode
			) {
				parent::__construct( $main, $name );
			}

			/**
			 * The handler must inject the context before it uses the module in any way.
			 */
			private function assertRestModeInitialized(): void {
				if ( !$this->context ) {
					throw new LogicException( 'The module was used before initRestMode() was called' );
				}
			}

			public function execute() {
				$this->assertRestModeInitialized();
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

			public function initRestMode( ApiRestHelperContext $context ): ApiRestHelper {
				$this->context = $context;
				if ( $this->onInitRestMode ) {
					( $this->onInitRestMode )( $context );
				}
				return $this->helper;
			}

			protected function getAllowedParams() {
				$this->assertRestModeInitialized();
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
	 *
	 * hasToken() is deliberately left unstubbed, so it reports that the session
	 * holds no token yet. Session::getToken() mints one on demand, so the handler
	 * must ask for the token rather than checking hasToken() first.
	 */
	private function newCsrfSession( bool $csrfSafe, bool $anon, Token $token ): Session {
		$session = $this->getSession( $csrfSafe );

		$user = $this->createMock( User::class );
		$user->method( 'isAnon' )->willReturn( $anon );

		$session->method( 'getUser' )->willReturn( $user );
		$session->method( 'getToken' )->willReturn( $token );

		return $session;
	}

	public function testInitRestMode_receivesAdapterAndHandlerConfig() {
		$contexts = [];
		$module = $this->newHybridModule(
			onInitRestMode: static function ( ApiRestHelperContext $context ) use ( &$contexts ) {
				$contexts[] = $context;
			}
		);
		$adapterConfig = [ GenericActionHandler::SUPPRESSED_PARAMS_KEY => [ 'movetalk' ] ];
		$handler = $this->newHandler( [
			'module' => $module,
			'adapterConfig' => $adapterConfig,
			'init' => true,
			'config' => [ 'method' => 'GET', 'path' => '/test/path' ],
		] );

		// Anything that needs the module triggers the injection.
		$handler->getParamSettings();

		$this->assertCount( 1, $contexts );
		// The essential assertions: the context carries the route's adapter spec,
		// and the handler's own config, which is the route config.
		$this->assertSame( $adapterConfig, $contexts[0]->getAdapterConfig() );
		$this->assertSame( $handler->getConfig(), $contexts[0]->getRouteConfig() );
		$this->assertSame( '/test/path', $contexts[0]->getRouteConfig()['path'] );
	}

	public function testInitRestMode_calledOnceBeforeTheModuleIsUsed() {
		$contexts = [];
		$module = $this->newHybridModule(
			actionName: 'fakeaction',
			onInitRestMode: static function ( ApiRestHelperContext $context ) use ( &$contexts ) {
				$contexts[] = $context;
			}
		);
		$handler = $this->newHandler( [ 'module' => $module ] );
		$request = new RequestData( [
			'method' => 'POST',
			// 'to' is required by the fake action module.
			'parsedBody' => [ 'from' => 'A', 'to' => 'B' ],
		] );

		// The module throws if the handler uses it before injecting the context
		// (to declare parameters, to validate the request, or to execute), so
		// executing successfully is the essential assertion.
		$this->executeHandlerAndGetBodyData( $handler, $request );

		$this->assertCount( 1, $contexts, 'The context should be injected once, not per use' );
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
			'queryParams' => [
				'from' => 'SourceOne',
				'to' => 'Target',
				// ApiMain params are not exposed, see T436749.
				'origin' => '*',
				'requestid' => 'abc',
			],
			// Extra body fields are rejected, see testExtraneousBodyFieldsAreRejected().
			'parsedBody' => [ 'from' => 'SourceTwo' ],
		] );

		// Run as a POST route, as in production, so the body params are declared.
		$data = $this->executeHandlerAndGetBodyData( $handler, $request, [ 'method' => 'POST' ] );

		// The fake hybrid module has that hard coded
		$params = $data['PARAMETERS'];

		// check supplied module parameters
		$this->assertSame( 'SourceTwo', $params['from'] );
		$this->assertSame( 'Target', $params['to'] );

		// check injected main parameters
		$mainParams = $data['MAIN_PARAMETERS'];

		// test filtered parameters
		$this->assertNull( $mainParams['origin'], 'ApiMain parameter should be dropped' );
		$this->assertNull( $mainParams['requestid'], 'ApiMain parameter should be dropped' );
		$this->assertSame( 'fakeaction', $mainParams['action'] );
		$this->assertSame( 'json', $mainParams['format'] );
		$this->assertSame( 'plaintext', $mainParams['errorformat'] );

		// NOTE: formatversion is not in ApiMain but in ApiFormat.
	}

	/**
	 * Unlike extra body fields, extra query parameters are ignored, consistent
	 * with how the REST framework treats query parameters for all handlers.
	 */
	public function testExtraQueryParamIsIgnored() {
		$handler = $this->newHandler( [
			'module' => $this->newHybridModule( actionName: 'fakeaction' ),
		] );
		$request = new RequestData( [
			'method' => 'GET',
			// 'to' is required by the fake action module.
			'queryParams' => [ 'to' => 'B', 'extra' => '123' ],
		] );

		// The essential assertion is implicit: the request succeeds.
		$data = $this->executeHandlerAndGetBodyData( $handler, $request );

		$this->assertArrayNotHasKey( 'extra', $data['PARAMETERS'], 'Extra parameter should be ignored' );
		$this->assertSame( 'B', $data['PARAMETERS']['to'] );
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
		$helper = new class ( new ApiRestHelperContext( [], [] ) ) extends ApiRestHelper {
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
				'origin' => '*', // ApiMain param, should be stripped
				'requestid' => 'abc', // ApiMain param, should be stripped
				'format' => 'xml'   // ApiMain param; ActionModuleBasedHandler forces 'json'
			],
		] );

		$params = $handler->getActionModuleParameters();

		$this->assertArrayNotHasKey( 'origin', $params, 'ApiMain param should be stripped' );
		$this->assertArrayNotHasKey( 'requestid', $params, 'ApiMain param should be stripped' );
		$this->assertArrayNotHasKey( 'format', $params, 'ApiMain param should be stripped' );
		$this->assertSame( 'test', $params['reason'], 'Body param should be used' );
		$this->assertSame( 'YY', $params['to'], 'Body param should win over query param' );
		$this->assertSame( 'X', $params['from'], 'Query param should be used when absent from body' );
		$this->assertArrayNotHasKey( '123', $params, 'Extra param should have been stripped' );
	}

	public static function provideCsrfTokenInjection() {
		// csrfSafe, anon, expected outcome
		// A session that is not safe against CSRF (cookie auth) must supply the
		// token itself.
		yield 'unsafe + logged-in: no token injected' => [ false, false, 'none' ];
		yield 'unsafe + anonymous: no token injected' => [ false, true, 'none' ];

		// A CSRF-safe session (e.g. OAuth) needs no CSRF protection, but the
		// action API validates the token regardless, so inject a valid one.
		yield 'safe + anonymous: logged-out edit token' => [ true, true, 'loggedout' ];
		yield 'safe + logged-in: session token' => [ true, false, 'session' ];
	}

	/**
	 * @dataProvider provideCsrfTokenInjection
	 */
	public function testCsrfTokenInjection(
		bool $csrfSafe,
		bool $anon,
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

		$token = $this->createMock( Token::class );
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
				// The essential assertion: a session that is not safe against
				// CSRF must NOT have a token fabricated for it, or the action
				// module's token check is bypassed.
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

		// The handler injects the REST context into the module, which may
		// change the module's parameters, so ask the handler first.
		$handlerParams = $handler->getParamSettings();
		$moduleParams = $module->getFinalParams();

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
		// PARAM_HELP_MSG may already be a message object (see ApiBase::PARAM_HELP_MSG);
		yield 'help message object' => [
			[ ParamValidator::PARAM_TYPE => 'string', ApiBase::PARAM_HELP_MSG => new MessageValue( 'apihelp-move-param-to' ) ],
			[ ParamValidator::PARAM_TYPE => 'string' ],
			new MessageValue( 'apihelp-move-param-to' ),
		];
		yield 'help message object with params' => [
			[
				ParamValidator::PARAM_TYPE => 'string',
				ApiBase::PARAM_HELP_MSG => new MessageValue( 'apihelp-move-param-to', [ 'p1', 2 ] )
			],
			[ ParamValidator::PARAM_TYPE => 'string' ],
			new MessageValue( 'apihelp-move-param-to', [ 'p1', 2 ] ),
		];
		yield 'help message object with numParams' => [
			[
				ParamValidator::PARAM_TYPE => 'string',
				ApiBase::PARAM_HELP_MSG => ( new MessageValue( 'apihelp-move-param-to' ) )
					->numParams( 10 )
			],
			[ ParamValidator::PARAM_TYPE => 'string' ],
			new MessageValue( 'apihelp-move-param-to',
				[ new ScalarParam( ParamType::NUM, 10 ) ] ),
		];
		// Help messages with wikitext links are replaced by their Markdown version...
		yield 'help message with a Markdown version' => [
			[ ParamValidator::PARAM_TYPE => 'string', ApiBase::PARAM_HELP_MSG => 'api-help-param-continue' ],
			[ ParamValidator::PARAM_TYPE => 'string' ],
			new MessageValue( 'api-help-param-continue.md' ),
		];
		// ...which keep the params.
		yield 'help message with a Markdown version and params' => [
			[ ParamValidator::PARAM_TYPE => 'string', ApiBase::PARAM_HELP_MSG => [ 'api-help-param-token', 'csrf' ] ],
			[ ParamValidator::PARAM_TYPE => 'string' ],
			new MessageValue( 'api-help-param-token.md', [ 'csrf' ] ),
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

	public static function provideMarkdownHelpMessages() {
		$messages = TestingAccessWrapper::constant(
			GenericActionHandler::class, 'MARKDOWN_HELP_MESSAGES'
		);
		foreach ( $messages as $helpMsg ) {
			yield $helpMsg => [ $helpMsg, "$helpMsg.md" ];
		}
	}

	/**
	 * @dataProvider provideMarkdownHelpMessages
	 */
	public function testMarkdownHelpMessages( string $helpMsg, string $markdownMsg ) {
		// Use a protocol-relative server, as WMF wikis do, to show that
		// links still get full URLs.
		$this->overrideConfigValues( [
			MainConfigNames::Server => '//example.org',
			MainConfigNames::CanonicalServer => 'https://example.org',
			MainConfigNames::ArticlePath => '/wiki/$1',
		] );

		$this->assertTrue( wfMessage( $helpMsg )->exists(), "$helpMsg exists" );
		$this->assertTrue( wfMessage( $markdownMsg )->exists(), "$markdownMsg exists" );

		$text = wfMessage( $markdownMsg, 'csrf' )->inLanguage( 'en' )->text();

		$this->assertStringNotContainsString( '[[', $text, 'no wikitext links' );
		$this->assertStringNotContainsString( '<', $text, 'no HTML' );
		preg_match_all( '/\]\(([^)]*)\)/', $text, $urls );
		$this->assertNotEmpty( $urls[1], 'has a Markdown link' );
		foreach ( $urls[1] as $url ) {
			$this->assertStringStartsWith( 'https://', $url, 'full URL' );
		}
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

		$helper = new class ( new ApiRestHelperContext( [], [] ), $bodySchema ) extends ApiRestHelper {
			public function __construct( ApiRestHelperContext $context, private array $bodySchema ) {
				parent::__construct( $context );
			}

			public function getResponseBodySchema(): array {
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

		$helper = new ApiRestHelper( new ApiRestHelperContext( [], [] ), __DIR__ . '/GenericActionHandlerTest.schema.json' );

		$module = $this->newHybridModule( helper: $helper );
		$handler = $this->newHandler( [
			'module' => $module,
			'init' => true,
			'config' => [ 'method' => 'POST' ],
		] );

		$spec = $handler->getOpenApiSpec( 'post' );

		// Every param the hybrid module declared in getAllowedParams() must
		// appear in the OpenAPI spec's request body schema (because it's a POST route).
		$this->assertSame(
			[],
			array_column( $spec['parameters'], 'name' ),
			'No query parameters expected for POST route'
		);
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

	/**
	 * An adapter endpoint exposes only the wrapped module's own parameters,
	 * see T436749. The exact set is pinned so that a parameter added to
	 * ApiMain, by core or by an extension, is shown not to leak into the endpoint.
	 */
	public function testGetActionModuleParamSpecs() {
		$handler = $this->newHandler( [ 'module' => $this->newHybridModule(), 'init' => true ] );

		$specs = TestingAccessWrapper::newFromObject( $handler )
			->getActionModuleParamSpecs();

		$expected = [
			// declared by the fake action module
			'from', 'fromid', 'to', 'reason', 'movetalk', 'redirect', 'tags',
		];

		$actual = array_keys( $specs );
		sort( $expected );
		sort( $actual );

		$this->assertSame( $expected, $actual );
	}

	/**
	 * Help messages get the params that ApiBase::getFinalParamDescription()
	 * adds, after their own, so that e.g. "$1fromid" reads "fromid".
	 */
	public function testGetActionModuleParamSpecs_helpMessageParams() {
		$helpMsg = new MessageValue( 'apihelp-test-param-to' );

		// A module that requires a token, so ApiBase adds a "token"
		// parameter whose help message has a param of its own.
		$module = new class( $this->apiMain, 'test', $helpMsg ) extends ApiBase {
			public function __construct( ApiMain $main, string $name, private MessageValue $helpMsg ) {
				parent::__construct( $main, $name );
			}

			public function execute() {
			}

			public function needsToken() {
				return 'csrf';
			}

			protected function getAllowedParams() {
				return [ 'to' => [ ApiBase::PARAM_HELP_MSG => $this->helpMsg ] ];
			}
		};

		$specs = TestingAccessWrapper::newFromObject( $this->newHandler( [ 'module' => $module ] ) )
			->getActionModuleParamSpecs();

		$this->assertEquals(
			new MessageValue( 'api-help-param-token.md', [ 'csrf', '', 'token', 'test', 'test' ] ),
			$specs['token'][ApiBase::PARAM_HELP_MSG],
			'The message\'s own params should come first'
		);
		$this->assertSame( [], $helpMsg->getParams(), 'The module\'s message should not be modified' );
	}

	public static function provideSuppressedApiMainParams() {
		// To be handled by the REST framework instead, probably as headers.
		yield 'maxlag' => [ 'maxlag', '5' ];
		yield 'assert' => [ 'assert', 'user' ];
		yield 'assertuser' => [ 'assertuser', 'Example' ];
		yield 'errorlang' => [ 'errorlang', 'de' ];

		// The REST framework handles the Origin header, see CorsUtils.
		yield 'origin' => [ 'origin', '*' ];
		yield 'crossorigin' => [ 'crossorigin', '1' ];

		// Not expected to be supported in REST.
		yield 'uselang' => [ 'uselang', 'de' ];
		yield 'variant' => [ 'variant', 'sr-el' ];
		yield 'maxage' => [ 'maxage', '60' ];
		yield 'smaxage' => [ 'smaxage', '60' ];
		yield 'errorsuselocal' => [ 'errorsuselocal', '1' ];
	}

	/**
	 * A suppressed ApiMain parameter must not be advertised in the OpenAPI
	 * spec, whether the route declares its parameters as query or as body
	 * parameters.
	 *
	 * @dataProvider provideSuppressedApiMainParams
	 */
	public function testSuppressedApiMainParamIsNotDeclared( string $name ) {
		foreach ( [ 'GET', 'POST' ] as $method ) {
			$handler = $this->newHandler( [
				'module' => $this->newHybridModule(),
				'init' => true,
				'config' => [ 'method' => $method ],
			] );

			$declared = $handler->getParamSettings() + $handler->getBodyParamSettings();

			$this->assertArrayNotHasKey( $name, $declared, "$method route must not declare '$name'" );

			// The module's own parameters must still be declared, so that an
			// empty result cannot satisfy the assertion above.
			$this->assertArrayHasKey( 'from', $declared, "$method route must still declare 'from'" );
		}
	}

	/**
	 * A client that supplies a suppressed ApiMain parameter anyway must not
	 * get it through to the action API, in the query string or in the body.
	 *
	 * @dataProvider provideSuppressedApiMainParams
	 */
	public function testSuppressedApiMainParamIsNotForwarded( string $name, string $value ) {
		$handler = $this->newHandler( [
			'module' => $this->newHybridModule(),
			'init' => true,
			'request' => new RequestData( [
				'method' => 'POST',
				'queryParams' => [ $name => $value, 'from' => 'A' ],
				'parsedBody' => [ $name => $value, 'to' => 'B' ],
			] ),
			'config' => [ 'method' => 'POST' ],
		] );

		$params = TestingAccessWrapper::newFromObject( $handler )->getActionModuleParameters();

		$this->assertArrayNotHasKey( $name, $params );

		// The module's own parameters, from the same request, do get through.
		$this->assertSame( 'A', $params['from'] );
		$this->assertSame( 'B', $params['to'] );
	}

	public static function provideDeclaredParamSources() {
		// A route whose method takes no request body declares its parameters
		// as query parameters, via getParamSettings()...
		yield 'GET route declares query parameters' => [ 'GET', 'query' ];
		// ...one that does declares them as body parameters instead, via
		// getBodyParamSettings(). Both have to honour the suppression list.
		yield 'POST route declares body parameters' => [ 'POST', 'body' ];
	}

	/**
	 * The suppressedParams list comes from the route's adapter spec, so it has
	 * to be reflected in the parameters the endpoint declares, not just applied
	 * internally.
	 *
	 * @dataProvider provideDeclaredParamSources
	 */
	public function testSuppressedParamIsNotDeclared( string $method, string $expectedSource ) {
		$handler = $this->newHandler( [
			'module' => $this->newHybridModule(),
			'init' => true,
			'config' => [ 'method' => $method ],
			'adapterConfig' => [
				GenericActionHandler::SUPPRESSED_PARAMS_KEY => [ 'movetalk' ],
			],
		] );

		// The two declaration methods are complementary: whichever one carries
		// the module's parameters for this route, the suppression must apply.
		$declared = $handler->getParamSettings() + $handler->getBodyParamSettings();

		$this->assertArrayNotHasKey(
			'movetalk', $declared, 'Suppressed parameter must not be declared'
		);

		// The essential assertion for the POST case: the module's remaining
		// parameters are still declared, as body parameters, rather than being
		// lost along with the query parameters getParamSettings() omits for
		// methods that take a body.
		$this->assertArrayHasKey( 'from', $declared );
		$this->assertSame( $expectedSource, $declared['from'][Handler::PARAM_SOURCE] );
	}

	/**
	 * Suppressing a parameter has to be enforced, not just undocumented: a
	 * client that supplies it anyway must not reach the action module with it.
	 * Declaration and enforcement read the same spec map today, but they are
	 * separate contracts (compare the T436749 TODO in
	 * getActionModuleParameters()).
	 */
	public function testSuppressedParamIsNotForwarded() {
		$handler = $this->newHandler( [
			'module' => $this->newHybridModule( actionName: 'fakeaction' ),
			'adapterConfig' => [
				GenericActionHandler::SUPPRESSED_PARAMS_KEY => [ 'movetalk' ],
			],
		] );
		$request = new RequestData( [
			'method' => 'POST',
			// 'to' is required by the fake action module.
			'parsedBody' => [ 'to' => 'B', 'movetalk' => true ],
		] );

		$data = $this->executeHandlerAndGetBodyData( $handler, $request );

		// movetalk defaults to false; the client's true must have been dropped.
		$this->assertFalse( $data['PARAMETERS']['movetalk'] );
	}

	public static function provideNonBodyQueryParamDeclarations() {
		// A route without a request body declares everything as query params.
		yield 'GET' => [ 'GET', 'query' ];
		// A route with a body declares the module's params as body params,
		// except for those that have to stay in the query string.
		yield 'POST' => [ 'POST', 'body' ];
	}

	/**
	 * Parameters listed by getQueryOnlyParams() are declared as query
	 * parameters whatever the route's method, and never as body parameters.
	 *
	 * @dataProvider provideNonBodyQueryParamDeclarations
	 */
	public function testNonBodyQueryParamsAreDeclaredAsQuery( string $method, string $moduleParamSource ) {
		$handler = $this->newHandler( [
			'module' => $this->newHybridModule(),
			'init' => true,
			'config' => [ 'method' => $method ],
			'queryOnlyParams' => [ 'reason' ],
		] );

		$queryParams = $handler->getParamSettings();
		$bodyParams = $handler->getBodyParamSettings();

		$this->assertSame( 'query', $queryParams['reason'][Handler::PARAM_SOURCE] );
		$this->assertArrayNotHasKey( 'reason', $bodyParams );

		// Ordinary module params still follow the route's method, so the
		// special case doesn't leak into them.
		$declared = $queryParams + $bodyParams;
		$this->assertSame( $moduleParamSource, $declared['from'][Handler::PARAM_SOURCE] );
	}

	public static function provideNonBodyQueryParamIsForwarded() {
		yield 'GET' => [ 'GET', [ 'to' => 'B', 'reason' => 'test' ], null ];
		// On a POST route, the query-only param is read from the query string
		// while the module's own params come from the body.
		yield 'POST' => [ 'POST', [ 'reason' => 'test' ], [ 'to' => 'B' ] ];
	}

	/**
	 * A query-only parameter given in the query string reaches the action
	 * module, on routes with and without a request body.
	 *
	 * @dataProvider provideNonBodyQueryParamIsForwarded
	 */
	public function testNonBodyQueryParamIsForwarded( string $method, array $query, ?array $body ) {
		$handler = $this->newHandler( [
			'module' => $this->newHybridModule( actionName: 'fakeaction' ),
			'queryOnlyParams' => [ 'reason' ],
		] );
		$request = new RequestData( [
			'method' => $method,
			'queryParams' => $query,
			'parsedBody' => $body,
		] );

		// For POST, the essential part is implicit: the body check doesn't
		// reject the request, since 'reason' isn't in the body.
		$data = $this->executeHandlerAndGetBodyData( $handler, $request, [ 'method' => $method ] );

		$this->assertSame( 'test', $data['PARAMETERS']['reason'] );
		$this->assertSame( 'B', $data['PARAMETERS']['to'] );
	}

	public static function provideExtraneousBodyFields() {
		yield 'unknown field' => [ [ 'extra' => 123 ], [ 'extra' ] ];
		// The framework sets the format itself, so the client can't.
		yield 'framework-controlled field' => [ [ 'format' => 'xml' ], [ 'format' ] ];
		yield 'several fields' => [ [ 'extra' => 1, 'bogus' => 2 ], [ 'extra', 'bogus' ] ];
		// An ApiMain parameter, which is not exposed, see T436749.
		yield 'ApiMain parameter' => [ [ 'origin' => '*' ], [ 'origin' ] ];
	}

	/**
	 * Body fields the endpoint doesn't declare are rejected, rather than
	 * silently dropped, with the framework's standard error.
	 *
	 * @dataProvider provideExtraneousBodyFields
	 */
	public function testExtraneousBodyFieldsAreRejected( array $extraFields, array $expectedNames ) {
		$handler = $this->newHandler( [
			'module' => $this->newHybridModule( actionName: 'fakeaction' ),
		] );
		$request = new RequestData( [
			'method' => 'POST',
			// 'to' and 'reason' are declared by the fake action module, so the
			// error must name only the extraneous fields, not these.
			'parsedBody' => [ 'to' => 'B', 'reason' => 'test' ] + $extraFields,
		] );

		// The route's method decides which params are declared as body params.
		// Without it, no body params are declared, and the framework's check
		// ignores the body entirely.
		$ex = $this->executeHandlerAndGetHttpException(
			$handler, $request, [ 'method' => 'POST' ]
		);

		$this->assertInstanceOf( LocalizedHttpException::class, $ex );
		$this->assertSame( 400, $ex->getCode() );
		$this->assertSame( 'rest-extraneous-body-fields', $ex->getMessageValue()->getKey() );
		$this->assertSame( 'extraneous-body-fields', $ex->getErrorData()['failureCode'] );
		$this->assertSame( $expectedNames, $ex->getErrorData()['failureData'] );
	}
}
