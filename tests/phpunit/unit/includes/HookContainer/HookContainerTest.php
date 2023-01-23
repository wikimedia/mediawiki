<?php

namespace MediaWiki\HookContainer {

	use MediaWiki\Tests\Unit\DummyServicesTrait;
	use MediaWikiUnitTestCase;
	use UnexpectedValueException;
	use Wikimedia\ScopedCallback;

	class HookContainerTest extends MediaWikiUnitTestCase {
		use DummyServicesTrait;

		/*
		 * Creates a new hook container with StaticHookRegistry and empty ObjectFactory
		 */
		private function newHookContainer(
			$oldHooks = null, $newHooks = null, $deprecatedHooksArray = []
		) {
			if ( $oldHooks === null ) {
				$oldHooks[ 'FoobarActionComplete' ][] = static function ( &$called ) {
					$called[] = 11;
				};
			}
			if ( $newHooks === null ) {
				$handler = [ 'handler' => [
					'name' => 'FooExtension-FooActionHandler',
					'class' => 'FooExtension\\Hooks',
					'services' => [] ]
				];
				$newHooks = [ 'FooActionComplete' => [ $handler ] ];
			}
			// object factory with no services
			$objectFactory = $this->getDummyObjectFactory();
			$registry = new StaticHookRegistry( $oldHooks, $newHooks, $deprecatedHooksArray );
			$hookContainer = new HookContainer( $registry, $objectFactory );
			return $hookContainer;
		}

		/**
		 * Values returned: hook, handler, handler arguments, options
		 */
		public static function provideRunLegacy() {
			$fooObj = new FooClass();
			$arguments = [ 'ParamsForHookHandler' ];
			return [
				'Method' => [ 'MWTestHook', 'FooGlobalFunction' ],
				'Falsey value' => [ 'MWTestHook', false ],
				'Method with arguments' => [ 'MWTestHook', [ 'FooGlobalFunction' ], $arguments ],
				'Method in array' => [ 'MWTestHook', [ 'FooGlobalFunction' ] ],
				'Object with no method' => [ 'MWTestHook', $fooObj ],
				'Object with no method in array' => [ 'MWTestHook', [ $fooObj ], $arguments ],
				'Object and method' => [ 'MWTestHook', [ $fooObj, 'FooMethod' ] ],
				'Class name and static method' => [
					'MWTestHook',
					[ 'MediaWiki\HookContainer\FooClass', 'FooStaticMethod' ]
				],
				'Object and static method' => [
					'MWTestHook',
					[ 'MediaWiki\HookContainer\FooClass::FooStaticMethod' ]
				],
				'Object and static method as array' => [
					'MWTestHook',
					[ [ 'MediaWiki\HookContainer\FooClass::FooStaticMethod' ] ]
				],
				'Object and fully-qualified non-static method' => [
					'MWTestHook',
					[ $fooObj, 'MediaWiki\HookContainer\FooClass::FooMethod' ]
				],
				'Closure' => [ 'MWTestHook', static function () {
					return true;
				} ],
				'Closure with data' => [ 'MWTestHook', static function () {
					return true;
				}, [ 'data' ] ]
			];
		}

		/**
		 * Values returned: hook, handlersToRegister, expectedReturn
		 */
		public static function provideGetHandlers() {
			return [
				'NoHandlersExist' => [ 'MWTestHook', null, [] ],
				'SuccessfulHandlerReturn' => [
					'FooActionComplete',
					[ 'handler' => [
						'name' => 'FooExtension-FooActionHandler',
						'class' => 'FooExtension\\Hooks',
						'services' => [] ]
					],
					[ new \FooExtension\Hooks() ]
				],
				'SkipDeprecated' => [
					'FooActionCompleteDeprecated',
					[ 'handler' => [
						'name' => 'FooExtension-FooActionHandler',
						'class' => 'FooExtension\\Hooks',
						'services' => [] ],
					'deprecated' => true
					],
					[]
				],
			];
		}

