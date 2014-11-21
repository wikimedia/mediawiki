<?php

/**
 * Tests for the SiteSQLStore class.
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
 * @since 1.25
 *
 * @ingroup Site
 * @ingroup Test
 *
 * @covers CachingFileSiteStore
 * @group Site
 * @group Database
 *
 * @licence GNU GPL v2+
 * @author Katie Filbert < aude.wiki@gmail.com >
 */
class CachingFileSiteStoreTest extends MediaWikiTestCase {

	protected static $setup;

	protected function setUp() {
		parent::setUp();

		if ( !isset( self::$setup ) ) {
			TestSites::insertIntoDb();
			self::$setup = true;
		}
	}

	public function testGetSites() {
		$cacheFile = $this->getCacheFile();

		$store = $this->newCachingFileSiteStore( $cacheFile );
		$sites = $store->getSites();

		$this->assertInstanceOf( 'SiteList', $sites );

		foreach ( $sites as $site ) {
			$this->assertInstanceOf( 'Site', $site );
		}

		$expectedSites = TestSites::getSites();

		foreach ( $expectedSites as $site ) {
			$this->assertTrue( $sites->hasSite( $site->getGlobalId() ) );
		}

		return $cacheFile;
	}

	/**
	 * @depends testGetSites
	 */
	public function testGetSitesFromCacheOnly( $cacheFile ) {
		// all the methods return null and it is not touched here
		$siteStore = $this->getMock( 'SiteStore' );

		$store = new CachingFileSiteStore( $siteStore, $cacheFile );
		$sites = $store->getSites();

		$this->assertInstanceOf( 'SiteList', $sites );

		$expectedSites = TestSites::getSites();

		foreach ( $expectedSites as $site ) {
			$this->assertTrue( $sites->hasSite( $site->getGlobalId() ) );
		}
	}

	/**
	 * @depends testGetSitesFromCacheOnly
	 */
	public function testSaveSites() {
		$cacheFile = $this->getCacheFile();
		$store = $this->newCachingFileSiteStore( $cacheFile );

		$sites = array();

		$site = new Site();
		$site->setGlobalId( 'eeeewiki' );
		$site->setLanguageCode( 'en' );
		$sites[] = $site;

		$site = new MediaWikiSite();
		$site->setGlobalId( 'nnnnwiki' );
		$site->setLanguageCode( 'nl' );
		$sites[] = $site;

		$this->assertTrue( $store->saveSites( $sites ) );

		$site = $store->getSite( 'eeeewiki' );

		$this->assertInstanceOf( 'Site', $site, 'instance of site eeeewiki' );
		$this->assertEquals( 'en', $site->getLanguageCode(), 'language code en' );
		$this->assertTrue( is_integer( $site->getInternalId() ), 'eeeewiki internal id is int' );
		$this->assertTrue( $site->getInternalId() >= 0, 'eeeewiki internal id > 0' );

		$site = $store->getSite( 'nnnnwiki' );

		$this->assertInstanceOf( 'Site', $site, 'instance of site nnnnwiki' );
		$this->assertEquals( 'nl', $site->getLanguageCode(), 'language code nl' );
		$this->assertTrue( is_integer( $site->getInternalId() ), 'nnnnwiki internal id' );
		$this->assertTrue( $site->getInternalId() >= 0, 'nnnnwiki internal id > 0' );
	}

	/**
	 * @depends testSaveSites
	 */
	public function testClear() {
		$cacheFile = $this->getCacheFile();

		$store = $this->newCachingFileSiteStore( $cacheFile );
		$this->assertTrue( $store->clear() );

		$site = $store->getSite( 'nlwiki' );
		$this->assertNull( $site );

		$sites = $store->getSites();
		$this->assertEquals( 0, $sites->count() );

		unlink( $cacheFile );
	}

	private function newCachingFileSiteStore( $cacheFile ) {
		return new CachingFileSiteStore(
			SiteSQLStore::newInstance(),
			$cacheFile
		);
	}

	private function getCacheFile() {
		return sys_get_temp_dir() . '/sites-' . time() . '.json';
	}

}
