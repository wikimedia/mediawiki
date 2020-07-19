<?php

use MediaWiki\Revision\SlotRecord;
use MediaWiki\Revision\SlotRenderingProvider;

/**
 * @group ContentHandler
 */
class FallbackContentHandlerTest extends MediaWikiLangTestCase {
	/**
	 * @covers FallbackContentHandler::supportsDirectEditing
	 */
	public function testSupportsDirectEditing() {
		$handler = new FallbackContentHandler( 'horkyporky' );
		$this->assertFalse( $handler->supportsDirectEditing(), 'direct editing supported' );
	}

	/**
	 * @covers FallbackContentHandler::serializeContent
	 */
	public function testSerializeContent() {
		$handler = new FallbackContentHandler( 'horkyporky' );
		$content = new FallbackContent( 'hello world', 'horkyporky' );

		$this->assertEquals( 'hello world', $handler->serializeContent( $content ) );
		$this->assertEquals(
			'hello world',
			$handler->serializeContent( $content, 'application/horkyporky' )
		);
	}

	/**
	 * @covers FallbackContentHandler::unserializeContent
	 */
	public function testUnserializeContent() {
		$handler = new FallbackContentHandler( 'horkyporky' );
		$content = $handler->unserializeContent( 'hello world' );
		$this->assertEquals( 'hello world', $content->getData() );

		$content = $handler->unserializeContent( 'hello world', 'application/horkyporky' );
		$this->assertEquals( 'hello world', $content->getData() );
	}

	/**
	 * @covers FallbackContentHandler::makeEmptyContent
	 */
	public function testMakeEmptyContent() {
		$handler = new FallbackContentHandler( 'horkyporky' );
		$content = $handler->makeEmptyContent();

		$this->assertTrue( $content->isEmpty() );
		$this->assertSame( '', $content->getData() );
	}

	public static function dataIsSupportedFormat() {
		return [
			[ null, true ],
			[ 'application/octet-stream', true ],
			[ 'unknown/unknown', true ],
			[ 'text/plain', false ],
			[ 99887766, false ],
		];
	}

	/**
	 * @dataProvider dataIsSupportedFormat
	 * @covers FallbackContentHandler::isSupportedFormat
	 */
	public function testIsSupportedFormat( $format, $supported ) {
		$handler = new FallbackContentHandler( 'horkyporky' );
		$this->assertEquals( $supported, $handler->isSupportedFormat( $format ) );
	}

	/**
	 * @covers ContentHandler::getSecondaryDataUpdates
	 */
	public function testGetSecondaryDataUpdates() {
		$title = Title::newFromText( 'Somefile.jpg', NS_FILE );
		$content = new FallbackContent( '', 'horkyporky' );

		/** @var SlotRenderingProvider $srp */
		$srp = $this->createMock( SlotRenderingProvider::class );

		$handler = new FallbackContentHandler( 'horkyporky' );
		$updates = $handler->getSecondaryDataUpdates( $title, $content, SlotRecord::MAIN, $srp );

		$this->assertEquals( [], $updates );
	}

	/**
	 * @covers ContentHandler::getDeletionUpdates
	 */
	public function testGetDeletionUpdates() {
		$title = Title::newFromText( 'Somefile.jpg', NS_FILE );

		$handler = new FallbackContentHandler( 'horkyporky' );
		$updates = $handler->getDeletionUpdates( $title, SlotRecord::MAIN );

		$this->assertEquals( [], $updates );
	}

	/**
	 * @covers ContentHandler::getDeletionUpdates
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
