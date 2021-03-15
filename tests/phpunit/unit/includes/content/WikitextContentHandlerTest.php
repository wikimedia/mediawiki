<?php

namespace MediaWiki\Tests\Unit;

use MediaWiki\Revision\SlotRecord;
use MediaWiki\Revision\SlotRenderingProvider;
use MediaWikiUnitTestCase;
use MWException;
use Title;
use WikitextContent;
use WikitextContentHandler;

/**
 * Split from \WikitextContentHandlerTest integration tests
 *
 * @group ContentHandler
 * @coversDefaultClass \WikitextContentHandler
 */
class WikitextContentHandlerTest extends MediaWikiUnitTestCase {

	/**
	 * @covers ::serializeContent
	 */
	public function testSerializeContent() {
		$content = new WikitextContent( 'hello world' );
		$handler = new WikitextContentHandler();

		$this->assertEquals( 'hello world', $handler->serializeContent( $content ) );
		$this->assertEquals(
			'hello world',
			$handler->serializeContent( $content, CONTENT_FORMAT_WIKITEXT )
		);

		$this->expectException( MWException::class );
		$handler->serializeContent( $content, 'dummy/foo' );
	}

	/**
	 * @covers ::unserializeContent
	 */
	public function testUnserializeContent() {
		$handler = new WikitextContentHandler();

		$content = $handler->unserializeContent( 'hello world' );
		$this->assertEquals( 'hello world', $content->getText() );

		$content = $handler->unserializeContent( 'hello world', CONTENT_FORMAT_WIKITEXT );
		$this->assertEquals( 'hello world', $content->getText() );

		$this->expectException( MWException::class );
		$handler->unserializeContent( 'hello world', 'dummy/foo' );
	}

	/**
	 * @covers WikitextContentHandler::makeEmptyContent
	 */
	public function testMakeEmptyContent() {
		$handler = new WikitextContentHandler();
		$content = $handler->makeEmptyContent();

		$this->assertTrue( $content->isEmpty() );
		$this->assertSame( '', $content->getText() );
	}

	public function dataIsSupportedFormat() {
		return [
			[ null, true ],
			[ CONTENT_FORMAT_WIKITEXT, true ],
			[ 99887766, false ],
		];
	}

	/**
	 * @dataProvider dataIsSupportedFormat
	 * @covers ::isSupportedFormat
	 */
	public function testIsSupportedFormat( $format, $supported ) {
		$handler = new WikitextContentHandler();
		$this->assertEquals( $supported, $handler->isSupportedFormat( $format ) );
	}

	/**
	 * @covers ::supportsDirectEditing
	 */
	public function testSupportsDirectEditing() {
		$handler = new WikiTextContentHandler();
		$this->assertTrue( $handler->supportsDirectEditing(), 'direct editing is supported' );
	}

	/**
	 * @covers ::getSecondaryDataUpdates
	 */
	public function testGetSecondaryDataUpdates() {
		$title = $this->createMock( Title::class );
		$content = new WikitextContent( '' );
		$srp = $this->createMock( SlotRenderingProvider::class );
		$handler = new WikitextContentHandler();

		$updates = $handler->getSecondaryDataUpdates( $title, $content, SlotRecord::MAIN, $srp );

		$this->assertEquals( [], $updates );
	}

	/**
	 * @covers ::getDeletionUpdates
	 */
	public function testGetDeletionUpdates() {
		$title = $this->createMock( Title::class );
		$handler = new WikitextContentHandler();

		$updates = $handler->getDeletionUpdates( $title, SlotRecord::MAIN );
		$this->assertEquals( [], $updates );
	}

}
