<?php

/**
 * Interface for lists of Site objects.
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
interface SiteList extends Countable, Traversable, Serializable, ArrayAccess {

	/**
	 * Returns all the global site identifiers.
	 * Optionally only those belonging to the specified group.
	 *
	 * @since 1.21
	 *
	 * @return array
	 */
	public function getGlobalIdentifiers();

	/**
	 * Returns if the list contains the site with the provided global site identifier.
	 *
	 * @param string $globalSiteId
	 *
	 * @return boolean
	 */
	public function hasSite( $globalSiteId );

	/**
	 * Returns the Site with the provided global site identifier.
	 * The site needs to exist, so if not sure, call hasGlobalId first.
	 *
	 * @since 1.21
	 *
	 * @param string $globalSiteId
	 *
	 * @return Site
	 */
	public function getSite( $globalSiteId );

	/**
	 * Removes the site with the specified global site identifier.
	 * The site needs to exist, so if not sure, call hasGlobalId first.
	 *
	 * @since 1.21
	 *
	 * @param string $globalSiteId
	 */
	public function removeSite( $globalSiteId );

	/**
	 * Returns if the list contains the site with the provided site id.
	 *
	 * @param integer $id
	 *
	 * @return boolean
	 */
	public function hasInternalId( $id );

	/**
	 * Returns the Site with the provided site id.
	 * The site needs to exist, so if not sure, call has first.
	 *
	 * @since 1.21
	 *
	 * @param integer $id
	 *
	 * @return Site
	 */
	public function getSiteByInternalId( $id );

	/**
	 * Removes the site with the specified site id.
	 * The site needs to exist, so if not sure, call has first.
	 *
	 * @since 1.21
	 *
	 * @param integer $id
	 */
	public function removeSiteByInternalId( $id );

	/**
	 * Sets a site in the list. If the site was not there,
	 * it will be added. If it was, it will be updated.
	 *
	 * @since 1.21
	 *
	 * @param Site $site
	 */
	public function setSite( Site $site );

	/**
	 * Returns if the site list contains no sites.
	 *
	 * @since 1.21
	 *
	 * @return boolean
	 */
	public function isEmpty();

}