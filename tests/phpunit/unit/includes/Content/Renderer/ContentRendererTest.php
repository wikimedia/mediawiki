<?php
/**
 * @license GPL-2.0-or-later
 * @file
 */

namespace MediaWiki\Tests\Unit\Content\Renderer;

use MediaWiki\Content\IContentHandlerFactory;
use MediaWiki\Content\Renderer\ContentRenderer;
use MediaWiki\Content\WikitextContent;
use MediaWiki\Content\WikitextContentHandler;
use MediaWiki\Page\PageIdentityValue;
use MediaWiki\Parser\ParserOptions;
use MediaWiki\Parser\ParserOutput;
use MediaWiki\Revision\MutableRevisionRecord;
use MediaWikiUnitTestCase;
use Wikimedia\UUID\GlobalIdGenerator;

/**
 * @group Renderer
 * @covers \MediaWiki\Content\Renderer\ContentRenderer
 */
class ContentRendererTest extends MediaWikiUnitTestCase {

	protected IContentHandlerFactory $contentHandlerFactory;
	protected GlobalIdGenerator $globalIdGenerator;

	protected function setUp(): void {
		parent::setUp();
		$this->contentHandlerFactory = $this->createMock( IContentHandlerFactory::class );
		$this->globalIdGenerator = $this->createMock( GlobalIdGenerator::class );
	}

	/**
	 * This method tests the getParserOutput method. It is expected that the method will return a ParserOutput
	 * object with a render ID, cache revision ID, and revision timestamp.
	 */
	public function testGetParserOutput() {
		$page = PageIdentityValue::localIdentity( 1, NS_MAIN, 'TestPage' );
		$parserOptions = $this->createMock( ParserOptions::class );
		$revision = new MutableRevisionRecord( $page );
		$revision->setTimestamp( '20230418000000' );

		$contentHandler = $this->createMock( WikitextContentHandler::class );

		$content = $this->createMock( WikitextContent::class );
		$content->method( 'getModel' )->willReturn( CONTENT_MODEL_WIKITEXT );

		$this->contentHandlerFactory->expects( $this->once() )
			->method( 'getContentHandler' )
			->with( $content->getModel() )
			->willReturn( $contentHandler );

		$expectedParserOutput = new ParserOutput();
		$expectedParserOutput->setRenderId( '12345' );
		$expectedParserOutput->setCacheRevisionId( 123 );

		$contentHandler->expects( $this->once() )
			->method( 'getParserOutput' )
			->with( $content, $this->anything() )
			->willReturn( $expectedParserOutput );

		$renderer = new ContentRenderer( $this->contentHandlerFactory, $this->globalIdGenerator );
		$parserOutput = $renderer->getParserOutput( $content, $page, $revision, $parserOptions );

		$this->assertInstanceOf( ParserOutput::class, $parserOutput );
		if ( $parserOutput->getCacheRevisionId() !== null ) {
			$this->assertEquals( 123, $parserOutput->getCacheRevisionId() );
		} else {
			$this->fail( 'Cache revision ID is null' );
		}
		if ( $parserOutput->getRenderId() !== null ) {
			$this->assertSame( '12345', $parserOutput->getRenderId() );
		} else {
			$this->fail( 'Render ID is null' );
		}
		if ( $parserOutput->getRevisionTimestamp() !== null ) {
			$this->assertSame( '20230418000000', $parserOutput->getRevisionTimestamp() );
		} else {
			$this->fail( 'Revision timestamp is null' );
		}
	}

	/**
	 * This method tests the getParserOutput method with a null revision. It is expected that the method will
	 * return a ParserOutput object with a render ID.
	 */
	public function testGetParserOutputWithNullRevision() {
		$page = PageIdentityValue::localIdentity( 1, NS_MAIN, 'TestPage' );
		$parserOptions = $this->createMock( ParserOptions::class );

		$contentHandler = $this->createMock( WikitextContentHandler::class );

		$content = $this->createMock( WikitextContent::class );
		$content->method( 'getModel' )->willReturn( CONTENT_MODEL_WIKITEXT );

		$this->contentHandlerFactory->expects( $this->once() )
			->method( 'getContentHandler' )
			->with( $content->getModel() )
			->willReturn( $contentHandler );

		$expectedParserOutput = new ParserOutput();
		$expectedParserOutput->setRenderId( '12345' );

		$contentHandler->expects( $this->once() )
			->method( 'getParserOutput' )
			->with( $content, $this->anything() )
			->willReturn( $expectedParserOutput );

		$renderer = new ContentRenderer( $this->contentHandlerFactory, $this->globalIdGenerator );
		$parserOutput = $renderer->getParserOutput( $content, $page, null, $parserOptions );

		$this->assertInstanceOf( ParserOutput::class, $parserOutput );
		if ( $parserOutput->getRenderId() !== null ) {
			$this->assertSame( '12345', $parserOutput->getRenderId() );
		} else {
			$this->fail( 'Render ID is null' );
		}
	}
}
