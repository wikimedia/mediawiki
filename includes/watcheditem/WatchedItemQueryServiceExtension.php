<?php

use Wikimedia\Rdbms\IResultWrapper;
use Wikimedia\Rdbms\IDatabase;

/**
 * Extension mechanism for WatchedItemQueryService
 *
 * @since 1.29
 *
 * @file
 * @ingroup Watchlist
 *
 * @license GPL-2.0-or-later
 */
interface WatchedItemQueryServiceExtension {

	/**
	 * Modify the WatchedItemQueryService::getWatchedItemsWithRecentChangeInfo()
	 * query before it's made.
	 *
	 * @warning Any joins added *must* join on a unique key of the target table
	 *  unless you really know what you're doing.
	 * @param User $user
	 * @param array $options Options from
	 *  WatchedItemQueryService::getWatchedItemsWithRecentChangeInfo()
	 * @param IDatabase $db Database connection being used for the query
	 * @param array &$tables Tables for Database::select()
	 * @param array &$fields Fields for Database::select()
	 * @param array &$conds Conditions for Database::select()
	 * @param array &$dbOptions Options for Database::select()
	 * @param array &$joinConds Join conditions for Database::select()
	 */
	public function modifyWatchedItemsWithRCInfoQuery( User $user, array $options, IDatabase $db,
		array &$tables, array &$fields, array &$conds, array &$dbOptions, array &$joinConds
	);

	/**
	 * Modify the results from WatchedItemQueryService::getWatchedItemsWithRecentChangeInfo()
	 * before they're returned.
	 *
	 * @param User $user
	 * @param array $options Options from
	 *  WatchedItemQueryService::getWatchedItemsWithRecentChangeInfo()
	 * @param IDatabase $db Database connection being used for the query
	 * @param array &$items array of pairs ( WatchedItem $watchedItem, string[] $recentChangeInfo ).
	 *  May be truncated if necessary, in which case $startFrom must be updated.
	 * @param IResultWrapper|bool $res Database query result
	 * @param array|null &$startFrom Continuation value. If you truncate $items, set this to
	 *  [ $recentChangeInfo['rc_timestamp'], $recentChangeInfo['rc_id'] ] from the first item
	 *  removed.
	 */
	public function modifyWatchedItemsWithRCInfo( User $user, array $options, IDatabase $db,
		array &$items, $res, &$startFrom
	);

}
