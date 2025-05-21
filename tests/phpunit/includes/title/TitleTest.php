<?php

use MediaWiki\Cache\BacklinkCache;
use MediaWiki\Content\WikitextContent;
use MediaWiki\Exception\MWException;
use MediaWiki\Language\RawMessage;
use MediaWiki\Linker\LinkTarget;
use MediaWiki\MainConfigNames;
use MediaWiki\Message\Message;
use MediaWiki\Page\Article;
use MediaWiki\Page\PageIdentityValue;
use MediaWiki\Tests\Unit\DummyServicesTrait;
use MediaWiki\Title\MalformedTitleException;
use MediaWiki\Title\Title;
use MediaWiki\Title\TitleValue;
use MediaWiki\Utils\MWTimestamp;
use Wikimedia\Assert\PreconditionException;
use Wikimedia\Rdbms\IDBAccessObject;

/**
 * @group Database
 * @group Title
 */
class TitleTest extends MediaWikiIntegrationTestCase {
	use DummyServicesTrait;
	use LinkCacheTestTrait;

	protected function setUp(): void {
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

		$this->overrideConfigValues( [
			MainConfigNames::AllowUserJs => false,
			MainConfigNames::DefaultLanguageVariant => false,
			MainConfigNames::MetaNamespace => 'Project',
			MainConfigNames::Server => 'https://example.org',
			MainConfigNames::CanonicalServer => 'https://example.org',
			MainConfigNames::ScriptPath => '/w',
			MainConfigNames::Script => '/w/index.php',
			MainConfigNames::LanguageCode => 'en',
			// For testSecureAndSplitValid, testSecureAndSplitInvalid
			MainConfigNames::LocalInterwikis => [ 'localtestiw' ],
		] );
		$this->setUserLang( 'en' );

		// Define valid interwiki prefixes and their configuration
		$interwikiLookup = $this->getDummyInterwikiLookup( [
			// testSecureAndSplitValid, testSecureAndSplitInvalid
			[ 'iw_prefix' => 'localtestiw', 'iw_url' => 'localtestiw' ],
			[ 'iw_prefix' => 'remotetestiw', 'iw_url' => 'remotetestiw' ],

			// testSubpages
			'wiki',

			// testIsValid
			'wikipedia',

			// testIsValidRedirectTarget
			'acme',

			// testGetFragmentForURL
			[ 'iw_prefix' => 'de', 'iw_local' => 1 ],
			[ 'iw_prefix' => 'zz', 'iw_local' => 0 ],

			// Some tests use interwikis - define valid prefixes and their configuration
			[ 'iw_prefix' => 'acme', 'iw_url' => 'https://acme.test/$1' ],
			[ 'iw_prefix' => 'yy', 'iw_url' => 'https://yy.wiki.test/wiki/$1', 'iw_local' => true ]
		] );
		$this->setService( 'InterwikiLookup', $interwikiLookup );
	}

