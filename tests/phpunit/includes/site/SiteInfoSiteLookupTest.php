<?php
use MediaWiki\Site\HashSiteInfoLookup;
use MediaWiki\Site\SiteInfoLookup;
use MediaWiki\Site\SiteInfoSiteLookup;

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
 *
 * @ingroup Site
 * @ingroup Test
 *
 * @covers MediaWiki\Site\SiteInfoSiteLookup
 * @group Site
 *
 * @author Daniel Kinzler
 */
class SiteInfoSiteLookupTest extends PHPUnit_Framework_TestCase {

	private function getSiteInfoLookup() {
		$sites = [
			'enwiki' => [
				SiteInfoLookup::SITE_TYPE => SiteInfoLookup::TYPE_MEDIAWIKI,
				SiteInfoLookup::SITE_LANGUAGE => 'en',
				SiteInfoLookup::SITE_FAMILY => 'wikipedia',
				SiteInfoLookup::SITE_BASE_URL => 'https://en.wikipedia.org/',
				SiteInfoLookup::SITE_IS_FORWARDABLE => true,
			],
			'commonswiki' => [
				SiteInfoLookup::SITE_TYPE => SiteInfoLookup::TYPE_MEDIAWIKI,
				SiteInfoLookup::SITE_LANGUAGE => 'en',
				SiteInfoLookup::SITE_FAMILY => 'commons',
				SiteInfoLookup::SITE_BASE_URL => 'https://commons.wikimedia.org/',
				SiteInfoLookup::SITE_IS_FORWARDABLE => true,
				SiteInfoLookup::SITE_IS_TRANSCLUDABLE => true,
			],
			'acme' => [
				SiteInfoLookup::SITE_LANGUAGE => 'en',
				SiteInfoLookup::SITE_BASE_URL => 'https://www.acme.test/',
				SiteInfoLookup::SITE_LINK_PATH => 'page/$1',
				SiteInfoLookup::SITE_SCRIPT_PATH => '',
			],
		];

		$ids = [
			SiteInfoLookup::ALIAS_ID => [
				'wiki' => 'enwiki',
			],
			SiteInfoLookup::INTERWIKI_ID => [
				'en' => 'enwiki',
				'e' => 'enwiki',
				'acme' => 'acme',
				'commons' => 'commonswiki',
			],
			SiteInfoLookup::INTERLANGUAGE_ID => [
				'en' => 'enwiki',
				'a' => 'acme',
			],
			'Dummy' => [
				'e' => 'enwiki',
			],
		];

		return new HashSiteInfoLookup( $sites, $ids );
	}

	private function getSiteLookup() {
		$lookup = new SiteInfoSiteLookup( $this->getSiteInfoLookup() );
		$lookup->setSiteDefaults( [
			SiteInfoLookup::SITE_LINK_PATH => 'wiki/$1',
			SiteInfoLookup::SITE_SCRIPT_PATH => 'w/',
		] );
		return $lookup;
	}

	public function provideGetSite() {
		$commons = new MediaWikiSite();
		$commons->setGlobalId( 'commonswiki' );
		$commons->setGroup( 'commons' );
		$commons->setLanguageCode( 'en' );
		$commons->addLocalId( Site::ID_INTERWIKI, 'commons' );
		$commons->setForward( true );
		$commons->setExtraData( [
			'paths' => [
				Site::PATH_LINK => 'wiki/$1',
				MediaWikiSite::PATH_PAGE => 'wiki/$1',
				MediaWikiSite::PATH_FILE => 'w/',
			]
		] );

		$enwiki = new MediaWikiSite();
		$enwiki->setGlobalId( 'enwiki' );
		$enwiki->setGroup( 'wikipedia' );
		$enwiki->setLanguageCode( 'en' );
		$enwiki->addLocalId( Site::ID_INTERWIKI, 'en' );
		$enwiki->addLocalId( Site::ID_INTERWIKI, 'e' );
		$enwiki->addLocalId( Site::ID_EQUIVALENT, 'en' );
		$enwiki->setForward( true );
		$enwiki->setExtraData( [
			'paths' => [
				Site::PATH_LINK => 'wiki/$1',
				MediaWikiSite::PATH_PAGE => 'wiki/$1',
				MediaWikiSite::PATH_FILE => 'w/',
			]
		] );

		$acme = new Site();
		$acme->setGlobalId( 'acme' );
		$acme->setLanguageCode( 'en' );
		$acme->addLocalId( Site::ID_INTERWIKI, 'acme' );
		$acme->addLocalId( Site::ID_EQUIVALENT, 'a' );
		$acme->setExtraData( [
			'paths' => [
				Site::PATH_LINK => 'page/$1',
				MediaWikiSite::PATH_PAGE => 'page/$1',
				MediaWikiSite::PATH_FILE => '',
			]
		] );

		return [
			'commonswiki' => [ 'commonswiki', $commons ],
			'enwiki' => [ 'enwiki', $enwiki ],
			'wiki alias' => [ 'wiki', $enwiki ],
			'acme' => [ 'acme', $acme ],
			'no match' => [ 'xyzzy', null ],
		];
	}

