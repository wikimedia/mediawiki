<?php

/**
 * @group ContentHandler
 * @group Database
 *        ^--- needed, because we do need the database to test link updates
 */
class JavaScriptContentTest extends TextContentTest {

	public function newContent( $text ) {
		return new JavaScriptContent( $text );
	}

	public static function dataGetParserOutput() {
		return array(
			array(
				'MediaWiki:Test.js',
				null,
				"hello <world>\n",
				"<pre class=\"mw-code mw-js\" dir=\"ltr\">\nhello &lt;world&gt;\n\n</pre>"
			),
			array(
				'MediaWiki:Test.js',
				null,
				"hello(); // [[world]]\n",
				"<pre class=\"mw-code mw-js\" dir=\"ltr\">\nhello(); // [[world]]\n\n</pre>",
				array(
					'Links' => array(
						array( 'World' => 0 )
					)
				)
			),

			// TODO: more...?
		);
	}

	// XXX: Unused function
	public static function dataGetSection() {
		return array(
			array( WikitextContentTest::$sections,
				'0',
				null
			),
			array( WikitextContentTest::$sections,
				'2',
				null
			),
			array( WikitextContentTest::$sections,
				'8',
				null
			),
		);
	}

	// XXX: Unused function
	public static function dataReplaceSection() {
		return array(
			array( WikitextContentTest::$sections,
				'0',
				'No more',
				null,
				null
			),
			array( WikitextContentTest::$sections,
				'',
				'No more',
				null,
				null
			),
			array( WikitextContentTest::$sections,
				'2',
				"== TEST ==\nmore fun",
				null,
				null
			),
			array( WikitextContentTest::$sections,
				'8',
				'No more',
				null,
				null
			),
			array( WikitextContentTest::$sections,
				'new',
				'No more',
				'New',
				null
			),
		);
	}

	/**
	 * @covers JavaScriptContent::addSectionHeader
	 */
	public function testAddSectionHeader() {
		$content = $this->newContent( 'hello world' );
		$c = $content->addSectionHeader( 'test' );

		$this->assertTrue( $content->equals( $c ) );
	}

	// XXX: currently, preSaveTransform is applied to scripts. this may change or become optional.
	public static function dataPreSaveTransform() {
		return array(
			array( 'hello this is ~~~',
				"hello this is [[Special:Contributions/127.0.0.1|127.0.0.1]]",
			),
			array( 'hello \'\'this\'\' is <nowiki>~~~</nowiki>',
				'hello \'\'this\'\' is <nowiki>~~~</nowiki>',
			),
			array( " Foo \n ",
				" Foo",
			),
		);
	}

	public static function dataPreloadTransform() {
		return array(
			array( 'hello this is ~~~',
				'hello this is ~~~',
			),
			array( 'hello \'\'this\'\' is <noinclude>foo</noinclude><includeonly>bar</includeonly>',
				'hello \'\'this\'\' is <noinclude>foo</noinclude><includeonly>bar</includeonly>',
			),
		);
	}

