<?php
namespace MediaWiki\Interwiki;

/**
 * InterwikiLookupAdapter on top of SiteLookup
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
 *
 * @since 1.29
 * @ingroup InterwikiLookup
 *
 * @license GNU GPL v2+
 */

use MediaWikiSite;
use Site;
use SiteList;
use SiteLookup;

class SiteLookupAdapter implements SiteLookup {

	/**
	 * @var InterwikiLookup
	 */
	private $interwikiLookup;

	/**
	 * @var Site[]|null associative array mapping global id prefixes to Site objects
	 */
	private $sitesMap;

	function __construct( InterwikiLookup $interwikiLookup, array $sitesMap = null ) {
		$this->interwikiLookup = $interwikiLookup;
		$this->sitesMap = $sitesMap;
	}

	/**
	 * Returns the site with provided global id, or null if there is no such site.
	 *
	 * @since 1.25
	 *
	 * @param string $globalId
	 *
	 * @return Site|null
	 */
	public function getSite( $globalId ) {
		if ( !array_key_exists( $globalId, $this->getSitesMap() ) ) {
			return null;
		}

		return $this->sitesMap[$globalId];
	}

	/**
	 * Returns a list of all sites.
	 *
	 * @since 1.25
	 *
	 * @return SiteList
	 */
	public function getSites() {
		return new SiteList( array_values( $this->getSitesMap() ) );
	}

	/**
	 * Get sitesMap attribute, load if needed.
	 *
	 * @return Site[]
	 */
	private function getSitesMap() {
		if ( $this->sitesMap === null ) {
			$this->loadSitesMap();
		}
		return $this->sitesMap;
	}

	/**
	 * Load sites map to use as cache
	 */
	private function loadSitesMap() {
		$this->sitesMap = [];
		$interwikis = $this->interwikiLookup->getAllPrefixes();
		foreach ( $interwikis as $interwikiPrefix ) {
			$this->sitesMap[$interwikiPrefix] = $this->getSiteFromInterwiki( $interwikiPrefix );
		}
	}

	private function getSiteFromInterwiki( $interwikiPrefix ) {
		$interwiki = $this->interwikiLookup->fetch( $interwikiPrefix );
		$site = new MediaWikiSite();
		$site->setGlobalId( $interwiki->getWikiID() );
		$site->setSource( $interwiki->isLocal() ? 'local' : 'interwikiLookup' );
		$site->setPagePath( $interwiki->getURL() );
		// This breaks when there is two api.php in the URL
		$site->setFilePath( str_replace( 'api.php', '$1', $interwiki->getAPI() ) );
		$site->addInterwikiId( $interwikiPrefix );

		return $site;
	}
}