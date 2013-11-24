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
 * @since 1.21
 *
 * @ingroup Site
 * @ingroup Test
 *
 * @group Site
 * @group Database
 *
 * @licence GNU GPL v2+
 * @author Jeroen De Dauw < jeroendedauw@gmail.com >
 */
class SiteSQLStoreTest extends MediaWikiTestCase {

	/**
	 * @covers SiteSQLStore::getSites
	 */
	public function testGetSites() {
		$expectedSites = TestSites::getSites();
		TestSites::insertIntoDb();

		$store = SiteSQLStore::newInstance();

		$sites = $store->getSites();

		$this->assertInstanceOf( 'SiteList', $sites );

		/**
		 * @var Site $site
		 */
		foreach ( $sites as $site ) {
			$this->assertInstanceOf( 'Site', $site );
		}

		foreach ( $expectedSites as $site ) {
			if ( $site->getGlobalId() !== null ) {
				$this->assertTrue( $sites->hasSite( $site->getGlobalId() ) );
			}
		}
	}

	/**
	 * @covers SiteSQLStore::saveSites
	 */
	public function testSaveSites() {
		$store = SiteSQLStore::newInstance();

		$sites = array();

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
		$this->assertInstanceOf( 'Site', $site );
		$this->assertEquals( 'en', $site->getLanguageCode() );
		$this->assertTrue( is_integer( $site->getInternalId() ) );
		$this->assertTrue( $site->getInternalId() >= 0 );

		$site = $store->getSite( 'sdfhxujgkfpth' );
		$this->assertInstanceOf( 'Site', $site );
		$this->assertEquals( 'nl', $site->getLanguageCode() );
		$this->assertTrue( is_integer( $site->getInternalId() ) );
		$this->assertTrue( $site->getInternalId() >= 0 );
	}

	/**
	 * @covers SiteSQLStore::reset
	 */
	public function testReset() {
		$store1 = SiteSQLStore::newInstance();
		$store2 = SiteSQLStore::newInstance();

		// initialize internal cache
		$this->assertGreaterThan( 0, $store1->getSites()->count() );
		$this->assertGreaterThan( 0, $store2->getSites()->count() );

		// Clear actual data. Will purge the external cache and reset the internal
		// cache in $store1, but not the internal cache in store2.
		$this->assertTrue( $store1->clear() );

		// sanity check: $store2 should have a stale cache now
		$this->assertNotNull( $store2->getSite( 'enwiki' ) );

		// purge cache
		$store2->reset();

		// ...now the internal cache of $store2 should be updated and thus empty.
		$site = $store2->getSite( 'enwiki' );
		$this->assertNull( $site );
	}

	/**
	 * @covers SiteSQLStore::clear
	 */
	public function testClear() {
		$store = SiteSQLStore::newInstance();
		$this->assertTrue( $store->clear() );

		$site = $store->getSite( 'enwiki' );
		$this->assertNull( $site );

		$sites = $store->getSites();
		$this->assertEquals( 0, $sites->count() );
	}
}
