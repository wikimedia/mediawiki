<?php

/**
 * @group ContentHandler
 * @group Database
 *        ^--- needed, because we do need the database to test link updates
 */
class CssContentTest extends JavaScriptContentTest {

	protected function setUp() {
		parent::setUp();

		// Anon user
		$user = new User();
		$user->setName( '127.0.0.1' );

		$this->setMwGlobals( array(
			'wgUser' => $user,
			'wgTextModelsToParse' => array(
				CONTENT_MODEL_CSS,
			)
		) );
	}

	public function newContent( $text ) {
		return new CssContent( $text );
	}

	public static function dataGetParserOutput() {
		return array(
			array(
				'MediaWiki:Test.css',
				null,
				"hello <world>\n",
				"<pre class=\"mw-code mw-css\" dir=\"ltr\">\nhello &lt;world&gt;\n\n</pre>"
			),
			array(
				'MediaWiki:Test.css',
				null,
				"/* hello [[world]] */\n",
				"<pre class=\"mw-code mw-css\" dir=\"ltr\">\n/* hello [[world]] */\n\n</pre>",
				array(
					'Links' => array(
						array( 'World' => 0 )
					)
				)
			),

			// TODO: more...?
		);
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

	public static function dataEquals() {
		return array(
			array( new CssContent( 'hallo' ), null, false ),
			array( new CssContent( 'hallo' ), new CssContent( 'hallo' ), true ),
			array( new CssContent( 'hallo' ), new WikitextContent( 'hallo' ), false ),
			array( new CssContent( 'hallo' ), new CssContent( 'HALLO' ), false ),
		);
	}

	/**
	 * @dataProvider dataEquals
	 * @covers CssContent::equals
	 */
	public function testEquals( Content $a, Content $b = null, $equal = false ) {
		$this->assertEquals( $equal, $a->equals( $b ) );
	}
}
