<?php

use MediaWiki\MainConfigNames;

class JavaScriptContentHandlerTest extends MediaWikiLangTestCase {

	/**
	 * @dataProvider provideMakeRedirectContent
	 * @covers JavaScriptContentHandler::makeRedirectContent
	 */
	public function testMakeRedirectContent( $title, $expected ) {
		$this->overrideConfigValues( [
			MainConfigNames::Server => '//example.org',
			MainConfigNames::Script => '/w/index.php',
		] );
		$ch = new JavaScriptContentHandler();
		$content = $ch->makeRedirectContent( Title::newFromText( $title ) );
		$this->assertInstanceOf( JavaScriptContent::class, $content );
		$this->assertEquals( $expected, $content->serialize( CONTENT_FORMAT_JAVASCRIPT ) );
	}

	/**
	 * Keep this in sync with JavaScriptContentTest::provideGetRedirectTarget()
	 */
	public static function provideMakeRedirectContent() {
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
			[
				'User:😂/unicode.js',
				'/* #REDIRECT */mw.loader.load("//example.org/w/index.php?title=User:%F0%9F%98%82/unicode.js\u0026action=raw\u0026ctype=text/javascript");'
			],
		];
		// phpcs:enable
	}
}
