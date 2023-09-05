<?php
/**
 * Purge expired watchlist items, looping through 500 at a time.
 */

use MediaWiki\MainConfigNames;

require_once __DIR__ . '/Maintenance.php';

class PurgeExpiredWatchlistItems extends Maintenance {

	public function __construct() {
		parent::__construct();
		$this->addDescription( 'Removes expired items from the watchlist and watchlist_expiry tables.' );
		$this->setBatchSize( 500 );
	}

	/**
	 * @inheritDoc
	 */
	public function execute() {
		// Make sure watchlist expiring is enabled.
		if ( !$this->getServiceContainer()->getMainConfig()->get( MainConfigNames::WatchlistExpiry ) ) {
			$this->error( "Watchlist expiry is not enabled. Set `\$wgWatchlistExpiry = true;` to enable." );
			return false;
		}

		// Loop through 500 entries at a time and delete them.
		$watchedItemStore = $this->getServiceContainer()->getWatchedItemStore();
		$count = $watchedItemStore->countExpired();
		$this->output( $count . " expired watchlist entries found.\n" );
		if ( $count === 0 ) {
			// None found to delete.
			return true;
		}
		while ( $watchedItemStore->countExpired() > 0 ) {
			$watchedItemStore->removeExpired( $this->getBatchSize(), true );
		}

		// Report success.
		$this->output( "All expired entries purged.\n" );
		return true;
	}
}

$maintClass = PurgeExpiredWatchlistItems::class;
require_once RUN_MAINTENANCE_IF_MAIN;
