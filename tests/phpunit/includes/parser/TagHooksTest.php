<?php

namespace MediaWiki\Tests\Parser;

use InvalidArgumentException;
use MediaWiki\Parser\ParserOptions;
use MediaWiki\Title\Title;
use MediaWiki\User\User;
use MediaWikiIntegrationTestCase;

/**
 * @group Database
 * @group Parser
 *
 * @covers \MediaWiki\Parser\Parser
 * @covers \MediaWiki\Parser\BlockLevelPass
 * @covers \MediaWiki\Parser\StripState
 *
 * @covers \MediaWiki\Parser\Preprocessor_Hash
 * @covers \MediaWiki\Parser\PPDStack_Hash
 * @covers \MediaWiki\Parser\PPDStackElement_Hash
 * @covers \MediaWiki\Parser\PPDPart_Hash
 * @covers \MediaWiki\Parser\PPFrame_Hash
 * @covers \MediaWiki\Parser\PPTemplateFrame_Hash
 * @covers \MediaWiki\Parser\PPCustomFrame_Hash
 * @covers \MediaWiki\Parser\PPNode_Hash_Tree
 * @covers \MediaWiki\Parser\PPNode_Hash_Text
 * @covers \MediaWiki\Parser\PPNode_Hash_Array
 * @covers \MediaWiki\Parser\PPNode_Hash_Attr
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
			$this->getServiceContainer()->getContentLanguage() );
		return $popt;
	}

	/**
	 * @dataProvider provideValidNames
	 */
	public function testTagHooks( $tag ) {
		$parser = $this->getServiceContainer()->getParserFactory()->create();

		$parser->setHook( $tag, $this->tagCallback( ... ) );
		$parserOutput = $parser->parse(
			"Foo<$tag>Bar</$tag>Baz",
			Title::makeTitle( NS_MAIN, 'Test' ),
			$this->getParserOptions()
		);
		$this->assertEquals( "<p>FooOneBaz\n</p>", $parserOutput->getRawText() );
	}

	/**
	 * @dataProvider provideBadNames
	 */
	public function testBadTagHooks( $tag ) {
		$parser = $this->getServiceContainer()->getParserFactory()->create();

		$this->expectException( InvalidArgumentException::class );
		$parser->setHook( $tag, $this->tagCallback( ... ) );
	}

	public function tagCallback( $text, $params, $parser ) {
		return str_rot13( $text );
	}
}
