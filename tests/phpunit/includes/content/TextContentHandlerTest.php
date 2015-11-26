<?php

/**
 * @group ContentHandler
 */
class TextContentHandlerTest extends MediaWikiLangTestCase {
	public function testSupportsDirectEditing() {
		$handler = new TextContentHandler();
		$this->assertTrue( $handler->supportsDirectEditing(), 'direct editing is supported' );
	}

}
