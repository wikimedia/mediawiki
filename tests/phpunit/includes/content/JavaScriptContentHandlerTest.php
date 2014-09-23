<?php

class JavaScriptContentHandlerTest extends MediaWikiTestCase {

	/**
	 * @dataProvider provideMakeRedirectContent
	 * @covers JavaScriptContentHandler::makeRedirectContent
	 */
	public function testMakeRedirectContent( $title, $expected ) {
		$this->setMwGlobals( array(
			'wgServer' => '//example.org',
			'wgScriptPath' => '/w',
		) );
		$ch = new JavaScriptContentHandler();
		$content = $ch->makeRedirectContent( Title::newFromText( $title ) );
		$this->assertInstanceOf( 'JavaScriptContent', $content );
		$this->assertEquals( $expected, $content->serialize( CONTENT_FORMAT_JAVASCRIPT ) );
	}

	public static function provideMakeRedirectContent() {
		return array(
			array( 'MediaWiki:MonoBook.js', 'mw.loader.load("//example.org/w/index.php?title=MediaWiki:MonoBook.js\u0026action=raw\u0026ctype=text%2Fjavascript");' ),
			array( 'User:FooBar/common.js', 'mw.loader.load("//example.org/w/index.php?title=User:FooBar/common.js\u0026action=raw\u0026ctype=text%2Fjavascript");' ),
			array( 'Gadget:FooBaz.js', 'mw.loader.load("//example.org/w/index.php?title=Gadget:FooBaz.js\u0026action=raw\u0026ctype=text%2Fjavascript");' ),
		);
	}
}
