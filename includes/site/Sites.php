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
 * @licence GNU GPL v2+
 * @author Jeroen De Dauw < jeroendedauw@gmail.com >
 */
class Sites {

	/**
	 * @since 1.21
	 * @var SiteList|false
	 */
	protected $sites = false;

	/**
	 * Constructor.
	 *
	 * @since 1.21
	 */
	protected function __construct() {}

	/**
	 * Returns an instance of Sites.
	 *
	 * @since 1.21
	 *
	 * @return Sites
	 */
	public static function singleton() {
		static $instance = false;

		if ( $instance === false ) {
			$instance = new static();
		}

		return $instance;
	}

	/**
	 * Factory for creating new site objects.
	 *
	 * @since 1.21
	 *
	 * @param string|false $globalId
	 *
	 * @return Site
	 */
	public static function newSite( $globalId = false ) {
		/**
		 * @var Site $site
		 */
		$site = SitesTable::singleton()->newRow( array(), true );

		if ( $globalId !== false ) {
			$site->setGlobalId( $globalId );
		}

		return $site;
	}

	/**
	 * Returns a list of all sites. By default this site is
	 * fetched from the cache, which can be changed to loading
	 * the list from the database using the $useCache parameter.
	 *
	 * @since 1.21
	 *
	 * @param string $source either 'cache' or 'recache'
	 *
	 * @return SiteList
	 */
	public function getSites( $source = 'cache' ) {
		if ( $source === 'cache' ) {
			if ( $this->sites === false ) {
				$cache = wfGetMainCache();
				$sites = $cache->get( 'sites-cache' );

				if ( is_object( $sites ) ) {
					$this->sites = $sites;
				}
				else {
					$this->loadSites();
				}
			}
		}
		else {
			$this->loadSites();
		}

		return $this->sites;
	}

	/**
	 * Returns a list of sites in the given group. Calling getGroup() on any of
	 * the sites in the resulting SiteList shall return $group.
	 *
	 * @since 1.21
	 *
	 * @param string $group th group to get.
	 *
	 * @return SiteList
	 */
	public function getSiteGroup( $group ) {
		$sites = self::getSites();

		$siteGroup = new SiteArray();

		/* @var Site $site */
		foreach ( $sites as $site ) {
			if ( $site->getGroup() == $group ) {
				$siteGroup->append( $site );
			}
		}

		return $siteGroup;
	}

	/**
	 * Fetches the site from the database and loads them into the sites field.
	 *
	 * @since 1.21
	 */
	protected function loadSites() {
		$this->sites = new SiteArray( SitesTable::singleton()->select() );

		// Batch load the local site identifiers.
		$dbr = wfGetDB( SitesTable::singleton()->getReadDb() );

		$ids = $dbr->select(
			'site_identifiers',
			array(
				'si_site',
				'si_type',
				'si_key',
			),
			array(),
			__METHOD__
		);

		foreach ( $ids as $id ) {
			if ( $this->sites->hasInternalId( $id->si_site ) ) {
				$site = $this->sites->getSiteByInternalId( $id->si_site );
				$site->addLocalId( $id->si_type, $id->si_key );
				$this->sites->setSite( $site );
			}
		}

		$cache = wfGetMainCache();
		$cache->set( 'sites-cache', $this->sites );
	}

	/**
	 * Returns the site with provided global id, or false if there is no such site.
	 *
	 * @since 1.21
	 *
	 * @param string $globalId
	 * @param string $source
	 *
	 * @return Site|false
	 */
	public function getSite( $globalId, $source = 'cache' ) {
		if ( $source === 'cache' && $this->sites !== false ) {
			return $this->sites->hasSite( $globalId ) ? $this->sites->getSite( $globalId ) : false;
		}

		return SitesTable::singleton()->selectRow( null, array( 'global_key' => $globalId ) );
	}

}
