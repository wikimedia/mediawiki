<?php

namespace MediaWiki\Tests;

use MediaWiki\Site\HashSiteStore;
use MediaWiki\Site\MediaWikiSite;
use MediaWiki\WikiMap\WikiMap;

/**
 * Ensure WikiMap::getWiki returns a MediaWikiSite for the current wiki, for tests.
 *
 * @since 1.44
 */
trait MockWikiMapTrait {

	/**
	 * Override the SiteLookup service to include the current site.
	 *
	 * @param string $server Server name to use for the current wiki. Defaults to https://example.com
	 * @param array[] $extraSites Other wikis. Each is an array with the following keys:
	 *   - wikiId
	 *   - server
	 */
	private function mockWikiMap( string $server = 'https://example.com', array $extraSites = [] ): void {
		$currentSite = new MediaWikiSite();
		$currentSite->setGlobalId( WikiMap::getCurrentWikiId() );
		$currentSite->setPath( MediaWikiSite::PATH_PAGE, "$server/wiki/\$1" );
		$sites = [ $currentSite ];
		foreach ( $extraSites as $extraSite ) {
			$site = new MediaWikiSite();
			$site->setGlobalId( $extraSite['wikiId'] );
			$site->setPath( MediaWikiSite::PATH_PAGE, "{$extraSite['server']}/wiki/\$1" );
			$sites[] = $site;
		}
		$this->setService( 'SiteLookup', new HashSiteStore( $sites ) );
	}

}
