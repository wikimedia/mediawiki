<?php

namespace MediaWiki\User;

use ActorMigration;
use InvalidArgumentException;
use MWTimestamp;
use Wikimedia\Rdbms\ILoadBalancer;

/**
 * Track info about user edit counts and timings
 *
 * @since 1.35
 *
 * @author DannyS712
 */
class UserEditTracker {

	private const FIRST_EDIT = 1;
	private const LATEST_EDIT = 2;

	/** @var ActorMigration */
	private $actorMigration;

	/** @var ILoadBalancer */
	private $loadBalancer;

	/**
	 * @var array
	 *
	 * Mapping of user id to edit count for caching
	 * To avoid using non-sequential numerical keys, keys are in the form: `u⧼user id⧽`
	 */
	private $userEditCountCache = [];

	/**
	 * @param ActorMigration $actorMigration
	 * @param ILoadBalancer $loadBalancer
	 */
	public function __construct(
		ActorMigration $actorMigration,
		ILoadBalancer $loadBalancer
	) {
		$this->actorMigration = $actorMigration;
		$this->loadBalancer = $loadBalancer;
	}

	/**
	 * Get a user's edit count from the user_editcount field, falling back to initialize
	 *
	 * @param UserIdentity $user
	 * @return int
	 * @throws InvalidArgumentException if the user id is invalid
	 */
	public function getUserEditCount( UserIdentity $user ) : int {
		if ( !$user->getId() ) {
			throw new InvalidArgumentException(
				__METHOD__ . ' requires Users with ids set'
			);
		}

		$userId = $user->getId();
		$cacheKey = 'u' . (string)$userId;

		if ( isset( $this->userEditCountCache[ $cacheKey ] ) ) {
			return $this->userEditCountCache[ $cacheKey ];
		}

		$dbr = $this->loadBalancer->getConnectionRef( DB_REPLICA );
		$count = $dbr->selectField(
			'user',
			'user_editcount',
			[ 'user_id' => $userId ],
			__METHOD__
		);

		if ( $count === null ) {
			// it has not been initialized. do so.
			$count = $this->initializeUserEditCount( $user );
		}

		$this->userEditCountCache[ $cacheKey ] = $count;
		return $count;
	}

	/**
	 * @internal public only for use in UserEditCountUpdate
	 *
	 * @param UserIdentity $user
	 * @return int
	 */
	public function initializeUserEditCount( UserIdentity $user ) : int {
		$dbr = $this->loadBalancer->getConnectionRef( DB_REPLICA );
		$actorWhere = $this->actorMigration->getWhere( $dbr, 'rev_user', $user );

		$count = (int)$dbr->selectField(
			[ 'revision' ] + $actorWhere['tables'],
			'COUNT(*)',
			[ $actorWhere['conds'] ],
			__METHOD__,
			[],
			$actorWhere['joins']
		);

		$dbw = $this->loadBalancer->getConnectionRef( DB_MASTER );
		$dbw->update(
			'user',
			[ 'user_editcount' => $count ],
			[
				'user_id' => $user->getId(),
				'user_editcount IS NULL OR user_editcount < ' . $count
			],
			__METHOD__
		);

		return $count;
	}

	/**
	 * Get the user's first edit timestamp
	 *
	 * @param UserIdentity $user
	 * @return string|bool Timestamp of first edit, or false for
	 *     non-existent/anonymous user accounts.
	 */
	public function getFirstEditTimestamp( UserIdentity $user ) {
		return $this->getUserEditTimestamp( $user, self::FIRST_EDIT );
	}

	/**
	 * Get the user's latest edit timestamp
	 *
	 * @param UserIdentity $user
	 * @return string|bool Timestamp of latest edit, or false for
	 *     non-existent/anonymous user accounts.
	 */
	public function getLatestEditTimestamp( UserIdentity $user ) {
		return $this->getUserEditTimestamp( $user, self::LATEST_EDIT );
	}

	/**
	 * Get the timestamp of a user's edit, either their first or latest
	 *
	 * @param UserIdentity $user
	 * @param int $type either self::FIRST_EDIT or ::LATEST_EDIT
	 * @return string|bool Timestamp of edit, or false for
	 *     non-existent/anonymous user accounts.
	 */
	private function getUserEditTimestamp( UserIdentity $user, int $type ) {
		if ( $user->getId() === 0 ) {
			return false; // anonymous user
		}

		$dbr = $this->loadBalancer->getConnectionRef( DB_REPLICA );
		$actorWhere = $this->actorMigration->getWhere( $dbr, 'rev_user', $user );

		$tsField = isset( $actorWhere['tables']['temp_rev_user'] )
			? 'revactor_timestamp' : 'rev_timestamp';

		$sortOrder = ( $type === self::FIRST_EDIT ) ? 'ASC' : 'DESC';
		$time = $dbr->selectField(
			[ 'revision' ] + $actorWhere['tables'],
			$tsField,
			[ $actorWhere['conds'] ],
			__METHOD__,
			[ 'ORDER BY' => "$tsField $sortOrder" ],
			$actorWhere['joins']
		);

		if ( !$time ) {
			return false; // no edits
		}

		return MWTimestamp::convert( TS_MW, $time );
	}

	/**
	 * @internal
	 *
	 * @param UserIdentity $user
	 */
	public function clearUserEditCache( UserIdentity $user ) {
		if ( !$user->isRegistered() ) {
			return;
		}

		$userId = $user->getId();
		$cacheKey = 'u' . (string)$userId;

		$this->userEditCountCache[ $cacheKey ] = null;
	}

}
