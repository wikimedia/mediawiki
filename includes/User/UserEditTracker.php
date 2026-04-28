<?php

namespace MediaWiki\User;

use InvalidArgumentException;
use LogicException;
use MediaWiki\DAO\WikiAwareEntity;
use MediaWiki\Deferred\DeferredUpdates;
use MediaWiki\Deferred\UserEditCountUpdate;
use MediaWiki\JobQueue\JobQueueGroup;
use MediaWiki\WikiMap\WikiMap;
use Wikimedia\ObjectCache\WANObjectCache;
use Wikimedia\Rdbms\IConnectionProvider;
use Wikimedia\Rdbms\IDBAccessObject;
use Wikimedia\Rdbms\SelectQueryBuilder;
use Wikimedia\Timestamp\ConvertibleTimestamp;
use Wikimedia\Timestamp\TimestampFormat as TS;

/**
 * Track info about user edit counts and timings
 *
 * @since 1.35
 * @ingroup User
 * @author DannyS712
 */
class UserEditTracker {

	private const FIRST_EDIT = 1;
	private const LATEST_EDIT = 2;

	private const CACHE_FIRST_EDIT = 'firsteditts';
	private const CACHE_EDIT_COUNT = 'editcount';

	/** @var int[] */
	private array $userEditCountCache = [];

	public function __construct(
		private readonly ActorNormalization $actorNormalization,
		private readonly IConnectionProvider $dbProvider,
		private readonly JobQueueGroup $jobQueueGroup,
		private readonly WANObjectCache $wanObjectCache,
	) {
	}

	/**
	 * Get a user's edit count from the user_editcount field, falling back to initialize
	 *
	 * @param UserIdentity $user
	 * @return int|null Null for anonymous users
	 */
	public function getUserEditCount( UserIdentity $user ): ?int {
		if ( !$user->isRegistered() ) {
			return null;
		}

		$cacheKey = $this->getCacheKey( self::CACHE_EDIT_COUNT, $user );
		if ( isset( $this->userEditCountCache[ $cacheKey ] ) ) {
			return $this->userEditCountCache[ $cacheKey ];
		}

		$wikiId = $user->getWikiId();
		$userId = $user->getId( $wikiId );
		$count = $this->dbProvider->getReplicaDatabase( $wikiId )->newSelectQueryBuilder()
			->select( 'user_editcount' )
			->from( 'user' )
			->where( [ 'user_id' => $userId ] )
			->caller( __METHOD__ )->fetchField();

		if ( $count === null ) {
			// it has not been initialized. do so.
			$count = $this->initializeUserEditCount( $user );
		}

		$this->userEditCountCache[ $cacheKey ] = $count;
		return $count;
	}

	/**
	 * Preloads the internal edit count cache for the given users.
	 *
	 * Use this when calls to {@link self::getUserEditCount()} are expected for
	 * multiple users, so that the queries can be batched instead of performing
	 * one query per user.
	 *
	 * Unlike {@link self::getUserEditCount()}, this will not try to update the
	 * edit counts stored in user_editcount for users for which the count was
	 * not previously initialized.
	 *
	 * @param UserIdentity[] $users
	 * @since 1.46
	 * @return void
	 */
	public function preloadUserEditCountCache( array $users ): void {
		$userIds = [];

		foreach ( $users as $user ) {
			if (
				$user->isRegistered() &&
				$user->getWikiId() === UserIdentity::LOCAL
			) {
				$userIds[] = $user->getId();
			}
		}

		$userIds = array_unique( $userIds );

		$dbr = $this->dbProvider->getReplicaDatabase();

		foreach ( array_chunk( $userIds, 500 ) as $batch ) {
			$rows = $dbr->newSelectQueryBuilder()
				->select( [ 'user_id', 'user_editcount' ] )
				->from( 'user' )
				->where( [ 'user_id' => $batch ] )
				->caller( __METHOD__ )
				->fetchResultSet();

			foreach ( $rows as $row ) {
				if ( $row->user_editcount !== null ) {
					$key = $this->getCacheKeyByUserId( self::CACHE_EDIT_COUNT, (int)$row->user_id );

					$this->userEditCountCache[$key] = (int)$row->user_editcount;
				}
			}
		}
	}

