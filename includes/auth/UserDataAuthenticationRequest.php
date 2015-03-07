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
 * This represents additional user data requested on the account creation form
 *
 * AuthManager::getAuthenticationRequestTypes() won't return this type, but it
 * may be passed to AuthManager::beginAccountCreation() anyway.
 *
 * @ingroup Auth
 * @since 1.26
 */
class UserDataAuthenticationRequest extends AuthenticationRequest {
	/** @var string|null Email address */
	public $email;

	/** @var string|null Real name */
	public $realname;

	/**
	 * @return array
	 */
	public static function getFieldInfo() {
		$config = ConfigFactory::getDefaultInstance()->makeConfig( 'main' );
		$ret = array(
			'email' => array(
				'type' => 'string',
				'label' => wfMessage( 'authmanager-email-label' ),
				'help' => wfMessage( 'authmanager-email-help' ),
				'optional' => true,
			),
			'realname' => array(
				'type' => 'string',
				'label' => wfMessage( 'authmanager-realname-label' ),
				'help' => wfMessage( 'authmanager-realname-help' ),
				'optional' => true,
			),
		);

		if ( in_array( 'realname', $config->get( 'HiddenPrefs' ) ) ) {
			unset( $ret['realname'] );
		}

		return $ret;
	}

	/**
	 * Add data to the User object
	 * @param User $user User being created (not added to the database yes).
	 *   This may become a "UserValue" in the future, or User may be refactored
	 *   into such.
	 */
	public function populateUser( $user ) {
		if ( $this->email !== null && $this->email !== '' ) {
			$user->setEmail( $this->email );
		}
		if ( $this->realname !== null && $this->realname !== '' ) {
			$user->setRealName( $this->realname );
		}
	}

}
