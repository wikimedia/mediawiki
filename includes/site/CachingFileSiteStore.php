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
	 * @var SiteList
	 */
	private $sites;

	/**
	 * @var SiteStore
	 */
	private $siteStore;

	/**
	 * @var string
	 */
	private $cacheFile;

	/**
	 * @param SiteStore $siteStore
	 * @param string $cacheFile
	 */
	public function __construct( SiteStore $siteStore, $cacheFile ) {
		$this->siteStore = $siteStore;
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
		if ( !isset( $this->sites ) ) {
			if ( $source !== 'recache' && is_readable( $this->cacheFile ) ) {
				$this->loadSitesFromCache();
			} else {
				$this->loadSitesFromStore();
				$this->saveSites( $this->sites->getArrayCopy() );
			}
		}

		return $this->sites;
	}

	/**
	 * @throws MWException
	 */
	private function loadSitesFromCache() {
		$data = $this->loadJsonFile();

		$this->sites = new SiteList();

		foreach( $data['sites'] as $siteArray ) {
			$this->sites[] = $this->newSiteFromArray( $siteArray );
		}

		if ( array_key_exists( 'identifiers', $data ) ) {
			$this->setLocalIdentifiers( $data['identifiers'] );
		}
	}

	/**
	 * @throws MWException
	 * @return array
	 */
	private function loadJsonFile() {
		$data = json_decode( file_get_contents( $this->cacheFile ), true );

		if ( !is_array( $data ) || !array_key_exists( 'sites', $data ) ) {
			throw new MWException( 'SiteStore json cache data is invalid.' );
		}

		return $data;
	}

	/**
	 * @param array $data
	 *
	 * @return Site
	 */
	private function newSiteFromArray( array $data ) {
		$siteType = array_key_exists( 'type', $data ) ? $data['type'] : Site::TYPE_UNKNOWN;
		$site = Site::newForType( $siteType );

		if ( array_key_exists( 'globalid', $data ) ) {
			$site->setGlobalId( $data['globalid'] );
		}

		if ( array_key_exists( 'internalid', $data ) ) {
			$site->setInternalId( $data['internalid'] );
		}

		if ( array_key_exists( 'forward', $data ) ) {
			$site->setForward( $data['forward'] );
		}

		if ( array_key_exists( 'group', $data ) ) {
			$site->setGroup( $data['group'] );
		}

		if ( array_key_exists( 'language', $data ) ) {
			$site->setLanguageCode( $data['language'] ) === '' ? null : $data['language'];
		}

		if ( array_key_exists( 'source', $data ) ) {
			$site->setSource( $data['source'] );
		}

		if ( array_key_exists( 'data', $data ) ) {
			$site->setExtraData( $data['data'] );
		}

		if ( array_key_exists( 'config', $data ) ) {
			$site->setExtraConfig( $data['config'] );
		}

		return $site;
	}

	private function loadSitesFromStore() {
		$sites = $this->siteStore->getSites( 'recache' );
		$this->sites = $sites;
	}

	/**
	 * @param array $identifiers
	 */
	private function setLocalIdentifiers( array $identifiers ) {
		foreach ( $identifiers as $identifier ) {
			if ( $this->sites->hasInternalId( $identifier['si_site'] ) ) {
				$site = $this->sites->getSiteByInternalId( $identifier['si_site'] );
				$site->addLocalId( $identifier['si_type'], $identifier['si_key'] );
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
		$sites = $this->getSites( $source );

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
		$this->sites->setSite( $site );
		$this->saveSites( $this->sites );
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

		$result = file_put_contents( $this->cacheFile, $json );

		return $result !== false;
	}

	/**
	 * @param Site $site
	 *
	 * @return array Site local identifiers
	 */
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

	/**
	 * @see SiteStore::rebuild
	 */
	public function rebuild() {
		$this->reset();
		$sites = $this->getSites();
		$this->saveSites( $sites->getArrayCopy() );
	}

	/**
	 * @see SiteStore::reset
	 */
	public function reset() {
		$this->sites = $this->siteStore->getSites( 'recache' );
	}

	/**
	 * @see SiteStore::clear()
	 */
	public function clear() {
		$this->saveSites( new SiteList() );
	}

}
