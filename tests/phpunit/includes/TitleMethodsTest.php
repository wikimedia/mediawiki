<?php

class TitleMethodsTest extends MediaWikiTestCase {

	public function dataEquals() {
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
	 * @dataProvider dataEquals
	 */
	public function testEquals( $titleA, $titleB, $expectedBool ) {
		$titleA = Title::newFromText( $titleA );
		$titleB = Title::newFromText( $titleB );

		$this->assertEquals( $titleA->equals( $titleB ), $expectedBool );
		$this->assertEquals( $titleB->equals( $titleA ), $expectedBool );
	}

	public function dataInNamespace() {
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
	 * @dataProvider dataInNamespace
	 */
	public function testInNamespace( $title, $ns, $expectedBool ) {
		$title = Title::newFromText( $title );
		$this->assertEquals( $title->inNamespace( $ns ), $expectedBool );
	}

	public function testInNamespaces() {
		$mainpage = Title::newFromText( 'Main Page' );
		$this->assertTrue( $mainpage->inNamespaces( NS_MAIN, NS_USER ) );
		$this->assertTrue( $mainpage->inNamespaces( array( NS_MAIN, NS_USER ) ) );
		$this->assertTrue( $mainpage->inNamespaces( array( NS_USER, NS_MAIN ) ) );
		$this->assertFalse( $mainpage->inNamespaces( array( NS_PROJECT, NS_TEMPLATE ) ) );
	}

	public function dataHasSubjectNamespace() {
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
	 * @dataProvider dataHasSubjectNamespace
	 */
	public function testHasSubjectNamespace( $title, $ns, $expectedBool ) {
		$title = Title::newFromText( $title );
		$this->assertEquals( $title->hasSubjectNamespace( $ns ), $expectedBool );
	}

}
