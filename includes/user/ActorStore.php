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

namespace MediaWiki\User;

use CannotCreateActorException;
use DBAccessObjectUtils;
use InvalidArgumentException;
use MediaWiki\DAO\WikiAwareEntity;
use Psr\Log\LoggerInterface;
use stdClass;
use User;
use Wikimedia\Assert\Assert;
use Wikimedia\IPUtils;
use Wikimedia\Rdbms\IDatabase;
use Wikimedia\Rdbms\ILoadBalancer;

/**
 * Service for interacting with the actor table.
 *
 * @package MediaWiki\User
 * @since 1.36
 */
class ActorStore implements UserIdentityLookup, ActorNormalization {

	/** @var ILoadBalancer */
	private $loadBalancer;

	/** @var UserNameUtils */
	private $userNameUtils;

	/** @var LoggerInterface */
	private $logger;

	/** @var string|false */
	private $wikiId;

	/**
	 * @param ILoadBalancer $loadBalancer
	 * @param UserNameUtils $userNameUtils
	 * @param LoggerInterface $logger
	 * @param string|false $wikiId
	 */
	public function __construct(
		ILoadBalancer $loadBalancer,
		UserNameUtils $userNameUtils,
		LoggerInterface $logger,
		$wikiId = WikiAwareEntity::LOCAL
	) {
		Assert::parameterType( 'string|boolean', $wikiId, '$wikiId' );
		Assert::parameter( $wikiId !== true, '$wikiId', 'must be false or a string' );

		$this->loadBalancer = $loadBalancer;
		$this->userNameUtils = $userNameUtils;
		$this->logger = $logger;
		$this->wikiId = $wikiId;
	}

	/**
	 * Instantiate a new UserIdentity object based on a $row from the actor table.
	 *
	 * Use this method when an actor row was already fetched from the DB via a join.
	 * This method just constructs a new instance and does not try fetching missing
	 * values from the DB again, use {@link UserIdentityLookup} for that.
	 *
	 * @param stdClass $row with the following fields:
	 *  - int actor_id
	 *  - string actor_name
	 *  - int|null actor_user
	 * @return UserIdentity
	 * @throws InvalidArgumentException
	 */
	public function newActorFromRow( stdClass $row ): UserIdentity {
		$actorId = (int)$row->actor_id;
		$userId = isset( $row->actor_user ) ? (int)$row->actor_user : 0;
		if ( $actorId === 0 ) {
			throw new InvalidArgumentException( "Actor ID is 0 for {$row->actor_name} and {$userId}" );
		}

		$normalizedName = $this->normalizeUserName( $row->actor_name );
		if ( !$normalizedName ) {
			$this->logger->warning( 'Encountered invalid actor name in database', [
				'user_id' => $userId,
				'actor_id' => $actorId,
				'actor_name' => $row->actor_name,
				'wiki_id' => $this->wikiId ?: 'local'
			] );
			// TODO: once we have guaranteed db only contains valid actor names,
			// we can skip normalization here - T273933
			if ( $row->actor_name === '' ) {
				throw new InvalidArgumentException( "Actor name can not be empty for {$userId} and {$actorId}" );
			}
		}
		return new UserIdentityValue( $userId, $row->actor_name, $actorId, $this->wikiId );
	}

