<?php

class JavaScriptContentHandlerTest extends MediaWikiTestCase {

	/**
	 * @dataProvider provideMakeRedirectContent
	 * @covers JavaScriptContentHandler::makeRedirectContent
	 */
	public function testMakeRedirectContent( $title, $expected ) {
		$this->setMwGlobals( array(
			'wgServer' => '//example.org',
			'wgScript' => '/w/index.php',
		) );
		$ch = new JavaScriptContentHandler();
		$content = $ch->makeRedirectContent( Title::newFromText( $title ) );
		$this->assertInstanceOf( 'JavaScriptContent', $content );
		$this->assertEquals( $expected, $content->serialize( CONTENT_FORMAT_JAVASCRIPT ) );
	}

	/**
	 * Keep this in sync with JavaScriptContentTest::provideGetRedirectTarget()
	 */
	public static function provideMakeRedirectContent() {
		return array(
			array( 'MediaWiki:MonoBook.js', '/* #REDIRECT */mw.loader.load("//example.org/w/index.php?title=MediaWiki:MonoBook.js\u0026action=raw\u0026ctype=text/javascript");' ),
			array( 'User:FooBar/common.js', '/* #REDIRECT */mw.loader.load("//example.org/w/index.php?title=User:FooBar/common.js\u0026action=raw\u0026ctype=text/javascript");' ),
			array( 'Gadget:FooBaz.js', '/* #REDIRECT */mw.loader.load("//example.org/w/index.php?title=Gadget:FooBaz.js\u0026action=raw\u0026ctype=text/javascript");' ),
		);
	}
}
