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

class WatchlistGroup {
	public static function getGroups( $context ){
		$dbr = wfGetDB( DB_SLAVE );
		$res = $dbr->select( 'watchlist_groups', array( 'wg_id', 'wg_name' ),
			array( 'wg_user' => $context->getUser()->getId()), __METHOD__ 
		);
		$groups = array();
		foreach ( $res as $s ) {
			$groups[$s->wg_id] = $s->wg_name;
		}
		return $groups;
	}

	public static function getGroupFromUserTitle( $title, $context ){
		$dbr = wfGetDB( DB_SLAVE );
		$res = $dbr->selectRow( 'watchlist', array( 'wl_group' ),
			array( 'wl_title' => $title, 'wl_user' => $context->getUser()->getId()), __METHOD__ 
		);
		$gid = intval( $res->wl_group );

		if($gid == 0){
			$gname = 'None';
		} else {
			$res = $dbr->selectRow( 'watchlist_groups', array( 'wg_name' ),
				array( 'wg_id' => $gid ), __METHOD__ 
			);
			$gname = $res->wg_name;
		}
		$group = array( $gname, $gid );
		return $group;
	}

	public static function regroupTitles( $titles, $group, $context ){
		$dbw = wfGetDB( DB_MASTER );
		foreach($titles as $t){
			$titles_escaped[] = Title::newFromText($t)->getDBKey();
		}
		$res = $dbw->update( 'watchlist', array( 'wl_group' => $group ),
			array( 'wl_title' => $titles_escaped, 'wl_user' => $context->getUser()->getId() ), __METHOD__ );
		return $res;
	}

	public static function getNextGroupId( $user ){
		$dbr = wfGetDB( DB_SLAVE );
		$res = $dbr->selectRow( 'watchlist_groups', array( 'MAX(wg_id) as maxid' ),
			array( 'wl_user' => $context->getUser()->getId() ), __METHOD__ 
		);
		return $res->maxid;
	}

	public static function createGroup( $name ){
		$dbw = wfGetDB( DB_MASTER );
		$res = $dbw->insert( 'watchlist_groups', array( 'wg_name' => $name, 'wg_user' => $context->getUser()->getId(),
			'wg_id' => getNextGroupId( $context->getUser()->getId() ) ), array( 'wl_title' => $titles), __METHOD__ );
		return $res;
	}

	public static function renameGroup( $group, $name, $context ){
		$dbw = wfGetDB( DB_MASTER );
		$res = $dbw->update( 'watchlist_groups', array( 'wg_name' => $name ),
			array( 'wg_id' => $group, 'wg_user' => $context->getUser()->getId() ), __METHOD__ );
		return $res;
	}

	public static function deleteGroup( $group, $context ){
		$dbw = wfGetDB( DB_MASTER );
		$res = $dbw->delete( 'watchlist_groups', array( 'wg_id' => $group, 'wg_user' => $context->getUser()->getId() ), __METHOD__ );
		return $res;
	}
}