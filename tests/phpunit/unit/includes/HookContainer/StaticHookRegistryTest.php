<?php
/**
 * This program is free software; you can redistribute it and/or modify
 * it under the terms of the GNU General Public License as published by
 * the Free Software Foundation; either version 2 of the License, or
 * (at your option) any later version.
 *
 * This program is distributed in the hope that it will be useful,
 * but WITHOUT ANY WARRANTY; without even the implied warranty of
 * MERCHANTABILITY or FITNESS FOR A PARTICULAR PURPOSE. See the
 * GNU General Public License for more details.
 *
 * You should have received a copy of the GNU General Public License along
 * with this program; if not, write to the Free Software Foundation, Inc.,
 * 51 Franklin Street, Fifth Floor, Boston, MA 02110-1301, USA.
 * http://www.gnu.org/copyleft/gpl.html
 *
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
