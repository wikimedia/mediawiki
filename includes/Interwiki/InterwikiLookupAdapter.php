<?php
/**
 * @license GPL-2.0-or-later
 * @file
 */

namespace MediaWiki\Interwiki;

use MediaWiki\Site\MediaWikiSite;
use MediaWiki\Site\Site;
use MediaWiki\Site\SiteLookup;

/**
 * InterwikiLookupAdapter on top of SiteLookup
 *
 * @since 1.29
 * @license GPL-2.0-or-later
 */
class InterwikiLookupAdapter implements InterwikiLookup {

	/**
	 * @var SiteLookup
	 */
	private $siteLookup;

	/**
	 * @var array<string,Interwiki>|null associative array mapping interwiki prefixes to Interwiki objects
	 */
	private $interwikiMap;

	public function __construct(
		SiteLookup $siteLookup,
		?array $interwikiMap = null
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

		// @phan-suppress-next-line PhanTypeArraySuspiciousNullable
		return $this->interwikiMap[$prefix];
	}

	/**
	 * See InterwikiLookup::getAllPrefixes
	 *
	 * @param bool|null $local If set, limits output to local/non-local interwikis
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
		$this->interwikiMap[$prefix] = $interwikis[$prefix];
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
	 * @return array<string,Interwiki>
	 */
	private function getInterwikiMap(): array {
		if ( $this->interwikiMap === null ) {
			$this->loadInterwikiMap();
		}
		return $this->interwikiMap;
	}

	/**
	 * Load interwikis for the given site
	 *
	 * @param Site $site
	 * @return array<string,Interwiki>
	 */
	private function getSiteInterwikis( Site $site ): array {
		$url = $site->getPageUrl();
		if ( $site instanceof MediaWikiSite ) {
			$path = $site->getFileUrl( 'api.php' );
		} else {
			$path = '';
		}
		$local = $site->getSource() === 'local';

		$interwikis = [];
		foreach ( $site->getInterwikiIds() as $interwiki ) {
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
