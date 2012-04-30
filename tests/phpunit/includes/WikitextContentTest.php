<?php

class WikitextContentTest extends MediaWikiTestCase {

	public function setup() {
		$this->context = new RequestContext( new FauxRequest() );
		$this->context->setTitle( Title::newFromText( "Test" ) );
	}

	public function newContent( $text ) {
		return new WikitextContent( $text );
	}

	public function dataGetParserOutput() {
		return array(
			array("hello ''world''\n", "<p>hello <i>world</i>\n</p>"),
			// @todo: more...?
		);
	}

	/**
	 * @dataProvider dataGetParserOutput
	 */
	public function testGetParserOutput( $text, $expectedHtml ) {
		$content = $this->newContent( $text );

		$po = $content->getParserOutput( $this->context );

		$this->assertEquals( $expectedHtml, $po->getText() );
		return $po;
	}

	static $sections =

"Intro

== stuff ==
hello world

== test ==
just a test

== foo ==
more stuff
";

	public function dataGetSection() {
		return array(
			array( WikitextContentTest::$sections,
					"0",
					"Intro"
			),
			array( WikitextContentTest::$sections,
					"2",
"== test ==
just a test"
			),
			array( WikitextContentTest::$sections,
					"8",
					false
			),
		);
	}

	/**
	 * @dataProvider dataGetSection
	 */
	public function testGetSection( $text, $sectionId, $expectedText ) {
		$content = $this->newContent( $text );

		$sectionContent = $content->getSection( $sectionId );

		$this->assertEquals( $expectedText, is_null( $sectionContent ) ? null : $sectionContent->getNativeData() );
	}

	public function dataReplaceSection() {
		return array(
			array( WikitextContentTest::$sections,
			       "0",
			       "No more",
			       null,
			       trim( preg_replace( '/^Intro/sm', 'No more', WikitextContentTest::$sections ) )
			),
			array( WikitextContentTest::$sections,
			       "",
			       "No more",
			       null,
			       "No more"
			),
			array( WikitextContentTest::$sections,
			       "2",
			       "== TEST ==\nmore fun",
			       null,
			       trim( preg_replace( '/^== test ==.*== foo ==/sm', "== TEST ==\nmore fun\n\n== foo ==", WikitextContentTest::$sections ) )
			),
			array( WikitextContentTest::$sections,
			       "8",
			       "No more",
			       null,
			       WikitextContentTest::$sections
			),
			array( WikitextContentTest::$sections,
			       "new",
			       "No more",
			       "New",
			       trim( WikitextContentTest::$sections ) . "\n\n\n== New ==\n\nNo more"
			),
		);
	}

	/**
	 * @dataProvider dataReplaceSection
	 */
	public function testReplaceSection( $text, $section, $with, $sectionTitle, $expected ) {
		$content = $this->newContent( $text );
		$c = $content->replaceSection( $section, $this->newContent( $with ), $sectionTitle );

		$this->assertEquals( $expected, is_null( $c ) ? null : $c->getNativeData() );
	}

	public function testAddSectionHeader( ) {
		$content = $this->newContent( 'hello world' );
		$content = $content->addSectionHeader( 'test' );

		$this->assertEquals( "== test ==\n\nhello world", $content->getNativeData() );
	}

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
		global $wgUser, $wgContLang;
		$options = ParserOptions::newFromUserAndLang( $wgUser, $wgContLang );

		$content = $this->newContent( $text );
		$content = $content->preSaveTransform( $this->context->getTitle(), $this->context->getUser(), $options );

