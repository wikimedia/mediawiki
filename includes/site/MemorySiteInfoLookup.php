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
 *
 * @license GNU GPL v2+
 */

/**
 * Provides an in-memory implementation of the SiteLookup and SiteInfoLookup services.
 *
 * @since 1.29
 */
class MemorySiteInfoLookup implements SiteLookup, SiteInfoLookup {

	/**
	 * @var array
	 */
	private $sites;

	/**
	 * @var array
	 */
	private $ids;

	/**
	 * @var array
	 */
	private $groups;

	/**
	 * @var SiteList|null
	 */
	private $siteList = null;

	/**
	 * MemorySiteInfoLookup constructor.
	 *
	 * @param array $sites [ globalid => [ name1 => value1, name2 => value2, ... ], ... ]
	 * @param array $ids [ scope => [ id1 => globalid1, id2 => globalid2, ... ], ... ]
	 * @param array $groups [ scope => [ name1 => [ member1, member2, ... ], name2 => ... ], ... ]
	 */
	public function __construct( array $sites, array $ids = [], array $groups = [] ) {
		$this->sites = $sites;
		$this->ids = $ids;
		$this->groups = $groups;
	}

	/**
	 * @param string $scope
	 * @param string $group
	 *
	 * @return string[] a list of site ids
	 */
	public function getGroupMembers( $scope, $group ) {
		if ( !isset( $this->groups[$scope][$group] ) ) {
			return [];
		}

		return $this->groups[$scope][$group];
	}

	/**
	 * @param string $scope
	 * @param string $siteId global siteId
	 *
	 * @return string the group the site is a member of in the given scope, or null if none
	 */
	public function getSiteMembership( $scope, $siteId ) {
		if ( !isset( $this->groups[$scope] ) ) {
			return null;
		}

		foreach ( $this->groups[$scope] as $group => $members ) {
			if ( in_array( $siteId, $members ) ) {
				return $group;
			}
		}

		return null;
	}

	/**
	 * @param string $scope
	 * @param string $id
	 *
	 * @return string the global site ID associated with $id in $scope, or null if none.
	 */
	public function resolveSiteId( $scope, $id ) {
		if ( $scope === SiteInfoLookup::GLOBAL_ID ) {
			return $id;
		}

		if ( !isset( $this->ids[$scope][$id] ) ) {
			return [];
		}

		return $this->ids[$scope][$id];
	}

	/**
	 * @param string $scope
	 * @param string $siteId global siteId
	 *
	 * @return string[] IDs of the given site in the given scope.
	 */
	public function getSiteIds( $scope, $siteId ) {
		if ( $scope === SiteInfoLookup::GLOBAL_ID ) {
			return $siteId;
		}

		if ( !isset( $this->ids[$scope] ) ) {
			return null;
		}

		$ids = [];

		// XXX: isn't there a conveniance function for this?
		foreach ( $this->ids[$scope] as $id => $associatedSiteId ) {
			if ( $siteId === $associatedSiteId ) {
				$ids[] = $id;
			}
		}

		return $ids;
	}

	/**
	 * @param string $siteId A canonical global site ID
	 * @param string $name The name of the desired property
	 * @param mixed $default
	 *
	 * @return mixed the property value, or null if the property is undefined.
	 */
	public function getSiteProperty( $siteId, $name, $default = null ) {
		if ( preg_match( '/^(.+)-id$/', $name, $m ) ) {
			$scope = $m[1];
			$value = $this->getSiteIds( $scope, $siteId );
			return $value !== null ? $value : $default;
		}

		if ( preg_match( '/^(.+)-group$/', $name, $m ) ) {
			$scope = $m[1];
			$value = $this->getSiteMembership( $scope, $siteId );
			return $value !== null ? $value : $default;
		}

		if ( !isset( $this->sites[$siteId][$name] ) ) {
			return $default;
		}

		return $this->sites[$siteId][$name];
	}

	/**
	 * Returns the site with provided global id, or null if there is no such site.
	 *
	 * @since 1.25
	 *
	 * @param string $globalId
	 *
	 * @return Site|null
	 */
	public function getSite( $globalId ) {
		$siteType = $this->getSiteProperty( $globalId, SiteInfoLookup::SITE_TYPE_PROP, SiteInfoLookup::SITE_TYPE_UNKNOWN );
		$site = Site::newForType( $siteType );

		//$globalIds = $this->getSiteIds( SiteInfoLookup::GLOBAL_ID, $globalId );

		$site->setGlobalId( $globalId );
		$site->setForward( $this->getSiteProperty( $globalId, SiteInfoLookup::ALLOW_FORWARD_PROP, false ) );
		$site->setGroup( $this->getSiteMembership( SiteInfoLookup::FAMILY_GROUP, $globalId ) );
		$site->setLanguageCode( $this->getSiteProperty( $globalId, SiteInfoLookup::CONTENT_LANG_PROP ) );
		//$site->setSource( $this->getSiteProperty( $globalId, 'source' ) );

		foreach ( $this->getSiteIds( SiteInfoLookup::INTERLANGUAGE_ID, $globalId ) as $identifier ) {
			$site->addLocalId( Site::ID_EQUIVALENT, $identifier );
		}

		foreach ( $this->getSiteIds( SiteInfoLookup::INTERWIKI_ID, $globalId ) as $identifier ) {
			$site->addLocalId( Site::ID_INTERWIKI, $identifier );
		}

		$paths = [];
		$paths[Site::PATH_LINK] = $this->getSiteProperty( SiteInfoLookup::PAGE_URL_PROP, $globalId );
		$paths[MediaWikiSite::PATH_PAGE] = $this->getSiteProperty( SiteInfoLookup::PAGE_URL_PROP, $globalId );
		$paths[MediaWikiSite::PATH_FILE] = $this->getSiteProperty( SiteInfoLookup::RESOURCE_URL_PROP, $globalId );

		foreach ( $paths as $type => $pth ) {
			$site->setPath( $type, $pth );
		}

		return $site;
	}

	/**
	 * Returns a list of all sites.
	 *
	 * @since 1.25
	 *
	 * @return SiteList
	 */
	public function getSites() {
		if ( !$this->siteList ) {
			$this->siteList = new SiteList();

			foreach ( array_keys( $this->sites ) as $id ) {
				$sites[] = $this->getSite( $id );
			}
		}

		return $this->siteList;
	}
}
