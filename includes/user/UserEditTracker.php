<?php

namespace MediaWiki\User;

use InvalidArgumentException;
use JobQueueGroup;
use MediaWiki\Deferred\DeferredUpdates;
use MediaWiki\Deferred\UserEditCountUpdate;
use UserEditCountInitJob;
use Wikimedia\Rdbms\DBAccessObjectUtils;
use Wikimedia\Rdbms\IConnectionProvider;
use Wikimedia\Rdbms\IDBAccessObject;
use Wikimedia\Rdbms\SelectQueryBuilder;
use Wikimedia\Timestamp\ConvertibleTimestamp;

/**
 * Track info about user edit counts and timings
 *
 * @since 1.35
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
	 * To avoid using non-sequential numerical keys, keys are in the form: `u⧼user id⧽`
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
		$userId = $user->getId();
		if ( !$userId ) {
			return null;
		}

		$cacheKey = 'u' . $userId;
		if ( isset( $this->userEditCountCache[ $cacheKey ] ) ) {
			return $this->userEditCountCache[ $cacheKey ];
		}

		$count = $this->dbProvider->getReplicaDatabase()->newSelectQueryBuilder()
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
		if ( !$user->getId() ) {
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
		if ( !$user->getId() ) {
			return false;
		}
		$db = DBAccessObjectUtils::getDBFromRecency( $this->dbProvider, $flags );

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
		$userId = $user->getId();
		if ( !$userId ) {
			return;
		}

		$cacheKey = 'u' . $userId;
		unset( $this->userEditCountCache[ $cacheKey ] );
	}

	/**
	 * @internal For use by User::loadFromRow() and tests
	 * @param UserIdentity $user
	 * @param int $editCount
	 * @throws InvalidArgumentException If the user is not registered
	 */
	public function setCachedUserEditCount( UserIdentity $user, int $editCount ) {
		$userId = $user->getId();
		if ( !$userId ) {
			throw new InvalidArgumentException( __METHOD__ . ' with an anonymous user' );
		}

		$cacheKey = 'u' . $userId;
		$this->userEditCountCache[ $cacheKey ] = $editCount;
	}

}
