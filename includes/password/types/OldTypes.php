<?php
/**
 * Our old password types.
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
 * @author Daniel Friesen <mediawiki@danielfriesen.name>
 * @license http://www.gnu.org/copyleft/gpl.html GNU General Public License
 * @ingroup Password
 */

/**
 * Type :A:, our oldest non-salted password type.
 * Simply md5s the password.
 * @ingroup Password
 * @since 1.20
 */
class Password_TypeA extends BasePasswordType {

	public function run( $params, $password ) {
		self::params( $params, 0 );
		return md5( $password );
	}

	public function cryptParams() {
		return array();
	}

	public function knownPasswordData() {
		return array(
			array( '912ec803b2ce49e4a541068d495ab570', 'asdf' ),
			array( '098f6bcd4621d373cade4e832627b4f6', 'test' ),
			array( 'b10a8db164e0754105b7a99be72e3fe5', 'Hello World' ),
			array( 'd41e98d1eafa6d6011d3a70f1a5b92f0', 'Passw0rd' ),
			array( 'ace0aab238adb911d27db0f767dda13e', 'D0g.....................' ),
			array( '19bf4669ce80fe42c09fc68ceb6fc75d', 'KiWVic0F6Le&%Ejn8p3j1vm@#XQclWOV' ),
		);
	}

}

/**
 * Type :B:, our first salted password type.
 * md5s a combination of a 32bit salt a '-' separator and
 * the md5 of the password.
 * @ingroup Password
 * @since 1.20
 */
class Password_TypeB extends BasePasswordType {

	public function run( $params, $password ) {
		list( $salt ) = self::params( $params, 1 );
		return md5( $salt . '-' . md5( $password ) );
	}

	public function cryptParams() {
		$salt = MWCryptRand::generateHex( 8 );
		return array( $salt );
	}

	public function knownPasswordData() {
		return array(
			# (3 sets with different salts)
			## Set 1
			array( '0549900c:8490f5e1c4283c1986a8a59b287d74dc', 'asdf' ),
			array( 'fa2630c2:469d0174c83ad114235dd50379bfd61a', 'test' ),
			array( '7ed91bde:3a29f005342c15f880843c575cdadab6', 'Hello World' ),
			array( '4392b6ac:9097931dbcffb3db2601fb73fb4fd969', 'Passw0rd' ),
			array( 'ca4a0984:36a2ee13001b274f573b2a8b4437398a', 'D0g.....................' ),
			array( 'ca0421e9:62b8224db9b3bd81b2293c97fb45ee15', 'KiWVic0F6Le&%Ejn8p3j1vm@#XQclWOV' ),
			## Set 2
			array( 'b6bfaddc:8335b0e24a382a69afd58bc08a8e8902', 'asdf' ),
			array( 'ddcc27bb:b59259f80a5354b3965b94e3caf88501', 'test' ),
			array( 'de7c3335:d5f0e74389e9ccc616853aad681a6c88', 'Hello World' ),
			array( 'd93f4959:05abd0d0fcc4713a22a4b43a1a831207', 'Passw0rd' ),
			array( '3156ea98:9d568e1ea7580bf299e4f09aba27477d', 'D0g.....................' ),
			array( 'b615768d:4b3ced67aaedd8917442ab258e40ec2e', 'KiWVic0F6Le&%Ejn8p3j1vm@#XQclWOV' ),
			## Set 3
			array( '6a2143cb:4d8a02f799cf64016dbd212e94e25bfb', 'asdf' ),
			array( 'd6c6c08d:03b4545eca23d05e26ffffa0cc833cf8', 'test' ),
			array( '2bcca772:b845721cdaaecaf6095ba5d5ad686b86', 'Hello World' ),
			array( '018cba3a:fadfb2f1b012d692969666335f8b9779', 'Passw0rd' ),
			array( 'e4408eef:55df39e98f4bad1485a38be9bc19559f', 'D0g.....................' ),
			array( 'ebf3811c:a7d1df3cdd22b972a104b03c47679c3b', 'KiWVic0F6Le&%Ejn8p3j1vm@#XQclWOV' ),
		);
	}

}
