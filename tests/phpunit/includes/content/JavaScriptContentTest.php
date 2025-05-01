<?php

use MediaWiki\Content\CssContent;
use MediaWiki\Content\JavaScriptContent;
use MediaWiki\MainConfigNames;
use MediaWiki\Title\Title;

/**
 * Needs database to do link updates.
 *
 * @group ContentHandler
 * @group Database
 * @covers \MediaWiki\Content\JavaScriptContent
 */
class JavaScriptContentTest extends TextContentTest {

	public function newContent( $text ) {
		return new JavaScriptContent( $text );
	}

	public function testAddSectionHeader() {
		$content = $this->newContent( 'hello world' );
		$c = $content->addSectionHeader( 'test' );

		$this->assertTrue( $content->equals( $c ) );
	}

	// XXX: currently, preSaveTransform is applied to scripts. this may change or become optional.
	public static function providePreSaveTransform() {
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

	public static function provideGetRedirectTargetTextContent() {
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

	public static function provideIsCountable() {
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
				'link',
				false
			],
		];
	}

	public static function provideGetTextForSummary() {
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

	public function testMatchMagicWord() {
		$mw = $this->getServiceContainer()->getMagicWordFactory()->get( "staticredirect" );

		$content = $this->newContent( "#REDIRECT [[FOO]]\n__STATICREDIRECT__" );
		$this->assertFalse(
			$content->matchMagicWord( $mw ),
			"should not have matched magic word, since it's not wikitext"
		);
	}

	/**
	 * @dataProvider provideUpdateRedirect
	 */
	public function testUpdateRedirect( $oldText, $expectedText ) {
		$this->overrideConfigValues( [
			MainConfigNames::Server => '//example.org',
			MainConfigNames::ScriptPath => '/w',
			MainConfigNames::Script => '/w/index.php',
			MainConfigNames::ResourceBasePath => '/w',
		] );
		$target = Title::makeTitle( NS_MAIN, 'TestUpdateRedirect_target' );

		$content = new JavaScriptContent( $oldText );
		$newContent = $content->updateRedirect( $target );

		$this->assertEquals( $expectedText, $newContent->getText() );
	}

	public static function provideUpdateRedirect() {
		return [
			[
				'#REDIRECT [[Someplace]]',
				'#REDIRECT [[Someplace]]',
			],
			[
				'/* #REDIRECT */mw.loader.load("//example.org/w/index.php?title=MediaWiki:MonoBook.js&action=raw&ctype=text/javascript");',
				'/* #REDIRECT */mw.loader.load("//example.org/w/index.php?title=TestUpdateRedirect_target&action=raw&ctype=text/javascript");'
			]
		];
	}

	public function testGetModel() {
		$content = $this->newContent( "hello world." );

		$this->assertEquals( CONTENT_MODEL_JAVASCRIPT, $content->getModel() );
	}

	public function testGetContentHandler() {
		$content = $this->newContent( "hello world." );

		$this->assertEquals( CONTENT_MODEL_JAVASCRIPT, $content->getContentHandler()->getModelID() );
	}

	// NOTE: Overridden by subclass!
	public static function provideEquals() {
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
		$this->overrideConfigValues( [
			MainConfigNames::Server => '//example.org',
			MainConfigNames::ScriptPath => '/w',
			MainConfigNames::Script => '/w/index.php',
			MainConfigNames::ResourceBasePath => '/w',
		] );
		$content = new JavaScriptContent( $text );
		$target = $content->getRedirectTarget();
		$this->assertEquals( $title, $target ? $target->getPrefixedText() : null );
	}

	public static function provideGetRedirectTarget() {
		// Roundtrip testing
		yield from JavaScriptContentHandlerTest::provideMakeRedirectContent();

		// Additional cases that don't roundtrip (errors, and back-compat)
		yield 'Missing #REDIRECT comment' => [
			null,
			'mw.loader.load("//example.org/w/index.php?title=MediaWiki:NoRedirect.js&action=raw&ctype=text/javascript");'
		];
		yield 'Different domain' => [
			null,
			'/* #REDIRECT */mw.loader.load("//example.com/w/index.php?title=MediaWiki:OtherWiki.js&action=raw&ctype=text/javascript");'
		];
		yield 'Encoding before MW 1.42 (T107289)' => [
			// \u0026 instead of literal &
			'MediaWiki:MonoBook.js',
			'/* #REDIRECT */mw.loader.load("//example.org/w/index.php?title=MediaWiki:MonoBook.js\u0026action=raw\u0026ctype=text/javascript");'
		];
	}
}
