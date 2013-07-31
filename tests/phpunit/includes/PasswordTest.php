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
 * @group large
 */
class PasswordTest extends MediaWikiTestCase {

	public static function providePasswordTypes() {
		$types = array();
		foreach ( array_keys( User::getPasswordFactory()->getTypes() ) as $type ) {
			$types[] = array( $type );
		}
		return $types;
	}

	public static function providePasswordTests() {
		$tests = array();
		foreach ( User::getPasswordFactory()->getTypes() as $type => $config ) {
			if ( $type === '' ) {
				continue;
			}
			$password = User::getPasswordFactory()->newFromType( $type );
			$tests += $password->tests();
		}

		return $tests;
	}

	/**
	 * @dataProvider providePasswordTests
	 * @covers Password::newFromCiphertext
	 * @covers Password::newFromPlaintext
	 * @covers Password::equals
	 */
	public function testHashing( $shouldMatch, $hash, $password ) {
		$hash = User::getPasswordFactory()->newFromCiphertext( $hash );
		$password = User::getPasswordFactory()->newFromPlaintext( $password, $hash );
		$this->assertSame( $shouldMatch, $hash->equals( $password ) );
	}

	/**
	 * @dataProvider providePasswordTests
	 * @covers Password::serialize
	 * @covers Password::unserialize
	 * @covers Password::toString
	 */
	public function testStringSerialization( $shouldMatch, $hash, $password ) {
		$hash = User::getPasswordFactory()->newFromCiphertext( $hash );
		$serialized = $hash->toString();
		$unserialized = User::getPasswordFactory()->newFromCiphertext( $serialized );
		$this->assertTrue( $hash->equals( $unserialized ) );
	}

	/**
	 * @covers InvalidPassword::equals
	 */
	public function testInvalidUnequalInvalid() {
		$invalid1 = User::getPasswordFactory()->newFromCiphertext( null );
		$invalid2 = User::getPasswordFactory()->newFromCiphertext( null );

		$this->assertFalse( $invalid1->equals( $invalid2 ) );
	}

	/**
	 * @dataProvider providePasswordTests
	 * @covers InvalidPassword::equals
	 * @covers InvalidPassword::toString
	 */
	public function testInvalidUnequalNormal( $shouldMatch, $hash, $password ) {
		$invalid = User::getPasswordFactory()->newFromCiphertext( null );
		$normal = User::getPasswordFactory()->newFromCiphertext( $hash );

		$this->assertFalse( $invalid->equals( $normal ) );
		$this->assertFalse( $normal->equals( $invalid ) );
	}

	/**
	 * @dataProvider providePasswordTypes
	 * @covers Password::newFromType
	 * @covers Password::getType
	 */
	public function testPasswordTypeTransparency( $type ) {
		$password = User::getPasswordFactory()->newFromType( $type );
		$this->assertSame( $type, $password->getType() );
	}

}