		/**
		 * Values returned: hook, handlersToRegister, options
		 */
		public static function provideRunLegacyErrors() {
			return [
				[ 123 ],
				[ static function () {
					return 'string';
				} ]
			];
		}

		/**
		 * @covers \MediaWiki\HookContainer\HookContainer::salvage
		 */
		public function testSalvage() {
			$hookContainer = $this->newHookContainer();
			$hookContainer->register( 'TestHook', 'TestHandler' );
			$this->assertTrue( $hookContainer->isRegistered( 'TestHook' ) );

			$accessibleHookContainer = $this->newHookContainer();

			$this->assertFalse( $accessibleHookContainer->isRegistered( 'TestHook' ) );
			$accessibleHookContainer->salvage( $hookContainer );
			$this->assertTrue( $accessibleHookContainer->isRegistered( 'TestHook' ) );
		}

		/**
		 * @covers \MediaWiki\HookContainer\HookContainer::salvage
		 */
		public function testSalvageThrows() {
			$this->expectException( \MWException::class );
			$hookContainer = $this->newHookContainer();
			$hookContainer->register( 'TestHook', 'TestHandler' );
			$hookContainer->salvage( $hookContainer );
			$this->assertTrue( $hookContainer->isRegistered( 'TestHook' ) );
		}

		/**
		 * @covers \MediaWiki\HookContainer\HookContainer::isRegistered
		 * @covers \MediaWiki\HookContainer\HookContainer::register
		 */
		public function testRegisteredLegacy() {
			$hookContainer = $this->newHookContainer();
			$this->assertFalse( $hookContainer->isRegistered( 'MWTestHook' ) );
			$hookContainer->register( 'MWTestHook', [ new FooClass(), 'FooMethod' ] );
			$this->assertTrue( $hookContainer->isRegistered( 'MWTestHook' ) );
		}

		/**
		 * @covers \MediaWiki\HookContainer\HookContainer::scopedRegister
		 */
		public function testScopedRegister() {
			$hookContainer = $this->newHookContainer();
			$reset = $hookContainer->scopedRegister( 'MWTestHook', [ new FooClass(), 'FooMethod' ] );
			$this->assertTrue( $hookContainer->isRegistered( 'MWTestHook' ) );
			ScopedCallback::consume( $reset );
			$this->assertFalse( $hookContainer->isRegistered( 'MWTestHook' ) );
		}

		/**
		 * @covers \MediaWiki\HookContainer\HookContainer::scopedRegister
		 */
		public function testScopedRegisterTwoHandlers() {
			$hookContainer = $this->newHookContainer();
			$called1 = $called2 = false;
			$reset1 = $hookContainer->scopedRegister( 'MWTestHook',
				static function () use ( &$called1 ) {
					$called1 = true;
				}, false
			);
			$reset2 = $hookContainer->scopedRegister( 'MWTestHook',
				static function () use ( &$called2 ) {
					$called2 = true;
				}, false
			);
			$hookContainer->run( 'MWTestHook' );
			$this->assertTrue( $called1 );
			$this->assertTrue( $called2 );

			$called1 = $called2 = false;
			$reset1 = null;
			$hookContainer->run( 'MWTestHook' );
			$this->assertFalse( $called1 );
			$this->assertTrue( $called2 );

			$called1 = $called2 = false;
			$reset2 = null;
			$hookContainer->run( 'MWTestHook' );
			$this->assertFalse( $called1 );
			$this->assertFalse( $called2 );
		}

		/**
		 * Register handlers with scopedReigster() and register()
		 * @covers \MediaWiki\HookContainer\HookContainer::scopedRegister
		 */
		public function testHandlersRegisteredWithScopedRegisterAndRegister() {
			$hookContainer = $this->newHookContainer();
			$numCalls = 0;
			$hookContainer->register( 'MWTestHook', static function () use ( &$numCalls ) {
				$numCalls++;
			} );
			$reset = $hookContainer->scopedRegister( 'MWTestHook', static function () use ( &$numCalls ) {
				$numCalls++;
			} );

			// handlers registered in 2 different ways
			$this->assertCount( 2, $hookContainer->getLegacyHandlers( 'MWTestHook' ) );
			$hookContainer->run( 'MWTestHook' );
			$this->assertEquals( 2, $numCalls );

			// Remove one of the handlers that increments $called
			ScopedCallback::consume( $reset );
			$this->assertCount( 1, $hookContainer->getLegacyHandlers( 'MWTestHook' ) );

			$numCalls = 0;
			$hookContainer->run( 'MWTestHook' );
			$this->assertSame( 1, $numCalls );
		}

