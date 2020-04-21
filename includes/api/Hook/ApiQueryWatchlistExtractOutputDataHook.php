<?php

namespace MediaWiki\Api\Hook;

/**
 * @stable for implementation
 * @ingroup Hooks
 */
interface ApiQueryWatchlistExtractOutputDataHook {
	/**
	 * Extract row data for ApiQueryWatchlist.
	 *
	 * @since 1.35
	 *
	 * @param ?mixed $module ApiQueryWatchlist instance
	 * @param ?mixed $watchedItem WatchedItem instance
	 * @param ?mixed $recentChangeInfo Array of recent change info data
	 * @param ?mixed &$vals Associative array of data to be output for the row
	 * @return bool|void True or no return value to continue or false to abort
	 */
	public function onApiQueryWatchlistExtractOutputData( $module, $watchedItem,
		$recentChangeInfo, &$vals
	);
}