	/**
	 * Instantiate a new UserIdentity object based on field values from a DB row.
	 *
	 * Until {@link ActorMigration} is completed, the actor table joins alias actor field names
	 * to legacy field names. This method is convenience to construct the UserIdentity based on
	 * legacy field names. It's more relaxed with typing then ::newFromRow to better support legacy
	 * code, so always prefer ::newFromRow in new code. Eventually, once {@link ActorMigration}
	 * is completed and all queries use explicit join with actor table, this method will be
	 * deprecated and removed.
	 *
	 * @throws InvalidArgumentException
	 * @param int|null $actorId
	 * @param string|null $name
	 * @param int|null $userId
	 * @return UserIdentity
	 */
	public function newActorFromRowFields( $actorId, $name, $userId ): UserIdentity {
		// For backwards compatibility we are quite relaxed about what to accept,
		// but try not to create entirely insane objects. As we move more code
		// from ActorMigration aliases to proper join with the actor table,
		// we should use ::newActorFromRow more, and eventually deprecate this method.
		$userId = $userId === null ? 0 : (int)$userId;
		$name = $name ?: '';
		if ( $actorId === null ) {
			throw new InvalidArgumentException( "Actor ID is null for {$name} and {$userId}" );
		}
		if ( (int)$actorId === 0 ) {
			throw new InvalidArgumentException( "Actor ID is 0 for {$name} and {$userId}" );
		}

		$normalizedName = $this->normalizeUserName( $name );
		if ( !$normalizedName ) {
			$this->logger->warning( 'Encountered invalid actor name in database', [
				'user_id' => $userId,
				'actor_id' => $actorId,
				'actor_name' => $name,
				'wiki_id' => $this->wikiId ?: 'local'
			] );
			// TODO: once we have guaranteed the DB entries only exist for normalized names,
			// we can skip normalization here - T273933
			if ( $name === '' ) {
				throw new InvalidArgumentException( "Actor name can not be empty for {$userId} and {$actorId}" );
			}
		}
		return new UserIdentityValue(
			$userId,
			$name,
			(int)$actorId,
			$this->wikiId
		);
	}

	/**
	 * Find an actor by $id.
	 *
	 * @param int $actorId
	 * @param int $queryFlags one of IDBAccessObject constants
	 * @return UserIdentity|null Returns null if no actor with this $actorId exists in the database.
	 */
	public function getActorById( int $actorId, int $queryFlags = self::READ_NORMAL ): ?UserIdentity {
		if ( !$actorId ) {
			return null;
		}
		return $this->getActorFromConds( [ 'actor_id' => $actorId ], $queryFlags );
	}

	/**
	 * Find an actor by $name
	 *
	 * @param string $name
	 * @param int $queryFlags one of IDBAccessObject constants
	 * @return UserIdentity|null
	 * @throws InvalidArgumentException if non-normalizable actor name is passed.
	 */
	public function getUserIdentityByName( string $name, int $queryFlags = self::READ_NORMAL ): ?UserIdentity {
		if ( $name === '' ) {
			throw new InvalidArgumentException( 'Empty string passed as actor name' );
		}

		$name = $this->normalizeUserName( $name );
		if ( !$name ) {
			throw new InvalidArgumentException( "Unable to normalize the provided actor name {$name}" );
		}
		return $this->getActorFromConds( [ 'actor_name' => $name ], $queryFlags );
	}

	/**
	 * Find an actor by $userId
	 *
	 * @param int $userId
	 * @param int $queryFlags one of IDBAccessObject constants
	 * @return UserIdentity|null
	 */
	public function getUserIdentityByUserId( int $userId, int $queryFlags = self::READ_NORMAL ): ?UserIdentity {
		if ( !$userId ) {
			return null;
		}
		return $this->getActorFromConds( [ 'actor_user' => $userId ], $queryFlags );
	}

	/**
	 * Find actor by $userId or by $name in this order.
	 *
	 * @note calling this method is different from instantiating a new UserIdentity
	 * implementation since the returned actor is guaranteed to exist in the database.
	 *
	 * @param int|null $userId
	 * @param string|null $name
	 * @param int $queryFlags one of IDBAccessObject constants
	 * @return UserIdentity|null
	 */
	public function getUserIdentityByAnyId(
		?int $userId,
		?string $name,
		int $queryFlags = self::READ_NORMAL
	): ?UserIdentity {
		if ( $userId ) {
			$fromUserId = $this->getUserIdentityByUserId( $userId, $queryFlags );
			if ( $fromUserId ) {
				return $fromUserId;
			}
		}
		if ( $name ) {
			$fromName = $this->getUserIdentityByName( $name, $queryFlags );
			if ( $fromName ) {
				return $fromName;
			}
		}
		return null;
	}

