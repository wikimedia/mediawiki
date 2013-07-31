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
	function setUp() {
		parent::setUp();

		Password::register( 'testLargeLayeredTop', array(
			'class' => 'LayeredParameterizedPassword',
			'types' => array(
				'testLargeLayeredBottom',
				'testLargeLayeredBottom',
				'testLargeLayeredBottom',
				'testLargeLayeredBottom',
				'testLargeLayeredBottom',
			),
		) );
		Password::register( 'testLargeLayeredBottom', array(
			'class' => 'BcryptPassword',
			'cost' => 5,
		) );
	}

	public static function providePasswordTypes() {
		$types = array();
		foreach ( array_keys( Password::getTypes() ) as $type ) {
			$types[] = array( $type );
		}
		return $types;
	}

	public static function providePasswordTests() {
		return Password::getTests();
	}

	/**
	 * @dataProvider providePasswordTests
	 * @covers Password::newFromCiphertext
	 * @covers Password::newFromPlaintext
	 * @covers Password::equals
	 */
	public function testHashing( $shouldMatch, $hash, $password ) {
		$hash = Password::newFromCiphertext( $hash );
		$password = Password::newFromPlaintext( $password, $hash );
		$this->assertSame( $shouldMatch, $hash->equals( $password ) );
	}

	/**
	 * @dataProvider providePasswordTests
	 * @covers Password::serialize
	 * @covers Password::unserialize
	 * @covers Password::equals
	 */
	public function testPhpSerialization( $shouldMatch, $hash, $password ) {
		$hash = Password::newFromCiphertext( $hash );
		$serialized = serialize( $hash );
		$unserialized = unserialize( $serialized );
		$this->assertTrue( $hash->equals( $unserialized ) );
	}

	/**
	 * @dataProvider providePasswordTests
	 * @covers Password::serialize
	 * @covers Password::unserialize
	 * @covers Password::toString
	 */
	public function testStringSerialization( $shouldMatch, $hash, $password ) {
		$hash = Password::newFromCiphertext( $hash );
		$serialized = $hash->serialize();
		$unserialized = Password::newFromCiphertext( $serialized );
		$this->assertTrue( $hash->equals( $unserialized ) );
	}

	/**
	 * @covers InvalidPassword::equals
	 */
	public function testInvalidUnequalInvalid() {
		$invalid1 = Password::newFromCiphertext( null );
		$invalid2 = Password::newFromCiphertext( null );

		$this->assertFalse( $invalid1->equals( $invalid2 ) );
	}

	/**
	 * @dataProvider providePasswordTests
	 * @covers InvalidPassword::equals
	 * @covers InvalidPassword::toString
	 */
	public function testInvalidUnequalNormal( $shouldMatch, $hash, $password ) {
		$invalid = Password::newFromCiphertext( null );
		$normal = Password::newFromCiphertext( $hash );

		$this->assertFalse( $invalid->equals( $normal ) );
		$this->assertFalse( $normal->equals( $invalid ) );
	}

	/**
	 * @dataProvider providePasswordTypes
	 * @covers Password::newFromType
	 * @covers Password::getType
	 */
	public function testPasswordTypeTransparency( $type ) {
		$password = Password::newFromType( $type );
		$this->assertSame( $type, $password->getType() );
	}

	/**
	 * @depends testPasswordTypeTransparency
	 * @covers Password::needsUpdate
	 */
	public function testPasswordUpdate() {
		$types = array_keys( Password::getTypes() );
		if ( count( $types ) < 2 ) {
			$this->markTestSkipped( 'Need at least two password types to test update.' );
		}

		$dflt = Password::newFromPlaintext( 'test' );
		$old = null;
		foreach ( $types as $type ) {
			if ( $type !== $dflt->getType() ) {
				$old = Password::newFromType( $type );
				break;
			}
		}

		$this->assertTrue( $old->needsUpdate() );
	}

	/**
	 * @covers LayeredParameterizedPassword::crypt
	 */
	public function testLargeLayeredPassword() {
		$password = Password::newFromType( 'testLargeLayeredTop' );
		$password->crypt( 'testPassword123' );
		$this->assertTrue( $password->equals( 'testPassword123' ) );
	}

	/**
	 * @depends testLargeLayeredPassword
	 * @covers LayeredParameterizedPassword::partialCrypt
	 */
	public function testLargeLayeredPartialUpdate() {
		$partialPassword = Password::newFromType( 'testLargeLayeredMiddle' );
		$partialPassword->crypt( 'testPassword123' );

		$totalPassword = Password::newFromType( 'testLargeLayeredTop' );
		$totalPassword->partialUpdate( $partialPassword );

		$this->assertTrue( $totalPassword->equals( 'testPassword123' ) );
	}
}
