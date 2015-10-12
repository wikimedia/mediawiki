<?php

/**
 * Represents the site configuration of a wiki.
 * Holds a list of sites (ie SiteList) and takes care
 * of retrieving and caching site information when appropriate.
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
 * @license GNU GPL v2+
 * @author Jeroen De Dauw < jeroendedauw@gmail.com >
 *
 * @node SiteSQLStore is a backwards compatibility stub. Despite the name, no guarantee is given
 * regarding the backend used for storing site information.
 */
class SiteSQLStore extends CachingSiteStore {

	/**
	 * Backwards compatibility alias for MediaWikiSites::getSiteStore();
	 *
	 * @since 1.21
	 * @deprecated 1.25 Use MediaWikiServices::getSiteStore() instead.
	 *
	 * @param null $sitesTable Unused
	 * @param null $cache
	 *
	 * @throws InvalidArgumentException if $sitesTable or $cache is not null.
	 * @return SiteStore A SiteStore; no guarantee is given regarding the storage backend used.
	 */
	public static function newInstance( $sitesTable = null, $cache = null ) {
		if ( $sitesTable !== null ) {
			throw new InvalidArgumentException(
				__METHOD__ . ': $sitesTable parameter is unused and must be null'
			);
		}

		if ( $cache !== null ) {
			throw new InvalidArgumentException(
				__METHOD__ . ': $cache parameter is unused and must be null'
			);
		}

		return MediaWikiServices::getInstance()->getSiteStore();
	}

}
