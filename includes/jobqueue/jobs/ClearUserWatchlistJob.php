<?php

/**
 * Job to clear a users watchlist in batches.
 *
 * @todo once the wl_id field has been introduced this job should use it!
 *
 * @author Addshore
 *
 * @ingroup JobQueue
 * @since 1.27
 */
class ClearUserWatchlistJob extends Job {

	/**
	 * @var LoadBalancer
	 */
	private $loadBalancer;

	public static function newForUser( User $user ) {
		return new self( null, [ 'userId' => $user->getId() ] );
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
		$this->loadBalancer = wfGetLB();
	}

	public function run() {
		$userId = $this->params['userId'];

		$dbw = $this->loadBalancer->getConnection( DB_MASTER, [ 'watchlist' ] );

		// Use a named lock so that jobs for this page see each others' changes
		$lockKey = "ClearUserWatchlistJob:$userId";
		$scopedLock = $dbw->getScopedLockAndFlush( $lockKey, __METHOD__, 10 );
		if ( !$scopedLock ) {
			$this->setLastError( "Could not acquire lock '$lockKey'" );
			return false;
		}

		$dbr = $this->loadBalancer->getConnection( DB_REPLICA, [ 'watchlist' ] );

		if ( !$this->loadBalancer->safeWaitForMasterPos( $dbr ) ) {
			$this->setLastError( "Timed out while waiting for slave to catch up" );
			return false;
		}

		$result = $dbr->select(
			'watchlist',
			[ 'wl_namespace', 'wl_title' ],
			[ 'wl_user' => $userId, ],
			__METHOD__,
			[
				'ORDER BY' => 'wl_namespace, wl_title ASC',
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

		$deleteConds = [];
		foreach ( $result as $row ) {
			$deleteConds[] = $dbw->makeList(
				[
					'wl_namespace' => $row->wl_namespace,
					'wl_title' => $row->wl_title,
					'wl_user' => $userId,
				],
				LIST_AND
			);
		}

		$result = $dbw->delete(
			'watchlist',
			$dbw->makeList( $deleteConds, LIST_OR ),
			__METHOD__
		);

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
