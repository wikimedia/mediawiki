<?php
/**
 * A central user id lookup service implementation
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
 * A CentralIdLookup provider that just uses local IDs. Useful if the wiki
 * isn't part of a cluster or you're using shared user tables.
 *
 * @note Shared user table support expects that all wikis involved have
 *  $wgSharedDB and $wgSharedTables set, and that all wikis involved in the
 *  sharing are listed in $wgLocalDatabases, and that no wikis not involved in
 *  the sharing are listed in $wgLocalDatabases.
 * @since 1.27
 */
class LocalIdLookup extends CentralIdLookup {

	public function isAttached( User $user, $wikiId = null ) {
		global $wgSharedDB, $wgSharedTables, $wgLocalDatabases;

		// If the user has no ID, it can't be attached
		if ( !$user->getId() ) {
			return false;
		}

		// Easy case, we're checking locally
		if ( $wikiId === null || WikiMap::isCurrentWikiId( $wikiId ) ) {
			return true;
		}

		// Assume that shared user tables are set up as described above, if
		// they're being used at all.
		return $wgSharedDB !== null &&
			in_array( 'user', $wgSharedTables, true ) &&
			in_array( $wikiId, $wgLocalDatabases, true );
	}

	public function lookupCentralIds(
		array $idToName, $audience = self::AUDIENCE_PUBLIC, $flags = self::READ_NORMAL
	) {
		if ( !$idToName ) {
			return [];
		}

		$audience = $this->checkAudience( $audience );
		list( $index, $options ) = DBAccessObjectUtils::getDBOptions( $flags );
		$db = wfGetDB( $index );

		$tables = [ 'user' ];
		$fields = [ 'user_id', 'user_name' ];
		$where = [
			'user_id' => array_map( 'intval', array_keys( $idToName ) ),
		];
		$join = [];
		if ( $audience && !$audience->isAllowed( 'hideuser' ) ) {
			$tables[] = 'ipblocks';
			$join['ipblocks'] = [ 'LEFT JOIN', 'ipb_user=user_id' ];
			$fields[] = 'ipb_deleted';
		}

		$res = $db->select( $tables, $fields, $where, __METHOD__, $options, $join );
		foreach ( $res as $row ) {
			$idToName[$row->user_id] = empty( $row->ipb_deleted ) ? $row->user_name : '';
		}

		return $idToName;
	}

	public function lookupUserNames(
		array $nameToId, $audience = self::AUDIENCE_PUBLIC, $flags = self::READ_NORMAL
	) {
		if ( !$nameToId ) {
			return [];
		}

		$audience = $this->checkAudience( $audience );
		list( $index, $options ) = DBAccessObjectUtils::getDBOptions( $flags );
		$db = wfGetDB( $index );

		$tables = [ 'user' ];
		$fields = [ 'user_id', 'user_name' ];
		$where = [
			'user_name' => array_map( 'strval', array_keys( $nameToId ) ),
		];
		$join = [];
		if ( $audience && !$audience->isAllowed( 'hideuser' ) ) {
			$tables[] = 'ipblocks';
			$join['ipblocks'] = [ 'LEFT JOIN', 'ipb_user=user_id' ];
			$where[] = 'ipb_deleted = 0 OR ipb_deleted IS NULL';
		}

		$res = $db->select( $tables, $fields, $where, __METHOD__, $options, $join );
		foreach ( $res as $row ) {
			$nameToId[$row->user_name] = (int)$row->user_id;
		}

		return $nameToId;
	}
}
