<?php
/**
 * Implements SiteLookup interface, with a file cache-based store,
 * allowing to get a Site, all sites or an array of sites by global id.
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
 * @ingroup Site
 *
 * @license GNU GPL v2+
 */

/**
 * Provides a file-based cache of a SiteStore or SiteList, stored as a json
 * file. The cache can be built with the rebuildSitesCache.php maintenance
 * script, and a MediaWiki instance can be setup to use this by setting the
 * 'wgSitesCacheFile' configuration to the cache file location.
 *
 * @since 1.25
 */
class SiteFileCacheLookup implements SiteLookup {

	/**
	 * @var Site[]
	 */
	private $sites = null;

	/**
	 * @var string
	 */
	private $cacheFile;

	/**
	 * @param string $cacheFile
	 */
	public function __construct( $cacheFile ) {
		$this->cacheFile = $cacheFile;
	}

	/**
	 * @see SiteLookup::getSites
	 *
	 * @since 1.25
	 *
	 * @param string[]|null $globalIds
	 *
	 * @return Site[]
	 */
	public function getSites( array $globalIds = null ) {
		if ( $this->sites === null ) {
			$this->loadSitesFromCache();
		}

		if ( $globalIds !== null ) {
			return array_intersect_key( $this->sites, array_flip( $globalIds ) );
		}

		return $this->sites;
	}

	/**
	 * @see SiteLookup::getSite
	 *
	 * @since 1.25
	 *
	 * @param string $globalId
	 *
	 * @return Site|null
	 */
	public function getSite( $globalId ) {
		$sites = $this->getSites();

		return array_key_exists( $globalId, $sites ) ? $sites[$globalId] : null;
	}

	/**
	 * @return SiteList
	 */
	private function loadSitesFromCache() {
		$data = $this->loadJsonFile();

		$this->sites = array();

		// @todo lazy initialize the site objects in the site list (e.g. only when needed to access)
		foreach ( $data['sites'] as $siteArray ) {
			$site = $this->newSiteFromArray( $siteArray );
			$globalId = $site->getGlobalId();
			$this->sites[$globalId] = $site;
		}

		return $this->sites;
	}

	/**
	 * @throws MWException
	 * @return array
	 */
	private function loadJsonFile() {
		if ( !is_readable( $this->cacheFile ) ) {
			throw new MWException( 'SiteList cache file not found.' );
		}

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

		foreach ( $data['identifiers'] as $identifier ) {
			$site->addLocalId( $identifier['type'], $identifier['key'] );
		}

		return $site;
	}

}
