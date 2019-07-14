<?php

use MediaWiki\MediaWikiServices;
use MediaWiki\User\UserIdentity;

/**
 * Job to clear a users watchlist in batches.
 *
 * @author Addshore
 *
 * @ingroup JobQueue
 * @since 1.31
 */
class ClearUserWatchlistJob extends Job implements GenericParameterJob {
	/**
	 * @param array $params
	 *  - userId,         The ID for the user whose watchlist is being cleared.
	 *  - maxWatchlistId, The maximum wl_id at the time the job was first created,
	 */
	public function __construct( array $params ) {
		parent::__construct( 'clearUserWatchlist', $params );

		$this->removeDuplicates = true;
	}

	/**
	 * @param UserIdentity $user User to clear the watchlist for.
	 * @param int $maxWatchlistId The maximum wl_id at the time the job was first created.
	 *
	 * @return ClearUserWatchlistJob
	 */
	public static function newForUser( UserIdentity $user, $maxWatchlistId ) {
		return new self( [ 'userId' => $user->getId(), 'maxWatchlistId' => $maxWatchlistId ] );
	}

	public function run() {
		global $wgUpdateRowsPerQuery;
		$userId = $this->params['userId'];
		$maxWatchlistId = $this->params['maxWatchlistId'];
		$batchSize = $wgUpdateRowsPerQuery;

		$loadBalancer = MediaWikiServices::getInstance()->getDBLoadBalancer();
		$dbw = $loadBalancer->getConnectionRef( DB_MASTER );
		$dbr = $loadBalancer->getConnectionRef( DB_REPLICA, [ 'watchlist' ] );

		// Wait before lock to try to reduce time waiting in the lock.
		if ( !$loadBalancer->waitForMasterPos( $dbr ) ) {
			$this->setLastError( 'Timed out waiting for replica to catch up before lock' );
			return false;
		}

		// Use a named lock so that jobs for this user see each others' changes
		$lockKey = "{{$dbw->getDomainID()}}:ClearUserWatchlist:$userId"; // per-wiki
		$scopedLock = $dbw->getScopedLockAndFlush( $lockKey, __METHOD__, 10 );
		if ( !$scopedLock ) {
			$this->setLastError( "Could not acquire lock '$lockKey'" );
			return false;
		}

		if ( !$loadBalancer->waitForMasterPos( $dbr ) ) {
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
			JobQueueGroup::singleton()->push( new self( $this->getParams() ) );
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
