<?php

use MediaWiki\Revision\SlotRecord;
use MediaWiki\Revision\SlotRenderingProvider;

/**
 * @group ContentHandler
 */
class UnknownContentHandlerTest extends MediaWikiLangTestCase {
	/**
	 * @covers UnknownContentHandler::supportsDirectEditing
	 */
	public function testSupportsDirectEditing() {
		$handler = new UnknownContentHandler( 'horkyporky' );
		$this->assertFalse( $handler->supportsDirectEditing(), 'direct editing supported' );
	}

	/**
	 * @covers UnknownContentHandler::serializeContent
	 */
	public function testSerializeContent() {
		$handler = new UnknownContentHandler( 'horkyporky' );
		$content = new UnknownContent( 'hello world', 'horkyporky' );

		$this->assertEquals( 'hello world', $handler->serializeContent( $content ) );
		$this->assertEquals(
			'hello world',
			$handler->serializeContent( $content, 'application/horkyporky' )
		);
	}

	/**
	 * @covers UnknownContentHandler::unserializeContent
	 */
	public function testUnserializeContent() {
		$handler = new UnknownContentHandler( 'horkyporky' );
		$content = $handler->unserializeContent( 'hello world' );
		$this->assertEquals( 'hello world', $content->getData() );

		$content = $handler->unserializeContent( 'hello world', 'application/horkyporky' );
		$this->assertEquals( 'hello world', $content->getData() );
	}

	/**
	 * @covers UnknownContentHandler::makeEmptyContent
	 */
	public function testMakeEmptyContent() {
		$handler = new UnknownContentHandler( 'horkyporky' );
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
	 * @covers UnknownContentHandler::isSupportedFormat
	 */
	public function testIsSupportedFormat( $format, $supported ) {
		$handler = new UnknownContentHandler( 'horkyporky' );
		$this->assertEquals( $supported, $handler->isSupportedFormat( $format ) );
	}

	/**
	 * @covers ContentHandler::getSecondaryDataUpdates
	 */
	public function testGetSecondaryDataUpdates() {
		$title = Title::newFromText( 'Somefile.jpg', NS_FILE );
		$content = new UnknownContent( '', 'horkyporky' );

		/** @var SlotRenderingProvider $srp */
		$srp = $this->getMock( SlotRenderingProvider::class );

		$handler = new UnknownContentHandler( 'horkyporky' );
		$updates = $handler->getSecondaryDataUpdates( $title, $content, SlotRecord::MAIN, $srp );

		$this->assertEquals( [], $updates );
	}

	/**
	 * @covers ContentHandler::getDeletionUpdates
	 */
	public function testGetDeletionUpdates() {
		$title = Title::newFromText( 'Somefile.jpg', NS_FILE );

		$handler = new UnknownContentHandler( 'horkyporky' );
		$updates = $handler->getDeletionUpdates( $title, SlotRecord::MAIN );

		$this->assertEquals( [], $updates );
	}

	/**
	 * @covers ContentHandler::getDeletionUpdates
	 */
	public function testGetSlotDiffRenderer() {
		$context = new RequestContext();
		$context->setRequest( new FauxRequest() );

		$handler = new UnknownContentHandler( 'horkyporky' );
		$slotDiffRenderer = $handler->getSlotDiffRenderer( $context );

		$oldContent = $handler->unserializeContent( 'Foo' );
		$newContent = $handler->unserializeContent( 'Foo bar' );

		$diff = $slotDiffRenderer->getDiff( $oldContent, $newContent );
		$this->assertNotEmpty( $diff );
	}

}
