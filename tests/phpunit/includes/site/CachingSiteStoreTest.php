<?php

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
 * @since 1.25
 *
 * @ingroup Site
 * @ingroup Test
 *
 * @group Site
 * @group Database
 *
 * @author Jeroen De Dauw < jeroendedauw@gmail.com >
 */
class CachingSiteStoreTest extends \MediaWikiIntegrationTestCase {

	/**
	 * @covers CachingSiteStore::getSites
	 */
	public function testGetSites() {
		$testSites = TestSites::getSites();

		$store = new CachingSiteStore(
			$this->getHashSiteStore( $testSites ),
			ObjectCache::getLocalClusterInstance()
		);

		$sites = $store->getSites();

		$this->assertInstanceOf( SiteList::class, $sites );

		/**
		 * @var Site $site
		 */
		foreach ( $sites as $site ) {
			$this->assertInstanceOf( Site::class, $site );
		}

		foreach ( $testSites as $site ) {
			if ( $site->getGlobalId() !== null ) {
				$this->assertTrue( $sites->hasSite( $site->getGlobalId() ) );
			}
		}
	}

	/**
	 * @covers CachingSiteStore::saveSites
	 */
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

	/**
	 * @covers CachingSiteStore::reset
	 */
	public function testReset() {
		$dbSiteStore = $this->getMockBuilder( SiteStore::class )
			->disableOriginalConstructor()
			->getMock();

		$dbSiteStore->expects( $this->any() )
			->method( 'getSite' )
			->will( $this->returnValue( $this->getTestSite() ) );

		$dbSiteStore->expects( $this->any() )
			->method( 'getSites' )
			->will( $this->returnCallback( function () {
				$siteList = new SiteList();
				$siteList->setSite( $this->getTestSite() );

				return $siteList;
			} ) );

		$store = new CachingSiteStore( $dbSiteStore, ObjectCache::getLocalClusterInstance() );

		// initialize internal cache
		$this->assertGreaterThan( 0, $store->getSites()->count(), 'count sites' );

		$store->getSite( 'enwiki' )->setLanguageCode( 'en-ca' );

		// sanity check: $store should have the new language code for 'enwiki'
		$this->assertEquals( 'en-ca', $store->getSite( 'enwiki' )->getLanguageCode(), 'sanity check' );

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

	/**
	 * @covers CachingSiteStore::clear
	 */
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
	 *
	 * @return SiteStore
	 */
	private function getHashSiteStore( array $sites ) {
		$siteStore = new HashSiteStore();
		$siteStore->saveSites( $sites );

		return $siteStore;
	}

}
