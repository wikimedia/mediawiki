<?php

/**
 * @group ContentHandler
 * @group Database
 *        ^--- needed, because we do need the database to test link updates
 *
 * @FIXME this should not extend JavaScriptContentTest.
 */
class CssContentTest extends JavaScriptContentTest {

	protected function setUp() {
		parent::setUp();

		// Anon user
		$user = new User();
		$user->setName( '127.0.0.1' );

		$this->setMwGlobals( [
			'wgUser' => $user,
			'wgTextModelsToParse' => [
				CONTENT_MODEL_CSS,
			]
		] );
	}

	public function newContent( $text ) {
		return new CssContent( $text );
	}

	public static function dataGetParserOutput() {
		return [
			[
				'MediaWiki:Test.css',
				null,
				"hello <world>\n",
				"<pre class=\"mw-code mw-css\" dir=\"ltr\">\nhello &lt;world&gt;\n\n</pre>"
			],
			[
				'MediaWiki:Test.css',
				null,
				"/* hello [[world]] */\n",
				"<pre class=\"mw-code mw-css\" dir=\"ltr\">\n/* hello [[world]] */\n\n</pre>",
				[
					'Links' => [
						[ 'World' => 0 ]
					]
				]
			],

			// TODO: more...?
		];
	}

	/**
	 * @covers CssContent::getModel
	 */
	public function testGetModel() {
		$content = $this->newContent( 'hello world.' );

		$this->assertEquals( CONTENT_MODEL_CSS, $content->getModel() );
	}

	/**
	 * @covers CssContent::getContentHandler
	 */
	public function testGetContentHandler() {
		$content = $this->newContent( 'hello world.' );

		$this->assertEquals( CONTENT_MODEL_CSS, $content->getContentHandler()->getModelID() );
	}

	/**
	 * Redirects aren't supported
	 */
	public static function provideUpdateRedirect() {
		return [
			[
				'#REDIRECT [[Someplace]]',
				'#REDIRECT [[Someplace]]',
			],
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
		] );
		$content = new CssContent( $text );
		$target = $content->getRedirectTarget();
		$this->assertEquals( $title, $target ? $target->getPrefixedText() : null );
	}

	/**
	 * Keep this in sync with CssContentHandlerTest::provideMakeRedirectContent()
	 */
	public static function provideGetRedirectTarget() {
		// @codingStandardsIgnoreStart Generic.Files.LineLength
		return [
			[ 'MediaWiki:MonoBook.css', "/* #REDIRECT */@import url(//example.org/w/index.php?title=MediaWiki:MonoBook.css&action=raw&ctype=text/css);" ],
			[ 'User:FooBar/common.css', "/* #REDIRECT */@import url(//example.org/w/index.php?title=User:FooBar/common.css&action=raw&ctype=text/css);" ],
			[ 'Gadget:FooBaz.css', "/* #REDIRECT */@import url(//example.org/w/index.php?title=Gadget:FooBaz.css&action=raw&ctype=text/css);" ],
			# No #REDIRECT comment
			[ null, "@import url(//example.org/w/index.php?title=Gadget:FooBaz.css&action=raw&ctype=text/css);" ],
			# Wrong domain
			[ null, "/* #REDIRECT */@import url(//example.com/w/index.php?title=Gadget:FooBaz.css&action=raw&ctype=text/css);" ],
		];
		// @codingStandardsIgnoreEnd
	}

	public static function dataEquals() {
		return [
			[ new CssContent( 'hallo' ), null, false ],
			[ new CssContent( 'hallo' ), new CssContent( 'hallo' ), true ],
			[ new CssContent( 'hallo' ), new WikitextContent( 'hallo' ), false ],
			[ new CssContent( 'hallo' ), new CssContent( 'HALLO' ), false ],
		];
	}

	/**
	 * @dataProvider dataEquals
	 * @covers CssContent::equals
	 */
	public function testEquals( Content $a, Content $b = null, $equal = false ) {
		$this->assertEquals( $equal, $a->equals( $b ) );
	}
}
