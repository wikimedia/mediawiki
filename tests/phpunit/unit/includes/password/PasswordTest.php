<?php
/**
 * Testing framework for the Password infrastructure
 *
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

/**
 * @covers Password
 */
class PasswordTest extends \MediaWikiUnitTestCase {
	public function testNoTypeThrows() {
		$factory = new PasswordFactory();
		$this->expectException( Exception::class );
		$this->expectExceptionMessage( 'Password configuration must contain a type name' );
		$this->getMockBuilder( Password::class )
			->setConstructorArgs( [ $factory, [] ] )
			->getMock();
	}

	public function testGetType() {
		$factory = new PasswordFactory();
		$password = new MWOldPassword( $factory, [ 'type' => 'this is a test' ] );
		$this->assertSame( 'this is a test', $password->getType() );
	}

	public function testToStringThrows() {
		$factory = new PasswordFactory();
		$password = new MWOldPassword( $factory, [ 'type' => 'B' ], str_repeat( 'X', 300 ) );
		$this->expectException( PasswordError::class );
		$this->expectExceptionMessage( 'Password hash is too big' );
		$password->toString();
	}

	public function testToString() {
		$factory = new PasswordFactory();
		$password = $this->getMockBuilder( Password::class )
			->setConstructorArgs( [ $factory, [ 'type' => 'X' ], ':X:foo' ] )
			->getMockForAbstractClass();
		$this->assertSame( ':X:foo', $password->toString() );
	}
}
