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
	/**
	 * Returns a two dimensional array containing a user's watchlist groups in the following format:
	 * array(
	 *     id => array( name, perm )
	 * )
	 *
	 * @param $user int
	 *
	 * @return array
	 */
	public static function getGroups( $user ){
		$dbr = wfGetDB( DB_SLAVE );
		$res = $dbr->select( 'watchlist_groups', array( 'wg_id', 'wg_name', 'wg_perm' ),
			array( 'wg_user' => $user), __METHOD__
		);
		$groups = array();
		foreach ( $res as $s ) {
			$groups[$s->wg_id] = array( $s->wg_name, $s->wg_perm );
		}
		return $groups;
	}

	/**
	 * Gets the group ID for a title in a user's watchlist
	 * @param $title Title
	 * @param $user int
	 *
	 * @return int
	 */
	public static function getGroupFromUserTitle( $title, $user ){
		$dbr = wfGetDB( DB_SLAVE );
		$res = $dbr->selectRow( 'watchlist', array( 'wl_group' ),
			 array( 'wl_title' => $title->getDBkey(), 'wl_namespace' => $title->getNamespace(), 'wl_user' => $user ), __METHOD__
		);
		$gid = intval( $res->wl_group );

		if($gid == 0){
			$gname = wfMsg( 'watchlistedit-nogroup' );
		} else {
			$res = $dbr->selectRow( 'watchlist_groups', array( 'wg_name' ),
				array( 'wg_id' => $gid ), __METHOD__
			);
			$gname = $res->wg_name;
		}
		$group = array( $gname, $gid );
		return $group;
	}

	/**
	 * Gets the group ID for a group name in a user's watchlist
	 * @param $name string
	 * @param $user int
	 *
	 * @return int
	 */
	public static function getGroupFromName( $name, $user ){
		$dbr = wfGetDB( DB_SLAVE );
		$res = $dbr->selectRow( 'watchlist_groups', array( 'wg_id' ),
			array( 'wg_name' => $name, 'wg_user' => $user), __METHOD__
		);
		if(isset($res->wg_id)){
			return intval($res->wg_id);
		}
		else{
			return false;
		}
	}

	/**
	 * Gets the group name for a group ID in a user's watchlist
	 * @param $id int
	 *
	 * @return String
	 */
	public static function getGroupNameFromID( $id ){
		$dbr = wfGetDB( DB_SLAVE );
		$res = $dbr->selectField( 'watchlist_groups', 'wg_name',
			array( 'wg_id' => $id ), __METHOD__
		);
		return $res;
	}

	/**
	 * Checks a group name and returns the name without invalid characters if it is otherwise okay.
	 * Returns false if is a reserved keyword.
	 * @param $name string: the group name
	 *
	 * @return mixed
	 */
	public static function checkValidGroupName( $name ){
		$name = preg_replace( '/[^a-zA-Z0-9]+/', '', $name );
		if( $name == '' || $name == 'clear' || $name == 'raw' || $name == 'edit'
			|| $name == '0' || $name == '1' || $name == '2' ){
			return false;
		}
		return $name;
	}

	/**
	 * Existance/permission-checking method for watchlist groups.
	 * @param $user int
	 * @param $name string: the name of the new group
	 * @param $id int: the group ID
	 * @param $perm bool: if true, the group must also be viewable by the given user to return true
	 *
	 * @return bool
	 */
	public static function isGroup( $user, $id = 0, $perm = false ){
		$dbw = wfGetDB( DB_SLAVE );
		$res = $dbw->selectRow( 'watchlist_groups', array( 'wg_name', 'wg_user', 'wg_perm' ), array( 'wg_id' => $id, ), __METHOD__ );
		return $res && ( !$perm || ( $perm && $res->wg_perm ) || $user == $res->wg_user );
	}

	/**
	 * Mutators
	 **/

	/**
	 * Changes the group associated with titles in a watchlist
	 * @param $titles array: titles to be regrouped
	 * @param $group int: the group ID of the new desired group
	 * @param $user int
	 */
	public static function regroupTitles( $titles, $group, $user ){
		$dbw = wfGetDB( DB_MASTER );
		$lb = new LinkBatch( $titles );
		$where_titles = $lb->constructSet( 'wl', $dbw );
		$res = $dbw->update( 'watchlist', array( 'wl_group' => $group ), array( $where_titles,
				 'wl_user' => $user ), __METHOD__ );
	}

	/**
	 * Create a watchlist group
	 * @param $name string: the name of the new group
	 * @param $user int
	 *
	 * @return bool
	 */
	public static function createGroup( $name, $context ){
		$dbw = wfGetDB( DB_MASTER );
		$res = $dbw->insert( 'watchlist_groups', array( 'wg_name' => $name, 'wg_user' => $user ), __METHOD__ );
		return $res;
	}

	/**
	 * Rename a watchlist group
	 * @param $group int: the group ID
	 * @param $name string: the new name of the group
	 * @param $user int
	 *
	 * @return bool
	 */
	public static function renameGroup( $group, $name, $context ){
		$dbw = wfGetDB( DB_MASTER );
		$res = $dbw->update( 'watchlist_groups', array( 'wg_name' => $name ),
			array( 'wg_id' => $group, 'wg_user' => $user ), __METHOD__ );
		return $res;
	}

	/**
	 * Change the permissions for a watchlist group
	 * @param $group int: the group ID
	 * @param $perm int: 0 (private) or 1 (public)
	 * @param $user int
	 *
	 * @return bool
	 */
	public static function changePerm( $group, $perm, $context ){
		$dbw = wfGetDB( DB_MASTER );
		$res = $dbw->update( 'watchlist_groups', array( 'wg_perm' => $user ),
			array( 'wg_id' => $group, 'wg_user' => $user ), __METHOD__ );
		return $res;
	}

	/**
	 * Delete a watchlist group
	 * @param $group int: the group ID
	 * @param $user int
	 *
	 * @return bool
	 */
	public static function deleteGroup( $group, $user ){
		$dbw = wfGetDB( DB_MASTER );
		$dbw->update( 'watchlist', array( 'wl_group' => 0 ), array( 'wl_group' => $group ), __METHOD__ );
		$res = $dbw->delete( 'watchlist_groups', array( 'wg_id' => $group, 'wg_user' => $user ), __METHOD__ );
		return $res;
	}
}