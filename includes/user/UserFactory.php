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
use User;

/**
 * Creates User objects.
 *
 * For now, there is nothing interesting in this class. It is meant for preventing static User
 * methods causing problems in unit tests.
 *
 * @since 1.35
 */
class UserFactory implements IDBAccessObject {

	/**
	 * @see User::newFromName
	 * @param string $name
	 * @param string $validate
	 * @return User|bool
	 */
	public function newFromName( string $name, string $validate = 'valid' ) {
		return User::newFromName( $name, $validate );
	}

	/**
	 * @see User::newFromId
	 * @param int $id
	 * @return User
	 */
	public function newFromId( int $id ) : User {
		return User::newFromId( $id );
	}

	/**
	 * @see User::newFromActorId
	 * @param int $actorId
	 * @return User
	 */
	public function newFromActorId( int $actorId ) : User {
		return User::newFromActorId( $actorId );
	}

	/**
	 * @see User::newFromIdentity
	 * @param UserIdentity $userIdentity
	 * @return User
	 */
	public function newFromUserIdentity( UserIdentity $userIdentity ) : User {
		return User::newFromIdentity( $userIdentity );
	}

	/**
	 * @see User::newFromAnyId
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

}
