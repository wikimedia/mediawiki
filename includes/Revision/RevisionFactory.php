<?php
/**
 * Service for constructing revision objects.
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

use MWException;
use Title;

/**
 * Service for constructing revision objects.
 *
 * @since 1.31
 * @since 1.32 Renamed from MediaWiki\Storage\RevisionFactory
 *
 * @note This was written to act as a drop-in replacement for the corresponding
 *       static methods in Revision.
 */
interface RevisionFactory {

	/**
	 * Constructs a new RevisionRecord based on the given associative array following the MW1.29
	 * database convention for the Revision constructor.
	 *
	 * MCR migration note: this replaces Revision::newFromRow
	 *
	 * @deprecated since 1.31. Use a MutableRevisionRecord instead.
	 *
	 * @param array $fields
	 * @param int $queryFlags Flags for lazy loading behavior, see IDBAccessObject::READ_XXX.
	 * @param Title|null $title
	 *
	 * @return MutableRevisionRecord
	 * @throws MWException
	 */
	public function newMutableRevisionFromArray( array $fields, $queryFlags = 0, Title $title = null );

	/**
	 * Constructs a RevisionRecord given a database row and content slots.
	 *
	 * MCR migration note: this replaces Revision::newFromRow for rows based on the
	 * revision, slot, and content tables defined for MCR since MW1.31.
	 *
	 * @param object $row A query result row as a raw object.
	 *        Use RevisionStore::getQueryInfo() to build a query that yields the required fields.
	 * @param int $queryFlags Flags for lazy loading behavior, see IDBAccessObject::READ_XXX.
	 * @param Title|null $title
	 *
	 * @return RevisionRecord
	 */
	public function newRevisionFromRow( $row, $queryFlags = 0, Title $title = null );

	/**
	 * Make a fake revision object from an archive table row. This is queried
	 * for permissions or even inserted (as in Special:Undelete)
	 *
	 * MCR migration note: this replaces Revision::newFromArchiveRow
	 *
	 * @param object $row A query result row as a raw object.
	 *        Use RevisionStore::getArchiveQueryInfo() to build a query that yields the
	 *        required fields.
	 * @param int $queryFlags Flags for lazy loading behavior, see IDBAccessObject::READ_XXX.
	 * @param Title|null $title
	 * @param array $overrides An associative array that allows fields in $row to be overwritten.
	 *        Keys in this array correspond to field names in $row without the "ar_" prefix, so
	 *        $overrides['user'] will override $row->ar_user, etc.
	 *
	 * @return RevisionRecord
	 */
	public function newRevisionFromArchiveRow(
		$row,
		$queryFlags = 0,
		Title $title = null,
		array $overrides = []
	);

}

/**
 * Retain the old class name for backwards compatibility.
 * @deprecated since 1.32
 */
class_alias( RevisionFactory::class, 'MediaWiki\Storage\RevisionFactory' );
