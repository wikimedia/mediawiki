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
	}

	public function run() {
		$userId = $this->params['userId'];
		$maxWatchlistId = $this->params['maxWatchlistId'];

		$this->loadBalancer = MediaWikiServices::getInstance()->getDBLoadBalancer();

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
				'LIMIT' => $this->params['batchSize'],
			]
		);

		if ( $watchlistIds === false ) {
			$this->setLastError( "Failed to select watchlist entries" );
			return false;
		}

		if ( count( $watchlistIds ) == 0 ) {
			return true;
		}

		$dbw->delete( 'watchlist', [ 'wl_id' => $watchlistIds ], __METHOD__ );

		if ( count( $watchlistIds ) == $this->params['batchSize'] ) {
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
