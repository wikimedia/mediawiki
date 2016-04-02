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
		return [
			[
				'MediaWiki:Test.js',
				null,
				"hello <world>\n",
				"<pre class=\"mw-code mw-js\" dir=\"ltr\">\nhello &lt;world&gt;\n\n</pre>"
			],
			[
				'MediaWiki:Test.js',
				null,
				"hello(); // [[world]]\n",
				"<pre class=\"mw-code mw-js\" dir=\"ltr\">\nhello(); // [[world]]\n\n</pre>",
				[
					'Links' => [
						[ 'World' => 0 ]
					]
				]
			],

			// TODO: more...?
		];
	}

	// XXX: Unused function
	public static function dataGetSection() {
		return [
			[ WikitextContentTest::$sections,
				'0',
				null
			],
			[ WikitextContentTest::$sections,
				'2',
				null
			],
			[ WikitextContentTest::$sections,
				'8',
				null
			],
		];
	}

	// XXX: Unused function
	public static function dataReplaceSection() {
		return [
			[ WikitextContentTest::$sections,
				'0',
				'No more',
				null,
				null
			],
			[ WikitextContentTest::$sections,
				'',
				'No more',
				null,
				null
			],
			[ WikitextContentTest::$sections,
				'2',
				"== TEST ==\nmore fun",
				null,
				null
			],
			[ WikitextContentTest::$sections,
				'8',
				'No more',
				null,
				null
			],
			[ WikitextContentTest::$sections,
				'new',
				'No more',
				'New',
				null
			],
		];
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
		return [
			[ 'hello this is ~~~',
				"hello this is [[Special:Contributions/127.0.0.1|127.0.0.1]]",
			],
			[ 'hello \'\'this\'\' is <nowiki>~~~</nowiki>',
				'hello \'\'this\'\' is <nowiki>~~~</nowiki>',
			],
			[ " Foo \n ",
				" Foo",
			],
		];
	}

	public static function dataPreloadTransform() {
		return [
			[
				'hello this is ~~~',
				'hello this is ~~~',
			],
			[
				'hello \'\'this\'\' is <noinclude>foo</noinclude><includeonly>bar</includeonly>',
				'hello \'\'this\'\' is <noinclude>foo</noinclude><includeonly>bar</includeonly>',
			],
		];
	}

	public static function dataGetRedirectTarget() {
		return [
			[ '#REDIRECT [[Test]]',
				null,
			],
			[ '#REDIRECT Test',
				null,
			],
			[ '* #REDIRECT [[Test]]',
				null,
			],
		];
	}

	public static function dataIsCountable() {
		return [
			[ '',
				null,
				'any',
				true
			],
			[ 'Foo',
				null,
				'any',
				true
			],
			[ 'Foo',
				null,
				'comma',
				false
			],
			[ 'Foo, bar',
				null,
				'comma',
				false
			],
			[ 'Foo',
				null,
				'link',
				false
			],
			[ 'Foo [[bar]]',
				null,
				'link',
				false
			],
			[ 'Foo',
				true,
				'link',
				false
			],
			[ 'Foo [[bar]]',
				false,
				'link',
				false
			],
			[ '#REDIRECT [[bar]]',
				true,
				'any',
				true
			],
			[ '#REDIRECT [[bar]]',
				true,
				'comma',
				false
			],
			[ '#REDIRECT [[bar]]',
				true,
				'link',
				false
			],
		];
	}

	public static function dataGetTextForSummary() {
		return [
			[ "hello\nworld.",
				16,
				'hello world.',
			],
			[ 'hello world.',
				8,
				'hello...',
			],
			[ '[[hello world]].',
				8,
				'[[hel...',
			],
		];
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
	public function testUpdateRedirect( $oldText, $expectedText ) {
		$this->setMwGlobals( [
			'wgServer' => '//example.org',
			'wgScriptPath' => '/w',
			'wgScript' => '/w/index.php',
			'wgResourceBasePath' => '/w',
		] );
		$target = Title::newFromText( "testUpdateRedirect_target" );

		$content = new JavaScriptContent( $oldText );
		$newContent = $content->updateRedirect( $target );

		$this->assertEquals( $expectedText, $newContent->getNativeData() );
	}

	public static function provideUpdateRedirect() {
		return [
			[
				'#REDIRECT [[Someplace]]',
				'#REDIRECT [[Someplace]]',
			],

			// @codingStandardsIgnoreStart Generic.Files.LineLength
			[
				'/* #REDIRECT */mw.loader.load("//example.org/w/index.php?title=MediaWiki:MonoBook.js\u0026action=raw\u0026ctype=text/javascript");',
				'/* #REDIRECT */mw.loader.load("//example.org/w/index.php?title=TestUpdateRedirect_target\u0026action=raw\u0026ctype=text/javascript");'
			]
			// @codingStandardsIgnoreEnd
		];
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
		return [
			[ new JavaScriptContent( "hallo" ), null, false ],
			[ new JavaScriptContent( "hallo" ), new JavaScriptContent( "hallo" ), true ],
			[ new JavaScriptContent( "hallo" ), new CssContent( "hallo" ), false ],
			[ new JavaScriptContent( "hallo" ), new JavaScriptContent( "HALLO" ), false ],
		];
	}

	/**
	 * @dataProvider provideGetRedirectTarget
	 */
	public function testGetRedirectTarget( $title, $text ) {
		$this->setMwGlobals( [
			'wgServer' => '//example.org',
			'wgScriptPath' => '/w',
			'wgScript' => '/w/index.php',
			'wgResourceBasePath' => '/w',
		] );
		$content = new JavaScriptContent( $text );
		$target = $content->getRedirectTarget();
		$this->assertEquals( $title, $target ? $target->getPrefixedText() : null );
	}

	/**
	 * Keep this in sync with JavaScriptContentHandlerTest::provideMakeRedirectContent()
	 */
	public static function provideGetRedirectTarget() {
		// @codingStandardsIgnoreStart Generic.Files.LineLength
		return [
			[
				'MediaWiki:MonoBook.js',
				'/* #REDIRECT */mw.loader.load("//example.org/w/index.php?title=MediaWiki:MonoBook.js\u0026action=raw\u0026ctype=text/javascript");'
			],
			[
				'User:FooBar/common.js',
				'/* #REDIRECT */mw.loader.load("//example.org/w/index.php?title=User:FooBar/common.js\u0026action=raw\u0026ctype=text/javascript");'
			],
			[
				'Gadget:FooBaz.js',
				'/* #REDIRECT */mw.loader.load("//example.org/w/index.php?title=Gadget:FooBaz.js\u0026action=raw\u0026ctype=text/javascript");'
			],
			// No #REDIRECT comment
			[
				null,
				'mw.loader.load("//example.org/w/index.php?title=MediaWiki:NoRedirect.js\u0026action=raw\u0026ctype=text/javascript");'
			],
			// Different domain
			[
				null,
				'/* #REDIRECT */mw.loader.load("//example.com/w/index.php?title=MediaWiki:OtherWiki.js\u0026action=raw\u0026ctype=text/javascript");'
			],
		];
		// @codingStandardsIgnoreEnd
	}
}
