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

use PHPUnit\Framework\Error\Deprecated;

/**
 * @covers DeprecatedGlobal
 */
class DeprecatedGlobalTest extends MediaWikiIntegrationTestCase {
	private $oldErrorLevel;

	protected function setUp() : void {
		parent::setUp();
		$this->oldErrorLevel = error_reporting( -1 );
	}

	protected function tearDown() : void {
		error_reporting( $this->oldErrorLevel );
		parent::tearDown();
	}

	public function testObjectDeStub() {
		global $wgDummy;

		$wgDummy = new DeprecatedGlobal( 'wgDummy', new HashBagOStuff(), '1.30' );
		$this->assertInstanceOf( DeprecatedGlobal::class, $wgDummy );

		$this->hideDeprecated( '$wgDummy' );
		// Trigger de-stubification
		$wgDummy->get( 'foo' );

		$this->assertInstanceOf( HashBagOStuff::class, $wgDummy );
	}

	public function testLazyLoad() {
		global $wgDummyLazy;

		$called = false;
		$factory = function () use ( &$called ) {
			$called = true;
			return new HashBagOStuff();
		};

		$wgDummyLazy = new DeprecatedGlobal( 'wgDummyLazy', $factory, '1.30' );
		$this->assertInstanceOf( DeprecatedGlobal::class, $wgDummyLazy );

		$this->hideDeprecated( '$wgDummyLazy' );
		$this->assertFalse( $called );
		// Trigger de-stubification
		$wgDummyLazy->get( 'foo' );
		$this->assertTrue( $called );
		$this->assertInstanceOf( HashBagOStuff::class, $wgDummyLazy );
	}

	public function testWarning() {
		global $wgDummy1;

		$wgDummy1 = new DeprecatedGlobal( 'wgDummy1', new HashBagOStuff(), '1.30' );
		$this->expectException( Deprecated::class );
		$this->expectExceptionMessage( 'Use of $wgDummy1 was deprecated in MediaWiki 1.30' );
		$wgDummy1->get( 'foo' );
		$this->assertInstanceOf( HashBagOStuff::class, $wgDummy1 );
	}

}
