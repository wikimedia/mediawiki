<?php

namespace MediaWiki\Tests\Rest\Handler;

use Exception;
use MediaWiki\Api\ApiBase;
use MediaWiki\Api\ApiMain;
use MediaWiki\Api\ApiMessage;
use MediaWiki\Api\ApiQuery;
use MediaWiki\Api\ApiQueryBase;
use MediaWiki\Api\ApiRestHelper;
use MediaWiki\Api\ApiRestHybrid;
use MediaWiki\Api\ApiUsageException;
use MediaWiki\Api\IApiMessage;
use MediaWiki\Rest\Handler\GenericListHandler;
use MediaWiki\Rest\RequestData;
use MediaWikiIntegrationTestCase;
use StatusValue;
use UnexpectedValueException;
use Wikimedia\ParamValidator\ParamValidator;
use Wikimedia\TestingAccessWrapper;

/**
 * Tests for GenericListHandler covering the behaviour it adds to
 * GenericActionHandler. Runs against a real ApiMain and ApiQuery with stub list
 * modules registered through the module manager of ApiQuery, so the full
 * execute() pipeline is exercised end-to-end.
 *
 * @covers \MediaWiki\Rest\Handler\GenericListHandler
 */
class GenericListHandlerTest extends MediaWikiIntegrationTestCase {
	use HandlerTestTrait;
	use ActionModuleBasedHandlerTestTrait;

	private ApiMain $apiMain;

	protected function setUp(): void {
		parent::setUp();
		$this->apiMain = $this->getApiMain( true );
	}

	/**
	 * Build a GenericListHandler for testing. All options are optional.
	 *
	 * @param array $options
	 *   - module: ApiQueryBase to wrap. Defaults to a dummy module built from the
	 *     listName/resultData/throwException options.
	 *   - listName: module name of the dummy module (default 'testlist').
	 *   - resultData: list items the dummy module returns (default []).
	 *   - throwException: Exception the dummy module throws (default null).
	 *   - init: whether to initialize the handler for direct method calls.
	 *     Execution tests leave this false and initialize via executeHandler()
	 *     instead (default false).
	 *   - request: RequestInterface to initialize with (default null).
	 *   - config: handler config, e.g. [ 'method' => ..., 'path' => ... ] (default []).
	 *   - session: Session to initialize with (default null).
	 *   - adapterConfig: the route's adapter spec, e.g. a 'suppressedParams'
	 *     list (default []).
	 */
	private function newHandler( array $options = [] ): GenericListHandler {
		$options += [
			'module' => null,
			'listName' => 'testlist',
			'resultData' => [],
			'throwException' => null,
			'init' => false,
			'request' => null,
			'config' => [],
			'session' => null,
			'adapterConfig' => [],
		];

		$module = $options['module'] ?? $this->getDummyListModule(
			$options['listName'],
			$options['resultData'],
			$options['throwException']
		);

		$this->overrideListModule( $module );

		$handler = new GenericListHandler( $module->getModuleName(), $options['adapterConfig'] );
		$handler->setApiMain( $this->apiMain );

		if ( $options['init'] ) {
			$this->initHandler(
				$handler, $options['request'], $options['config'], [], null, $options['session']
			);
		}

		return $handler;
	}

	private function getApiQuery(): ApiQuery {
		return $this->apiMain->getModuleManager()->getModule( 'query' );
	}

	/**
	 * Like getDummyApiModule(), but for a module of the "list" group of ApiQuery.
	 * The module has no parameters; use newHybridModule() if the test needs some.
	 *
	 * @param string $name
	 * @param array $items Items to add to the module's result.
	 * @param Exception|null $throwException
	 *
	 * @return ApiQueryBase
	 */
	private function getDummyListModule(
		string $name,
		array $items,
		?Exception $throwException = null
	): ApiQueryBase {
		$module = $this->getMockBuilder( ApiQueryBase::class )
			->setConstructorArgs( [ $this->getApiQuery(), $name, 'tl' ] )
			->onlyMethods( [ 'execute' ] )
			->getMock();

		$module->method( 'execute' )
			->willReturnCallback(
				static function () use ( $module, $items, $throwException ) {
					if ( $throwException ) {
						throw $throwException;
					}

					$res = $module->getResult();
					foreach ( $items as $item ) {
						$res->addValue( [ 'query', $module->getModuleName() ], null, $item );
					}
				}
			);

		$this->overrideListModule( $module );
		return $module;
	}

