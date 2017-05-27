<?php

/**
 * Represents the site configuration of a wiki.
 * Holds a list of sites (ie SiteList), with a caching layer.
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
 * @author Jeroen De Dauw < jeroendedauw@gmail.com >
 * @author Katie Filbert < aude.wiki@gmail.com >
 */
class CachingSiteStore implements SiteStore {

	/**
	 * @var SiteList|null
	 */
	private $sites = null;

	/**
	 * @var string|null
	 */
	private $cacheKey;

	/**
	 * @var int
	 */
	private $cacheTimeout;

	/**
	 * @var BagOStuff
	 */
	private $cache;

	/**
	 * @var SiteStore
	 */
	private $siteStore;

	/**
	 * @param SiteStore $siteStore
	 * @param BagOStuff $cache
	 * @param string|null $cacheKey
	 * @param int $cacheTimeout
	 */
	public function __construct(
		SiteStore $siteStore,
		BagOStuff $cache,
		$cacheKey = null,
		$cacheTimeout = 3600
	) {
		$this->siteStore = $siteStore;
		$this->cache = $cache;
		$this->cacheKey = $cacheKey;
		$this->cacheTimeout = $cacheTimeout;
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
	private function getCacheKey() {
		if ( $this->cacheKey === null ) {
			$type = 'SiteList#' . SiteList::getSerialVersionId();
			$this->cacheKey = $this->cache->makeKey( "sites/$type" );
		}

		return $this->cacheKey;
	}

	/**
	 * @see SiteStore::getSites
	 *
	 * @since 1.25
	 *
	 * @return SiteList
	 */
	public function getSites() {
		if ( $this->sites === null ) {
			$this->sites = $this->cache->get( $this->getCacheKey() );

			if ( !is_object( $this->sites ) ) {
				$this->sites = $this->siteStore->getSites();

				$this->cache->set( $this->getCacheKey(), $this->sites, $this->cacheTimeout );
			}
		}

		return $this->sites;
	}

	/**
	 * @see SiteStore::getSite
	 *
	 * @since 1.25
	 *
	 * @param string $globalId
	 *
	 * @return Site|null
	 */
	public function getSite( $globalId ) {
		$sites = $this->getSites();

		return $sites->hasSite( $globalId ) ? $sites->getSite( $globalId ) : null;
	}

	/**
	 * @see SiteStore::saveSite
	 *
	 * @since 1.25
	 *
	 * @param Site $site
	 *
	 * @return bool Success indicator
	 */
	public function saveSite( Site $site ) {
		return $this->saveSites( [ $site ] );
	}

	/**
	 * @see SiteStore::saveSites
	 *
	 * @since 1.25
	 *
	 * @param Site[] $sites
	 *
	 * @return bool Success indicator
	 */
	public function saveSites( array $sites ) {
		if ( empty( $sites ) ) {
			return true;
		}

		$success = $this->siteStore->saveSites( $sites );

		// purge cache
		$this->reset();

		return $success;
	}

	/**
	 * Purges the internal and external cache of the site list, forcing the list.
	 * of sites to be reloaded.
	 *
	 * Only use this for testing, as APC is typically used and is per-server
	 *
	 * @since 1.25
	 */
	public function reset() {
		// purge cache
		$this->cache->delete( $this->getCacheKey() );
		$this->sites = null;
	}

	/**
	 * Clears the list of sites stored.
	 *
	 * Only use this for testing, as APC is typically used and is per-server.
	 *
	 * @see SiteStore::clear()
	 *
	 * @return bool Success
	 */
	public function clear() {
		$this->reset();

		return $this->siteStore->clear();
	}

}
