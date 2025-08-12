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

/**
 * This is a value object for authentication requests with a username and password
 *
 * @stable to extend
 * @ingroup Auth
 * @since 1.27
 */
class PasswordAuthenticationRequest extends AuthenticationRequest {
	/** @var string|null */
	public $password = null;

	/** @var string|null Password, again */
	public $retype = null;

	/**
	 * @inheritDoc
	 * @stable to override
	 */
	public function getFieldInfo() {
		if ( $this->action === AuthManager::ACTION_REMOVE ) {
			return [];
		}

		// for password change it's nice to make extra clear that we are asking for the new password
		$forNewPassword = $this->action === AuthManager::ACTION_CHANGE;
		$passwordLabel = $forNewPassword ? 'newpassword' : 'userlogin-yourpassword';
		$retypeLabel = $forNewPassword ? 'retypenew' : 'yourpasswordagain';

		$ret = [
			'username' => [
				'type' => 'string',
				'label' => wfMessage( 'userlogin-yourname' ),
				'help' => wfMessage( 'authmanager-username-help' ),
			],
			'password' => [
				'type' => 'password',
				'label' => wfMessage( $passwordLabel ),
				'help' => wfMessage( 'authmanager-password-help' ),
				'sensitive' => true,
			],
		];

		switch ( $this->action ) {
			case AuthManager::ACTION_CHANGE:
			case AuthManager::ACTION_REMOVE:
				unset( $ret['username'] );
				break;
		}

		if ( $this->action !== AuthManager::ACTION_LOGIN ) {
			$ret['retype'] = [
				'type' => 'password',
				'label' => wfMessage( $retypeLabel ),
				'help' => wfMessage( 'authmanager-retype-help' ),
				'sensitive' => true,
			];
		}

		return $ret;
	}

	/**
	 * @inheritDoc
	 * @stable to override
	 */
	public function describeCredentials() {
		return [
			'provider' => wfMessage( 'authmanager-provider-password' ),
			'account' => new RawMessage( '$1', [ $this->username ] ),
		];
	}
}
