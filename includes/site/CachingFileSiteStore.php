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
 * @since 1.25
 *
 * @file
 *
 * @license GNU GPL v2+
 */
class CachingFileSiteStore implements SiteStore {

	/**
	 * @var SiteListFileCache
	 */
	private $cache;

	/**
	 * @var SiteListFileCacheBuilder
	 */
	private $cacheBuilder;

	/**
	 * @var boolean
	 */
	private $readOnly;

	/**
	 * @param SiteListFileCache $cache
	 * @param SiteListFileCacheBuilder $cacheBuilder
	 * @param boolean $readOnly default is true.
	 */
	public function __construct(
		SiteListFileCache $cache,
		SiteListFileCacheBuilder $cacheBuilder,
		$readOnly = true
	) {
		$this->cache = $cache;
		$this->cacheBuilder = $cacheBuilder;
		$this->readOnly = $readOnly;
	}

	/**
	 * @see SiteStore::getSites
	 *
	 * @since 1.25
	 *
	 * @throws MWException
	 * @return SiteList
	 */
	public function getSites( $source = 'cache' ) {
		if ( $this->sites === null ) {
			if ( $this->isExpired( $source ) ) {
				$this->recache();
			} else {
				return $this->cache->getSites();
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
	 * @param string $source
	 *
	 * @return Site|null
	 */
	public function getSite( $globalId, $source = 'cache' ) {
		if ( $source === 'recache' ) {
			$this->recache();
		}

		return $this->cache->getSite( $globalId );
	}

	/**
	 * @param string $source set to 'recache' to force recache
	 *
	 * @return boolean
	 */
	private function isExpired( $source ) {
		return $source === 'recache' || !is_readable( $this->cacheFile );
	}

	private function recache() {
		$this->cacheBuilder->build();
	}

	/**
	 * @see SiteStore::saveSite
	 *
	 * @since 1.25
	 *
	 * @param Site $site
	 *
	 * @throws MWException since the store does not support saving sites.
	 */
	public function saveSite( Site $site ) {
		throw new MWException( 'CachingFileSiteStore can only be updated with a rebuild.' );
	}

	/**
	 * @see SiteStore::saveSites
	 *
	 * @since 1.25
	 *
	 * @param Site[] $sites
	 *
	 * @throws MWException since the store does not support saving sites.
	 */
	public function saveSites( array $sites ) {
		throw new MWException( 'CachingFileSiteStore can only be updated with a rebuild.' );
	}

	/**
	 * @see SiteStore::clear()
	 */
	public function clear() {
		throw new MWException( 'CachingFileSiteStore can only be updated with a rebuild.' );
	}

}
