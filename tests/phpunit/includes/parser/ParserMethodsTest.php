<?php

class ParserMethodsTest extends MediaWikiLangTestCase {

	public static function providePreSaveTransform() {
		return array(
			array( 'hello this is ~~~',
				"hello this is [[Special:Contributions/127.0.0.1|127.0.0.1]]",
			),
			array( 'hello \'\'this\'\' is <nowiki>~~~</nowiki>',
				'hello \'\'this\'\' is <nowiki>~~~</nowiki>',
			),
		);
	}

	/**
	 * @dataProvider providePreSaveTransform
	 * @covers Parser::preSaveTransform
	 */
	public function testPreSaveTransform( $text, $expected ) {
		global $wgParser;

		$title = Title::newFromText( str_replace( '::', '__', __METHOD__ ) );
		$user = new User();
		$user->setName( "127.0.0.1" );
		$popts = ParserOptions::newFromUser( $user );
		$text = $wgParser->preSaveTransform( $text, $title, $user, $popts );

		$this->assertEquals( $expected, $text );
	}

	/**
	 * @covers Parser::callParserFunction
	 */
	public function testCallParserFunction() {
		global $wgParser;

		// Normal parses test passing PPNodes. Test passing an array.
		$title = Title::newFromText( str_replace( '::', '__', __METHOD__ ) );
		$wgParser->startExternalParse( $title, new ParserOptions(), Parser::OT_HTML );
		$frame = $wgParser->getPreprocessor()->newFrame();
		$ret = $wgParser->callParserFunction( $frame, '#tag',
			array( 'pre', 'foo', 'style' => 'margin-left: 1.6em' )
		);
		$ret['text'] = $wgParser->mStripState->unstripBoth( $ret['text'] );
		$this->assertSame( array(
			'found' => true,
			'text' => '<pre style="margin-left: 1.6em">foo</pre>',
		), $ret, 'callParserFunction works for {{#tag:pre|foo|style=margin-left: 1.6em}}' );
	}

	/**
	 * @covers Parser::parse
	 * @covers ParserOutput::getSections
	 */
	public function testGetSections() {
		global $wgParser;

		$title = Title::newFromText( str_replace( '::', '__', __METHOD__ ) );
		$out = $wgParser->parse( "==foo==\n<h2>bar</h2>\n==baz==\n", $title, new ParserOptions() );
		$this->assertSame( array(
			array(
				'toclevel' => 1,
				'level' => '2',
				'line' => 'foo',
				'number' => '1',
				'index' => '1',
				'fromtitle' => $title->getPrefixedDBkey(),
				'byteoffset' => 0,
				'anchor' => 'foo',
			),
			array(
				'toclevel' => 1,
				'level' => '2',
				'line' => 'bar',
				'number' => '2',
				'index' => '',
				'fromtitle' => false,
				'byteoffset' => null,
				'anchor' => 'bar',
			),
			array(
				'toclevel' => 1,
				'level' => '2',
				'line' => 'baz',
				'number' => '3',
				'index' => '2',
				'fromtitle' => $title->getPrefixedDBkey(),
				'byteoffset' => 21,
				'anchor' => 'baz',
			),
		), $out->getSections(), 'getSections() with proper value when <h2> is used' );
	}
	//@Todo Add tests for cleanSig() / cleanSigInSig(), getSection(), replaceSection(), getPreloadText()
}
