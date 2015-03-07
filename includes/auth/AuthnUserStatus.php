<?php
/**
 * Authentication status of a user
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
 * @ingroup Auth
 */

/**
 * This value object indicates the status of a user in the PrimaryAuthenticationProviders.
 * @ingroup Auth
 * @since 1.26
 */
class AuthnUserStatus {
	private static $defaultAuthnUserStatus = null;
	private static $existsAuthnUserStatus = null;

	private $exists = false;
	private $locked = false;
	private $hidden = false;

	/**
	 * @param array $statuses Associative array, keys match accessors
	 */
	public function __construct( array $statuses ) {
		$this->exists = !empty( $statuses['exists'] );
		$this->locked = !empty( $statuses['locked'] );
		$this->hidden = !empty( $statuses['hidden'] );
	}

	/**
	 * Returns a status with all fields as defaults
	 * @return AuthnUserStatus
	 */
	public static function newDefaultStatus() {
		if ( self::$defaultAuthnUserStatus === null ) {
			self::$defaultAuthnUserStatus = new self( array() );
		}
		return self::$defaultAuthnUserStatus;
	}

	/**
	 * Returns a status with exists true and all other fields as default
	 * @return AuthnUserStatus
	 */
	public static function newExistsStatus() {
		if ( self::$existsAuthnUserStatus === null ) {
			self::$existsAuthnUserStatus = new self( array( 'exists' => true ) );
		}
		return self::$existsAuthnUserStatus;
	}

	/**
	 * Returns a status that is the combination of two statuses
	 * @param AuthnUserStatus $a
	 * @param AuthnUserStatus $b
	 * @return AuthnUserStatus
	 */
	public static function newCombinedStatus( AuthnUserStatus $a, AuthnUserStatus $b ) {
		return new AuthnUserStatus( array(
			'exists' => $a->exists || $b->exists,
			'locked' => $a->locked || $b->locked,
			'hidden' => $a->hidden || $b->hidden,
		) );
	}

	/**
	 * Whether the user exists in any PrimaryAuthenticationProvider
	 * @return bool
	 */
	public function exists() {
		return $this->exists;
	}

	/**
	 * Whether the user is locked in any PrimaryAuthenticationProvider
	 * @return bool
	 */
	public function locked() {
		return $this->locked;
	}

	/**
	 * Whether any PrimaryAuthenticationProvider considers this user hidden
	 *
	 * "Hidden" means that the UI should avoid displaying this user's name.
	 *
	 * @return bool
	 */
	public function hidden() {
		return $this->hidden;
	}

}
