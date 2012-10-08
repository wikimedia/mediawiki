<?php

class TitleMethodsTest extends MediaWikiTestCase {

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
	 */
	public function testInNamespace( $title, $ns, $expectedBool ) {
		$title = Title::newFromText( $title );
		$this->assertEquals( $expectedBool, $title->inNamespace( $ns ) );
	}

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
	 */
	public function testHasSubjectNamespace( $title, $ns, $expectedBool ) {
		$title = Title::newFromText( $title );
		$this->assertEquals( $expectedBool, $title->hasSubjectNamespace( $ns ) );
	}

	public static function provideIsCssOrJsPage() {
		return array(
			array( 'Foo', false ),
			array( 'Foo.js', false ),
			array( 'Foo/bar.js', false ),
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
		);
	}

	/**
	 * @dataProvider provideIsCssOrJsPage
	 */
	public function testIsCssOrJsPage( $title, $expectedBool ) {
		$title = Title::newFromText( $title );
		$this->assertEquals( $expectedBool, $title->isCssOrJsPage() );
	}


	public static function provideIsCssJsSubpage() {
		return array(
			array( 'Foo', false ),
			array( 'Foo.js', false ),
			array( 'Foo/bar.js', false ),
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
		);
	}

	/**
	 * @dataProvider provideIsCssJsSubpage
	 */
	public function testIsCssJsSubpage( $title, $expectedBool ) {
		$title = Title::newFromText( $title );
		$this->assertEquals( $expectedBool, $title->isCssJsSubpage() );
	}

	public static function provideIsCssSubpage() {
		return array(
			array( 'Foo', false ),
			array( 'Foo.css', false ),
			array( 'User:Foo', false ),
			array( 'User:Foo.js', false ),
			array( 'User:Foo.css', false ),
			array( 'User:Foo/bar.js', false ),
			array( 'User:Foo/bar.css', true ),
		);
	}

	/**
	 * @dataProvider provideIsCssSubpage
	 */
	public function testIsCssSubpage( $title, $expectedBool ) {
		$title = Title::newFromText( $title );
		$this->assertEquals( $expectedBool, $title->isCssSubpage() );
	}

	public static function provideIsJsSubpage() {
		return array(
			array( 'Foo', false ),
			array( 'Foo.css', false ),
			array( 'User:Foo', false ),
			array( 'User:Foo.js', false ),
			array( 'User:Foo.css', false ),
			array( 'User:Foo/bar.js', true ),
			array( 'User:Foo/bar.css', false ),
		);
	}

	/**
	 * @dataProvider provideIsJsSubpage
	 */
	public function testIsJsSubpage( $title, $expectedBool ) {
		$title = Title::newFromText( $title );
		$this->assertEquals( $expectedBool, $title->isJsSubpage() );
	}

	public static function provideIsWikitextPage() {
		return array(
			array( 'Foo', true ),
			array( 'Foo.js', true ),
			array( 'Foo/bar.js', true ),
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
		);
	}

	/**
	 * @dataProvider provideIsWikitextPage
	 */
	public function testIsWikitextPage( $title, $expectedBool ) {
		$title = Title::newFromText( $title );
		$this->assertEquals( $expectedBool, $title->isWikitextPage() );
	}

}
