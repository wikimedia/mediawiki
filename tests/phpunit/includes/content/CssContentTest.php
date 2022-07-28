<?php

use MediaWiki\MainConfigNames;

/**
 * @group ContentHandler
 * @group Database
 *        ^--- needed, because we do need the database to test link updates
 */
class CssContentTest extends TextContentTest {

	protected function setUp(): void {
		parent::setUp();

		$this->overrideConfigValue(
			MainConfigNames::TextModelsToParse,
			[
				CONTENT_MODEL_CSS,
			]
		);
	}

	public function newContent( $text ) {
		return new CssContent( $text );
	}

	// XXX: currently, preSaveTransform is applied to styles. this may change or become optional.
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
	 * @covers CssContent::getRedirectTarget
	 * @dataProvider provideGetRedirectTarget
	 */
	public function testGetRedirectTarget( $title, $text ) {
		$this->overrideConfigValues( [
			MainConfigNames::Server => '//example.org',
			MainConfigNames::ScriptPath => '/w',
			MainConfigNames::Script => '/w/index.php',
		] );
		$content = new CssContent( $text );
		$target = $content->getRedirectTarget();
		$this->assertEquals( $title, $target ? $target->getPrefixedText() : null );
	}

	/**
	 * Keep this in sync with CssContentHandlerTest::provideMakeRedirectContent()
	 */
	public static function provideGetRedirectTarget() {
		return [
			[ 'MediaWiki:MonoBook.css', "/* #REDIRECT */@import url(//example.org/w/index.php?title=MediaWiki:MonoBook.css&action=raw&ctype=text/css);" ],
			[ 'User:FooBar/common.css', "/* #REDIRECT */@import url(//example.org/w/index.php?title=User:FooBar/common.css&action=raw&ctype=text/css);" ],
			[ 'Gadget:FooBaz.css', "/* #REDIRECT */@import url(//example.org/w/index.php?title=Gadget:FooBaz.css&action=raw&ctype=text/css);" ],
			[
				'User:😂/unicode.css',
				'/* #REDIRECT */@import url(//example.org/w/index.php?title=User:%F0%9F%98%82/unicode.css&action=raw&ctype=text/css);'
			],
			# No #REDIRECT comment
			[ null, "@import url(//example.org/w/index.php?title=Gadget:FooBaz.css&action=raw&ctype=text/css);" ],
			# Wrong domain
			[ null, "/* #REDIRECT */@import url(//example.com/w/index.php?title=Gadget:FooBaz.css&action=raw&ctype=text/css);" ],
		];
		// phpcs:enable
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
