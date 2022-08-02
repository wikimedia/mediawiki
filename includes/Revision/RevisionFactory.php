<?php
/**
 * Service for constructing RevisionRecord objects.
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

namespace MediaWiki\Revision;

use IDBAccessObject;
use MediaWiki\Page\PageIdentity;

/**
 * Service for constructing RevisionRecord objects.
 *
 * @since 1.31
 * @since 1.32 Renamed from MediaWiki\Storage\RevisionFactory
 *
 * @note This was written to act as a drop-in replacement for the corresponding
 *       static methods in the old Revision class (which was later removed in 1.37).
 */
interface RevisionFactory extends IDBAccessObject {

	/**
	 * Constructs a RevisionRecord given a database row and content slots.
	 *
	 * MCR migration note: this replaced Revision::newFromRow for rows based on the
	 * revision, slot, and content tables defined for MCR since MW1.31.
	 *
	 * @param \stdClass $row A query result row as a raw object.
	 *        Use getQueryInfo() to build a query that yields the required fields.
	 * @param int $queryFlags Flags for lazy loading behavior, see IDBAccessObject::READ_XXX.
	 * @param PageIdentity|null $page A page object for the revision.
	 *
	 * @return RevisionRecord
	 */
	public function newRevisionFromRow(
		$row,
		$queryFlags = self::READ_NORMAL,
		PageIdentity $page = null
	);

	/**
	 * Make a fake RevisionRecord object from an archive table row. This is queried
	 * for permissions or even inserted (as in Special:Undelete).
	 *
	 * The user ID and user name may optionally be supplied using the aliases
	 * ar_user and ar_user_text (the names of fields which existed before
	 * MW 1.34).
	 *
	 * MCR migration note: this replaced Revision::newFromArchiveRow
	 *
	 * @param \stdClass $row A query result row as a raw object.
	 *        Use getArchiveQueryInfo() to build a query that yields the required fields.
	 * @param int $queryFlags Flags for lazy loading behavior, see IDBAccessObject::READ_XXX.
	 * @param PageIdentity|null $page
	 * @param array $overrides An associative array that allows fields in $row to be overwritten.
	 *        Keys in this array correspond to field names in $row without the "ar_" prefix, so
	 *        $overrides['user'] will override $row->ar_user, etc.
	 *
	 * @return RevisionRecord
	 */
	public function newRevisionFromArchiveRow(
		$row,
		$queryFlags = self::READ_NORMAL,
		PageIdentity $page = null,
		array $overrides = []
	);

	/**
	 * Return the tables, fields, and join conditions to be selected to create
	 * a new RevisionArchiveRecord object.
	 *
	 * @since 1.37, since 1.31 on RevisionStore
	 *
	 * @return array[] With three keys:
	 *   - tables: (string[]) to include in the `$table` to `IDatabase->select()` or `SelectQueryBuilder::tables`
	 *   - fields: (string[]) to include in the `$vars` to `IDatabase->select()` or `SelectQueryBuilder::fields`
	 *   - joins: (array) to include in the `$join_conds` to `IDatabase->select()` or `SelectQueryBuilder::joinConds`
	 * @phan-return array{tables:string[],fields:string[],joins:array}
	 */
	public function getArchiveQueryInfo();

	/**
	 * Return the tables, fields, and join conditions to be selected to create
	 * a new RevisionStoreRecord object.
	 *
	 * MCR migration note: this replaced Revision::getQueryInfo
	 *
	 * If the format of fields returned changes in any way then the cache key provided by
	 * self::getRevisionRowCacheKey should be updated.
	 *
	 * @since 1.37, since 1.31 on RevisionStore
	 *
	 * @param array $options Any combination of the following strings
	 *  - 'page': Join with the page table, and select fields to identify the page
	 *  - 'user': Join with the user table, and select the user name
	 *
	 * @return array[] With three keys:
	 *  - tables: (string[]) to include in the `$table` to `IDatabase->select()` or `SelectQueryBuilder::tables`
	 *  - fields: (string[]) to include in the `$vars` to `IDatabase->select()` or `SelectQueryBuilder::fields`
	 *  - joins: (array) to include in the `$join_conds` to `IDatabase->select()` or `SelectQueryBuilder::joinConds`
	 * @phan-return array{tables:string[],fields:string[],joins:array}
	 */
	public function getQueryInfo( $options = [] );

	/**
	 * Determine whether the parameter is a row containing all the fields
	 * that RevisionFactory needs to create a RevisionRecord from the row.
	 *
	 * @param mixed $row
	 * @param string $table 'archive' or empty
	 * @return bool
	 */
	public function isRevisionRow( $row, string $table = '' );

}
