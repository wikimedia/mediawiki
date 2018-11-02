<?php

class CssContentHandlerTest extends MediaWikiLangTestCase {

	/**
	 * @dataProvider provideMakeRedirectContent
	 * @covers CssContentHandler::makeRedirectContent
	 */
	public function testMakeRedirectContent( $title, $expected ) {
		$this->setMwGlobals( [
			'wgServer' => '//example.org',
			'wgScript' => '/w/index.php',
		] );
		$ch = new CssContentHandler();
		$content = $ch->makeRedirectContent( Title::newFromText( $title ) );
		$this->assertInstanceOf( CssContent::class, $content );
		$this->assertEquals( $expected, $content->serialize( CONTENT_FORMAT_CSS ) );
	}

	/**
	 * Keep this in sync with CssContentTest::provideGetRedirectTarget()
	 */
	public static function provideMakeRedirectContent() {
		// phpcs:disable Generic.Files.LineLength
		return [
			[
				'MediaWiki:MonoBook.css',
				"/* #REDIRECT */@import url(//example.org/w/index.php?title=MediaWiki:MonoBook.css&action=raw&ctype=text/css);"
			],
			[
				'User:FooBar/common.css',
				"/* #REDIRECT */@import url(//example.org/w/index.php?title=User:FooBar/common.css&action=raw&ctype=text/css);"
			],
			[
				'Gadget:FooBaz.css',
				"/* #REDIRECT */@import url(//example.org/w/index.php?title=Gadget:FooBaz.css&action=raw&ctype=text/css);"
			],
			[
				'User:😂/unicode.css',
				'/* #REDIRECT */@import url(//example.org/w/index.php?title=User:%F0%9F%98%82/unicode.css&action=raw&ctype=text/css);'
			],
		];
		// phpcs:enable
	}
}
