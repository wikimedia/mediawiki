<?php

namespace MediaWiki\Api\Hook;

use MediaWiki\Api\ApiQueryWatchlist;

/**
 * This is a hook handler interface, see docs/Hooks.md.
 * Use the hook name "ApiQueryWatchlistPrepareWatchedItemQueryServiceOptions"
 * to register handlers implementing this interface.
 *
 * @stable to implement
 * @ingroup Hooks
 */
interface ApiQueryWatchlistPrepareWatchedItemQueryServiceOptionsHook {
	/**
	 * Use this hook to populate the options to be passed from ApiQueryWatchlist
	 * to WatchedItemQueryService.
	 *
	 * @since 1.35
	 *
	 * @param ApiQueryWatchlist $module
	 * @param array $params Array of parameters, as would be returned by
	 *   $module->extractRequestParams()
	 * @param array &$options Array of options for
	 *   WatchedItemQueryService::getWatchedItemsWithRecentChangeInfo()
	 * @return bool|void True or no return value to continue or false to abort
	 */
	public function onApiQueryWatchlistPrepareWatchedItemQueryServiceOptions(
		$module, $params, &$options
	);
}
