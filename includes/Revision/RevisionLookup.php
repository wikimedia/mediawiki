<?php
/**
 *  Service for looking up page revisions.
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
use MediaWiki\Linker\LinkTarget;
use MediaWiki\Page\PageIdentity;

/**
 * Service for looking up page revisions.
 *
 * @note This was written to act as a drop-in replacement for the corresponding
 *       static methods in the old Revision class (which was later removed in 1.37).
 *
 * @since 1.31
 * @since 1.32 Renamed from MediaWiki\Storage\RevisionLookup
 */
interface RevisionLookup extends IDBAccessObject {

	/**
	 * Load a page revision from a given revision ID number.
	 * Returns null if no such revision can be found.
	 *
	 * MCR migration note: this replaced Revision::newFromId
	 *
	 * $flags include:
	 *
	 * @param int $id
	 * @param int $flags bit field, see IDBAccessObject::READ_XXX
	 * @param PageIdentity|null $page The page the revision belongs to.
	 *        Providing the page may improve performance.
	 *
	 * @return RevisionRecord|null
	 */
	public function getRevisionById( $id, $flags = 0, PageIdentity $page = null );

	/**
	 * Load either the current, or a specified, revision
	 * that's attached to a given link target. If not attached
	 * to that link target, will return null.
	 *
	 * MCR migration note: this replaced Revision::newFromTitle
	 *
	 * @param LinkTarget|PageIdentity $page Calling with LinkTarget is deprecated since 1.36
	 * @param int $revId (optional)
	 * @param int $flags bit field, see IDBAccessObject::READ_XXX
	 * @return RevisionRecord|null
	 */
	public function getRevisionByTitle( $page, $revId = 0, $flags = 0 );

	/**
	 * Load either the current, or a specified, revision
	 * that's attached to a given page ID.
	 * Returns null if no such revision can be found.
	 *
	 * MCR migration note: this replaced Revision::newFromPageId
	 *
	 * @param int $pageId
	 * @param int $revId (optional)
	 * @param int $flags bit field, see IDBAccessObject::READ_XXX
	 * @return RevisionRecord|null
	 */
	public function getRevisionByPageId( $pageId, $revId = 0, $flags = 0 );

	/**
	 * Load the revision for the given title with the given timestamp.
	 * WARNING: Timestamps may in some circumstances not be unique,
	 * so this isn't the best key to use.
	 *
	 * MCR migration note: this replaced Revision::loadFromTimestamp
	 *
	 * @param LinkTarget|PageIdentity $page Calling with LinkTarget is deprecated since 1.36
	 * @param string $timestamp
	 * @param int $flags Bitfield (optional) include:
	 *      RevisionLookup::READ_LATEST: Select the data from the primary DB
	 *      RevisionLookup::READ_LOCKING: Select & lock the data from the primary DB
	 *      Default: RevisionLookup::READ_NORMAL
	 * @return RevisionRecord|null
	 */
	public function getRevisionByTimestamp(
		$page,
		string $timestamp,
		int $flags = RevisionLookup::READ_NORMAL
	): ?RevisionRecord;

	/**
	 * Get previous revision for this title
	 *
	 * MCR migration note: this replaced Revision::getPrevious
	 *
	 * @param RevisionRecord $rev
	 * @param int $flags (optional) $flags include:
	 *      IDBAccessObject::READ_LATEST: Select the data from the primary DB
	 *
	 * @return RevisionRecord|null
	 */
	public function getPreviousRevision( RevisionRecord $rev, $flags = 0 );

	/**
	 * Get next revision for this title
	 *
	 * MCR migration note: this replaced Revision::getNext
	 *
	 * @param RevisionRecord $rev
	 * @param int $flags (optional) $flags include:
	 *      IDBAccessObject::READ_LATEST: Select the data from the primary DB
	 *
	 * @return RevisionRecord|null
	 */
	public function getNextRevision( RevisionRecord $rev, $flags = 0 );

	/**
	 * Get rev_timestamp from rev_id, without loading the rest of the row.
	 *
	 * MCR migration note: this replaced Revision::getTimestampFromId
	 *
	 * @param int $id
	 * @param int $flags
	 * @return string|false False if not found
	 * @since 1.34 (present earlier in RevisionStore)
	 */
	public function getTimestampFromId( $id, $flags = 0 );

	/**
	 * Load a revision based on a known page ID and current revision ID from the DB
	 *
	 * This method allows for the use of caching, though accessing anything that normally
	 * requires permission checks (aside from the text) will trigger a small DB lookup.
	 *
	 * MCR migration note: this replaced Revision::newKnownCurrent
	 *
	 * @param PageIdentity $page the associated page
	 * @param int $revId current revision of this page
	 *
	 * @return RevisionRecord|false Returns false if missing
	 */
	public function getKnownCurrentRevision( PageIdentity $page, $revId = 0 );

	/**
	 * Get the first revision of the page.
	 *
	 * @since 1.35
	 * @param LinkTarget|PageIdentity $page Calling with LinkTarget is deprecated since 1.36
	 * @param int $flags bit field, see IDBAccessObject::READ_* constants.
	 * @return RevisionRecord|null
	 */
	public function getFirstRevision(
		$page,
		int $flags = IDBAccessObject::READ_NORMAL
	): ?RevisionRecord;

}
