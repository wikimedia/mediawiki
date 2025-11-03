<?php

namespace MediaWiki\RecentChanges\Hook;

use Wikimedia\Rdbms\SelectQueryBuilder;

/**
 * This is a hook handler interface, see docs/Hooks.md.
 * Use the hook name "RecentChangesPurgeQuery" to register handlers implementing this interface.
 *
 * @stable to implement
 * @ingroup Hooks
 */
interface RecentChangesPurgeQueryHook {
	/**
	 * This hook is called by RecentChangesUpdateJob before recentchanges rows
	 * are deleted. It can be used to add fields to the query, and to register
	 * a callback function which will be called when the query is done, after
	 * the rows have been deleted, but before the end of the transaction.
	 *
	 * @since 1.45
	 * @param SelectQueryBuilder $query A query on the recentchanges table to
	 *   which fields and joins can be added.
	 * @param callable[] &$callbacks Out parameter specifying a list of
	 *   callbacks. The handler should append to the list to receive the result
	 *   of the potentially modified query. The callbacks will be called with
	 *   one parameter: a \Wikimedia\Rdbms\IResultWrapper. The callbacks will
	 *   not be called if there were no rows in the result set.
	 * @return void This hook must not abort, it must return no value
	 */
	public function onRecentChangesPurgeQuery( $query, &$callbacks );

}
