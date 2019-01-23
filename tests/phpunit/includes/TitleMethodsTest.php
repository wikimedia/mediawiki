<?php

use MediaWiki\Interwiki\InterwikiLookup;
use MediaWiki\MediaWikiServices;

/**
 * @group ContentHandler
 * @group Database
 *
 * @note We don't make assumptions about the main namespace.
 *       But we do expect the Help namespace to contain Wikitext.
 */
class TitleMethodsTest extends MediaWikiLangTestCase {

	protected function setUp() {
		parent::setUp();

		$this->mergeMwGlobalArrayValue(
			'wgExtraNamespaces',
			[
				12302 => 'TEST-JS',
				12303 => 'TEST-JS_TALK',
			]
		);

		$this->mergeMwGlobalArrayValue(
			'wgNamespaceContentModels',
			[
				12302 => CONTENT_MODEL_JAVASCRIPT,
			]
		);
	}

	public static function provideEquals() {
		return [
			[ 'Main Page', 'Main Page', true ],
			[ 'Main Page', 'Not The Main Page', false ],
			[ 'Main Page', 'Project:Main Page', false ],
			[ 'File:Example.png', 'Image:Example.png', true ],
			[ 'Special:Version', 'Special:Version', true ],
			[ 'Special:Version', 'Special:Recentchanges', false ],
			[ 'Special:Version', 'Main Page', false ],
		];
	}

	/**
	 * @dataProvider provideEquals
	 * @covers Title::equals
	 */
	public function testEquals( $titleA, $titleB, $expectedBool ) {
		$titleA = Title::newFromText( $titleA );
		$titleB = Title::newFromText( $titleB );

		$this->assertEquals( $expectedBool, $titleA->equals( $titleB ) );
		$this->assertEquals( $expectedBool, $titleB->equals( $titleA ) );
	}

	public static function provideInNamespace() {
		return [
			[ 'Main Page', NS_MAIN, true ],
			[ 'Main Page', NS_TALK, false ],
			[ 'Main Page', NS_USER, false ],
			[ 'User:Foo', NS_USER, true ],
			[ 'User:Foo', NS_USER_TALK, false ],
			[ 'User:Foo', NS_TEMPLATE, false ],
			[ 'User_talk:Foo', NS_USER_TALK, true ],
			[ 'User_talk:Foo', NS_USER, false ],
		];
	}

	/**
	 * @dataProvider provideInNamespace
	 * @covers Title::inNamespace
	 */
	public function testInNamespace( $title, $ns, $expectedBool ) {
		$title = Title::newFromText( $title );
		$this->assertEquals( $expectedBool, $title->inNamespace( $ns ) );
	}

	/**
	 * @covers Title::inNamespaces
	 */
	public function testInNamespaces() {
		$mainpage = Title::newFromText( 'Main Page' );
		$this->assertTrue( $mainpage->inNamespaces( NS_MAIN, NS_USER ) );
		$this->assertTrue( $mainpage->inNamespaces( [ NS_MAIN, NS_USER ] ) );
		$this->assertTrue( $mainpage->inNamespaces( [ NS_USER, NS_MAIN ] ) );
		$this->assertFalse( $mainpage->inNamespaces( [ NS_PROJECT, NS_TEMPLATE ] ) );
	}

	public static function provideHasSubjectNamespace() {
		return [
			[ 'Main Page', NS_MAIN, true ],
			[ 'Main Page', NS_TALK, true ],
			[ 'Main Page', NS_USER, false ],
			[ 'User:Foo', NS_USER, true ],
			[ 'User:Foo', NS_USER_TALK, true ],
			[ 'User:Foo', NS_TEMPLATE, false ],
			[ 'User_talk:Foo', NS_USER_TALK, true ],
			[ 'User_talk:Foo', NS_USER, true ],
		];
	}

	/**
	 * @dataProvider provideHasSubjectNamespace
	 * @covers Title::hasSubjectNamespace
	 */
	public function testHasSubjectNamespace( $title, $ns, $expectedBool ) {
		$title = Title::newFromText( $title );
		$this->assertEquals( $expectedBool, $title->hasSubjectNamespace( $ns ) );
	}

