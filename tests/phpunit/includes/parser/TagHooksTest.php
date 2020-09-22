<?php

use MediaWiki\MediaWikiServices;

/**
 * @group Database
 * @group Parser
 *
 * @covers Parser
 * @covers BlockLevelPass
 * @covers StripState
 *
 * @covers Preprocessor_Hash
 * @covers PPDStack_Hash
 * @covers PPDStackElement_Hash
 * @covers PPDPart_Hash
 * @covers PPFrame_Hash
 * @covers PPTemplateFrame_Hash
 * @covers PPCustomFrame_Hash
 * @covers PPNode_Hash_Tree
 * @covers PPNode_Hash_Text
 * @covers PPNode_Hash_Array
 * @covers PPNode_Hash_Attr
 */
class TagHooksTest extends MediaWikiIntegrationTestCase {
	public static function provideValidNames() {
		return [
			[ 'foo' ],
			[ 'foo-bar' ],
			[ 'foo_bar' ],
			[ 'FOO-BAR' ],
			[ 'foo bar' ]
		];
	}

	public static function provideBadNames() {
		return [ [ "foo<bar" ], [ "foo>bar" ], [ "foo\nbar" ], [ "foo\rbar" ] ];
	}

	private function getParserOptions() {
		$popt = ParserOptions::newFromUserAndLang( new User,
			MediaWikiServices::getInstance()->getContentLanguage() );
		return $popt;
	}

	/**
	 * @dataProvider provideValidNames
	 */
	public function testTagHooks( $tag ) {
		$parser = MediaWikiServices::getInstance()->getParserFactory()->create();

		$parser->setHook( $tag, [ $this, 'tagCallback' ] );
		$parserOutput = $parser->parse(
			"Foo<$tag>Bar</$tag>Baz",
			Title::newFromText( 'Test' ),
			$this->getParserOptions()
		);
		$this->assertEquals( "<p>FooOneBaz\n</p>", $parserOutput->getText( [ 'unwrap' => true ] ) );

		$parser->mPreprocessor = null; # Break the Parser <-> Preprocessor cycle
	}

	/**
	 * @dataProvider provideBadNames
	 */
	public function testBadTagHooks( $tag ) {
		$parser = MediaWikiServices::getInstance()->getParserFactory()->create();

		$this->expectException( MWException::class );
		$parser->setHook( $tag, [ $this, 'tagCallback' ] );
	}

	public function tagCallback( $text, $params, $parser ) {
		return str_rot13( $text );
	}
}
