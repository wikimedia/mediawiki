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
	private $sites = null;

	/**
	 * @var SiteStore
	 */
	private $siteStore;

	/**
	 * @var string
	 */
	private $cacheFile;

	/**
	 * @var boolean
	 */
	private $manualRecache;

	/**
	 * @param SiteStore $siteStore
	 * @param string $cacheFile
	 * @param boolean $manualRecache default false, set to true to restrict to manual recache.
	 */
	public function __construct( SiteStore $siteStore, $cacheFile, $manualRecache = false ) {
		$this->siteStore = $siteStore;
		$this->cacheFile = $cacheFile;
		$this->manualRecache = $manualRecache;
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
				$this->refreshFromStore();
			} else {
				$this->loadSitesFromCache();
			}
		}

		return $this->sites;
	}

	/**
	 * @param string $source set to 'recache' to force recache
	 *
	 * @return boolean
	 */
	private function isExpired( $source ) {
		return $source === 'recache' || !is_readable( $this->cacheFile );
	}

	/**
	 * @throws MWException
	 */
	private function loadSitesFromCache() {
		$data = $this->loadJsonFile();

		$this->sites = new SiteList();

		// @todo lazy initialize the site objects in the site list (e.g. only when needed to access)
		foreach( $data['sites'] as $siteArray ) {
			$this->sites[] = $this->newSiteFromArray( $siteArray );
		}
	}

	/**
	 * @throws MWException
	 * @return array
	 */
	private function loadJsonFile() {
		$contents = file_get_contents( $this->cacheFile );
		$data = json_decode( $contents, true );

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

		$site->setGlobalId( $data['globalid'] );
		$site->setInternalId( $data['internalid'] );
		$site->setForward( $data['forward'] );
		$site->setGroup( $data['group'] );
		$site->setLanguageCode( $data['language'] );
		$site->setSource( $data['source'] );
		$site->setExtraData( $data['data'] );
		$site->setExtraConfig( $data['config'] );

		foreach( $data['identifiers'] as $identifier ) {
			$site->addLocalId( $identifier['type'], $identifier['key'] );
		}

		return $site;
	}

	/**
	 * @throws MWException if in manualRecache mode
	 */
	public function refreshFromStore() {
		$this->sites = $this->siteStore->getSites( 'recache' );
		$this->cacheSites( $this->sites->getArrayCopy() );
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
	 * @throws MWException if in manualRecache mode
	 * @return bool Success indicator
	 */
	public function saveSite( Site $site ) {
		$this->sites->setSite( $site );
		$this->saveSites( $this->sites->getArrayCopy() );
	}

	/**
	 * @see SiteStore::saveSites
	 *
	 * @since 1.25
	 *
	 * @param Site[] $sites
	 *
	 * @throws MWException if in manualRecache mode
	 * @return bool Success indicator
	 */
	public function saveSites( array $sites ) {
		$this->siteStore->saveSites( $sites );
		$sites = $this->siteStore->getSites();
		return $this->cacheSites( $sites->getArrayCopy() );
	}

	/**
	 * @param Site[] $sites
	 *
	 * @throws MWException if in manualRecache mode
	 * @return bool
	 */
	private function cacheSites( array $sites ) {
		if ( $this->manualRecache === true ) {
			throw new MWException( 'Cannot rebuild from store in manual recache mode.' );
		}

		$sitesArray = array();

		foreach ( $sites as $site ) {
			$globalId = $site->getGlobalId();
			$sitesArray[$globalId] = $this->getSiteAsArray( $site );
		}

		$json = json_encode( array(
			'sites' => $sitesArray
		) );

		$result = file_put_contents( $this->cacheFile, $json );

		return $result !== false;
	}

	/**
	 * @param Site $site
	 *
	 * @return array
	 */
	private function getSiteAsArray( Site $site ) {
		$siteEntry = unserialize( $site->serialize() );
		$siteIdentifiers = $this->buildLocalIdentifiers( $site );
		$identifiersArray = array();

		foreach( $siteIdentifiers as $identifier ) {
			$identifiersArray[] = $identifier;
		}

		$siteEntry['identifiers'] = $identifiersArray;

		return $siteEntry;
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
					'type' => $idType,
					'key' => $id
				);
			}
		}

		return $localIds;
	}

	/**
	 * @see SiteStore::clear()
	 */
	public function clear() {
		if ( $this->manualRecache === true ) {
			throw new MWException( 'Cannot clear store in manual recache mode.' );
		}

		$this->sites = null;
		unlink( $this->cacheFile );
		return $this->siteStore->clear();
	}

}