	public function dataGetContentModel() {
		return [
			[ 'Help:Foo', CONTENT_MODEL_WIKITEXT ],
			[ 'Help:Foo.js', CONTENT_MODEL_WIKITEXT ],
			[ 'Help:Foo/bar.js', CONTENT_MODEL_WIKITEXT ],
			[ 'User:Foo', CONTENT_MODEL_WIKITEXT ],
			[ 'User:Foo.js', CONTENT_MODEL_WIKITEXT ],
			[ 'User:Foo/bar.js', CONTENT_MODEL_JAVASCRIPT ],
			[ 'User:Foo/bar.css', CONTENT_MODEL_CSS ],
			[ 'User talk:Foo/bar.css', CONTENT_MODEL_WIKITEXT ],
			[ 'User:Foo/bar.js.xxx', CONTENT_MODEL_WIKITEXT ],
			[ 'User:Foo/bar.xxx', CONTENT_MODEL_WIKITEXT ],
			[ 'MediaWiki:Foo.js', CONTENT_MODEL_JAVASCRIPT ],
			[ 'MediaWiki:Foo.css', CONTENT_MODEL_CSS ],
			[ 'MediaWiki:Foo/bar.css', CONTENT_MODEL_CSS ],
			[ 'MediaWiki:Foo.JS', CONTENT_MODEL_WIKITEXT ],
			[ 'MediaWiki:Foo.CSS', CONTENT_MODEL_WIKITEXT ],
			[ 'MediaWiki:Foo.css.xxx', CONTENT_MODEL_WIKITEXT ],
			[ 'TEST-JS:Foo', CONTENT_MODEL_JAVASCRIPT ],
			[ 'TEST-JS:Foo.js', CONTENT_MODEL_JAVASCRIPT ],
			[ 'TEST-JS:Foo/bar.js', CONTENT_MODEL_JAVASCRIPT ],
			[ 'TEST-JS_TALK:Foo.js', CONTENT_MODEL_WIKITEXT ],
		];
	}

	/**
	 * @dataProvider dataGetContentModel
	 * @covers Title::getContentModel
	 */
	public function testGetContentModel( $title, $expectedModelId ) {
		$title = Title::newFromText( $title );
		$this->assertEquals( $expectedModelId, $title->getContentModel() );
	}

	/**
	 * @dataProvider dataGetContentModel
	 * @covers Title::hasContentModel
	 */
	public function testHasContentModel( $title, $expectedModelId ) {
		$title = Title::newFromText( $title );
		$this->assertTrue( $title->hasContentModel( $expectedModelId ) );
	}

	public static function provideIsSiteConfigPage() {
		return [
			[ 'Help:Foo', false ],
			[ 'Help:Foo.js', false ],
			[ 'Help:Foo/bar.js', false ],
			[ 'User:Foo', false ],
			[ 'User:Foo.js', false ],
			[ 'User:Foo/bar.js', false ],
			[ 'User:Foo/bar.json', false ],
			[ 'User:Foo/bar.css', false ],
			[ 'User:Foo/bar.JS', false ],
			[ 'User:Foo/bar.JSON', false ],
			[ 'User:Foo/bar.CSS', false ],
			[ 'User talk:Foo/bar.css', false ],
			[ 'User:Foo/bar.js.xxx', false ],
			[ 'User:Foo/bar.xxx', false ],
			[ 'MediaWiki:Foo.js', true ],
			[ 'MediaWiki:Foo.json', true ],
			[ 'MediaWiki:Foo.css', true ],
			[ 'MediaWiki:Foo.JS', false ],
			[ 'MediaWiki:Foo.JSON', false ],
			[ 'MediaWiki:Foo.CSS', false ],
			[ 'MediaWiki:Foo/bar.css', true ],
			[ 'MediaWiki:Foo.css.xxx', false ],
			[ 'TEST-JS:Foo', false ],
			[ 'TEST-JS:Foo.js', false ],
		];
	}

	/**
	 * @dataProvider provideIsSiteConfigPage
	 * @covers Title::isSiteConfigPage
	 */
	public function testSiteConfigPage( $title, $expectedBool ) {
		$title = Title::newFromText( $title );
		$this->assertEquals( $expectedBool, $title->isSiteConfigPage() );
	}