	/**
	 * Overrides a list module on the ApiQuery module of $this->apiMain.
	 * Counterpart of overrideActionModule(), for modules that are not
	 * registered with ApiMain itself.
	 */
	private function overrideListModule( ApiBase $module ) {
		$this->getApiQuery()->getModuleManager()->addModule(
			$module->getModuleName(),
			'list',
			[
				'class' => get_class( $module ),
				'factory' => static function () use ( $module ) {
					return $module;
				}
			]
		);
	}

	private function newHybridModule(
		string $listName = 'testlist',
		?ApiRestHelper $helper = null,
		?Exception $throwException = null,
		array $resultData = [],
		?string $continue = null
	): ApiRestHybrid&ApiQueryBase {
		$helper ??= new class extends ApiRestHelper {
		};

		/** @var ApiRestHybrid & ApiQueryBase $module */
		$module = new class( $this->getApiQuery(), $listName, $helper, $resultData, $throwException, $continue )
			extends ApiQueryBase implements ApiRestHybrid
		{
			public function __construct(
				ApiQuery $query,
				string $name,
				private ApiRestHelper $helper,
				private array $resultData,
				private ?Exception $throwException,
				private ?string $continue
			) {
				parent::__construct( $query, $name, 'tl' );
			}

			public function execute() {
				if ( $this->throwException ) {
					throw $this->throwException;
				}

				$res = $this->getResult();
				$path = [ 'query', $this->getModuleName() ];
				foreach ( $this->resultData as $item ) {
					$res->addValue( $path, null, $item );
				}

				$res->addValue( $path, 'PARAMETERS', $this->extractRequestParams() );
				$res->addValue( $path, 'MAIN_PARAMETERS', $this->getMain()->extractRequestParams() );

				if ( $this->continue !== null ) {
					$this->setContinueEnumParameter( 'next', $this->continue );
				}
			}

			public function getRestHelper(): ApiRestHelper {
				return $this->helper;
			}

			protected function getAllowedParams() {
				return [
					'title' => [
						ParamValidator::PARAM_TYPE => 'string',
						ApiBase::PARAM_HELP_MSG => 'apihelp-custom',
					],
					'limit' => [
						ParamValidator::PARAM_TYPE => 'integer',
						ParamValidator::PARAM_DEFAULT => 10,
					],
					// Scalar shorthand: just a default value
					'dir' => 'ascending',
					'next' => null,
				];
			}
		};

		return $module;
	}

	public function testGet() {
		$handler = $this->newHandler( [
			'resultData' => [ [ 'title' => 'A' ], [ 'title' => 'B' ] ],
		] );
		$request = new RequestData( [ 'method' => 'GET' ] );

		$data = $this->executeHandlerAndGetBodyData( $handler, $request );

		$this->assertSame( [ [ 'title' => 'A' ], [ 'title' => 'B' ] ], $data['items'] );
		// The module reports no continuation, which means "no more pages".
		$this->assertNull( $data['pagination']['next'] );
	}

	public function testExecute_pagination() {
		$handler = $this->newHandler( [
			'module' => $this->newHybridModule( continue: 'B' ),
		] );
		$request = new RequestData( [ 'method' => 'GET' ] );

		$data = $this->executeHandlerAndGetBodyData( $handler, $request );

		// The continuation parameters lose the module prefix, since they are meant
		// to be passed back to the REST API. ApiQuery adds its own generic "continue"
		// marker to the action API result; it must not leak into the REST result.
		$this->assertSame( [ 'next' => 'B' ], $data['pagination']['next']['params'] );
	}

	public function testExecute_paginationRoundTrip() {
		$firstPage = $this->executeHandlerAndGetBodyData(
			$this->newHandler( [ 'module' => $this->newHybridModule( continue: 'B' ) ] ),
			new RequestData( [ 'method' => 'GET' ] )
		);

		// Each REST request gets its own ApiMain, and with it its own ApiResult.
		$this->apiMain = $this->getApiMain( true );

		// A client passes the continuation parameters back as query parameters.
		$secondPage = $this->executeHandlerAndGetBodyData(
			$this->newHandler( [ 'module' => $this->newHybridModule() ] ),
			new RequestData( [
				'method' => 'GET',
				'queryParams' => $firstPage['pagination']['next']['params'],
			] )
		);

		// The essential assertion: the list module receives the continuation value
		// under its own parameter name, so the handler restored the prefix.
		$this->assertSame( 'B', $secondPage['items']['PARAMETERS']['next'] );
		$this->assertNull( $secondPage['pagination']['next'] );
	}

