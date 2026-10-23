<?php

namespace MediaWiki\Tests\Rest\Handler;

use MediaWiki\Api\ApiListRestHelper;
use MediaWiki\Api\ApiMain;
use MediaWiki\Api\ApiQuery;
use MediaWiki\Api\ApiQueryBase;
use MediaWiki\Api\ApiRestHelper;
use MediaWiki\Api\ApiRestHelperContext;
use MediaWiki\Api\ApiRestHybrid;
use MediaWiki\Rest\Handler;
use MediaWiki\Rest\Handler\GenericItemHandler;
use MediaWiki\Rest\LocalizedHttpException;
use MediaWiki\Rest\RequestData;
use MediaWikiIntegrationTestCase;
use Psr\Log\LogLevel;
use TestLogger;
use Wikimedia\ParamValidator\ParamValidator;

/**
 * Tests for GenericItemHandler covering the behaviour it adds to
 * GenericListHandler. Runs against a real ApiMain and ApiQuery with stub list
 * modules that, like list=users, take a list of names and return one item per
 * name.
 *
 * @covers \MediaWiki\Rest\Handler\GenericItemHandler
 */
class GenericItemHandlerTest extends MediaWikiIntegrationTestCase {
	use HandlerTestTrait;
	use ActionModuleBasedHandlerTestTrait;

	private const LIST_NAME = 'testitems';

	private const ALLOWED_PARAMS = [
		'users' => [
			ParamValidator::PARAM_ISMULTI => true,
		],
		'prop' => [
			ParamValidator::PARAM_ISMULTI => true,
			ParamValidator::PARAM_TYPE => [ 'editcount', 'groups' ],
		],
	];

	/** Handler config for routes that use newRenamingHelper() */
	private const CONFIG = [ 'method' => 'GET', 'path' => '/users/{name}' ];

	private ApiMain $apiMain;

	/** @var string[][] The values of "users" the stub module was called with */
	private array $receivedUsers = [];

	protected function setUp(): void {
		parent::setUp();
		$this->apiMain = $this->getApiMain( true );
	}

	private function newHandler( ApiQueryBase $module ): GenericItemHandler {
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

		$handler = new GenericItemHandler( $module->getModuleName() );
		$handler->setApiMain( $this->apiMain );
		return $handler;
	}

	private function getApiQuery(): ApiQuery {
		return $this->apiMain->getModuleManager()->getModule( 'query' );
	}

	/**
	 * Returns a callback for the stub modules that answers like list=users:
	 * one item per requested name, flagged as missing for unknown names.
	 *
	 * @param array[] $known Items by name
	 */
	private function respondPerName( array $known ): callable {
		return static function ( array $users ) use ( $known ): array {
			$items = [];
			foreach ( $users as $name ) {
				$items[] = $known[$name] ?? [ 'name' => $name, 'missing' => true ];
			}
			return $items;
		};
	}

	/**
	 * Wraps a callback for the stub modules so that it records the values of
	 * "users" the module was called with.
	 */
	private function recording( callable $respond ): callable {
		return function ( array $users ) use ( $respond ): array {
			$this->receivedUsers[] = $users;
			return $respond( $users );
		};
	}

	/**
	 * A list module that is not an ApiRestHybrid, so it has no helper.
	 *
	 * @param callable $respond Takes the values of "users", returns the items
	 */
	private function newPlainModule( callable $respond ): ApiQueryBase {
		$respond = $this->recording( $respond );
		$module = $this->getMockBuilder( ApiQueryBase::class )
			->setConstructorArgs( [ $this->getApiQuery(), self::LIST_NAME, 'ti' ] )
			->onlyMethods( [ 'execute', 'getAllowedParams' ] )
			->getMock();

		$module->method( 'getAllowedParams' )->willReturn( self::ALLOWED_PARAMS );
		$module->method( 'execute' )->willReturnCallback(
			static function () use ( $module, $respond ) {
				$users = $module->extractRequestParams()['users'] ?? [];
				foreach ( $respond( $users ) as $item ) {
					$module->getResult()->addValue( [ 'query', self::LIST_NAME ], null, $item );
				}
			}
		);

		return $module;
	}

