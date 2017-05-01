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

use MediaWiki\MediaWikiServices;

/**
 * This represents the intention to set a temporary password for the user.
 * @ingroup Auth
 * @since 1.27
 */
class TemporaryPasswordAuthenticationRequest extends AuthenticationRequest {
	/** @var string|null Temporary password */
	public $password;

	/** @var bool Email password to the user. */
	public $mailpassword = false;

	/** @var string Username or IP address of the caller */
	public $caller;

	public function getFieldInfo() {
		return [
			'mailpassword' => [
				'type' => 'checkbox',
				'label' => wfMessage( 'createaccountmail' ),
				'help' => wfMessage( 'createaccountmail-help' ),
			],
		];
	}

	/**
	 * @param string|null $password
	 */
	public function __construct( $password = null ) {
		$this->password = $password;
		if ( $password ) {
			$this->mailpassword = true;
		}
	}

	/**
	 * Return an instance with a new, random password
	 * @return TemporaryPasswordAuthenticationRequest
	 */
	public static function newRandom() {
		$config = MediaWikiServices::getInstance()->getMainConfig();

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
		$request = new self( null );
		return $request;
	}

	public function describeCredentials() {
		return [
			'provider' => wfMessage( 'authmanager-provider-temporarypassword' ),
			'account' => new \RawMessage( '$1', [ $this->username ] ),
		] + parent::describeCredentials();
	}

}
