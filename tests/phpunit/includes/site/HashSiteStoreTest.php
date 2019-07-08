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
 * @group Site
 *
 * @author Katie Filbert < aude.wiki@gmail.com >
 */
class HashSiteStoreTest extends \MediaWikiIntegrationTestCase {

	/**
	 * @covers HashSiteStore::getSites
	 */
	public function testGetSites() {
		$expectedSites = [];

		foreach ( TestSites::getSites() as $testSite ) {
			$siteId = $testSite->getGlobalId();
			$expectedSites[$siteId] = $testSite;
		}

		$siteStore = new HashSiteStore( $expectedSites );

		$this->assertEquals( new SiteList( $expectedSites ), $siteStore->getSites() );
	}

	/**
	 * @covers HashSiteStore::saveSite
	 * @covers HashSiteStore::getSite
	 */
	public function testSaveSite() {
		$store = new HashSiteStore();

		$site = new Site();
		$site->setGlobalId( 'dewiki' );

		$this->assertCount( 0, $store->getSites(), '0 sites in store' );

		$store->saveSite( $site );

		$this->assertCount( 1, $store->getSites(), 'Store has 1 sites' );
		$this->assertEquals( $site, $store->getSite( 'dewiki' ), 'Store has dewiki' );
	}

	/**
	 * @covers HashSiteStore::saveSites
	 */
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

	/**
	 * @covers HashSiteStore::clear
	 */
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
