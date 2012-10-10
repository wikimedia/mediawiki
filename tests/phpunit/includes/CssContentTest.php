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
			array("MediaWiki:Test.css", null, "hello <world>\n",
				"<pre class=\"mw-code mw-css\" dir=\"ltr\">\nhello &lt;world&gt;\n\n</pre>"),
			// @todo: more...?
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
