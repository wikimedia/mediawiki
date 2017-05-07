<?php
use MediaWiki\Site\HashSiteInfoLookup;
use MediaWiki\Site\SiteInfoLookup;
use MediaWiki\Site\SiteUrlBuilder;

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
 * @covers MediaWiki\Site\SiteUrlBuilder
 * @group Site
 *
 * @author Daniel Kinzler
 */
class SiteUrlBuilderTest extends PHPUnit_Framework_TestCase {

	private function getUrlBuilder() {
		$sites = [
			'enwiki' => [
				SiteInfoLookup::SITE_TYPE => SiteInfoLookup::TYPE_MEDIAWIKI,
				SiteInfoLookup::SITE_BASE_URL => '//en.wikipedia.org',
				SiteInfoLookup::SITE_LINK_PATH => '/wiki/$1',
				SiteInfoLookup::SITE_SCRIPT_PATH => '/w/',
			],
			'acme' => [
				SiteInfoLookup::SITE_BASE_URL => 'http://www.acme.test/',
				SiteInfoLookup::SITE_LINK_PATH => 'page/',
			],
		];

		$ids = [
			SiteInfoLookup::ALIAS_ID => [
				'wiki' => 'enwiki',
			],
			SiteInfoLookup::INTERWIKI_ID => [
				'acme' => 'acme',
			],
			SiteInfoLookup::NAVIGATION_ID => [
				'en' => 'enwiki',
			],
		];

		$siteInfoLookup = new HashSiteInfoLookup( $sites, $ids );
		$urlBuilder = new SiteUrlBuilder( $siteInfoLookup );
		$urlBuilder->setProtocolExpansionMode( PROTO_HTTPS );

		return $urlBuilder;
	}

	public function testResolveLocalId() {
		$urlBuilder = $this->getUrlBuilder();

		$this->assertSame( 'acme', $urlBuilder->resolveLocalId( SiteInfoLookup::INTERWIKI_ID, 'acme' ) );
		$this->assertSame( 'enwiki', $urlBuilder->resolveLocalId( SiteInfoLookup::NAVIGATION_ID, 'en' ) );
		$this->assertSame( 'enwiki', $urlBuilder->resolveLocalId( SiteInfoLookup::ALIAS_ID, 'wiki' ) );
	}

	public function testSetProtocolExpansionMode() {
		$urlBuilder = $this->getUrlBuilder();

		$enTarget = new TitleValue( 0, 'Kitten', '', 'en' );
		$acmeTarget = new TitleValue( 0, 'Puppy', '', 'acme' );

		$urlBuilder->setProtocolExpansionMode( PROTO_RELATIVE );
		$this->assertSame( '//en.wikipedia.org/wiki/Kitten', $urlBuilder->getLinkUrl( $enTarget ) );
		$this->assertSame( 'http://www.acme.test/page/Puppy', $urlBuilder->getLinkUrl( $acmeTarget ) );

		$urlBuilder->setProtocolExpansionMode( PROTO_HTTPS );
		$this->assertSame( 'https://en.wikipedia.org/wiki/Kitten', $urlBuilder->getLinkUrl( $enTarget ) );
		$this->assertSame( 'http://www.acme.test/page/Puppy', $urlBuilder->getLinkUrl( $acmeTarget ) );

		$urlBuilder->setProtocolExpansionMode( PROTO_CANONICAL );
		$this->assertSame( 'http://en.wikipedia.org/wiki/Kitten', $urlBuilder->getLinkUrl( $enTarget ) );
		$this->assertSame( 'http://www.acme.test/page/Puppy', $urlBuilder->getLinkUrl( $acmeTarget ) );
	}

	public function provideGetUrl() {
		return [
			[ 'enwiki', SiteInfoLookup::SITE_LINK_PATH, null, 'https://en.wikipedia.org/wiki/$1' ],
			[ 'enwiki', SiteInfoLookup::SITE_LINK_PATH, 'Foo&Bar/Cud', 'https://en.wikipedia.org/wiki/Foo%26Bar%2FCud' ],
			[ 'enwiki', SiteInfoLookup::SITE_SCRIPT_PATH, null, 'https://en.wikipedia.org/w/' ],
			[ 'enwiki', SiteInfoLookup::SITE_SCRIPT_PATH, 'thumb.php', 'https://en.wikipedia.org/w/thumb.php' ],
			[ 'acme', SiteInfoLookup::SITE_LINK_PATH, null, 'http://www.acme.test/page/' ],
			[ 'acme', SiteInfoLookup::SITE_LINK_PATH, 'x#y', 'http://www.acme.test/page/x%23y' ],
			[ 'acme', SiteInfoLookup::SITE_SCRIPT_PATH, 'thumb.php', null ],
		];
	}

