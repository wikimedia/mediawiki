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

namespace MediaWiki\Storage;

use MediaWiki\Linker\LinkTarget;
use MWException;
use Title;
use User;
use Wikimedia\Rdbms\IDatabase;

/**
 * Service for constructing revision objects.
 *
 * @since 1.30
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
	 * @deprecated since 1.30. Use a MutableRevisionRecord instead.
	 *
	 * @param array $fields
	 * @param Title|null $title
	 *
	 * @return RevisionRecord
	 * @throws MWException
	 */
	public function newRevisionFromArray_1_29( array $fields, Title $title = null );

	/**
	 * Constructs a RevisionRecord given a database row and content slots.
	 *
	 * MCR migration note: this replaces Revision::newFromRow for rows based on the
	 * revision, slot, and content tables defined for MCR since MW1.30.
	 *
	 * @param object $row A database row from the revision table, as a raw object
	 * @param Title|null $title The Title of the page the revision is associated with
	 *
	 * @return RevisionRecord
	 */
	public function newRevisionFromRow( $row, Title $title = null );

}