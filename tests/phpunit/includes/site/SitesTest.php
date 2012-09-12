<?php

/**
 * Tests for the Sites class.
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
 * @since 1.20
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
class SitesTest extends MediaWikiTestCase {

	public function setUp() {
		parent::setUp();
		TestSites::insertIntoDb();
	}

	public function testSingleton() {
		$this->assertInstanceOf( 'Sites', Sites::singleton() );
		$this->assertTrue( Sites::singleton() === Sites::singleton() );
	}

	public function testGetSites() {
		$this->assertInstanceOf( 'SiteList', Sites::singleton()->getSites() );
	}


	public function testGetSite() {
		$count = 0;
		$sites = Sites::singleton()->getSites();

		/**
		 * @var Site $site
		 */
		foreach ( $sites as $site ) {
			$this->assertInstanceOf( 'Site', $site );

			$this->assertEquals(
				$site,
				Sites::singleton()->getSite( $site->getGlobalId() )
			);

			if ( ++$count > 100 ) {
				break;
			}
		}
	}

	public function testNewSite() {
		$this->assertInstanceOf( 'Site', Sites::newSite() );
		$this->assertInstanceOf( 'Site', Sites::newSite( 'enwiki' ) );
	}

	public function testGetGroup() {
		$wikipedias = Sites::singleton()->getSiteGroup( "wikipedia" );

		$this->assertFalse( $wikipedias->isEmpty() );

		/* @var Site $site */
		foreach ( $wikipedias as $site ) {
			$this->assertEquals( 'wikipedia', $site->getGroup() );
		}
	}

}
