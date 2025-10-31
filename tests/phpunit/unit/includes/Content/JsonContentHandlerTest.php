<?php

use MediaWiki\Content\JsonContent;
use MediaWiki\Content\JsonContentHandler;
use MediaWiki\Parser\Parsoid\ParsoidParserFactory;
use MediaWiki\Title\TitleFactory;

/**
 * @covers \MediaWiki\Content\JsonContentHandler
 */
class JsonContentHandlerTest extends \MediaWikiUnitTestCase {

	public function testMakeEmptyContent() {
		$handler = new JsonContentHandler(
			CONTENT_MODEL_JSON,
			$this->createMock( ParsoidParserFactory::class ),
			$this->createMock( TitleFactory::class )
		);
		$content = $handler->makeEmptyContent();
		$this->assertInstanceOf( JsonContent::class, $content );
		$this->assertTrue( $content->isValid() );
	}
}
