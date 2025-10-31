<?php

namespace MediaWiki\Tests\Content;

use MediaWiki\Content\FallbackContent;
use MediaWiki\Content\FallbackContentHandler;
use MediaWiki\Context\RequestContext;
use MediaWiki\Parser\ParserObserver;
use MediaWiki\Parser\ParserOptions;
use MediaWiki\Request\FauxRequest;
use MediaWiki\Title\Title;
use MediaWikiLangTestCase;

/**
 * See also unit tests at \MediaWiki\Tests\Unit\FallbackContentHandlerTest
 *
 * @group ContentHandler
 * @covers \MediaWiki\Content\FallbackContentHandler
 * @covers \MediaWiki\Content\ContentHandler
 */
class FallbackContentHandlerTest extends MediaWikiLangTestCase {

	private const CONTENT_MODEL = 'xyzzy';

	protected function setUp(): void {
		parent::setUp();
		$this->mergeMwGlobalArrayValue(
			'wgContentHandlers',
			[ self::CONTENT_MODEL => FallbackContentHandler::class ]
		);
		$this->setService( '_ParserObserver', $this->createMock( ParserObserver::class ) );
	}

	private function newContent( string $data, string $type = self::CONTENT_MODEL ) {
		return new FallbackContent( $data, $type );
	}

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

	public function testGetParserOutput() {
		$this->setUserLang( 'en' );
		$this->setContentLang( 'qqx' );

		$title = $this->createMock( Title::class );
		$title->method( 'getPageLanguage' )
			->willReturn( $this->getServiceContainer()->getContentLanguage() );

		$content = $this->newContent( 'Horkyporky' );
		$contentRenderer = $this->getServiceContainer()->getContentRenderer();
		$opts = ParserOptions::newFromAnon();
		// TODO T371004
		$po = $contentRenderer->getParserOutput( $content, $title, null, $opts );
		$html = $po->runOutputPipeline( $opts, [] )->getContentHolderText();
		$html = preg_replace( '#<!--.*?-->#sm', '', $html ); // strip comments

		$this->assertStringNotContainsString( 'Horkyporky', $html );
		$this->assertStringNotContainsString( '(unsupported-content-model)', $html );
	}
}
