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

		$sitesTable = SiteSQLStore::newInstance();

		$sites = $sitesTable->getSites();

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
		$sitesTable = SiteSQLStore::newInstance();

		$sites = array();

		$site = new Site();
		$site->setGlobalId( 'ertrywuutr' );
		$site->setLanguageCode( 'en' );
		$sites[] = $site;

		$site = new MediaWikiSite();
		$site->setGlobalId( 'sdfhxujgkfpth' );
		$site->setLanguageCode( 'nl' );
		$sites[] = $site;

		$this->assertTrue( $sitesTable->saveSites( $sites ) );

		$site = $sitesTable->getSite( 'ertrywuutr', 'nocache' );
		$this->assertInstanceOf( 'Site', $site );
		$this->assertEquals( 'en', $site->getLanguageCode() );

		$site = $sitesTable->getSite( 'sdfhxujgkfpth', 'nocache' );
		$this->assertInstanceOf( 'Site', $site );
		$this->assertEquals( 'nl', $site->getLanguageCode() );
	}

}
