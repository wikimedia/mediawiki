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

namespace MediaWiki\Tests\Unit;

use MediaWiki\HookContainer\HookContainer;
use MediaWikiUnitTestCase;
use ProxyLookup;

/**
 * @author DannyS712
 *
 * @coversDefaultClass \ProxyLookup
 */
class ProxyLookupTest extends MediaWikiUnitTestCase {

	/**
	 * @covers ::__construct
	 */
	public function testConstruct() {
		$proxyLookup = new ProxyLookup(
			[],
			[],
			$this->createNoOpMock( HookContainer::class )
		);
		$this->assertInstanceOf( ProxyLookup::class, $proxyLookup, 'No errors' );
	}

	public function provideIsConfiguredProxy() {
		// $ip, $expected
		yield 'Listed ip #1' => [ '1.1.1.1', true ];
		yield 'Listed ip #2' => [ '2.2.2.2', true ];
		yield 'In complex list #1' => [ '127.0.0.127', true ];
		yield 'In complex list #2' => [ '255.0.0.127', true ];
		yield 'Not listed #1' => [ '3.3.3.3', false ];
		yield 'Not listed #2' => [ '255.255.255.255', false ];
	}

	/**
	 * @covers ::isConfiguredProxy
	 * @dataProvider provideIsConfiguredProxy
	 */
	public function testIsConfiguredProxy( string $ip, bool $expected ) {
		// Should never be called
		$hookContainer = $this->createNoOpMock( HookContainer::class );

		// Not an exhaustive test of the functionality of IPSet since that has its own
		// tests, just need to make sure it works
		$proxyLookup = new ProxyLookup(
			[
				'1.1.1.1',
				'2.2.2.2',
			],
			[
				'127.0.0.0/24',
				'255.0.0.0/24',
			],
			$hookContainer
		);

		$this->assertSame( $expected, $proxyLookup->isConfiguredProxy( $ip ) );
	}

	public function provideIsTrustedProxy() {
		// $ip, $expectedForHookCall, $hookResult
		yield 'Listed, hook return true' => [ '1.1.1.1', true, true ];
		yield 'Listed, hook return false' => [ '1.1.1.1', true, false ];
		yield 'Not listed, hook return true' => [ '2.2.2.2', false, true ];
		yield 'Not listed, hook return false' => [ '2.2.2.2', false, false ];
	}

	/**
	 * @covers ::isTrustedProxy
	 * @dataProvider provideIsTrustedProxy
	 */
	public function testIsTrustedProxy(
		string $ip,
		bool $expectedForHookCall,
		bool $hookResult
	) {
		// Hook should be called with the correct parameters
		$hookCalled = false;
		$hookCallback = function ( $hookIP, &$trusted ) use ( &$hookCalled, $ip, $expectedForHookCall, $hookResult ) {
			$hookCalled = true;

			// Make sure called correctly
			$this->assertSame( $ip, $hookIP, 'Hook called with the right IP' );
			$this->assertSame( $expectedForHookCall, $trusted, 'Hook called with the correct $trusted' );
			$trusted = $hookResult;
		};

		$hookContainer = $this->createHookContainer( [
			'IsTrustedProxy' => $hookCallback,
		] );

		$proxyLookup = new ProxyLookup(
			[ '1.1.1.1' ],
			[],
			$hookContainer
		);

		$this->assertSame( $hookResult, $proxyLookup->isTrustedProxy( $ip ) );
		$this->assertTrue( $hookCalled );
	}

}
