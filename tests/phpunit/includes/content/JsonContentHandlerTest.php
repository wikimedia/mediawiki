<?php

class JsonContentHandlerTest extends MediaWikiTestCase {

	/**
	 * @covers JsonContentHandler::makeEmptyContent
	 */
	public function testMakeEmptyContent() {
		$handler = new JsonContentHandler();
		$content = $handler->makeEmptyContent();
		$this->assertInstanceOf( JsonContent::class, $content );
		$this->assertTrue( $content->isValid() );
	}
}
