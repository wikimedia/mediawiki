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

		$site = $store->getSite( 'sdfhxujgkfpth' );
		$this->assertInstanceOf( 'Site', $site );
		$this->assertEquals( 'nl', $site->getLanguageCode() );
	}

	public function testReset() {
		$store = SiteSQLStore::newInstance();

		// initialize internal cache
		$this->assertGreaterThan( 0, $store->getSites()->count() );

		// Clear actual data. Will not purge the internal cache in store2.
		$dbw = wfGetDB( DB_MASTER );
		$dbw->delete( 'sites', '*', __METHOD__ );
		$dbw->delete( 'site_identifiers', '*', __METHOD__ );

		// sanity check: $store2 should have a stale cache now
		$this->assertNotNull( $store->getSite( 'enwiki' ) );

		// purge cache
		$store->reset();

		// ...now the internal cache of $store2 should be updated and thus empty.
		$site = $store->getSite( 'enwiki' );
		$this->assertNull( $site );
	}

}