	public function testExecute_parameters() {
		$handler = $this->newHandler( [
			'module' => $this->newHybridModule( resultData: [ [ 'title' => 'A' ] ] ),
		] );

		$request = new RequestData( [
			'method' => 'GET',
			'queryParams' => [
				// the REST API uses the name without prefix, the module sees "tllimit"
				'limit' => 7,
				'title' => 'Foo',
				// maxlag is an ApiMain param the adapter deliberately allows
				// through; requestid is one it suppresses.
				'maxlag' => 17,
				'requestid' => 'abc',
			],
		] );

		$data = $this->executeHandlerAndGetBodyData( $handler, $request );

		// The fake hybrid module has that hard coded
		$params = $data['items']['PARAMETERS'];

		// check supplied module parameters
		$this->assertSame( 7, $params['limit'] );
		$this->assertSame( 'Foo', $params['title'] );
		$this->assertSame( 'ascending', $params['dir'], 'Default should apply to omitted parameter' );

		// check main parameters
		$mainParams = $data['items']['MAIN_PARAMETERS'];

		$this->assertSame( 17, $mainParams['maxlag'], 'Allowed ApiMain parameter should reach ApiMain' );
		$this->assertNull( $mainParams['requestid'], 'Suppressed ApiMain parameter should be dropped' );
		$this->assertSame( 'query', $mainParams['action'] );
	}

	public function testExecuteUsesListModuleHelper() {
		$helper = new class extends ApiRestHelper {
			public function getStatusForErrorMessage( IApiMessage $msg ): int {
				return $msg->getApiCode() === 'list-specific-code' ? 403 : 0;
			}
		};
		$exception = new ApiUsageException(
			null,
			StatusValue::newFatal( ApiMessage::create( 'apierror-something', 'list-specific-code' ) )
		);

		$handler = $this->newHandler( [
			'module' => $this->newHybridModule( helper: $helper, throwException: $exception ),
		] );
		$request = new RequestData( [ 'method' => 'GET' ] );

		$ex = $this->executeHandlerAndGetHttpException( $handler, $request );

		// The essential assertion: the status comes from the helper of the list module,
		// not from that of ApiQuery, which is the action module being called.
		$this->assertSame( 403, $ex->getCode() );
	}

	public function testGetActionModuleParameters() {
		$handler = $this->newHandler( [
			'module' => $this->newHybridModule(),
			'init' => true,
			'request' => new RequestData( [
				'method' => 'GET',
				'queryParams' => [
					'limit' => '7', // list module param, should get the module prefix
					'maxlag' => '3', // allowed ApiMain param, should not get a prefix
				],
			] ),
		] );

		$params = TestingAccessWrapper::newFromObject( $handler )->getActionModuleParameters();

		$this->assertSame( '7', $params['tllimit'], 'List module param should be prefixed' );
		$this->assertArrayNotHasKey( 'limit', $params, 'Unprefixed list module param should be gone' );
		$this->assertSame( '3', $params['maxlag'], 'ApiMain param should not be prefixed' );
		$this->assertSame( 'query', $params['action'] );
		$this->assertSame( 'testlist', $params['list'] );
	}

	public function testGetActionModuleParamSpecs() {
		$handler = $this->newHandler( [
			'module' => $this->newHybridModule(),
		] );

		$specs = TestingAccessWrapper::newFromObject( $handler )->getActionModuleParamSpecs();

		$this->assertArrayHasKey( 'limit', $specs, 'List module param should have no prefix' );
		$this->assertArrayNotHasKey( 'tllimit', $specs );

		$this->assertSame(
			'ascending',
			$specs['dir'][ParamValidator::PARAM_DEFAULT],
			'Scalar shorthand spec should be normalized to array form'
		);

		$this->assertSame(
			'apihelp-query+testlist-param-limit',
			$specs['limit'][ApiBase::PARAM_HELP_MSG],
			'Help message should be derived from the list module, not from "query"'
		);
		$this->assertSame(
			'apihelp-custom',
			$specs['title'][ApiBase::PARAM_HELP_MSG],
			'Explicit help message should be retained'
		);

		$this->assertArrayHasKey( 'maxlag', $specs, 'Allowed ApiMain param should be included' );
		$this->assertArrayNotHasKey( 'requestid', $specs, 'Suppressed ApiMain param should be excluded' );
	}

	public function testUnknownListModule() {
		$handler = new GenericListHandler( 'nosuchlist' );
		$handler->setApiMain( $this->apiMain );

		$this->expectException( UnexpectedValueException::class );
		TestingAccessWrapper::newFromObject( $handler )->getActionModuleParamSpecs();
	}

}
