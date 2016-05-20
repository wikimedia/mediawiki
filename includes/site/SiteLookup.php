<?php

/**
 * Interface for service objects providing a lookup of Site objects.
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
 */
interface SiteLookup {

	/**
	 * Returns the site with provided id in the given scope,
	 * or null if there no such site is known.
	 *
	 * @since 1.25
	 *
	 * @param string $id The ID within the given scope.
	 * @param string $scope The ID's scope.  See the Site::ID_XXX constants for possible values.
	 *        Parameter supported since 1.28.
	 *
	 * @return Site|null
	 */
	public function getSite( $id, $scope = Site::ID_GLOBAL );

	/**
	 * Checks if a site is known.
	 *
	 * @since 1.28
	 *
	 * @param string $id The ID within the given scope.
	 * @param string $scope The ID's scope. See the Site::ID_XXX constants for possible values.
	 *
	 * @return bool True if a site with the given $id in the given
	 * $scope is know, false otherwise.
	 */
	public function hasSite( $id, $scope = Site::ID_GLOBAL );

	/**
	 * Returns a list of all sites.
	 *
	 * @since 1.25
	 *
	 * @param string[]|null $ids Global IDs of the sites to return. null for all.
	 *        Parameter supported since 1.28.
	 *
	 * @return SiteList
	 */
	public function getSites( array $ids = null );

	/**
	 * List IDs of sites in a group.
	 *
	 * @example for the "wikipedia" group in the "family" scope, sites listed may be
	 *        "enwiki", "dewiki", "zhwiki", etc.
	 *
	 * @param string $group The group ID (within the given scope).
	 * @param string $scope The group ID's scope. See the Site::GROUP_XXX constants
	 *        for possible values.
	 *
	 * @return string[] Global IDs of the sites in the given group, for use with getSies().
	 */
	public function listSitesInGroup( $group, $scope = Site::GROUP_FAMILY );

	/**
	 * Lists names of groups in a scope.
	 *
	 * @example for the "family" scope, the groups "wikipedia", "wiktionary", and
	 *          "wikisource" may exist.
	 *
	 * @param string $scope The group ID's scope. See the Site::GROUP_XXX constants
	 *        for possible values.
	 *
	 * @return string[] Names of groups in the given scope.
	 */
	public function listGroups( $scope = Site::GROUP_FAMILY );

}
