<?php

namespace MediaWiki\HookContainer {

	use ExtensionRegistry;
	use MediaWiki\MediaWikiServices;
	use Wikimedia\ScopedCallback;

	class HookContainerIntegrationTest extends \MediaWikiIntegrationTestCase {

		/**
		 * @covers \MediaWiki\HookContainer\HookContainer::run
		 */
		public function testHookRunsWhenExtensionRegistered() {
			$hookContainer = MediaWikiServices::getInstance()->getHookContainer();
			$extensionRegistry = ExtensionRegistry::getInstance();
			$numHandlersExecuted = 0;
			$handlers = [ 'FooHook' => [ [
				'handler' => [
					'class' => 'FooExtension\\FooExtensionHooks',
					'name' => 'FooExtension-FooHandler',
				] ] ]
			];
			$reset = $extensionRegistry->setAttributeForTest( 'Hooks', $handlers );
			$this->assertEquals( $numHandlersExecuted, 0 );
			$hookContainer->run( 'FooHook', [ &$numHandlersExecuted ] );
			$this->assertEquals( $numHandlersExecuted, 1 );
			ScopedCallback::consume( $reset );
		}

		/**
		 * @covers \MediaWiki\HookContainer\HookContainer::run
		 * @covers \MediaWiki\HookContainer\HookContainer::scopedRegister
		 */
		public function testPreviouslyRegisteredHooksAreReAppliedAfterScopedRegisterRemovesThem() {
			$hookContainer = MediaWikiServices::getInstance()->getHookContainer();

			// Some handlers for FooHook have been previously set
			$reset = $hookContainer->register( 'FooHook', function () {
				return true;
			} );
			$reset1 = $hookContainer->register( 'FooHook', function () {
				return true;
			} );
			$handlersBeforeScopedRegister = $hookContainer->getLegacyHandlers( 'FooHook' );
			$this->assertCount( 2, $handlersBeforeScopedRegister );

			// Wipe out the 2 existing handlers and add a new scoped handler
			$reset2 = $hookContainer->scopedRegister( 'FooHook', function () {
				return true;
			}, true );
			$handlersAfterScopedRegister = $hookContainer->getLegacyHandlers( 'FooHook' );
			$this->assertCount( 1, $handlersAfterScopedRegister );

			ScopedCallback::consume( $reset2 );

			// Teardown causes the original handlers to be re-applied
			$this->mediaWikiTearDown();

			$handlersAfterTearDown = $hookContainer->getLegacyHandlers( 'FooHook' );
			$this->assertCount( 2, $handlersAfterTearDown );
		}

		/**
		 * @covers \MediaWiki\HookContainer\HookContainer::run
		 * @covers \MediaWiki\HookContainer\HookContainer::scopedRegister
		 */
		public function testHookRunsWithMultipleMixedHandlerTypes() {
			$hookContainer = MediaWikiServices::getInstance()->getHookContainer();
			$numHandlersExecuted = 0;
			$reset = $hookContainer->scopedRegister( 'FooHook', function ( &$numHandlersRun ) {
				$numHandlersRun++;
			}, false );
			$reset2 = $hookContainer->scopedRegister( 'FooHook', function ( &$numHandlersRun ) {
				$numHandlersRun++;
			}, false );
			$handlerThree = [
				'FooHook' => [
					[ 'handler' => [
						'class' => 'FooExtension\\FooExtensionHooks',
						'name' => 'FooExtension-FooHandler',
					]
					]
				]
			];
			$reset3 = ExtensionRegistry::getInstance()->setAttributeForTest( 'Hooks', $handlerThree );
			$hookContainer->run( 'FooHook', [ &$numHandlersExecuted ] );
			$this->assertEquals( $numHandlersExecuted, 3 );
			ScopedCallback::consume( $reset );
			ScopedCallback::consume( $reset2 );
			ScopedCallback::consume( $reset3 );
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
			$hookContainer = MediaWikiServices::getInstance()->getHookContainer();
			$reset = ExtensionRegistry::getInstance()->setAttributeForTest( 'Hooks', $hooks );
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
			$hookContainer = MediaWikiServices::getInstance()->getHookContainer();
			$reset = ExtensionRegistry::getInstance()->setAttributeForTest( 'Hooks', $hooks );
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
		public function __construct( \ReadOnlyMode $readOnlyMode ) {
		}

		public function onMash( &$arg ) {
			$arg++;
			return true;
		}
	}

}
