<?php

/**
 * @group Parser
 */
class TagHookTest extends MediaWikiTestCase {
	public static function provideValidNames() {
		return array( array( 'foo' ), array( 'foo-bar' ), array( 'foo_bar' ), array( 'FOO-BAR' ), array( 'foo bar' ) );
	}

	public static function provideBadNames() {
		return array( array( "foo<bar" ), array( "foo>bar" ), array( "foo\nbar" ), array( "foo\rbar" ) );
	}

	protected function setUp() {
		parent::setUp();

		$this->setMwGlobals( 'wgAlwaysUseTidy', false );
	}

	/**
	 * @dataProvider provideValidNames
	 */
	public function testTagHooks( $tag ) {
		global $wgParserConf, $wgContLang;
		$parser = new Parser( $wgParserConf );

		$parser->setHook( $tag, array( $this, 'tagCallback' ) );
		$parserOutput = $parser->parse( "Foo<$tag>Bar</$tag>Baz", Title::newFromText( 'Test' ), ParserOptions::newFromUserAndLang( new User, $wgContLang ) );
		$this->assertEquals( "<p>FooOneBaz\n</p>", $parserOutput->getText() );

		$parser->mPreprocessor = null; # Break the Parser <-> Preprocessor cycle
	}

	/**
	 * @dataProvider provideBadNames
	 * @expectedException MWException
	 */
	public function testBadTagHooks( $tag ) {
		global $wgParserConf, $wgContLang;
		$parser = new Parser( $wgParserConf );

		$parser->setHook( $tag, array( $this, 'tagCallback' ) );
		$parser->parse( "Foo<$tag>Bar</$tag>Baz", Title::newFromText( 'Test' ), ParserOptions::newFromUserAndLang( new User, $wgContLang ) );
		$this->fail( 'Exception not thrown.' );
	}

	/**
	 * @dataProvider provideValidNames
	 */
	public function testFunctionTagHooks( $tag ) {
		global $wgParserConf, $wgContLang;
		$parser = new Parser( $wgParserConf );

		$parser->setFunctionTagHook( $tag, array( $this, 'functionTagCallback' ), 0 );
		$parserOutput = $parser->parse( "Foo<$tag>Bar</$tag>Baz", Title::newFromText( 'Test' ), ParserOptions::newFromUserAndLang( new User, $wgContLang ) );
		$this->assertEquals( "<p>FooOneBaz\n</p>", $parserOutput->getText() );

		$parser->mPreprocessor = null; # Break the Parser <-> Preprocessor cycle
	}

	/**
	 * @dataProvider provideBadNames
	 * @expectedException MWException
	 */
	public function testBadFunctionTagHooks( $tag ) {
		global $wgParserConf, $wgContLang;
		$parser = new Parser( $wgParserConf );

		$parser->setFunctionTagHook( $tag, array( $this, 'functionTagCallback' ), SFH_OBJECT_ARGS );
		$parser->parse( "Foo<$tag>Bar</$tag>Baz", Title::newFromText( 'Test' ), ParserOptions::newFromUserAndLang( new User, $wgContLang ) );
		$this->fail( 'Exception not thrown.' );
	}

	function tagCallback( $text, $params, $parser ) {
		return str_rot13( $text );
	}

	function functionTagCallback( &$parser, $frame, $code, $attribs ) {
		return str_rot13( $code );
	}
}
