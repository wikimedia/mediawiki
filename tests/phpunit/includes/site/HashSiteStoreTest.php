<?php
/**
 * @license GPL-2.0-or-later
 * @file
 */
namespace MediaWiki\Tests\Site;

use MediaWiki\Site\HashSiteStore;
use MediaWiki\Site\MediaWikiSite;
use MediaWiki\Site\Site;
use MediaWiki\Site\SiteList;
use MediaWikiIntegrationTestCase;

/**
 * @covers \MediaWiki\Site\HashSiteStore
 * @group Site
 * @author Katie Filbert < aude.wiki@gmail.com >
 */
class HashSiteStoreTest extends MediaWikiIntegrationTestCase {

	public function testGetSites() {
		$expectedSites = [];

		foreach ( TestSites::getSites() as $testSite ) {
			$siteId = $testSite->getGlobalId();
			$expectedSites[$siteId] = $testSite;
		}

		$siteStore = new HashSiteStore( $expectedSites );

		$this->assertEquals( new SiteList( $expectedSites ), $siteStore->getSites() );
	}

	public function testSaveSite() {
		$store = new HashSiteStore();

		$site = new Site();
		$site->setGlobalId( 'dewiki' );

		$this->assertCount( 0, $store->getSites(), '0 sites in store' );

		$store->saveSite( $site );

		$this->assertCount( 1, $store->getSites(), 'Store has 1 sites' );
		$this->assertEquals( $site, $store->getSite( 'dewiki' ), 'Store has dewiki' );
	}

	public function testSaveSites() {
		$store = new HashSiteStore();

		$sites = [];

		$site = new Site();
		$site->setGlobalId( 'enwiki' );
		$site->setLanguageCode( 'en' );
		$sites[] = $site;

		$site = new MediaWikiSite();
		$site->setGlobalId( 'eswiki' );
		$site->setLanguageCode( 'es' );
		$sites[] = $site;

		$this->assertCount( 0, $store->getSites(), '0 sites in store' );

		$store->saveSites( $sites );

		$this->assertCount( 2, $store->getSites(), 'Store has 2 sites' );
		$this->assertTrue( $store->getSites()->hasSite( 'enwiki' ), 'Store has enwiki' );
		$this->assertTrue( $store->getSites()->hasSite( 'eswiki' ), 'Store has eswiki' );
	}

	public function testClear() {
		$store = new HashSiteStore();

		$site = new Site();
		$site->setGlobalId( 'arwiki' );
		$store->saveSite( $site );

		$this->assertCount( 1, $store->getSites(), '1 site in store' );

		$store->clear();
		$this->assertCount( 0, $store->getSites(), '0 sites in store' );
	}
}
