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
class SiteSQLStore implements SiteStore {
	/**
	 * @since 1.21
	 *
	 * @var SiteList|null
	 */
	protected $sites = null;

	/**
	 * @var ORMTable
	 */
	protected $sitesTable;

	/**
	 * @var string|null
	 */
	private $cacheKey = null;

	/**
	 * @var int
	 */
	private $cacheTimeout = 3600;

	/**
	 * @var BagOStuff
	 */
	private $cache;

	/**
	 * @since 1.21
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

		return new static( $cache, $sitesTable );
	}

	/**
	 * Constructor.
	 *
	 * @since 1.21
	 *
	 * @param BagOStuff $cache
	 * @param ORMTable|null $sitesTable
	 */
	protected function __construct( BagOStuff $cache, ORMTable $sitesTable = null ) {
		if ( $sitesTable === null ) {
			$sitesTable = $this->newSitesTable();
		}

		$this->cache = $cache;
		$this->sitesTable = $sitesTable;
	}

	/**
	 * Constructs a cache key to use for caching the list of sites.
	 *
	 * This includes the concrete class name of the site list as well as a version identifier
	 * for the list's serialization, to avoid problems when unserializing site lists serialized
	 * by an older version, e.g. when reading from a cache.
	 *
	 * The cache key also includes information about where the sites were loaded from, e.g.
	 * the name of a database table.
	 *
	 * @see SiteList::getSerialVersionId
	 *
	 * @return string The cache key.
	 */
	protected function getCacheKey() {

		if ( $this->cacheKey === null ) {
			$type = 'SiteList#' . SiteList::getSerialVersionId();
			$source = $this->sitesTable->getName();

			if ( $this->sitesTable->getTargetWiki() !== false ) {
				$source = $this->sitesTable->getTargetWiki() . '.' . $source;
			}

			$this->cacheKey = wfMemcKey( "$source/$type" );
		}

		return $this->cacheKey;
	}

	/**
	 * @see SiteStore::getSites
	 *
	 * @since 1.21
	 *
	 * @param string $source Either 'cache' or 'recache'
	 *
	 * @return SiteList
	 */
	public function getSites( $source = 'cache' ) {

		if ( $source === 'cache' ) {
			if ( $this->sites === null ) {
				$sites = $this->cache->get( $this->getCacheKey() );

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

		$site->setInternalId( $siteRow->getField( 'id' ) );

		if ( $siteRow->hasField( 'forward' ) ) {
			$site->setForward( $siteRow->getField( 'forward' ) );
		}

		if ( $siteRow->hasField( 'group' ) ) {
			$site->setGroup( $siteRow->getField( 'group' ) );
		}

		if ( $siteRow->hasField( 'language' ) ) {
			$site->setLanguageCode( $siteRow->getField( 'language' ) === ''
				? null
				: $siteRow->getField( 'language' )
			);
		}

		if ( $siteRow->hasField( 'source' ) ) {
			$site->setSource( $siteRow->getField( 'source' ) );
		}

		if ( $siteRow->hasField( 'data' ) ) {
			$site->setExtraData( $siteRow->getField( 'data' ) );
		}

		if ( $siteRow->hasField( 'config' ) ) {
			$site->setExtraConfig( $siteRow->getField( 'config' ) );
		}

		return $site;
	}

	/**
	 * Get a new ORMRow from a Site object
	 *
	 * @since 1.22
	 *
	 * @param Site $site
	 *
	 * @return ORMRow
	 */
	protected function getRowFromSite( Site $site ) {
		$fields = array(
			// Site data
			'global_key' => $site->getGlobalId(), // TODO: check not null
			'type' => $site->getType(),
			'group' => $site->getGroup(),
			'source' => $site->getSource(),
			'language' => $site->getLanguageCode() === null ? '' : $site->getLanguageCode(),
			'protocol' => $site->getProtocol(),
			'domain' => strrev( $site->getDomain() ) . '.',
			'data' => $site->getExtraData(),

			// Site config
			'forward' => $site->shouldForward(),
			'config' => $site->getExtraConfig(),
		);

		if ( $site->getInternalId() !== null ) {
			$fields['id'] = $site->getInternalId();
		}

		return new ORMRow( $this->sitesTable, $fields );
	}

	/**
	 * Fetches the site from the database and loads them into the sites field.
	 *
	 * @since 1.21
	 */
	protected function loadSites() {

		$this->sites = new SiteList();

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

		$this->cache->set( $this->getCacheKey(), $this->sites, $this->cacheTimeout );

	}

	/**
	 * @see SiteStore::getSite
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

	/**
	 * @see SiteStore::saveSite
	 *
	 * @since 1.21
	 *
	 * @param Site $site
	 *
	 * @return bool Success indicator
	 */
	public function saveSite( Site $site ) {
		return $this->saveSites( array( $site ) );
	}

	/**
	 * @see SiteStore::saveSites
	 *
	 * @since 1.21
	 *
	 * @param Site[] $sites
	 *
	 * @return bool Success indicator
	 */
	public function saveSites( array $sites ) {

		if ( empty( $sites ) ) {
			return true;
		}

		$dbw = $this->sitesTable->getWriteDbConnection();

		$dbw->startAtomic( __METHOD__ );

		$success = true;

		$internalIds = array();
		$localIds = array();

		foreach ( $sites as $site ) {
			if ( $site->getInternalId() !== null ) {
				$internalIds[] = $site->getInternalId();
			}

			$siteRow = $this->getRowFromSite( $site );
			$success = $siteRow->save( __METHOD__ ) && $success;

			foreach ( $site->getLocalIds() as $idType => $ids ) {
				foreach ( $ids as $id ) {
					$localIds[] = array( $siteRow->getId(), $idType, $id );
				}
			}
		}

		if ( $internalIds !== array() ) {
			$dbw->delete(
				'site_identifiers',
				array( 'si_site' => $internalIds ),
				__METHOD__
			);
		}

		foreach ( $localIds as $localId ) {
			$dbw->insert(
				'site_identifiers',
				array(
					'si_site' => $localId[0],
					'si_type' => $localId[1],
					'si_key' => $localId[2],
				),
				__METHOD__
			);
		}

		$dbw->endAtomic( __METHOD__ );

		// purge cache
		$this->reset();

		return $success;
	}

	/**
	 * Purges the internal and external cache of the site list, forcing the list
	 * of sites to be re-read from the database.
	 *
	 * @since 1.21
	 */
	public function reset() {
		// purge cache
		$this->cache->delete( $this->getCacheKey() );
		$this->sites = null;

	}

	/**
	 * Clears the list of sites stored in the database.
	 *
	 * @see SiteStore::clear()
	 *
	 * @return bool Success
	 */
	public function clear() {
		$dbw = $this->sitesTable->getWriteDbConnection();

		$dbw->startAtomic( __METHOD__ );
		$ok = $dbw->delete( 'sites', '*', __METHOD__ );
		$ok = $dbw->delete( 'site_identifiers', '*', __METHOD__ ) && $ok;
		$dbw->endAtomic( __METHOD__ );

		$this->reset();

		return $ok;
	}

	/**
	 * @since 1.21
	 *
	 * @return ORMTable
	 */
	protected function newSitesTable() {
		return new ORMTable(
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
			'ORMRow',
			'site_'
		);
	}

}