	protected function tearDown(): void {
		parent::tearDown();
		// delete dummy pages
		$this->getNonexistingTestPage( 'UTest1' );
		$this->getNonexistingTestPage( 'UTest2' );
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
	 * @covers \MediaWiki\Title\Title::inNamespace
	 */
	public function testInNamespace( $title, $ns, $expectedBool ) {
		$title = Title::newFromText( $title );
		$this->assertEquals( $expectedBool, $title->inNamespace( $ns ) );
	}

	/**
	 * @covers \MediaWiki\Title\Title::inNamespaces
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
	 * @covers \MediaWiki\Title\Title::hasSubjectNamespace
	 */
	public function testHasSubjectNamespace( $title, $ns, $expectedBool ) {
		$title = Title::newFromText( $title );
		$this->assertEquals( $expectedBool, $title->hasSubjectNamespace( $ns ) );
	}

	public static function provideGetContentModel() {
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
	 * @dataProvider provideGetContentModel
	 * @covers \MediaWiki\Title\Title::getContentModel
	 */
	public function testGetContentModel( $title, $expectedModelId ) {
		$title = Title::newFromText( $title );
		$this->assertEquals( $expectedModelId, $title->getContentModel() );
	}

	/**
	 * @dataProvider provideGetContentModel
	 * @covers \MediaWiki\Title\Title::hasContentModel
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
	 * @covers \MediaWiki\Title\Title::isSiteConfigPage
	 * @covers \MediaWiki\Title\Title::isSiteJsConfigPage
	 * @covers \MediaWiki\Title\Title::isSiteJsonConfigPage
	 * @covers \MediaWiki\Title\Title::isSiteCssConfigPage
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
	 * @covers \MediaWiki\Title\Title::isUserConfigPage
	 * @covers \MediaWiki\Title\Title::isUserJsConfigPage
	 * @covers \MediaWiki\Title\Title::isUserJsonConfigPage
	 * @covers \MediaWiki\Title\Title::isUserCssConfigPage
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
	 * @covers \MediaWiki\Title\Title::isWikitextPage
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
	 * @covers \MediaWiki\Title\Title::getOtherPage
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
	 * @covers \MediaWiki\Title\Title::clearCaches
	 */
	public function testClearCaches() {
		$linkCache = $this->getServiceContainer()->getLinkCache();

		$title1 = Title::newFromText( 'Foo' );
		$this->addGoodLinkObject( 23, $title1 );

		Title::clearCaches();

		$title2 = Title::newFromText( 'Foo' );
		$this->assertNotSame( $title1, $title2, 'title cache should be empty' );
		$this->assertSame( 0, $linkCache->getGoodLinkID( 'Foo' ), 'link cache should be empty' );
	}

	/**
	 * @covers \MediaWiki\Title\Title::getFieldFromPageStore
	 */
	public function testUseCaches() {
		$title1 = Title::makeTitle( NS_MAIN, __METHOD__ . '998724352' );
		$this->addGoodLinkObject( 23, $title1, 7, 0, 42 );

		// Ensure that getLatestRevID uses the LinkCache even after
		// the article ID is known (T296063#7520023).
		$this->assertSame( 23, $title1->getArticleID() );
		$this->assertSame( 42, $title1->getLatestRevID() );
	}

	public static function provideGetLinkURL() {
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

		yield 'Fragment only (query is ignored)' => [
			'#Goatificatiön',
			NS_MAIN,
			'',
			'Goatificatiön',
			'',
			[
				'a' => 1,
			]
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
	 * @covers \MediaWiki\Title\Title::getLinkURL
	 * @covers \MediaWiki\Title\Title::getFullURL
	 * @covers \MediaWiki\Title\Title::getLocalURL
	 * @covers \MediaWiki\Title\Title::getFragmentForURL
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
		$this->overrideConfigValues( [
			MainConfigNames::Server => 'https://xx.wiki.test',
			MainConfigNames::ExternalInterwikiFragmentMode => 'legacy',
			MainConfigNames::FragmentMode => [ 'html5', 'legacy' ]
		] );

		$title = Title::makeTitle( $ns, $title, $fragment, $interwiki );
		$this->assertSame( $expected, $title->getLinkURL( $query, $query2, $proto ) );
	}

	public static function provideProperPage() {
		return [
			[ NS_MAIN, 'Test' ],
			[ NS_MAIN, 'User' ],
		];
	}

	/**
	 * @dataProvider provideProperPage
	 * @covers \MediaWiki\Title\Title::toPageIdentity
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
	 * @covers \MediaWiki\Title\Title::toPageRecord
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
		$this->assertSame( $title->getTouched(), $record->getTouched() );
	}

	/**
	 * @dataProvider provideImproperPage
	 * @covers \MediaWiki\Title\Title::toPageRecord
	 */
	public function testToPageRecord_fail( $ns, $text, $fragment = '', $interwiki = '' ) {
		$title = Title::makeTitle( $ns, $text, $fragment, $interwiki );

		$this->expectException( PreconditionException::class );
		$title->toPageRecord();
	}

	public static function provideImproperPage() {
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
		];
	}

	/**
	 * @dataProvider provideImproperPage
	 * @covers \MediaWiki\Title\Title::getId
	 */
	public function testGetId_fail( $ns, $text, $fragment = '', $interwiki = '' ) {
		$title = Title::makeTitle( $ns, $text, $fragment, $interwiki );

		$this->expectException( PreconditionException::class );
		$title->getId();
	}

	/**
	 * @dataProvider provideImproperPage
	 * @covers \MediaWiki\Title\Title::getId
	 */
	public function testGetId_fragment() {
		$title = Title::makeTitle( NS_MAIN, 'Test', 'References' );

		// should not throw
		$this->assertIsInt( $title->getId() );
	}

	/**
	 * @dataProvider provideImproperPage
	 * @covers \MediaWiki\Title\Title::toPageIdentity
	 */
	public function testToPageIdentity_fail( $ns, $text, $fragment = '', $interwiki = '' ) {
		$title = Title::makeTitle( $ns, $text, $fragment, $interwiki );

		$this->expectException( PreconditionException::class );
		$title->toPageIdentity();
	}

	public static function provideMakeTitle() {
		yield 'main namespace' => [ 'Foo', NS_MAIN, 'Foo' ];
		yield 'user namespace' => [ 'User:Foo', NS_USER, 'Foo' ];
		yield 'fragment' => [ 'Foo#Section', NS_MAIN, 'Foo', 'Section' ];
		yield 'only fragment' => [ '#Section', NS_MAIN, '', 'Section' ];
		yield 'interwiki' => [ 'acme:Foo', NS_MAIN, 'Foo', '', 'acme' ];
		yield 'normalized underscores' => [ 'Foo Bar', NS_MAIN, 'Foo_Bar' ];
	}

	/**
	 * @dataProvider provideMakeTitle
	 * @covers \MediaWiki\Title\Title::makeTitle
	 */
	public function testMakeTitle( $expected, $ns, $text, $fragment = '', $interwiki = '' ) {
		$title = Title::makeTitle( $ns, $text, $fragment, $interwiki );

		$this->assertTrue( $title->isValid() );
		$this->assertSame( $expected, $title->getFullText() );
	}

	public static function provideMakeTitle_invalid() {
		yield 'bad namespace' => [ 'Special:Badtitle/NS-1234:Foo', -1234, 'Foo' ];
		yield 'lower case' => [ 'User:foo', NS_USER, 'foo' ];
		yield 'empty' => [ '', NS_MAIN, '' ];
		yield 'bad character' => [ 'Foo|Bar', NS_MAIN, 'Foo|Bar' ];
		yield 'bad interwiki' => [ 'qwerty:Foo', NS_MAIN, 'Foo', '', 'qwerty' ];
	}

	/**
	 * @dataProvider provideMakeTitle_invalid
	 * @covers \MediaWiki\Title\Title::makeTitle
	 */
	public function testMakeTitle_invalid( $expected, $ns, $text, $fragment = '', $interwiki = '' ) {
		$title = Title::makeTitle( $ns, $text, $fragment, $interwiki );

		$this->assertFalse( $title->isValid() );
		$this->assertSame( $expected, $title->getFullText() );
	}

	public static function provideMakeName() {
		yield 'main namespace' => [ 'Foo', NS_MAIN, 'Foo' ];
		yield 'user namespace' => [ 'User:Foo', NS_USER, 'Foo' ];
		yield 'fragment' => [ 'Foo#Section', NS_MAIN, 'Foo', 'Section' ];
		yield 'only fragment' => [ '#Section', NS_MAIN, '', 'Section' ];
		yield 'interwiki' => [ 'acme:Foo', NS_MAIN, 'Foo', '', 'acme' ];
		yield 'bad namespace' => [ 'Special:Badtitle/NS-1234:Foo', -1234, 'Foo' ];
		yield 'lower case' => [ 'User:foo', NS_USER, 'foo' ];
		yield 'empty' => [ '', NS_MAIN, '' ];
		yield 'bad character' => [ 'Foo|Bar', NS_MAIN, 'Foo|Bar' ];
		yield 'bad interwiki' => [ 'qwerty:Foo', NS_MAIN, 'Foo', '', 'qwerty' ];
	}

	/**
	 * @dataProvider provideMakeName
	 * @covers \MediaWiki\Title\Title::makeName
	 */
	public function testMakeName( $expected, $ns, $text, $fragment = '', $interwiki = '' ) {
		$titleName = Title::makeName( $ns, $text, $fragment, $interwiki );

		$this->assertSame( $expected, $titleName );
	}

	public static function provideMakeTitleSafe() {
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
	 * @covers \MediaWiki\Title\Title::makeTitleSafe
	 */
	public function testMakeTitleSafe( $expected, $ns, $text, $fragment = '', $interwiki = '' ) {
		$title = Title::makeTitleSafe( $ns, $text, $fragment, $interwiki );

		$this->assertTrue( $title->isValid() );
		$this->assertSame( $expected, $title->getFullText() );
	}

	public static function provideMakeTitleSafe_invalid() {
		yield 'bad namespace' => [ -1234, 'Foo' ];
		yield 'empty' => [ '', NS_MAIN, '' ];
		yield 'bad character' => [ NS_MAIN, 'Foo|Bar' ];
	}

	/**
	 * @dataProvider provideMakeTitleSafe_invalid
	 * @covers \MediaWiki\Title\Title::makeTitleSafe
	 */
	public function testMakeTitleSafe_invalid( $ns, $text, $fragment = '', $interwiki = '' ) {
		$title = Title::makeTitleSafe( $ns, $text, $fragment, $interwiki );

		$this->assertNull( $title );
	}

	/**
	 * @covers \MediaWiki\Title\Title::getContentModel
	 * @covers \MediaWiki\Title\Title::setContentModel
	 * @covers \MediaWiki\Title\Title::uncache
	 */
	public function testSetContentModel() {
		// NOTE: must use newFromText to test behavior of internal instance cache (T281337)
		$title = Title::newFromText( 'Foo' );

		$title->setContentModel( CONTENT_MODEL_UNKNOWN );
		$this->assertSame( CONTENT_MODEL_UNKNOWN, $title->getContentModel() );

		// Ensure that the instance we get back from newFromText isn't the modified one.
		$title = Title::newFromText( 'Foo' );
		$this->assertNotSame( CONTENT_MODEL_UNKNOWN, $title->getContentModel() );
	}

	/**
	 * @covers \MediaWiki\Title\Title::newFromID
	 * @covers \MediaWiki\Title\Title::newFromRow
	 */
	public function testNewFromId() {
		// First id
		$existingPage1 = $this->getExistingTestPage( 'UTest1' );
		$existingTitle1 = $existingPage1->getTitle();
		$existingId1 = $existingTitle1->getId();

		$this->assertGreaterThan( 0, $existingId1, 'Existing test page should have a positive id' );

		$newFromId1 = Title::newFromID( $existingId1 );
		$this->assertInstanceOf( Title::class, $newFromId1, 'newFromID returns a title for an existing id' );
		$this->assertTrue(
			$newFromId1->equals( $existingTitle1 ),
			'newFromID returns the correct title'
		);

		// Second id
		$existingPage2 = $this->getExistingTestPage( 'UTest2' );
		$existingTitle2 = $existingPage2->getTitle();
		$existingId2 = $existingTitle2->getId();

		$this->assertGreaterThan( 0, $existingId2, 'Existing test page should have a positive id' );

		$newFromId2 = Title::newFromID( $existingId2 );
		$this->assertInstanceOf( Title::class, $newFromId2, 'newFromID returns a title for an existing id' );
		$this->assertTrue(
			$newFromId2->equals( $existingTitle2 ),
			'newFromID returns the correct title'
		);
	}

	/**
	 * @covers \MediaWiki\Title\Title::newFromID
	 */
	public function testNewFromMissingId() {
		// Testing return of null for an id that does not exist
		$maxPageId = (int)$this->getDb()->newSelectQueryBuilder()
			->select( 'max(page_id)' )
			->from( 'page' )
			->caller( __METHOD__ )->fetchField();
		$res = Title::newFromID( $maxPageId + 1 );
		$this->assertNull( $res, 'newFromID returns null for missing ids' );
	}

	public static function provideValidSecureAndSplit() {
		return [
			[ 'Sandbox' ],
			[ 'A "B"' ],
			[ 'A \'B\'' ],
			[ '.com' ],
			[ '~' ],
			[ '#' ],
			[ '"' ],
			[ '\'' ],
			[ 'Talk:Sandbox' ],
			[ 'Talk:Foo:Sandbox' ],
			[ 'File:Example.svg' ],
			[ 'File_talk:Example.svg' ],
			[ 'Foo/.../Sandbox' ],
			[ 'Sandbox/...' ],
			[ 'A~~' ],
			[ ':A' ],
			// Length is 256 total, but only title part matters
			[ 'Category:' . str_repeat( 'x', 248 ) ],
			[ str_repeat( 'x', 252 ) ],
			// interwiki prefix
			[ 'localtestiw: #anchor' ],
			[ 'localtestiw:' ],
			[ 'localtestiw:foo' ],
			[ 'localtestiw: foo # anchor' ],
			[ 'localtestiw: Talk: Sandbox # anchor' ],
			[ 'remotetestiw:' ],
			[ 'remotetestiw: Talk: # anchor' ],
			[ 'remotetestiw: #bar' ],
			[ 'remotetestiw: Talk:' ],
			[ 'remotetestiw: Talk: Foo' ],
			[ 'localtestiw:remotetestiw:' ],
			[ 'localtestiw:remotetestiw:foo' ]
		];
	}

	public static function provideInvalidSecureAndSplit() {
		return [
			[ '', 'title-invalid-empty' ],
			[ ':', 'title-invalid-empty' ],
			[ '__  __', 'title-invalid-empty' ],
			[ '  __  ', 'title-invalid-empty' ],
			// Bad characters forbidden regardless of wgLegalTitleChars
			[ 'A [ B', 'title-invalid-characters' ],
			[ 'A ] B', 'title-invalid-characters' ],
			[ 'A { B', 'title-invalid-characters' ],
			[ 'A } B', 'title-invalid-characters' ],
			[ 'A < B', 'title-invalid-characters' ],
			[ 'A > B', 'title-invalid-characters' ],
			[ 'A | B', 'title-invalid-characters' ],
			[ "A \t B", 'title-invalid-characters' ],
			[ "A \n B", 'title-invalid-characters' ],
			// URL encoding
			[ 'A%20B', 'title-invalid-characters' ],
			[ 'A%23B', 'title-invalid-characters' ],
			[ 'A%2523B', 'title-invalid-characters' ],
			// XML/HTML character entity references
			// Note: Commented out because they are not marked invalid by the PHP test as
			// Title::newFromText runs Sanitizer::decodeCharReferencesAndNormalize first.
			// 'A &eacute; B',
			// Subject of NS_TALK does not roundtrip to NS_MAIN
			[ 'Talk:File:Example.svg', 'title-invalid-talk-namespace' ],
			// Directory navigation
			[ '.', 'title-invalid-relative' ],
			[ '..', 'title-invalid-relative' ],
			[ './Sandbox', 'title-invalid-relative' ],
			[ '../Sandbox', 'title-invalid-relative' ],
			[ 'Foo/./Sandbox', 'title-invalid-relative' ],
			[ 'Foo/../Sandbox', 'title-invalid-relative' ],
			[ 'Sandbox/.', 'title-invalid-relative' ],
			[ 'Sandbox/..', 'title-invalid-relative' ],
			// Tilde
			[ 'A ~~~ Name', 'title-invalid-magic-tilde' ],
			[ 'A ~~~~ Signature', 'title-invalid-magic-tilde' ],
			[ 'A ~~~~~ Timestamp', 'title-invalid-magic-tilde' ],
			// Length
			[ str_repeat( 'x', 256 ), 'title-invalid-too-long' ],
			// Namespace prefix without actual title
			[ 'Talk:', 'title-invalid-empty' ],
			[ 'Talk:#', 'title-invalid-empty' ],
			[ 'Category: ', 'title-invalid-empty' ],
			[ 'Category: #bar', 'title-invalid-empty' ],
			// interwiki prefix
			[ 'localtestiw: Talk: # anchor', 'title-invalid-empty' ],
			[ 'localtestiw: Talk:', 'title-invalid-empty' ]
		];
	}

	/**
	 * See also mediawiki.Title.test.js
	 * @covers \MediaWiki\Title\Title::secureAndSplit
	 * @dataProvider provideValidSecureAndSplit
	 * @note This mainly tests TitleParser::parseTitle().
	 */
	public function testSecureAndSplitValid( $text ) {
		$this->assertInstanceOf( Title::class, Title::newFromText( $text ), "Valid: $text" );
	}

	/**
	 * See also mediawiki.Title.test.js
	 * @covers \MediaWiki\Title\Title::secureAndSplit
	 * @dataProvider provideInvalidSecureAndSplit
	 * @note This mainly tests TitleParser::parseTitle().
	 */
	public function testSecureAndSplitInvalid( $text, $expectedErrorMessage ) {
		try {
			Title::newFromTextThrow( $text ); // should throw
			$this->fail( "Title::newFromTextThrow should have thrown with $text" );
		} catch ( MalformedTitleException $ex ) {
			$this->assertEquals( $expectedErrorMessage, $ex->getErrorMessage(), "Invalid: $text" );
		}
	}

	public static function provideSpecialNamesWithAndWithoutParameter() {
		return [
			[ 'Special:Version', null ],
			[ 'Special:Version/', '' ],
			[ 'Special:Version/param', 'param' ],
		];
	}

	/**
	 * @dataProvider provideSpecialNamesWithAndWithoutParameter
	 * @covers \MediaWiki\Title\Title::fixSpecialName
	 */
	public function testFixSpecialNameRetainsParameter( $text, $expectedParam ) {
		$title = Title::newFromText( $text );
		$fixed = $title->fixSpecialName();
		$stuff = explode( '/', $fixed->getDBkey(), 2 );
		$par = $stuff[1] ?? null;
		$this->assertEquals(
			$expectedParam,
			$par,
			"T33100 regression check: Title->fixSpecialName() should preserve parameter"
		);
	}

	public function flattenErrorsArray( $errors ) {
		$result = [];
		foreach ( $errors as $error ) {
			$result[] = $error[0];
		}

		return $result;
	}

	public static function provideGetPageViewLanguage() {
		# Format:
		# - expected
		# - Title name
		# - content language (expected in most cases)
		# - wgLang (on some specific pages)
		# - wgDefaultLanguageVariant
		return [
			[ 'fr', 'Help:I_need_somebody', 'fr', 'fr', false ],
			[ 'es', 'Help:I_need_somebody', 'es', 'zh-tw', false ],
			[ 'zh', 'Help:I_need_somebody', 'zh', 'zh-tw', false ],

			[ 'es', 'Help:I_need_somebody', 'es', 'zh-tw', 'zh-cn' ],
			[ 'es', 'MediaWiki:About', 'es', 'zh-tw', 'zh-cn' ],
			[ 'es', 'MediaWiki:About/', 'es', 'zh-tw', 'zh-cn' ],
			[ 'de', 'MediaWiki:About/de', 'es', 'zh-tw', 'zh-cn' ],
			[ 'en', 'MediaWiki:Common.js', 'es', 'zh-tw', 'zh-cn' ],
			[ 'en', 'MediaWiki:Common.css', 'es', 'zh-tw', 'zh-cn' ],
			[ 'en', 'User:JohnDoe/Common.js', 'es', 'zh-tw', 'zh-cn' ],
			[ 'en', 'User:JohnDoe/Monobook.css', 'es', 'zh-tw', 'zh-cn' ],

			[ 'zh-cn', 'Help:I_need_somebody', 'zh', 'zh-tw', 'zh-cn' ],
			[ 'zh', 'MediaWiki:About', 'zh', 'zh-tw', 'zh-cn' ],
			[ 'zh', 'MediaWiki:About/', 'zh', 'zh-tw', 'zh-cn' ],
			[ 'de', 'MediaWiki:About/de', 'zh', 'zh-tw', 'zh-cn' ],
			[ 'zh-cn', 'MediaWiki:About/zh-cn', 'zh', 'zh-tw', 'zh-cn' ],
			[ 'zh-tw', 'MediaWiki:About/zh-tw', 'zh', 'zh-tw', 'zh-cn' ],
			[ 'en', 'MediaWiki:Common.js', 'zh', 'zh-tw', 'zh-cn' ],
			[ 'en', 'MediaWiki:Common.css', 'zh', 'zh-tw', 'zh-cn' ],
			[ 'en', 'User:JohnDoe/Common.js', 'zh', 'zh-tw', 'zh-cn' ],
			[ 'en', 'User:JohnDoe/Monobook.css', 'zh', 'zh-tw', 'zh-cn' ],

			[ 'zh-tw', 'Special:NewPages', 'es', 'zh-tw', 'zh-cn' ],
			[ 'zh-tw', 'Special:NewPages', 'zh', 'zh-tw', 'zh-cn' ],

		];
	}

	/**
	 * Superseded by OutputPageTest::testGetJsVarsAboutPageLang
	 *
	 * @dataProvider provideGetPageViewLanguage
	 * @covers \MediaWiki\Title\Title::getPageViewLanguage
	 */
	public function testGetPageViewLanguage( $expected, $titleText, $contLang,
		$lang, $variant, $msg = ''
	) {
		// Setup environment for this test
		$this->overrideConfigValues( [
			MainConfigNames::DefaultLanguageVariant => $variant,
			MainConfigNames::AllowUserJs => true,
		] );
		$this->setUserLang( $lang );
		$this->overrideConfigValue( MainConfigNames::LanguageCode, $contLang );

		$title = Title::newFromText( $titleText );
		$this->assertInstanceOf( Title::class, $title,
			"Test must be passed a valid title text, you gave '$titleText'"
		);
		$this->hideDeprecated( Title::class . '::getPageViewLanguage' );
		$this->assertEquals( $expected,
			$title->getPageViewLanguage()->getCode(),
			$msg
		);
	}

	public static function provideSubpage() {
		// NOTE: avoid constructing Title objects in the provider, since it may access the database.
		return [
			[ 'Foo', 'x', new TitleValue( NS_MAIN, 'Foo/x' ) ],
			[ 'Foo#bar', 'x', new TitleValue( NS_MAIN, 'Foo/x' ) ],
			[ 'User:Foo', 'x', new TitleValue( NS_USER, 'Foo/x' ) ],
			[ 'wiki:User:Foo', 'x', new TitleValue( NS_MAIN, 'User:Foo/x', '', 'wiki' ) ],
		];
	}

	/**
	 * @dataProvider provideSubpage
	 * @covers \MediaWiki\Title\Title::getSubpage
	 */
	public function testSubpage( $title, $sub, LinkTarget $expected ) {
		$title = Title::newFromText( $title );
		$expected = Title::newFromLinkTarget( $expected );
		$actual = $title->getSubpage( $sub );

		// NOTE: convert to string for comparison
		$this->assertSame( $expected->getPrefixedText(), $actual->getPrefixedText(), 'text form' );
		$this->assertTrue( $expected->equals( $actual ), 'Title equality' );
	}

	public static function provideIsAlwaysKnown() {
		return [
			[ 'Some nonexistent page' . wfRandomString(), false ],
			[ 'Some existent page', false, true ],
			[ '#test', true ],
			[ 'Special:BlankPage', true ],
			[ 'Special:SomeNonexistentSpecialPage', false ],
			[ 'MediaWiki:Parentheses', true ],
			[ 'MediaWiki:Some nonexistent message', false ],
		];
	}

	/**
	 * @covers \MediaWiki\Title\Title::isAlwaysKnown
	 * @dataProvider provideIsAlwaysKnown
	 * @param string $page
	 * @param bool $isKnown
	 * @param bool $createIfNotExists
	 */
	public function testIsAlwaysKnown( $page, $isKnown, bool $createIfNotExists = false ) {
		if ( $createIfNotExists ) {
			$this->getExistingTestPage( $page );
		}
		$title = Title::newFromText( $page );
		$this->assertEquals( $isKnown, $title->isAlwaysKnown() );
	}

	public static function provideIsValid() {
		return [
			[ Title::makeTitle( NS_MAIN, '' ), false ],
			[ Title::makeTitle( NS_MAIN, '<>' ), false ],
			[ Title::makeTitle( NS_MAIN, '|' ), false ],
			[ Title::makeTitle( NS_MAIN, '#' ), false ],
			[ Title::makeTitle( NS_PROJECT, '#' ), false ],
			[ Title::makeTitle( NS_MAIN, '', 'test' ), true ],
			[ Title::makeTitle( NS_PROJECT, '#test' ), false ],
			[ Title::makeTitle( NS_MAIN, '', 'test', 'wikipedia' ), true ],
			[ Title::makeTitle( NS_MAIN, 'Test', '', 'wikipedia' ), true ],
			[ Title::makeTitle( NS_MAIN, 'Test' ), true ],
			[ Title::makeTitle( NS_SPECIAL, 'Test' ), true ],
			[ Title::makeTitle( NS_MAIN, ' Test' ), false ],
			[ Title::makeTitle( NS_MAIN, '_Test' ), false ],
			[ Title::makeTitle( NS_MAIN, 'Test ' ), false ],
			[ Title::makeTitle( NS_MAIN, 'Test_' ), false ],
			[ Title::makeTitle( NS_MAIN, "Test\nthis" ), false ],
			[ Title::makeTitle( NS_MAIN, "Test\tthis" ), false ],
			[ Title::makeTitle( -33, 'Test' ), false ],
			[ Title::makeTitle( 77663399, 'Test' ), false ],
		];
	}

	/**
	 * @covers \MediaWiki\Title\Title::isValid
	 * @dataProvider provideIsValid
	 * @param Title $title
	 * @param bool $isValid
	 */
	public function testIsValid( Title $title, $isValid ) {
		$this->assertEquals( $isValid, $title->isValid(), $title->getFullText() );
	}

	public static function provideIsValidRedirectTarget() {
		return [
			[ Title::makeTitle( NS_MAIN, '' ), false ],
			[ Title::makeTitle( NS_MAIN, '', 'test' ), false ],
			[ Title::makeTitle( NS_MAIN, 'Foo', 'test' ), true ],
			[ Title::makeTitle( NS_MAIN, '<>' ), false ],
			[ Title::makeTitle( NS_MAIN, '_' ), false ],
			[ Title::makeTitle( NS_MAIN, 'Test', '', 'acme' ), true ],
			[ Title::makeTitle( NS_SPECIAL, 'UserLogout' ), false ],
			[ Title::makeTitle( NS_SPECIAL, 'RecentChanges' ), true ],
		];
	}

	/**
	 * @covers \MediaWiki\Title\Title::isValidRedirectTarget
	 * @dataProvider provideIsValidRedirectTarget
	 * @param Title $title
	 * @param bool $isValid
	 */
	public function testIsValidRedirectTarget( Title $title, $isValid ) {
		// InterwikiLookup is configured in setUp()
		$this->assertEquals( $isValid, $title->isValidRedirectTarget(), $title->getFullText() );
	}

	public static function provideCanExist() {
		return [
			[ Title::makeTitle( NS_MAIN, '' ), false ],
			[ Title::makeTitle( NS_MAIN, '<>' ), false ],
			[ Title::makeTitle( NS_MAIN, '|' ), false ],
			[ Title::makeTitle( NS_MAIN, '#' ), false ],
			[ Title::makeTitle( NS_PROJECT, '#test' ), false ],
			[ Title::makeTitle( NS_MAIN, '', 'test', 'acme' ), false ],
			[ Title::makeTitle( NS_MAIN, 'Test' ), true ],
			[ Title::makeTitle( NS_MAIN, ' Test' ), false ],
			[ Title::makeTitle( NS_MAIN, '_Test' ), false ],
			[ Title::makeTitle( NS_MAIN, 'Test ' ), false ],
			[ Title::makeTitle( NS_MAIN, 'Test_' ), false ],
			[ Title::makeTitle( NS_MAIN, "Test\nthis" ), false ],
			[ Title::makeTitle( NS_MAIN, "Test\tthis" ), false ],
			[ Title::makeTitle( -33, 'Test' ), false ],
			[ Title::makeTitle( 77663399, 'Test' ), false ],

			// Valid but can't exist
			[ Title::makeTitle( NS_MAIN, '', 'test' ), false ],
			[ Title::makeTitle( NS_SPECIAL, 'Test' ), false ],
			[ Title::makeTitle( NS_MAIN, 'Test', '', 'acme' ), false ],
		];
	}

	/**
	 * @covers \MediaWiki\Title\Title::canExist
	 * @dataProvider provideCanExist
	 * @param Title $title
	 * @param bool $canExist
	 */
	public function testCanExist( Title $title, $canExist ) {
		$this->assertEquals( $canExist, $title->canExist(), $title->getFullText() );
	}

	/**
	 * @covers \MediaWiki\Title\Title::isAlwaysKnown
	 */
	public function testIsAlwaysKnownOnInterwiki() {
		$title = Title::makeTitle( NS_MAIN, 'Interwiki link', '', 'externalwiki' );
		$this->assertTrue( $title->isAlwaysKnown() );
	}

	public static function provideGetSkinFromConfigSubpage() {
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
	 * @covers \MediaWiki\Title\Title::getSkinFromConfigSubpage
	 */
	public function testGetSkinFromConfigSubpage( $title, $expected ) {
		$title = Title::newFromText( $title );
		$this->assertSame( $expected, $title->getSkinFromConfigSubpage() );
	}

	/**
	 * @covers \MediaWiki\Title\Title::getWikiId
	 */
	public function testGetWikiId() {
		$title = Title::newFromText( 'Foo' );
		$this->assertFalse( $title->getWikiId() );
	}

	/**
	 * @covers \MediaWiki\Title\Title::getFragment
	 * @covers \MediaWiki\Title\Title::getFragment
	 * @covers \MediaWiki\Title\Title::uncache
	 */
	public function testSetFragment() {
		// NOTE: must use newFromText to test behavior of internal instance cache (T281337)
		$title = Title::newFromText( 'Foo' );

		$title->setFragment( '#Xyzzy' );
		$this->assertSame( 'Xyzzy', $title->getFragment() );

		// Ensure that the instance we get back from newFromText isn't the modified one.
		$title = Title::newFromText( 'Foo' );
		$this->assertNotSame( 'Xyzzy', $title->getFragment() );
	}

	/**
	 * @covers \MediaWiki\Title\Title::__clone
	 */
	public function testClone() {
		// NOTE: must use newFromText to test behavior of internal instance cache (T281337)
		$title = Title::newFromText( 'Foo' );

		$clone = clone $title;
		$clone->setFragment( '#Xyzzy' );

		// Ensure that the instance we get back from newFromText is the original one
		$title2 = Title::newFromText( 'Foo' );
		$this->assertSame( $title, $title2 );
	}

	public static function provideBaseTitleCases() {
		return [
			# Namespace, Title text, expected base
			[ NS_USER, 'John_Doe', 'John Doe' ],
			[ NS_USER, 'John_Doe/subOne/subTwo', 'John Doe/subOne' ],
			[ NS_USER, 'Foo / Bar / Baz', 'Foo / Bar ' ],
			[ NS_USER, 'Foo/', 'Foo' ],
			[ NS_USER, 'Foo/Bar/', 'Foo/Bar' ],
			[ NS_USER, '/', '/' ],
			[ NS_USER, '//', '/' ],
			[ NS_USER, '/oops/', '/oops' ],
			[ NS_USER, '/indeed', '/indeed' ],
			[ NS_USER, '//indeed', '/' ],
			[ NS_USER, '/Ramba/Zamba/Mamba/', '/Ramba/Zamba/Mamba' ],
			[ NS_USER, '//x//y//z//', '//x//y//z/' ],
		];
	}

	/**
	 * @dataProvider provideBaseTitleCases
	 * @covers \MediaWiki\Title\Title::getBaseText
	 */
	public function testGetBaseText( $namespace, $title, $expected ) {
		$title = Title::makeTitle( $namespace, $title );
		$this->assertSame( $expected, $title->getBaseText() );
	}

	/**
	 * @dataProvider provideBaseTitleCases
	 * @covers \MediaWiki\Title\Title::getBaseTitle
	 */
	public function testGetBaseTitle( $namespace, $title, $expected ) {
		$title = Title::makeTitle( $namespace, $title );
		$base = $title->getBaseTitle();
		$this->assertTrue( $base->isValid() );
		$this->assertTrue(
			$base->equals( Title::makeTitleSafe( $title->getNamespace(), $expected ) )
		);
	}

	/**
	 * Don't explode on invalid titles (T290194).
	 * @covers \MediaWiki\Title\Title::getBaseTitle
	 */
	public function testGetBaseTitle_invalid() {
		$title = Title::makeTitle( -23, 'Test' );
		$base = $title->getBaseTitle();
		$this->assertSame( $title, $base );
	}

	public static function provideRootTitleCases() {
		return [
			# Namespace, Title, expected base
			[ NS_USER, 'John_Doe', 'John Doe' ],
			[ NS_USER, 'John_Doe/subOne/subTwo', 'John Doe' ],
			[ NS_USER, 'Foo / Bar / Baz', 'Foo ' ],
			[ NS_USER, 'Foo/', 'Foo' ],
			[ NS_USER, 'Foo/Bar/', 'Foo' ],
			[ NS_USER, '/', '/' ],
			[ NS_USER, '//', '/' ],
			[ NS_USER, '/oops/', '/oops' ],
			[ NS_USER, '/Ramba/Zamba/Mamba/', '/Ramba' ],
			[ NS_USER, '//x//y//z//', '//x' ],
			[ NS_TALK, '////', '///' ],
			[ NS_TEMPLATE, '////', '///' ],
			[ NS_TEMPLATE, 'Foo////', 'Foo' ],
			[ NS_TEMPLATE, 'Foo////Bar', 'Foo' ],
		];
	}

	/**
	 * @dataProvider provideRootTitleCases
	 * @covers \MediaWiki\Title\Title::getRootText
	 */
	public function testGetRootText( $namespace, $title, $expected ) {
		$title = Title::makeTitle( $namespace, $title );
		$this->assertEquals( $expected, $title->getRootText() );
	}

	/**
	 * @dataProvider provideRootTitleCases
	 * @covers \MediaWiki\Title\Title::getRootTitle
	 */
	public function testGetRootTitle( $namespace, $title, $expected ) {
		$title = Title::makeTitle( $namespace, $title );
		$root = $title->getRootTitle();
		$this->assertTrue( $root->isValid() );
		$this->assertTrue(
			$root->equals( Title::makeTitleSafe( $title->getNamespace(), $expected ) )
		);
	}

	/**
	 * Don't explode on invalid titles (T290194).
	 * @covers \MediaWiki\Title\Title::getRootTitle
	 */
	public function testGetRootTitle_invalid() {
		$title = Title::makeTitle( -23, 'Test' );
		$base = $title->getRootTitle();
		$this->assertSame( $title, $base );
	}

	public static function provideSubpageTitleCases() {
		return [
			# Namespace, Title, expected base
			[ NS_USER, 'John_Doe', 'John Doe' ],
			[ NS_USER, 'John_Doe/subOne/subTwo', 'subTwo' ],
			[ NS_USER, 'John_Doe/subOne', 'subOne' ],
			[ NS_USER, '/', '/' ],
			[ NS_USER, '//', '' ],
			[ NS_USER, '/oops/', '' ],
			[ NS_USER, '/indeed', '/indeed' ],
			[ NS_USER, '//indeed', 'indeed' ],
			[ NS_USER, '/Ramba/Zamba/Mamba/', '' ],
			[ NS_USER, '//x//y//z//', '' ],
			[ NS_TEMPLATE, 'Foo', 'Foo' ],
			[ NS_CATEGORY, 'Foo', 'Foo' ],
			[ NS_MAIN, 'Bar', 'Bar' ],
		];
	}

	/**
	 * @dataProvider provideSubpageTitleCases
	 * @covers \MediaWiki\Title\Title::getSubpageText
	 */
	public function testGetSubpageText( $namespace, $title, $expected ) {
		$title = Title::makeTitle( $namespace, $title );
		$this->assertEquals( $expected, $title->getSubpageText() );
	}

	public static function provideGetTitleValue() {
		return [
			[ 'Foo' ],
			[ 'Foo#bar' ],
			[ 'User:Hansi_Maier' ],
		];
	}

	/**
	 * @covers \MediaWiki\Title\Title::getTitleValue
	 * @dataProvider provideGetTitleValue
	 */
	public function testGetTitleValue( $text ) {
		$title = Title::newFromText( $text );
		$value = $title->getTitleValue();

		$dbkey = str_replace( ' ', '_', $value->getText() );
		$this->assertEquals( $title->getDBkey(), $dbkey );
		$this->assertEquals( $title->getNamespace(), $value->getNamespace() );
		$this->assertEquals( $title->getFragment(), $value->getFragment() );
	}

	public static function provideGetFragment() {
		return [
			[ 'Foo', '' ],
			[ 'Foo#bar', 'bar' ],
			[ 'Foo#bär', 'bär' ],

			// Inner whitespace is normalized
			[ 'Foo#bar_bar', 'bar bar' ],
			[ 'Foo#bar bar', 'bar bar' ],
			[ 'Foo#bar   bar', 'bar bar' ],

			// Leading whitespace is kept, trailing whitespace is trimmed.
			// XXX: Is this really want we want?
			[ 'Foo#_bar_bar_', ' bar bar' ],
			[ 'Foo# bar bar ', ' bar bar' ],
		];
	}

	/**
	 * @covers \MediaWiki\Title\Title::getFragment
	 * @dataProvider provideGetFragment
	 *
	 * @param string $full
	 * @param string $fragment
	 */
	public function testGetFragment( $full, $fragment ) {
		$title = Title::newFromText( $full );
		$this->assertEquals( $fragment, $title->getFragment() );
	}

	/**
	 * @covers \MediaWiki\Title\Title::exists
	 */
	public function testExists() {
		$title = Title::makeTitle( NS_PROJECT, 'New page' );
		$linkCache = $this->getServiceContainer()->getLinkCache();

		$article = new Article( $title );
		$page = $article->getPage();
		$page->doUserEditContent(
			new WikitextContent( 'Some [[link]]' ),
			$this->getTestSysop()->getUser(),
			'summary'
		);

		// Tell Title it doesn't know whether it exists
		$title->mArticleID = -1;

		// Tell the link cache it doesn't exist when it really does
		$linkCache->clearLink( $title );
		$linkCache->addBadLinkObj( $title );

		$this->assertFalse(
			$title->exists(),
			'exists() should rely on link cache unless READ_LATEST is used'
		);
		$this->assertTrue(
			$title->exists( IDBAccessObject::READ_LATEST ),
			'exists() should re-query database when READ_LATEST is used'
		);
	}

	/**
	 * @covers \MediaWiki\Title\Title::getArticleID
	 * @covers \MediaWiki\Title\Title::getId
	 */
	public function testGetArticleID() {
		$title = Title::makeTitle( NS_PROJECT, __METHOD__ );
		$this->assertSame( 0, $title->getArticleID() );
		$this->assertSame( $title->getArticleID(), $title->getId() );

		$article = new Article( $title );
		$page = $article->getPage();
		$page->doUserEditContent(
			new WikitextContent( 'Some [[link]]' ),
			$this->getTestSysop()->getUser(),
			'summary'
		);

		$this->assertGreaterThan( 0, $title->getArticleID() );
		$this->assertSame( $title->getArticleID(), $title->getId() );
	}

	public static function provideNonProperTitles() {
		return [
			'section link' => [ Title::makeTitle( NS_MAIN, '', 'Section' ) ],
			'empty' => [ Title::makeTitle( NS_MAIN, '' ) ],
			'bad chars' => [ Title::makeTitle( NS_MAIN, '_|_' ) ],
			'empty in namspace' => [ Title::makeTitle( NS_USER, '' ) ],
			'special' => [ Title::makeTitle( NS_SPECIAL, 'RecentChanges' ) ],
			'interwiki' => [ Title::makeTitle( NS_MAIN, 'Test', '', 'acme' ) ],
		];
	}

	/**
	 * @dataProvider provideNonProperTitles
	 * @covers \MediaWiki\Title\Title::getArticleID
	 */
	public function testGetArticleIDFromNonProperTitle( $title ) {
		// make sure nothing explodes
		$this->assertSame( 0, $title->getArticleID() );
	}

	public static function provideCanHaveTalkPage() {
		return [
			'User page has talk page' => [
				Title::makeTitle( NS_USER, 'Jane' ), true
			],
			'Talke page has talk page' => [
				Title::makeTitle( NS_TALK, 'Foo' ), true
			],
			'Special page cannot have talk page' => [
				Title::makeTitle( NS_SPECIAL, 'Thing' ), false
			],
			'Virtual namespace cannot have talk page' => [
				Title::makeTitle( NS_MEDIA, 'Kitten.jpg' ), false
			],
			'Relative link has no talk page' => [
				Title::makeTitle( NS_MAIN, '', 'Kittens' ), false
			],
			'Interwiki link has no talk page' => [
				Title::makeTitle( NS_MAIN, 'Kittens', '', 'acme' ), false
			],
		];
	}

	public static function provideGetTalkPage_good() {
		return [
			[ Title::makeTitle( NS_MAIN, 'Test' ), Title::makeTitle( NS_TALK, 'Test' ) ],
			[ Title::makeTitle( NS_TALK, 'Test' ), Title::makeTitle( NS_TALK, 'Test' ) ],
		];
	}

	public static function provideGetTalkPage_bad() {
		return [
			[ Title::makeTitle( NS_SPECIAL, 'Test' ) ],
			[ Title::makeTitle( NS_MEDIA, 'Test' ) ],
		];
	}

	public static function provideGetTalkPage_broken() {
		// These cases *should* be bad, but are not treated as bad, for backwards compatibility.
		// See discussion on T227817.
		return [
			[
				Title::makeTitle( NS_MAIN, '', 'Kittens' ),
				Title::makeTitle( NS_TALK, '' ), // Section is lost!
				false,
			],
			[
				Title::makeTitle( NS_MAIN, 'Kittens', '', 'acme' ),
				Title::makeTitle( NS_TALK, 'Kittens', '' ), // Interwiki prefix is lost!
				true,
			],
		];
	}

	public static function provideGetSubjectPage_good() {
		return [
			[ Title::makeTitle( NS_TALK, 'Test' ), Title::makeTitle( NS_MAIN, 'Test' ) ],
			[ Title::makeTitle( NS_MAIN, 'Test' ), Title::makeTitle( NS_MAIN, 'Test' ) ],
		];
	}

	public static function provideGetOtherPage_good() {
		return [
			[ Title::makeTitle( NS_MAIN, 'Test' ), Title::makeTitle( NS_TALK, 'Test' ) ],
			[ Title::makeTitle( NS_TALK, 'Test' ), Title::makeTitle( NS_MAIN, 'Test' ) ],
		];
	}

	/**
	 * @dataProvider provideCanHaveTalkPage
	 * @covers \MediaWiki\Title\Title::canHaveTalkPage
	 *
	 * @param Title $title
	 * @param bool $expected
	 */
	public function testCanHaveTalkPage( Title $title, $expected ) {
		$actual = $title->canHaveTalkPage();
		$this->assertSame( $expected, $actual, $title->getPrefixedDBkey() );
	}

	/**
	 * @dataProvider provideGetTalkPage_good
	 * @covers \MediaWiki\Title\Title::getTalkPageIfDefined
	 */
	public function testGetTalkPage_good( Title $title, Title $expected ) {
		$actual = $title->getTalkPage();
		$this->assertTrue( $expected->equals( $actual ), $title->getPrefixedDBkey() );
	}

	/**
	 * @dataProvider provideGetTalkPage_bad
	 * @covers \MediaWiki\Title\Title::getTalkPageIfDefined
	 */
	public function testGetTalkPage_bad( Title $title ) {
		$this->expectException( MWException::class );
		$title->getTalkPage();
	}

	/**
	 * @dataProvider provideGetTalkPage_broken
	 * @covers \MediaWiki\Title\Title::getTalkPageIfDefined
	 */
	public function testGetTalkPage_broken( Title $title, Title $expected, $valid ) {
		// NOTE: Eventually we want to throw in this case. But while there is still code that
		// calls this method without checking, we want to avoid fatal errors.
		// See discussion on T227817.
		$result = @$title->getTalkPage();
		$this->assertTrue( $expected->equals( $result ) );
		$this->assertSame( $valid, $result->isValid() );
	}

	/**
	 * @dataProvider provideGetTalkPage_good
	 * @covers \MediaWiki\Title\Title::getTalkPageIfDefined
	 */
	public function testGetTalkPageIfDefined_good( Title $title, Title $expected ) {
		$actual = $title->getTalkPageIfDefined();
		$this->assertNotNull( $actual, $title->getPrefixedDBkey() );
		$this->assertTrue( $expected->equals( $actual ), $title->getPrefixedDBkey() );
	}

	/**
	 * @dataProvider provideGetTalkPage_bad
	 * @covers \MediaWiki\Title\Title::getTalkPageIfDefined
	 */
	public function testGetTalkPageIfDefined_bad( Title $title ) {
		$talk = $title->getTalkPageIfDefined();
		$this->assertNull(
			$talk,
			$title->getPrefixedDBkey()
		);
	}

	/**
	 * @dataProvider provideGetSubjectPage_good
	 * @covers \MediaWiki\Title\Title::getSubjectPage
	 */
	public function testGetSubjectPage_good( Title $title, Title $expected ) {
		$actual = $title->getSubjectPage();
		$this->assertTrue( $expected->equals( $actual ), $title->getPrefixedDBkey() );
	}

	/**
	 * @dataProvider provideGetOtherPage_good
	 * @covers \MediaWiki\Title\Title::getOtherPage
	 */
	public function testGetOtherPage_good( Title $title, Title $expected ) {
		$actual = $title->getOtherPage();
		$this->assertTrue( $expected->equals( $actual ), $title->getPrefixedDBkey() );
	}

	/**
	 * @dataProvider provideGetTalkPage_bad
	 * @covers \MediaWiki\Title\Title::getOtherPage
	 */
	public function testGetOtherPage_bad( Title $title ) {
		$this->expectException( MWException::class );
		$title->getOtherPage();
	}

	public static function provideIsMovable() {
		return [
			'Simple title' => [ 'Foo', true ],
			// @todo Should these next two really be true?
			'Empty name' => [ Title::makeTitle( NS_MAIN, '' ), true ],
			'Invalid name' => [ Title::makeTitle( NS_MAIN, '<' ), true ],
			'Interwiki' => [ Title::makeTitle( NS_MAIN, 'Test', '', 'otherwiki' ), false ],
			'Special page' => [ 'Special:FooBar', false ],
			'Aborted by hook' => [ 'Hooked in place', false,
				static function ( Title $title, &$result ) {
					$result = false;
				}
			],
		];
	}

	/**
	 * @dataProvider provideIsMovable
	 * @covers \MediaWiki\Title\Title::isMovable
	 *
	 * @param string|Title $title
	 * @param bool $expected
	 * @param callable|null $hookCallback For TitleIsMovable
	 */
	public function testIsMovable( $title, $expected, $hookCallback = null ) {
		if ( $hookCallback ) {
			$this->setTemporaryHook( 'TitleIsMovable', $hookCallback );
		}
		if ( is_string( $title ) ) {
			$title = Title::newFromText( $title );
		}

		$this->assertSame( $expected, $title->isMovable() );
	}

	public static function provideGetPrefixedText() {
		return [
			// ns = 0
			[
				Title::makeTitle( NS_MAIN, 'Foo bar' ),
				'Foo bar'
			],
			// ns = 2
			[
				Title::makeTitle( NS_USER, 'Foo bar' ),
				'User:Foo bar'
			],
			// ns = 3
			[
				Title::makeTitle( NS_USER_TALK, 'Foo bar' ),
				'User talk:Foo bar'
			],
			// fragment not included
			[
				Title::makeTitle( NS_MAIN, 'Foo bar', 'fragment' ),
				'Foo bar'
			],
			// ns = -2
			[
				Title::makeTitle( NS_MEDIA, 'Foo bar' ),
				'Media:Foo bar'
			],
			// non-existent namespace
			[
				Title::makeTitle( 100777, 'Foo bar' ),
				'Special:Badtitle/NS100777:Foo bar'
			],
		];
	}

	/**
	 * @covers \MediaWiki\Title\Title::getPrefixedText
	 * @dataProvider provideGetPrefixedText
	 */
	public function testGetPrefixedText( Title $title, $expected ) {
		$this->assertEquals( $expected, $title->getPrefixedText() );
	}

	public static function provideGetPrefixedDBKey() {
		return [
			// ns = 0
			[
				Title::makeTitle( NS_MAIN, 'Foo_bar' ),
				'Foo_bar'
			],
			// ns = 2
			[
				Title::makeTitle( NS_USER, 'Foo_bar' ),
				'User:Foo_bar'
			],
			// ns = 3
			[
				Title::makeTitle( NS_USER_TALK, 'Foo_bar' ),
				'User_talk:Foo_bar'
			],
			// fragment not included
			[
				Title::makeTitle( NS_MAIN, 'Foo_bar', 'fragment' ),
				'Foo_bar'
			],
			// ns = -2
			[
				Title::makeTitle( NS_MEDIA, 'Foo_bar' ),
				'Media:Foo_bar'
			],
			// non-existent namespace
			[
				Title::makeTitle( 100777, 'Foo_bar' ),
				'Special:Badtitle/NS100777:Foo_bar'
			],
		];
	}

	/**
	 * @covers \MediaWiki\Title\Title::getPrefixedDBKey
	 * @dataProvider provideGetPrefixedDBKey
	 */
	public function testGetPrefixedDBKey( Title $title, $expected ) {
		$this->assertEquals( $expected, $title->getPrefixedDBkey() );
	}

	public static function provideGetFragmentForURL() {
		return [
			[ 'Foo', '' ],
			[ 'Foo#ümlåût', '#ümlåût' ],
			[ 'de:Foo#Bå®', '#Bå®' ],
			[ 'zz:Foo#тест', '#.D1.82.D0.B5.D1.81.D1.82' ],
		];
	}

	/**
	 * @covers \MediaWiki\Title\Title::getFragmentForURL
	 * @dataProvider provideGetFragmentForURL
	 *
	 * @param string $titleStr
	 * @param string $expected
	 */
	public function testGetFragmentForURL( $titleStr, $expected ) {
		$this->overrideConfigValues( [
			MainConfigNames::FragmentMode => [ 'html5' ],
			MainConfigNames::ExternalInterwikiFragmentMode => 'legacy',
		] );
		// InterwikiLookup is configured in setUp()

		$title = Title::newFromText( $titleStr );
		self::assertEquals( $expected, $title->getFragmentForURL() );
	}

	public static function provideIsRawHtmlMessage() {
		return [
			[ 'MediaWiki:Foobar', true ],
			[ 'MediaWiki:Foo bar', true ],
			[ 'MediaWiki:Foo-bar', true ],
			[ 'MediaWiki:foo bar', true ],
			[ 'MediaWiki:foo-bar', true ],
			[ 'MediaWiki:foobar', true ],
			[ 'MediaWiki:some-other-message', false ],
			[ 'Main Page', false ],
		];
	}

	/**
	 * @covers \MediaWiki\Title\Title::isRawHtmlMessage
	 * @dataProvider provideIsRawHtmlMessage
	 */
	public function testIsRawHtmlMessage( $textForm, $expected ) {
		$this->overrideConfigValue(
			MainConfigNames::RawHtmlMessages,
			[
				'foobar',
				'foo_bar',
				'foo-bar',
			]
		);

		$title = Title::newFromText( $textForm );
		$this->assertSame( $expected, $title->isRawHtmlMessage() );
	}

	/**
	 * @covers \MediaWiki\Title\Title::newMainPage
	 */
	public function testNewMainPage() {
		$mock = $this->createMock( MessageCache::class );
		$mock->method( 'get' )->willReturn( 'Foresheet' );

		$this->setService( 'MessageCache', $mock );

		$this->assertSame(
			'Foresheet',
			Title::newMainPage()->getText()
		);
	}

	/**
	 * Regression test for T297571
	 *
	 * @covers \MediaWiki\Title\Title::newMainPage
	 */
	public function testNewMainPageNoRecursion() {
		$mock = $this->createMock( MessageCache::class );
		$mock->method( 'get' )->willReturn( 'localtestiw:' );
		$this->setService( 'MessageCache', $mock );

		$this->assertSame(
			'Main Page',
			Title::newMainPage()->getPrefixedText()
		);
	}

	/**
	 * @covers \MediaWiki\Title\Title::newMainPage
	 */
	public function testNewMainPageWithLocal() {
		$local = $this->createMock( MessageLocalizer::class );
		$local->method( 'msg' )->willReturn( new RawMessage( 'Prime Article' ) );

		$this->assertSame(
			'Prime Article',
			Title::newMainPage( $local )->getText()
		);
	}

	/**
	 * @covers \MediaWiki\Page\PageStore::getSubpages
	 */
	public function testGetSubpages() {
		$existingPage = $this->getExistingTestPage();
		$title = $existingPage->getTitle();

		$this->overrideConfigValue(
			MainConfigNames::NamespacesWithSubpages,
			[ $title->getNamespace() => true ]
		);

		$this->getExistingTestPage( $title->getSubpage( 'A' ) );
		$this->getExistingTestPage( $title->getSubpage( 'B' ) );

		$notQuiteSubpageTitle = $title->getPrefixedDBkey() . 'X'; // no slash!
		$this->getExistingTestPage( $notQuiteSubpageTitle );

		$subpages = iterator_to_array( $title->getSubpages() );

		$this->assertCount( 2, $subpages );
		$this->assertCount( 1, $title->getSubpages( 1 ) );
	}

	/**
	 * @covers \MediaWiki\Page\PageStore::getSubpages
	 */
	public function testGetSubpages_disabled() {
		$this->overrideConfigValue( MainConfigNames::NamespacesWithSubpages, [] );

		$existingPage = $this->getExistingTestPage();
		$title = $existingPage->getTitle();

		$this->getExistingTestPage( $title->getSubpage( 'A' ) );
		$this->getExistingTestPage( $title->getSubpage( 'B' ) );

		$this->assertSame( [], $title->getSubpages() );
	}

	public static function provideNamespaces() {
		// For ->isExternal() code path, construct a title with interwiki
		$title = Title::makeTitle( NS_FILE, 'test', 'frag', 'meta' );
		return [
			[ NS_MAIN, '' ],
			[ NS_FILE, 'File' ],
			[ NS_MEDIA, 'Media' ],
			[ NS_TALK, 'Talk' ],
			[ NS_CATEGORY, 'Category' ],
			[ $title, 'File' ],
		];
	}

	/**
	 * @covers \MediaWiki\Title\Title::getNsText
	 * @dataProvider provideNamespaces
	 */
	public function testGetNsText( $namespace, $expected ) {
		if ( $namespace instanceof Title ) {
			$this->assertSame( $expected, $namespace->getNsText() );
		} else {
			$actual = Title::makeTitle( $namespace, 'Title_test' )->getNsText();
			$this->assertSame( $expected, $actual );
		}
	}

	public static function providePagesWithSubjects() {
		return [
			[ Title::makeTitle( NS_USER_TALK, 'User_test' ), 'User' ],
			[ Title::makeTitle( NS_PROJECT, 'Test' ), 'Project' ],
			[ Title::makeTitle( NS_MAIN, 'Test' ), '' ],
			[ Title::makeTitle( NS_CATEGORY, 'Cat_test' ), 'Category' ],
		];
	}

	/**
	 * @covers \MediaWiki\Title\Title::getSubjectNsText
	 * @dataProvider providePagesWithSubjects
	 */
	public function testGetSubjectNsText( Title $title, $expected ) {
		$actual = $title->getSubjectNsText();
		$this->assertSame( $expected, $actual );
	}

	public static function provideTitlesWithTalkPages() {
		return [
			[ Title::makeTitle( NS_HELP, 'Help page' ), 'Help_talk' ],
			[ Title::newMainPage(), 'Talk' ],
			[ Title::makeTitle( NS_PROJECT, 'Test' ), 'Project_talk' ],
		];
	}

	/**
	 * @covers \MediaWiki\Title\Title::getTalkNsText
	 * @dataProvider provideTitlesWithTalkPages
	 */
	public function testGetTalkNsText( Title $title, $expected ) {
		$actual = $title->getTalkNsText();
		$this->assertSame( $expected, $actual );
	}

	/**
	 * @covers \MediaWiki\Title\Title::isSpecial
	 */
	public function testIsSpecial() {
		$title = Title::makeTitle( NS_SPECIAL, 'Recentchanges/Subpage' );
		$this->assertTrue( $title->isSpecial( 'Recentchanges' ) );
	}

	/**
	 * @covers \MediaWiki\Title\Title::isSpecial
	 */
	public function testIsNotSpecial() {
		$title = Title::newFromText( 'NotSpecialPage/Subpage', NS_SPECIAL );
		$this->assertFalse( $title->isSpecial( 'NotSpecialPage' ) );
	}

	/**
	 * @covers \MediaWiki\Title\Title::isTalkPage
	 */
	public function testIsTalkPage() {
		$title = Title::newFromText( 'Talk page', NS_TALK );
		$this->assertTrue( $title->isTalkPage() );

		$titleNotInTalkNs = Title::makeTitle( NS_HELP, 'Test' );
		$this->assertFalse( $titleNotInTalkNs->isTalkPage() );
	}

	/**
	 * @coversNothing
	 */
	public function testGetBacklinkCache() {
		$blcFactory = $this->getServiceContainer()->getBacklinkCacheFactory();
		$backlinkCache = $blcFactory->getBacklinkCache( Title::makeTitle( NS_FILE, 'Test' ) );
		$this->assertInstanceOf( BacklinkCache::class, $backlinkCache );
	}

	public static function provideNsWithSubpagesSupport() {
		return [
			[ NS_HELP, 'Mainhelp', 'Mainhelp/Subhelp' ],
			[ NS_USER, 'Mainuser', 'Mainuser/Subuser' ],
			[ NS_TALK, 'Maintalk', 'Maintalk/Subtalk' ],
			[ NS_PROJECT, 'Mainproject', 'Mainproject/Subproject' ],
		];
	}

	/**
	 * @covers \MediaWiki\Title\Title::isSubpage
	 * @covers \MediaWiki\Title\Title::isSubpageOf
	 * @dataProvider provideNsWithSubpagesSupport
	 */
	public function testIsSubpageOfWithNamespacesSubpages( $namespace, $pageName, $subpageName ) {
		$page = Title::makeTitle( $namespace, $pageName, '', 'meta' );
		$subPage = Title::makeTitle( $namespace, $subpageName, '', 'meta' );

		$this->assertTrue( $subPage->isSubpageOf( $page ) );
		$this->assertTrue( $subPage->isSubpage() );
	}

	public static function provideNsWithNoSubpages() {
		return [
			[ NS_CATEGORY, 'Maincat', 'Maincat/Subcat' ],
			[ NS_MAIN, 'Mainpage', 'Mainpage/Subpage' ]
		];
	}

	/**
	 * @covers \MediaWiki\Title\Title::isSubpage
	 * @covers \MediaWiki\Title\Title::isSubpageOf
	 * @dataProvider provideNsWithNoSubpages
	 */
	public function testIsSubpageOfWithoutNamespacesSubpages( $namespace, $pageName, $subpageName ) {
		$page = Title::makeTitle( $namespace, $pageName, '', 'meta' );
		$subPage = Title::makeTitle( $namespace, $subpageName, '', 'meta' );

		$this->assertFalse( $page->isSubpageOf( $page ) );
		$this->assertFalse( $subPage->isSubpage() );
	}

	public static function provideTitleEditURLs() {
		return [
			[ Title::makeTitle( NS_MAIN, 'Title' ), '/w/index.php?title=Title&action=edit' ],
			[ Title::makeTitle( NS_HELP, 'Test', '', 'mw' ), '' ],
			[ Title::makeTitle( NS_HELP, 'Test' ), '/w/index.php?title=Help:Test&action=edit' ],
		];
	}

	/**
	 * @covers \MediaWiki\Title\Title::getEditURL
	 * @dataProvider provideTitleEditURLs
	 */
	public function testGetEditURL( Title $title, $expected ) {
		$actual = $title->getEditURL();
		$this->assertSame( $expected, $actual );
	}

	public static function provideTitleEditURLsWithActionPaths() {
		return [
			[ Title::newFromText( 'Title', NS_MAIN ), '/wiki/edit/Title' ],
			[ Title::makeTitle( NS_HELP, 'Test', '', 'mw' ), '' ],
			[ Title::newFromText( 'Test', NS_HELP ), '/wiki/edit/Help:Test' ],
		];
	}

	/**
	 * @covers \MediaWiki\Title\Title::getEditURL
	 * @dataProvider provideTitleEditURLsWithActionPaths
	 */
	public function testGetEditUrlWithActionPaths( Title $title, $expected ) {
		$this->overrideConfigValue( MainConfigNames::ActionPaths, [ 'edit' => '/wiki/edit/$1' ] );
		$actual = $title->getEditURL();
		$this->assertSame( $expected, $actual );
	}

	/**
	 * @covers \MediaWiki\Title\Title::isMainPage
	 * @covers \MediaWiki\Title\Title::equals
	 */
	public function testIsMainPage() {
		$this->assertTrue( Title::newMainPage()->isMainPage() );
	}

	/**
	 * @covers \MediaWiki\Title\Title::isMainPage
	 * @covers \MediaWiki\Title\Title::equals
	 * @dataProvider provideMainPageTitles
	 */
	public function testIsNotMainPage( Title $title, $expected ) {
		$this->assertSame( $expected, $title->isMainPage() );
	}

	public static function provideMainPageTitles() {
		return [
			[ Title::makeTitle( NS_MAIN, 'Test' ), false ],
			[ Title::makeTitle( NS_CATEGORY, 'mw:Category' ), false ],
		];
	}

	/**
	 * @covers \MediaWiki\Title\Title::getPrefixedURL
	 * @covers \MediaWiki\Title\Title::prefix
	 * @dataProvider provideDataForTestGetPrefixedURL
	 */
	public function testGetPrefixedURL( Title $title, $expected ) {
		$actual = $title->getPrefixedURL();

		$this->assertSame( $expected, $actual );
	}

	public static function provideDataForTestGetPrefixedURL() {
		return [
			[ Title::makeTitle( NS_FILE, 'Title' ), 'File:Title' ],
			[ Title::makeTitle( NS_MEDIA, 'Title' ), 'Media:Title' ],
			[ Title::makeTitle( NS_CATEGORY, 'Title' ), 'Category:Title' ],
			[ Title::makeTitle( NS_FILE, 'Title with spaces' ), 'File:Title_with_spaces' ],
			[
				Title::makeTitle( NS_FILE, 'Title with spaces', '', 'mw' ),
				'mw:File:Title_with_spaces'
			],
		];
	}

	/**
	 * @covers \MediaWiki\Title\Title::__toString
	 */
	public function testToString() {
		$title = Title::makeTitle( NS_USER, 'User test' );

		$this->assertSame( 'User:User test', (string)$title );
	}

	/**
	 * @covers \MediaWiki\Title\Title::getFullText
	 * @dataProvider provideDataForTestGetFullText
	 */
	public function testGetFullText( Title $title, $expected ) {
		$actual = $title->getFullText();

		$this->assertSame( $expected, $actual );
	}

	public static function provideDataForTestGetFullText() {
		return [
			[ Title::makeTitle( NS_TALK, 'Test' ), 'Talk:Test' ],
			[ Title::makeTitle( NS_HELP, 'Test', 'frag' ), 'Help:Test#frag' ],
			[ Title::makeTitle( NS_TALK, 'Test', 'frag', 'phab' ), 'phab:Talk:Test#frag' ],
		];
	}

	public static function provideIsSamePageAs() {
		$title = Title::makeTitle( 0, 'Foo' );
		$title->resetArticleID( 1 );
		yield '(PageIdentityValue) same text, title has ID 0' => [
			$title,
			PageIdentityValue::localIdentity( 1, 0, 'Foo' ),
			true
		];

		$title = Title::makeTitle( 1, 'Bar_Baz' );
		$title->resetArticleID( 0 );
		yield '(PageIdentityValue) same text, PageIdentityValue has ID 0' => [
			$title,
			PageIdentityValue::localIdentity( 0, 1, 'Bar_Baz' ),
			true
		];

		$title = Title::makeTitle( 0, 'Foo' );
		$title->resetArticleID( 0 );
		yield '(PageIdentityValue) different text, both IDs are 0' => [
			$title,
			PageIdentityValue::localIdentity( 0, 0, 'Foozz' ),
			false
		];

		$title = Title::makeTitle( 0, 'Foo' );
		$title->resetArticleID( 0 );
		yield '(PageIdentityValue) different namespace' => [
			$title,
			PageIdentityValue::localIdentity( 0, 1, 'Foo' ),
			false
		];

		$title = Title::makeTitle( 0, 'Foo', '' );
		$title->resetArticleID( 1 );
		yield '(PageIdentityValue) different wiki, different ID' => [
			$title,
			new PageIdentityValue( 1, 0, 'Foo', 'bar' ),
			false
		];

		$title = Title::makeTitle( 0, 'Foo', '' );
		$title->resetArticleID( 0 );
		yield '(PageIdentityValue) different wiki, both IDs are 0' => [
			$title,
			new PageIdentityValue( 0, 0, 'Foo', 'bar' ),
			false
		];
	}

	/**
	 * @covers \MediaWiki\Title\Title::isSamePageAs
	 * @dataProvider provideIsSamePageAs
	 */
	public function testIsSamePageAs( Title $firstValue, $secondValue, $expectedSame ) {
		$this->assertSame(
			$expectedSame,
			$firstValue->isSamePageAs( $secondValue )
		);
	}

	/**
	 * @covers \MediaWiki\Title\Title::getArticleID
	 * @covers \MediaWiki\Title\Title::getId
	 * @covers \MediaWiki\Title\Title::getLength
	 * @covers \MediaWiki\Title\Title::getLatestRevID
	 * @covers \MediaWiki\Title\Title::exists
	 * @covers \MediaWiki\Title\Title::isNewPage
	 * @covers \MediaWiki\Title\Title::isRedirect
	 * @covers \MediaWiki\Title\Title::getTouched
	 * @covers \MediaWiki\Title\Title::getContentModel
	 * @covers \MediaWiki\Title\Title::getFieldFromPageStore
	 */
	public function testGetFieldsOfNonExistingPage() {
		$title = Title::makeTitle( NS_MAIN, 'ThisDoesNotExist-92347852349' );

		$this->assertSame( 0, $title->getArticleID() );
		$this->assertSame( 0, $title->getId() );
		$this->assertSame( 0, $title->getLength() );
		$this->assertSame( 0, $title->getLatestRevID() );
		$this->assertFalse( $title->exists() );
		$this->assertFalse( $title->isNewPage() );
		$this->assertFalse( $title->isRedirect() );
		$this->assertFalse( $title->getTouched() );
		$this->assertNotEmpty( $title->getContentModel() );
	}

	/**
	 * @covers \MediaWiki\Title\Title::getDefaultSystemMessage
	 */
	public function testGetDefaultSystemMessage() {
		$title = Title::makeTitle( NS_MEDIAWIKI, 'Logouttext' );

		$this->assertInstanceOf( Message::class, $title->getDefaultSystemMessage() );
		$this->assertStringContainsString( 'You are now logged out', $title->getDefaultMessageText() );
	}

	/**
	 * @covers \MediaWiki\Title\Title::getDefaultSystemMessage
	 */
	public function testGetDefaultSystemMessageReturnsNull() {
		$title = Title::makeTitle( NS_MAIN, 'Some title' );

		$this->assertNull( $title->getDefaultSystemMessage() );
	}

}
