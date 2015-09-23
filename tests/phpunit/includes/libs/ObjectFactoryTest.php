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
		$obj = ObjectFactory::getObjectFromSpec( array(
			'class' => 'ObjectFactoryTest_Fixture',
			'args' => array( function() {
				return 'unwrapped';
			}, ),
			'calls' => array(
				'setter' => array( function() {
					return 'unwrapped';
				}, ),
			),
			'closure_expansion' => false,
		) );
		$this->assertInstanceOf( 'Closure', $obj->args[0] );
		$this->assertSame( 'unwrapped', $obj->args[0]() );
		$this->assertInstanceOf( 'Closure', $obj->setterArgs[0] );
		$this->assertSame( 'unwrapped', $obj->setterArgs[0]() );
	}

	/**
	 * @covers ObjectFactory::getObjectFromSpec
	 */
	public function testClosureExpansionEnabled() {
		$obj = ObjectFactory::getObjectFromSpec( array(
			'class' => 'ObjectFactoryTest_Fixture',
			'args' => array( function() {
				return 'unwrapped';
			}, ),
			'calls' => array(
				'setter' => array( function() {
					return 'unwrapped';
				}, ),
			),
			'closure_expansion' => true,
		) );
		$this->assertInternalType( 'string', $obj->args[0] );
		$this->assertSame( 'unwrapped', $obj->args[0] );
		$this->assertInternalType( 'string', $obj->setterArgs[0] );
		$this->assertSame( 'unwrapped', $obj->setterArgs[0] );

		$obj = ObjectFactory::getObjectFromSpec( array(
			'class' => 'ObjectFactoryTest_Fixture',
			'args' => array( function() {
				return 'unwrapped';
			}, ),
			'calls' => array(
				'setter' => array( function() {
					return 'unwrapped';
				}, ),
			),
		) );
		$this->assertInternalType( 'string', $obj->args[0] );
		$this->assertSame( 'unwrapped', $obj->args[0] );
		$this->assertInternalType( 'string', $obj->setterArgs[0] );
		$this->assertSame( 'unwrapped', $obj->setterArgs[0] );
	}
}

class ObjectFactoryTest_Fixture {
	public $args;
	public $setterArgs;
	public function __construct( /*...*/ ) {
		$this->args = func_get_args();
	}
	public function setter( /*...*/ ) {
		$this->setterArgs = func_get_args();
	}
}
