<?php

class CssContentHandlerTest extends MediaWikiTestCase {

	/**
	 * @dataProvider provideMakeRedirectContent
	 * @covers CssContentHandler::makeRedirectContent
	 */
	public function testMakeRedirectContent( $title, $expected ) {
		$this->setMwGlobals( array(
			'wgServer' => '//example.org',
			'wgScript' => '/w/index.php',
		) );
		$ch = new CssContentHandler();
		$content = $ch->makeRedirectContent( Title::newFromText( $title ) );
		$this->assertInstanceOf( 'CssContent', $content );
		$this->assertEquals( $expected, $content->serialize( CONTENT_FORMAT_CSS ) );
	}

	/**
	 * Keep this in sync with CssContentTest::provideGetRedirectTarget()
	 */
	public static function provideMakeRedirectContent() {
		return array(
			array( 'MediaWiki:MonoBook.css', "/* #REDIRECT */@import url(//example.org/w/index.php?title=MediaWiki:MonoBook.css&action=raw&ctype=text/css);" ),
			array( 'User:FooBar/common.css', "/* #REDIRECT */@import url(//example.org/w/index.php?title=User:FooBar/common.css&action=raw&ctype=text/css);" ),
			array( 'Gadget:FooBaz.css', "/* #REDIRECT */@import url(//example.org/w/index.php?title=Gadget:FooBaz.css&action=raw&ctype=text/css);" ),
		);
	}
}
