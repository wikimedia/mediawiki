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
use MediaWiki\HookContainer\GlobalHookRegistry;

/**
 * @author DannyS712
 *
 * @covers \MediaWiki\HookContainer\GlobalHookRegistry
 */
class GlobalHookRegistryTest extends MediaWikiUnitTestCase {

	public function testGlobalHookRegistry() {
		global $wgHooks;

		$deprecatedHooks = new DeprecatedHooks();
		$extensionHooks = [ 'extension hooks' ]; // Format doesn't matter for now

		$extensionRegistry = $this->createMock( ExtensionRegistry::class );
		$extensionRegistry->method( 'getAttribute' )->willReturn( $extensionHooks );

		$globalHookRegistery = new GlobalHookRegistry( $extensionRegistry, $deprecatedHooks );

		$this->assertEquals( $wgHooks, $globalHookRegistery->getGlobalHooks() );
		$this->assertEquals( $extensionHooks, $globalHookRegistery->getExtensionHooks() );
		$this->assertSame( $deprecatedHooks, $globalHookRegistery->getDeprecatedHooks() );
	}

}
