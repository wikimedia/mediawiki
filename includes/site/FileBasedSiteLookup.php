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
 * @license GPL-2.0-or-later
 */

/**
 * Provides a file-based cache of a SiteStore, using a json file.
 *
 * @since 1.25
 * @deprecated since 1.33 Use CachingSiteStore instead.
 */
class FileBasedSiteLookup implements SiteLookup {

	/**
	 * @var SiteList
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
		wfDeprecated( __CLASS__, '1.33' );
		$this->cacheFile = $cacheFile;
	}

	/**
	 * @since 1.25
	 *
	 * @return SiteList
	 */
	public function getSites() {
		if ( $this->sites === null ) {
			$this->sites = $this->loadSitesFromCache();
		}

		return $this->sites;
	}

	/**
	 * @param string $globalId
	 *
	 * @since 1.25
	 *
	 * @return Site|null
	 */
	public function getSite( $globalId ) {
		$sites = $this->getSites();

		return $sites->hasSite( $globalId ) ? $sites->getSite( $globalId ) : null;
	}

	/**
	 * @return SiteList
	 */
	private function loadSitesFromCache() {
		$data = $this->loadJsonFile();

		$sites = new SiteList();

		// @todo lazy initialize the site objects in the site list (e.g. only when needed to access)
		foreach ( $data['sites'] as $siteArray ) {
			$sites[] = $this->newSiteFromArray( $siteArray );
		}

		return $sites;
	}

	/**
	 * @throws MWException
	 * @return array see docs/sitescache.txt for format of the array.
	 */
	private function loadJsonFile() {
		if ( !is_readable( $this->cacheFile ) ) {
			throw new MWException( 'SiteList cache file not found.' );
		}

		$contents = file_get_contents( $this->cacheFile );
		$data = json_decode( $contents, true );

		if ( !is_array( $data ) || !is_array( $data['sites'] )
			|| !array_key_exists( 'sites', $data )
		) {
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
