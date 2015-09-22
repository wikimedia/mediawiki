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
	const TYPE_LOGIN = 'login';
	const TYPE_CREATE = 'create';
	const TYPE_CHANGE = 'change';

	/** @var string */
	protected $type;

	/** @var string Password */
	public $password = null;

	/** @var string Password, again */
	public $retype = null;

	/**
	 * @param string $type One of:
	 *   - 'login': (default) Used for logging in with an existing password.
	 *   - 'create': Used for creating a new password (typically for a new account).
	 *   - 'change': Used for changing an existing password.
	 *   This will influence what labels are shown, and whether there is a password retype field.
	 */
	public function __construct( $type = 'login' ) {
		if ( !in_array( $type, [ self::TYPE_LOGIN, self::TYPE_CREATE, self::TYPE_CHANGE ], true ) ) {
			throw new \InvalidArgumentException( 'Invalid type: ' . $type );
		}
		$this->type = $type;
	}

	public function getFieldInfo() {
		// for password change it's nice to make extra clear that we are asking for the new password
		$passwordLabel = $this->type === self::TYPE_CHANGE ? 'newpassword' : 'userlogin-yourpassword';
		$retypeLabel = $this->type === self::TYPE_CHANGE ? 'retypenew' : 'yourpasswordagain';

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
			],
		];

		switch ( $this->action ) {
			case AuthManager::ACTION_LINK:
			case AuthManager::ACTION_CHANGE:
			case AuthManager::ACTION_REMOVE:
				unset( $ret['username'] );
				break;
		}

		if ( $this->type !== 'login' ) {
			$ret['retype'] = [
				'type' => 'password',
				'label' => wfMessage( $retypeLabel ),
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
