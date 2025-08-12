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
 */

namespace MediaWiki\Auth;

use MediaWiki\Language\RawMessage;
use MediaWiki\MainConfigNames;
use MediaWiki\MediaWikiServices;
use MediaWiki\Password\PasswordFactory;

/**
 * This represents the intention to set a temporary password for the user.
 *
 * @stable to extend
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

	/**
	 * @inheritDoc
	 * @stable to override
	 */
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
	 * @stable to call
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
		$minLength = 0;
		$policy = $config->get( MainConfigNames::PasswordPolicy );
		foreach ( $policy['policies'] as $p ) {
			foreach ( [ 'MinimalPasswordLength', 'MinimumPasswordLengthToLogin' ] as $check ) {
				$minLength = max( $minLength, $p[$check]['value'] ?? $p[$check] ?? 0 );
			}
		}

		$password = PasswordFactory::generateRandomPasswordString( $minLength );

		return new self( $password );
	}

	/**
	 * Return an instance with an invalid password
	 * @return TemporaryPasswordAuthenticationRequest
	 */
	public static function newInvalid() {
		return new self( null );
	}

	/**
	 * @inheritDoc
	 * @stable to override
	 */
	public function describeCredentials() {
		return [
			'provider' => wfMessage( 'authmanager-provider-temporarypassword' ),
			'account' => new RawMessage( '$1', [ $this->username ] ),
		] + parent::describeCredentials();
	}

}
