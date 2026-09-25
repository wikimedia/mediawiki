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
use Wikimedia\Rdbms\DBAccessObjectUtils;
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
		private readonly ActorStoreFactory $actorStoreFactory,
		private readonly IConnectionProvider $dbProvider,
		private readonly JobQueueGroup $jobQueueGroup,
		private readonly WANObjectCache $wanCache,
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

		$actorStore = $this->actorStoreFactory->getActorStore( $user->getWikiId() );
		$dbr = $this->dbProvider->getReplicaDatabase();
		$count = (int)$dbr->newSelectQueryBuilder()
			->select( 'COUNT(*)' )
			->from( 'revision' )
			->where( [ 'rev_actor' => $actorStore->findActorId( $user, $dbr ) ] )
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
	 * Get the user's first edit timestamp.
	 *
	 * First edit timestamp rarely changes and is therefore strongly cached.
	 * We try to best-effort purge this, but if you need to obtain absolutely
	 * correct and uncached data from a primary database, use READ_LATEST.
	 *
	 * We account for the following:
	 *
	 * - Your first ever edit.
	 *   Purged post-save via UserEditCountUpdate whenever the cached editcount is 0.
	 *   Given this acts on cached count (for performance reasons), this may purge
	 *   for the first several edits, which is harmless.
	 *
	 * - Your first edit's page is deleted and a different edit is now your first.
	 *   Purged via DeletePage after any deletion for all impacted users.
	 *
	 * - Your first edit after your only edits(s) were deleted.
	 *   Via UserEditCountUpdate, same as for first ever edit.
	 *
	 * We do not account for:
	 *
	 * - Importing revisions that attach edits to your account that are older than your
	 *   oldest edit on this wiki. In practice it will probably be correct within a few minutes,
	 *   but if the data for this user is in the cache and frequently accessed, it may be stale
	 *   for upto a month.
	 *
	 * @param UserIdentity $user
	 * @param int $flags IDBAccessObject::READ_NORMAL or IDBAccessObject::READ_LATEST
	 * @return string|false Timestamp of first edit, or false for unknown or unregistered accounts.
	 */
	public function getFirstEditTimestamp(
		UserIdentity $user,
		int $flags = IDBAccessObject::READ_NORMAL
	): string|false {
		if ( !$user->isRegistered() ) {
			// User is unregistered, quick to determine, no need to cache
			return false;
		}
		if ( DBAccessObjectUtils::hasFlags( $flags, IDBAccessObject::READ_LATEST ) ) {
			return $this->getUserEditTimestamp( $user, self::FIRST_EDIT, $flags );
		}

		// For users with edits, the first edit timestamp is very stable,
		// only deleting their first or only edit would alter it, so cache it for a long time.
		$timestamp = $this->wanCache->getWithSetCallback(
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
				'lockTSE' => WANObjectCache::TTL_DAY,
				// Disable the default 5min pre-emptive popularity refresh, because this this
				// data is very stable. Users that read a lot of pages are not more likely to
				// lose their first edit compared to other users. We have purging anyway.
				'hotTTR' => 0,
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

		$actorStore = $this->actorStoreFactory->getActorStore( $user->getWikiId() );
		$sortOrder = ( $type === self::FIRST_EDIT ) ? SelectQueryBuilder::SORT_ASC : SelectQueryBuilder::SORT_DESC;
		$time = $db->newSelectQueryBuilder()
			->select( 'rev_timestamp' )
			->from( 'revision' )
			->where( [ 'rev_actor' => $actorStore->findActorId( $user, $db ) ] )
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
	 * Invalidate the cached first-edit timestamp.
	 *
	 * @param UserIdentity[] $users
	 */
	public function invalidateCachedFirstEditTimestamps( array $users ): void {
		foreach ( $users as $user ) {
			if ( !$user->isRegistered() ) {
				continue;
			}
			$this->wanCache->delete( $this->getCacheKey( self::CACHE_FIRST_EDIT, $user ) );
		}
	}

	/**
	 * Get the cache key by keygroup and UserIdentity.
	 *
	 * This key is used for WANObjectCache and the in-class array cache.
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
	 * Get the cache key by keygroup, user ID, and wiki ID.
	 *
	 * This key is used for WANObjectCache and the in-class array cache.
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
		return $this->wanCache->makeKey( $keygroup, $userId, $wikiId );
	}
}