	/**
	 * @dataProvider provideGetSite
	 */
	public function testGetSite( $wikiId, $expected ) {
		$lookup = $this->getSiteLookup();

		$this->assertEquals( $expected, $lookup->getSite( $wikiId ) );
	}

	public function testSites() {
		$lookup = $this->getSiteLookup();

		$sites = $lookup->getSites();

		$this->assertNotNull( $sites->getSite( 'enwiki' ), 'enwiki' );
		$this->assertNotNull( $sites->getSite( 'commonswiki' ), 'commonswiki' );
		$this->assertNotNull( $sites->getSite( 'acme' ), 'acme' );

		$this->assertSame( 'enwiki', $sites->getSiteByNavigationId( 'en' )->getGlobalId() );
		$this->assertSame( 'acme', $sites->getSiteByNavigationId( 'a' )->getGlobalId() );

		$this->assertSetEquals( [ 'acme', 'enwiki', 'commonswiki' ], $sites->getGlobalIdentifiers() );
		$this->assertSetEquals( [ 'enwiki' ], $sites->getGroup( 'wikipedia' )->getGlobalIdentifiers() );
	}

	public function testSetSiteDefaults() {
		$lookup = $this->getSiteLookup();

		$lookup->setSiteDefaults( [
			SiteInfoLookup::SITE_LINK_PATH => 'ARTICLE/$1',
			SiteInfoLookup::SITE_SCRIPT_PATH => 'SCRIPT/',
			SiteInfoLookup::SITE_FAMILY => 'OTHER',
		] );

		$defaults = [
			SiteInfoLookup::SITE_TYPE => SiteInfoLookup::TYPE_UNKNOWN,
			SiteInfoLookup::SITE_BASE_URL => '',
			SiteInfoLookup::SITE_LINK_PATH => 'ARTICLE/$1',
			SiteInfoLookup::SITE_SCRIPT_PATH => 'SCRIPT/',
			SiteInfoLookup::SITE_IS_FORWARDABLE => false,
			SiteInfoLookup::SITE_FAMILY => 'OTHER',
			SiteInfoLookup::SITE_LANGUAGE => null,
		];

		$this->assertEquals( $defaults, $lookup->getSiteDefaults() );

		$enwiki = new MediaWikiSite();
		$enwiki->setGlobalId( 'enwiki' );
		$enwiki->setGroup( 'wikipedia' );
		$enwiki->setLanguageCode( 'en' );
		$enwiki->addLocalId( Site::ID_INTERWIKI, 'en' );
		$enwiki->addLocalId( Site::ID_INTERWIKI, 'e' );
		$enwiki->addLocalId( Site::ID_EQUIVALENT, 'en' );
		$enwiki->setForward( true );
		$enwiki->setExtraData( [
			'paths' => [
				Site::PATH_LINK => 'ARTICLE/$1',
				MediaWikiSite::PATH_PAGE => 'ARTICLE/$1',
				MediaWikiSite::PATH_FILE => 'SCRIPT/',
			]
		] );

		$this->assertEquals( $enwiki, $lookup->getSite( 'enwiki' ) );
	}

	private function assertSetEquals( $expected, $actual, $message = '' ) {
		sort( $expected );
		sort( $actual );

		$this->assertEquals( $expected, $actual, $message );
	}

}
