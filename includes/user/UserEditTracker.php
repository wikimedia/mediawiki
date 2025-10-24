<?php

namespace MediaWiki\User;

use InvalidArgumentException;
use LogicException;
use MediaWiki\Deferred\DeferredUpdates;
use MediaWiki\Deferred\UserEditCountUpdate;
use MediaWiki\JobQueue\JobQueueGroup;
use MediaWiki\WikiMap\WikiMap;
use UserEditCountInitJob;
use Wikimedia\Rdbms\IConnectionProvider;
use Wikimedia\Rdbms\IDBAccessObject;
use Wikimedia\Rdbms\SelectQueryBuilder;
use Wikimedia\Timestamp\ConvertibleTimestamp;

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

	private ActorNormalization $actorNormalization;
	private IConnectionProvider $dbProvider;
	private JobQueueGroup $jobQueueGroup;

	/**
	 * @var int[]
	 *
	 * Mapping of user id to edit count for caching
	 * The keys are in one of the forms:
	 * * `u{user_id}` - for registered users from the local wiki
	 * * `{wiki_id}:u{user_id}` - for registered users from other wikis
	 */
	private $userEditCountCache = [];

	/**
	 * @param ActorNormalization $actorNormalization
	 * @param IConnectionProvider $dbProvider
	 * @param JobQueueGroup $jobQueueGroup
	 */
	public function __construct(
		ActorNormalization $actorNormalization,
		IConnectionProvider $dbProvider,
		JobQueueGroup $jobQueueGroup
	) {
		$this->actorNormalization = $actorNormalization;
		$this->dbProvider = $dbProvider;
		$this->jobQueueGroup = $jobQueueGroup;
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

		$cacheKey = $this->getCacheKey( $user );
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
	 * Get the user's first edit timestamp
	 *
	 * @param UserIdentity $user
	 * @param int $flags bit field, see IDBAccessObject::READ_XXX
	 * @return string|false Timestamp of first edit, or false for non-existent/anonymous user
	 *  accounts.
	 */
	public function getFirstEditTimestamp( UserIdentity $user, int $flags = IDBAccessObject::READ_NORMAL ) {
		return $this->getUserEditTimestamp( $user, self::FIRST_EDIT, $flags );
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

		return ConvertibleTimestamp::convert( TS_MW, $time );
	}

	/**
	 * @internal For use by User::clearInstanceCache()
	 * @param UserIdentity $user
	 */
	public function clearUserEditCache( UserIdentity $user ) {
		if ( !$user->isRegistered() ) {
			return;
		}

		$cacheKey = $this->getCacheKey( $user );
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

		$cacheKey = $this->getCacheKey( $user );
		$this->userEditCountCache[ $cacheKey ] = $editCount;
	}

	private function getCacheKey( UserIdentity $user ): string {
		if ( !$user->isRegistered() ) {
			throw new InvalidArgumentException( 'Cannot prepare cache key for an anonymous user' );
		}

		$wikiId = $user->getWikiId();
		$userId = $user->getId( $wikiId );
		$isRemoteWiki = ( $wikiId !== UserIdentity::LOCAL ) && !WikiMap::isCurrentWikiId( $wikiId );
		if ( $isRemoteWiki ) {
			return $wikiId . ':u' . $userId;
		}
		return 'u' . $userId;
	}
}
