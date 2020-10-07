<?php

namespace MediaWiki\Hook;

/**
 * @stable to implement
 * @ingroup Hooks
 */
interface SpecialWatchlistGetNonRevisionTypesHook {
	/**
	 * This hook is called when building the SQL query for SpecialWatchlist.
	 *
	 * It allows extensions to register custom values they have
	 * inserted to rc_type so they can be returned as part of the watchlist.
	 *
	 * @since 1.35
	 *
	 * @param int[] &$nonRevisionTypes Array of values in the rc_type field of
	 *   the recentchanges table
	 * @return bool|void True or no return value to continue or false to abort
	 */
	public function onSpecialWatchlistGetNonRevisionTypes( &$nonRevisionTypes );
}
