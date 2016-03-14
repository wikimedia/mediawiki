<?php

use MediaWiki\MediaWikiServices;
use Wikimedia\Rdbms\LoadBalancer;

/**
 * Job to clear a users watchlist in batches.
 *
 * @author Addshore
 *
 * @ingroup JobQueue
 * @since 1.30
 */
class ClearUserWatchlistJob extends Job {

	/**
	 * @var LoadBalancer
	 */
	private $loadBalancer;

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

	public function __construct( Title $title = null, array $params ) {
		if ( !array_key_exists( 'batchSize', $params ) ) {
			$params['batchSize'] = 1000;
		}

		parent::__construct(
			'clearUserWatchlist',
			SpecialPage::getTitleFor( 'EditWatchlist', 'clear' ),
			$params
		);

		$this->removeDuplicates = true;
		$this->loadBalancer = MediaWikiServices::getInstance()->getDBLoadBalancer();
	}

	public function run() {
		$userId = $this->params['userId'];
		$maxWatchlistId = $this->params['maxWatchlistId'];

		$dbw = $this->loadBalancer->getConnection( DB_MASTER, [ 'watchlist' ] );

		// Use a named lock so that jobs for this user see each others' changes
		$lockKey = "ClearUserWatchlistJob:$userId";
		$scopedLock = $dbw->getScopedLockAndFlush( $lockKey, __METHOD__, 10 );
		if ( !$scopedLock ) {
			$this->setLastError( "Could not acquire lock '$lockKey'" );
			return false;
		}

		$dbr = $this->loadBalancer->getConnection( DB_REPLICA, [ 'watchlist' ] );

		if ( !$this->loadBalancer->safeWaitForMasterPos( $dbr ) ) {
			$this->setLastError( 'Timed out while waiting for slave to catch up' );
			return false;
		}

		$result = $dbr->select(
			'watchlist',
			[ 'wl_id' ],
			[
				'wl_user' => $userId,
				'wl_id <= ' . $maxWatchlistId
			],
			__METHOD__,
			[
				'ORDER BY' => 'wl_id ASC',
				'LIMIT' => $this->params['batchSize'],
			]
		);

		$this->loadBalancer->reuseConnection( $dbr );

		if ( $result === false ) {
			$this->setLastError( "Failed to select watchlist entries" );
			return false;
		}

		if ( $result->numRows() == 0 ) {
			return true;
		}

		$watchlistIds = [];
		foreach ( $result as $row ) {
			$watchlistIds[] = $row->wl_id;
		}
		$deleteConds = $dbw->makeList( [ 'wl_id' => $watchlistIds ], LIST_OR );
		$result = $dbw->delete( 'watchlist', $deleteConds, __METHOD__ );

		$this->loadBalancer->reuseConnection( $dbw );

		if ( $result === false ) {
			$this->setLastError( "Failed to delete watchlist entries" );
			return false;
		}

		JobQueueGroup::singleton()->push( new self( $this->getTitle(), $this->getParams() ) );

		return true;
	}

	public function getDeduplicationInfo() {
		return [
			'type' => $this->getType(),
			'userId' => $this->params['userId'],
		];
	}
}
