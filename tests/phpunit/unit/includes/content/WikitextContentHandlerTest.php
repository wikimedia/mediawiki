<?php

namespace MediaWiki\Tests\Unit;

use MediaWiki\Content\Renderer\ContentParseParams;
use MediaWiki\Languages\LanguageNameUtils;
use MediaWiki\Parser\MagicWordFactory;
use MediaWiki\Parser\Parsoid\ParsoidParser;
use MediaWiki\Parser\Parsoid\ParsoidParserFactory;
use MediaWiki\Revision\SlotRecord;
use MediaWiki\Revision\SlotRenderingProvider;
use MediaWiki\Title\Title;
use MediaWiki\Title\TitleFactory;
use MediaWikiUnitTestCase;
use MWException;
use Parser;
use ParserFactory;
use ParserOptions;
use ParserOutput;
use ReflectionClass;
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

	private function newWikitextContentHandler( $overrides = [] ): WikitextContentHandler {
		return new WikitextContentHandler(
			CONTENT_MODEL_WIKITEXT,
			$overrides[TitleFactory::class] ?? $this->createMock( TitleFactory::class ),
			$overrides[ParserFactory::class] ?? $this->createMock( ParserFactory::class ),
			$overrides[GlobalIdGenerator::class] ?? $this->createMock( GlobalIdGenerator::class ),
			$overrides[LanguageNameUtils::class] ?? $this->createMock( LanguageNameUtils::class ),
			$overrides[MagicWordFactory::class] ?? $this->createMock( MagicWordFactory::class ),
			$overrides[ParsoidParserFactory::class] ?? $this->createMock( ParsoidParserFactory::class )
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

	/**
	 * @covers ::fillParserOutput
	 * @dataProvider provideFillParserOutput
	 */
	public function testFillParserOutput( $useParsoid = true ) {
		$parserOptions = $this->createMock( ParserOptions::class );
		$parserOptions
			->method( 'getUseParsoid' )
			->willReturn( $useParsoid );

		// This is the core of the test: if the useParsoid option is NOT
		// present, we expect ParserFactory->getInstance()->parse()
		// to be called exactly once, otherwise never.
		$parser = $this->createMock( Parser::class );
		$parser
			->expects( $useParsoid ? $this->never() : $this->once() )
			->method( 'parse' );
		$parserFactory = $this->createMock( ParserFactory::class );
		$parserFactory
			->expects( $useParsoid ? $this->never() : $this->once() )
			->method( 'getInstance' )
			->willReturn( $parser );

		// If the useParsoid option is present, we expect
		// ParsoidParserFactory()->create()->parse() to be called
		// exactly once, otherwise never.
		$parsoidParser = $this->createMock( ParsoidParser::class );
		$parsoidParser
			->expects( $useParsoid ? $this->once() : $this->never() )
			->method( 'parse' );
		$parsoidParserFactory = $this->createMock( ParsoidParserFactory::class );
		$parsoidParserFactory
			->expects( $useParsoid ? $this->once() : $this->never() )
			->method( 'create' )
			->willReturn( $parsoidParser );

		// Set up the rest of the mocks
		$content = $this->createMock( WikitextContent::class );
		$content
			->method( 'getRedirectTargetAndText' )
			->willReturn( [ false, '* Hello, world!' ] );
		$content
			->method( 'getPreSaveTransformFlags' )
			->willReturn( [] );

		$title = $this->createMock( Title::class );
		$titleFactory = $this->createMock( TitleFactory::class );
		$titleFactory
			->method( 'newFromPageReference' )
			->willReturn( $title );

		$cpoParams = new ContentParseParams( $title, 42, $parserOptions );

		$parserOutput = $this->createMock( ParserOutput::class );

		// The method we'd like to test, fillParserOutput, is protected;
		// make it public
		$class = new ReflectionClass( WikitextContentHandler::class );
		$method = $class->getMethod( 'fillParserOutput' );
		$method->setAccessible( true );

		$handler = $this->newWikitextContentHandler( [
			TitleFactory::class => $titleFactory,
			ParserFactory::class => $parserFactory,
			ParsoidParserFactory::class => $parsoidParserFactory,
		] );

		// Okay, invoke fillParserOutput() and verify that the assertions
		// above about the parse() invocations are correct.
		$method->invokeArgs( $handler, [ $content, $cpoParams, &$parserOutput ] );
	}

	public static function provideFillParserOutput() {
		return [ [ false ], [ true ] ];
	}
}
