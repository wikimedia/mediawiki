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
 * @covers SiteStoreLookup
 * @group Site
 *
 * @licence GNU GPL v2+
 * @author Katie Filbert < aude.wiki@gmail.com >
 */
class SiteStoreLookupTest extends PHPUnit_Framework_TestCase {

	/**
	 * @dataProvider getSitesProvider
	 */
	public function testGetSites( array $expected, array $sites, $globalIds ) {
		$lookup = $this->newSiteStoreLookup( $sites );
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
		$lookup = $this->newSiteStoreLookup( $sites );
		$this->assertEquals( $sites['enwiktionary'], $lookup->getSite( 'enwiktionary' ) );
	}

	public function getGetSite_notFound() {
		$lookup = $this->newSiteStoreLookup( array() );
		$this->setExpectedException( 'OutOfBoundsException' );
		$lookup->getSite( 'foowiki' );
	}

	private function newSiteStoreLookup( array $sites ) {
		$siteStore = $this->getSiteStore( $sites );
		return new SiteStoreLookup( $siteStore );
	}

	private function getSiteStore( $sites ) {
		$siteStore = $this->getMockBuilder( 'SiteStore' )
			->disableOriginalConstructor()
			->getMock();

		$siteStore->expects( $this->any() )
			->method( 'getSites' )
			->will( $this->returnValue( new SiteList( $sites ) ) );

		$siteStore->expects( $this->any() )
			->method( 'getSite' )
			->will( $this->returnCallback( function( $globalId ) use ( $sites ) {
					$siteList = new SiteList( $sites );
					return $siteList->hasSite( $globalId ) ? $siteList->getSite( $globalId ) : null;
				} ) );

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

}
