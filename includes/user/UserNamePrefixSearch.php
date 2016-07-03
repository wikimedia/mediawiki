<?php
/**
 * Prefix search of user names.
 *
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

/**
 * Handles searching prefixes of user names
 *
 * @since 1.27
 */
class UserNamePrefixSearch {

	/**
	 * Do a prefix search of user names and return a list of matching user names.
	 *
	 * @param string|User $audience The string 'public' or a user object to show the search for
	 * @param string $search
	 * @param int $limit
	 * @param int $offset How many results to offset from the beginning
	 * @return array Array of strings
	 */
	public static function search( $audience, $search, $limit, $offset = 0 ) {
		$user = User::newFromName( $search );

		$dbr = wfGetDB( DB_SLAVE );
		$prefix = $user ? $user->getName() : '';
		$tables = [ 'user' ];
		$cond = [ 'user_name ' . $dbr->buildLike( $prefix, $dbr->anyString() ) ];
		$joinConds = [];

		// Filter out hidden user names
		if ( $audience === 'public' || !$audience->isAllowed( 'hideuser' ) ) {
			$tables[] = 'ipblocks';
			$cond['ipb_deleted'] = [ 0, null ];
			$joinConds['ipblocks'] = [ 'LEFT JOIN', 'user_id=ipb_user' ];
		}

		$res = $dbr->selectFieldValues(
			$tables,
			'user_name',
			$cond,
			__METHOD__,
			[
				'LIMIT' => $limit,
				'ORDER BY' => 'user_name',
				'OFFSET' => $offset
			],
			$joinConds
		);

		return $res === false ? [] : $res;
	}
}
