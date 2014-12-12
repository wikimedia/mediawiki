<?php

/**
 * Interface for looking up a Site or getting a list of Site objects
 * from a SiteStore or cache.
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
 * @since 1.25
 *
 * @file
 * @ingroup Site
 *
 * @license GNU GPL v2+
 * @author Katie Filbert < aude.wiki@gmail.com >
 */
interface SiteLookup {

	/**
	 * Returns the site with provided global id, or null if there is no such site.
	 *
	 * @since 1.25
	 *
	 * @param string $globalId
	 *
	 * @throws OutOfBoundsException
	 * @return Site
	 */
	public function getSite( $globalId );

	/**
	 * Returns a list of all sites as an map, keyed by site global id (e.g. enwiki),
	 * or an empty array if no sites are found.
	 *
	 * An array of $globalIds can be given, optionally, to get multiple sites.
	 * If the parameter is omitted, then all sites in a SiteStore are returned.
	 *
	 * If advanced list indexing of Sites is desired, then one can use the array
	 * returned here to create a SiteList object.
	 *
	 * @since 1.25
	 *
	 * @param string[]|null $globalIds
	 *
	 * @return Site[] with site global id as key.
	 */
	public function getSites( array $globalIds = null );

}