		/**
		 * @covers \MediaWiki\HookContainer\HookContainer::isRegistered
		 */
		public function testNotRegisteredLegacy() {
			$hookContainer = $this->newHookContainer();
			$this->assertFalse( $hookContainer->isRegistered( 'UnregisteredHook' ) );
		}

		/**
		 * @covers \MediaWiki\HookContainer\HookContainer::getHandlers
		 * @dataProvider provideGetHandlers
		 */
		public function testGetHandlers( string $hook, ?array $handlerToRegister, array $expectedReturn ) {
			if ( $handlerToRegister ) {
				$hooks = [ $hook => [ $handlerToRegister ] ];
			} else {
				$hooks = [];
			}
			$fakeDeprecatedHooks = [
				'FooActionCompleteDeprecated' => [ 'deprecatedVersion' => '1.35' ]
			];
			$hookContainer = $this->newHookContainer( [], $hooks, $fakeDeprecatedHooks );
			$handlers = $hookContainer->getHandlers( $hook );
			$this->assertArrayEquals(
				$handlers,
				$expectedReturn,
				'HookContainer::getHandlers() should return array of handler functions'
			);
		}

		/**
		 * @covers \MediaWiki\HookContainer\HookContainer::getRegisteredHooks
		 */
		public function testGetRegisteredHooks() {
			$configuredHooks = [ 'A' => 'strtoupper', 'X' => 'strtoupper' ];
			$extensionHooks = [
				'A' => [ 'handler' => 'Foo' ],
				'Y' => [ 'handler' => 'Bar' ]
			];

			$hookContainer = new HookContainer(
				new StaticHookRegistry( $configuredHooks, $extensionHooks ),
				$this->getDummyObjectFactory()
			);

			$hookContainer->register( 'A', 'strtoupper' );
			$hookContainer->register( 'Z', 'strtoupper' );

			$expected = [ 'A', 'X', 'Y', 'Z' ];
			$this->assertArrayEquals( $expected, $hookContainer->getRegisteredHooks() );
		}

		/**
		 * @dataProvider provideRunLegacyErrors
		 * @covers \MediaWiki\HookContainer\HookContainer::normalizeHandler
		 * Test errors thrown with invalid handlers
		 */
		public function testRunLegacyErrors() {
			$hookContainer = $this->newHookContainer();
			$this->hideDeprecated(
				'returning a string from a hook handler (done by hook-MWTestHook-closure for MWTestHook)'
			);
			$this->expectException( UnexpectedValueException::class );
			$hookContainer->register( 'MWTestHook', 123 );
			$hookContainer->run( 'MWTestHook', [] );
		}

		/**
		 * @covers \MediaWiki\HookContainer\HookContainer::getLegacyHandlers
		 */
		public function testGetLegacyHandlers() {
			$hookContainer = $this->newHookContainer();
			$hookContainer->register(
				'FooLegacyActionComplete',
				[ new FooClass(), 'FooMethod' ]
			);
			$expectedHandlers = [ [ new FooClass(), 'FooMethod' ] ];
			$hookHandlers = $hookContainer->getLegacyHandlers( 'FooLegacyActionComplete' );
			$this->assertIsCallable( $hookHandlers[0] );
			$this->assertArrayEquals(
				$hookHandlers,
				$expectedHandlers,
				true
			);
		}