	public static function provideIsUserConfigPage() {
		return [
			[ 'Help:Foo', false ],
			[ 'Help:Foo.js', false ],
			[ 'Help:Foo/bar.js', false ],
			[ 'User:Foo', false ],
			[ 'User:Foo.js', false ],
			[ 'User:Foo/bar.js', true ],
			[ 'User:Foo/bar.JS', false ],
			[ 'User:Foo/bar.json', true ],
			[ 'User:Foo/bar.JSON', false ],
			[ 'User:Foo/bar.css', true ],
			[ 'User:Foo/bar.CSS', false ],
			[ 'User talk:Foo/bar.css', false ],
			[ 'User:Foo/bar.js.xxx', false ],
			[ 'User:Foo/bar.xxx', false ],
			[ 'MediaWiki:Foo.js', false ],
			[ 'MediaWiki:Foo.json', false ],
			[ 'MediaWiki:Foo.css', false ],
			[ 'MediaWiki:Foo.JS', false ],
			[ 'MediaWiki:Foo.JSON', false ],
			[ 'MediaWiki:Foo.CSS', false ],
			[ 'MediaWiki:Foo.css.xxx', false ],
			[ 'TEST-JS:Foo', false ],
			[ 'TEST-JS:Foo.js', false ],
		];
	}

	/**
	 * @dataProvider provideIsUserConfigPage
	 * @covers Title::isUserConfigPage
	 */
	public function testIsUserConfigPage( $title, $expectedBool ) {
		$title = Title::newFromText( $title );
		$this->assertEquals( $expectedBool, $title->isUserConfigPage() );
	}

	public static function provideIsUserCssConfigPage() {
		return [
			[ 'Help:Foo', false ],
			[ 'Help:Foo.css', false ],
			[ 'User:Foo', false ],
			[ 'User:Foo.js', false ],
			[ 'User:Foo.json', false ],
			[ 'User:Foo.css', false ],
			[ 'User:Foo/bar.js', false ],
			[ 'User:Foo/bar.json', false ],
			[ 'User:Foo/bar.css', true ],
		];
	}

	/**
	 * @dataProvider provideIsUserCssConfigPage
	 * @covers Title::isUserCssConfigPage
	 */
	public function testIsUserCssConfigPage( $title, $expectedBool ) {
		$title = Title::newFromText( $title );
		$this->assertEquals( $expectedBool, $title->isUserCssConfigPage() );
	}

	public static function provideIsUserJsConfigPage() {
		return [
			[ 'Help:Foo', false ],
			[ 'Help:Foo.css', false ],
			[ 'User:Foo', false ],
			[ 'User:Foo.js', false ],
			[ 'User:Foo.json', false ],
			[ 'User:Foo.css', false ],
			[ 'User:Foo/bar.js', true ],
			[ 'User:Foo/bar.json', false ],
			[ 'User:Foo/bar.css', false ],
		];
	}

	/**
	 * @dataProvider provideIsUserJsConfigPage
	 * @covers Title::isUserJsConfigPage
	 */
	public function testIsUserJsConfigPage( $title, $expectedBool ) {
		$title = Title::newFromText( $title );
		$this->assertEquals( $expectedBool, $title->isUserJsConfigPage() );
	}

	public static function provideIsWikitextPage() {
		return [
			[ 'Help:Foo', true ],
			[ 'Help:Foo.js', true ],
			[ 'Help:Foo/bar.js', true ],
			[ 'User:Foo', true ],
			[ 'User:Foo.js', true ],
			[ 'User:Foo/bar.js', false ],
			[ 'User:Foo/bar.json', false ],
			[ 'User:Foo/bar.css', false ],
			[ 'User talk:Foo/bar.css', true ],
			[ 'User:Foo/bar.js.xxx', true ],
			[ 'User:Foo/bar.xxx', true ],
			[ 'MediaWiki:Foo.js', false ],
			[ 'User:Foo/bar.JS', true ],
			[ 'User:Foo/bar.JSON', true ],
			[ 'User:Foo/bar.CSS', true ],
			[ 'MediaWiki:Foo.json', false ],
			[ 'MediaWiki:Foo.css', false ],
			[ 'MediaWiki:Foo.JS', true ],
			[ 'MediaWiki:Foo.JSON', true ],
			[ 'MediaWiki:Foo.CSS', true ],
			[ 'MediaWiki:Foo.css.xxx', true ],
			[ 'TEST-JS:Foo', false ],
			[ 'TEST-JS:Foo.js', false ],
		];
	}

	/**
	 * @dataProvider provideIsWikitextPage
	 * @covers Title::isWikitextPage
	 */
	public function testIsWikitextPage( $title, $expectedBool ) {
		$title = Title::newFromText( $title );
		$this->assertEquals( $expectedBool, $title->isWikitextPage() );
	}

