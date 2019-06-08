<?php
/**
 * Testing framework for the password hashes
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
 * @since 1.24
 */
abstract class PasswordTestCase extends MediaWikiTestCase {
	/**
	 * @var PasswordFactory
	 */
	protected $passwordFactory;

	protected function setUp() {
		parent::setUp();

		$this->passwordFactory = new PasswordFactory();
		foreach ( $this->getTypeConfigs() as $type => $config ) {
			$this->passwordFactory->register( $type, $config );
		}
	}

	/**
	 * Return an array of configs to be used for this class's password type.
	 *
	 * @return array[]
	 */
	abstract protected function getTypeConfigs();

	/**
	 * An array of tests in the form of (bool, string, string), where the first
	 * element is whether the second parameter (a password hash) and the third
	 * parameter (a password) should match.
	 * @return array
	 * @throws MWException
	 */
	public static function providePasswordTests() {
		throw new MWException( "Not implemented" );
	}

	/**
	 * @dataProvider providePasswordTests
	 */
	public function testHashing( $shouldMatch, $hash, $password ) {
		$passwordObj = $this->passwordFactory->newFromCiphertext( $hash );
		$this->assertSame( $shouldMatch, $passwordObj->verify( $password ) );
	}

	/**
	 * @dataProvider providePasswordTests
	 */
	public function testStringSerialization( $shouldMatch, $hash, $password ) {
		$hashObj = $this->passwordFactory->newFromCiphertext( $hash );
		$serialized = $hashObj->toString();
		$unserialized = $this->passwordFactory->newFromCiphertext( $serialized );
		$this->assertEquals( $hashObj->toString(), $unserialized->toString() );
	}

	/**
	 * @dataProvider providePasswordTests
	 * @covers InvalidPassword
	 */
	public function testInvalidUnequalNormal( $shouldMatch, $hash, $password ) {
		$invalid = $this->passwordFactory->newFromCiphertext( null );
		$normal = $this->passwordFactory->newFromCiphertext( $hash );

		$this->assertFalse( $invalid->equals( $normal ) );
		$this->assertFalse( $normal->equals( $invalid ) );
		$this->assertFalse( $invalid->verify( $hash ) );
	}

	protected function getValidTypes() {
		return array_keys( $this->getTypeConfigs() );
	}

	public function provideTypes( $type ) {
		$params = [];
		foreach ( $this->getValidTypes() as $type ) {
			$params[] = [ $type ];
		}
		return $params;
	}

	/**
	 * @dataProvider provideTypes
	 */
	public function testCrypt( $type ) {
		$fromType = $this->passwordFactory->newFromType( $type );
		$fromType->crypt( 'password' );
		$fromPlaintext = $this->passwordFactory->newFromPlaintext( 'password', $fromType );
		$this->assertTrue( $fromType->verify( 'password' ) );
		$this->assertTrue( $fromPlaintext->verify( 'password' ) );
		$this->assertFalse( $fromType->verify( 'different password' ) );
		$this->assertFalse( $fromPlaintext->verify( 'different password' ) );
		$this->assertEquals( get_class( $fromType ),
			get_class( $fromPlaintext ),
			'newFromPlaintext() should produce instance of the same class as newFromType()'
		);
	}
}