	/**
	 * Find the actor_id of the given $user.
	 *
	 * @param UserIdentity $user
	 * @param int $queryFlags
	 * @return int|null
	 */
	public function findActorId( UserIdentity $user, int $queryFlags = self::READ_NORMAL ): ?int {
		// TODO: we want to assert this user belongs to the correct wiki,
		// but User objects are always local and we used to use them
		// on a non-local DB connection. We need to first deprecate this
		// possibility and then throw on mismatching User object - T273972
		// $user->assertWiki( $this->wikiId );
		return $this->findActorIdInternal(
			$user,
			$this->getDBConnectionRefForQueryFlags( $queryFlags )
		);
	}

	/**
	 * Find actor_id of the given $user using the passed $db connection.
	 *
	 * @param UserIdentity $user
	 * @param IDatabase $db
	 * @return int|null
	 */
	private function findActorIdInternal( UserIdentity $user, IDatabase $db ): ?int {
		// Note: UserIdentity::getActorId will be deprecated and removed,
		// and this is the replacement for it. Can't call User::getActorId, cause
		// User always thinks it's local, so we could end up fetching the ID
		// from the wrong database.

		// TODO: In the future we would be able to assume UserIdentity name is ok
		// and will be able to skip normalization here - T273933
		$name = $this->normalizeUserName( $user->getName() );
		if ( !$name ) {
			$this->logger->warning( 'Encountered a UserIdentity with invalid name', [
				'user_name' => $user->getName()
			] );
			return null;
		}

		// TODO: fix FileImporter tests and make this into selectField
		$row = $db->selectRow(
			'actor',
			'actor_id',
			[ 'actor_name' => $name ],
			__METHOD__
		);

		if ( !$row || !$row->actor_id ) {
			return null;
		}

		$id = (int)$row->actor_id;
		// TODO: Cache! Cache! Cache! But while we are not yet caching, let's use
		// existing UserIdentity actor field - T273974
		if ( $user instanceof UserIdentityValue ) {
			$user->setActorId( $id );
		} elseif ( $user instanceof User ) {
			$user->setActorId( $id );
		}
		return $id;
	}

	/**
	 * Attempt to assign an actor ID to the given $user
	 * If it is already assigned, return the existing ID.
	 *
	 * @param UserIdentity $user
	 * @param IDatabase|null $dbw
	 * @return int greater then 0
	 * @throws CannotCreateActorException if no actor ID has been assigned to this $user
	 */
	public function acquireActorId( UserIdentity $user, IDatabase $dbw = null ): int {
		if ( $dbw ) {
			$this->checkDatabaseDomain( $dbw );
		} else {
			$dbw = $this->getDBConnectionRef( DB_MASTER );
		}
		// TODO: we want to assert this user belongs to the correct wiki,
		// but User objects are always local and we used to use them
		// on a non-local DB connection. We need to first deprecate this
		// possibility and then throw on mismatching User object - T273972
		// $user->assertWiki( $this->wikiId );

		$existingActorId = $this->findActorIdInternal( $user, $dbw );
		if ( $existingActorId ) {
			return $existingActorId;
		}

		// TODO: Passing user's own wiki as assertion is a workaround for the fact
		// it's still possible to encounter always LOCAL User instances
		// for non-local DB connection. To be replaced with $this->wikiId - T273972
		$userId = $user->getUserId( $user->getWikiId() ) ?: null;
		if ( $userId === null && $this->userNameUtils->isUsable( $user->getName() ) ) {
			throw new CannotCreateActorException(
				'Cannot create an actor for a usable name that is not an existing user: ' .
				"user_name=\"{$user->getName()}\""
			);
		}

		$userName = $this->normalizeUserName( $user->getName() );
		if ( $userName === null || $userName === '' ) {
			throw new CannotCreateActorException(
				'Cannot create an actor for a user with no name: ' .
				"user_id={$userId} user_name=\"{$user->getName()}\""
			);
		}
		$q = [
			'actor_user' => $userId,
			// make sure to use normalized form of IP for anonymous users
			'actor_name' => $userName,
		];

		$dbw->insert( 'actor', $q, __METHOD__, [ 'IGNORE' ] );
		if ( $dbw->affectedRows() ) {
			$actorId = (int)$dbw->insertId();
		} else {
			// Outdated cache?
			// Use LOCK IN SHARE MODE to bypass any MySQL REPEATABLE-READ snapshot.
			$actorId = (int)$dbw->selectField(
				'actor',
				'actor_id',
				$q,
				__METHOD__,
				[ 'LOCK IN SHARE MODE' ]
			);
			if ( !$actorId ) {
				throw new CannotCreateActorException(
					"Failed to create actor ID for " .
					"user_id={$userId} user_name=\"{$userName}\""
				);
			}
		}

		// TODO: this is extremely ugly, but this is temporary. UserIdentity/User is getting
		// untied from the actor_id, so the getActorId method is being removed. Until that happens,
		// we need to set the actor ID on the user.
		if ( $user instanceof UserIdentityValue ) {
			$user->setActorId( $actorId );
		} elseif ( $user instanceof User ) {
			$user->setActorId( $actorId );
		}

		return $actorId;
	}

