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

/**
 * A page that may exist in the database.
 *
 * @since 1.31
 *
 * @note PageIdentity objects are intended to represent pages that can exist es editable content
 * in the database, but they technically can also be used to represent SpecialPages and interwiki
 * links. This was done to allow Title to implement PageIdentity directly.
 */
interface PageIdentity extends LinkTarget {

	const UNKNOWN_DOMAIN = '<unknown>';
	const LOCAL_DOMAIN = false;

	/**
	 * Get the domain ID of the wiki database this PageIdentity belongs to.
	 *
	 * For interwiki links that are not bound to a database domain, this wil return UNKNOWN_DOMAIN.
	 * For the local database, it may return false, for historical reasons.
	 *
	 * @see IDatabase::getDomainID()
	 *
	 * @return string|false the wiki's domain ID, or false if it belongs to the local wiki's
	 * database.
	 */
	public function getDomainID();

	/**
	 * Get the page ID, if it exist.
	 *
	 * @return int|false the page's ID in the page table, or 0 if the page does not (but could)
	 *         exist in the database indicated by getDomainID(), or false if this page cannot be
	 *         created in the database indicated by getDomainID().
	 */
	public function getPageID();

	/**
	 * Can this page be created in the database indicated by getDomainId().
	 *
	 * This will always return false for SpecialPages and interwiki links that
	 * other titles that can exist as link targets, but cannot be written into a known database.
	 *
	 * @return bool
	 */
	public function canExist();

}
