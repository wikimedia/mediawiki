<?php

use MediaWiki\Interwiki\InterwikiLookup;
use MediaWiki\MediaWikiServices;
use Wikimedia\Assert\PreconditionException;

/**
 * @group ContentHandler
 * @group Database
 *
 * @note We don't make assumptions about the main namespace.
 *       But we do expect the Help namespace to contain Wikitext.
 */
class TitleMethodsTest extends MediaWikiLangTestCase {

	protected function setUp() : void {
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

	protected function tearDown() : void {
		Title::clearCaches();
		parent::tearDown();
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
			[ 'MediaWiki:Foo.js', 'javascript' ],
			[ 'MediaWiki:Foo.json', 'json' ],
			[ 'MediaWiki:Foo.css', 'css' ],
			[ 'MediaWiki:Foo.JS', false ],
			[ 'MediaWiki:Foo.JSON', false ],
			[ 'MediaWiki:Foo.CSS', false ],
			[ 'MediaWiki:Foo/bar.css', 'css' ],
			[ 'MediaWiki:Foo.css.xxx', false ],
			[ 'TEST-JS:Foo', false ],
			[ 'TEST-JS:Foo.js', false ],
		];
	}

	/**
	 * @dataProvider provideIsSiteConfigPage
	 * @covers Title::isSiteConfigPage
	 * @covers Title::isSiteJsConfigPage
	 * @covers Title::isSiteJsonConfigPage
	 * @covers Title::isSiteCssConfigPage
	 */
	public function testSiteConfigPage( $title, $expected ) {
		$title = Title::newFromText( $title );

		// $expected is either false or the relevant type ('javascript', 'json', 'css')
		$this->assertSame(
			$expected !== false,
			$title->isSiteConfigPage()
		);
		$this->assertSame(
			$expected === 'javascript',
			$title->isSiteJsConfigPage()
		);
		$this->assertSame(
			$expected === 'json',
			$title->isSiteJsonConfigPage()
		);
		$this->assertSame(
			$expected === 'css',
			$title->isSiteCssConfigPage()
		);
	}

	public function provideGetSkinFromConfigSubpage() {
		return [
			[ 'User:Foo', '' ],
			[ 'User:Foo.css', '' ],
			[ 'User:Foo/', '' ],
			[ 'User:Foo/bar', '' ],
			[ 'User:Foo./bar', '' ],
			[ 'User:Foo/bar.', 'bar' ],
			[ 'User:Foo/bar.css', 'bar' ],
			[ '/bar.css', '' ],
			[ '//bar.css', 'bar' ],
			[ '.css', '' ],
		];
	}

	/**
	 * @dataProvider provideGetSkinFromConfigSubpage
	 * @covers Title::getSkinFromConfigSubpage
	 */
	public function testGetSkinFromConfigSubpage( $title, $expected ) {
		$title = Title::newFromText( $title );
		$this->assertSame( $expected, $title->getSkinFromConfigSubpage() );
	}

