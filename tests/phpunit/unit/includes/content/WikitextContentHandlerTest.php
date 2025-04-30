<?php

namespace MediaWiki\Tests\Unit;

use MediaWiki\Content\Renderer\ContentParseParams;
use MediaWiki\Content\WikitextContent;
use MediaWiki\Content\WikitextContentHandler;
use MediaWiki\Exception\MWException;
use MediaWiki\Language\Language;
use MediaWiki\Languages\LanguageNameUtils;
use MediaWiki\Linker\LinkRenderer;
use MediaWiki\Parser\MagicWord;
use MediaWiki\Parser\MagicWordFactory;
use MediaWiki\Parser\Parser;
use MediaWiki\Parser\ParserFactory;
use MediaWiki\Parser\ParserOptions;
use MediaWiki\Parser\ParserOutput;
use MediaWiki\Parser\Parsoid\ParsoidParser;
use MediaWiki\Parser\Parsoid\ParsoidParserFactory;
use MediaWiki\Revision\SlotRecord;
use MediaWiki\Revision\SlotRenderingProvider;
use MediaWiki\Title\Title;
use MediaWiki\Title\TitleFactory;
use MediaWikiUnitTestCase;
use MockTitleTrait;
use ReflectionClass;
use Wikimedia\UUID\GlobalIdGenerator;

/**
 * Split from \WikitextContentHandlerTest integration tests
 *
 * @group ContentHandler
 * @covers \MediaWiki\Content\WikitextContentHandler
 */
class WikitextContentHandlerTest extends MediaWikiUnitTestCase {
	use MockTitleTrait;

	private function newWikitextContentHandler( $overrides = [] ): WikitextContentHandler {
		return new WikitextContentHandler(
			CONTENT_MODEL_WIKITEXT,
			$overrides[TitleFactory::class] ?? $this->createMock( TitleFactory::class ),
			$overrides[ParserFactory::class] ?? $this->createMock( ParserFactory::class ),
			$overrides[GlobalIdGenerator::class] ?? $this->createMock( GlobalIdGenerator::class ),
			$overrides[LanguageNameUtils::class] ?? $this->createMock( LanguageNameUtils::class ),
			$overrides[LinkRenderer::class] ?? $this->createMock( LinkRenderer::class ),
			$overrides[MagicWordFactory::class] ?? $this->createMock( MagicWordFactory::class ),
			$overrides[ParsoidParserFactory::class] ?? $this->createMock( ParsoidParserFactory::class )
		);
	}

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

	public function testUnserializeContent() {
		$handler = $this->newWikitextContentHandler();

		$content = $handler->unserializeContent( 'hello world' );
		$this->assertEquals( 'hello world', $content->getText() );

		$content = $handler->unserializeContent( 'hello world', CONTENT_FORMAT_WIKITEXT );
		$this->assertEquals( 'hello world', $content->getText() );

		$this->expectException( MWException::class );
		$handler->unserializeContent( 'hello world', 'dummy/foo' );
	}

	public function testMakeEmptyContent() {
		$handler = $this->newWikitextContentHandler();
		$content = $handler->makeEmptyContent();

		$this->assertTrue( $content->isEmpty() );
		$this->assertSame( '', $content->getText() );
	}

	public function provideIsSupportedFormat() {
		return [
			[ null, true ],
			[ CONTENT_FORMAT_WIKITEXT, true ],
			[ 99887766, false ],
		];
	}

	/**
	 * @dataProvider provideIsSupportedFormat
	 */
	public function testIsSupportedFormat( $format, $supported ) {
		$handler = $this->newWikitextContentHandler();
		$this->assertEquals( $supported, $handler->isSupportedFormat( $format ) );
	}

	public function testSupportsDirectEditing() {
		$handler = $this->newWikiTextContentHandler();
		$this->assertTrue( $handler->supportsDirectEditing(), 'direct editing is supported' );
	}

	public function testGetSecondaryDataUpdates() {
		$title = $this->createMock( Title::class );
		$content = new WikitextContent( '' );
		$srp = $this->createMock( SlotRenderingProvider::class );
		$handler = $this->newWikitextContentHandler();

		$updates = $handler->getSecondaryDataUpdates( $title, $content, SlotRecord::MAIN, $srp );

		$this->assertEquals( [], $updates );
	}

