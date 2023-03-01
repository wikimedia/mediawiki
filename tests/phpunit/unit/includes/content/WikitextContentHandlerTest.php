<?php

namespace MediaWiki\Tests\Unit;

use MediaWiki\Languages\LanguageNameUtils;
use MediaWiki\Parser\MagicWordFactory;
use MediaWiki\Revision\SlotRecord;
use MediaWiki\Revision\SlotRenderingProvider;
use MediaWiki\Title\Title;
use MediaWiki\Title\TitleFactory;
use MediaWikiUnitTestCase;
use MWException;
use ParserFactory;
use Wikimedia\UUID\GlobalIdGenerator;
use WikitextContent;
use WikitextContentHandler;

/**
 * Split from \WikitextContentHandlerTest integration tests
 *
 * @group ContentHandler
 * @coversDefaultClass \WikitextContentHandler
 */
class WikitextContentHandlerTest extends MediaWikiUnitTestCase {

	private function newWikitextContentHandler(): WikitextContentHandler {
		return new WikitextContentHandler(
			CONTENT_MODEL_WIKITEXT,
			$this->createMock( TitleFactory::class ),
			$this->createMock( ParserFactory::class ),
			$this->createMock( GlobalIdGenerator::class ),
			$this->createMock( LanguageNameUtils::class ),
			$this->createMock( MagicWordFactory::class )
		);
	}

	/**
	 * @covers ::serializeContent
	 */
	public function testSerializeContent() {
		$content = new WikitextContent( 'hello world' );
		$handler = $this->newWikitextContentHandler();

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
		$handler = $this->newWikitextContentHandler();

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
		$handler = $this->newWikitextContentHandler();
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
		$handler = $this->newWikitextContentHandler();
		$this->assertEquals( $supported, $handler->isSupportedFormat( $format ) );
	}

	/**
	 * @covers ::supportsDirectEditing
	 */
	public function testSupportsDirectEditing() {
		$handler = $this->newWikiTextContentHandler();
		$this->assertTrue( $handler->supportsDirectEditing(), 'direct editing is supported' );
	}

	/**
	 * @covers ::getSecondaryDataUpdates
	 */
	public function testGetSecondaryDataUpdates() {
		$title = $this->createMock( Title::class );
		$content = new WikitextContent( '' );
		$srp = $this->createMock( SlotRenderingProvider::class );
		$handler = $this->newWikitextContentHandler();

		$updates = $handler->getSecondaryDataUpdates( $title, $content, SlotRecord::MAIN, $srp );

		$this->assertEquals( [], $updates );
	}

	/**
	 * @covers ::getDeletionUpdates
	 */
	public function testGetDeletionUpdates() {
		$title = $this->createMock( Title::class );
		$handler = $this->newWikitextContentHandler();

		$updates = $handler->getDeletionUpdates( $title, SlotRecord::MAIN );
		$this->assertEquals( [], $updates );
	}

}
