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
 * @since 1.25
 *
 * @file
 * @ingroup Site
 *
 * @license GNU GPL v2+
 */
class SiteFileStore implements SiteStore {

	/**
	 * @since 1.25
	 *
	 * @var SiteList|null
	 */
	private $sites = null;

	/**
	 * @var string
	 */
	private $cacheFile;

	/**
	 * @param string $cacheFile
	 * @throws InvalidArgumentException
	 */
	public function __construct( $cacheFile ) {
		if ( !is_string( $cacheFile ) ) {
			throw new InvalidArgumentException( '$cacheFile: ' . $cacheFile . ' is not readable.' );
		}

		$this->cacheFile = $cacheFile;
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
		if ( $source === 'recache' ) {
			$this->reset();
		}

		if ( $this->sites === null ) {
			$this->sites = $this->loadSitesFromCache();
		}

		return $this->sites;
	}

	private function loadSitesFromCache() {
		if ( !is_readable( $this->cacheFile ) ) {
			$this->reset();
			return $this->sites;
		}

		$data = json_decode( file_get_contents( $this->cacheFile ), true );

		if ( !is_array( $data ) || !array_key_exists( 'sites', $data ) ) {
			throw new MWException( 'Cached sites data is invalid or corrupt.' );
		}

		$sites = array();

		foreach( $data['sites'] as $site ) {
			$sites[] = unserialize( serialize( $site ) );
		}

		$this->sites = new SiteList( $sites );

		if ( array_key_exists( 'identifiers', $data ) ) {
			$this->setLocalIdentifiers( $data['identifiers'] );
		}
	}

	private function setLocalIdentifiers( array $ids ) {
		foreach ( $ids as $id ) {
			if ( $this->sites->hasInternalId( $id->si_site ) ) {
				$site = $this->sites->getSiteByInternalId( $id->si_site );
				$site->addLocalId( $id->si_type, $id->si_key );
				$this->sites->setSite( $site );
			}
		}
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
		wfProfileIn( __METHOD__ );

		// @todo this does not seem very nice, but is in the interface.
		if ( $source === 'recache' ) {
			$this->rebuild();
		}

		$sites = $this->getSites( $source );

		wfProfileOut( __METHOD__ );
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
		return $this->saveSites( array( $site ) );
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
		wfProfileIn( __METHOD__ );

		if ( empty( $sites ) ) {
			wfProfileOut( __METHOD__ );
			return true;
		}

		$sitesArray = array();
		$identifiersArray = array();

		foreach ( $sites as $site ) {
			$sitesArray[] = unserialize( $site->serialize() );

			foreach( $this->buildLocalIdentifiers( $site ) as $identifier ) {
				$identifiersArray[] = $identifier;
			}
		}

		$json = json_encode( array(
			'sites' => $sitesArray,
			'identifiers' => $identifiersArray
		) );

		file_put_contents( $this->cacheFile, $json );
	}

	private function buildLocalIdentifiers( Site $site ) {
		$localIds = array();

		foreach ( $site->getLocalIds() as $idType => $ids ) {
			foreach ( $ids as $id ) {
				$localIds[] = array(
					'si_site' => $site->getInternalId(),
					'si_type' => $idType,
					'si_key' => $id
				);
			}
		}

		return $localIds;
	}

	public function rebuild() {
		$this->reset();
		$sites = $this->getSites();
		$this->saveSites( $sites->getArrayCopy() );
	}

	/**
	 * Purges the internal and external cache of the site list, forcing the list
	 * of sites to be re-read from the database.
	 *
	 * @since 1.25
	 */
	public function reset() {
		$this->sites = SiteSQLStore::newInstance()->getSites( 'recache' );
	}

	/**
	 * Clears the list of sites stored in the cache.
	 *
	 * @see SiteStore::clear()
	 *
	 * @return bool Success
	 */
	public function clear() {
		$this->saveSites( new SiteList() );
	}

}
