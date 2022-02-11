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

use DBAccessObjectUtils;
use IDBAccessObject;
use InvalidArgumentException;
use MediaWiki\Permissions\Authority;
use stdClass;
use User;
use Wikimedia\Rdbms\ILoadBalancer;

/**
 * Creates User objects.
 *
 * @since 1.35
 */
class UserFactory implements IDBAccessObject, UserRigorOptions {

	/**
	 * RIGOR_* constants are inherited from UserRigorOptions
	 * READ_* constants are inherited from IDBAccessObject
	 */

	/** @var ILoadBalancer */
	private $loadBalancer;

	/** @var UserNameUtils */
	private $userNameUtils;

	/** @var User|null */
	private $lastUserFromIdentity = null;

	/**
	 * @param ILoadBalancer $loadBalancer
	 * @param UserNameUtils $userNameUtils
	 */
	public function __construct(
		ILoadBalancer $loadBalancer,
		UserNameUtils $userNameUtils
	) {
		$this->loadBalancer = $loadBalancer;
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
	): ?User {
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
	public function newAnonymous( ?string $ip = null ): User {
		if ( $ip ) {
			if ( !$this->userNameUtils->isIP( $ip ) ) {
				throw new InvalidArgumentException( 'Invalid IP address' );
			}
			$user = $this->newFromName( $ip, self::RIGOR_NONE );
		} else {
			$user = new User();
		}
		return $user;
	}

	/**
	 * Factory method for creation from a given user ID, replacing User::newFromId
	 *
	 * @since 1.35
	 *
	 * @param int $id Valid user ID
	 * @return User
	 */
	public function newFromId( int $id ): User {
		$user = new User();
		$user->mId = $id;
		$user->mFrom = 'id';
		$user->setItemLoaded( 'id' );
		return $user;
	}

	/**
	 * Factory method for creation from a given actor ID, replacing User::newFromActorId
	 *
	 * @since 1.35
	 *
	 * @param int $actorId
	 * @return User
	 */
	public function newFromActorId( int $actorId ): User {
		$user = new User();
		$user->mActorId = $actorId;
		$user->mFrom = 'actor';
		$user->setItemLoaded( 'actor' );
		return $user;
	}

	/**
	 * Factory method for creation fom a given UserIdentity, replacing User::newFromIdentity
	 *
	 * @since 1.35
	 *
	 * @param UserIdentity $userIdentity
	 * @return User
	 */
	public function newFromUserIdentity( UserIdentity $userIdentity ): User {
		if ( $userIdentity instanceof User ) {
			return $userIdentity;
		}

		$id = $userIdentity->getId();
		$name = $userIdentity->getName();
		// Cache the $userIdentity we converted last. This avoids redundant conversion
		// in cases where we would be converting the same UserIdentity over and over,
		// for instance because we need to access data preferences when formatting
		// timestamps in a listing.
		if (
			$this->lastUserFromIdentity
			&& $this->lastUserFromIdentity->getId() === $id
			&& $this->lastUserFromIdentity->getName() === $name
		) {
			return $this->lastUserFromIdentity;
		}

		$this->lastUserFromIdentity = $this->newFromAnyId(
			$id === 0 ? null : $id,
			$name === '' ? null : $name,
			null
		);

		return $this->lastUserFromIdentity;
	}

	/**
	 * Factory method for creation from an ID, name, and/or actor ID, replacing User::newFromAnyId
	 *
	 * @note This does not check that the ID, name, and actor ID all correspond to
	 * the same user.
	 *
	 * @since 1.35
	 *
	 * @param ?int $userId
	 * @param ?string $userName
	 * @param ?int $actorId
	 * @param bool|string $dbDomain
	 * @return User
	 * @throws InvalidArgumentException if none of userId, userName, and actorId are specified
	 */
	public function newFromAnyId(
		?int $userId,
		?string $userName,
		?int $actorId = null,
		$dbDomain = false
	): User {
		// Stop-gap solution for the problem described in T222212.
		// Force the User ID and Actor ID to zero for users loaded from the database
		// of another wiki, to prevent subtle data corruption and confusing failure modes.
		if ( $dbDomain !== false ) {
			$userId = 0;
			$actorId = 0;
		}

		$user = new User;
		$user->mFrom = 'defaults';

		if ( $actorId !== null ) {
			$user->mActorId = $actorId;
			if ( $actorId !== 0 ) {
				$user->mFrom = 'actor';
			}
			$user->setItemLoaded( 'actor' );
		}

		if ( $userName !== null && $userName !== '' ) {
			$user->mName = $userName;
			$user->mFrom = 'name';
			$user->setItemLoaded( 'name' );
		}

		if ( $userId !== null ) {
			$user->mId = $userId;
			if ( $userId !== 0 ) {
				$user->mFrom = 'id';
			}
			$user->setItemLoaded( 'id' );
		}

		if ( $user->mFrom === 'defaults' ) {
			throw new InvalidArgumentException(
				'Cannot create a user with no name, no ID, and no actor ID'
			);
		}

		return $user;
	}

	/**
	 * Factory method to fetch the user for a given email confirmation code, replacing User::newFromConfirmationCode
	 *
	 * This code is generated when an account is created or its e-mail address has changed.
	 * If the code is invalid or has expired, returns null.
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
		list( $index, $options ) = DBAccessObjectUtils::getDBOptions( $flags );

		$db = $this->loadBalancer->getConnectionRef( $index );

		$id = $db->selectField(
			'user',
			'user_id',
			[
				'user_email_token' => md5( $confirmationCode ),
				'user_email_token_expires > ' . $db->addQuotes( $db->timestamp() ),
			],
			__METHOD__,
			$options
		);

		if ( !$id ) {
			return null;
		}

		return $this->newFromId( (int)$id );
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

	/**
	 * @internal for transition from User to Authority as performer concept.
	 * @param Authority $authority
	 * @return User
	 */
	public function newFromAuthority( Authority $authority ): User {
		if ( $authority instanceof User ) {
			return $authority;
		}
		return $this->newFromUserIdentity( $authority->getUser() );
	}

}
