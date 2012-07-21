<?php
/**
 * Accessor and mutator for watchlist groups.
 *
 * Note that (for security) mutators require the User object, while accessors just take user IDs.
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

class WatchlistGroup {
	private $user;
	private $id;
	private $groups = array();

	/**
	 * Create a WatchlistGroup object with the given user and title
	 * @param $user User: the user that owns the watchlist
	 * @return WatchlistGroup object
	 */
	public static function newFromUser( $user ) {
		$dbr = wfGetDB( DB_SLAVE );
		$res = $dbr->select( 'watchlist_groups', array( 'wg_id', 'wg_name', 'wg_perm' ),
			array( 'wg_user' => $user->getId() ), __METHOD__
		);
		$groups = array();
		foreach ( $res as $s ) {
			$groups[$s->wg_id] = array( $s->wg_name, $s->wg_perm );
		}

		$wg = new WatchlistGroup;
		$wg->user = $user;
		$wg->id = $user->getId();
		$wg->groups = $groups;
		return $wg;
	}

	/**
	 * Returns a two dimensional array containing a user's watchlist groups in the following format:
	 * array(
	 *     id => array( name, perm )
	 * )
	 *
	 * @param $include_nogroup bool
	 *
	 * @return array
	 */
	public function getGroups( $include_nogroup = false ) {
		$groups = $this->groups;
		// Include the "ungrouped" group
		if( $include_nogroup ) {
			$groups[0] = array( wfMsg( 'watchlistedit-nogroup' ), 0 );
		}
		return $groups;
	}

	/**
	 * Gets the group ID for a group name in a user's watchlist
	 * @param $name string
	 *
	 * @return bool|string False on failure
	 */
	public function getGroupFromName( $name ) {
		$dbr = wfGetDB( DB_SLAVE );
		if( $name == 'none' ){
			return 0;
		}
		foreach ($this->groups as $gid => $ginfo) {
			if( $name == $ginfo[0] ) {
				return intval( $gid );
			}
		}
		return false;
	}

	/**
	 * Gets the group name for a group ID in a user's watchlist
	 * @param $id int
	 *
	 * @return String
	 */
	public function getGroupNameFromID( $id ) {
		return $this->groups[$id][0];
	}

	/**
	 * Checks a group name and returns the name without invalid characters if it is otherwise okay.
	 * Returns false if is a reserved keyword.
	 * @param $name string: the group name
	 *
	 * @return bool|string False on failure
	 */
	public static function checkValidGroupName( $name ) {
		$name = preg_replace( '/[^a-zA-Z0-9]+/', '', $name );
		if( $name == '' || $name == 'clear' || $name == 'raw' || $name == 'edit'
			|| $name == '0' || $name == '1' || $name == '2' ) {
			return false;
		}
		return $name;
	}

	/**
	 * Existance/permission-checking method for watchlist groups.
	 * @param $id int: the group ID
	 * @param $perm bool: if true, the group must also be viewable by the given user to return true
	 *
	 * @return bool
	 */
	public function isGroup( $id, $perm = false ) {
		$dbw = wfGetDB( DB_SLAVE );
		$res = $dbw->selectRow( 'watchlist_groups', array( 'wg_name', 'wg_user', 'wg_perm' ), array( 'wg_id' => $id, ), __METHOD__ );
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
	 * @param $titles array: titles to be regrouped
	 * @param $group int: the group ID of the new desired group
	 */
	public function regroupTitles( $titles, $group ) {
		$dbw = wfGetDB( DB_MASTER );
		$lb = new LinkBatch( $titles );
		$where_titles = $lb->constructSet( 'wl', $dbw );
		$res = $dbw->update( 'watchlist', array( 'wl_group' => $group ),
			array( 'wl_user' => $user, $where_titles ), __METHOD__ );
	}

	/**
	 * Create a watchlist group
	 * @param $name string: the name of the new group
	 *
	 * @return bool
	 */
	public function createGroup( $name ) {
		$dbw = wfGetDB( DB_MASTER );
		$res = $dbw->insert( 'watchlist_groups', array( 'wg_name' => $name, 'wg_user' => $this->id ), __METHOD__ );
		return $res;
	}

	/**
	 * Rename a watchlist group
	 * @param $group int: the group ID
	 * @param $name string: the new name of the group
	 *
	 * @return bool
	 */
	public function renameGroup( $group, $name ) {
		$dbw = wfGetDB( DB_MASTER );
		$res = $dbw->update( 'watchlist_groups', array( 'wg_name' => $name ),
			array( 'wg_id' => $group, 'wg_user' => $this->id ), __METHOD__ );
		return $res;
	}

	/**
	 * Change the permissions for a watchlist group
	 * @param $group int: the group ID
	 * @param $perm int: 0 (private) or 1 (public)
	 *
	 * @return bool
	 */
	public function changePerm( $group, $perm ) {
		$dbw = wfGetDB( DB_MASTER );
		$res = $dbw->update( 'watchlist_groups', array( 'wg_perm' => $perm ),
			array( 'wg_id' => $group, 'wg_user' => $this->id ), __METHOD__ );
		return $res;
	}

	/**
	 * Delete a watchlist group
	 * @param $group int: the group ID
	 *
	 * @return bool
	 */
	public function deleteGroup( $group ) {
		$dbw = wfGetDB( DB_MASTER );
		$dbw->update( 'watchlist', array( 'wl_group' => 0 ), array( 'wl_group' => $group ), __METHOD__ );
		$res = $dbw->delete( 'watchlist_groups', array( 'wg_id' => $group, 'wg_user' => $this->id ), __METHOD__ );
		return $res;
	}
}