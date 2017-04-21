<?php
use MediaWiki\Site\HashSiteInfoLookup;
use MediaWiki\Site\SiteInfoLookup;

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
 * @covers MediaWiki\Site\HashSiteInfoLookup
 * @group Site
 *
 * @author Daniel Kinzler
 */
class HashSiteInfoLookupTest extends PHPUnit_Framework_TestCase {

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

	public function provideGetSiteInfo() {
		$enwiki = [
			SiteInfoLookup::SITE_TYPE => SiteInfoLookup::TYPE_MEDIAWIKI,
			SiteInfoLookup::SITE_LANGUAGE => 'en',
			SiteInfoLookup::SITE_FAMILY => 'wikipedia',
			SiteInfoLookup::SITE_BASE_URL => 'https://en.wikipedia.org/',
			SiteInfoLookup::SITE_IS_FORWARDABLE => true,
		];
		$commons = [
			SiteInfoLookup::SITE_TYPE => SiteInfoLookup::TYPE_MEDIAWIKI,
			SiteInfoLookup::SITE_LANGUAGE => 'en',
			SiteInfoLookup::SITE_FAMILY => 'commons',
			SiteInfoLookup::SITE_BASE_URL => 'https://commons.wikimedia.org/',
			SiteInfoLookup::SITE_IS_FORWARDABLE => true,
			SiteInfoLookup::SITE_IS_TRANSCLUDABLE => true,
		];
		$acme = [
			SiteInfoLookup::SITE_LANGUAGE => 'en',
			SiteInfoLookup::SITE_BASE_URL => 'https://www.acme.test/',
			SiteInfoLookup::SITE_LINK_PATH => 'page/$1',
			SiteInfoLookup::SITE_SCRIPT_PATH => '',
		];

		$defaults = [
			SiteInfoLookup::SITE_LINK_PATH => 'wiki/$1',
			SiteInfoLookup::SITE_SCRIPT_PATH => 'w/',
		];

		return [
			'commonswiki' => [ 'commonswiki', [], $commons ],
			'enwiki' => [ 'enwiki', [], $enwiki ],
			'wiki alias' => [ 'wiki', [], $enwiki ],
			'acme' => [ 'acme', [], $acme ],
			'acme+defaults' => [ 'acme', $defaults, array_merge( $defaults, $acme ) ],
		];
	}

	/**
	 * @dataProvider provideGetSiteInfo
	 */
	public function testGetSiteInfo( $wikiId, $defaults, $expected ) {
		$lookup = $this->getSiteInfoLookup();

		$this->assertEquals( $expected, $lookup->getSiteInfo( $wikiId, $defaults ) );
	}

	public function testGetSiteInfo_fail() {
		$lookup = $this->getSiteInfoLookup();

		$this->setExpectedException( OutOfBoundsException::class );
		$lookup->getSiteInfo( 'asdf' );
	}

	public function testResolveLocalId() {
		$this->fail( 'TBD' );
	}

	public function testGetLocalId() {
		$this->fail( 'TBD' );
	}

	public function testGetAssociatedLocalIds() {
		$this->fail( 'TBD' );
	}

	public function testListGroupMembers() {
		$this->fail( 'TBD' );
	}

	public function testGetIdMap() {
		$this->fail( 'TBD' );
	}

	public function testListSites() {
		$this->fail( 'TBD' );
	}

}
