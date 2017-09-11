<?php
/**
 * Value object representing basic information about an existing regular
 * page within MediaWiki.
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

/**
 * Represents basic information about an existing regular page within MediaWiki.
 * It cannot be used to represent special pages or non-existing pages. It may
 * be used to represent pages on another wiki, if sufficient information is
 * known about that page.
 *
 * A PageRecord corresponds logically, but not necessarily literally, to a
 * row in the page table.
 *
 * @note This is designed as a drop-in replacement for the Title objects,
 *       representing the information stored about an existing page in
 *       the database.
 *
 * @note Since PageRecord can only represent existing pages, Title cannot
 *       directly implement page record, because Title may also represent
 *       non-existing pages.
 *
 * @since 1.30
 */
interface PageRecord {

	/**
	 * Get a LinkTarget object representing this page.
	 *
	 * @return LinkTarget|null
	 */
	public function getLinkTarget();

	/**
	 * Returns the page title in DB key form (with underscores instead of spaces).
	 *
	 * @return string
	 */
	public function getDBkey();

	/**
	 * Returns the pages namespace ID.
	 *
	 * @return int
	 */
	public function getNamespace();

	/**
	 * Returns true if the title is inside the specified namespace.
	 *
	 * Please make use of this instead of comparing to getNamespace()
	 * This function is much more resistant to changes we may make
	 * to namespaces than code that makes direct comparisons.
	 *
	 * @param int $ns The namespace
	 *
	 * @return bool
	 */
	public function inNamespace( $ns );

	/**
	 * Returns restriction types for the current Title
	 *
	 * @return array Applicable restriction types
	 */
	public function getRestrictionTypes();

	/**
	 * Is this page "semi-protected" - the *only* protection levels are listed
	 * in $wgSemiprotectedRestrictionLevels?
	 *
	 * @param string $action Action to check (default: edit)
	 *
	 * @return bool
	 */
	public function isSemiProtected( $action = 'edit' );

	/**
	 * Is there a version of this page in the deletion archive?
	 *
	 * @return int The number of archived revisions
	 */
	public function isDeleted();

	/**
	 * Get the article ID for this page.
	 *
	 * @return int
	 */
	public function getArticleID();

	/**
	 * Is this an article that is a redirect page?
	 *
	 * @return bool
	 */
	public function isRedirect();

	/**
	 * Check if this is a new page
	 *
	 * @return bool
	 */
	public function isNew();

	/**
	 * What is the length of this page?
	 *
	 * @return int
	 */
	public function getLength();

	/**
	 * What is the ID of the latest revision of this page?
	 *
	 * @return int
	 */
	public function getLatestRevID();

	/**
	 * Compare with another PageRecord. Two page records are considered equal
	 * if they have the same ArticleID, and they belong to the same wiki.
	 *
	 * @param PageRecord $page
	 *
	 * @return bool
	 */
	public function equals( PageRecord $page );

	/**
	 * Get the last touched timestamp
	 *
	 * @return string
	 */
	public function getTouched();
}