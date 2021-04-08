<?php

/**
 * See also unit tests at \MediaWiki\Tests\Unit\FallbackContentHandlerTest
 *
 * @group ContentHandler
 */
class FallbackContentHandlerTest extends MediaWikiLangTestCase {

	/**
	 * @covers ContentHandler::getSlotDiffRenderer
	 */
	public function testGetSlotDiffRenderer() {
		$context = new RequestContext();
		$context->setRequest( new FauxRequest() );

		$handler = new FallbackContentHandler( 'horkyporky' );
		$slotDiffRenderer = $handler->getSlotDiffRenderer( $context );

		$oldContent = $handler->unserializeContent( 'Foo' );
		$newContent = $handler->unserializeContent( 'Foo bar' );

		$diff = $slotDiffRenderer->getDiff( $oldContent, $newContent );
		$this->assertNotEmpty( $diff );
	}

}
