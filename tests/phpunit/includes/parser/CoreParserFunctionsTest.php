<?php

use MediaWiki\MediaWikiServices;

/**
 * @group Database
 * @covers CoreParserFunctions
 */
class CoreParserFunctionsTest extends MediaWikiLangTestCase {

	public function testGender() {
		$user = User::createNew( '*Female' );
		$user->setOption( 'gender', 'female' );
		$user->saveSettings();

		$msg = ( new RawMessage( '{{GENDER:*Female|m|f|o}}' ) )->parse();
		$this->assertEquals( $msg, 'f', 'Works unescaped' );
		$escapedName = wfEscapeWikiText( '*Female' );
		$msg2 = ( new RawMessage( '{{GENDER:' . $escapedName . '|m|f|o}}' ) )
			->parse();
		$this->assertEquals( $msg, 'f', 'Works escaped' );
	}

	public function provideTalkpagename() {
		yield [ 'Talk:Foo bar', 'foo_bar' ];
		yield [ 'Talk:Foo', ' foo ' ];
		yield [ 'Talk:Foo', 'Talk:Foo' ];
		yield [ 'User talk:Foo', 'User:foo' ];
		yield [ '', 'Special:Foo' ];
		yield [ '', '' ];
		yield [ '', ' ' ];
		yield [ '', '__' ];
		yield [ '', '#xyzzy' ];
		yield [ '', '#' ];
		yield [ '', ':' ];
		yield [ '', ':#' ];
		yield [ '', 'User:' ];
		yield [ '', 'User:#' ];
	}

	/**
	 * @dataProvider provideTalkpagename
	 */
	public function testTalkpagename( $expected, $title ) {
		$parser = MediaWikiServices::getInstance()->getParser();

		$this->assertSame( $expected, CoreParserFunctions::talkpagename( $parser, $title ) );
	}

	public function provideSubjectpagename() {
		yield [ 'Foo bar', 'Talk:foo_bar' ];
		yield [ 'Foo', ' Talk:foo ' ];
		yield [ 'User:Foo', 'User talk:foo' ];
		yield [ 'Special:Foo', 'Special:Foo' ];
		yield [ '', '' ];
		yield [ '', ' ' ];
		yield [ '', '__' ];
		yield [ '', '#xyzzy' ];
		yield [ '', '#' ];
		yield [ '', ':' ];
		yield [ '', ':#' ];
		yield [ '', 'Talk:' ];
		yield [ '', 'User talk:#' ];
		yield [ '', 'User:#' ];
	}

	/**
	 * @dataProvider provideTalkpagename
	 */
	public function testSubjectpagename( $expected, $title ) {
		$parser = MediaWikiServices::getInstance()->getParser();

		$this->assertSame( $expected, CoreParserFunctions::talkpagename( $parser, $title ) );
	}

}
