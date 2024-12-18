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
	 * @return void
	 */
	private function mockWikiMap() {
		$currentSite = new MediaWikiSite();
		$currentSite->setGlobalId( WikiMap::getCurrentWikiId() );
		$currentSite->setPath( MediaWikiSite::PATH_PAGE, 'https://example.com/wiki/$1' );
		$this->setService( 'SiteLookup', new HashSiteStore( [ $currentSite ] ) );
	}

}
