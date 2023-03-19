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

use MediaWiki\StubObject\StubObject;

/**
 * Testing the magic for __get(), __set(), and __call() for our
 * example global, $wgDummy, which would be an instance
 * of DemoStubbed but is wrapped in a MediaWiki\StubObject\StubObject
 * @author DannyS712
 *
 * @covers \MediaWiki\StubObject\StubObject
 */
class StubObjectTest extends MediaWikiIntegrationTestCase {

	protected function setUp(): void {
		parent::setUp();

		global $wgDummy;
		$wgDummy = new StubObject(
			'wgDummy',
			[ self::class, 'factory' ]
		);
	}

	/**
	 * Static factory method for creating the underlying global, which is
	 * a DemoStubbed with the starting value of 5
	 */
	public static function factory(): DemoStubbed {
		return new DemoStubbed( 5 );
	}

	public function testCallMagic() {
		global $wgDummy;
		$this->assertInstanceOf(
			StubObject::class,
			$wgDummy,
			'Global starts as stub object'
		);
		$this->assertSame(
			5,
			$wgDummy->getNum(),
			'__call() based on id set in ::setUp()'
		);
		$this->assertInstanceOf(
			DemoStubbed::class,
			$wgDummy,
			'__call() resulted in unstubbing'
		);
	}

	public function testGetMagic() {
		// MediaWiki\StubObject\StubObject::__get() returning DemoStubbed::$num
		global $wgDummy;
		$this->assertInstanceOf(
			StubObject::class,
			$wgDummy,
			'Global starts as stub object'
		);
		$this->assertSame(
			5,
			$wgDummy->num,
			'__get() based on id set in ::setUp()'
		);
		$this->assertInstanceOf(
			DemoStubbed::class,
			$wgDummy,
			'__get() resulted in unstubbing'
		);
	}

	public function testGetMagic_virtual() {
		// MediaWiki\StubObject\StubObject::__get() calling DemoStubbed::__get()
		global $wgDummy;
		$this->assertInstanceOf(
			StubObject::class,
			$wgDummy,
			'Global starts as stub object'
		);
		$this->assertSame(
			10,
			$wgDummy->doubleNum,
			'__get() a virtual property based on id set in ::setUp()'
		);
		$this->assertInstanceOf(
			DemoStubbed::class,
			$wgDummy,
			'__get() resulted in unstubbing'
		);
	}

	public function testSetMagic() {
		// MediaWiki\StubObject\StubObject::__set() changing DemoStubbed::$num
		global $wgDummy;
		$this->assertInstanceOf(
			StubObject::class,
			$wgDummy,
			'Global starts as stub object'
		);
		$wgDummy->num = 100;
		$this->assertInstanceOf(
			DemoStubbed::class,
			$wgDummy,
			'__set() resulted in unstubbing'
		);
		$this->assertSame(
			100,
			$wgDummy->num,
			'__set() changed the value'
		);
	}

	public function testSetMagic_virtual() {
		// MediaWiki\StubObject\StubObject::__set() calling DemoStubbed::__set()
		global $wgDummy;
		$this->assertInstanceOf(
			StubObject::class,
			$wgDummy,
			'Global starts as stub object'
		);
		$wgDummy->doubleNum = 100;
		$this->assertInstanceOf(
			DemoStubbed::class,
			$wgDummy,
			'__set() resulted in unstubbing'
		);
		$this->assertSame(
			50,
			$wgDummy->num,
			'__set() changed the value'
		);
	}
}

/**
 * This is the object that we are stubbing so we can test the various magic methods
 */
class DemoStubbed {

	/** @var int */
	public $num;

	public function __construct( int $num ) {
		$this->num = $num;
	}

	public function getNum(): int {
		return $this->num;
	}

	/**
	 * Magic handling for retrieving fake property "doubleNum"
	 *
	 * @param string $field
	 * @return mixed
	 */
	public function __get( $field ) {
		if ( $field === 'doubleNum' ) {
			return ( 2 * $this->num );
		}
		trigger_error( 'Inaccessible property via __get(): ' . $field, E_USER_NOTICE );
	}

	/**
	 * Magic handling for setting fake property "doubleNum"
	 *
	 * @param string $field
	 * @param mixed $value
	 */
	public function __set( $field, $value ) {
		if ( $field === 'doubleNum' ) {
			$this->num = (int)( $value / 2 );
			return;
		}
		trigger_error( 'Inaccessible property via __set(): ' . $field, E_USER_NOTICE );
	}

}
