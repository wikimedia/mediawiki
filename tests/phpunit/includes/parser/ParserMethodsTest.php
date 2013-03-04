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

	// TODO: Add tests for cleanSig() / cleanSigInSig(), getSection(), replaceSection(), getPreloadText()
}
