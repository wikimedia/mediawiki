<?php

/**
 * @covers CachingSiteStore
 * @group Site
 * @group Database
 * @author Jeroen De Dauw < jeroendedauw@gmail.com >
 */
class CachingSiteStoreTest extends \MediaWikiIntegrationTestCase {

	public function testGetSites() {
		$testSites = TestSites::getSites();

		$store = new CachingSiteStore(
			$this->getHashSiteStore( $testSites ),
			ObjectCache::getLocalClusterInstance()
		);

		$sites = $store->getSites();

		$this->assertInstanceOf( SiteList::class, $sites );

		/** @var Site $site */
		foreach ( $sites as $site ) {
			$this->assertInstanceOf( Site::class, $site );
		}

		foreach ( $testSites as $site ) {
			if ( $site->getGlobalId() !== null ) {
				$this->assertTrue( $sites->hasSite( $site->getGlobalId() ) );
			}
		}
	}

	public function testSaveSites() {
		$store = new CachingSiteStore(
			new HashSiteStore(), ObjectCache::getLocalClusterInstance()
		);

		$sites = [];

		$site = new Site();
		$site->setGlobalId( 'ertrywuutr' );
		$site->setLanguageCode( 'en' );
		$sites[] = $site;

		$site = new MediaWikiSite();
		$site->setGlobalId( 'sdfhxujgkfpth' );
		$site->setLanguageCode( 'nl' );
		$sites[] = $site;

		$this->assertTrue( $store->saveSites( $sites ) );

		$site = $store->getSite( 'ertrywuutr' );
		$this->assertInstanceOf( Site::class, $site );
		$this->assertEquals( 'en', $site->getLanguageCode() );

		$site = $store->getSite( 'sdfhxujgkfpth' );
		$this->assertInstanceOf( Site::class, $site );
		$this->assertEquals( 'nl', $site->getLanguageCode() );
	}

	public function testReset() {
		$dbSiteStore = $this->createMock( SiteStore::class );

		$dbSiteStore->method( 'getSite' )
			->willReturn( $this->getTestSite() );

		$dbSiteStore->method( 'getSites' )
			->willReturnCallback( function () {
				$siteList = new SiteList();
				$siteList->setSite( $this->getTestSite() );

				return $siteList;
			} );

		$store = new CachingSiteStore( $dbSiteStore, ObjectCache::getLocalClusterInstance() );

		// initialize internal cache
		$this->assertGreaterThan( 0, $store->getSites()->count(), 'count sites' );

		$store->getSite( 'enwiki' )->setLanguageCode( 'en-ca' );

		// check: $store should have the new language code for 'enwiki'
		$this->assertEquals( 'en-ca', $store->getSite( 'enwiki' )->getLanguageCode() );

		// purge cache
		$store->reset();

		// the internal cache of $store should be updated, and now pulling
		// the site from the 'fallback' DBSiteStore with the original language code.
		$this->assertEquals( 'en', $store->getSite( 'enwiki' )->getLanguageCode(), 'reset' );
	}

	public function getTestSite() {
		$enwiki = new MediaWikiSite();
		$enwiki->setGlobalId( 'enwiki' );
		$enwiki->setLanguageCode( 'en' );
		return $enwiki;
	}

	public function testClear() {
		$store = new CachingSiteStore(
			new HashSiteStore(), ObjectCache::getLocalClusterInstance()
		);
		$this->assertTrue( $store->clear() );

		$site = $store->getSite( 'enwiki' );
		$this->assertNull( $site );

		$sites = $store->getSites();
		$this->assertSame( 0, $sites->count() );
	}

	/**
	 * @param Site[] $sites
	 * @return SiteStore
	 */
	private function getHashSiteStore( array $sites ) {
		$siteStore = new HashSiteStore();
		$siteStore->saveSites( $sites );
		return $siteStore;
	}

}
