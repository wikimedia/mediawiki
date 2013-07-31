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
class PasswordLayerTest extends MediaWikiTestCase {

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

	/**
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
