<?php

class CssContentTest extends JavascriptContentTest {

	public function newContent( $text ) {
		return new CssContent( $text );
	}


	public function dataGetParserOutput() {
		return array(
			array("hello <world>\n", "<pre class=\"mw-code mw-css\" dir=\"ltr\">\nhello &lt;world&gt;\n\n</pre>\n"),
			// @todo: more...?
		);
	}


	# =================================================================================================================

	public function getModelName() {
		$content = $this->newContent( "hello world." );

		$this->assertEquals( CONTENT_MODEL_JAVASCRIPT, $content->getModelName() );
	}

	public function getContentHandler() {
		$content = $this->newContent( "hello world." );

		$this->assertEquals( CONTENT_MODEL_JAVASCRIPT, $content->getContentHandler()->getModelName() );
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
