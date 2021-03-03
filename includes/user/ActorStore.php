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
use ExternalUserNames;
use InvalidArgumentException;
use MapCacheLRU;
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

	public const UNKNOWN_USER_NAME = 'Unknown user';

	private const LOCAL_CACHE_SIZE = 5;

	/** @var ILoadBalancer */
	private $loadBalancer;

	/** @var UserNameUtils */
	private $userNameUtils;

	/** @var LoggerInterface */
	private $logger;

	/** @var string|false */
	private $wikiId;

	/** @var MapCacheLRU int actor ID => [ UserIdentity, int actor ID ] */
	private $actorsByActorId;

	/** @var MapCacheLRU int user ID => [ UserIdentity, int actor ID ] */
	private $actorsByUserId;

	/** @var MapCacheLRU string user name => [ UserIdentity, int actor ID ] */
	private $actorsByName;

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

		$this->actorsByActorId = new MapCacheLRU( self::LOCAL_CACHE_SIZE );
		$this->actorsByUserId = new MapCacheLRU( self::LOCAL_CACHE_SIZE );
		$this->actorsByName = new MapCacheLRU( self::LOCAL_CACHE_SIZE );
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

		$actor = new UserIdentityValue( $userId, $row->actor_name, $actorId, $this->wikiId );
		$this->addUserIdentityToCache( $actorId, $actor );
		return $actor;
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
	 * @param int|null $userId
	 * @param string|null $name
	 * @param int|null $actorId
	 * @return UserIdentity
	 */
	public function newActorFromRowFields( $userId, $name, $actorId ): UserIdentity {
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

		$actorId = (int)$actorId;
		$actor = new UserIdentityValue(
			$userId,
			$name,
			$actorId,
			$this->wikiId
		);

		$this->addUserIdentityToCache( $actorId, $actor );
		return $actor;
	}

	/**
	 * @param int $actorId
	 * @param UserIdentity $actor
	 */
	private function addUserIdentityToCache( int $actorId, UserIdentity $actor ) {
		$this->actorsByActorId->set( $actorId, [ $actor, $actorId ] );
		$userId = $actor->getId( $this->wikiId );
		if ( $userId ) {
			$this->actorsByUserId->set( $userId, [ $actor, $actorId ] );
		}
		$this->actorsByName->set( $actor->getName(), [ $actor, $actorId ] );
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

		$cachedValue = $this->actorsByActorId->get( $actorId );
		if ( $cachedValue ) {
			return $cachedValue[0];
		}

		return $this->newSelectQueryBuilder( $queryFlags )
			->caller( __METHOD__ )
			->conds( [ 'actor_id' => $actorId ] )
			->fetchUserIdentity();
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

		$cachedValue = $this->actorsByName->get( $name );
		if ( $cachedValue ) {
			return $cachedValue[0];
		}

		return $this->newSelectQueryBuilder( $queryFlags )
			->caller( __METHOD__ )
			->userNames( $name )
			->fetchUserIdentity();
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

		$cachedValue = $this->actorsByUserId->get( $userId );
		if ( $cachedValue ) {
			return $cachedValue[0];
		}

		return $this->newSelectQueryBuilder( $queryFlags )
			->caller( __METHOD__ )
			->userIds( $userId )
			->fetchUserIdentity();
	}

	/**
	 * Attach the actor ID to $user for backwards compatibility.
	 *
	 * @todo remove this method when no longer needed (T273974).
	 *
	 * @param UserIdentity $user
	 * @param int $id
	 */
	private function attachActorId( UserIdentity $user, int $id ) {
		if ( $user instanceof UserIdentityValue ) {
			$user->setActorId( $id );
		} elseif ( $user instanceof User ) {
			$user->setActorId( $id );
		}
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

		// TODO: In the future we would be able to assume UserIdentity name is ok
		// and will be able to skip normalization here - T273933
		$name = $this->normalizeUserName( $user->getName() );
		if ( !$name ) {
			$this->logger->warning( 'Encountered a UserIdentity with invalid name', [
				'user_name' => $user->getName()
			] );
			return null;
		}

		[ $db, $options ] = $this->getDBConnectionRefForQueryFlags( $queryFlags );
		$id = $this->findActorIdInternal( $name, $db, $options );

		if ( $id ) {
			$this->attachActorId( $user, $id );
		}

		return $id;
	}

	/**
	 * Find the actor_id of the given $name.
	 *
	 * @param string $name
	 * @param int $queryFlags
	 * @return int|null
	 */
	public function findActorIdByName( $name, int $queryFlags = self::READ_NORMAL ): ?int {
		// NOTE: $name may be user-supplied, need full normalization
		$name = $this->normalizeUserName( $name, UserNameUtils::RIGOR_VALID );
		if ( !$name ) {
			return null;
		}

		[ $db, $options ] = $this->getDBConnectionRefForQueryFlags( $queryFlags );
		$id = $this->findActorIdInternal( $name, $db, $options );

		return $id;
	}

	/**
	 * Find actor_id of the given $user using the passed $db connection.
	 *
	 * @param string $name
	 * @param IDatabase $db
	 * @param array $queryOptions
	 * @return int|null
	 */
	private function findActorIdInternal(
		string $name,
		IDatabase $db,
		array $queryOptions = []
	): ?int {
		// Note: UserIdentity::getActorId will be deprecated and removed,
		// and this is the replacement for it. Can't call User::getActorId, cause
		// User always thinks it's local, so we could end up fetching the ID
		// from the wrong database.

		$cachedValue = $this->actorsByName->get( $name );
		if ( $cachedValue ) {
			return $cachedValue[1];
		}

		$row = $db->selectRow(
			'actor',
			[ 'actor_user', 'actor_name', 'actor_id' ],
			[ 'actor_name' => $name ],
			__METHOD__,
			$queryOptions
		);

		if ( !$row || !$row->actor_id ) {
			return null;
		}

		$id = (int)$row->actor_id;

		// to cache row
		$this->newActorFromRow( $row );

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
			[ $dbw, ] = $this->getDBConnectionRefForQueryFlags( self::READ_LATEST );
		}
		// TODO: we want to assert this user belongs to the correct wiki,
		// but User objects are always local and we used to use them
		// on a non-local DB connection. We need to first deprecate this
		// possibility and then throw on mismatching User object - T273972
		// $user->assertWiki( $this->wikiId );

		$userName = $this->normalizeUserName( $user->getName() );
		if ( $userName === null || $userName === '' ) {
			$userIdForErrorMessage = $user->getId( $this->wikiId );
			throw new CannotCreateActorException(
				'Cannot create an actor for a user with no name: ' .
				"user_id={$userIdForErrorMessage} user_name=\"{$user->getName()}\""
			);
		}

		// allow cache to be used, because if it is in the cache, it already has an actor ID
		$existingActorId = $this->findActorIdInternal( $userName, $dbw );
		if ( $existingActorId ) {
			$this->attachActorId( $user, $existingActorId );
			return $existingActorId;
		}

		$userId = $user->getId( $this->wikiId ) ?: null;
		if ( $userId === null && $this->userNameUtils->isUsable( $user->getName() ) ) {
			throw new CannotCreateActorException(
				'Cannot create an actor for a usable name that is not an existing user: ' .
				"user_name=\"{$user->getName()}\""
			);
		}

		$dbw->insert(
			'actor',
			[
				'actor_user' => $userId,
				'actor_name' => $userName,
			],
			__METHOD__,
			[ 'IGNORE' ] );
		if ( $dbw->affectedRows() ) {
			$actorId = (int)$dbw->insertId();
		} else {
			// Outdated cache?
			// Use LOCK IN SHARE MODE to bypass any MySQL REPEATABLE-READ snapshot.
			$actorId = $this->findActorIdInternal(
				$userName,
				$dbw,
				[ 'LOCK IN SHARE MODE' ]
			);
			if ( !$actorId ) {
				throw new CannotCreateActorException(
					"Failed to create actor ID for " .
					"user_id={$userId} user_name=\"{$userName}\""
				);
			}
		}

		// Cache row we've just created
		$this->newActorFromRowFields( $userId, $userName, $actorId );
		$this->attachActorId( $user, $actorId );

		return $actorId;
	}

	/**
	 * Returns a canonical form of user name suitable for storage.
	 *
	 * @internal
	 * @param string $name
	 * @param string $rigor UserNameUtils::RIGOR_XXX
	 *
	 * @return string|null
	 */
	public function normalizeUserName( string $name, $rigor = UserNameUtils::RIGOR_NONE ): ?string {
		if ( $this->userNameUtils->isIP( $name ) ) {
			return IPUtils::sanitizeIP( $name );
		} elseif ( ExternalUserNames::isExternal( $name ) ) {
			// TODO: ideally, we should probably canonicalize external usernames,
			// but it was not done before, so we can not start doing it unless we
			// fix existing DB rows - T273933
			return $name;
		} elseif ( $rigor !== UserNameUtils::RIGOR_NONE ) {
			return $this->userNameUtils->getCanonical( $name, $rigor ) ?: null;
		} else {
			return $name;
		}
	}

	/**
	 * @param int $queryFlags a bit field composed of READ_XXX flags
	 * @return array [ IDatabase $db, array $options ]
	 */
	private function getDBConnectionRefForQueryFlags( int $queryFlags ): array {
		[ $mode, $options ] = DBAccessObjectUtils::getDBOptions( $queryFlags );
		return [ $this->loadBalancer->getConnectionRef( $mode, [], $this->wikiId ), $options ];
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

	/**
	 * In case all reasonable attempts of initializing a proper actor from the
	 * database have failed, entities can be attributed to special 'Unknown user' actor.
	 *
	 * @return UserIdentity
	 */
	public function getUnknownActor(): UserIdentity {
		$actor = $this->getUserIdentityByName( self::UNKNOWN_USER_NAME );
		if ( $actor ) {
			return $actor;
		}
		$actor = new UserIdentityValue( 0, self::UNKNOWN_USER_NAME, 0, $this->wikiId );
		$this->acquireActorId( $actor );
		return $actor;
	}

	/**
	 * Returns a specialized SelectQueryBuilder for querying the UserIdentity objects.
	 *
	 * @param int $queryFlags one of IDBAccessObject constants
	 * @return UserSelectQueryBuilder
	 */
	public function newSelectQueryBuilder( int $queryFlags = self::READ_NORMAL ): UserSelectQueryBuilder {
		[ $db, $options ] = $this->getDBConnectionRefForQueryFlags( $queryFlags );
		return ( new UserSelectQueryBuilder( $db, $this ) )->options( $options );
	}
}
