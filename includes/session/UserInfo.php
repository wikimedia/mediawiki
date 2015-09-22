<?php
/**
 * MediaWiki session user info
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
 * @ingroup Session
 */

namespace MediaWiki\Session;
use User;

/**
 * Value object holding data about a session's user
 *
 * @ingroup Session
 * @since 1.27
 */
final class UserInfo {
	private $anon = true;
	private $authenticated = false;

	/** @var User|null */
	private $user = null;

	private function __construct( User $user = null, $authenticated ) {
		$this->anon = $user === null;
		$this->authenticated = $authenticated;
		$this->user = $user;
	}

	/**
	 * Create an instance for an anonymous (i.e. not logged in) user
	 *
	 * Logged-out users are always "authenticated".
	 *
	 * @return UserInfo
	 */
	public static function newAnonymous() {
		return new self( null, true );
	}

	/**
	 * Create an instance for a logged-in user by ID
	 * @param int $id User ID
	 * @param bool $authenticated True if the user is already authenticated
	 * @return UserInfo
	 */
	public static function newFromId( $id, $authenticated = false ) {
		$user = User::newFromId( $id );

		// Ensure the ID actually exists
		$user->load();
		if ( $user->isAnon() ) {
			throw new \InvalidArgumentException( 'Invalid ID' );
		}

		return new self( $user, $authenticated );
	}

	/**
	 * Create an instance for a logged-in user by name
	 * @param string $name User name
	 * @param bool $authenticated True if the user is already authenticated
	 * @return UserInfo
	 */
	public static function newFromName( $name, $authenticated = false ) {
		$user = User::newFromName( $name, 'usable' );
		if ( !$user ) {
			throw new \InvalidArgumentException( 'Invalid user name' );
		}
		return new self( $user, $authenticated );
	}

	/**
	 * Create an instance from an existing User object
	 * @param User $user
	 * @param bool $authenticated True if the user is already authenticated
	 * @return UserInfo
	 */
	public static function newFromUser( User $user, $authenticated = false ) {
		return new self( $user, $authenticated );
	}

	/**
	 * Return whether this is an anonymous user
	 * @return bool
	 */
	public function isAnon() {
		return $this->anon;
	}

	/**
	 * Return whether this represents an authenticated user
	 * @return bool
	 */
	public function isAuthenticated() {
		return $this->authenticated;
	}

	/**
	 * Return the user ID
	 * @return int
	 */
	public function getId() {
		return $this->anon ? 0 : $this->user->getId();
	}

	/**
	 * Return the user name
	 * @return string|null
	 */
	public function getName() {
		return $this->anon ? null : $this->user->getName();
	}

	/**
	 * Return the user token
	 * @return string|null
	 */
	public function getToken() {
		return $this->anon || $this->user->getId() === 0 ? null : $this->user->getToken( true );
	}

	/**
	 * Return the user
	 * @return User
	 */
	public function getUser() {
		return $this->anon ? new User : $this->user;
	}

	/**
	 * Return an authenticated version of this object
	 * @return UserInfo
	 */
	public function authenticated() {
		return $this->authenticated ? $this : new self( $this->user, true );
	}

	public function __toString() {
		if ( $this->anon ) {
			return '<anon>';
		}
		return '<' .
			( $this->authenticated ? '+' : '-' ) . ':' .
			$this->getId() . ':' . $this->getName() .
			'>';
	}

}