		/**
		 * @covers \MediaWiki\HookContainer\HookContainer::run
		 * @covers \MediaWiki\HookContainer\HookContainer::callLegacyHook
		 * @covers \MediaWiki\HookContainer\HookContainer::normalizeHandler
		 * @dataProvider provideRunLegacy
		 * Test Hook run with legacy hook system, registered via wgHooks()
		 */
		public function testRunLegacy( $event, $hook, $hookArguments = [], $options = [] ) {
			$hookContainer = $this->newHookContainer();
			$hookContainer->register( $event, $hook );
			$hookValue = $hookContainer->run( $event, $hookArguments, $options );
			$this->assertTrue( $hookValue );
		}

		/**
		 * @covers \MediaWiki\HookContainer\HookContainer::run
		 * @covers \MediaWiki\HookContainer\HookContainer::normalizeHandler
		 * Test HookContainer::run() with abortable option
		 */
		public function testRunNotAbortable() {
			$handler = [ 'handler' => [
				'name' => 'FooExtension-InvalidReturnHandler',
				'class' => 'FooExtension\\Hooks',
				'services' => [] ]
			];
			$hookContainer = $this->newHookContainer( [], [ 'InvalidReturnHandler' => [ $handler ] ] );
			$this->expectException( UnexpectedValueException::class );
			$this->expectExceptionMessage(
				"Invalid return from onInvalidReturnHandler for " .
				"unabortable InvalidReturnHandler"
			);
			$hookRun = $hookContainer->run( 'InvalidReturnHandler', [], [ 'abortable' => false ] );
			$this->assertTrue( $hookRun );
		}

		/**
		 * @covers \MediaWiki\HookContainer\HookContainer::run
		 * @covers \MediaWiki\HookContainer\HookContainer::normalizeHandler
		 * Test HookContainer::run() when the handler returns false
		 */
		public function testRunAbort() {
			$handler1 = [ 'handler' => [
				'name' => 'FooExtension-Abort1',
				'class' => 'FooExtension\\AbortHooks1'
			] ];
			$handler2 = [ 'handler' => [
				'name' => 'FooExtension-Abort2',
				'class' => 'FooExtension\\AbortHooks2'
			] ];
			$handler3 = [ 'handler' => [
				'name' => 'FooExtension-Abort3',
				'class' => 'FooExtension\\AbortHooks3'
			] ];
			$hooks = [
				'Abort' => [
					$handler1,
					$handler2,
					$handler3
				]
			];
			$hookContainer = $this->newHookContainer( [], $hooks );
			$called = [];
			$ret = $hookContainer->run( 'Abort', [ &$called ] );
			$this->assertFalse( $ret );
			$this->assertArrayEquals( [ 1, 2 ], $called );
		}

		/**
		 * @covers \MediaWiki\HookContainer\HookContainer::register
		 * Test HookContainer::register() successfully registers even when hook is deprecated
		 */
		public function testRegisterDeprecated() {
			$this->hideDeprecated( 'FooActionComplete hook' );
			$fakeDeprecatedHooks = [ 'FooActionComplete' => [ 'deprecatedVersion' => '1.0' ] ];
			$handler = [
				'handler' => [
					'name' => 'FooExtension-FooActionHandler',
					'class' => 'FooExtension\\Hooks',
					'services' => []
				]
			];
			$hookContainer = $this->newHookContainer(
				[],
				[ 'FooActionComplete' => [ $handler ] ],
				$fakeDeprecatedHooks );
			$hookContainer->register( 'FooActionComplete', new FooClass() );
			$this->assertTrue( $hookContainer->isRegistered( 'FooActionComplete' ) );
		}

		/**
		 * @covers \MediaWiki\HookContainer\HookContainer::isRegistered
		 * Test HookContainer::isRegistered() with current hook system with arguments
		 */
		public function testIsRegistered() {
			$hookContainer = $this->newHookContainer();
			$hookContainer->register( 'FooActionComplete', static function () {
				return true;
			} );
			$isRegistered = $hookContainer->isRegistered( 'FooActionComplete' );
			$this->assertTrue( $isRegistered );
		}

