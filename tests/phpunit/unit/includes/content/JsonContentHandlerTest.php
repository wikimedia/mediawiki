<?php

use MediaWiki\Content\JsonContent;
use MediaWiki\Content\JsonContentHandler;

/**
 * @covers \MediaWiki\Content\JsonContentHandler
 */
class JsonContentHandlerTest extends \MediaWikiUnitTestCase {

	public function testMakeEmptyContent() {
		$handler = new JsonContentHandler();
		$content = $handler->makeEmptyContent();
		$this->assertInstanceOf( JsonContent::class, $content );
		$this->assertTrue( $content->isValid() );
	}
}
