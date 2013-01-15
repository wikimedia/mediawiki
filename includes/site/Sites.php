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
class Sites {

	/**
	 * Factory for creating new site objects.
	 *
	 * @since 1.21
	 * @deprecated
	 *
	 * @param string|boolean false $globalId
	 *
	 * @return Site
	 */
	public static function newSite( $globalId = false ) {
		$site = new Site();

		if ( $globalId !== false ) {
			$site->setGlobalId( $globalId );
		}

		return $site;
	}

	/**
	 * @since 1.21
	 *
	 * @var SiteList|null
	 */
	protected $sites = null;

	/**
	 * @var SaneTable
	 */
	protected $sitesTable;

	public function __construct() {
		$this->sitesTable = new SaneTable(
			'sites',
			array(
				'id' => 'id',

				// Site data
				'global_key' => 'str',
				'type' => 'str',
				'group' => 'str',
				'source' => 'str',
				'language' => 'str',
				'protocol' => 'str',
				'domain' => 'str',
				'data' => 'array',

				// Site config
				'forward' => 'bool',
				'config' => 'array',
			),
			array(
				'type' => Site::TYPE_UNKNOWN,
				'group' => Site::GROUP_NONE,
				'source' => Site::SOURCE_LOCAL,
				'data' => array(),

				'forward' => false,
				'config' => array(),
				'language' => '',
			),
			'SiteRow',
			'site_'
		);
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
			if ( $this->sites === null ) {
				$cache = wfGetMainCache();
				$sites = $cache->get( wfMemcKey( 'SiteList' ) );

				if ( is_object( $sites ) ) {
					$this->sites = $sites;
				} else {
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
	 * Returns a new Site object constructed from the provided ORMRow.
	 *
	 * @since 1.21
	 *
	 * @param ORMRow $siteRow
	 *
	 * @return Site
	 */
	protected function siteFromRow( ORMRow $siteRow ) {
		$site = Site::newForType( $siteRow->getField( 'type', Site::TYPE_UNKNOWN ) );

		$site->setGlobalId( $siteRow->getField( 'global_key' ) );

		if ( $siteRow->hasField( 'forward' ) ) {
			$site->setForward( $siteRow->getField( 'forward' ) );
		}

		if ( $siteRow->hasField( 'group' ) ) {
			$site->setGroup( $siteRow->getField( 'group' ) );
		}

		if ( $siteRow->hasField( 'language' ) ) {
			$site->setLanguageCode( $siteRow->getField( 'language' ) );
		}

		if ( $siteRow->hasField( 'source' ) ) {
			$site->setSource( $siteRow->getField( 'source' ) );
		}

		if ( $siteRow->hasField( 'data' ) ) {
			$site->setExtraData( $siteRow->getField( 'data' ) );
		}

		// TODO: config

		return $site;
	}

	/**
	 * Fetches the site from the database and loads them into the sites field.
	 *
	 * @since 1.21
	 */
	protected function loadSites() {
		$this->sites = new SiteArray();

		foreach ( $this->sitesTable->select() as $siteRow ) {
			$this->sites[] = $this->siteFromRow( $siteRow );
		}

		// Batch load the local site identifiers.
		$ids = wfGetDB( $this->sitesTable->getReadDb() )->select(
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
		$cache->set( wfMemcKey( 'SiteList' ), $this->sites );
	}

	/**
	 * Returns the site with provided global id, or null if there is no such site.
	 *
	 * @since 1.21
	 *
	 * @param string $globalId
	 * @param string $source
	 *
	 * @return Site|null
	 */
	public function getSite( $globalId, $source = 'cache' ) {
		$sites = $this->getSites( $source );

		return $sites->hasSite( $globalId ) ? $sites->getSite( $globalId ) : null;
	}

	public function saveSite( Site $site ) {
		$this->saveSites( array( $site ) );
	}

	/**
	 * @param Site[] $sites
	 */
	public function saveSites( array $sites ) {


		foreach ( $sites as $site ) {
			$this->setField( 'protocol', $site->getProtocol() );
			$this->setField( 'domain', strrev( $site->getDomain() ) . '.' );
		}
	}

}
