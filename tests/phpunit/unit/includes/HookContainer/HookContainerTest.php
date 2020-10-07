<?php

namespace MediaWiki\HookContainer {

	use Psr\Container\ContainerInterface;
	use UnexpectedValueException;
	use Wikimedia\ObjectFactory;
	use ExtensionRegistry;
	use MediaWikiUnitTestCase;
	use Wikimedia\ScopedCallback;
	use Wikimedia\TestingAccessWrapper;

	class HookContainerTest extends MediaWikiUnitTestCase {

		/*
		 * Creates a new hook container with mocked ObjectFactory, ExtensionRegistry, and DeprecatedHooks
		 */
		private function newHookContainer(
			$hooks = null, $deprecatedHooksArray = []
		) {
			if ( $hooks === null ) {
				$handler = [ 'handler' => [
					'name' => 'FooExtension-FooActionHandler',
					'class' => 'FooExtension\\Hooks',
					'services' => [] ]
				];
				$hooks = [ 'FooActionComplete' => [ $handler ] ];
			}
			$mockObjectFactory = $this->getObjectFactory();
			$registry = new StaticHookRegistry( [], $hooks, $deprecatedHooksArray );
			$hookContainer = new HookContainer( $registry, $mockObjectFactory );
			return $hookContainer;
		}

		private function getMockExtensionRegistry( $hooks ) {
			$mockRegistry = $this->createNoOpMock( ExtensionRegistry::class, [ 'getAttribute' ] );
			$mockRegistry->method( 'getAttribute' )
				->with( 'Hooks' )
				->willReturn( $hooks );
			return $mockRegistry;
		}

