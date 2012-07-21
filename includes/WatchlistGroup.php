<?php
/**
 * Accessor and mutator for watchlist groups.
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
 * Class representing whatchlist groups.
 *
 * @note mutators require a User object to check permissions, while accessors
 * just take user IDs.
 *
*/
class WatchlistGroup {

	/**
	 * User to whom belong the whatched group of items
	 */
	protected $user;

	/**
	 * Id of the user
	 */
	protected $id;

	/**
	 * Array representing this user watchlists.
	 * Key is the groupID, the value contains the group name and
	 * the permission.
	 *
	 */
	protected $groups = array();

	/**
	 * Constant representing the ID of the general group which is used
	 * whenever an item is watched but not actually assigned to a specifc
	 * group.
	 */
	const DEFAULT_GROUP = 0;

	/**
	 * Create a WatchlistGroup object with the given user and title
	 * @param $user User: the user that owns the watchlist
	 * @return WatchlistGroup object
	 */
	public static function newFromUser( $user ) {
		$dbr = wfGetDB( DB_SLAVE );
		$res = $dbr->select( 'watchlist_groups',
			array( 'wg_id', 'wg_name', 'wg_perm' ),
			array( 'wg_user' => $user->getId() ), __METHOD__
		);
		$groups = array();
		foreach ( $res as $s ) {
			$groups[$s->wg_id] = array( $s->wg_name, $s->wg_perm );
		}

		# Instantiate a represent the whatchlist
		$wg = new WatchlistGroup;
		$wg->user = $user;
		$wg->id = $user->getId();
		$wg->groups = $groups;

		return $wg;
	}

	/**
	 * Returns a two dimensional array containing user's watchlist groups in
	 * the following format:
	 *
	 * @code
	 * array(
	 *     id => array( <group name>, <permission> ),
	 * )
	 * @endcode
	 *
	 * @see $groups
	 *
	 * @param bool $include_nogroup (default false)
	 * @return array of groups
	 */
	public function getGroups( $include_nogroup = false ) {

		$groups = $this->groups;

		// Include the "ungrouped" group
		if( $include_nogroup ) {
			$groups[WatchlistGroup::DEFAULT_GROUP] = array(
				wfMsg( 'watchlistedit-nogroup' ),
				0 /** permission */
			);
		}
		return $groups;
	}

	/**
	 * Gets the group ID for a group name in a user's watchlist
	 *
	 * @param $groupName string
	 * @return bool|string False on failure
	 */
	public function getGroupFromName( $groupName ) {
		if( $groupName == 'none' ){
			return WatchlistGroup::DEFAULT_GROUP;
		}
		foreach ($this->groups as $gid => $ginfo) {
			if( $groupName == $ginfo[0] ) {
				return intval( $gid );
			}
		}
		return false;
	}

	/**
	 * Gets the group name for a group ID in a user's watchlist
	 *
	 * @param $groupId int
	 *
	 * @return String
	 */
	public function getGroupNameFromID( $groupId ) {
		return $this->groups[$groupId][0];
	}

	/**
	 * Checks a group name and returns the name without invalid characters if
	 * it is otherwise okay.
	 * Returns false if is a reserved keyword.
	 *
	 * @param $groupName string: the group name
	 *
	 * @return bool|string False on failure
	 */
	public static function checkValidGroupName( $groupName ) {
		# FIXME this is most probably wrong
		$groupName = preg_replace( '/[^a-zA-Z0-9]+/', '', $name );
		if( $groupName == '' || $groupName == 'clear' || $groupName == 'raw'
			|| $groupName == 'edit' || $groupName == '0' || $groupName == '1'
			|| $groupName == '2'
		) {
			return false;
		}
		return $groupName;
	}

	/**
	 * Existence/permission-checking method for watchlist groups.
	 *
	 * @param $groupId int: the group ID
	 * @param $perm bool: if true, the group must also be viewable by the given
	 * user to return true
	 *
	 * @return bool
	 */
	public function isGroup( $groupId, $perm = false ) {
		$dbw = wfGetDB( DB_SLAVE );
		$res = $dbw->selectRow( 'watchlist_groups',
			array( 'wg_name', 'wg_user', 'wg_perm' ),
			array( 'wg_id' => $groupId, ),
			__METHOD__
		);

		# FIXME this need to be made easier to understand
		return $res && (
			// we're not supposed to check permissions:
			!$perm ||
			// the user has permission:
			( $perm && $res->wg_perm ) ||
			// users can access their own watchlists:
			$this->id == $res->wg_user
		);
	}

	/**
	 * Mutators
	 **/

	/**
	 * Changes the group associated with titles in a watchlist
	 *
	 * @param $titles array: titles to be regrouped
	 * @param $group int: the group ID of the new desired group
	 *
	 * @return bool
	 */
	public function regroupTitles( $titles, $group ) {
		$dbw = wfGetDB( DB_MASTER );
		$lb = new LinkBatch( $titles );
		$where_titles = $lb->constructSet( 'wl', $dbw );
		$res = $dbw->update( 'watchlist',
			array( 'wl_group' => $group ),
			array( 'wl_user' => $this->id,  $where_titles ),
			__METHOD__
		);
		return $res;
	}

	/**
	 * Create a watchlist group.
	 *
	 * @param $groupName string: the name of the new group
	 *
	 * @return bool
	 */
	public function createGroup( $groupName, $user ) {
		$dbw = wfGetDB( DB_MASTER );
		$res = $dbw->insert( 'watchlist_groups',
			array( 'wg_name' => $groupName, 'wg_user' => $user ),
			__METHOD__
		);
		return $res;
	}

	/**
	 * Rename a watchlist group.
	 *
	 * @param $group int: the group ID
	 * @param $newName string: the new name of the group
	 *
	 * @return bool
	 */
	public function renameGroup( $groupId, $newName ) {
		$dbw = wfGetDB( DB_MASTER );
		$res = $dbw->update( 'watchlist_groups',
			array( 'wg_name' => $newName ),
			array( 'wg_id' => $groupId, 'wg_user' => $this->id ),
			__METHOD__
		);
		return $res;
	}

	/**
	 * Change the permissions for a watchlist group
	 *
	 * @param $groupId int: the group ID
	 * @param $perm int: 0 (private) or 1 (public)
	 *
	 * @return bool
	 */
	public function changePerm( $groupId, $perm ) {
		$dbw = wfGetDB( DB_MASTER );
		$res = $dbw->update( 'watchlist_groups',
			array( 'wg_perm' => $perm ),
			array( 'wg_id' => $groupId, 'wg_user' => $this->id ),
			__METHOD__
		);
		return $res;
	}

	/**
	 * Delete a watchlist group
	 *
	 * @param $groupId int: the group ID
	 *
	 * @return bool
	 */
	public function deleteGroup( $groupId ) {
		$dbw = wfGetDB( DB_MASTER );
		# FIXME: this should be wrapped in a transaction
		$dbw->update( 'watchlist',
			array( 'wl_group' => 0 ), array( 'wl_group' => $groupId ),
			__METHOD__
		);
		$res = $dbw->delete( 'watchlist_groups',
			array( 'wg_id' => $groupId, 'wg_user' => $this->id),
			__METHOD__
		);
		return $res;
	}
}
