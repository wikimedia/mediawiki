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
	 * @param string $search
	 * @param int $limit
	 * @param array $namespaces Used if query is not explicitly prefixed
	 * @param int $offset How many results to offset from the beginning
	 * @return array Array of strings
	 */
	public function search( $search, $limit, $offset = 0 ) {
		$user = User::newFromName( $search );

		$prefix = $user ? $user->getName() : '';
		$dbr = wfGetDB( DB_SLAVE );
		$res = $dbr->select( 'user',
			'user_name',
			array(
				'user_name ' . $dbr->buildLike( $prefix, $dbr->anyString() )
			),
			__METHOD__,
			array(
				'LIMIT' => $limit,
				'ORDER BY' => 'user_name',
				'OFFSET' => $offset
			)
		);

		$userNames = array();
		foreach ( $res as $row ) {
			$userNames[] = $row->user_name;
		}
		return $userNames;
	}
}