	/**
	 * Returns a canonical form of user name suitable for storage.
	 *
	 * @param string $name
	 * @return string|null
	 */
	private function normalizeUserName( string $name ): ?string {
		if ( $this->userNameUtils->isIP( $name ) ) {
			return IPUtils::sanitizeIP( $name );
		} else {
			// TODO: currently we can encounter non-canonicalized user names both
			// in the input (obviously) and in the database. Some codepaths are
			// normalizing the names, some don't (User::createNew for example).
			// We will transition towards having guaranteed normalized user name
			// in UserIdentity and in the database, but at this point we rely on
			// how things used to work before - T273933

			// TODO: ideally, we should probably canonicalize external usernames,
			// but it was not done before, so we can not start doing it unless we
			// fix existing DB rows - T273933
			return $name;
		}
	}

	/**
	 * Queries an actor by $conds
	 *
	 * @param array $conds
	 * @param int $queryFlags
	 * @return UserIdentity|null
	 */
	private function getActorFromConds( array $conds, int $queryFlags = self::READ_NORMAL ): ?UserIdentity {
		$row = $this->getDBConnectionRefForQueryFlags( $queryFlags )
			->selectRow(
				'actor',
				[ 'actor_id', 'actor_name', 'actor_user' ],
				$conds,
				__METHOD__
			);
		if ( $row === false ) {
			return null;
		}
		return $this->newActorFromRow( $row );
	}

	/**
	 * @param int $queryFlags a bit field composed of READ_XXX flags
	 * @return IDatabase
	 */
	private function getDBConnectionRefForQueryFlags( int $queryFlags ): IDatabase {
		list( $mode, ) = DBAccessObjectUtils::getDBOptions( $queryFlags );
		return $this->getDBConnectionRef( $mode );
	}

	/**
	 * @param int $mode DB_MASTER or DB_REPLICA
	 * @return IDatabase
	 */
	private function getDBConnectionRef( int $mode ): IDatabase {
		return $this->loadBalancer->getConnectionRef( $mode, [], $this->wikiId );
	}

	/**
	 * Throws an exception if the given database connection does not belong to the wiki this
	 * RevisionStore is bound to.
	 *
	 * @param IDatabase $db
	 */
	private function checkDatabaseDomain( IDatabase $db ) {
		$dbDomain = $db->getDomainID();
		$storeDomain = $this->loadBalancer->resolveDomainID( $this->wikiId );
		if ( $dbDomain !== $storeDomain ) {
			throw new InvalidArgumentException(
				"DB connection domain '$dbDomain' does not match '$storeDomain'"
			);
		}
	}
}
