<?php

use MediaWiki\Content\FallbackContent;
use MediaWiki\Content\FallbackContentHandler;
use MediaWiki\Content\TextContent;
use MediaWiki\Content\TextContentHandler;
use MediaWiki\Message\Message;

/**
 * @covers \UnsupportedSlotDiffRenderer
 */
class UnsupportedSlotDiffRendererTest extends MediaWikiUnitTestCase {

	public function provideDiff() {
		// AbstactContent::getContentHandler uses the ContentHandlerFactory
		// from MediaWikiServices, which we need to avoid. Instead, mock
		// to return the relevant ContentHandlers. The actual "content" of the
		// Content objects isn't used, just getModel() and getContentHandler()
		$oldContent = $this->createMock( TextContent::class );
		$oldContent->method( 'getModel' )->willReturn( CONTENT_MODEL_TEXT );
		$oldContent->method( 'getContentHandler' )->willReturn( new TextContentHandler() );

		$newContent = $this->createMock( TextContent::class );
		$newContent->method( 'getModel' )->willReturn( CONTENT_MODEL_TEXT );
		$newContent->method( 'getContentHandler' )->willReturn( new TextContentHandler() );

		$badContent = $this->createMock( FallbackContent::class );
		$badContent->method( 'getModel' )->willReturn( 'xyzzy' );
		$badContent->method( 'getContentHandler' )->willReturn( new FallbackContentHandler( 'xyzzy' ) );

		yield [ '(unsupported-content-diff)', $oldContent, null ];
		yield [ '(unsupported-content-diff)', null, $newContent ];
		yield [ '(unsupported-content-diff)', $oldContent, $newContent ];
		yield [ '(unsupported-content-diff2)', $badContent, $newContent ];
		yield [ '(unsupported-content-diff2)', $oldContent, $badContent ];
		yield [ '(unsupported-content-diff)', null, $badContent ];
		yield [ '(unsupported-content-diff)', $badContent, null ];
	}

	/**
	 * @dataProvider provideDiff
	 */
	public function testDiff( $expected, $oldContent, $newContent ) {
		$localizer = $this->createMock( MessageLocalizer::class );

		$localizer->method( 'msg' )
			->willReturnCallback( function ( $key, ...$params ) {
				$msg = $this->createMock( Message::class );
				$msg->method( 'parse' )->willReturn( "($key)" );
				return $msg;
			} );

		$sdr = new UnsupportedSlotDiffRenderer( $localizer );
		$this->assertStringContainsString( $expected, $sdr->getDiff( $oldContent, $newContent ) );
	}

}
