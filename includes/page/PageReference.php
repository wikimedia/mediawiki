<?php
/**
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

namespace MediaWiki\Page;

use MediaWiki\DAO\WikiAwareEntity;

/**
 * Interface for objects (potentially) representing a page that can be viewable and linked to
 * on a wiki. This includes special pages.
 *
 * The identity of any PageReference object is defined by the
 * namespace, the dbkey, and the wiki ID.
 * If the wiki ID is self::LOCAL, the identity is relative to the local wiki.
 *
 * @note For compatibility with the Title class, PageReference instances
 *   may for represent things that are not viewable pages, such as interwiki links
 *   and section links. This is intended to change in the future.
 *
 * @note Instances of Title shall be the only instances of PageReference that are not
 *   viewable pages. Other classes implementing PageReference must not permit an empty DB key.
 *   The idea is that once Title has been removed, all PageReference are then viewable pages.
 *
 * @note Code that deserializes instances of PageReference must ensure that the original
 *   meaning of the "local" Wiki ID is preserved if the PageReference originated on
 *   another wiki.
 *
 * @stable to type
 *
 * @since 1.37
 */
interface PageReference extends WikiAwareEntity {

	/**
	 * Get the ID of the wiki this page belongs to.
	 *
	 * @return string|false The wiki's logical name,
	 *         or self::LOCAL to indicate the local wiki.
	 */
	public function getWikiId();

	/**
	 * Returns the page's namespace number.
	 *
	 * The value returned by this method should represent a valid namespace,
	 * but this cannot be guaranteed in all cases.
	 *
	 * @return int
	 */
	public function getNamespace(): int;

	/**
	 * Get the page title in DB key form.
	 *
	 * @note This may return a string starting with a hash, if the PageReference represents
	 *       the target of a block or unblock operation. This is due to the way the block target
	 *       is represented in the logging table. This is intended to change in the future.
	 *
	 * @note This may return an empty string, if this PageReference is a Title that represents
	 *       a relative section link. This is intended to change in the future.
	 *
	 * @return string
	 */
	public function getDBkey(): string;

	/**
	 * Checks whether the given PageReference refers to the same page as this PageReference.
	 *
	 * Two PageReference instances are considered to refer to the same page if
	 * they belong to the same wiki, and have the same namespace and DB key.
	 *
	 * @param PageReference $other
	 *
	 * @return bool
	 */
	public function isSamePageAs( PageReference $other ): bool;

	/**
	 * Returns an informative human readable unique representation of the page identity,
	 * for use as a cache key and for logging and debugging.
	 *
	 * @return string
	 */
	public function __toString(): string;

}
