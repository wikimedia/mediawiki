<?php

use MediaWiki\Content\JavaScriptContent;
use MediaWiki\Content\JavaScriptContentHandler;
use MediaWiki\MainConfigNames;
use MediaWiki\Title\Title;

class JavaScriptContentHandlerTest extends MediaWikiLangTestCase {

	/**
	 * @dataProvider provideMakeRedirectContent
	 * @covers \MediaWiki\Content\JavaScriptContentHandler::makeRedirectContent
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
				'/* #REDIRECT */mw.loader.load("//example.org/w/index.php?title=MediaWiki:MonoBook.js&action=raw&ctype=text/javascript");'
			],
			[
				'User:FooBar/common.js',
				'/* #REDIRECT */mw.loader.load("//example.org/w/index.php?title=User:FooBar/common.js&action=raw&ctype=text/javascript");'
			],
			[
				'Gadget:FooBaz.js',
				'/* #REDIRECT */mw.loader.load("//example.org/w/index.php?title=Gadget:FooBaz.js&action=raw&ctype=text/javascript");'
			],
			[
				'User:😂/unicode.js',
				'/* #REDIRECT */mw.loader.load("//example.org/w/index.php?title=User:%F0%9F%98%82/unicode.js&action=raw&ctype=text/javascript");'
			],
			[
				'User:A&B/ampersand.js',
				'/* #REDIRECT */mw.loader.load("//example.org/w/index.php?title=User:A%26B/ampersand.js&action=raw&ctype=text/javascript");'
			],
		];
		// phpcs:enable
	}
}
