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
 */

namespace MediaWiki\Tests\Site;

use MediaWiki\Site\Site;
use MediaWiki\Site\SiteList;
use MediaWikiIntegrationTestCase;

/**
 * @covers \MediaWiki\Site\SiteList
 * @group Site
 * @author Jeroen De Dauw < jeroendedauw@gmail.com >
 */
class SiteListTest extends MediaWikiIntegrationTestCase {

	/**
	 * Returns instances of SiteList implementing objects.
	 * @return array
	 */
	public static function siteListProvider() {
		$sitesArrays = self::siteArrayProvider();

		$listInstances = [];

		foreach ( $sitesArrays as $sitesArray ) {
			$listInstances[] = new SiteList( $sitesArray[0] );
		}

		return self::arrayWrap( $listInstances );
	}

	/**
	 * Returns arrays with instances of Site implementing objects.
	 * @return array
	 */
	public static function siteArrayProvider() {
		$sites = TestSites::getSites();

		$siteArrays = [];

		$siteArrays[] = $sites;

		$siteArrays[] = [ array_shift( $sites ) ];

		$siteArrays[] = [ array_shift( $sites ), array_shift( $sites ) ];

		return self::arrayWrap( $siteArrays );
	}

	/**
	 * @dataProvider siteListProvider
	 * @param SiteList $sites
	 */
	public function testIsEmpty( SiteList $sites ) {
		$this->assertEquals( count( $sites ) === 0, $sites->isEmpty() );
	}

	/**
	 * @dataProvider siteListProvider
	 * @param SiteList $sites
	 */
	public function testGetSiteByGlobalId( SiteList $sites ) {
		/**
		 * @var Site $site
		 */
		foreach ( $sites as $site ) {
			$this->assertEquals( $site, $sites->getSite( $site->getGlobalId() ) );
		}

		$this->assertTrue( true );
	}

	/**
	 * @dataProvider siteListProvider
	 * @param SiteList $sites
	 */
	public function testGetSiteByInternalId( $sites ) {
		/**
		 * @var Site $site
		 */
		foreach ( $sites as $site ) {
			if ( is_int( $site->getInternalId() ) ) {
				$this->assertEquals( $site, $sites->getSiteByInternalId( $site->getInternalId() ) );
			}
		}

		$this->assertTrue( true );
	}

	/**
	 * @dataProvider siteListProvider
	 * @param SiteList $sites
	 */
	public function testGetSiteByNavigationId( $sites ) {
		/**
		 * @var Site $site
		 */
		foreach ( $sites as $site ) {
			$ids = $site->getNavigationIds();
			foreach ( $ids as $navId ) {
				$this->assertEquals( $site, $sites->getSiteByNavigationId( $navId ) );
			}
		}

		$this->assertTrue( true );
	}

	/**
	 * @dataProvider siteListProvider
	 * @param SiteList $sites
	 */
	public function testHasGlobalId( $sites ) {
		$this->assertFalse( $sites->hasSite( 'non-existing-global-id' ) );
		$this->assertFalse( $sites->hasInternalId( 720101010 ) );

		if ( !$sites->isEmpty() ) {
			/**
			 * @var Site $site
			 */
			foreach ( $sites as $site ) {
				$this->assertTrue( $sites->hasSite( $site->getGlobalId() ) );
			}
		}
	}

	/**
	 * @dataProvider siteListProvider
	 * @param SiteList $sites
	 */
	public function testHasInternallId( $sites ) {
		/**
		 * @var Site $site
		 */
		foreach ( $sites as $site ) {
			if ( is_int( $site->getInternalId() ) ) {
				$this->assertTrue( $site, $sites->hasInternalId( $site->getInternalId() ) );
			}
		}

		$this->assertFalse( $sites->hasInternalId( -1 ) );
	}

	/**
	 * @dataProvider siteListProvider
	 * @param SiteList $sites
	 */
	public function testHasNavigationId( $sites ) {
		/**
		 * @var Site $site
		 */
		foreach ( $sites as $site ) {
			$ids = $site->getNavigationIds();
			foreach ( $ids as $navId ) {
				$this->assertTrue( $sites->hasNavigationId( $navId ) );
			}
		}

		$this->assertFalse( $sites->hasNavigationId( 'non-existing-navigation-id' ) );
	}

	/**
	 * @dataProvider siteListProvider
	 * @param SiteList $sites
	 */
	public function testGetGlobalIdentifiers( SiteList $sites ) {
		$identifiers = $sites->getGlobalIdentifiers();

		$this->assertIsArray( $identifiers );

		$expected = [];

		/**
		 * @var Site $site
		 */
		foreach ( $sites as $site ) {
			$expected[] = $site->getGlobalId();
		}

		$this->assertArrayEquals( $expected, $identifiers );
	}

	/**
	 * @dataProvider siteListProvider
	 * @param SiteList $list
	 */
	public function testSerialization( SiteList $list ) {
		$serialization = serialize( $list );
		/**
		 * @var SiteList $copy
		 */
		$copy = unserialize( $serialization );

		$this->assertArrayEquals( $list->getGlobalIdentifiers(), $copy->getGlobalIdentifiers() );

		/**
		 * @var Site $site
		 */
		foreach ( $list as $site ) {
			$this->assertTrue( $copy->hasInternalId( $site->getInternalId() ) );

			foreach ( $site->getNavigationIds() as $navId ) {
				$this->assertTrue(
					$copy->hasNavigationId( $navId ),
					'unserialized data expects nav id ' . $navId . ' for site ' . $site->getGlobalId()
				);
			}
		}
	}
}
