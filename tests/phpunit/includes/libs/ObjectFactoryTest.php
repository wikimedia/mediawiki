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

class ObjectFactoryTest extends PHPUnit_Framework_TestCase {

	/**
	 * @covers ObjectFactory::getObjectFromSpec
	 */
	public function testClosureExpansionDisabled() {
		$obj = ObjectFactory::getObjectFromSpec( [
			'class' => 'ObjectFactoryTestFixture',
			'args' => [
				function() {
					return 'wrapped';
				},
				'unwrapped',
			],
			'calls' => [
				'setter' => [ function() {
					return 'wrapped';
				}, ],
			],
			'closure_expansion' => false,
		] );
		$this->assertInstanceOf( 'Closure', $obj->args[0] );
		$this->assertSame( 'wrapped', $obj->args[0]() );
		$this->assertSame( 'unwrapped', $obj->args[1] );
		$this->assertInstanceOf( 'Closure', $obj->setterArgs[0] );
		$this->assertSame( 'wrapped', $obj->setterArgs[0]() );
	}

	/**
	 * @covers ObjectFactory::getObjectFromSpec
	 * @covers ObjectFactory::expandClosures
	 */
	public function testClosureExpansionEnabled() {
		$obj = ObjectFactory::getObjectFromSpec( [
			'class' => 'ObjectFactoryTestFixture',
			'args' => [
				function() {
					return 'wrapped';
				},
				'unwrapped',
			],
			'calls' => [
				'setter' => [ function() {
					return 'wrapped';
				}, ],
			],
			'closure_expansion' => true,
		] );
		$this->assertInternalType( 'string', $obj->args[0] );
		$this->assertSame( 'wrapped', $obj->args[0] );
		$this->assertSame( 'unwrapped', $obj->args[1] );
		$this->assertInternalType( 'string', $obj->setterArgs[0] );
		$this->assertSame( 'wrapped', $obj->setterArgs[0] );

		$obj = ObjectFactory::getObjectFromSpec( [
			'class' => 'ObjectFactoryTestFixture',
			'args' => [ function() {
				return 'unwrapped';
			}, ],
			'calls' => [
				'setter' => [ function() {
					return 'unwrapped';
				}, ],
			],
		] );
		$this->assertInternalType( 'string', $obj->args[0] );
		$this->assertSame( 'unwrapped', $obj->args[0] );
		$this->assertInternalType( 'string', $obj->setterArgs[0] );
		$this->assertSame( 'unwrapped', $obj->setterArgs[0] );
	}

	/**
	 * @covers ObjectFactory::getObjectFromSpec
	 */
	public function testGetObjectFromFactory() {
		$args = [ 'a', 'b' ];
		$obj = ObjectFactory::getObjectFromSpec( [
			'factory' => function ( $a, $b ) {
				return new ObjectFactoryTestFixture( $a, $b );
			},
			'args' => $args,
		] );
		$this->assertSame( $args, $obj->args );
	}

	/**
	 * @covers ObjectFactory::getObjectFromSpec
	 * @expectedException InvalidArgumentException
	 */
	public function testGetObjectFromInvalid() {
		$args = [ 'a', 'b' ];
		$obj = ObjectFactory::getObjectFromSpec( [
			// Missing 'class' or 'factory'
			'args' => $args,
		] );
	}

	/**
	 * @covers ObjectFactory::getObjectFromSpec
	 * @dataProvider provideConstructClassInstance
	 */
	public function testGetObjectFromClass( $args ) {
		$obj = ObjectFactory::getObjectFromSpec( [
			'class' => 'ObjectFactoryTestFixture',
			'args' => $args,
		] );
		$this->assertSame( $args, $obj->args );
	}

	/**
	 * @covers ObjectFactory::constructClassInstance
	 * @dataProvider provideConstructClassInstance
	 */
	public function testConstructClassInstance( $args ) {
		$obj = ObjectFactory::constructClassInstance(
			'ObjectFactoryTestFixture', $args
		);
		$this->assertSame( $args, $obj->args );
	}

	public static function provideConstructClassInstance() {
		// These args go to 11. I thought about making 10 one louder, but 11!
		return [
			'0 args' => [ [] ],
			'1 args' => [ [ 1, ] ],
			'2 args' => [ [ 1, 2, ] ],
			'3 args' => [ [ 1, 2, 3, ] ],
			'4 args' => [ [ 1, 2, 3, 4, ] ],
			'5 args' => [ [ 1, 2, 3, 4, 5, ] ],
			'6 args' => [ [ 1, 2, 3, 4, 5, 6, ] ],
			'7 args' => [ [ 1, 2, 3, 4, 5, 6, 7, ] ],
			'8 args' => [ [ 1, 2, 3, 4, 5, 6, 7, 8, ] ],
			'9 args' => [ [ 1, 2, 3, 4, 5, 6, 7, 8, 9, ] ],
			'10 args' => [ [ 1, 2, 3, 4, 5, 6, 7, 8, 9, 10, ] ],
			'11 args' => [ [ 1, 2, 3, 4, 5, 6, 7, 8, 9, 10, 11, ] ],
		];
	}

	/**
	 * @covers ObjectFactory::constructClassInstance
	 * @expectedException InvalidArgumentException
	 */
	public function testNamedArgs() {
		$args = [ 'foo' => 1, 'bar' => 2, 'baz' => 3 ];
		$obj = ObjectFactory::constructClassInstance(
			'ObjectFactoryTestFixture', $args
		);
	}
}

class ObjectFactoryTestFixture {
	public $args;
	public $setterArgs;
	public function __construct( /*...*/ ) {
		$this->args = func_get_args();
	}
	public function setter( /*...*/ ) {
		$this->setterArgs = func_get_args();
	}
}
