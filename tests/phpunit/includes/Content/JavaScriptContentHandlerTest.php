<?php

namespace MediaWiki\Tests\Content;

use MediaWiki\Content\JavaScriptContent;
use MediaWiki\MainConfigNames;
use MediaWiki\Title\Title;
use MediaWikiLangTestCase;

/**
 * @covers \MediaWiki\Content\JavaScriptContentHandler
 */
class JavaScriptContentHandlerTest extends MediaWikiLangTestCase {

	/**
	 * @dataProvider provideMakeRedirectContent
	 */
	public function testMakeRedirectContent( $title, $expected ) {
		$this->overrideConfigValues( [
			MainConfigNames::Server => '//example.org',
			MainConfigNames::Script => '/w/index.php',
		] );
		$ch = $this->getServiceContainer()->getContentHandlerFactory()
			->getContentHandler( CONTENT_MODEL_JAVASCRIPT );
		$content = $ch->makeRedirectContent( Title::newFromText( $title ) );
		$this->assertInstanceOf( JavaScriptContent::class, $content );
		$this->assertEquals( $expected, $content->serialize( CONTENT_FORMAT_JAVASCRIPT ) );
	}

	/**
	 * This is re-used by JavaScriptContentTest to assert roundtrip
	 */
	public static function provideMakeRedirectContent() {
		return [
			'MediaWiki namespace page' => [
				'MediaWiki:MonoBook.js',
				'/* #REDIRECT */mw.loader.load("//example.org/w/index.php?title=MediaWiki:MonoBook.js&action=raw&ctype=text/javascript");'
			],
			'User subpage' => [
				'User:FooBar/common.js',
				'/* #REDIRECT */mw.loader.load("//example.org/w/index.php?title=User:FooBar/common.js&action=raw&ctype=text/javascript");'
			],
			'Gadget page' => [
				'Gadget:FooBaz.js',
				'/* #REDIRECT */mw.loader.load("//example.org/w/index.php?title=Gadget:FooBaz.js&action=raw&ctype=text/javascript");'
			],
			'Unicode basename' => [
				'User:😂/unicode.js',
				'/* #REDIRECT */mw.loader.load("//example.org/w/index.php?title=User:%F0%9F%98%82/unicode.js&action=raw&ctype=text/javascript");'
			],
			'Ampersand basename' => [
				'User:Penn & Teller/ampersand.js',
				'/* #REDIRECT */mw.loader.load("//example.org/w/index.php?title=User:Penn_%26_Teller/ampersand.js&action=raw&ctype=text/javascript");'
			],
		];
	}
}
