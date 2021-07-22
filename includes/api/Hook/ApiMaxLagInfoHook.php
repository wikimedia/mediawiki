<?php

namespace MediaWiki\Api\Hook;

/**
 * This is a hook handler interface, see docs/Hooks.md.
 * Use the hook name "ApiMaxLagInfo" to register handlers implementing this interface.
 *
 * @stable to implement
 * @ingroup Hooks
 */
interface ApiMaxLagInfoHook {
	/**
	 * This hook is called when lag information is being requested via API. Use this hook
	 * to override lag information. Generally a hook function should only replace
	 * $lagInfo if the new $lagInfo['lag'] is greater than the current $lagInfo['lag'].
	 *
	 * @since 1.35
	 *
	 * @param array &$lagInfo Maximum lag information array. Fields in the array are:
	 *   - `lag`: number of seconds of lag
	 *   - `host`: host name on which the lag exists
	 *   - `type`: an indication of the type of lag. For example: "db" for database
	 *      replication lag or "jobqueue" for job queue size converted to pseudo-seconds
	 *
	 *   You can also add more fields that are returned to the user in the API response.
	 * @return void This hook must not abort, it must return no value
	 */
	public function onApiMaxLagInfo( &$lagInfo ): void;
}
