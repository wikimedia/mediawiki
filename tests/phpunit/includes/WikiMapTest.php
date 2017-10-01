<?php
use Wikimedia\Rdbms\DatabaseDomain;

/**
 * @covers WikiMap
 *
 * @group Database
 */
class WikiMapTest extends MediaWikiLangTestCase {

	public function setUp() {
		parent::setUp();

		$conf = new SiteConfiguration();
		$conf->settings = [
			'wgServer' => [
				'enwiki' => 'http://en.example.org',
				'ruwiki' => '//ru.example.org',
				'nopathwiki' => '//nopath.example.org',
				'thiswiki' => '//this.wiki.org'
			],
			'wgArticlePath' => [
				'enwiki' => '/w/$1',
				'ruwiki' => '/wiki/$1',
			],
		];
		$conf->suffixes = [ 'wiki' ];
		$this->setMwGlobals( [
			'wgConf' => $conf,
			'wgLocalDatabases' => [ 'enwiki', 'ruwiki', 'nopathwiki' ],
			'wgCanonicalServer' => '//this.wiki.org',
			'wgDBname' => 'thiswiki',
			'wgDBprefix' => ''
		] );

		TestSites::insertIntoDb();
	}

	public function provideGetWiki() {
		// As provided by $wgConf
		$enwiki = new WikiReference( 'http://en.example.org', '/w/$1' );
		$ruwiki = new WikiReference( '//ru.example.org', '/wiki/$1' );

		// Created from site objects
		$nlwiki = new WikiReference( 'https://nl.wikipedia.org', '/wiki/$1' );
		// enwiktionary doesn't have an interwiki id, thus this falls back to minor = lang code
		$enwiktionary = new WikiReference( 'https://en.wiktionary.org', '/wiki/$1' );

		return [
			'unknown' => [ null, 'xyzzy' ],
			'enwiki (wgConf)' => [ $enwiki, 'enwiki' ],
			'ruwiki (wgConf)' => [ $ruwiki, 'ruwiki' ],
			'nlwiki (sites)' => [ $nlwiki, 'nlwiki', false ],
			'enwiktionary (sites)' => [ $enwiktionary, 'enwiktionary', false ],
			'non MediaWiki site' => [ null, 'spam', false ],
			'boguswiki' => [ null, 'boguswiki' ],
			'nopathwiki' => [ null, 'nopathwiki' ],
		];
	}

	/**
	 * @dataProvider provideGetWiki
	 */
	public function testGetWiki( $expected, $wikiId, $useWgConf = true ) {
		if ( !$useWgConf ) {
			$this->setMwGlobals( [
				'wgConf' => new SiteConfiguration(),
			] );
		}

		$this->assertEquals( $expected, WikiMap::getWiki( $wikiId ) );
	}

	public function provideGetWikiName() {
		return [
			'unknown' => [ 'xyzzy', 'xyzzy' ],
			'enwiki' => [ 'en.example.org', 'enwiki' ],
			'ruwiki' => [ 'ru.example.org', 'ruwiki' ],
			'enwiktionary (sites)' => [ 'en.wiktionary.org', 'enwiktionary' ],
		];
	}

	/**
	 * @dataProvider provideGetWikiName
	 */
	public function testGetWikiName( $expected, $wikiId ) {
		$this->assertEquals( $expected, WikiMap::getWikiName( $wikiId ) );
	}

	public function provideMakeForeignLink() {
		return [
			'unknown' => [ false, 'xyzzy', 'Foo' ],
			'enwiki' => [
				'<a class="external" rel="nofollow" ' .
					'href="http://en.example.org/w/Foo">Foo</a>',
				'enwiki',
				'Foo'
			],
			'ruwiki' => [
				'<a class="external" rel="nofollow" ' .
					'href="//ru.example.org/wiki/%D0%A4%D1%83">вар</a>',
				'ruwiki',
				'Фу',
				'вар'
			],
			'enwiktionary (sites)' => [
				'<a class="external" rel="nofollow" ' .
					'href="https://en.wiktionary.org/wiki/Kitten">Kittens!</a>',
				'enwiktionary',
				'Kitten',
				'Kittens!'
			],
		];
	}

	/**
	 * @dataProvider provideMakeForeignLink
	 */
	public function testMakeForeignLink( $expected, $wikiId, $page, $text = null ) {
		$this->assertEquals(
			$expected,
			WikiMap::makeForeignLink( $wikiId, $page, $text )
		);
	}