		private function getObjectFactory() {
			$mockServiceContainer = $this->createMock( ContainerInterface::class );
			$mockServiceContainer->method( 'get' )
				->willThrowException( new \RuntimeException );

			$objectFactory = new ObjectFactory( $mockServiceContainer );
			return $objectFactory;
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
				'Closure' => [ 'MWTestHook', function () {
					return true;
				} ],
				'Closure with data' => [ 'MWTestHook', function () {
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
				[ function () {
					return 'string';
				} ]
			];
		}

		/**
		 * @covers       \MediaWiki\HookContainer\HookContainer::salvage
		 */
		public function testSalvage() {
			$hookContainer = $this->newHookContainer();
			$hookContainer->register( 'TestHook', 'TestHandler' );
			$this->assertTrue( $hookContainer->isRegistered( 'TestHook' ) );

			$accessibleHookContainer = $this->newHookContainer();
			$testingAccessHookContainer = TestingAccessWrapper::newFromObject( $accessibleHookContainer );

			$this->assertFalse( $testingAccessHookContainer->isRegistered( 'TestHook' ) );
			$testingAccessHookContainer->salvage( $hookContainer );
			$this->assertTrue( $testingAccessHookContainer->isRegistered( 'TestHook' ) );
		}

		/**
		 * @covers       \MediaWiki\HookContainer\HookContainer::salvage
		 */
		public function testSalvageThrows() {
			$this->expectException( 'MWException' );
			$hookContainer = $this->newHookContainer();
			$hookContainer->register( 'TestHook', 'TestHandler' );
			$hookContainer->salvage( $hookContainer );
			$this->assertTrue( $hookContainer->isRegistered( 'TestHook' ) );
		}

		/**
		 * @covers       \MediaWiki\HookContainer\HookContainer::isRegistered
		 * @covers       \MediaWiki\HookContainer\HookContainer::register
		 */
		public function testRegisteredLegacy() {
			$hookContainer = $this->newHookContainer();
			$this->assertFalse( $hookContainer->isRegistered( 'MWTestHook' ) );
			$hookContainer->register( 'MWTestHook', [ new FooClass(), 'FooMethod' ] );
			$this->assertTrue( $hookContainer->isRegistered( 'MWTestHook' ) );
		}

		/**
		 * @covers       \MediaWiki\HookContainer\HookContainer::scopedRegister
		 */
		public function testScopedRegister() {
			$hookContainer = $this->newHookContainer();
			$reset = $hookContainer->scopedRegister( 'MWTestHook', [ new FooClass(), 'FooMethod' ] );
			$this->assertTrue( $hookContainer->isRegistered( 'MWTestHook' ) );
			ScopedCallback::consume( $reset );
			$this->assertFalse( $hookContainer->isRegistered( 'MWTestHook' ) );
		}

		/**
		 * @covers       \MediaWiki\HookContainer\HookContainer::scopedRegister
		 */
		public function testScopedRegister2() {
			$hookContainer = $this->newHookContainer();
			$called1 = $called2 = false;
			$reset1 = $hookContainer->scopedRegister( 'MWTestHook',
				function () use ( &$called1 ) {
					$called1 = true;
				}, false
			);
			$reset2 = $hookContainer->scopedRegister( 'MWTestHook',
				function () use ( &$called2 ) {
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
		 * @covers       \MediaWiki\HookContainer\HookContainer::isRegistered
		 */
		public function testNotRegisteredLegacy() {
			$hookContainer = $this->newHookContainer();
			$this->assertFalse( $hookContainer->isRegistered( 'UnregisteredHook' ) );
		}

		/**
		 * @covers       \MediaWiki\HookContainer\HookContainer::getHandlers
		 * @dataProvider provideGetHandlers
		 * @param $hook
		 * @param $handlerToRegister
		 * @param $expectedReturn
		 */
		public function testGetHandlers( $hook, $handlerToRegister, $expectedReturn ) {
			if ( $handlerToRegister ) {
				$hooks = [ $hook => [ $handlerToRegister ] ];
			} else {
				$hooks = [];
			}
			$fakeDeprecatedHooks = [
				'FooActionCompleteDeprecated' => [ 'deprecatedVersion' => '1.35' ]
			];
			$hookContainer = $this->newHookContainer( $hooks, $fakeDeprecatedHooks );
			$handlers = $hookContainer->getHandlers( $hook );
			$this->assertArrayEquals(
				$handlers,
				$expectedReturn,
				'HookContainer::getHandlers() should return array of handler functions'
			);
		}

		/**
		 * @dataProvider provideRunLegacyErrors
		 * @covers       \MediaWiki\HookContainer\HookContainer::normalizeHandler
		 * Test errors thrown with invalid handlers
		 */
		public function testRunLegacyErrors() {
			$hookContainer = $this->newHookContainer();
			$this->hideDeprecated(
				'returning a string from a hook handler (done by hook-MWTestHook-closure for MWTestHook)'
			);
			$this->expectException( 'UnexpectedValueException' );
			$hookContainer->register( 'MWTestHook', 123 );
			$hookContainer->run( 'MWTestHook', [] );
		}

		/**
		 * @covers       \MediaWiki\HookContainer\HookContainer::getLegacyHandlers
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
		 * @covers       \MediaWiki\HookContainer\HookContainer::run
		 * @covers       \MediaWiki\HookContainer\HookContainer::callLegacyHook
		 * @covers       \MediaWiki\HookContainer\HookContainer::normalizeHandler
		 * @dataProvider provideRunLegacy
		 * Test Hook run with legacy hook system, registered via wgHooks()
		 * @param $event
		 * @param $hook
		 * @param array $hookArguments
		 * @param array $options
		 * @throws \FatalError
		 */
		public function testRunLegacy( $event, $hook, $hookArguments = [], $options = [] ) {
			$hookContainer = $this->newHookContainer();
			$hookContainer->register( $event, $hook );
			$hookValue = $hookContainer->run( $event, $hookArguments, $options );
			$this->assertTrue( $hookValue );
		}

		/**
		 * @covers       \MediaWiki\HookContainer\HookContainer::run
		 * @covers       \MediaWiki\HookContainer\HookContainer::normalizeHandler
		 * Test HookContainer::run() with abortable option
		 */
		public function testRunNotAbortable() {
			$handler = [ 'handler' => [
				'name' => 'FooExtension-InvalidReturnHandler',
				'class' => 'FooExtension\\Hooks',
				'services' => [] ]
			];
			$hookContainer = $this->newHookContainer( [ 'InvalidReturnHandler' => [ $handler ] ] );
			$this->expectException( UnexpectedValueException::class );
			$this->expectExceptionMessage(
				"Invalid return from onInvalidReturnHandler for " .
				"unabortable InvalidReturnHandler"
			);
			$hookRun = $hookContainer->run( 'InvalidReturnHandler', [], [ 'abortable' => false ] );
			$this->assertTrue( $hookRun );
		}

		/**
		 * @covers       \MediaWiki\HookContainer\HookContainer::run
		 * @covers       \MediaWiki\HookContainer\HookContainer::normalizeHandler
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
			$hookContainer = $this->newHookContainer( $hooks );
			$called = [];
			$ret = $hookContainer->run( 'Abort', [ &$called ] );
			$this->assertFalse( $ret );
			$this->assertArrayEquals( [ 1, 2 ], $called );
		}

		/**
		 * @covers       \MediaWiki\HookContainer\HookContainer::register
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
				[ 'FooActionComplete' => [ $handler ] ],
				$fakeDeprecatedHooks );
			$hookContainer->register( 'FooActionComplete', new FooClass() );
			$this->assertTrue( $hookContainer->isRegistered( 'FooActionComplete' ) );
		}

		/**
		 * @covers       \MediaWiki\HookContainer\HookContainer::isRegistered
		 * Test HookContainer::isRegistered() with current hook system with arguments
		 */
		public function testIsRegistered() {
			$hookContainer = $this->newHookContainer();
			$hookContainer->register( 'FooActionComplete', function () {
				return true;
			} );
			$isRegistered = $hookContainer->isRegistered( 'FooActionComplete' );
			$this->assertTrue( $isRegistered );
		}

		/**
		 * @covers       \MediaWiki\HookContainer\HookContainer::run
		 * @covers       \MediaWiki\HookContainer\HookContainer::normalizeHandler
		 * Test HookContainer::run() throws exceptions appropriately
		 */
		public function testRunExceptions() {
			$handler = [ 'handler' => [
				'name' => 'FooExtension-InvalidReturnHandler',
				'class' => 'FooExtension\\Hooks',
				'services' => [] ]
			];
			$hookContainer = $this->newHookContainer(
				[ 'InvalidReturnHandler' => [ $handler ] ] );
			$this->expectException( UnexpectedValueException::class );
			$hookContainer->run( 'InvalidReturnHandler' );
		}

		/**
		 * @covers       \MediaWiki\HookContainer\HookContainer::emitDeprecationWarnings
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

			$hookContainer = $this->newHookContainer( $hooks, $deprecatedHooksArray );

			$this->expectDeprecation();
			$hookContainer->emitDeprecationWarnings();
		}

		/**
		 * @covers       \MediaWiki\HookContainer\HookContainer::emitDeprecationWarnings
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

			$hookContainer = $this->newHookContainer( $hooks, $deprecatedHooksArray );

			$hookContainer->emitDeprecationWarnings();
			$this->assertTrue( true );
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
