<?php

namespace MediaWiki\Tests\Unit;

use MediaWiki\Content\FallbackContent;
use MediaWiki\Content\FallbackContentHandler;
use MediaWiki\Revision\SlotRecord;
use MediaWiki\Revision\SlotRenderingProvider;
use MediaWiki\Title\Title;
use MediaWikiUnitTestCase;

/**
 * Split from \FallbackContentHandlerTest integration tests
 *
 * @group ContentHandler
 * @covers \MediaWiki\Content\FallbackContentHandler
 */
class FallbackContentHandlerTest extends MediaWikiUnitTestCase {
	public function testSupportsDirectEditing() {
		$handler = new FallbackContentHandler( 'horkyporky' );
		$this->assertFalse( $handler->supportsDirectEditing(), 'direct editing supported' );
	}

	public function testSerializeContent() {
		$handler = new FallbackContentHandler( 'horkyporky' );
		$content = new FallbackContent( 'hello world', 'horkyporky' );

		$this->assertEquals( 'hello world', $handler->serializeContent( $content ) );
		$this->assertEquals(
			'hello world',
			$handler->serializeContent( $content, 'application/horkyporky' )
		);
	}

	public function testUnserializeContent() {
		$handler = new FallbackContentHandler( 'horkyporky' );
		$content = $handler->unserializeContent( 'hello world' );
		$this->assertEquals( 'hello world', $content->getData() );

		$content = $handler->unserializeContent( 'hello world', 'application/horkyporky' );
		$this->assertEquals( 'hello world', $content->getData() );
	}

	public function testMakeEmptyContent() {
		$handler = new FallbackContentHandler( 'horkyporky' );
		$content = $handler->makeEmptyContent();

		$this->assertTrue( $content->isEmpty() );
		$this->assertSame( '', $content->getData() );
	}

	public function provideIsSupportedFormat() {
		return [
			[ null, true ],
			[ 'application/octet-stream', true ],
			[ 'unknown/unknown', true ],
			[ 'text/plain', false ],
			[ 99887766, false ],
		];
	}

	/**
	 * @dataProvider provideIsSupportedFormat
	 */
	public function testIsSupportedFormat( $format, $supported ) {
		$handler = new FallbackContentHandler( 'horkyporky' );
		$this->assertEquals( $supported, $handler->isSupportedFormat( $format ) );
	}

	public function testGetSecondaryDataUpdates() {
		$title = $this->createMock( Title::class );
		$content = new FallbackContent( '', 'horkyporky' );
		$srp = $this->createMock( SlotRenderingProvider::class );
		$handler = new FallbackContentHandler( 'horkyporky' );

		$updates = $handler->getSecondaryDataUpdates( $title, $content, SlotRecord::MAIN, $srp );
		$this->assertEquals( [], $updates );
	}

	public function testGetDeletionUpdates() {
		$title = $this->createMock( Title::class );
		$handler = new FallbackContentHandler( 'horkyporky' );

		$updates = $handler->getDeletionUpdates( $title, SlotRecord::MAIN );
		$this->assertEquals( [], $updates );
	}

}
