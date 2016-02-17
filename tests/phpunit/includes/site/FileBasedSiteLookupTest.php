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
 * @covers FileBasedSiteLookup
 * @group Site
 *
 * @author Katie Filbert < aude.wiki@gmail.com >
 */
class FileBasedSiteLookupTest extends PHPUnit_Framework_TestCase {

	protected function setUp() {
		$this->cacheFile = $this->getCacheFile();
	}

	protected function tearDown() {
		unlink( $this->cacheFile );
	}

	public function testGetSites() {
		$sites = $this->getSites();
		$cacheBuilder = $this->newSitesCacheFileBuilder( $sites );
		$cacheBuilder->build();

		$cache = new FileBasedSiteLookup( $this->cacheFile );
		$this->assertEquals( $sites, $cache->getSites() );
	}

	public function testGetSite() {
		$sites = $this->getSites();
		$cacheBuilder = $this->newSitesCacheFileBuilder( $sites );
		$cacheBuilder->build();

		$cache = new FileBasedSiteLookup( $this->cacheFile );

		$this->assertEquals( $sites->getSite( 'enwiktionary' ), $cache->getSite( 'enwiktionary' ) );
	}

	private function newSitesCacheFileBuilder( SiteList $sites ) {
		return new SitesCacheFileBuilder(
			$this->getSiteLookup( $sites ),
			$this->cacheFile
		);
	}

	private function getSiteLookup( SiteList $sites ) {
		$siteLookup = $this->getMockBuilder( 'SiteLookup' )
			->disableOriginalConstructor()
			->getMock();

		$siteLookup->expects( $this->any() )
			->method( 'getSites' )
			->will( $this->returnValue( $sites ) );

		return $siteLookup;
	}

	private function getSites() {
		$sites = [];

		$site = new Site();
		$site->setGlobalId( 'foobar' );
		$sites[] = $site;

		$site = new MediaWikiSite();
		$site->setGlobalId( 'enwiktionary' );
		$site->setGroup( 'wiktionary' );
		$site->setLanguageCode( 'en' );
		$site->addNavigationId( 'enwiktionary' );
		$site->setPath( MediaWikiSite::PATH_PAGE, "https://en.wiktionary.org/wiki/$1" );
		$site->setPath( MediaWikiSite::PATH_FILE, "https://en.wiktionary.org/w/$1" );
		$sites[] = $site;

		return new SiteList( $sites );
	}

	private function getCacheFile() {
		return tempnam( sys_get_temp_dir(), 'mw-test-sitelist' );
	}

}