	/**
	 * A list module with the given helper. It also reports a continuation,
	 * like list=users does when the result is too large.
	 *
	 * @param ApiRestHelper $helper
	 * @param callable $respond Takes the values of "users", returns the items
	 */
	private function newHybridModule( ApiRestHelper $helper, callable $respond ): ApiRestHybrid&ApiQueryBase {
		return new class(
			$this->getApiQuery(), self::LIST_NAME, self::ALLOWED_PARAMS, $helper, $this->recording( $respond )
		) extends ApiQueryBase implements ApiRestHybrid {
			/** @var callable */
			private $respond;

			private bool $restMode = false;

			public function __construct(
				ApiQuery $query,
				string $name,
				private array $allowedParams,
				private ApiRestHelper $helper,
				callable $respond
			) {
				parent::__construct( $query, $name, 'ti' );
				$this->respond = $respond;
			}

			public function execute() {
				$params = $this->extractRequestParams();
				// Like list=users, REST mode exposes a single-valued "name".
				$users = $this->restMode ? [ $params['name'] ] : ( $params['users'] ?? [] );
				foreach ( ( $this->respond )( $users ) as $item ) {
					$this->getResult()->addValue( [ 'query', $this->getModuleName() ], null, $item );
				}
				$this->setContinueEnumParameter( 'continue', 'X' );
			}

			public function initRestMode( ApiRestHelperContext $context ): ApiRestHelper {
				$this->restMode = true;
				return $this->helper;
			}

			protected function getAllowedParams() {
				if ( !$this->restMode ) {
					return $this->allowedParams;
				}

				$params = $this->allowedParams;
				$params['name'] = [ ParamValidator::PARAM_ISMULTI => false ] + $params['users'];
				unset( $params['users'] );
				return $params;
			}
		};
	}

	/**
	 * A helper for a module that exposes a single-valued "name" in REST mode,
	 * the pattern for /users/{name} on top of list=users.
	 *
	 * @param ?callable $getItemStatus Overrides getItemStatus() if given
	 * @param ?array $itemSchema Returned by getResponseItemSchema() if given
	 */
	private function newRenamingHelper(
		?callable $getItemStatus = null,
		?array $itemSchema = null
	): ApiListRestHelper {
		return new class( $getItemStatus, $itemSchema ) extends ApiListRestHelper {
			/** @var ?callable */
			private $getItemStatus;

			public function __construct( ?callable $getItemStatus, private ?array $itemSchema ) {
				parent::__construct( new ApiRestHelperContext( [], [] ) );
				$this->getItemStatus = $getItemStatus;
			}

			public function getItemStatus( array $item ): int {
				return $this->getItemStatus ? ( $this->getItemStatus )( $item ) : 0;
			}

			public function getResponseItemSchema(): array {
				return $this->itemSchema ?? parent::getResponseItemSchema();
			}
		};
	}

	private function newRequest( string $name ): RequestData {
		return new RequestData( [
			'method' => 'GET',
			'pathParams' => [ 'name' => $name ],
		] );
	}

	public function testGetReturnsTheItem() {
		$handler = $this->newHandler( $this->newHybridModule(
			$this->newRenamingHelper(),
			$this->respondPerName( [ 'Foo' => [ 'name' => 'Foo', 'editcount' => 3 ] ] )
		) );

		$data = $this->executeHandlerAndGetBodyData( $handler, $this->newRequest( 'Foo' ), self::CONFIG );

		// The item itself, not a list of one, and no pagination envelope:
		// the continuation the module reports doesn't apply to a single item.
		$this->assertSame( [ 'name' => 'Foo', 'editcount' => 3 ], $data );
		// The module receives the path value as a list of one name.
		$this->assertSame( [ [ 'Foo' ] ], $this->receivedUsers );
	}

	public function testGetWithoutHelperUsesTheModuleParamName() {
		$handler = $this->newHandler( $this->newPlainModule(
			$this->respondPerName( [ 'Foo' => [ 'name' => 'Foo' ] ] )
		) );
		$request = new RequestData( [
			'method' => 'GET',
			'pathParams' => [ 'users' => 'Foo' ],
		] );

		$data = $this->executeHandlerAndGetBodyData(
			$handler, $request, [ 'method' => 'GET', 'path' => '/users/{users}' ]
		);

		$this->assertSame( [ 'name' => 'Foo' ], $data );
		$this->assertSame( [ [ 'Foo' ] ], $this->receivedUsers );
	}

	public static function provideMultipleValues() {
		yield 'pipe' => [ 'Foo|Bar' ];
		yield 'unit separator' => [ "\x1fFoo\x1fBar" ];
		yield 'unit separator inside the value' => [ "Foo\x1fBar" ];
	}

	/**
	 * @dataProvider provideMultipleValues
	 */
	public function testRejectsMultipleValuesInPath( string $value ) {
		$handler = $this->newHandler( $this->newHybridModule(
			$this->newRenamingHelper(),
			$this->respondPerName( [] )
		) );

		$ex = $this->executeHandlerAndGetHttpException( $handler, $this->newRequest( $value ), self::CONFIG );

		$this->assertSame( 400, $ex->getCode() );
		$this->assertSame( 'parameter-validation-failed', $ex->getErrorData()['error'] );
		$this->assertSame( 'name', $ex->getErrorData()['name'] );
		$this->assertSame( [], $this->receivedUsers, 'The module must not be called' );
	}

	public static function provideItemStatusConvention() {
		yield 'missing' => [ [ [ 'name' => 'Foo', 'missing' => true ] ], 404, 'rest-item-not-found' ];
		yield 'invalid' => [ [ [ 'name' => 'Foo', 'invalid' => true ] ], 400, 'rest-item-invalid' ];
		yield 'empty result' => [ [], 404, 'rest-item-not-found' ];
	}

