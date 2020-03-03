<?php

namespace MediaWiki\Hook;

/**
 * @stable for implementation
 * @ingroup Hooks
 */
interface SpecialWatchlistGetNonRevisionTypesHook {
	/**
	 * Called when building sql query for
	 * SpecialWatchlist. Allows extensions to register custom values they have
	 * inserted to rc_type so they can be returned as part of the watchlist.
	 *
	 * @since 1.35
	 *
	 * @param ?mixed &$nonRevisionTypes array of values in the rc_type field of recentchanges table
	 * @return bool|void True or no return value to continue or false to abort
	 */
	public function onSpecialWatchlistGetNonRevisionTypes( &$nonRevisionTypes );
}
