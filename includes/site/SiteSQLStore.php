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
 * @deprecated 1.27 use DBSiteStore and CachingSiteStore instead.
 *
 * @license GNU GPL v2+
 * @author Daniel Kinzler
 */
class SiteSQLStore {

	/**
	 * @since 1.21
	 * @deprecated 1.27 use MediaWikiServices::getSiteStore() or MediaWikiServices::getSiteLookup() instead.
	 *
	 * @param null $sitesTable IGNORED
	 * @param BagOStuff|null $cache IGNORED
	 *
	 * @return SiteStore
	 */
	public static function newInstance( $sitesTable = null, BagOStuff $cache = null ) {
		return \MediaWiki\MediaWikiServices::getInstance()->getSiteStore();
	}

}