	/**
	 * @dataProvider provideItemStatusConvention
	 */
	public function testItemStatusConvention( array $items, int $expectedStatus, string $expectedKey ) {
		// No helper: the convention applies to any module.
		$handler = $this->newHandler( $this->newPlainModule( static fn () => $items ) );
		$request = new RequestData( [
			'method' => 'GET',
			'pathParams' => [ 'users' => 'Foo' ],
		] );

		$ex = $this->executeHandlerAndGetHttpException(
			$handler, $request, [ 'method' => 'GET', 'path' => '/users/{users}' ]
		);

		$this->assertSame( $expectedStatus, $ex->getCode() );
		$this->assertInstanceOf( LocalizedHttpException::class, $ex );
		$this->assertSame( $expectedKey, $ex->getMessageValue()->getKey() );
	}

	public function testMissingButKnownItemIsReturned() {
		// Like a page that doesn't exist locally but is known, e.g. a file on Commons.
		$item = [ 'name' => 'Foo', 'missing' => true, 'known' => true ];
		$handler = $this->newHandler( $this->newHybridModule(
			$this->newRenamingHelper(),
			$this->respondPerName( [ 'Foo' => $item ] )
		) );

		$data = $this->executeHandlerAndGetBodyData( $handler, $this->newRequest( 'Foo' ), self::CONFIG );

		$this->assertSame( $item, $data );
	}

	public function testHelperCanReturnAMissingItem() {
		$item = [ 'name' => 'Foo', 'missing' => true ];
		$handler = $this->newHandler( $this->newHybridModule(
			$this->newRenamingHelper( getItemStatus: static fn () => 200 ),
			$this->respondPerName( [ 'Foo' => $item ] )
		) );

		$data = $this->executeHandlerAndGetBodyData( $handler, $this->newRequest( 'Foo' ), self::CONFIG );

		$this->assertSame( $item, $data );
	}

	public function testHelperCanRejectAnItem() {
		$handler = $this->newHandler( $this->newHybridModule(
			$this->newRenamingHelper(
				getItemStatus: static fn ( array $item ) => isset( $item['hidden'] ) ? 403 : 0
			),
			$this->respondPerName( [ 'Foo' => [ 'name' => 'Foo', 'hidden' => true ] ] )
		) );

		$ex = $this->executeHandlerAndGetHttpException( $handler, $this->newRequest( 'Foo' ), self::CONFIG );

		$this->assertSame( 403, $ex->getCode() );
	}

	public function testMultipleItemsInResult() {
		$logger = new TestLogger( true );
		$this->setLogger( 'rest', $logger );

		// A module that returns several items for one name can't back an item endpoint.
		$handler = $this->newHandler( $this->newHybridModule(
			$this->newRenamingHelper(),
			static fn () => [ [ 'name' => 'Foo' ], [ 'name' => 'Foo' ] ]
		) );

		$ex = $this->executeHandlerAndGetHttpException( $handler, $this->newRequest( 'Foo' ), self::CONFIG );

		// A server error: the route is misconfigured, the request is fine.
		$this->assertSame( 500, $ex->getCode() );
		$this->assertInstanceOf( LocalizedHttpException::class, $ex );
		$this->assertSame( 'rest-item-multiple-results', $ex->getMessageValue()->getKey() );
		// The REST framework doesn't report HttpExceptions, so the handler logs it.
		$this->assertSame( [ LogLevel::ERROR ], array_column( $logger->getBuffer(), 0 ) );
	}

	public function testPathParamIsDeclaredSingleValued() {
		// Without a helper, the path param is the module's multi-valued "users".
		$handler = $this->newHandler( $this->newPlainModule( $this->respondPerName( [] ) ) );
		$this->initHandler(
			$handler,
			new RequestData( [ 'method' => 'GET' ] ),
			[ 'method' => 'GET', 'path' => '/users/{users}' ]
		);

		$settings = $handler->getParamSettings();

		$this->assertSame( 'path', $settings['users'][Handler::PARAM_SOURCE] );
		$this->assertFalse( $settings['users'][ParamValidator::PARAM_ISMULTI] ?? false );
		// Query params keep the module's settings.
		$this->assertTrue( $settings['prop'][ParamValidator::PARAM_ISMULTI] );
	}

	public function testGetOpenApiSpecUsesItemSchema() {
		$itemSchema = [ 'type' => 'object', 'properties' => [ 'name' => [ 'type' => 'string' ] ] ];
		$handler = $this->newHandler( $this->newHybridModule(
			$this->newRenamingHelper( itemSchema: $itemSchema ),
			$this->respondPerName( [] )
		) );
		$this->initHandler( $handler, new RequestData( [ 'method' => 'GET' ] ), self::CONFIG );

		$spec = $handler->getOpenApiSpec( 'get' );

		// The item schema itself, not wrapped in the list envelope.
		$this->assertSame(
			$itemSchema,
			$spec['responses']['200']['content']['application/json']['schema']
		);
	}

}
