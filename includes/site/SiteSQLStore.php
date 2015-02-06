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
 */
class SiteSQLStore extends CachingSiteStore {

	/**
	 * @since 1.21
	 * @deprecated 1.25 Construct a SiteStore instance directly instead.
	 *
	 * @param ORMTable|null $sitesTable
	 * @param BagOStuff|null $cache
	 *
	 * @return SiteStore
	 */
	public static function newInstance( ORMTable $sitesTable = null, BagOStuff $cache = null ) {
		if ( $cache === null ) {
			$cache = wfGetMainCache();
		}

		$siteStore = new DBSiteStore();

		return new static( $siteStore, $cache );
	}

}
