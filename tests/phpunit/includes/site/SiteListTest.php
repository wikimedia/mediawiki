<?php
use MediaWiki\Site\MutableSite;

/**
 * Tests for the SiteList class.
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
 *
 * @author Jeroen De Dauw < jeroendedauw@gmail.com >
 */
class SiteListTest extends MediaWikiTestCase {

	/**
	 * Returns instances of SiteList implementing objects.
	 * @return array
	 */
	public function siteListProvider() {
		$sitesArrays = $this->siteArrayProvider();

		$listInstances = [];

		foreach ( $sitesArrays as $sitesArray ) {
			$listInstances[] = new SiteList( $sitesArray[0] );
		}

		return $this->arrayWrap( $listInstances );
	}

	/**
	 * Returns arrays with instances of Site implementing objects.
	 * @return array
	 */
	public function siteArrayProvider() {
		$sites = TestSites::getSites();

		$siteArrays = [];

		$siteArrays[] = $sites;

		$siteArrays[] = [ array_shift( $sites ) ];

		$siteArrays[] = [ array_shift( $sites ), array_shift( $sites ) ];

		return $this->arrayWrap( $siteArrays );
	}

	/**
	 * @dataProvider siteListProvider
	 * @param SiteList $sites
	 * @covers SiteList::isEmpty
	 */
	public function testIsEmpty( SiteList $sites ) {
		$this->assertEquals( count( $sites ) === 0, $sites->isEmpty() );
	}

	/**
	 * @covers SiteList::getSubList
	 */
	public function testGetSubList() {
		$sites = new SiteList();

		$sites[] = new MutableSite( 'foo' );
		$sites[] = new MutableSite( 'bar' );

		$sub = $sites->getSubList( [ 'xoo', 'bar' ] );

		$this->assertNotNull( $sub->getSite( 'bar' ) );
		$this->assertFalse( $sub->hasSite( 'foo' ) );
	}

	/**
	 * @dataProvider siteListProvider
	 * @param SiteList $sites
	 * @covers SiteList::getSite
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
	 * @covers SiteList::getSiteByNavigationId
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
	 * @covers SiteList::hasSite
	 */
	public function testHasGlobalId( $sites ) {
		$this->assertFalse( $sites->hasSite( 'non-existing-global-id' ) );

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
	 * @covers SiteList::hasNavigationId
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
	 * @covers SiteList::getGlobalIdentifiers
	 */
	public function testGetGlobalIdentifiers( SiteList $sites ) {
		$identifiers = $sites->getGlobalIdentifiers();

		$this->assertTrue( is_array( $identifiers ) );

		$expected = [];

		/**
		 * @var Site $site
		 */
		foreach ( $sites as $site ) {
			$expected[] = $site->getGlobalId();
		}

		$this->assertArrayEquals( $expected, $identifiers );
	}

}
