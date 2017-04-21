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
				SiteInfoLookup::SITE_IS_LOCAL => true,
			],
			'enwiktionary' => [
				SiteInfoLookup::SITE_BASE_URL => 'https://en.wiktionary.org/',
				SiteInfoLookup::SITE_IS_LOCAL => true,
			],
			'ruwiki' => [
				SiteInfoLookup::SITE_BASE_URL => 'https://ru.wikipedia.org/',
				SiteInfoLookup::SITE_IS_LOCAL => true,
			],
			'ruwiktionary' => [
				SiteInfoLookup::SITE_BASE_URL => 'https://ru.wiktionary.org/',
				SiteInfoLookup::SITE_IS_LOCAL => true,
			],
			'commonswiki' => [
				SiteInfoLookup::SITE_BASE_URL => 'https://commons.wikimedia.org/',
				SiteInfoLookup::SITE_IS_LOCAL => true,
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
			SiteInfoLookup::NAVIGATION_ID => [
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

	private $expectedInterwikiRows = [
		'commons' => [
			'iw_prefix' => 'commons',
			'iw_url' => 'https://commons.wikimedia.org/wiki/$1',
			'iw_api' => 'https://commons.wikimedia.org/w/api.php',
			'iw_wikiid' => 'commonswiki',
			'iw_local' => 1,
			'iw_trans' => 1,
		],

		'ru' => [
			'iw_prefix' => 'ru',
			'iw_url' => 'https://ru.wikipedia.org/wiki/$1',
			'iw_api' => 'https://ru.wikipedia.org/w/api.php',
			'iw_wikiid' => 'ruwiki',
			'iw_local' => 1,
			'iw_trans' => 0,
		],

		'wiktionary' => [
			'iw_prefix' => 'wiktionary',
			'iw_url' => 'https://en.wiktionary.org/wiki/$1',
			'iw_api' => 'https://en.wiktionary.org/w/api.php',
			'iw_wikiid' => 'enwiktionary',
			'iw_local' => 1,
			'iw_trans' => 0,
		],

		'wikt' => [
			'iw_prefix' => 'wikt',
			'iw_url' => 'https://en.wiktionary.org/wiki/$1',
			'iw_api' => 'https://en.wiktionary.org/w/api.php',
			'iw_wikiid' => 'enwiktionary',
			'iw_local' => 1,
			'iw_trans' => 0,
		],

		'a' => [
			'iw_prefix' => 'a',
			'iw_url' => 'https://www.acme.test/page/$1',
			'iw_api' => 'https://www.acme.test/mw/api.php',
			'iw_wikiid' => 'acme',
			'iw_local' => 0,
			'iw_trans' => 0
		],

		'acme' => [
			'iw_prefix' => 'acme',
			'iw_url' => 'https://www.acme.test/page/$1',
			'iw_api' => 'https://www.acme.test/mw/api.php',
			'iw_wikiid' => 'acme',
			'iw_local' => 0,
			'iw_trans' => 0
		],

	];

	/**
	 * @param string[] $prefixes
	 *
	 * return array[]
	 */
	private function getInterwikiRows( $prefixes ) {
		$rows = [];

		foreach ( $prefixes as $prefix ) {
			$rows[] = $this->expectedInterwikiRows[$prefix];
		}

		return $rows;
	}

	/**
	 * @param string $prefix
	 *
	 * @return bool|Interwiki
	 */
	private function getInterwiki( $prefix ) {
		if ( !isset( $this->expectedInterwikiRows[$prefix] ) ) {
			return false;
		}

		$row = $this->expectedInterwikiRows[$prefix];

		return new Interwiki(
			$row['iw_prefix'],
			$row['iw_url'],
			$row['iw_api'],
			$row['iw_wikiid'],
			$row['iw_local'],
			$row['iw_trans']
		);
	}

	public function provideFetch() {
		return [
			'commons' => [ 'commons', $this->getInterwiki( 'commons' ) ],
			'ru' => [ 'ru', $this->getInterwiki( 'ru' ) ],
			'a' => [ 'a', $this->getInterwiki( 'a' ) ],
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

		$expected = $this->getInterwikiRows( [ 'wiktionary', 'acme', 'commons', 'ru', 'wikt', 'a' ] );

		$this->assertSameRows( $expected, $lookup->getAllPrefixes(), 'iw_prefix' );
	}

	public function testGetAllPrefixes_local() {
		$lookup = $this->getInterwikiLookup();

		$expected = $this->getInterwikiRows( [ 'ru', 'commons', 'wiktionary', 'wikt' ] );

		$this->assertSameRows( $expected, $lookup->getAllPrefixes( true ), 'iw_prefix' );
	}

	public function testGetAllPrefixes_nonlocal() {
		$lookup = $this->getInterwikiLookup();

		$expected = $this->getInterwikiRows( [ 'a', 'acme' ] );

		$this->assertSameRows( $expected, $lookup->getAllPrefixes( false ), 'iw_prefix' );
	}

	public function testSetSiteDefaults() {
		$lookup = $this->getInterwikiLookup();

		$lookup->setSiteDefaults( [
			SiteInfoLookup::SITE_LINK_PATH => 'LINK/$1',
			SiteInfoLookup::SITE_SCRIPT_PATH => 'SCRIPT/',
			SiteInfoLookup::SITE_IS_TRANSCLUDABLE => true,
		] );

		$defaults = [
			SiteInfoLookup::SITE_BASE_URL => '',
			SiteInfoLookup::SITE_LINK_PATH => 'LINK/$1',
			SiteInfoLookup::SITE_SCRIPT_PATH => 'SCRIPT/',
			SiteInfoLookup::SITE_IS_LOCAL => false,
			SiteInfoLookup::SITE_IS_TRANSCLUDABLE => true,
		];

		$this->assertEquals( $defaults, $lookup->getSiteDefaults() );

		$expected = new Interwiki(
			'ru',
			'https://ru.wikipedia.org/LINK/$1',
			'https://ru.wikipedia.org/SCRIPT/api.php',
			'ruwiki',
			1,
			1
		);

		$this->assertEquals( $expected, $lookup->fetch( 'ru' ) );
	}

	private function assertSameRows( $expected, $actual, $key ) {
		$expected = $this->keyByField( $expected, $key );
		$actual = $this->keyByField( $actual, $key );

		ksort( $expected );
		ksort( $actual );

		$missingKeys = array_diff( array_keys( $expected ), array_keys( $actual ) );
		$extraKeys = array_diff( array_keys( $actual ), array_keys( $expected ) );

		$this->assertEquals( $missingKeys, $extraKeys, "Prefixes" );

		foreach ( $expected as $key => $expRow ) {
			$actRow = $actual[$key];

			ksort( $expRow );
			ksort( $actRow );

			$this->assertEquals( $expRow, $actRow, "Fields for prefix $key" );
		}
	}

	/**
	 * @param array[] $listOfRows
	 * @param string $field
	 *
	 * @return array[]
	 */
	private function keyByField( array $listOfRows, $field ) {
		$result = [];

		foreach ( $listOfRows as $row ) {
			$key = $row[$field];
			$result[$key] = $row;
		}

		return $result;
	}

}
