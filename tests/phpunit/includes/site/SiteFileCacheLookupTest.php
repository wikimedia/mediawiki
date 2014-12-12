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
 * @covers SiteFileCacheLookup
 * @group Site
 *
 * @licence GNU GPL v2+
 * @author Katie Filbert < aude.wiki@gmail.com >
 */
class SiteFileCacheLookupTest extends PHPUnit_Framework_TestCase {

	private $cacheFile;

	protected function setUp() {
		$this->cacheFile = $this->getCacheFile();
	}

	protected function tearDown() {
		unlink( $this->cacheFile );
	}

	/**
	 * @dataProvider getSitesProvider
	 */
	public function testGetSites( array $expected, array $sites, $globalIds ) {
		$lookup = $this->newSiteFileBasedLookup( $sites );
		$this->assertEquals( $expected, $lookup->getSites( $globalIds ) );
	}

	public function getSitesProvider() {
		$sites = $this->getSites();

		$cases = array();
		$cases[] = array( $sites, $sites, null );

		$expected = array(
			'foobar' => $sites['foobar'],
			'mywiki' => $sites['mywiki']
		);

		$cases[] = array( $expected, $sites, array( 'foobar', 'mywiki' ) );
		$cases[] = array( array(), array(), array( 'foobar' ) );

		return $cases;
	}

	public function testGetSite() {
		$sites = $this->getSites();
		$lookup = $this->newSiteFileBasedLookup( $sites );
		$this->assertEquals( $sites['enwiktionary'], $lookup->getSite( 'enwiktionary' ) );
	}

	public function getGetSite_notFound() {
		$lookup = $this->newSiteFileBasedLookup( array() );
		$this->assertIsNull( $lookup->getSite( 'foowiki' ) );
	}

	private function newSiteFileBasedLookup( array $sites ) {
		$cacheBuilder = $this->newSiteFileCacheBuilder( $sites );
		$cacheBuilder->build();

		return new SiteFileCacheLookup( $this->cacheFile );
	}

	private function newSiteFileCacheBuilder( $sites ) {
		return new SiteFileCacheBuilder(
			$this->getSiteStore( $sites ),
			$this->cacheFile
		);
	}

	private function getSiteStore( $sites ) {
		$siteStore = $this->getMockBuilder( 'SiteStore' )
			->disableOriginalConstructor()
			->getMock();

		$siteStore->expects( $this->any() )
			->method( 'getSites' )
			->will( $this->returnValue( new SiteList( $sites ) ) );

		return $siteStore;
	}

	private function getSites() {
		$sites = array();

		$site = new Site();
		$site->setGlobalId( 'foobar' );
		$sites['foobar'] = $site;

		$site = new Site();
		$site->setGlobalId( 'mywiki' );
		$sites['mywiki'] = $site;

		$site = new MediaWikiSite();
		$site->setGlobalId( 'enwiktionary' );
		$site->setGroup( 'wiktionary' );
		$site->setLanguageCode( 'en' );
		$site->addNavigationId( 'enwiktionary' );
		$site->setPath( MediaWikiSite::PATH_PAGE, "https://en.wiktionary.org/wiki/$1" );
		$site->setPath( MediaWikiSite::PATH_FILE, "https://en.wiktionary.org/w/$1" );
		$sites['enwiktionary'] = $site;

		return $sites;
	}

	private function getCacheFile() {
		return tempnam( sys_get_temp_dir(), 'mw-test-sitelist' );
	}

}
