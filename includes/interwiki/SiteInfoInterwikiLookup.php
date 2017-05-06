<?php
namespace MediaWiki\Interwiki;

/**
 * InterwikiLookupAdapter on top of SiteInfoLookup
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

use Interwiki;
use MediaWiki\Site\SiteInfoLookup;
use MediaWiki\Site\SiteInfoMaintenanceLookup;
use OutOfBoundsException;

class SiteInfoInterwikiLookup implements InterwikiLookup {

	private $defaults = [
		SiteInfoLookup::SITE_BASE_URL => '',
		SiteInfoLookup::SITE_LINK_PATH => '',
		SiteInfoLookup::SITE_SCRIPT_PATH => '',
		SiteInfoLookup::SITE_IS_FORWARDABLE => false,
		SiteInfoLookup::SITE_IS_TRANSCLUDABLE => false,
	];

	/**
	 * @var SiteInfoMaintenanceLookup
	 */
	private $siteLookup;

	function __construct(
		SiteInfoMaintenanceLookup $siteLookup
	) {
		$this->siteLookup = $siteLookup;
	}

	/**
	 * See InterwikiLookup::isValidInterwiki
	 * It loads the whole interwiki map.
	 *
	 * @param string $prefix Interwiki prefix to use
	 * @return bool Whether it exists
	 */
	public function isValidInterwiki( $prefix ) {
		return $this->siteLookup->getSiteId( $prefix, SiteInfoLookup::INTERWIKI_ID ) !== null
			|| $this->siteLookup->getSiteId( $prefix, SiteInfoLookup::INTERLANGUAGE_ID ) !== null;
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

		$id = $this->siteLookup->getSiteId( $prefix, SiteInfoLookup::INTERWIKI_ID );

		if ( $id === null ) {
			$id = $this->siteLookup->getSiteId( $prefix, SiteInfoLookup::INTERLANGUAGE_ID );
		}

		if ( $id === null ) {
			return false;
		}

		try {
			$info = $this->siteLookup->getSiteInfo( $id, $this->defaults );
		} catch ( OutOfBoundsException $ex ) {
			return false;
		}

		$articleUrl = $info[SiteInfoLookup::SITE_BASE_URL]
			. $info[SiteInfoLookup::SITE_LINK_PATH];

		$apiUrl = $info[SiteInfoLookup::SITE_BASE_URL]
			. $info[SiteInfoLookup::SITE_SCRIPT_PATH]
			. 'api.php';

		// XXX: cache?! Implement invalidateCache() if we do!
		$interwiki = new Interwiki(
			$prefix,
			$articleUrl,
			$apiUrl,
			$id,
			$info[SiteInfoLookup::SITE_IS_FORWARDABLE] === true,
			$info[SiteInfoLookup::SITE_IS_TRANSCLUDABLE] === true
		);

		return $interwiki;
	}

	/**
	 * See InterwikiLookup::getAllPrefixes
	 *
	 * @param string|null $local If set, limits output to local/non-local interwikis
	 * @return array[] Interwiki rows, where each row is an associative array
	 */
	public function getAllPrefixes( $local = null ) {
		// @var array $sites: maps local IDs to global site IDs
		$sites = array_merge(
			$this->siteLookup->getAliasMap( SiteInfoLookup::INTERWIKI_ID ),
			$this->siteLookup->getAliasMap( SiteInfoLookup::INTERLANGUAGE_ID )
		);

		if ( $local !== null ) {
			// @var array $matching: a list of global site IDs
			$matching = $this->siteLookup->findSites(
				SiteInfoLookup::SITE_IS_FORWARDABLE,
				$local
			);
			$sites = array_intersect_key( $sites, array_flip( $matching ) );
		}
		$res = [];
		foreach ( $sites as $interwikiId => $siteId ) {
			$interwiki = $this->fetch( $interwikiId );

			if ( $local === null || $interwiki->isLocal() === $local ) {
				$res[] = [
					'iw_prefix' => $interwikiId,
					'iw_url' => $interwiki->getURL(),
					'iw_api' => $interwiki->getAPI(),
					'iw_wikiid' => $interwiki->getWikiID(),
					'iw_local' => $interwiki->isLocal() ? '1' : '0',
					'iw_trans' => $interwiki->isTranscludable() ? '1' : '0',
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
		// noop
	}

	/**
	 * @return array
	 */
	public function getSiteDefaults() {
		return $this->defaults;
	}

	/**
	 * @param array $defaults
	 */
	public function setSiteDefaults( $defaults ) {
		$this->defaults = array_merge( $this->defaults, $defaults );
	}

}
