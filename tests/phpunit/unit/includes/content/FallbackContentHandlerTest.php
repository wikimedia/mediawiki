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
 * @coversDefaultClass \MediaWiki\Content\FallbackContentHandler
 */
class FallbackContentHandlerTest extends MediaWikiUnitTestCase {
	/**
	 * @covers ::supportsDirectEditing
	 */
	public function testSupportsDirectEditing() {
		$handler = new FallbackContentHandler( 'horkyporky' );
		$this->assertFalse( $handler->supportsDirectEditing(), 'direct editing supported' );
	}

	/**
	 * @covers ::serializeContent
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
	 * @covers ::unserializeContent
	 */
	public function testUnserializeContent() {
		$handler = new FallbackContentHandler( 'horkyporky' );
		$content = $handler->unserializeContent( 'hello world' );
		$this->assertEquals( 'hello world', $content->getData() );

		$content = $handler->unserializeContent( 'hello world', 'application/horkyporky' );
		$this->assertEquals( 'hello world', $content->getData() );
	}

	/**
	 * @covers ::makeEmptyContent
	 */
	public function testMakeEmptyContent() {
		$handler = new FallbackContentHandler( 'horkyporky' );
		$content = $handler->makeEmptyContent();

		$this->assertTrue( $content->isEmpty() );
		$this->assertSame( '', $content->getData() );
	}

	public function dataIsSupportedFormat() {
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
	 * @covers ::isSupportedFormat
	 */
	public function testIsSupportedFormat( $format, $supported ) {
		$handler = new FallbackContentHandler( 'horkyporky' );
		$this->assertEquals( $supported, $handler->isSupportedFormat( $format ) );
	}

	/**
	 * @covers ::getSecondaryDataUpdates
	 */
	public function testGetSecondaryDataUpdates() {
		$title = $this->createMock( Title::class );
		$content = new FallbackContent( '', 'horkyporky' );
		$srp = $this->createMock( SlotRenderingProvider::class );
		$handler = new FallbackContentHandler( 'horkyporky' );

		$updates = $handler->getSecondaryDataUpdates( $title, $content, SlotRecord::MAIN, $srp );
		$this->assertEquals( [], $updates );
	}

	/**
	 * @covers ::getDeletionUpdates
	 */
	public function testGetDeletionUpdates() {
		$title = $this->createMock( Title::class );
		$handler = new FallbackContentHandler( 'horkyporky' );

		$updates = $handler->getDeletionUpdates( $title, SlotRecord::MAIN );
		$this->assertEquals( [], $updates );
	}

}