		/**
		 * @covers \MediaWiki\HookContainer\HookContainer::getHookNames
		 */
		public function testGetHookNames() {
			$fooHandler = [ 'handler' => [
				'name' => 'FooHookHandler',
				'class' => 'FooExtension\\Hooks'
			] ];

			$container = $this->newHookContainer(
				[
					'A' => static function () {
						// noop
					}
				],
				[
					'B' => $fooHandler
				]
			);

			$container->register( 'C', 'strtoupper' );

			$this->assertArrayEquals( [ 'A', 'B', 'C' ], $container->getHookNames() );

			// make sure we are getting each hook name only once
			$container->register( 'B', 'strtoupper' );
			$container->register( 'A', 'strtoupper' );

			$this->assertArrayEquals( [ 'A', 'B', 'C' ], $container->getHookNames() );
		}

		/**
		 * @covers \MediaWiki\HookContainer\HookContainer::run
		 * @covers \MediaWiki\HookContainer\HookContainer::normalizeHandler
		 * Test HookContainer::run() throws exceptions appropriately
		 */
		public function testRunExceptions() {
			$handler = [ 'handler' => [
				'name' => 'FooExtension-InvalidReturnHandler',
				'class' => 'FooExtension\\Hooks',
				'services' => [] ]
			];
			$hookContainer = $this->newHookContainer(
				[], [ 'InvalidReturnHandler' => [ $handler ] ] );
			$this->expectException( UnexpectedValueException::class );
			$hookContainer->run( 'InvalidReturnHandler' );
		}

		/**
		 * @covers \MediaWiki\HookContainer\HookContainer::emitDeprecationWarnings
		 */
		public function testEmitDeprecationWarnings() {
			$hooks = [
				'FooActionComplete' => [
					[
						'handler' => 'FooGlobalFunction',
						'extensionPath' => 'fake-extension.json'
					]
				]
			];
			$deprecatedHooksArray = [
				'FooActionComplete' => [ 'deprecatedVersion' => '1.35' ]
			];
			$hookContainer = $this->newHookContainer( [], $hooks, $deprecatedHooksArray );
			$this->expectDeprecation();
			$hookContainer->emitDeprecationWarnings();
		}

		/**
		 * @covers \MediaWiki\HookContainer\HookContainer::emitDeprecationWarnings
		 */
		public function testEmitDeprecationWarningsSilent() {
			$hooks = [
				'FooActionComplete' => [
					[
						'handler' => 'FooGlobalFunction',
						'extensionPath' => 'fake-extension.json'
					]
				]
			];
			$deprecatedHooksArray = [
				'FooActionComplete' => [
					'deprecatedVersion' => '1.35',
					'silent' => true
				]
			];
			$hookContainer = $this->newHookContainer( [], $hooks, $deprecatedHooksArray );
			$hookContainer->emitDeprecationWarnings();
			$this->assertTrue( true );
		}

		/**
		 * @covers \MediaWiki\HookContainer\HookContainer::clear
		 */
		public function testClear() {
			// Register handlers in three different ways
			$hookContainer = $this->newHookContainer(
				[ 'Increment' => [ [ new \FooExtension\Hooks(), 'onIncrement' ] ] ],
				[ 'Increment' => [ [ 'handler' => [
					'name' => 'TestIncrement',
					'class' => 'FooExtension\Hooks'
				] ] ] ]
			);
			$hookContainer->register( 'Increment', static function ( &$count ) {
				$count++;
			} );

			// Check: all three handlers should be called initially.
			$count = 0;
			$hookContainer->run( 'Increment', [ &$count ] );
			$this->assertSame( 3, $count );
			$this->assertTrue( $hookContainer->isRegistered( 'Increment' ) );

			$hookContainer->clear( 'Increment' );

			// both handlers should now be disabled
			$count = 0;
			$hookContainer->run( 'Increment', [ &$count ] );
			$this->assertSame( 0, $count );
			$this->assertFalse( $hookContainer->isRegistered( 'Increment' ) );

			// When adding a handler again...
			$hookContainer->register( 'Increment', static function ( &$count ) {
				$count = 11;
			} );

			// ...the new handler should be called, but not the old ones.
			$count = 0;
			$hookContainer->run( 'Increment', [ &$count ] );
			$this->assertSame( 11, $count );
			$this->assertTrue( $hookContainer->isRegistered( 'Increment' ) );
		}