	/**
	 * @internal For use in UserEditCountUpdate class
	 * @param UserIdentity $user
	 * @return int
	 */
	public function initializeUserEditCount( UserIdentity $user ): int {
		if ( $user->getWikiId() !== UserIdentity::LOCAL ) {
			// Don't record edits on remote wikis
			throw new LogicException( __METHOD__ . ' only supports local users' );
		}

		$dbr = $this->dbProvider->getReplicaDatabase();
		$count = (int)$dbr->newSelectQueryBuilder()
			->select( 'COUNT(*)' )
			->from( 'revision' )
			->where( [ 'rev_actor' => $this->actorNormalization->findActorId( $user, $dbr ) ] )
			->caller( __METHOD__ )
			->fetchField();

		// Defer updating the edit count via a job (T259719)
		$this->jobQueueGroup->push( new UserEditCountInitJob( [
			'userId' => $user->getId(),
			'editCount' => $count,
		] ) );

		return $count;
	}

	/**
	 * Schedule a job to increase a user's edit count
	 *
	 * @since 1.37
	 * @param UserIdentity $user
	 */
	public function incrementUserEditCount( UserIdentity $user ) {
		if ( !$user->isRegistered() ) {
			// Can't store editcount without user row (i.e. unregistered)
			return;
		}

		DeferredUpdates::addUpdate(
			new UserEditCountUpdate( $user, 1 ),
			DeferredUpdates::POSTSEND
		);
	}

	/**
	 * Get the user's first edit timestamp. First edit timestamp is fairly immutable and therefore it's cached.
	 * If you need to obtain uncached data, use $flags different from READ_NORMAL.
	 *
	 * @param UserIdentity $user
	 * @param int $flags bit field, see IDBAccessObject::READ_XXX
	 * @return string|false Timestamp of first edit, or false for non-existent/anonymous user
	 *  accounts.
	 */
	public function getFirstEditTimestamp(
		UserIdentity $user,
		int $flags = IDBAccessObject::READ_NORMAL
	): string|false {
		if ( !$user->isRegistered() ) {
			// User is unregistered, quick to determine, no need to cache
			return false;
		}
		if ( $flags !== IDBAccessObject::READ_NORMAL ) {
			return $this->getUserEditTimestamp( $user, self::FIRST_EDIT, $flags );
		}

		// For users with edits, the first edit timestamp is fairly stable, only deleting their first edit can
		// alter it, so we can cache it for a long time.
		$timestamp = $this->wanObjectCache->getWithSetCallback(
			$this->getCacheKey( self::CACHE_FIRST_EDIT, $user ),
			WANObjectCache::TTL_MONTH,
			function () use ( $user ) {
				$timestamp = $this->getUserEditTimestamp( $user, self::FIRST_EDIT );
				if ( $timestamp === false ) {
					$timestamp = 0;
				}
				return $timestamp;
			},
			[
				'lockTSE' => 30,
				'staleTTL' => WANObjectCache::TTL_DAY,
				// First edit timestamp is very stable; reduce popularity-based preemptive refresh rate
				// After the initial hour passes (newAge), the timestamp will be refreshed on average every
				// 10k requests (i.e. once every 6 hours if requests come at 1 req/sec)
				'newAge' => 3600,
				'hotTTR' => 21600,
			]
		);
		if ( $timestamp === 0 ) {
			return false;
		}
		return $timestamp;
	}

	/**
	 * Get the user's latest edit timestamp
	 *
	 * @param UserIdentity $user
	 * @param int $flags bit field, see IDBAccessObject::READ_XXX
	 * @return string|false Timestamp of latest edit, or false for non-existent/anonymous user
	 *  accounts.
	 */
	public function getLatestEditTimestamp( UserIdentity $user, int $flags = IDBAccessObject::READ_NORMAL ) {
		return $this->getUserEditTimestamp( $user, self::LATEST_EDIT, $flags );
	}

