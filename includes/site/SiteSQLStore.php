<?php

/**
 * Dummy class for accessing the global SiteStore instance.
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
 * @since 1.21
 *
 * @file
 * @ingroup Site
 *
 * @license GPL-2.0-or-later
 * @author Daniel Kinzler
 */
class SiteSQLStore {

	/**
	 * Returns the global SiteStore instance. This is a relict of the first implementation
	 * of SiteStore, and is kept around for compatibility.
	 *
	 * @note This does not return an instance of SiteSQLStore!
	 *
	 * @since 1.21
	 * @deprecated since 1.27 use MediaWikiServices::getSiteStore()
	 *             or MediaWikiServices::getSiteLookup() instead.
	 *
	 * @param null $sitesTable IGNORED
	 * @param BagOStuff|null $cache IGNORED
	 *
	 * @return SiteStore
	 */
	public static function newInstance( $sitesTable = null, BagOStuff $cache = null ) {
		if ( $sitesTable !== null ) {
			throw new InvalidArgumentException(
				__METHOD__ . ': $sitesTable parameter is unused and must be null'
			);
		}

		// NOTE: we silently ignore $cache for now, since some existing callers
		// specify it. If we break compatibility with them, we could just as
		// well just remove this class.

		return \MediaWiki\MediaWikiServices::getInstance()->getSiteStore();
	}

}
