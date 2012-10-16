<?php

/**
 * @group ContentHandler
 *
 * @group Database
 *        ^--- needed, because we do need the database to test link updates
 */
class CssContentTest extends JavascriptContentTest {

	public function newContent( $text ) {
		return new CssContent( $text );
	}


	public function dataGetParserOutput() {
		return array(
			array( #0
				"MediaWiki:Test.css", null, "p > a { color:green; }\n",
				"<pre class=\"mw-code mw-css\" dir=\"ltr\">\np &gt; a { color:green; }\n\n</pre>"
			),
			array( #1
				"MediaWiki:Test.css", null, "/* Foo Bar */\n",
				"<pre class=\"mw-code mw-css\" dir=\"ltr\">\n/* Foo Bar */\n\n</pre>"
			),
		);
	}

	# =================================================================================================================

	public function testGetModel() {
		$content = $this->newContent( "hello world." );

		$this->assertEquals( CONTENT_MODEL_CSS, $content->getModel() );
	}

	public function testGetContentHandler() {
		$content = $this->newContent( "hello world." );

		$this->assertEquals( CONTENT_MODEL_CSS, $content->getContentHandler()->getModelID() );
	}

	public function dataEquals( ) {
		return array(
			array( new CssContent( "hallo" ), null, false ),
			array( new CssContent( "hallo" ), new CssContent( "hallo" ), true ),
			array( new CssContent( "hallo" ), new WikitextContent( "hallo" ), false ),
			array( new CssContent( "hallo" ), new CssContent( "HALLO" ), false ),
		);
	}

}