	public static function dataGetRedirectTarget() {
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
	 * @todo Test needs database!
	 */
	/*
	public function getRedirectChain() {
		$text = $this->getNativeData();
		return Title::newFromRedirectArray( $text );
	}
	*/

	/**
	 * @todo Test needs database!
	 */
	/*
	public function getUltimateRedirectTarget() {
		$text = $this->getNativeData();
		return Title::newFromRedirectRecurse( $text );
	}
	*/

	public static function dataIsCountable() {
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

	public static function dataGetTextForSummary() {
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

	/**
	 * @covers JavaScriptContent::matchMagicWord
	 */
	public function testMatchMagicWord() {
		$mw = MagicWord::get( "staticredirect" );

		$content = $this->newContent( "#REDIRECT [[FOO]]\n__STATICREDIRECT__" );
		$this->assertFalse(
			$content->matchMagicWord( $mw ),
			"should not have matched magic word, since it's not wikitext"
		);
	}

	/**
	 * @covers JavaScriptContent::updateRedirect
	 * @dataProvider provideUpdateRedirect
	 */
	public function testUpdateRedirect( $oldText, $expectedText) {
		$this->setMwGlobals( array(
			'wgServer' => '//example.org',
			'wgScriptPath' => '/w/index.php',
		) );
		$target = Title::newFromText( "testUpdateRedirect_target" );

		$content = new JavaScriptContent( $oldText );
		$newContent = $content->updateRedirect( $target );

		$this->assertEquals( $expectedText, $newContent->getNativeData() );
	}

	public static function provideUpdateRedirect() {
		return array(
			array(
				'#REDIRECT [[Someplace]]',
				'#REDIRECT [[Someplace]]',
			),
			array(
				'/* #REDIRECT */mw.loader.load("//example.org/w/index.php?title=MediaWiki:MonoBook.js\u0026action=raw\u0026ctype=text/javascript");',
				'/* #REDIRECT */mw.loader.load("//example.org/w/index.php?title=TestUpdateRedirect_target\u0026action=raw\u0026ctype=text/javascript");'
			)
		);
	}

	/**
	 * @covers JavaScriptContent::getModel
	 */
	public function testGetModel() {
		$content = $this->newContent( "hello world." );

		$this->assertEquals( CONTENT_MODEL_JAVASCRIPT, $content->getModel() );
	}

	/**
	 * @covers JavaScriptContent::getContentHandler
	 */
	public function testGetContentHandler() {
		$content = $this->newContent( "hello world." );

		$this->assertEquals( CONTENT_MODEL_JAVASCRIPT, $content->getContentHandler()->getModelID() );
	}

	public static function dataEquals() {
		return array(
			array( new JavaScriptContent( "hallo" ), null, false ),
			array( new JavaScriptContent( "hallo" ), new JavaScriptContent( "hallo" ), true ),
			array( new JavaScriptContent( "hallo" ), new CssContent( "hallo" ), false ),
			array( new JavaScriptContent( "hallo" ), new JavaScriptContent( "HALLO" ), false ),
		);
	}

	/**
	 * @dataProvider provideGetRedirectTarget
	 */
	public function testGetRedirectTarget( $title, $text ) {
		$this->setMwGlobals( array(
			'wgServer' => '//example.org',
			'wgScriptPath' => '/w/index.php',
		) );
		$content = new JavaScriptContent( $text );
		$target = $content->getRedirectTarget();
		$this->assertEquals( $title, $target ? $target->getPrefixedText() : null );
	}

	/**
	 * Keep this in sync with JavaScriptContentHandlerTest::provideMakeRedirectContent()
	 */
	public static function provideGetRedirectTarget() {
		return array(
			array( 'MediaWiki:MonoBook.js', '/* #REDIRECT */mw.loader.load("//example.org/w/index.php?title=MediaWiki:MonoBook.js\u0026action=raw\u0026ctype=text/javascript");' ),
			array( 'User:FooBar/common.js', '/* #REDIRECT */mw.loader.load("//example.org/w/index.php?title=User:FooBar/common.js\u0026action=raw\u0026ctype=text/javascript");' ),
			array( 'Gadget:FooBaz.js', '/* #REDIRECT */mw.loader.load("//example.org/w/index.php?title=Gadget:FooBaz.js\u0026action=raw\u0026ctype=text/javascript");' ),
			// No #REDIRECT comment
			array( null, 'mw.loader.load("//example.org/w/index.php?title=MediaWiki:NoRedirect.js\u0026action=raw\u0026ctype=text/javascript");' ),
			// Different domain
			array( null, '/* #REDIRECT */mw.loader.load("//example.com/w/index.php?title=MediaWiki:OtherWiki.js\u0026action=raw\u0026ctype=text/javascript");' ),
		);
	}
}