	public static function provideIsUserConfigPage() {
		return [
			[ 'Help:Foo', false ],
			[ 'Help:Foo.js', false ],
			[ 'Help:Foo/bar.js', false ],
			[ 'User:Foo', false ],
			[ 'User:Foo.js', false ],
			[ 'User:Foo/bar.js', 'javascript' ],
			[ 'User:Foo/bar.JS', false ],
			[ 'User:Foo/bar.json', 'json' ],
			[ 'User:Foo/bar.JSON', false ],
			[ 'User:Foo/bar.css', 'css' ],
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
	 * @covers Title::isUserJsConfigPage
	 * @covers Title::isUserJsonConfigPage
	 * @covers Title::isUserCssConfigPage
	 */
	public function testIsUserConfigPage( $title, $expected ) {
		$title = Title::newFromText( $title );

		// $expected is either false or the relevant type ('javascript', 'json', 'css')
		$this->assertSame(
			$expected !== false,
			$title->isUserConfigPage()
		);
		$this->assertSame(
			$expected === 'javascript',
			$title->isUserJsConfigPage()
		);
		$this->assertSame(
			$expected === 'json',
			$title->isUserJsonConfigPage()
		);
		$this->assertSame(
			$expected === 'css',
			$title->isUserCssConfigPage()
		);
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
			$this->expectException( MWException::class );
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
		$this->assertSame( 0, $linkCache->getGoodLinkID( 'Foo' ), 'link cache should be empty' );
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

	private function installFakeInterwikiLookup() {
		$interwikiLookup =
			$this->createNoOpMock( InterwikiLookup::class, [ 'fetch', 'isValidInterwiki' ] );

		$interwikis = [
			'' => null,
			'acme' => new Interwiki(
				'acme',
				'https://acme.test/$1'
			),
			'yy' => new Interwiki(
				'yy',
				'https://yy.wiki.test/wiki/$1',
				'/w/api.php',
				'yywiki',
				true
			),
		];

		$interwikiLookup->method( 'fetch' )
			->willReturnCallback( static function ( $interwiki ) use ( $interwikis ) {
				return $interwikis[$interwiki] ?? false;
			} );

		$interwikiLookup->method( 'isValidInterwiki' )
			->willReturnCallback( static function ( $interwiki ) use ( $interwikis ) {
				return isset( $interwikis[$interwiki] );
			} );

		$this->setService( 'InterwikiLookup', $interwikiLookup );
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

		$this->installFakeInterwikiLookup();

		$title = Title::makeTitle( $ns, $title, $fragment, $interwiki );
		$this->assertSame( $expected, $title->getLinkURL( $query, $query2, $proto ) );
	}

	/**
	 * @covers Title::getWikiId
	 */
	public function testGetWikiId() {
		$title = Title::newFromText( 'Foo' );
		$this->assertFalse( $title->getWikiId() );
	}

	public function provideProperPage() {
		return [
			[ NS_MAIN, 'Test' ],
			[ NS_MAIN, 'User' ],
		];
	}

	/**
	 * @dataProvider provideProperPage
	 * @covers Title::toPageIdentity
	 */
	public function testToPageIdentity( $ns, $text ) {
		$title = Title::makeTitle( $ns, $text );

		$page = $title->toPageIdentity();

		$this->assertNotSame( $title, $page );
		$this->assertSame( $title->getId(), $page->getId() );
		$this->assertSame( $title->getNamespace(), $page->getNamespace() );
		$this->assertSame( $title->getDBkey(), $page->getDBkey() );
		$this->assertSame( $title->getWikiId(), $page->getWikiId() );
	}

	/**
	 * @dataProvider provideProperPage
	 * @covers Title::toPageRecord
	 */
	public function testToPageRecord( $ns, $text ) {
		$title = Title::makeTitle( $ns, $text );
		$wikiPage = $this->getExistingTestPage( $title );

		$record = $title->toPageRecord();

		$this->assertNotSame( $title, $record );
		$this->assertNotSame( $title, $wikiPage );

		$this->assertSame( $title->getId(), $record->getId() );
		$this->assertSame( $title->getNamespace(), $record->getNamespace() );
		$this->assertSame( $title->getDBkey(), $record->getDBkey() );
		$this->assertSame( $title->getWikiId(), $record->getWikiId() );

		$this->assertSame( $title->getLatestRevID(), $record->getLatest() );
		$this->assertSame( MWTimestamp::convert( TS_MW, $title->getTouched() ), $record->getTouched() );
		$this->assertSame( $title->isNewPage(), $record->isNew() );
		$this->assertSame( $title->isRedirect(), $record->isRedirect() );
	}

	/**
	 * @dataProvider provideImproperPage
	 * @covers Title::toPageRecord
	 */
	public function testToPageRecord_fail( $ns, $text, $fragment = '', $interwiki = '' ) {
		$title = Title::makeTitle( $ns, $text, $fragment, $interwiki );

		$this->expectException( PreconditionException::class );
		$title->toPageRecord();
	}

	public function provideImproperPage() {
		return [
			[ NS_MAIN, '' ],
			[ NS_MAIN, '<>' ],
			[ NS_MAIN, '|' ],
			[ NS_MAIN, '#' ],
			[ NS_PROJECT, '#test' ],
			[ NS_MAIN, '', 'test', 'acme' ],
			[ NS_MAIN, ' Test' ],
			[ NS_MAIN, '_Test' ],
			[ NS_MAIN, 'Test ' ],
			[ NS_MAIN, 'Test_' ],
			[ NS_MAIN, "Test\nthis" ],
			[ NS_MAIN, "Test\tthis" ],
			[ -33, 'Test' ],
			[ 77663399, 'Test' ],

			// Valid but can't exist
			[ NS_MAIN, '', 'test' ],
			[ NS_SPECIAL, 'Test' ],
			[ NS_MAIN, 'Test', '', 'acme' ],

			// Can exist but include a fragment
			[ NS_MAIN, 'Foo', 'bar' ],
		];
	}

	/**
	 * @dataProvider provideImproperPage
	 * @covers Title::getId
	 */
	public function testGetId_fail( $ns, $text, $fragment = '', $interwiki = '' ) {
		$title = Title::makeTitle( $ns, $text, $fragment, $interwiki );

		$this->expectException( PreconditionException::class );
		$title->getId();
	}

	/**
	 * @dataProvider provideImproperPage
	 * @covers Title::toPageIdentity
	 */
	public function testToPageIdentity_fail( $ns, $text, $fragment = '', $interwiki = '' ) {
		$title = Title::makeTitle( $ns, $text, $fragment, $interwiki );

		$this->expectException( PreconditionException::class );
		$title->toPageIdentity();
	}

	public function provideMakeTitle() {
		yield 'main namespace' => [ 'Foo', NS_MAIN, 'Foo' ];
		yield 'user namespace' => [ 'User:Foo', NS_USER, 'Foo' ];
		yield 'fragment' => [ 'Foo#Section', NS_MAIN, 'Foo', 'Section' ];
		yield 'only fragment' => [ '#Section', NS_MAIN, '', 'Section' ];
		yield 'interwiki' => [ 'acme:Foo', NS_MAIN, 'Foo', '', 'acme' ];
		yield 'normalized underscores' => [ 'Foo Bar', NS_MAIN, 'Foo_Bar' ];
	}

	/**
	 * @dataProvider provideMakeTitle
	 * @covers Title::makeTitle
	 */
	public function testMakeTitle( $expected, $ns, $text, $fragment = '', $interwiki = '' ) {
		$this->installFakeInterwikiLookup();
		$title = Title::makeTitle( $ns, $text, $fragment, $interwiki );

		$this->assertTrue( $title->isValid() );
		$this->assertSame( $expected, $title->getFullText() );
	}

	public function provideMakeTitle_invalid() {
		yield 'bad namespace' => [ 'Special:Badtitle/NS-1234:Foo', -1234, 'Foo' ];
		yield 'lower case' => [ 'User:foo', NS_USER, 'foo' ];
		yield 'empty' => [ '', NS_MAIN, '' ];
		yield 'bad character' => [ 'Foo|Bar', NS_MAIN, 'Foo|Bar' ];

		// Is the trailing # intentional?
		yield 'bad interwiki' => [ 'qwerty:Foo#', NS_MAIN, 'Foo', null, 'qwerty' ];
	}

	/**
	 * @dataProvider provideMakeTitle_invalid
	 * @covers Title::makeTitle
	 */
	public function testMakeTitle_invalid( $expected, $ns, $text, $fragment = '', $interwiki = '' ) {
		$this->installFakeInterwikiLookup();
		$title = Title::makeTitle( $ns, $text, $fragment, $interwiki );

		$this->assertFalse( $title->isValid() );
		$this->assertSame( $expected, $title->getFullText() );
	}

	public function provideMakeTitleSafe() {
		yield 'main namespace' => [ 'Foo', NS_MAIN, 'Foo' ];
		yield 'user namespace' => [ 'User:Foo', NS_USER, 'Foo' ];
		yield 'fragment' => [ 'Foo#Section', NS_MAIN, 'Foo', 'Section' ];
		yield 'only fragment' => [ '#Section', NS_MAIN, '', 'Section' ];
		yield 'interwiki' => [ 'acme:Foo', NS_MAIN, 'Foo', '', 'acme' ];

		// Normalize
		yield 'normalized underscores' => [ 'Foo Bar', NS_MAIN, 'Foo_Bar' ];
		yield 'lower case' => [ 'User:Foo', NS_USER, 'foo' ];

		// Bad interwiki becomes part of the title text. Is this intentional?
		yield 'bad interwiki' => [ 'Qwerty:Foo', NS_MAIN, 'Foo', '', 'qwerty' ];
	}

	/**
	 * @dataProvider provideMakeTitleSafe
	 * @covers Title::makeTitleSafe
	 */
	public function testMakeTitleSafe( $expected, $ns, $text, $fragment = '', $interwiki = '' ) {
		$this->installFakeInterwikiLookup();
		$title = Title::makeTitleSafe( $ns, $text, $fragment, $interwiki );

		$this->assertTrue( $title->isValid() );
		$this->assertSame( $expected, $title->getFullText() );
	}

	public function provideMakeTitleSafe_invalid() {
		yield 'bad namespace' => [ -1234, 'Foo' ];
		yield 'empty' => [ '', NS_MAIN, '' ];
		yield 'bad character' => [ NS_MAIN, 'Foo|Bar' ];
	}

	/**
	 * @dataProvider provideMakeTitleSafe_invalid
	 * @covers Title::makeTitleSafe
	 */
	public function testMakeTitleSafe_invalid( $ns, $text, $fragment = '', $interwiki = '' ) {
		$this->installFakeInterwikiLookup();
		$title = Title::makeTitleSafe( $ns, $text, $fragment, $interwiki );

		$this->assertNull( $title );
	}

}
