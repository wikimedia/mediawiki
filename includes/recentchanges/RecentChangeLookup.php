<?php
/**
 * @license GPL-2.0-or-later
 * @file
 */

namespace MediaWiki\RecentChanges;

/**
 * @since 1.45
 */
interface RecentChangeLookup {
	/**
	 * Get a recent change by its ID
	 *
	 * @param int $rcid The rc_id value to retrieve
	 * @return RecentChange|null Null if no such recent change exists
	 */
	public function getRecentChangeById( int $rcid ): ?RecentChange;

	/**
	 * Get the first recent change matching some specific conditions
	 *
	 * @param array $conds Array of conditions
	 * @param string $fname Override the method name in profiling/logs
	 * @param bool $fromPrimary Whether to fetch from the primary database
	 * @return RecentChange|null
	 */
	public function getRecentChangeByConds(
		array $conds,
		string $fname = __METHOD__,
		bool $fromPrimary = false
	): ?RecentChange;

	/**
	 * Get the rc_source values for events that are not replicated from elsewhere
	 *
	 * @return string[]
	 */
	public function getPrimarySources(): array;

	/**
	 * Check if a recent change is from a primary source
	 *
	 * @param RecentChange $rc
	 * @return bool
	 */
	public function isFromPrimarySource( RecentChange $rc ): bool;
}