	public static function provideGetOtherPage() {
		return [
			[ 'Main Page', 'Talk:Main Page' ],
			[ 'Talk:Main Page', 'Main Page' ],
			[ 'Help:Main Page', 'Help talk:Main Page' ],
			[ 'Help talk:Main Page', 'Help:Main Page' ],
			[ 'Special:FooBar', null ],
			[ 'Media:File.jpg', null ],
		];
	}

	/**
	 * @dataProvider provideGetOtherpage
	 * @covers Title::getOtherPage
	 *
	 * @param string $text
	 * @param string|null $expected
	 */
	public function testGetOtherPage( $text, $expected ) {
		if ( $expected === null ) {
			$this->setExpectedException( MWException::class );
		}

		$title = Title::newFromText( $text );
		$this->assertEquals( $expected, $title->getOtherPage()->getPrefixedText() );
	}

	/**
	 * @covers Title::clearCaches
	 */
	public function testClearCaches() {
		$linkCache = MediaWikiServices::getInstance()->getLinkCache();

		$title1 = Title::newFromText( 'Foo' );
		$linkCache->addGoodLinkObj( 23, $title1 );

		Title::clearCaches();

		$title2 = Title::newFromText( 'Foo' );
		$this->assertNotSame( $title1, $title2, 'title cache should be empty' );
		$this->assertEquals( 0, $linkCache->getGoodLinkID( 'Foo' ), 'link cache should be empty' );
	}

	public function provideGetLinkURL() {
		yield 'Simple' => [
			'/wiki/Goats',
			NS_MAIN,
			'Goats'
		];

		yield 'Fragment' => [
			'/wiki/Goats#Goatificatiön',
			NS_MAIN,
			'Goats',
			'Goatificatiön'
		];

		yield 'Unknown interwiki with fragment' => [
			'https://xx.wiki.test/wiki/xyzzy:Goats#Goatificatiön',
			NS_MAIN,
			'Goats',
			'Goatificatiön',
			'xyzzy'
		];

		yield 'Interwiki with fragment' => [
			'https://acme.test/Goats#Goatificati.C3.B6n',
			NS_MAIN,
			'Goats',
			'Goatificatiön',
			'acme'
		];

		yield 'Interwiki with query' => [
			'https://acme.test/Goats?a=1&b=blank+blank#Goatificati.C3.B6n',
			NS_MAIN,
			'Goats',
			'Goatificatiön',
			'acme',
			[
				'a' => 1,
				'b' => 'blank blank'
			]
		];

		yield 'Local interwiki with fragment' => [
			'https://yy.wiki.test/wiki/Goats#Goatificatiön',
			NS_MAIN,
			'Goats',
			'Goatificatiön',
			'yy'
		];
	}

	/**
	 * @dataProvider provideGetLinkURL
	 *
	 * @covers Title::getLinkURL
	 * @covers Title::getFullURL
	 * @covers Title::getLocalURL
	 * @covers Title::getFragmentForURL
	 */
	public function testGetLinkURL(
		$expected,
		$ns,
		$title,
		$fragment = '',
		$interwiki = '',
		$query = '',
		$query2 = false,
		$proto = false
	) {
		$this->setMwGlobals( [
			'wgServer' => 'https://xx.wiki.test',
			'wgArticlePath' => '/wiki/$1',
			'wgExternalInterwikiFragmentMode' => 'legacy',
			'wgFragmentMode' => [ 'html5', 'legacy' ]
		] );

		$interwikiLookup = $this->getMock( InterwikiLookup::class );

		$interwikiLookup->method( 'fetch' )
			->willReturnCallback( function ( $interwiki ) {
				switch ( $interwiki ) {
					case '':
						return null;
					case 'acme':
						return new Interwiki(
							'acme',
							'https://acme.test/$1'
						);
					case 'yy':
						return new Interwiki(
							'yy',
							'https://yy.wiki.test/wiki/$1',
							'/w/api.php',
							'yywiki',
							true
						);
					default:
						return false;
				}
			} );

		$this->setService( 'InterwikiLookup', $interwikiLookup );

		$title = Title::makeTitle( $ns, $title, $fragment, $interwiki );
		$this->assertSame( $expected, $title->getLinkURL( $query, $query2, $proto ) );
	}

	function tearDown() {
		Title::clearCaches();
		parent::tearDown();
	}
}
