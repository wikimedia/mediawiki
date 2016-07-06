<?php

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
			],
			'wgArticlePath' => [
				'enwiki' => '/w/$1',
				'ruwiki' => '/wiki/$1',
			],
		];
		$conf->suffixes = [ 'wiki' ];
		$this->setMwGlobals( [
			'wgConf' => $conf,
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

}
