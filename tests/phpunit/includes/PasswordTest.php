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

		$passwordFactory = User::getPasswordFactory();

		$passwordFactory->register( 'testLargeLayeredTop', array(
				'class' => 'LayeredParameterizedPassword',
				'types' => array(
					'testLargeLayeredBottom',
					'testLargeLayeredBottom',
					'testLargeLayeredBottom',
					'testLargeLayeredBottom',
					'testLargeLayeredFinal',
				),
			) );
		$passwordFactory->register( 'testLargeLayeredBottom', array(
				'class' => 'Pbkdf2Password',
				'algo' => 'sha512',
				'cost' => 1024,
				'length' => 512,
			) );
		$passwordFactory->register( 'testLargeLayeredFinal', array(
				'class' => 'BcryptPassword',
				'cost' => 5,
			) );
	}

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
	 * @covers InvalidPassword::equals
	 * @covers InvalidPassword::toString
	 */
	public function testHashing( $shouldMatch, $hash, $password ) {
		$hash = User::getPasswordFactory()->newFromCiphertext( $hash );
		$invalid = User::getPasswordFactory()->newFromCiphertext( null );
		$invalid2 = User::getPasswordFactory()->newFromCiphertext( null );
		$password = User::getPasswordFactory()->newFromPlaintext( $password, $hash );
		$serialized = $hash->toString();
		$unserialized = User::getPasswordFactory()->newFromCiphertext( $serialized );

		$this->assertSame( $shouldMatch, $hash->equals( $password ) );
		$this->assertFalse( $invalid->equals( $invalid2 ) );
		$this->assertFalse( $invalid->equals( $hash ) );
		$this->assertFalse( $hash->equals( $invalid ) );
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


	public function testDoNothing() {
		$this->assertFalse( false );
	}
	public function testDoNothing01() {
		$this->assertFalse( false );
	}
	public function testDoNothing02() {
		$this->assertFalse( false );
	}
	public function testDoNothing03() {
		$this->assertFalse( false );
	}
	public function testDoNothing04() {
		$this->assertFalse( false );
	}
	public function testDoNothing05() {
		$this->assertFalse( false );
	}
	public function testDoNothing06() {
		$this->assertFalse( false );
	}
	public function testDoNothing07() {
		$this->assertFalse( false );
	}
	public function testDoNothing08() {
		$this->assertFalse( false );
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

	/**
	 * @depends testPasswordTypeTransparency
	 * @covers Password::needsUpdate
	 */
	public function testPasswordUpdate() {
		$types = array_keys( User::getPasswordFactory()->getTypes() );
		if ( count( $types ) < 2 ) {
			$this->markTestSkipped( 'Need at least two password types to test update.' );
		}

		$dflt = User::getPasswordFactory()->newFromPlaintext( 'test' );
		$old = null;
		foreach ( $types as $type ) {
			if ( $type !== $dflt->getType() ) {
				$old = User::getPasswordFactory()->newFromType( $type );
				break;
			}
		}

		$this->assertTrue( User::getPasswordFactory()->needsUpdate( $old ) );
	}

	/**
	 * @covers LayeredParameterizedPassword::crypt
	 */
	public function testLargeLayeredPassword() {
		$password = User::getPasswordFactory()->newFromType( 'testLargeLayeredTop' );
		$password->crypt( 'testPassword123' );
		$this->assertTrue( $password->equals( 'testPassword123' ) );
	}

	/**
	 * @depends testLargeLayeredPassword
	 * @covers LayeredParameterizedPassword::partialCrypt
	 */
	public function testLargeLayeredPartialUpdate() {
		/** @var ParameterizedPassword $partialPassword */
		$partialPassword = User::getPasswordFactory()->newFromType( 'testLargeLayeredBottom' );
		$partialPassword->crypt( 'testPassword123' );

		$totalPassword = User::getPasswordFactory()->newFromType( 'testLargeLayeredTop' );
		$totalPassword->partialCrypt( $partialPassword );

		$this->assertTrue( $totalPassword->equals( 'testPassword123' ) );
	}
}
