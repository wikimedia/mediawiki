<?php

/**
 * @covers WikiMap
 */

class WikiMapTest extends MediaWikiLangTestCase {

	public function setUp() {
		parent::setUp();

		$conf = new SiteConfiguration();
		$conf->settings = array(
			'wgServer' => array(
				'enwiki' => 'http://en.example.org',
				'ruwiki' => '//ru.example.org',
			),
			'wgArticlePath' => array(
				'enwiki' => '/w/$1',
				'ruwiki' => '/wiki/$1',
			),
		);
		$conf->suffixes = array( 'wiki' );
		$this->setMwGlobals( array(
			'wgConf' => $conf,
		) );
	}

	public function provideGetWiki() {
		$enwiki = new WikiReference( 'wiki', 'en', 'http://en.example.org', '/w/$1' );
		$ruwiki = new WikiReference( 'wiki', 'ru', '//ru.example.org', '/wiki/$1' );

		return array(
			'unknown' => array( false, 'xyzzy' ),
			'enwiki' => array( $enwiki, 'enwiki' ),
			'ruwiki' => array( $ruwiki, 'ruwiki' ),
		);
	}

	/**
	 * @dataProvider provideGetWiki
	 */
	public function testGetWiki( $expected, $wikiId ) {
		$this->assertEquals( $expected, WikiMap::getWiki( $wikiId ) );
	}

	public function provideGetWikiName() {
		return array(
			'unknown' => array( 'xyzzy', 'xyzzy' ),
			'enwiki' => array( 'en.example.org', 'enwiki' ),
			'ruwiki' => array( 'ru.example.org', 'ruwiki' ),
		);
	}

	/**
	 * @dataProvider provideGetWikiName
	 */
	public function testGetWikiName( $expected, $wikiId ) {
		$this->assertEquals( $expected, WikiMap::getWikiName( $wikiId ) );
	}

	public function provideMakeForeignLink() {
		return array(
			'unknown' => array( false, 'xyzzy', 'Foo' ),
			'enwiki' => array( '<a class="external" rel="nofollow" href="http://en.example.org/w/Foo">Foo</a>', 'enwiki', 'Foo',  ),
			'ruwiki' => array( '<a class="external" rel="nofollow" href="//ru.example.org/wiki/%D0%A4%D1%83">вар</a>', 'ruwiki', 'Фу', 'вар' ),
		);
	}

	/**
	 * @dataProvider provideMakeForeignLink
	 */
	public function testMakeForeignLink( $expected, $wikiId, $page, $text = null ) {
		$this->assertEquals( $expected, WikiMap::makeForeignLink( $wikiId, $page, $text ) );
	}

	public function provideForeignUserLink() {
		return array(
			'unknown' => array( false, 'xyzzy', 'Foo' ),
			'enwiki' => array( '<a class="external" rel="nofollow" href="http://en.example.org/w/User:Foo">User:Foo</a>', 'enwiki', 'Foo',  ),
			'ruwiki' => array( '<a class="external" rel="nofollow" href="//ru.example.org/wiki/User:%D0%A4%D1%83">вар</a>', 'ruwiki', 'Фу', 'вар' ),
		);
	}

	/**
	 * @dataProvider provideForeignUserLink
	 */
	public function testForeignUserLink( $expected, $wikiId, $user, $text = null ) {
		$this->assertEquals( $expected, WikiMap::foreignUserLink( $wikiId, $user, $text ) );
	}

	public function provideGetForeignURL() {
		return array(
			'unknown' => array( false, 'xyzzy', 'Foo' ),
			'enwiki' => array( 'http://en.example.org/w/Foo', 'enwiki', 'Foo',  ),
			'ruwiki with fragement' => array( '//ru.example.org/wiki/%D0%A4%D1%83#%D0%B2%D0%B0%D1%80', 'ruwiki', 'Фу', 'вар' ),
		);
	}

	/**
	 * @dataProvider provideGetForeignURL
	 */
	public function testGetForeignURL( $expected, $wikiId, $page, $fragment = null ) {
		$this->assertEquals( $expected, WikiMap::getForeignURL( $wikiId, $page, $fragment ) );
	}

}

