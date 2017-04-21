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
				SiteInfoLookup::SITE_IS_LOCAL => true,
			],
			'commonswiki' => [
				SiteInfoLookup::SITE_TYPE => SiteInfoLookup::TYPE_MEDIAWIKI,
				SiteInfoLookup::SITE_LANGUAGE => 'en',
				SiteInfoLookup::SITE_FAMILY => 'commons',
				SiteInfoLookup::SITE_BASE_URL => 'https://commons.wikimedia.org/',
				SiteInfoLookup::SITE_IS_LOCAL => true,
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
				'e' => 'wiki',
				'acme' => 'acme',
				'commons' => 'commonswiki',
			],
			SiteInfoLookup::NAVIGATION_ID => [
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
			SiteInfoLookup::SITE_IS_LOCAL => true,
		];
		$commons = [
			SiteInfoLookup::SITE_TYPE => SiteInfoLookup::TYPE_MEDIAWIKI,
			SiteInfoLookup::SITE_LANGUAGE => 'en',
			SiteInfoLookup::SITE_FAMILY => 'commons',
			SiteInfoLookup::SITE_BASE_URL => 'https://commons.wikimedia.org/',
			SiteInfoLookup::SITE_IS_LOCAL => true,
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

	public function testGetSiteId() {
		$lookup = $this->getSiteInfoLookup();

		$this->assertSame( 'acme', $lookup->getSiteId( 'acme', SiteInfoLookup::INTERWIKI_ID ) );
		$this->assertSame( 'enwiki', $lookup->getSiteId( 'en', SiteInfoLookup::INTERWIKI_ID ) );
		$this->assertSame( 'enwiki', $lookup->getSiteId( 'en', SiteInfoLookup::NAVIGATION_ID ) );
		$this->assertSame( 'enwiki', $lookup->getSiteId( 'wiki', SiteInfoLookup::ALIAS_ID ) );

		$this->assertSame(
			'commonswiki',
			$lookup->getSiteId( 'commons', SiteInfoLookup::INTERWIKI_ID )
		);

		$this->assertSame(
			'commonswiki',
			$lookup->getSiteId( 'commons', 'Xyzzy', SiteInfoLookup::INTERWIKI_ID ),
			'search multiple scopes'
		);

		$this->assertSame(
			'commonswiki',
			$lookup->getSiteId( 'commons' ),
			'search all scopes'
		);

		$this->assertNull(
			$lookup->getSiteId( 'wiki', SiteInfoLookup::INTERWIKI_ID ),
			'id unknown in interwiki scope'
		);

		$this->assertNull(
			$lookup->getSiteId( 'enwiki', SiteInfoLookup::INTERWIKI_ID ),
			'site id is not a local id'
		);

		$this->assertNull(
			$lookup->getSiteId( 'en', 'Xyzzy' ),
			'unknown scope'
		);
	}

	public function testGetLocalId() {
		$lookup = $this->getSiteInfoLookup();

		$this->assertSame( 'acme', $lookup->getLocalId( 'acme', SiteInfoLookup::INTERWIKI_ID ) );
		$this->assertSame( 'acme', $lookup->getLocalId( 'acme', SiteInfoLookup::INTERWIKI_ID ) );
		$this->assertSame( 'en', $lookup->getLocalId( 'enwiki', SiteInfoLookup::NAVIGATION_ID ) );
		$this->assertSame( 'wiki', $lookup->getLocalId( 'enwiki', SiteInfoLookup::ALIAS_ID ) );

		$this->assertSame(
			'commons',
			$lookup->getLocalId( 'commonswiki', SiteInfoLookup::INTERWIKI_ID )
		);

		$this->setExpectedException( OutOfBoundsException::class );
		$lookup->getLocalId( 'xyz', SiteInfoLookup::INTERWIKI_ID );
	}

	public function testGetAssociatedLocalIds() {
		$lookup = $this->getSiteInfoLookup();

		$acmeIds = [
			SiteInfoLookup::INTERWIKI_ID => [ 'acme' ],
			SiteInfoLookup::NAVIGATION_ID => [ 'a' ],
			SiteInfoLookup::ALIAS_ID => [],
			'Dummy' => [],
		];
		$this->assertEquals( $acmeIds, $lookup->getAssociatedLocalIds( 'acme' ) );

		$enwikiIds = [
			SiteInfoLookup::ALIAS_ID => [ 'wiki' ],
			SiteInfoLookup::INTERWIKI_ID => [ 'en' ],
			SiteInfoLookup::NAVIGATION_ID => [ 'en' ],
			'Dummy' => [ 'e' ],
		];
		$this->assertEquals( $enwikiIds, $lookup->getAssociatedLocalIds( 'enwiki' ) );

		$commonsIds = [
			SiteInfoLookup::INTERWIKI_ID => [ 'commons' ],
			SiteInfoLookup::NAVIGATION_ID => [],
			SiteInfoLookup::ALIAS_ID => [],
			'Dummy' => [],
		];
		$this->assertEquals( $commonsIds, $lookup->getAssociatedLocalIds( 'commonswiki' ) );

		$this->setExpectedException( OutOfBoundsException::class );
		$lookup->getAssociatedLocalIds( 'xyz' );
	}

	public function testListGroupMembers() {
		$lookup = $this->getSiteInfoLookup();

		$this->assertEquals(
			[ 'enwiki', 'commonswiki', 'acme', ],
			$lookup->listGroupMembers( SiteInfoLookup::SITE_LANGUAGE, 'en' )
		);

		$this->assertEquals(
			[ 'enwiki' ],
			$lookup->listGroupMembers( SiteInfoLookup::SITE_FAMILY, 'wikipedia' )
		);

		$this->assertEquals(
			[],
			$lookup->listGroupMembers( 'foobar', 'xyz' )
		);
	}

	public function testGetIdMap() {
		$lookup = $this->getSiteInfoLookup();

		$aliasIds = [
			'wiki' => 'enwiki',
		];
		$this->assertEquals(
			$aliasIds,
			$lookup->getIdMap( SiteInfoLookup::ALIAS_ID ),
			SiteInfoLookup::ALIAS_ID
		);

		$interwikiIds = [
			'en' => 'enwiki',
			'e' => 'wiki',
			'acme' => 'acme',
			'commons' => 'commonswiki',
		];
		$this->assertEquals(
			$interwikiIds,
			$lookup->getIdMap( SiteInfoLookup::INTERWIKI_ID ),
			SiteInfoLookup::INTERWIKI_ID
		);

		$navigationIds = [
			'en' => 'enwiki',
			'a' => 'acme',
		];
		$this->assertEquals(
			$navigationIds,
			$lookup->getIdMap( SiteInfoLookup::NAVIGATION_ID ),
			SiteInfoLookup::NAVIGATION_ID
		);

		$dummyIds = [
			'e' => 'enwiki',
		];
		$this->assertEquals(
			$dummyIds,
			$lookup->getIdMap( 'Dummy' ),
			'Dummy'
		);
	}

	public function testListSites() {
		$lookup = $this->getSiteInfoLookup();

		$this->assertEquals( [ 'enwiki', 'commonswiki', 'acme' ], $lookup->listSites() );
	}

}