	/**
	 * Get the timestamp of a user's edit, either their first or latest
	 *
	 * @param UserIdentity $user
	 * @param int $type either self::FIRST_EDIT or ::LATEST_EDIT
	 * @param int $flags bit field, see IDBAccessObject::READ_XXX
	 * @return string|false Timestamp of edit, or false for non-existent/anonymous user accounts.
	 */
	private function getUserEditTimestamp( UserIdentity $user, int $type, int $flags = IDBAccessObject::READ_NORMAL ) {
		if ( !$user->isRegistered() ) {
			return false;
		}
		if ( $flags & IDBAccessObject::READ_LATEST ) {
			$db = $this->dbProvider->getPrimaryDatabase( $user->getWikiId() );
		} else {
			$db = $this->dbProvider->getReplicaDatabase( $user->getWikiId() );
		}

		$sortOrder = ( $type === self::FIRST_EDIT ) ? SelectQueryBuilder::SORT_ASC : SelectQueryBuilder::SORT_DESC;
		$time = $db->newSelectQueryBuilder()
			->select( 'rev_timestamp' )
			->from( 'revision' )
			->where( [ 'rev_actor' => $this->actorNormalization->findActorId( $user, $db ) ] )
			->orderBy( 'rev_timestamp', $sortOrder )
			->caller( __METHOD__ )
			->fetchField();

		if ( !$time ) {
			return false; // no edits
		}

		return ConvertibleTimestamp::convert( TS::MW, $time );
	}

	/**
	 * @internal For use by User::clearInstanceCache()
	 * @param UserIdentity $user
	 */
	public function clearUserEditCache( UserIdentity $user ) {
		if ( !$user->isRegistered() ) {
			return;
		}

		$cacheKey = $this->getCacheKey( self::CACHE_EDIT_COUNT, $user );
		unset( $this->userEditCountCache[ $cacheKey ] );
	}

	/**
	 * @internal For use by User::loadFromRow() and tests
	 * @param UserIdentity $user
	 * @param int $editCount
	 * @throws InvalidArgumentException If the user is not registered
	 */
	public function setCachedUserEditCount( UserIdentity $user, int $editCount ) {
		if ( !$user->isRegistered() ) {
			throw new InvalidArgumentException( __METHOD__ . ' with an anonymous user' );
		}

		$cacheKey = $this->getCacheKey( self::CACHE_EDIT_COUNT, $user );
		$this->userEditCountCache[ $cacheKey ] = $editCount;
	}

	/**
	 * Invalidates the timestamps of the first edits by users, if they are equal to the timestamps
	 * passed together with users.
	 * @param array<array{0:UserIdentity,1:string|false}> $users Array of pairs (UserIdentity, timestamp).
	 */
	public function invalidateCachedFirstEditTimestamps( array $users ): void {
		foreach ( $users as [ $user, $timestamp ] ) {
			if ( !$user->isRegistered() ) {
				continue;
			}

			if ( $timestamp === false ) {
				// We cache "no first edit" as 0, because false means "don't cache"
				$timestamp = 0;
			}

			$key = $this->getCacheKey( self::CACHE_FIRST_EDIT, $user );
			$cachedTimestamp = $this->wanObjectCache->get( $key );

			if ( $timestamp === $cachedTimestamp ) {
				$this->wanObjectCache->delete( $key );
			}
		}
	}

	/**
	 * Returns the cache key to be used for reading from or updating the cache
	 * for a given user, identified by its user ID and the ID of the wiki it
	 * belongs to. This key can be used for WANObjectCache and array-based caches.
	 *
	 * @param string $keygroup Key group component, to separate different things.
	 * @param UserIdentity $user User to get the cache key for.
	 * @return string
	 */
	private function getCacheKey( string $keygroup, UserIdentity $user ): string {
		if ( !$user->isRegistered() ) {
			throw new InvalidArgumentException( 'Cannot prepare cache key for an anonymous user' );
		}

		$wikiId = $user->getWikiId();

		return $this->getCacheKeyByUserId( $keygroup, $user->getId( $wikiId ), $wikiId );
	}

	/**
	 * Returns the cache key to be used for reading from or updating the cache
	 * for a given user, identified by its user ID and the ID of the wiki it
	 * belongs to. This key can be used for WANObjectCache and array-based caches.
	 *
	 * @param string $keygroup Key group component, to separate different things.
	 * @param int $userId ID of the user to get the cache key for.
	 * @param string|false $wikiId ID of the wiki the user belongs to.
	 * @return string
	 */
	private function getCacheKeyByUserId(
		string $keygroup,
		int $userId,
		string|bool $wikiId = WikiAwareEntity::LOCAL
	): string {
		if ( $wikiId === WikiAwareEntity::LOCAL ) {
			$wikiId = WikiMap::getCurrentWikiId();
		}
		return $this->wanObjectCache->makeKey( $keygroup, $userId, $wikiId );
	}
}
