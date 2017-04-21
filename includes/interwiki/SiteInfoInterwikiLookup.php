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

class SiteInfoInterwikiLookup implements InterwikiLookup {

	private $defaults = [
		SiteInfoLookup::SITE_BASE_URL => '',
		SiteInfoLookup::SITE_LINK_PATH => '',
		SiteInfoLookup::SITE_SCRIPT_PATH => '',
		SiteInfoLookup::SITE_IS_LOCAL => false,
		SiteInfoLookup::SITE_IS_TRANSCLUDABLE => false,
	];

	/**
	 * @var SiteInfoLookup
	 */
	private $siteLookup;

	function __construct(
		SiteInfoLookup $siteLookup
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
		return $this->siteLookup->resolveLocalId( SiteInfoLookup::INTERWIKI_ID, $prefix ) !== null
			|| $this->siteLookup->resolveLocalId( SiteInfoLookup::NAVIGATION_ID, $prefix ) !== null;
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

		$siteId = $this->siteLookup->resolveLocalId( SiteInfoLookup::INTERWIKI_ID, $prefix );

		if ( $siteId === null ) {
			$siteId = $this->siteLookup->resolveLocalId( SiteInfoLookup::NAVIGATION_ID, $prefix );
		}

		if ( $siteId === null ) {
			return false;
		}

		$info = $this->siteLookup->getSiteInfo( $siteId, $this->defaults );
		$row = $this->buildInterwikiRow( $siteId, $prefix, $info );

		// XXX: cache?! Implement invalidateCache() if we do!
		$interwiki = new Interwiki(
			$row['iw_prefix'],
			$row['iw_url'],
			$row['iw_api'],
			$row['iw_wikiid'],
			$row['iw_local'],
			$row['iw_trans']
		);

		return $interwiki;
	}

	private function buildInterwikiRow( $siteId, $prefix, array $info ) {
		$id = $this->siteLookup->resolveLocalId( SiteInfoLookup::INTERWIKI_ID, $prefix );

		if ( $id === null ) {
			$id = $this->siteLookup->resolveLocalId( SiteInfoLookup::NAVIGATION_ID, $prefix );
		}

		if ( $id === null ) {
			return false;
		}

		$info = $this->siteLookup->getSiteInfo( $id, $this->defaults );

		$articleUrl = $this->composeUrl(
			$info[SiteInfoLookup::SITE_BASE_URL],
			$info[SiteInfoLookup::SITE_LINK_PATH]
		);

		$apiUrl = $this->composeUrl(
			$info[SiteInfoLookup::SITE_BASE_URL],
			$info[SiteInfoLookup::SITE_SCRIPT_PATH],
			'api.php'
		);

		$row = [
			'iw_prefix' => $prefix,
			'iw_url' => $articleUrl,
			'iw_api' => $apiUrl,
			'iw_wikiid' => $siteId,
			'iw_trans' => $info[SiteInfoLookup::SITE_IS_TRANSCLUDABLE] ? 1 : 0,
			'iw_local' => $info[SiteInfoLookup::SITE_IS_LOCAL]  ? 1 : 0
		];

		return $row;
	}

	/**
	 * @param string $base
	 * @param string|null $path
	 * @param string $suffix
	 *
	 * @return null|string
	 */
	private function composeUrl( $base, $path, $suffix = '' ) {
		if ( $path === null ) {
			return null;
		}

		if ( preg_match( '!^(https?:)?//!', $path ) ) {
			return $path . $suffix;
		}

		return $base . $path . $suffix;
	}

	/**
	 * See InterwikiLookup::getAllPrefixes
	 *
	 * @param string|null $local If set, limits output to local/non-local interwikis
	 * @return array[] List of interwiki rows
	 */
	public function getAllPrefixes( $local = null ) {
		// @var array $sites: maps local IDs to global site IDs
		$sites = array_merge(
			$this->siteLookup->getIdMap( SiteInfoLookup::INTERWIKI_ID ),
			$this->siteLookup->getIdMap( SiteInfoLookup::NAVIGATION_ID )
		);

		if ( $local !== null ) {
			// @var array $matching: a list of global site IDs
			$matching = $this->siteLookup->listGroupMembers(
				SiteInfoLookup::SITE_IS_LOCAL,
				$local,
				false
			);
			$sites = array_intersect( $sites, $matching );
		}

		$rows = [];
		foreach ( $sites as $prefix => $siteId ) {
			$info = $this->siteLookup->getSiteInfo( $siteId );
			$rows[] = $this->buildInterwikiRow( $siteId, $prefix, $info );
		}

		return $rows;
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
