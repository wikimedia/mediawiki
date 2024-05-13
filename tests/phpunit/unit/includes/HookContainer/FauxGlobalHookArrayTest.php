<?php

namespace MediaWiki\Tests\HookContainer;

use MediaWiki\HookContainer\FauxGlobalHookArray;
use MediaWiki\HookContainer\HookContainer;
use MediaWiki\HookContainer\StaticHookRegistry;
use MediaWikiUnitTestCase;
use Wikimedia\ObjectFactory\ObjectFactory;

/**
 * Tests that all arguments passed into FauxGlobalHookArray are passed along to HookContainer.
 * @covers \MediaWiki\HookContainer\FauxGlobalHookArray
 * @covers \MediaWiki\HookContainer\FauxHookHandlerArray
 */
class FauxGlobalHookArrayTest extends MediaWikiUnitTestCase {

	public function testRegisterHandler() {
		$this->expectDeprecationAndContinue( '/Accessing \$wgHooks directly is deprecated/' );

		$counter = 0;

		$handler = static function () use ( &$counter ) {
			$counter++;
		};

		$registry = new StaticHookRegistry();
		$factory = $this->createNoOpMock( ObjectFactory::class );
		$container = new HookContainer( $registry, $factory );
		$hooks = new FauxGlobalHookArray( $container );

		$this->expectDeprecationAndContinue( '/getHandlerCallbacks/' );

		// Register a handler via the array
		$hooks['FirstHook'][] = $handler;

		$this->assertTrue( $container->isRegistered( 'FirstHook' ) );
		$this->assertCount( 1, $container->getHandlerCallbacks( 'FirstHook' ) );

		$this->assertTrue( isset( $hooks['FirstHook'] ) );
		$this->assertCount( 1, $hooks['FirstHook'] );

		$first = $hooks['FirstHook'][0];
		$this->assertSame( $handler, $first );

		$container->run( 'FirstHook' );
		$this->assertSame( 1, $counter );

		// Register a handler via the HookContainer
		$container->register( 'SecondHook', $handler );

		$this->assertTrue( isset( $hooks['SecondHook'] ) );
		$this->assertCount( 1, $hooks['SecondHook'] );

		$first = $hooks['SecondHook'][0];
		$this->assertSame( $handler, $first );
	}

}
