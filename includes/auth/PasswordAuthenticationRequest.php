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
 * This is a value object for authentication requests with a username and password
 * @ingroup Auth
 * @since 1.27
 */
class PasswordAuthenticationRequest extends AuthenticationRequest {
	protected $needsRetype = false;
	protected $isNew = false;

	/** @var string Password */
	public $password = null;

	/** @var string Password, again */
	public $retype = null;

	/**
	 * @param bool $needsRetype Whether to include a "retype" field
	 * @param bool $isNew Whether to clarify in the labels that this is a new password
	 */
	public function __construct( $needsRetype = false, $isNew = false ) {
		$this->needsRetype = $needsRetype;
		$this->isNew = $isNew;
	}

	public function getFieldInfo() {
		$ret = [
			'username' => [
				'type' => 'string',
				'label' => wfMessage( 'userlogin-yourname' ),
				'help' => wfMessage( 'authmanager-username-help' ),
			],
			'password' => [
				'type' => 'password',
				'label' => wfMessage( $this->isNew ? 'newpassword' : 'userlogin-yourpassword' ),
				'help' => wfMessage( 'authmanager-password-help' ),
			],
		];

		switch ( $this->action ) {
			case AuthManager::ACTION_LINK:
			case AuthManager::ACTION_CHANGE:
			case AuthManager::ACTION_REMOVE:
				unset( $ret['username'] );
				break;
		}

		if ( $this->needsRetype ) {
			$ret['retype'] = [
				'type' => 'password',
				'label' => wfMessage( $this->isNew ? 'retypenew' : 'yourpasswordagain' ),
				'help' => wfMessage( 'authmanager-retype-help' ),
			];
		}

		return $ret;
	}

	public function describeCredentials() {
		return [
			'provider' => wfMessage( 'authmanager-provider-password' ),
			'account' => new \RawMessage( '$1', [ $this->username ] ),
		];
	}
}
