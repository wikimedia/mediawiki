<?php

/**
 * Job for pruning expired watchlist entries
 *
 * @author Addshore
 * @ingroup JobQueue
 * @since 1.27
 */
class WatchlistUpdateJob extends Job {

	public function __construct( Title $title ) {
		parent::__construct( 'watchlistUpdate', $title, array() );
		$this->removeDuplicates = true;
	}

	/**
	 * @return RecentChangesUpdateJob
	 */
	final public static function newPurgeJob() {
		return new self(
			SpecialPage::getTitleFor( 'Watchlist' )
		);
	}

	/**
	 * Run the job
	 * @return bool Success
	 */
	public function run() {
		$this->purgeExpiredRows();
		return true;
	}

	private function purgeExpiredRows() {
		$lockKey = wfWikiID() . ':watchlist-prune';

		$dbw = wfGetDB( DB_MASTER );
		if ( !$dbw->lockIsFree( $lockKey, __METHOD__ )
			|| !$dbw->lock( $lockKey, __METHOD__, 1 )
		) {
			return; // already in progress
		}

		$batchSize = 100; // avoid slave lag
		$cutoff = $dbw->timestamp( time() );
		do {
			$ids = $dbw->selectFieldValues( 'watchlist',
				'wl_id',
				array(
					'wl_expirytimestamp < ' . $dbw->addQuotes( $cutoff ),
					'wl_expirytimestamp != ' . $dbw->addQuotes( '' )
				),
				__METHOD__,
				array( 'LIMIT' => $batchSize )
			);
			if ( $ids ) {
				$dbw->delete( 'watchlist', array( 'wl_id' => $ids ), __METHOD__ );
			}
			// Commit in chunks to avoid slave lag
			$dbw->commit( __METHOD__, 'flush' );

			if ( count( $ids ) === $batchSize ) {
				// There might be more, so try waiting for slaves
				try {
					wfGetLBFactory()->waitForReplication( array( 'timeout' => 3 ) );
				} catch ( DBReplicationWaitError $e ) {
					// Another job will continue anyway
					break;
				}
			}
		} while ( $ids );

		$dbw->unlock( $lockKey, __METHOD__ );
	}
}
