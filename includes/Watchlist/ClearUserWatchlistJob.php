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
		$config = MediaWikiServices::getInstance()->getMainConfig();
		$dbProvider = MediaWikiServices::getInstance()->getConnectionProvider();

		$batchSize = $config->get( MainConfigNames::UpdateRowsPerQuery );
		$userId = $this->params['userId'];
		$maxWatchlistId = $this->params['maxWatchlistId'];

		$dbw = $dbProvider->getPrimaryDatabase();
		$dbr = $dbProvider->getReplicaDatabase();
		$ticket = $dbProvider->getEmptyTransactionTicket( __METHOD__ );

		// Use a named lock so that jobs for this user see each others' changes
		$lockKey = "ClearUserWatchlist:$userId"; // per-wiki
		$scopedLock = MediaWikiServices::getInstance()->getLockManager()->scopedLock( $lockKey );
		if ( !$scopedLock ) {
			$this->setLastError( "Could not acquire lock '$lockKey'" );
			return false;
		}

		// Optimization: Query the replica instead of the primary.
		// Therefore, ensure we're caught up with the latest changes first
		$dbProvider->commitAndWaitForReplication( __METHOD__, $ticket );

		$watchlistIds = $dbr->newSelectQueryBuilder()
			->select( 'wl_id' )
			->from( 'watchlist' )
			->where( [ 'wl_user' => $userId ] )
			->andWhere( $dbr->expr( 'wl_id', '<=', $maxWatchlistId ) )
			->limit( $batchSize )
			->caller( __METHOD__ )->fetchFieldValues();
		if ( !$watchlistIds ) {
			return true;
		}

		$dbw->newDeleteQueryBuilder()
			->deleteFrom( 'watchlist' )
			->where( [ 'wl_id' => $watchlistIds ] )
			->caller( __METHOD__ )->execute();

		if ( $config->get( MainConfigNames::WatchlistExpiry ) ) {
			$dbw->newDeleteQueryBuilder()
				->deleteFrom( 'watchlist_expiry' )
				->where( [ 'we_item' => $watchlistIds ] )
				->caller( __METHOD__ )->execute();
		}

		// Commit changes and remove lock before inserting next job.
		$dbProvider->commitAndWaitForReplication( __METHOD__, $ticket );
		unset( $scopedLock );

		if ( count( $watchlistIds ) === (int)$batchSize ) {
			// Until we get less results than the limit, repeat the same job later.
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