	public function testGetDeletionUpdates() {
		$title = $this->createMock( Title::class );
		$handler = $this->newWikitextContentHandler();

		$updates = $handler->getDeletionUpdates( $title, SlotRecord::MAIN );
		$this->assertEquals( [], $updates );
	}

	/**
	 * @dataProvider provideFillParserOutput
	 */
	public function testFillParserOutput( $useParsoid = true, $testRedirect = false ) {
		$parserOptions = $this->createMock( ParserOptions::class );
		$parserOptions
			->method( 'getUseParsoid' )
			->willReturn( $useParsoid );

		$parserOutput = $this->createMock( ParserOutput::class );

		// This is the core of the test: if the useParsoid option is NOT
		// present, we expect ParserFactory->getInstance()->parse()
		// to be called exactly once, otherwise never.
		$parser = $this->createMock( Parser::class );
		$parser
			->expects( $useParsoid ? $this->never() : $this->once() )
			->method( 'parse' )
			->willReturn( $parserOutput );
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
			->method( 'parse' )
			->willReturn( $parserOutput );
		$parsoidParserFactory = $this->createMock( ParsoidParserFactory::class );
		$parsoidParserFactory
			->expects( $useParsoid ? $this->once() : $this->never() )
			->method( 'create' )
			->willReturn( $parsoidParser );

		$linkRenderer = $this->createMock( LinkRenderer::class );
		// For a redirect test, we expect LinkRenderer::makeRedirectHeader() to
		// be called once, and ParserOutput::setRedirectHeader() to be called
		// with whatever it returns.
		$linkRenderer
			->expects( $testRedirect ? $this->once() : $this->never() )
			->method( 'makeRedirectHeader' )
			->willReturn( 'ABCDEFG' );
		$parserOutput
			->expects( $testRedirect ? $this->once() : $this->never() )
			->method( 'setRedirectHeader' )
			->with( 'ABCDEFG' );

		// Set up the rest of the mocks
		$magicWordRedirect = $this->createMock( MagicWord::class );
		$magicWordRedirect
			->method( 'getSynonym' )
			->willReturnMap( [ [ 0, '#REDIRECT' ] ] );
		$magicWordRedirect
			->method( 'matchStartAndRemove' )
			->willReturnCallback( static function ( string &$text ) use ( $testRedirect ) {
				if ( $testRedirect ) {
					$text = '[[SomeTitle]]';
					return true;
				} else {
					return false;
				}
			} );
		$magicWordFactory = $this->createMock( MagicWordFactory::class );
		$magicWordFactory
			->method( 'get' )
			->willReturnMap( [ [ 'redirect', $magicWordRedirect ] ] );

		$title = $this->makeMockTitle( 'SomeTitle', [
			'language' => $this->createMock( Language::class ),
		] );
		$titleFactory = $this->createMock( TitleFactory::class );
		$titleFactory
			->method( 'newFromPageReference' )
			->willReturn( $title );
		$titleFactory
			->method( 'newFromText' )
			->willReturnMap( [ [ 'SomeTitle', NS_MAIN, $title ] ] );

		$cpoParams = new ContentParseParams( $title, 42, $parserOptions );

		// The method we'd like to test, fillParserOutput, is protected;
		// make it public
		$class = new ReflectionClass( WikitextContentHandler::class );
		$method = $class->getMethod( 'fillParserOutput' );
		$method->setAccessible( true );

		$handler = $this->newWikitextContentHandler( [
			TitleFactory::class => $titleFactory,
			LinkRenderer::class => $linkRenderer,
			MagicWordFactory::class => $magicWordFactory,
			ParserFactory::class => $parserFactory,
			ParsoidParserFactory::class => $parsoidParserFactory,
		] );

		if ( $testRedirect ) {
			$content = $handler->makeRedirectContent( $title );
		} else {
			$content = new WikitextContent( '* Hello, world!' );
		}

		// Okay, invoke fillParserOutput() and verify that the assertions
		// above about the parse() invocations are correct.
		$method->invokeArgs( $handler, [ $content, $cpoParams, &$parserOutput ] );
	}

	public static function provideFillParserOutput() {
		return [
			[ false, false ],
			[ false, true ],
			[ true, false ],
			[ true, true ],
		];
	}
}
