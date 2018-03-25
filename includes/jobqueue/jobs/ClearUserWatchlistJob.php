<?php

use MediaWiki\MediaWikiServices;

/**
 * Job to clear a users watchlist in batches.
 *
 * @author Addshore
 *
 * @ingroup JobQueue
 * @since 1.31
 */
class ClearUserWatchlistJob extends Job {

	/**
	 * @param User $user User to clear the watchlist for.
	 * @param int $maxWatchlistId The maximum wl_id at the time the job was first created.
	 *
	 * @return ClearUserWatchlistJob
	 */
	public static function newForUser( User $user, $maxWatchlistId ) {
		return new self(
			null,
			[ 'userId' => $user->getId(), 'maxWatchlistId' => $maxWatchlistId ]
		);
	}

	/**
	 * @param Title|null $title Not used by this job.
	 * @param array $params
	 *  - userId,         The ID for the user whose watchlist is being cleared.
	 *  - maxWatchlistId, The maximum wl_id at the time the job was first created,
	 */
	public function __construct( Title $title = null, array $params ) {
		parent::__construct(
			'clearUserWatchlist',
			SpecialPage::getTitleFor( 'EditWatchlist', 'clear' ),
			$params
		);

		$this->removeDuplicates = true;
	}

	public function run() {
		global $wgUpdateRowsPerQuery;
		$userId = $this->params['userId'];
		$maxWatchlistId = $this->params['maxWatchlistId'];
		$batchSize = $wgUpdateRowsPerQuery;

		$loadBalancer = MediaWikiServices::getInstance()->getDBLoadBalancer();
		$dbw = $loadBalancer->getConnection( DB_MASTER );
		$dbr = $loadBalancer->getConnection( DB_REPLICA, [ 'watchlist' ] );

		// Wait before lock to try to reduce time waiting in the lock.
		if ( !$loadBalancer->safeWaitForMasterPos( $dbr ) ) {
			$this->setLastError( 'Timed out waiting for replica to catch up before lock' );
			return false;
		}

		// Use a named lock so that jobs for this user see each others' changes
		$lockKey = "ClearUserWatchlistJob:$userId";
		$scopedLock = $dbw->getScopedLockAndFlush( $lockKey, __METHOD__, 10 );
		if ( !$scopedLock ) {
			$this->setLastError( "Could not acquire lock '$lockKey'" );
			return false;
		}

		if ( !$loadBalancer->safeWaitForMasterPos( $dbr ) ) {
			$this->setLastError( 'Timed out waiting for replica to catch up within lock' );
			return false;
		}

		// Clear any stale REPEATABLE-READ snapshot
		$dbr->flushSnapshot( __METHOD__ );

		$watchlistIds = $dbr->selectFieldValues(
			'watchlist',
			'wl_id',
			[
				'wl_user' => $userId,
				'wl_id <= ' . $maxWatchlistId
			],
			__METHOD__,
			[
				'ORDER BY' => 'wl_id ASC',
				'LIMIT' => $batchSize,
			]
		);

		if ( count( $watchlistIds ) == 0 ) {
			return true;
		}

		$dbw->delete( 'watchlist', [ 'wl_id' => $watchlistIds ], __METHOD__ );

		// Commit changes and remove lock before inserting next job.
		$lbf = MediaWikiServices::getInstance()->getDBLoadBalancerFactory();
		$lbf->commitMasterChanges( __METHOD__ );
		unset( $scopedLock );

		if ( count( $watchlistIds ) === (int)$batchSize ) {
			// Until we get less results than the limit, recursively push
			// the same job again.
			JobQueueGroup::singleton()->push( new self( $this->getTitle(), $this->getParams() ) );
		}

		return true;
	}

	public function getDeduplicationInfo() {
		$info = parent::getDeduplicationInfo();
		// This job never has a namespace or title so we can't use it for deduplication
		unset( $info['namespace'] );
		unset( $info['title'] );
		return $info;
	}

}
