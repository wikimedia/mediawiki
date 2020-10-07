<?php

namespace MediaWiki\Api\Hook;

use ApiQueryWatchlist;
use WatchedItem;

/**
 * @stable to implement
 * @ingroup Hooks
 */
interface ApiQueryWatchlistExtractOutputDataHook {
	/**
	 * Use this hook to extract row data for ApiQueryWatchlist.
	 *
	 * @since 1.35
	 *
	 * @param ApiQueryWatchlist $module
	 * @param WatchedItem $watchedItem
	 * @param array $recentChangeInfo Array of recent change info data
	 * @param array &$vals Associative array of data to be output for the row
	 * @return bool|void True or no return value to continue or false to abort
	 */
	public function onApiQueryWatchlistExtractOutputData( $module, $watchedItem,
		$recentChangeInfo, &$vals
	);
}
