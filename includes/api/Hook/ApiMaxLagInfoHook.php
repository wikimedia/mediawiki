<?php

namespace MediaWiki\Api\Hook;

/**
 * @stable for implementation
 * @ingroup Hooks
 */
interface ApiMaxLagInfoHook {
	/**
	 * When lag information is being requested via API. Use this to
	 * override lag information. Generally a hook function should only replace
	 * $lagInfo if the new $lagInfo['lag'] is greater than the current $lagInfo['lag'].
	 *
	 * @since 1.35
	 *
	 * @param ?mixed &$lagInfo Maximum lag information array. Fields in the array are:
	 *   'lag' is the number of seconds of lag.
	 *   'host' is the host name on which the lag exists.
	 *   'type' is an indication of the type of lag,
	 *     e.g. "db" for database replication lag or "jobqueue" for job queue size
	 *     converted to pseudo-seconds.
	 *   It is possible to add more fields and they will be returned to the user in
	 *   the API response.
	 * @return bool|void This hook must not abort, it must return true or null.
	 */
	public function onApiMaxLagInfo( &$lagInfo );
}