	public function provideForeignUserLink() {
		return [
			'unknown' => [ false, 'xyzzy', 'Foo' ],
			'enwiki' => [
				'<a class="external" rel="nofollow" ' .
					'href="http://en.example.org/w/User:Foo">User:Foo</a>',
				'enwiki',
				'Foo'
			],
			'ruwiki' => [
				'<a class="external" rel="nofollow" ' .
					'href="//ru.example.org/wiki/User:%D0%A4%D1%83">вар</a>',
				'ruwiki',
				'Фу',
				'вар'
			],
			'enwiktionary (sites)' => [
				'<a class="external" rel="nofollow" ' .
					'href="https://en.wiktionary.org/wiki/User:Dummy">Whatever</a>',
				'enwiktionary',
				'Dummy',
				'Whatever'
			],
		];
	}

	/**
	 * @dataProvider provideForeignUserLink
	 */
	public function testForeignUserLink( $expected, $wikiId, $user, $text = null ) {
		$this->assertEquals( $expected, WikiMap::foreignUserLink( $wikiId, $user, $text ) );
	}

	public function provideGetForeignURL() {
		return [
			'unknown' => [ false, 'xyzzy', 'Foo' ],
			'enwiki' => [ 'http://en.example.org/w/Foo', 'enwiki', 'Foo' ],
			'enwiktionary (sites)' => [
				'https://en.wiktionary.org/wiki/Testme',
				'enwiktionary',
				'Testme'
			],
			'ruwiki with fragment' => [
				'//ru.example.org/wiki/%D0%A4%D1%83#%D0%B2%D0%B0%D1%80',
				'ruwiki',
				'Фу',
				'вар'
			],
		];
	}

	/**
	 * @dataProvider provideGetForeignURL
	 */
	public function testGetForeignURL( $expected, $wikiId, $page, $fragment = null ) {
		$this->assertEquals( $expected, WikiMap::getForeignURL( $wikiId, $page, $fragment ) );
	}

	/**
	 * @covers WikiMap::getCanonicalServerInfoForAllWikis()
	 */
	public function testGetCanonicalServerInfoForAllWikis() {
		$expected = [
			'thiswiki' => [
				'url' => '//this.wiki.org',
				'parts' => [ 'scheme' => '', 'host' => 'this.wiki.org', 'delimiter' => '//' ]
			],
			'enwiki' => [
				'url' => 'http://en.example.org',
				'parts' => [
					'scheme' => 'http', 'host' => 'en.example.org', 'delimiter' => '://' ]
			],
			'ruwiki' => [
				'url' => '//ru.example.org',
				'parts' => [ 'scheme' => '', 'host' => 'ru.example.org', 'delimiter' => '//' ]
			]
		];

		$this->assertArrayEquals(
			$expected,
			WikiMap::getCanonicalServerInfoForAllWikis(),
			true,
			true
		);
	}

	public function provideGetWikiFromUrl() {
		return [
			[ 'http://this.wiki.org', 'thiswiki' ],
			[ 'https://this.wiki.org', 'thiswiki' ],
			[ 'http://this.wiki.org/$1', 'thiswiki' ],
			[ 'https://this.wiki.org/$2', 'thiswiki' ],
			[ 'http://en.example.org', 'enwiki' ],
			[ 'https://en.example.org', 'enwiki' ],
			[ 'http://en.example.org/$1', 'enwiki' ],
			[ 'https://en.example.org/$2', 'enwiki' ],
			[ 'http://ru.example.org', 'ruwiki' ],
			[ 'https://ru.example.org', 'ruwiki' ],
			[ 'http://ru.example.org/$1', 'ruwiki' ],
			[ 'https://ru.example.org/$2', 'ruwiki' ],
			[ 'http://not.defined.org', false ]
		];
	}

	/**
	 * @dataProvider provideGetWikiFromUrl
	 * @covers WikiMap::getWikiFromUrl()
	 */
	public function testGetWikiFromUrl( $url, $wiki ) {
		$this->assertEquals( $wiki, WikiMap::getWikiFromUrl( $url ) );
	}

	/**
	 * @dataProvider provideGetWikiIdFromDomain
	 * @covers WikiMap::getWikiIdFromDomain()
	 */
	public function testGetWikiIdFromDomain( $domain, $wikiId ) {
		$this->assertEquals( $wikiId, WikiMap::getWikiIdFromDomain( $domain ) );
	}

	public function provideGetWikiIdFromDomain() {
		return [
			[ 'db-prefix', 'db-prefix' ],
			[ wfWikiID(), wfWikiID() ],
			[ new DatabaseDomain( 'db-dash', null, 'prefix' ), 'db-dash-prefix' ]
		];
	}
}
