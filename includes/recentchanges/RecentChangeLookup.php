<?php
/**
 * This program is free software; you can redistribute it and/or modify
 * it under the terms of the GNU General Public License as published by
 * the Free Software Foundation; either version 2 of the License, or
 * (at your option) any later version.
 *
 * This program is distributed in the hope that it will be useful,
 * but WITHOUT ANY WARRANTY; without even the implied warranty of
 * MERCHANTABILITY or FITNESS FOR A PARTICULAR PURPOSE. See the
 * GNU General Public License for more details.
 *
 * You should have received a copy of the GNU General Public License along
 * with this program; if not, write to the Free Software Foundation, Inc.,
 * 51 Franklin Street, Fifth Floor, Boston, MA 02110-1301, USA.
 * http://www.gnu.org/copyleft/gpl.html
 *
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
	public function getRecentChangeById( $rcid );

	/**
	 * Get the first recent change matching some specific conditions
	 *
	 * @param array $conds Array of conditions
	 * @param string $fname Override the method name in profiling/logs
	 * @param bool $fromPrimary Whether to fetch from the primary database
	 * @return RecentChange|null
	 */
	public function getRecentChangeByConds( $conds, $fname = __METHOD__, $fromPrimary = false );
}
