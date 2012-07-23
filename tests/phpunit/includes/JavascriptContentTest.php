<?php

/**
 * @group ContentHandler
 */
class JavascriptContentTest extends WikitextContentTest {

	public function newContent( $text ) {
		return new JavascriptContent( $text );
	}


	public function dataGetParserOutput() {
		return array(
			array("MediaWiki:Test.js", "hello <world>\n", "<pre class=\"mw-code mw-js\" dir=\"ltr\">\nhello &lt;world&gt;\n\n</pre>\n"),
			// @todo: more...?
		);
	}

	public function dataGetSection() {
		return array(
			array( WikitextContentTest::$sections,
			       "0",
			       null
			),
			array( WikitextContentTest::$sections,
			       "2",
			       null
			),
			array( WikitextContentTest::$sections,
			       "8",
			       null
			),
		);
	}

	public function dataReplaceSection() {
		return array(
			array( WikitextContentTest::$sections,
			       "0",
			       "No more",
			       null,
			       null
			),
			array( WikitextContentTest::$sections,
			       "",
			       "No more",
			       null,
			       null
			),
			array( WikitextContentTest::$sections,
			       "2",
			       "== TEST ==\nmore fun",
			       null,
			       null
			),
			array( WikitextContentTest::$sections,
			       "8",
			       "No more",
			       null,
			       null
			),
			array( WikitextContentTest::$sections,
			       "new",
			       "No more",
			       "New",
			       null
			),
		);
	}

	public function testAddSectionHeader( ) {
		$content = $this->newContent( 'hello world' );
		$c = $content->addSectionHeader( 'test' );

		$this->assertTrue( $content->equals( $c ) );
	}

	// XXX: currently, preSaveTransform is applied to scripts. this may change or become optional.
	/*
	public function dataPreSaveTransform() {
		return array(
			array( 'hello this is ~~~',
			       "hello this is ~~~",
			),
			array( 'hello \'\'this\'\' is <nowiki>~~~</nowiki>',
			       'hello \'\'this\'\' is <nowiki>~~~</nowiki>',
			),
		);
	}
	*/

	public function dataPreloadTransform() {
		return array(
			array( 'hello this is ~~~',
			       "hello this is ~~~",
			),
			array( 'hello \'\'this\'\' is <noinclude>foo</noinclude><includeonly>bar</includeonly>',
			       'hello \'\'this\'\' is <noinclude>foo</noinclude><includeonly>bar</includeonly>',
			),
		);
	}

	public function dataGetRedirectTarget() {
		return array(
			array( '#REDIRECT [[Test]]',
			       null,
			),
			array( '#REDIRECT Test',
			       null,
			),
			array( '* #REDIRECT [[Test]]',
			       null,
			),
		);
	}

	/**
	 * @todo: test needs database!
	 */
	/*
	public function getRedirectChain() {
		$text = $this->getNativeData();
		return Title::newFromRedirectArray( $text );
	}
	*/

	/**
	 * @todo: test needs database!
	 */
	/*
	public function getUltimateRedirectTarget() {
		$text = $this->getNativeData();
		return Title::newFromRedirectRecurse( $text );
	}
	*/


	public function dataIsCountable() {
		return array(
			array( '',
			       null,
			       'any',
			       true
			),
			array( 'Foo',
			       null,
			       'any',
			       true
			),
			array( 'Foo',
			       null,
			       'comma',
			       false
			),
			array( 'Foo, bar',
			       null,
			       'comma',
			       false
			),
			array( 'Foo',
			       null,
			       'link',
			       false
			),
			array( 'Foo [[bar]]',
			       null,
			       'link',
			       false
			),
			array( 'Foo',
			       true,
			       'link',
			       false
			),
			array( 'Foo [[bar]]',
			       false,
			       'link',
			       false
			),
			array( '#REDIRECT [[bar]]',
			       true,
			       'any',
			       true
			),
			array( '#REDIRECT [[bar]]',
			       true,
			       'comma',
			       false
			),
			array( '#REDIRECT [[bar]]',
			       true,
			       'link',
			       false
			),
		);
	}

	public function dataGetTextForSummary() {
		return array(
			array( "hello\nworld.",
			       16,
			       'hello world.',
			),
			array( 'hello world.',
			       8,
			       'hello...',
			),
			array( '[[hello world]].',
			       8,
			       '[[hel...',
			),
		);
	}

	# =================================================================================================================

	public function testGetModel() {
		$content = $this->newContent( "hello world." );

		$this->assertEquals( CONTENT_MODEL_JAVASCRIPT, $content->getModel() );
	}

	public function testGetContentHandler() {
		$content = $this->newContent( "hello world." );

		$this->assertEquals( CONTENT_MODEL_JAVASCRIPT, $content->getContentHandler()->getModelID() );
	}

	public function dataEquals( ) {
		return array(
			array( new JavascriptContent( "hallo" ), null, false ),
			array( new JavascriptContent( "hallo" ), new JavascriptContent( "hallo" ), true ),
			array( new JavascriptContent( "hallo" ), new CssContent( "hallo" ), false ),
			array( new JavascriptContent( "hallo" ), new JavascriptContent( "HALLO" ), false ),
		);
	}

}
