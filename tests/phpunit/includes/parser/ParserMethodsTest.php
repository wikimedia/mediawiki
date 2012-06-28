<?php

class ParserMethodsTest extends MediaWikiLangTestCase {

	public function dataPreSaveTransform() {
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
	 * @dataProvider dataPreSaveTransform
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

	// TODO: Add tests for cleanSig() / cleanSigInSig(), getSection(), replaceSection(), getPreloadText()
}

