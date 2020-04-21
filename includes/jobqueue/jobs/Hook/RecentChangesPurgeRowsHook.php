<?php

namespace MediaWiki\Hook;

/**
 * @stable for implementation
 * @ingroup Hooks
 */
interface RecentChangesPurgeRowsHook {
	/**
	 * Called when old recentchanges rows are purged, after
	 * deleting those rows but within the same transaction.
	 *
	 * @since 1.35
	 *
	 * @param ?mixed $rows The deleted rows as an array of recentchanges row objects (with up to
	 *   $wgUpdateRowsPerQuery items).
	 * @return bool|void True or no return value to continue or false to abort
	 */
	public function onRecentChangesPurgeRows( $rows );
}
