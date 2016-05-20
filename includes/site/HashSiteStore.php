<?php
/**
 * In-memory implementation of SiteStore.
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

/**
 * In-memory SiteStore implementation, storing sites in an associative array.
 *
 * @author Daniel Kinzler
 * @author Katie Filbert < aude.wiki@gmail.com >
 *
 * @since 1.25
 * @ingroup Site
 */
class HashSiteStore implements SiteStore {

	/**
	 * @var array[]
	 */
	private $siteData = [];

	/**
	 * @var array[]
	 */
	private $idIndex = [];

	/**
	 * @var array[]
	 */
	private $groupIndex = [];

	public function clear() {
		$this->siteData = [];
		$this->idIndex = [];
		$this->groupIndex = [];
	}

	public function addSiteData( array $siteData, $reindex = 'reindex' ) {
		$this->siteData = array_merge_recursive( $this->siteData, $siteData );

		if ( $reindex === 'reindex' ) {
			$this->updateIndex();
		}
	}

	private function updateIndex() {
		foreach ( $this->siteData as $id => $site ) {
			if ( !isset( $site['ids'] ) ) {
				$this->indexAllIds( $id, $site['ids'] );
			}
			if ( !isset( $site['groups'] ) ) {
				$this->indexAllGroups( $id, $site['groups'] );
			}
		}
	}

	private function indexAllIds( $globalId, array $allIds ) {
		$allIds[ Site::ID_GLOBAL ][] = $globalId;

		foreach ( $allIds as $scope => $idsInScope ) {
			foreach ( $idsInScope as $id ) {
				$this->idIndex[$scope][$id] = $globalId;
			}
		}
	}

	private function indexAllGroups( $globalId, array $allGroups ) {
		foreach ( $allGroups as $scope => $groupsInScope ) {
			foreach ( $groupsInScope as $group ) {
				$this->groupIndex[$scope][$group][] = $globalId;
			}
		}
	}

	/**
	 * @param string $scope
	 * @param string $id
	 *
	 * @return string|null
	 */
	private function resolveId( $scope, $id ) {
		if ( !isset( $this->idIndex[$scope][$id] ) ) {
			return null;
		}

		return $this->idIndex[$scope][$id];
	}

	/**
	 * @param string $scope
	 * @param string $name
	 *
	 * @return string[]
	 */
	private function resolveGroup( $scope, $name ) {
		if ( !isset( $this->idIndex[$scope][$name] ) ) {
			return [];
		}

		return $this->idIndex[$scope][$name];
	}

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
	public function getSite( $id, $scope = 'global' ) {
		$id = $this->resolveId( $scope, $id );

		if ( $id === null ) {
			return null;
		}

		return $this->newSiteObject( $this->siteData[$id] );
	}

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
	public function hasSite( $id, $scope = 'global' ) {
		return $this->resolveId( $scope, $id ) !== null;
	}

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
	public function getSites( array $ids = null ) {
		if ( $ids === null ) {
			$ids = array_keys( $this->siteData );site
		}

		$sites = new SiteList();
		foreach( $ids as $id ) {
			$sites[] = $this->getSite( $id );
		}

		return $sites;
	}

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
	public function listSitesInGroup( $group, $scope = 'family' ) {
		$ids = $this->resolveGroup( $scope, $group );
		return $this->getSites( $ids );
	}

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
	public function listGroups( $scope = 'family' ) {
		// TODO: Implement listGroups() method.
	}

	/**
	 * Saves the provided site.
	 *
	 * @since 1.21
	 *
	 * @param Site $site
	 *
	 * @return bool Success indicator
	 */
	public function saveSite( Site $site ) {
		// TODO: Implement saveSite() method.
	}

	/**
	 * Saves the provided sites.
	 *
	 * @since 1.21
	 *
	 * @param Site[] $sites
	 *
	 * @return bool Success indicator
	 */
	public function saveSites( array $sites ) {
		// TODO: Implement saveSites() method.
	}
}
