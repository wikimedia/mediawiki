<?php

namespace MediaWiki\Api\Hook;

// phpcs:disable Generic.Files.LineLength -- Remove this after doc review
/**
 * @stable for implementation
 * @ingroup Hooks
 */
interface ApiQueryWatchlistPrepareWatchedItemQueryServiceOptionsHook {
	/**
	 * Populate the options
	 * to be passed from ApiQueryWatchlist to WatchedItemQueryService.
	 *
	 * @since 1.35
	 *
	 * @param ?mixed $module ApiQueryWatchlist instance
	 * @param ?mixed $params Array of parameters, as would be returned by $module->extractRequestParams()
	 * @param ?mixed &$options Array of options for WatchedItemQueryService::getWatchedItemsWithRecentChangeInfo()
	 * @return bool|void True or no return value to continue or false to abort
	 */
	public function onApiQueryWatchlistPrepareWatchedItemQueryServiceOptions(
		$module, $params, &$options
	);
}