		/**
		 * @covers \MediaWiki\HookContainer\HookContainer::scopedRegister
		 */
		public function testScopedHandlerWithReplace() {
			$hookContainer = $this->newHookContainer(
				[ 'Increment' => [ [ new \FooExtension\Hooks(), 'onIncrement' ] ] ],
				[ 'Increment' => [ [ 'handler' => [
					'name' => 'TestIncrement',
					'class' => 'FooExtension\Hooks'
				] ] ] ]
			);
			$hookContainer->register( 'Increment', static function ( &$count ) {
				$count++;
			} );

			// Check: both handlers should be called initially.
			$count = 0;
			$hookContainer->run( 'Increment', [ &$count ] );
			$this->assertSame( 3, $count );
			$this->assertTrue( $hookContainer->isRegistered( 'Increment' ) );

			// Adding a scoped handler, with the $replace flag set.
			$scope1 = $hookContainer->scopedRegister( 'Increment', static function ( &$count ) {
				$count -= 3;
			}, true );

			// original handlers should now be disabled, the scoped handler active
			$count = 0;
			$hookContainer->run( 'Increment', [ &$count ] );
			$this->assertSame( -3, $count );
			$this->assertTrue( $hookContainer->isRegistered( 'Increment' ) );

			// Adding another permanent handler should work...
			$hookContainer->register( 'Increment', static function ( &$count ) {
				$count++;
			} );

			// ...so that now the temporary and the permanent handler are called.
			$count = 0;
			$hookContainer->run( 'Increment', [ &$count ] );
			$this->assertSame( -2, $count );

			// Adding another scoped handler, with the $replace flag set.
			$scope2 = $hookContainer->scopedRegister( 'Increment', static function ( &$count ) {
				$count -= 10;
			}, true );

			// Only the new scoped callback should now be active
			$count = 0;
			$hookContainer->run( 'Increment', [ &$count ] );
			$this->assertSame( -10, $count );

			// After consuming the first scoped callback, the second one still overrides...
			ScopedCallback::consume( $scope1 );

			// ...so that still only the new scoped callback should now be active
			$count = 0;
			$hookContainer->run( 'Increment', [ &$count ] );
			$this->assertSame( -10, $count );

			// After also consuming the second scoped callback,
			// all four permanent handlers should be active,
			// since all scoped callbacks are out of the way.
			ScopedCallback::consume( $scope2 );

			$count = 0;
			$hookContainer->run( 'Increment', [ &$count ] );
			$this->assertSame( 4, $count );
			$this->assertTrue( $hookContainer->isRegistered( 'Increment' ) );
		}
	}

	// Mock class for different types of handler functions
	class FooClass {

		public function FooMethod( $data = false ) {
			return true;
		}

		public static function FooStaticMethod() {
			return true;
		}

		public static function FooMethodReturnValueError() {
			return 'a string';
		}

		public static function onMWTestHook() {
			return true;
		}
	}

}

// Function in global namespace
namespace {

	function FooGlobalFunction() {
		return true;
	}

}

// Mock Extension
namespace FooExtension {

	class Hooks {

		public function OnFooActionComplete() {
			return true;
		}

		public function onInvalidReturnHandler() {
			return 123;
		}

		public function onIncrement( &$count ) {
			$count++;
		}
	}

	class AbortHooks1 {
		public function onAbort( &$called ) {
			$called[] = 1;
			return true;
		}
	}

	class AbortHooks2 {
		public function onAbort( &$called ) {
			$called[] = 2;
			return false;
		}
	}

	class AbortHooks3 {
		public function onAbort( &$called ) {
			$called[] = 3;
			return true;
		}
	}

}
