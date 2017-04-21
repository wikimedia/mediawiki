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

	/**
	 * @var SiteInfoLookup
	 */
	private $siteInfoLookup = null;

	/**
	 * @var SiteList
	 */
	private $sites;

	/**
	 * @param SiteInfoLookup $siteInfoLookup
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
		$info = $this->siteInfoLookup->getSiteInfo( $globalId, [
			SiteInfoLookup::SITE_TYPE => SiteInfoLookup::TYPE_UNKNOWN,
			SiteInfoLookup::SITE_IS_FORWARDABLE => false,
			SiteInfoLookup::SITE_BASE_URL => '',
			SiteInfoLookup::SITE_SCRIPT_PATH => '',
			SiteInfoLookup::SITE_ARTICLE_PATH => '',
			SiteInfoLookup::SITE_FAMILY => null,
			SiteInfoLookup::SITE_LANGUAGE => null,
		] );

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
		$articlePath = $this->reducePath( $baseUrl, $info[SiteInfoLookup::SITE_ARTICLE_PATH] );
		$scriptPath = $this->reducePath( $baseUrl, $info[SiteInfoLookup::SITE_SCRIPT_PATH] );

		$site->setExtraData( [
			'paths' => [
				Site::PATH_LINK => $articlePath,
				MediaWikiSite::PATH_PAGE => $articlePath,
				MediaWikiSite::PATH_FILE => $scriptPath,
			]
		] );

		// XXX: this is expensive and probably not needed in most cases.
		$idMap = $this->siteInfoLookup->getAssociatedLocalIds( $globalId );

		foreach ( $idMap as $context => $ids ) {
			foreach ( $ids as $id ) {
				$site->addLocalId( $context, $id );
			}
		}

		return $site;
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

		if ( substr_compare( $url, $base, 0, $blen ) === 0 ) {
			return substr( $url, $blen );
		} else {
			return $url;
		}
	}

}
