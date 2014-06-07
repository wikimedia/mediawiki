<?php

/**
 * @group ContentHandler
 * @group Database
 *
 * @note: We don't make assumptions about the main namespace.
 *        But we do expect the Help namespace to contain Wikitext.
 */
class TitleMethodsTest extends MediaWikiTestCase {

	protected function setUp() {
		global $wgContLang;

		parent::setUp();

		$this->mergeMwGlobalArrayValue(
			'wgExtraNamespaces',
			array(
				12302 => 'TEST-JS',
				12303 => 'TEST-JS_TALK',
			)
		);

		$this->mergeMwGlobalArrayValue(
			'wgNamespaceContentModels',
			array(
				12302 => CONTENT_MODEL_JAVASCRIPT,
			)
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
		return array(
			array( 'Main Page', 'Main Page', true ),
			array( 'Main Page', 'Not The Main Page', false ),
			array( 'Main Page', 'Project:Main Page', false ),
			array( 'File:Example.png', 'Image:Example.png', true ),
			array( 'Special:Version', 'Special:Version', true ),
			array( 'Special:Version', 'Special:Recentchanges', false ),
			array( 'Special:Version', 'Main Page', false ),
		);
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
		return array(
			array( 'Main Page', NS_MAIN, true ),
			array( 'Main Page', NS_TALK, false ),
			array( 'Main Page', NS_USER, false ),
			array( 'User:Foo', NS_USER, true ),
			array( 'User:Foo', NS_USER_TALK, false ),
			array( 'User:Foo', NS_TEMPLATE, false ),
			array( 'User_talk:Foo', NS_USER_TALK, true ),
			array( 'User_talk:Foo', NS_USER, false ),
		);
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
		$this->assertTrue( $mainpage->inNamespaces( array( NS_MAIN, NS_USER ) ) );
		$this->assertTrue( $mainpage->inNamespaces( array( NS_USER, NS_MAIN ) ) );
		$this->assertFalse( $mainpage->inNamespaces( array( NS_PROJECT, NS_TEMPLATE ) ) );
	}

	public static function provideHasSubjectNamespace() {
		return array(
			array( 'Main Page', NS_MAIN, true ),
			array( 'Main Page', NS_TALK, true ),
			array( 'Main Page', NS_USER, false ),
			array( 'User:Foo', NS_USER, true ),
			array( 'User:Foo', NS_USER_TALK, true ),
			array( 'User:Foo', NS_TEMPLATE, false ),
			array( 'User_talk:Foo', NS_USER_TALK, true ),
			array( 'User_talk:Foo', NS_USER, true ),
		);
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
		return array(
			array( 'Help:Foo', CONTENT_MODEL_WIKITEXT ),
			array( 'Help:Foo.js', CONTENT_MODEL_WIKITEXT ),
			array( 'Help:Foo/bar.js', CONTENT_MODEL_WIKITEXT ),
			array( 'User:Foo', CONTENT_MODEL_WIKITEXT ),
			array( 'User:Foo.js', CONTENT_MODEL_WIKITEXT ),
			array( 'User:Foo/bar.js', CONTENT_MODEL_JAVASCRIPT ),
			array( 'User:Foo/bar.css', CONTENT_MODEL_CSS ),
			array( 'User talk:Foo/bar.css', CONTENT_MODEL_WIKITEXT ),
			array( 'User:Foo/bar.js.xxx', CONTENT_MODEL_WIKITEXT ),
			array( 'User:Foo/bar.xxx', CONTENT_MODEL_WIKITEXT ),
			array( 'MediaWiki:Foo.js', CONTENT_MODEL_JAVASCRIPT ),
			array( 'MediaWiki:Foo.css', CONTENT_MODEL_CSS ),
			array( 'MediaWiki:Foo/bar.css', CONTENT_MODEL_CSS ),
			array( 'MediaWiki:Foo.JS', CONTENT_MODEL_WIKITEXT ),
			array( 'MediaWiki:Foo.CSS', CONTENT_MODEL_WIKITEXT ),
			array( 'MediaWiki:Foo.css.xxx', CONTENT_MODEL_WIKITEXT ),
			array( 'TEST-JS:Foo', CONTENT_MODEL_JAVASCRIPT ),
			array( 'TEST-JS:Foo.js', CONTENT_MODEL_JAVASCRIPT ),
			array( 'TEST-JS:Foo/bar.js', CONTENT_MODEL_JAVASCRIPT ),
			array( 'TEST-JS_TALK:Foo.js', CONTENT_MODEL_WIKITEXT ),
		);
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
		return array(
			array( 'Help:Foo', false ),
			array( 'Help:Foo.js', false ),
			array( 'Help:Foo/bar.js', false ),
			array( 'User:Foo', false ),
			array( 'User:Foo.js', false ),
			array( 'User:Foo/bar.js', false ),
			array( 'User:Foo/bar.css', false ),
			array( 'User talk:Foo/bar.css', false ),
			array( 'User:Foo/bar.js.xxx', false ),
			array( 'User:Foo/bar.xxx', false ),
			array( 'MediaWiki:Foo.js', true ),
			array( 'MediaWiki:Foo.css', true ),
			array( 'MediaWiki:Foo.JS', false ),
			array( 'MediaWiki:Foo.CSS', false ),
			array( 'MediaWiki:Foo.css.xxx', false ),
			array( 'TEST-JS:Foo', false ),
			array( 'TEST-JS:Foo.js', false ),
		);
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
		return array(
			array( 'Help:Foo', false ),
			array( 'Help:Foo.js', false ),
			array( 'Help:Foo/bar.js', false ),
			array( 'User:Foo', false ),
			array( 'User:Foo.js', false ),
			array( 'User:Foo/bar.js', true ),
			array( 'User:Foo/bar.css', true ),
			array( 'User talk:Foo/bar.css', false ),
			array( 'User:Foo/bar.js.xxx', false ),
			array( 'User:Foo/bar.xxx', false ),
			array( 'MediaWiki:Foo.js', false ),
			array( 'User:Foo/bar.JS', false ),
			array( 'User:Foo/bar.CSS', false ),
			array( 'TEST-JS:Foo', false ),
			array( 'TEST-JS:Foo.js', false ),
		);
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
		return array(
			array( 'Help:Foo', false ),
			array( 'Help:Foo.css', false ),
			array( 'User:Foo', false ),
			array( 'User:Foo.js', false ),
			array( 'User:Foo.css', false ),
			array( 'User:Foo/bar.js', false ),
			array( 'User:Foo/bar.css', true ),
		);
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
		return array(
			array( 'Help:Foo', false ),
			array( 'Help:Foo.css', false ),
			array( 'User:Foo', false ),
			array( 'User:Foo.js', false ),
			array( 'User:Foo.css', false ),
			array( 'User:Foo/bar.js', true ),
			array( 'User:Foo/bar.css', false ),
		);
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
		return array(
			array( 'Help:Foo', true ),
			array( 'Help:Foo.js', true ),
			array( 'Help:Foo/bar.js', true ),
			array( 'User:Foo', true ),
			array( 'User:Foo.js', true ),
			array( 'User:Foo/bar.js', false ),
			array( 'User:Foo/bar.css', false ),
			array( 'User talk:Foo/bar.css', true ),
			array( 'User:Foo/bar.js.xxx', true ),
			array( 'User:Foo/bar.xxx', true ),
			array( 'MediaWiki:Foo.js', false ),
			array( 'MediaWiki:Foo.css', false ),
			array( 'MediaWiki:Foo/bar.css', false ),
			array( 'User:Foo/bar.JS', true ),
			array( 'User:Foo/bar.CSS', true ),
			array( 'TEST-JS:Foo', false ),
			array( 'TEST-JS:Foo.js', false ),
			array( 'TEST-JS_TALK:Foo.js', true ),
		);
	}

	/**
	 * @dataProvider provideIsWikitextPage
	 * @covers Title::isWikitextPage
	 */
	public function testIsWikitextPage( $title, $expectedBool ) {
		$title = Title::newFromText( $title );
		$this->assertEquals( $expectedBool, $title->isWikitextPage() );
	}
}