	/**
	 * @dataProvider provideGetUrl()
	 */
	public function testGetUrl( $siteId, $field, $name, $expected ) {
		$urlBuilder = $this->getUrlBuilder();
		$this->assertSame( $expected, $urlBuilder->getUrl( $siteId, $field, $name ) );
	}

	public function testGetUrl_failsGivenUnknownSite() {
		$urlBuilder = $this->getUrlBuilder();

		$this->setExpectedException( OutOfBoundsException::class );
		$urlBuilder->getUrl( 'xyzzy', SiteInfoLookup::SITE_LINK_PATH );
	}

	public function provideGetScriptUrl() {
		return [
			[ 'enwiki', null, null, 'https://en.wikipedia.org/w/' ],
			[ 'enwiki', 'thumb.php', [], 'https://en.wikipedia.org/w/thumb.php' ],
			[ 'enwiki', 'thumb.php', [ 't' => 'foo' ], 'https://en.wikipedia.org/w/thumb.php?t=foo' ],
			[ 'acme', 'thumb.php', [ 't' => 'foo' ], null ],
		];
	}

	/**
	 * @dataProvider provideGetScriptUrl()
	 */
	public function testGetScriptUrl( $siteId, $name, $parameters, $expected ) {
		$urlBuilder = $this->getUrlBuilder();
		$this->assertSame( $expected, $urlBuilder->getScriptUrl( $siteId, $name, $parameters ) );
	}

	public function provideGetApiUrl() {
		return [
			[ 'enwiki', null, 'https://en.wikipedia.org/w/api.php' ],
			[ 'enwiki', [ 't' => 'foo' ], 'https://en.wikipedia.org/w/api.php?t=foo' ],
			[ 'acme', [ 't' => 'foo' ], null ],
		];
	}

	/**
	 * @dataProvider provideGetApiUrl()
	 */
	public function testGetApiUrl( $siteId, $parameters, $expected ) {
		$urlBuilder = $this->getUrlBuilder();
		$this->assertSame( $expected, $urlBuilder->getApiUrl( $siteId, $parameters ) );
	}

	public function provideGetLinkUrl() {
		return [
			[ new TitleValue( 0, 'Foo&Bar', '', 'en' ), 'https://en.wikipedia.org/wiki/Foo%26Bar' ],
			[ new TitleValue( 0, 'Foo&Bar', '', 'xyzzy' ), null ],
			[ new TitleValue( 0, 'Foo&Bar', 'XXX', 'en' ), 'https://en.wikipedia.org/wiki/Foo%26Bar#XXX' ],
			[ new TitleValue( 0, 'Foo&Bar', 'XXX', 'acme' ), 'http://www.acme.test/page/Foo%26Bar#XXX' ],
		];
	}

	/**
	 * @dataProvider provideGetLinkUrl()
	 */
	public function testGetLinkUrl( $target, $expected ) {
		$urlBuilder = $this->getUrlBuilder();
		$this->assertSame( $expected, $urlBuilder->getLinkUrl( $target ) );
	}

	public function testGetHost() {
		$urlBuilder = $this->getUrlBuilder();

		$this->assertSame( 'www.acme.test', $urlBuilder->getHost( 'acme' ) );
		$this->assertSame( 'en.wikipedia.org', $urlBuilder->getHost( 'enwiki' ) );

		$this->assertSame( 'www.acme.test', $urlBuilder->getHost( 'acme', SiteInfoLookup::INTERWIKI_ID ) );
		$this->assertSame( 'en.wikipedia.org', $urlBuilder->getHost( 'en', SiteInfoLookup::NAVIGATION_ID ) );

		$this->assertNull( $urlBuilder->getHost( 'xyz', SiteInfoLookup::INTERWIKI_ID ) );
		$this->assertNull( $urlBuilder->getHost( 'en', SiteInfoLookup::INTERWIKI_ID ) );
	}

}
