<?php

/**
 * @group ContentHandler
 * @group Database
 *
 * @note We don't make assumptions about the main namespace.
 *       But we do expect the Help namespace to contain Wikitext.
 */
class TitleMethodsTest extends MediaWikiLangTestCase {

	protected function setUp() {
		global $wgContLang;

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

		MWNamespace::getCanonicalNamespaces( true ); # reset namespace cache
		$wgContLang->resetNamespaces(); # reset namespace cache
	}

	protected function tearDown() {
		global $wgContLang;

		parent::tearDown();

		MWNamespace::getCanonicalNamespaces( true ); # reset namespace cache
		$wgContLang->resetNamespaces(); # reset namespace cache
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

	public static function provideIsCssOrJsPage() {
		return [
			[ 'Help:Foo', false ],
			[ 'Help:Foo.js', false ],
			[ 'Help:Foo/bar.js', false ],
			[ 'User:Foo', false ],
			[ 'User:Foo.js', false ],
			[ 'User:Foo/bar.js', false ],
			[ 'User:Foo/bar.css', false ],
			[ 'User talk:Foo/bar.css', false ],
			[ 'User:Foo/bar.js.xxx', false ],
			[ 'User:Foo/bar.xxx', false ],
			[ 'MediaWiki:Foo.js', true ],
			[ 'MediaWiki:Foo.css', true ],
			[ 'MediaWiki:Foo.JS', false ],
			[ 'MediaWiki:Foo.CSS', false ],
			[ 'MediaWiki:Foo.css.xxx', false ],
			[ 'TEST-JS:Foo', false ],
			[ 'TEST-JS:Foo.js', false ],
		];
	}

	/**
	 * @dataProvider provideIsCssOrJsPage
	 * @covers Title::isCssOrJsPage
	 */
	public function testIsCssOrJsPage( $title, $expectedBool ) {
		$title = Title::newFromText( $title );
		$this->assertEquals( $expectedBool, $title->isCssOrJsPage() );
	}

	public static function provideIsCssJsSubpage() {
		return [
			[ 'Help:Foo', false ],
			[ 'Help:Foo.js', false ],
			[ 'Help:Foo/bar.js', false ],
			[ 'User:Foo', false ],
			[ 'User:Foo.js', false ],
			[ 'User:Foo/bar.js', true ],
			[ 'User:Foo/bar.css', true ],
			[ 'User talk:Foo/bar.css', false ],
			[ 'User:Foo/bar.js.xxx', false ],
			[ 'User:Foo/bar.xxx', false ],
			[ 'MediaWiki:Foo.js', false ],
			[ 'User:Foo/bar.JS', false ],
			[ 'User:Foo/bar.CSS', false ],
			[ 'TEST-JS:Foo', false ],
			[ 'TEST-JS:Foo.js', false ],
		];
	}

	/**
	 * @dataProvider provideIsCssJsSubpage
	 * @covers Title::isCssJsSubpage
	 */
	public function testIsCssJsSubpage( $title, $expectedBool ) {
		$title = Title::newFromText( $title );
		$this->assertEquals( $expectedBool, $title->isCssJsSubpage() );
	}

	public static function provideIsCssSubpage() {
		return [
			[ 'Help:Foo', false ],
			[ 'Help:Foo.css', false ],
			[ 'User:Foo', false ],
			[ 'User:Foo.js', false ],
			[ 'User:Foo.css', false ],
			[ 'User:Foo/bar.js', false ],
			[ 'User:Foo/bar.css', true ],
		];
	}

	/**
	 * @dataProvider provideIsCssSubpage
	 * @covers Title::isCssSubpage
	 */
	public function testIsCssSubpage( $title, $expectedBool ) {
		$title = Title::newFromText( $title );
		$this->assertEquals( $expectedBool, $title->isCssSubpage() );
	}

	public static function provideIsJsSubpage() {
		return [
			[ 'Help:Foo', false ],
			[ 'Help:Foo.css', false ],
			[ 'User:Foo', false ],
			[ 'User:Foo.js', false ],
			[ 'User:Foo.css', false ],
			[ 'User:Foo/bar.js', true ],
			[ 'User:Foo/bar.css', false ],
		];
	}

	/**
	 * @dataProvider provideIsJsSubpage
	 * @covers Title::isJsSubpage
	 */
	public function testIsJsSubpage( $title, $expectedBool ) {
		$title = Title::newFromText( $title );
		$this->assertEquals( $expectedBool, $title->isJsSubpage() );
	}

	public static function provideIsWikitextPage() {
		return [
			[ 'Help:Foo', true ],
			[ 'Help:Foo.js', true ],
			[ 'Help:Foo/bar.js', true ],
			[ 'User:Foo', true ],
			[ 'User:Foo.js', true ],
			[ 'User:Foo/bar.js', false ],
			[ 'User:Foo/bar.css', false ],
			[ 'User talk:Foo/bar.css', true ],
			[ 'User:Foo/bar.js.xxx', true ],
			[ 'User:Foo/bar.xxx', true ],
			[ 'MediaWiki:Foo.js', false ],
			[ 'MediaWiki:Foo.css', false ],
			[ 'MediaWiki:Foo/bar.css', false ],
			[ 'User:Foo/bar.JS', true ],
			[ 'User:Foo/bar.CSS', true ],
			[ 'TEST-JS:Foo', false ],
			[ 'TEST-JS:Foo.js', false ],
			[ 'TEST-JS_TALK:Foo.js', true ],
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
			$this->setExpectedException( 'MWException' );
		}

		$title = Title::newFromText( $text );
		$this->assertEquals( $expected, $title->getOtherPage()->getPrefixedText() );
	}

	public function testClearCaches() {
		$linkCache = LinkCache::singleton();

		$title1 = Title::newFromText( 'Foo' );
		$linkCache->addGoodLinkObj( 23, $title1 );

		Title::clearCaches();

		$title2 = Title::newFromText( 'Foo' );
		$this->assertNotSame( $title1, $title2, 'title cache should be empty' );
		$this->assertEquals( 0, $linkCache->getGoodLinkID( 'Foo' ), 'link cache should be empty' );
	}
}
