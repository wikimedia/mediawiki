<?php

/**
 * See also unit tests at \MediaWiki\Tests\Unit\FallbackContentHandlerTest
 *
 * @group ContentHandler
 */
class FallbackContentHandlerTest extends MediaWikiLangTestCase {

	private const CONTENT_MODEL = 'xyzzy';

	protected function setUp(): void {
		parent::setUp();
		$this->mergeMwGlobalArrayValue(
			'wgContentHandlers',
			[ self::CONTENT_MODEL => FallbackContentHandler::class ]
		);
	}

	/**
	 * @param string $data
	 * @param string $type
	 *
	 * @return FallbackContent
	 */
	public function newContent( $data, $type = self::CONTENT_MODEL ) {
		return new FallbackContent( $data, $type );
	}

	/**
	 * @covers ContentHandler::getSlotDiffRenderer
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

	/**
	 * @covers FallbackContentHandler::fillParserOutput
	 */
	public function testGetParserOutput() {
		$this->setUserLang( 'en' );
		$this->setContentLang( 'qqx' );

		$title = Title::newFromText( 'Test' );
		$content = $this->newContent( 'Horkyporky' );
		$contentRenderer = $this->getServiceContainer()->getContentRenderer();
		$po = $contentRenderer->getParserOutput( $content, $title );
		$html = $po->getText();
		$html = preg_replace( '#<!--.*?-->#sm', '', $html ); // strip comments

		$this->assertStringNotContainsString( 'Horkyporky', $html );
		$this->assertStringNotContainsString( '(unsupported-content-model)', $html );
	}
}
