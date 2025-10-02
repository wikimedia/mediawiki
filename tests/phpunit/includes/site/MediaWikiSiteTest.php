<?php
/**
 * @license GPL-2.0-or-later
 * @file
 */
namespace MediaWiki\Tests\Site;

use MediaWiki\MainConfigNames;
use MediaWiki\Site\MediaWikiSite;

/**
 * @covers \MediaWiki\Site\MediaWikiSite
 * @group Site
 * @author Jeroen De Dauw < jeroendedauw@gmail.com >
 */
class MediaWikiSiteTest extends SiteTest {

	public function testNormalizePageTitle() {
		$this->overrideConfigValue( MainConfigNames::CapitalLinks, true );

		$site = new MediaWikiSite();
		$site->setGlobalId( 'enwiki' );

		// NOTE: this does not actually call out to the enwiki site to perform the normalization,
		//      but uses a local Title object to do so. This is hardcoded on SiteLink::normalizePageTitle
		//      for the case that MW_PHPUNIT_TEST is set.
		$this->assertEquals( 'Foo', $site->normalizePageName( ' foo ' ) );
	}

	public static function fileUrlProvider() {
		return [
			// url, filepath, path arg, expected
			[ 'https://en.wikipedia.org', '/w/$1', 'api.php', 'https://en.wikipedia.org/w/api.php' ],
			[ 'https://en.wikipedia.org', '/w/', 'api.php', 'https://en.wikipedia.org/w/' ],
			[
				'https://en.wikipedia.org',
				'/foo/page.php?name=$1',
				'api.php',
				'https://en.wikipedia.org/foo/page.php?name=api.php'
			],
			[
				'https://en.wikipedia.org',
				'/w/$1',
				'',
				'https://en.wikipedia.org/w/'
			],
			[
				'https://en.wikipedia.org',
				'/w/$1',
				'foo/bar/api.php',
				'https://en.wikipedia.org/w/foo/bar/api.php'
			],
		];
	}

	/**
	 * @dataProvider fileUrlProvider
	 */
	public function testGetFileUrl( $url, $filePath, $pathArgument, $expected ) {
		$site = new MediaWikiSite();
		$site->setFilePath( $url . $filePath );

		$this->assertEquals( $expected, $site->getFileUrl( $pathArgument ) );
	}

	public static function provideGetPageUrl() {
		return [
			// path, page, expected substring
			[ 'http://acme.test/wiki/$1', 'Berlin', '/wiki/Berlin' ],
			[ 'http://acme.test/wiki/', 'Berlin', '/wiki/' ],
			[ 'http://acme.test/w/index.php?title=$1', 'Berlin', '/w/index.php?title=Berlin' ],
			[ 'http://acme.test/wiki/$1', '', '/wiki/' ],
			[ 'http://acme.test/wiki/$1', 'Berlin/subpage', '/wiki/Berlin/subpage' ],
			[ 'http://acme.test/wiki/$1', 'Berlin/subpage with spaces', '/wiki/Berlin/subpage_with_spaces' ],
			[ 'http://acme.test/wiki/$1', 'Cork (city)   ', '/Cork_(city)' ],
			[ 'http://acme.test/wiki/$1', 'M&M', '/wiki/M%26M' ],
		];
	}

	/**
	 * @dataProvider provideGetPageUrl
	 */
	public function testGetPageUrl( $path, $page, $expected ) {
		$site = new MediaWikiSite();
		$site->setLinkPath( $path );

		$this->assertStringContainsString( $path, $site->getPageUrl() );
		$this->assertStringContainsString( $expected, $site->getPageUrl( $page ) );
	}
}
