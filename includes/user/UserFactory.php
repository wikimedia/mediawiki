<?php
/**
 * Factory for creating User objects without static coupling.
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
 */

namespace MediaWiki\User;

use IDBAccessObject;
use stdClass;
use User;

/**
 * Creates User objects.
 *
 * For now, there is nothing much interesting in this class. It was meant for preventing static User
 * methods causing problems in unit tests.
 *
 * @since 1.35
 */
class UserFactory implements IDBAccessObject, UserRigorOptions {

	/**
	 * RIGOR_* constants are inherited from UserRigorOptions
	 */

	/**
	 * @var UserNameUtils
	 */
	private $userNameUtils;

	/**
	 * @param UserNameUtils $userNameUtils
	 */
	public function __construct( UserNameUtils $userNameUtils ) {
		$this->userNameUtils = $userNameUtils;
	}

	/**
	 * Factory method for creating users by name, replacing static User::newFromName
	 *
	 * This is slightly less efficient than newFromId(), so use newFromId() if
	 * you have both an ID and a name handy.
	 *
	 * @note unlike User::newFromName, this returns null instead of false for invalid usernames
	 *
	 * @since 1.35
	 * @since 1.36 returns null instead of false for invalid user names
	 *
	 * @param string $name Username, validated by Title::newFromText
	 * @param string $validate Validation strategy, one of the RIGOR_* constants. For no
	 *    validation, use RIGOR_NONE.
	 * @return ?User User object, or null if the username is invalid (e.g. if it contains
	 *  illegal characters or is an IP address). If the username is not present in the database,
	 *  the result will be a user object with a name, a user id of 0, and default settings.
	 */
	public function newFromName(
		string $name,
		string $validate = self::RIGOR_VALID
	) : ?User {
		// RIGOR_* constants are the same here and in the UserNameUtils class
		$canonicalName = $this->userNameUtils->getCanonical( $name, $validate );
		if ( $canonicalName === false ) {
			return null;
		}

		$user = new User();
		$user->mName = $canonicalName;
		$user->mFrom = 'name';
		$user->setItemLoaded( 'name' );
		return $user;
	}

	/**
	 * Returns a new anonymous User based on ip.
	 *
	 * @since 1.35
	 *
	 * @param string|null $ip IP address
	 * @return User
	 */
	public function newAnonymous( $ip = null ) : User {
		if ( $ip ) {
			$validIp = $this->userNameUtils->isIP( $ip );
			if ( $validIp ) {
				$user = $this->newFromName( $ip, self::RIGOR_NONE );
			} else {
				throw new \InvalidArgumentException( 'Invalid IP address' );
			}
		} else {
			$user = new User();
		}
		return $user;
	}

	/**
	 * @see User::newFromId
	 *
	 * @since 1.35
	 *
	 * @param int $id
	 * @return User
	 */
	public function newFromId( int $id ) : User {
		return User::newFromId( $id );
	}

	/**
	 * @see User::newFromActorId
	 *
	 * @since 1.35
	 *
	 * @param int $actorId
	 * @return User
	 */
	public function newFromActorId( int $actorId ) : User {
		return User::newFromActorId( $actorId );
	}

	/**
	 * @see User::newFromIdentity
	 *
	 * @since 1.35
	 *
	 * @param UserIdentity $userIdentity
	 * @return User
	 */
	public function newFromUserIdentity( UserIdentity $userIdentity ) : User {
		if ( $userIdentity instanceof User ) {
			return $userIdentity;
		}

		return $this->newFromAnyId(
			$userIdentity->getId() === 0 ? null : $userIdentity->getId(),
			$userIdentity->getName() === '' ? null : $userIdentity->getName(),
			$userIdentity->getActorId() === 0 ? null : $userIdentity->getActorId()
		);
	}

	/**
	 * @see User::newFromAnyId
	 *
	 * @since 1.35
	 *
	 * @param ?int $userId
	 * @param ?string $userName
	 * @param ?int $actorId
	 * @param bool|string $dbDomain
	 * @return User
	 */
	public function newFromAnyId(
		?int $userId,
		?string $userName,
		?int $actorId,
		$dbDomain = false
	) : User {
		return User::newFromAnyId( $userId, $userName, $actorId, $dbDomain );
	}

	/**
	 * @see User::newFromConfirmationCode
	 *
	 * @since 1.35
	 *
	 * @param string $confirmationCode
	 * @param int $flags
	 * @return User|null
	 */
	public function newFromConfirmationCode(
		string $confirmationCode,
		int $flags = self::READ_NORMAL
	) {
		return User::newFromConfirmationCode( $confirmationCode, $flags );
	}

	/**
	 * @see User::newFromRow
	 *
	 * @since 1.36
	 *
	 * @param stdClass $row A row from the user table
	 * @param array|null $data Further data to load into the object
	 * @return User
	 */
	public function newFromRow( $row, $data = null ) {
		return User::newFromRow( $row, $data );
	}

}
