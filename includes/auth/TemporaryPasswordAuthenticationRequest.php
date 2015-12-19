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

namespace MediaWiki\Auth;

/**
 * This represents the intention to set a temporary password for the user.
 * @ingroup Auth
 * @since 1.27
 */
class TemporaryPasswordAuthenticationRequest extends AuthenticationRequest {
	/** @var string|null Temporary password */
	public $password;

	public function getFieldInfo() {
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
		$config = \ConfigFactory::getDefaultInstance()->makeConfig( 'main' );

		// get the min password length
		$minLength = $config->get( 'MinimalPasswordLength' );
		$policy = $config->get( 'PasswordPolicy' );
		foreach ( $policy['policies'] as $p ) {
			if ( isset( $p['MinimalPasswordLength'] ) ) {
				$minLength = max( $minLength, $p['MinimalPasswordLength'] );
			}
			if ( isset( $p['MinimalPasswordLengthToLogin'] ) ) {
				$minLength = max( $minLength, $p['MinimalPasswordLengthToLogin'] );
			}
		}

		$password = \PasswordFactory::generateRandomPasswordString( $minLength );

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
