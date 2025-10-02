<?php
/**
 * @license GPL-2.0-or-later
 * @file
 */

use MediaWiki\HookContainer\DeprecatedHooks;
use MediaWiki\HookContainer\StaticHookRegistry;

/**
 * @author DannyS712
 *
 * @covers \MediaWiki\HookContainer\StaticHookRegistry
 */
class StaticHookRegistryTest extends MediaWikiUnitTestCase {

	public function testStaticHookRegistry() {
		// Since the actual format for the first two isn't checked in the StaticHookRegistry
		// code, no need to follow it
		$globalHooks = [ 'global hooks' ];
		$extensionHooks = [ 'extension hooks' ];
		$deprecatedHooks = [
			'ExampleDeprecatedHook' => [ 'deprecatedVersion' => '1.36' ]
		];
		$staticHookRegistry = new StaticHookRegistry(
			$globalHooks,
			$extensionHooks,
			$deprecatedHooks
		);
		$this->assertEquals( $globalHooks, $staticHookRegistry->getGlobalHooks() );
		$this->assertEquals( $extensionHooks, $staticHookRegistry->getExtensionHooks() );
		$this->assertInstanceOf( DeprecatedHooks::class, $staticHookRegistry->getDeprecatedHooks() );
	}

}
