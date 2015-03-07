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
 * @ingroup Auth
 */

/**
 * This represents the intention to set a temporary password for the user.
 * @ingroup Auth
 * @since 1.26
 */
class TemporaryPasswordAuthenticationRequest extends AuthenticationRequest {
	/** @var string|null Temporary password */
	public $password;

	/**
	 * @return array
	 */
	public static function getFieldInfo() {
		return array();
	}

	/**
	 * @param string|null $password
	 */
	public function __construct( $password = null ) {
		$this->password = $password;
	}

	/**
	 * Return an instance with a new, random password
	 * @return TemporaryPasswordAuthenticationRequest
	 */
	public static function newRandom() {
		$config = ConfigFactory::getDefaultInstance()->makeConfig( 'main' );

		// Decide the final password length based on our min password length,
		// stopping at a minimum of 10 chars.
		$length = max( 10, $config->get( 'MinimalPasswordLength' ) );
		$policy = $config->get( 'PasswordPolicy' );
		foreach ( $policy['policies'] as $p ) {
			if ( isset( $p['MinimalPasswordLength'] ) ) {
				$length = max( $length, $p['MinimalPasswordLength'] );
			}
			if ( isset( $p['MinimalPasswordLengthToLogin'] ) ) {
				$length = max( $length, $p['MinimalPasswordLengthToLogin'] );
			}
		}

		// Multiply by 1.25 to get the number of hex characters we need
		$length = $length * 1.25;
		// Generate random hex chars
		$hex = MWCryptRand::generateHex( $length );
		// Convert from base 16 to base 32 to get a proper password like string
		$password = wfBaseConvert( $hex, 16, 32 );

		return new self( $password );
	}

	/**
	 * Return an instance with an invalid password
	 * @return TemporaryPasswordAuthenticationRequest
	 */
	public static function newInvalid() {
		return new self( null );
	}
}
