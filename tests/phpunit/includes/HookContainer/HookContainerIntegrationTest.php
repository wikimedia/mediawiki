<?php

namespace MediaWiki\HookContainer {

	use ExtensionRegistry;
	use Wikimedia\ScopedCallback;

	class HookContainerIntegrationTest extends \MediaWikiIntegrationTestCase {

		/**
		 * @covers \MediaWiki\HookContainer\HookContainer::run
		 */
		public function testHookRunsWhenExtensionRegistered() {
			$extensionRegistry = ExtensionRegistry::getInstance();
			$numHandlersExecuted = 0;
			$handlers = [ 'FooHook' => [ [
				'handler' => [
					'class' => 'FooExtension\\FooExtensionHooks',
					'name' => 'FooExtension-FooHandler',
				] ] ]
			];
			$reset = $extensionRegistry->setAttributeForTest( 'Hooks', $handlers );
			$this->assertSame( 0, $numHandlersExecuted );

			$this->resetServices();
			$hookContainer = $this->getServiceContainer()->getHookContainer();
			$hookContainer->run( 'FooHook', [ &$numHandlersExecuted ] );
			$this->assertSame( 1, $numHandlersExecuted );
			ScopedCallback::consume( $reset );
		}

		/**
		 * @covers \MediaWiki\HookContainer\HookContainer::run
		 * @covers \MediaWiki\HookContainer\HookContainer::scopedRegister
		 */
		public function testPreviouslyRegisteredHooksAreReAppliedAfterScopedRegisterRemovesThem() {
			$hookContainer = $this->getServiceContainer()->getHookContainer();

			// Some handlers for FooHook have been previously set
			$hookContainer->register( 'FooHook', static function () {
				return true;
			} );
			$hookContainer->register( 'FooHook', static function () {
				return true;
			} );
			$handlersBeforeScopedRegister = $hookContainer->getHandlerCallbacks( 'FooHook' );
			$this->assertCount( 2, $handlersBeforeScopedRegister );

			// Wipe out the 2 existing handlers and add a new scoped handler
			$reset2 = $hookContainer->scopedRegister( 'FooHook', static function () {
				return true;
			}, true );
			$handlersAfterScopedRegister = $hookContainer->getHandlerCallbacks( 'FooHook' );
			$this->assertCount( 1, $handlersAfterScopedRegister );

			ScopedCallback::consume( $reset2 );

			// Teardown causes the original handlers to be re-applied
			$this->mediaWikiTearDown();

			$handlersAfterTearDown = $hookContainer->getHandlerCallbacks( 'FooHook' );
			$this->assertCount( 2, $handlersAfterTearDown );
		}

		/**
		 * @covers \MediaWiki\HookContainer\HookContainer::run
		 * @covers \MediaWiki\HookContainer\HookContainer::scopedRegister
		 */
		public function testHookRunsWithMultipleMixedHandlerTypes() {
			$handlerExt = [
				'FooHook' => [
					[ 'handler' => [
						'class' => 'FooExtension\\FooExtensionHooks',
						'name' => 'FooExtension-FooHandler',
					]
					]
				]
			];
			$resetExt = ExtensionRegistry::getInstance()->setAttributeForTest( 'Hooks', $handlerExt );

			$this->resetServices();
			$hookContainer = $this->getServiceContainer()->getHookContainer();

			$numHandlersExecuted = 0;
			$reset = $hookContainer->scopedRegister( 'FooHook', static function ( &$numHandlersRun ) {
				$numHandlersRun++;
			}, false );
			$reset2 = $hookContainer->scopedRegister( 'FooHook', static function ( &$numHandlersRun ) {
				$numHandlersRun++;
			}, false );

			$hookContainer->run( 'FooHook', [ &$numHandlersExecuted ] );
			$this->assertEquals( 3, $numHandlersExecuted );

			ScopedCallback::consume( $reset );
			ScopedCallback::consume( $reset2 );
			ScopedCallback::consume( $resetExt );
		}

		/**
		 * @covers \MediaWiki\HookContainer\HookContainer
		 */
		public function testValidServiceInjection() {
			$handler = [
				'handler' => [
					'name' => 'FooExtension-Mash',
					'class' => 'FooExtension\\ServiceHooks',
					'services' => [ 'ReadOnlyMode' ]
				],
				'extensionPath' => '/path/to/extension.json'
			];
			$hooks = [ 'Mash' => [ $handler ] ];
			$reset = ExtensionRegistry::getInstance()->setAttributeForTest( 'Hooks', $hooks );

			$this->resetServices();
			$hookContainer = $this->getServiceContainer()->getHookContainer();

			$arg = 0;
			$ret = $hookContainer->run( 'Mash', [ &$arg ] );
			$this->assertTrue( $ret );
			$this->assertSame( 1, $arg );
		}

		/**
		 * @covers \MediaWiki\HookContainer\HookContainer
		 */
		public function testInvalidServiceInjection() {
			$handler = [
				'handler' => [
					'name' => 'FooExtension-Mash',
					'class' => 'FooExtension\\ServiceHooks',
					'services' => [ 'ReadOnlyMode' ]
				],
				'extensionPath' => '/path/to/extension.json'
			];
			$hooks = [ 'Mash' => [ $handler ] ];
			$reset = ExtensionRegistry::getInstance()->setAttributeForTest( 'Hooks', $hooks );

			$this->resetServices();
			$hookContainer = $this->getServiceContainer()->getHookContainer();

			$this->expectException( \UnexpectedValueException::class );
			$arg = 0;
			$hookContainer->run( 'Mash', [ &$arg ], [ 'noServices' => true ] );
		}
	}
}

namespace FooExtension {

	class FooExtensionHooks {

		public function onFooHook( &$numHandlersRun ) {
			$numHandlersRun++;
		}
	}

	class ServiceHooks {
		public function __construct( \Wikimedia\Rdbms\ReadOnlyMode $readOnlyMode ) {
		}

		public function onMash( &$arg ) {
			$arg++;
			return true;
		}
	}

}
