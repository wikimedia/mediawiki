<?php

namespace MediaWiki\Hook;

/**
 * This is a hook handler interface, see docs/Hooks.md.
 * Use the hook name "RecentChangesPurgeRows" to register handlers implementing this interface.
 *
 * @deprecated since 1.45 use RecentChangesPurgeQuery
 * @ingroup Hooks
 */
interface RecentChangesPurgeRowsHook {
	/**
	 * This hook is called by RecentChangesUpdateJob when expired recentchanges rows are deleted, after
	 * deleting those rows but within the same database transaction.
	 *
	 * @since 1.35
	 * @param \stdClass[] $rows Deleted rows as an array of recentchanges row objects (with up to
	 *   $wgUpdateRowsPerQuery items)
	 * @return void This hook must not abort, it must return no value
	 */
	public function onRecentChangesPurgeRows( $rows ): void;
}
