<?php

namespace MediaWiki\HookRunner {

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
			$mockRegistry = null,
			$mockObjectFactory = null,
			$mockDeprecatedHooks = null
		) {
			if ( !$mockRegistry ) {
				$handler = [ 'handler' => [
					'name' => 'Path/To/extension.json-FooActionHandler',
					'class' => 'FooExtension\\Hooks',
					'services' => [] ]
				];
				$mockRegistry = $this->getMockExtensionRegistry( $handler, $hook = 'FooActionComplete' );
			}
			if ( !$mockObjectFactory ) {
				$mockObjectFactory = $this->getMockObjectFactory();
			}
			if ( !$mockDeprecatedHooks ) {
				$mockDeprecatedHooks = $this->getMockDeprecatedHooks();
			}
			$hookContainer = new HookContainer( $mockRegistry, $mockObjectFactory, $mockDeprecatedHooks );
			return $hookContainer;
		}

		private function getMockDeprecatedHooks() {
			$mockDeprecatedHooks = $this->createMock( DeprecatedHooks::class );
			return $mockDeprecatedHooks;
		}

		private function getMockExtensionRegistry( $handler, $hook = 'FooActionComplete' ) {
			$mockRegistry = $this->createMock( ExtensionRegistry::class );
			$mockRegistry->method( 'getAttribute' )->with( 'Hooks' )->willReturn( [
				$hook => [ $handler ]
			] );
			return $mockRegistry;
		}

		private function getMockObjectFactory() {
			$mockObjectFactory = $this->createMock( ObjectFactory::class );
			$mockObjectFactory->method( 'createObject' )->willReturn( new \FooExtension\Hooks() );
			return $mockObjectFactory;
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
					[ 'MediaWiki\HookRunner\FooClass::FooStaticMethod' ]
				],
				'Object and static method as array' => [
					'MWTestHook',
					[ [ 'MediaWiki\HookRunner\FooClass::FooStaticMethod' ] ]
				],
				'Object and fully-qualified non-static method' => [
					'MWTestHook',
					[ $fooObj, 'MediaWiki\HookRunner\FooClass::FooMethod' ]
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
						'name' => 'Path/To/extension.json-FooActionHandler',
						'class' => 'FooExtension\\Hooks',
						'services' => [] ]
					],
					[ new \FooExtension\Hooks() ]
				],
				'SkipDeprecated' => [
					'FooActionCompleteDeprecated',
					[ 'handler' => [
						'name' => 'Path/To/extension.json-FooActionHandler',
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
		 * @covers       \MediaWiki\HookRunner\HookContainer::salvage
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
		 * @covers       \MediaWiki\HookRunner\HookContainer::salvage
		 */
		public function testSalvageThrows() {
			$this->expectException( 'MWException' );
			$hookContainer = $this->newHookContainer();
			$hookContainer->register( 'TestHook', 'TestHandler' );
			$hookContainer->salvage( $hookContainer );
			$this->assertTrue( $hookContainer->isRegistered( 'TestHook' ) );
		}

		/**
		 * @covers       \MediaWiki\HookRunner\HookContainer::isRegistered
		 * @covers       \MediaWiki\HookRunner\HookContainer::register
		 */
		public function testRegisteredLegacy() {
			$hookContainer = $this->newHookContainer();
			$this->assertFalse( $hookContainer->isRegistered( 'MWTestHook' ) );
			$hookContainer->register( 'MWTestHook', [ new FooClass(), 'FooMethod' ] );
			$this->assertTrue( $hookContainer->isRegistered( 'MWTestHook' ) );
		}

		/**
		 * @covers       \MediaWiki\HookRunner\HookContainer::scopedRegister
		 */
		public function testScopedRegister() {
			$hookContainer = $this->newHookContainer();
			$reset = $hookContainer->scopedRegister( 'MWTestHook', [ new FooClass(), 'FooMethod' ] );
			$this->assertTrue( $hookContainer->isRegistered( 'MWTestHook' ) );
			ScopedCallback::consume( $reset );
			$this->assertFalse( $hookContainer->isRegistered( 'MWTestHook' ) );
		}

		/**
		 * @covers       \MediaWiki\HookRunner\HookContainer::scopedRegister
		 */
		public function testScopedRegister2() {
			$hookContainer = $this->newHookContainer();
			$called1 = $called2 = false;
			$reset1 = $hookContainer->scopedRegister( 'MWTestHook',
				function () use ( &$called1 ) {
					$called1 = true;
				}
			);
			$reset2 = $hookContainer->scopedRegister( 'MWTestHook',
				function () use ( &$called2 ) {
					$called2 = true;
				}
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
		 * @covers       \MediaWiki\HookRunner\HookContainer::isRegistered
		 */
		public function testNotRegisteredLegacy() {
			$hookContainer = $this->newHookContainer();
			$this->assertFalse( $hookContainer->isRegistered( 'UnregisteredHook' ) );
		}

		/**
		 * @covers       \MediaWiki\HookRunner\HookContainer::getHandlers
		 * @dataProvider provideGetHandlers
		 * @param $hook
		 * @param $handlersToRegister
		 * @param $expectedReturn
		 */
		public function testGetHandlers( $hook, $handlersToRegister, $expectedReturn ) {
			$mockExtensionRegistry = $this->getMockExtensionRegistry( $handlersToRegister );
			$mockDeprecatedHooks = $this->getMockDeprecatedHooks();
			$mockDeprecatedHooks->method( 'isHookDeprecated' )->will( $this->returnValueMap( [
				[ 'FooActionComplete', false ],
				[ 'FooActionCompleteDeprecated', true ]
			] ) );
			$hookContainer = $this->newHookContainer( $mockExtensionRegistry, null, $mockDeprecatedHooks );
			$handlers = $hookContainer->getHandlers( $hook );
			$this->assertArrayEquals(
				$handlers,
				$expectedReturn,
				'HookContainer::getHandlers() should return array of handler functions'
			);
		}

		/**
		 * @dataProvider provideRunLegacyErrors
		 * @covers       \MediaWiki\HookRunner\HookContainer::normalizeHandler
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
		 * @covers       \MediaWiki\HookRunner\HookContainer::getLegacyHandlers
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
				'HookContainer::getLegacyHandlers() should return array of handler functions'
			);
		}

		/**
		 * @covers       \MediaWiki\HookRunner\HookContainer::run
		 * @covers       \MediaWiki\HookRunner\HookContainer::callLegacyHook
		 * @covers       \MediaWiki\HookRunner\HookContainer::normalizeHandler
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
		 * @covers       \MediaWiki\HookRunner\HookContainer::run
		 * @covers       \MediaWiki\HookRunner\HookContainer::normalizeHandler
		 * Test HookContainer::run() with abort option
		 */
		public function testRunAbort() {
			$handler = [ 'handler' => [
				'name' => 'Path/To/extension.json-InvalidReturnHandler',
				'class' => 'FooExtension\\Hooks',
				'services' => [] ]
			];
			$mockExtensionRegistry = $this->getMockExtensionRegistry( $handler, 'InvalidReturnHandler' );
			$hookContainer = $this->newHookContainer( $mockExtensionRegistry );
			$this->expectException( UnexpectedValueException::class );
			$this->expectExceptionMessage(
				"Invalid return from onInvalidReturnHandler for " .
				"unabortable InvalidReturnHandler"
			);
			$hookRun = $hookContainer->run( 'InvalidReturnHandler', [], [ 'abortable' => false ] );
			$this->assertTrue( $hookRun );
		}

		/**
		 * @covers       \MediaWiki\HookRunner\HookContainer::register
		 * Test HookContainer::register() successfully registers even when hook is deprecated
		 */
		public function testRegisterDeprecated() {
			$this->hideDeprecated( 'FooActionComplete hook' );
			$mockDeprecatedHooks = $this->getMockDeprecatedHooks();
			$mockDeprecatedHooks->method( 'isHookDeprecated' )->with( 'FooActionComplete' )
				->willReturn( true );
			$mockDeprecatedHooks->method( 'getDeprecationInfo' )->with( 'FooActionComplete' )
				->willReturn( [ 'deprecatedVersion' => '1.0' ] );
			$handler = [
				'handler' => [
					'name' => 'Path/To/extension.json-FooActionHandler',
					'class' => 'FooExtension\\Hooks',
					'services' => []
				]
			];
			$mockRegistry = $this->getMockExtensionRegistry( $handler, 'FooActionComplete' );
			$hookContainer = $this->newHookContainer( $mockRegistry, null, $mockDeprecatedHooks );
			$hookContainer->register( 'FooActionComplete', new FooClass() );
			$this->assertTrue( $hookContainer->isRegistered( 'FooActionComplete' ) );
		}

		/**
		 * @covers       \MediaWiki\HookRunner\HookContainer::isRegistered
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
		 * @covers       \MediaWiki\HookRunner\HookContainer::run
		 * @covers       \MediaWiki\HookRunner\HookContainer::normalizeHandler
		 * Test HookContainer::run() throws exceptions appropriately
		 */
		public function testRunExceptions() {
			$handler = [ 'handler' => [
				'name' => 'Path/To/extension.json-InvalidReturnHandler',
				'class' => 'FooExtension\\Hooks',
				'services' => [] ]
			];
			$mockExtensionRegistry = $this->getMockExtensionRegistry( $handler, 'InvalidReturnHandler' );
			$hookContainer = $this->newHookContainer( $mockExtensionRegistry );
			$this->expectException( UnexpectedValueException::class );
			$hookContainer->run( 'InvalidReturnHandler' );
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

}
