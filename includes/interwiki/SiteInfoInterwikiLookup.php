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

		$id = $this->siteLookup->resolveLocalId( SiteInfoLookup::INTERWIKI_ID, $prefix );

		if ( $id === null ) {
			$id = $this->siteLookup->resolveLocalId( SiteInfoLookup::NAVIGATION_ID, $prefix );
		}

		if ( $id === null ) {
			return false;
		}

		$info = $this->siteLookup->getSiteInfo( $id, [
			SiteInfoLookup::SITE_BASE_URL => '',
			SiteInfoLookup::SITE_ARTICLE_PATH => '',
			SiteInfoLookup::SITE_SCRIPT_PATH => '',
			SiteInfoLookup::SITE_IS_FORWARDABLE => false,
			SiteInfoLookup::SITE_IS_TRANSCLUDABLE => false,
		] );

		$articleUrl = $info[SiteInfoLookup::SITE_BASE_URL]
			. $info[SiteInfoLookup::SITE_ARTICLE_PATH];

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
	 * @return string[] List of prefixes
	 */
	public function getAllPrefixes( $local = null ) {
		// @var array $sites: maps local IDs to global site IDs
		$sites = array_merge(
			$this->siteLookup->getIdMap( SiteInfoLookup::INTERWIKI_ID ),
			$this->siteLookup->getIdMap( SiteInfoLookup::NAVIGATION_ID )
		);

		if ( $local !== null ) {
			// @var array $matching: a list of global site IDs
			$matching = $this->siteLookup->listGroupMembers( SiteInfoLookup::SITE_IS_FORWARDABLE, $local );
			$sites = array_intersect( $sites, $matching );
		}

		return array_keys( $sites );
	}

	/**
	 * See InterwikiLookup::invalidateCache
	 *
	 * @param string $prefix
	 */
	public function invalidateCache( $prefix ) {
		// noop
	}

}
