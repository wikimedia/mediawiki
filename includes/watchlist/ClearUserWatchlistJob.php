<?php

namespace MediaWiki\Watchlist;

use MediaWiki\JobQueue\GenericParameterJob;
use MediaWiki\JobQueue\Job;
use MediaWiki\MainConfigNames;
use MediaWiki\MediaWikiServices;
use MediaWiki\User\UserIdentity;

/**
 * Job to clear a users watchlist in batches.
 *
 * @since 1.31
 * @ingroup JobQueue
 * @author Addshore
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

	/** @inheritDoc */
	public function run() {
		$updateRowsPerQuery = MediaWikiServices::getInstance()->getMainConfig()->get(
			MainConfigNames::UpdateRowsPerQuery );
		$userId = $this->params['userId'];
		$maxWatchlistId = $this->params['maxWatchlistId'];
		$batchSize = $updateRowsPerQuery;

		$loadBalancer = MediaWikiServices::getInstance()->getDBLoadBalancer();
		$dbw = $loadBalancer->getConnection( DB_PRIMARY );
		$dbr = $loadBalancer->getConnection( DB_REPLICA );

		// Wait before lock to try to reduce time waiting in the lock.
		if ( !$loadBalancer->waitForPrimaryPos( $dbr ) ) {
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

		if ( !$loadBalancer->waitForPrimaryPos( $dbr ) ) {
			$this->setLastError( 'Timed out waiting for replica to catch up within lock' );
			return false;
		}

		// Clear any stale REPEATABLE-READ snapshot
		$dbr->flushSnapshot( __METHOD__ );

		$watchlistIds = $dbr->newSelectQueryBuilder()
			->select( 'wl_id' )
			->from( 'watchlist' )
			->where( [ 'wl_user' => $userId ] )
			->andWhere( $dbr->expr( 'wl_id', '<=', $maxWatchlistId ) )
			->limit( $batchSize )
			->caller( __METHOD__ )->fetchFieldValues();
		if ( count( $watchlistIds ) == 0 ) {
			return true;
		}

		$dbw->newDeleteQueryBuilder()
			->deleteFrom( 'watchlist' )
			->where( [ 'wl_id' => $watchlistIds ] )
			->caller( __METHOD__ )->execute();
		if ( MediaWikiServices::getInstance()->getMainConfig()->get( MainConfigNames::WatchlistExpiry ) ) {
			$dbw->newDeleteQueryBuilder()
				->deleteFrom( 'watchlist_expiry' )
				->where( [ 'we_item' => $watchlistIds ] )
				->caller( __METHOD__ )->execute();
		}

		// Commit changes and remove lock before inserting next job.
		$lbf = MediaWikiServices::getInstance()->getDBLoadBalancerFactory();
		$lbf->commitPrimaryChanges( __METHOD__ );
		unset( $scopedLock );

		if ( count( $watchlistIds ) === (int)$batchSize ) {
			// Until we get less results than the limit, recursively push
			// the same job again.
			MediaWikiServices::getInstance()->getJobQueueGroup()->push( new self( $this->getParams() ) );
		}

		return true;
	}

	/** @inheritDoc */
	public function getDeduplicationInfo() {
		$info = parent::getDeduplicationInfo();
		// This job never has a namespace or title so we can't use it for deduplication
		unset( $info['namespace'] );
		unset( $info['title'] );
		return $info;
	}

}
/** @deprecated class alias since 1.43 */
class_alias( ClearUserWatchlistJob::class, 'ClearUserWatchlistJob' );
