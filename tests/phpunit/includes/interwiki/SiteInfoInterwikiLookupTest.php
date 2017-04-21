<?php

/**
 * @covers MediaWiki\Interwiki\SiteInfoInterwikiLookup
 *
 * @group MediaWiki
 * @group Interwiki
 */
use MediaWiki\Interwiki\SiteInfoInterwikiLookup;
use MediaWiki\Site\HashSiteInfoLookup;
use MediaWiki\Site\SiteInfoLookup;

class SiteInfoInterwikiLookupTest extends MediaWikiTestCase {

	private function getSiteInfoLookup() {
		$sites = [
			'enwiki' => [
				SiteInfoLookup::SITE_BASE_URL => 'https://en.wikipedia.org/',
				SiteInfoLookup::SITE_IS_FORWARDABLE => true,
			],
			'enwiktionary' => [
				SiteInfoLookup::SITE_BASE_URL => 'https://en.wiktionary.org/',
				SiteInfoLookup::SITE_IS_FORWARDABLE => true,
			],
			'ruwiki' => [
				SiteInfoLookup::SITE_BASE_URL => 'https://ru.wikipedia.org/',
				SiteInfoLookup::SITE_IS_FORWARDABLE => true,
			],
			'ruwiktionary' => [
				SiteInfoLookup::SITE_BASE_URL => 'https://ru.wiktionary.org/',
				SiteInfoLookup::SITE_IS_FORWARDABLE => true,
			],
			'commonswiki' => [
				SiteInfoLookup::SITE_BASE_URL => 'https://commons.wikimedia.org/',
				SiteInfoLookup::SITE_IS_FORWARDABLE => true,
				SiteInfoLookup::SITE_IS_TRANSCLUDABLE => true,
			],
			'acme' => [
				SiteInfoLookup::SITE_BASE_URL => 'https://www.acme.test/',
				SiteInfoLookup::SITE_LINK_PATH => 'page/$1',
				SiteInfoLookup::SITE_SCRIPT_PATH => 'mw/',
			],
		];

		$ids = [
			// identifiers for interwiki links
			SiteInfoLookup::INTERWIKI_ID => [
				'acme' => 'acme',
				'commons' => 'commonswiki',
				'ru' => 'ruwiki',
				'wiktionary' => 'enwiktionary',
				'wikt' => 'enwiktionary',
			],
			// identifiers for navigation (language links)
			SiteInfoLookup::INTERLANGUAGE_ID => [
				'ru' => 'ruwiki',
				'a' => 'acme',
			],
			// another class of identifiers, to be ignored by testGetAllPrefixes
			'Dummy' => [
				'ruwi' => 'ruwiktionary',
			]
		];

		return new HashSiteInfoLookup( $sites, $ids );
	}

	private function getInterwikiLookup() {
		$lookup = new SiteInfoInterwikiLookup( $this->getSiteInfoLookup() );
		$lookup->setSiteDefaults( [
			SiteInfoLookup::SITE_LINK_PATH => 'wiki/$1',
			SiteInfoLookup::SITE_SCRIPT_PATH => 'w/',
		] );
		return $lookup;
	}

	public function provideIsValidInterwiki() {
		return [
			'interwiki match' => [ 'commons', true ],
			'navigation match' => [ 'a', true ],
			'no match' => [ 'xyzzy', false ],
		];
	}

	/**
	 * @dataProvider provideIsValidInterwiki
	 */
	public function testIsValidInterwiki( $prefix, $expected ) {
		$lookup = $this->getInterwikiLookup();

		$this->assertSame( $expected, $lookup->isValidInterwiki( $prefix ) );
	}

	public function provideFetch() {
		$commons = new Interwiki(
			'commons',
			'https://commons.wikimedia.org/wiki/$1',
			'https://commons.wikimedia.org/w/api.php',
			'commonswiki',
			1,
			1
		);

		$ruwiki = new Interwiki(
			'ru',
			'https://ru.wikipedia.org/wiki/$1',
			'https://ru.wikipedia.org/w/api.php',
			'ruwiki',
			1
		);

		$acme = new Interwiki(
			'a',
			'https://www.acme.test/page/$1',
			'https://www.acme.test/mw/api.php',
			'acme'
		);

		return [
			'commons' => [ 'commons', $commons ],
			'ru' => [ 'ru', $ruwiki ],
			'acme' => [ 'a', $acme ],
			'no match' => [ 'xyzzy', false ],
		];
	}

	/**
	 * @dataProvider provideFetch
	 */
	public function testFetch( $prefix, $expected ) {
		$lookup = $this->getInterwikiLookup();

		$this->assertEquals( $expected, $lookup->fetch( $prefix ) );
	}

	public function testGetAllPrefixes() {
		$lookup = $this->getInterwikiLookup();

		$expected = [ 'wiktionary', 'acme', 'commons', 'ru', 'wikt', 'a' ];

		$this->assertSetEquals( $expected, $lookup->getAllPrefixes() );
	}

	public function testGetAllPrefixes_local() {
		$lookup = $this->getInterwikiLookup();

		$expected = [ 'ru', 'commons', 'wiktionary', 'wikt' ];

		$this->assertSetEquals( $expected, $lookup->getAllPrefixes( true ) );
	}

	public function testGetAllPrefixes_nonlocal() {
		$lookup = $this->getInterwikiLookup();

		$expected = [ 'a', 'acme' ];

		$this->assertSetEquals( $expected, $lookup->getAllPrefixes( false ) );
	}

	public function testSetSiteDefaults() {
		$lookup = $this->getInterwikiLookup();

		$lookup->setSiteDefaults( [
			SiteInfoLookup::SITE_LINK_PATH => 'ARTICLE/$1',
			SiteInfoLookup::SITE_SCRIPT_PATH => 'SCRIPT/',
			SiteInfoLookup::SITE_IS_TRANSCLUDABLE => true,
		] );

		$defaults = [
			SiteInfoLookup::SITE_BASE_URL => '',
			SiteInfoLookup::SITE_LINK_PATH => 'ARTICLE/$1',
			SiteInfoLookup::SITE_SCRIPT_PATH => 'SCRIPT/',
			SiteInfoLookup::SITE_IS_FORWARDABLE => false,
			SiteInfoLookup::SITE_IS_TRANSCLUDABLE => true,
		];

		$this->assertEquals( $defaults, $lookup->getSiteDefaults() );

		$expected = new Interwiki(
			'ru',
			'https://ru.wikipedia.org/ARTICLE/$1',
			'https://ru.wikipedia.org/SCRIPT/api.php',
			'ruwiki',
			1,
			1
		);

		$this->assertEquals( $expected, $lookup->fetch( 'ru' ) );
	}

	private function assertSetEquals( $expected, $actual, $message = '' ) {
		sort( $expected );
		sort( $actual );

		$this->assertEquals( $expected, $actual, $message );
	}

}
