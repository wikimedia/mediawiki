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
 * @license GPL-2.0-or-later
 */

use Interwiki;
use Site;
use SiteLookup;
use MediaWikiSite;

class InterwikiLookupAdapter implements InterwikiLookup {

	/**
	 * @var SiteLookup
	 */
	private $siteLookup;

	/**
	 * @var Interwiki[]|null associative array mapping interwiki prefixes to Interwiki objects
	 */
	private $interwikiMap;

	function __construct(
		SiteLookup $siteLookup,
		array $interwikiMap = null
	) {
		$this->siteLookup = $siteLookup;
		$this->interwikiMap = $interwikiMap;
	}

	/**
	 * See InterwikiLookup::isValidInterwiki
	 * It loads the whole interwiki map.
	 *
	 * @param string $prefix Interwiki prefix to use
	 * @return bool Whether it exists
	 */
	public function isValidInterwiki( $prefix ) {
		return array_key_exists( $prefix, $this->getInterwikiMap() );
	}

	/**
	 * See InterwikiLookup::fetch
	 * It loads the whole interwiki map.
	 *
	 * @param string $prefix Interwiki prefix to use
	 * @return Interwiki|null|bool
	 */
	public function fetch( $prefix ) {
		if ( $prefix == '' ) {
			return null;
		}

		if ( !$this->isValidInterwiki( $prefix ) ) {
			return false;
		}

		return $this->interwikiMap[$prefix];
	}

	/**
	 * See InterwikiLookup::getAllPrefixes
	 *
	 * @param string|null $local If set, limits output to local/non-local interwikis
	 * @return array[] interwiki rows
	 */
	public function getAllPrefixes( $local = null ) {
		$res = [];
		foreach ( $this->getInterwikiMap() as $interwikiId => $interwiki ) {
			if ( $local === null || $interwiki->isLocal() === $local ) {
				$res[] = [
					'iw_prefix' => $interwikiId,
					'iw_url' => $interwiki->getURL(),
					'iw_api' => $interwiki->getAPI(),
					'iw_wikiid' => $interwiki->getWikiID(),
					'iw_local' => $interwiki->isLocal(),
					'iw_trans' => $interwiki->isTranscludable(),
				];
			}
		}
		return $res;
	}

	/**
	 * See InterwikiLookup::invalidateCache
	 *
	 * @param string $prefix
	 */
	public function invalidateCache( $prefix ) {
		if ( !isset( $this->interwikiMap[$prefix] ) ) {
			return;
		}
		$globalId = $this->interwikiMap[$prefix]->getWikiID();
		unset( $this->interwikiMap[$prefix] );

		// Reload the interwiki
		$site = $this->siteLookup->getSites()->getSite( $globalId );
		$interwikis = $this->getSiteInterwikis( $site );
		$this->interwikiMap = array_merge( $this->interwikiMap, [ $interwikis[$prefix] ] );
	}

	/**
	 * Load interwiki map to use as cache
	 */
	private function loadInterwikiMap() {
		$interwikiMap = [];
		$siteList = $this->siteLookup->getSites();
		foreach ( $siteList as $site ) {
			$interwikis = $this->getSiteInterwikis( $site );
			$interwikiMap = array_merge( $interwikiMap, $interwikis );
		}
		$this->interwikiMap = $interwikiMap;
	}

	/**
	 * Get interwikiMap attribute, load if needed.
	 *
	 * @return Interwiki[]
	 */
	private function getInterwikiMap() {
		if ( $this->interwikiMap === null ) {
			$this->loadInterwikiMap();
		}
		return $this->interwikiMap;
	}

	/**
	 * Load interwikis for the given site
	 *
	 * @param Site $site
	 * @return Interwiki[]
	 */
	private function getSiteInterwikis( Site $site ) {
		$interwikis = [];
		foreach ( $site->getInterwikiIds() as $interwiki ) {
			$url = $site->getPageUrl();
			if ( $site instanceof MediaWikiSite ) {
				$path = $site->getFileUrl( 'api.php' );
			} else {
				$path = '';
			}
			$local = $site->getSource() === 'local';
			// TODO: How to adapt trans?
			$interwikis[$interwiki] = new Interwiki(
				$interwiki,
				$url,
				$path,
				$site->getGlobalId(),
				$local
			);
		}
		return $interwikis;
	}
}
