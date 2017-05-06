<?php

namespace MediaWiki\Site;

use MediaWikiSite;
use Site;
use SiteList;
use SiteLookup;

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
 * @license GNU GPL v2+
 */

/**
 * SiteLookup based on a SiteInfoLookup
 *
 * @since 1.29
 */
class SiteInfoSiteLookup implements SiteLookup {

	private $defaults = [
		SiteInfoLookup::SITE_TYPE => SiteInfoLookup::TYPE_UNKNOWN,
		SiteInfoLookup::SITE_IS_FORWARDABLE => false,
		SiteInfoLookup::SITE_BASE_URL => null,
		SiteInfoLookup::SITE_SCRIPT_PATH => null,
		SiteInfoLookup::SITE_LINK_PATH => null,
		SiteInfoLookup::SITE_FAMILY => null,
		SiteInfoLookup::SITE_LANGUAGE => null,
	];

	/**
	 * @var SiteInfoMaintenanceLookup
	 */
	private $siteInfoLookup = null;

	/**
	 * @var SiteList
	 */
	private $sites;

	/**
	 * @param SiteInfoMaintenanceLookup $siteInfoLookup
	 */
	public function __construct( SiteInfoLookup $siteInfoLookup ) {
		$this->siteInfoLookup = $siteInfoLookup;
	}

	/**
	 * @return SiteList
	 */
	public function getSites() {
		if ( $this->sites === null ) {
			$this->sites = $this->loadSites();
		}

		return $this->sites;
	}

	/**
	 * @param string $globalId
	 *
	 * @return Site|null
	 */
	public function getSite( $globalId ) {
		$unaliased = $this->siteInfoLookup->getSiteId( $globalId, SiteInfoLookup::ALIAS_ID );
		$globalId = $unaliased ?: $globalId;

		$sites = $this->getSites();
		return $sites->hasSite( $globalId ) ? $sites->getSite( $globalId ) : null;
	}

	/**
	 * @return SiteList
	 */
	private function loadSites() {
		$ids = $this->siteInfoLookup->listSites();

		$sites = new SiteList();

		foreach ( $ids as $id ) {
			$sites[] = $this->newSite( $id );
		}

		return $sites;
	}

	/**
	 * @param string $globalId
	 *
	 * @return Site
	 */
	private function newSite( $globalId ) {
		$info = $this->siteInfoLookup->getSiteInfo( $globalId, $this->defaults );

		$siteType = $info[SiteInfoLookup::SITE_TYPE] ?: Site::TYPE_UNKNOWN;
		$site = Site::newForType( $siteType );

		$site->setGlobalId( $globalId );
		$site->setForward( $info[SiteInfoLookup::SITE_IS_FORWARDABLE] );

		if ( isset( $info[ SiteInfoLookup::SITE_FAMILY ] ) ) {
			$site->setGroup( $info[ SiteInfoLookup::SITE_FAMILY ] );
		}

		if ( isset( $info[ SiteInfoLookup::SITE_LANGUAGE ] ) ) {
			$site->setLanguageCode( $info[ SiteInfoLookup::SITE_LANGUAGE ] );
		}

		$baseUrl = $info[SiteInfoLookup::SITE_BASE_URL];
		$articlePath = $this->expandUrl( $baseUrl, $info[SiteInfoLookup::SITE_LINK_PATH] );
		$scriptPath = $this->expandUrl( $baseUrl, $info[SiteInfoLookup::SITE_SCRIPT_PATH] );

		$site->setExtraData( array_filter( [
			'paths' => array_filter( [
				Site::PATH_LINK => $articlePath,
				MediaWikiSite::PATH_PAGE => $articlePath,
				MediaWikiSite::PATH_FILE => $scriptPath,
			] )
		] ) );

		// XXX: this is expensive and probably not needed in most cases.
		$idMap = $this->siteInfoLookup->getAliasesFor( $globalId );

		if ( isset( $idMap[SiteInfoLookup::INTERWIKI_ID] ) ) {
			foreach ( $idMap[SiteInfoLookup::INTERWIKI_ID] as $id ) {
				$site->addLocalId( Site::ID_INTERWIKI, $id );
			}
		}

		if ( isset( $idMap[SiteInfoLookup::INTERLANGUAGE_ID] ) ) {
			foreach ( $idMap[SiteInfoLookup::INTERLANGUAGE_ID] as $id ) {
				$site->addLocalId( Site::ID_EQUIVALENT, $id );
			}
		}

		return $site;
	}

	/**
	 * @param string $base
	 * @param string|null $path
	 *
	 * @return string|null
	 */
	private function expandUrl( $base, $path ) {
		if ( $path === null ) {
			return null;
		}

		if ( !preg_match( '!\$1$!', $path ) ) {
			$path .= '$1';
		}

		if ( preg_match( '!^https?://!', $path ) ) {
			return $path;
		} else {
			return $base . $path;
		}
	}

	/**
	 * @param string|null $base
	 * @param string|null $url
	 *
	 * @return string
	 */
	private function reducePath( $base, $url ) {
		$base = $base ?: '';
		$url = $url ?: '';
		$blen = strlen( $base );

		if ( strlen( $url ) > $blen && substr_compare( $url, $base, 0, $blen ) === 0 ) {
			return substr( $url, $blen );
		} else {
			return $url;
		}
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