		$this->assertEquals( $expected, $content->getNativeData() );
	}

	public function dataPreloadTransform() {
		return array(
			array( 'hello this is ~~~',
			       "hello this is ~~~",
			),
			array( 'hello \'\'this\'\' is <noinclude>foo</noinclude><includeonly>bar</includeonly>',
			       'hello \'\'this\'\' is bar',
			),
		);
	}

	/**
	 * @dataProvider dataPreloadTransform
	 */
	public function testPreloadTransform( $text, $expected ) {
		global $wgUser, $wgContLang;
		$options = ParserOptions::newFromUserAndLang( $wgUser, $wgContLang );

		$content = $this->newContent( $text );
		$content = $content->preloadTransform( $this->context->getTitle(), $options );

		$this->assertEquals( $expected, $content->getNativeData() );
	}

	public function dataGetRedirectTarget() {
		return array(
			array( '#REDIRECT [[Test]]',
			       'Test',
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
	 * @dataProvider dataGetRedirectTarget
	 */
	public function testGetRedirectTarget( $text, $expected ) {
		$content = $this->newContent( $text );
		$t = $content->getRedirectTarget( );

		if ( is_null( $expected ) ) $this->assertNull( $t, "text should not have generated a redirect target: $text" );
		else $this->assertEquals( $expected, $t->getPrefixedText() );
	}

	/**
	 * @dataProvider dataGetRedirectTarget
	 */
	public function isRedirect( $text, $expected ) {
		$content = $this->newContent( $text );

		$this->assertEquals( !is_null($expected), $content->isRedirect() );
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
			       true
			),
			array( 'Foo',
			       null,
			       'link',
			       false
			),
			array( 'Foo [[bar]]',
			       null,
			       'link',
			       true
			),
			array( 'Foo',
			       true,
			       'link',
			       true
			),
			array( 'Foo [[bar]]',
			       false,
			       'link',
			       false
			),
			array( '#REDIRECT [[bar]]',
			       true,
			       'any',
			       false
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


	/**
	 * @dataProvider dataIsCountable
	 */
	public function testIsCountable( $text, $hasLinks, $mode, $expected ) {
		global $wgArticleCountMethod;

		$old = $wgArticleCountMethod;
		$wgArticleCountMethod = $mode;

		$content = $this->newContent( $text );

		$v = $content->isCountable( $hasLinks, $this->context );
		$wgArticleCountMethod = $old;

		$this->assertEquals( $expected, $v, "isCountable() returned unexpected value " . var_export( $v, true )
		                                    . " instead of " . var_export( $expected, true ) . " in mode `$mode` for text \"$text\"" );
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
			       'hel...',
			),
		);
	}

	/**
	 * @dataProvider dataGetTextForSummary
	 */
	public function testGetTextForSummary( $text, $maxlength, $expected ) {
		$content = $this->newContent( $text );

		$this->assertEquals( $expected, $content->getTextForSummary( $maxlength ) );
	}


	public function testGetTextForSearchIndex( ) {
		$content = $this->newContent( "hello world." );

		$this->assertEquals( "hello world.", $content->getTextForSearchIndex() );
	}

	public function testCopy() {
		$content = $this->newContent( "hello world." );
		$copy = $content->copy();

		$this->assertTrue( $content->equals( $copy ), "copy must be equal to original" );
		$this->assertEquals( "hello world.", $copy->getNativeData() );
	}

	public function testGetSize( ) {
		$content = $this->newContent( "hello world." );

		$this->assertEquals( 12, $content->getSize() );
	}

	public function testGetNativeData( ) {
		$content = $this->newContent( "hello world." );

		$this->assertEquals( "hello world.", $content->getNativeData() );
	}

	public function testGetWikitextForTransclusion( ) {
		$content = $this->newContent( "hello world." );

		$this->assertEquals( "hello world.", $content->getWikitextForTransclusion() );
	}

	# =================================================================================================================

	public function getModelName() {
		$content = $this->newContent( "hello world." );

		$this->assertEquals( CONTENT_MODEL_WIKITEXT, $content->getModelName() );
	}

	public function getContentHandler() {
		$content = $this->newContent( "hello world." );

		$this->assertEquals( CONTENT_MODEL_WIKITEXT, $content->getContentHandler()->getModelName() );
	}

	public function dataIsEmpty( ) {
		return array(
			array( '', true ),
			array( '  ', false ),
			array( '0', false ),
			array( 'hallo welt.', false ),
		);
	}

	/**
	 * @dataProvider dataIsEmpty
	 */
	public function testIsEmpty( $text, $empty ) {
		$content = $this->newContent( $text );

		$this->assertEquals( $empty, $content->isEmpty() );
	}

	public function dataEquals( ) {
		return array(
			array( new WikitextContent( "hallo" ), null, false ),
			array( new WikitextContent( "hallo" ), new WikitextContent( "hallo" ), true ),
			array( new WikitextContent( "hallo" ), new JavascriptContent( "hallo" ), false ),
			array( new WikitextContent( "hallo" ), new WikitextContent( "HALLO" ), false ),
		);
	}

	/**
	 * @dataProvider dataEquals
	 */
	public function testEquals( Content $a, Content $b = null, $equal = false ) {
		$this->assertEquals( $equal, $a->equals( $b ) );
	}

}
