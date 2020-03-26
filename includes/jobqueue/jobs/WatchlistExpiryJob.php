<?php

use MediaWiki\MediaWikiServices;

class WatchlistExpiryJob extends Job {

	public function __construct( string $command = 'watchlistExpiry', array $params = [] ) {
		parent::__construct( $command, $params );
	}

	/**
	 * Run the job recursively in batches of 100 until there are no more expired items.
	 *
	 * @return bool Always true, to indicate success.
	 */
	public function run() {
		$watchedItemStore = MediaWikiServices::getInstance()->getWatchedItemStore();
		$watchedItemStore->removeExpired( 100 );
		if ( $watchedItemStore->countExpired() ) {
			// If there are still items, add a new job.
			JobQueueGroup::singleton()->push( new static() );
		}
		return true;
	}

}
