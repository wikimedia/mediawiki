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

	public static function provideDiff() {
		// AbstactContent::getContentHandler uses the ContentHandlerFactory
		// from MediaWikiServices, which we need to avoid. Instead, mock
		// to return the relevant ContentHandlers. The actual "content" of the
		// Content objects isn't used, just getModel() and getContentHandler()
		$oldContent = [ TextContent::class, CONTENT_MODEL_TEXT, new TextContentHandler() ];
		$newContent = [ TextContent::class, CONTENT_MODEL_TEXT, new TextContentHandler() ];
		$badContent = [ FallbackContent::class, 'xyzzy', new FallbackContentHandler( 'xyzzy' ) ];

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
	public function testDiff( $expected, $oldContentSpec, $newContentSpec ) {
		if ( $oldContentSpec !== null ) {
			$oldContent = $this->createMock( $oldContentSpec[0] );
			$oldContent->method( 'getModel' )->willReturn( $oldContentSpec[1] );
			$oldContent->method( 'getContentHandler' )->willReturn( $oldContentSpec[2] );
		} else {
			$oldContent = null;
		}
		if ( $newContentSpec !== null ) {
			$newContent = $this->createMock( $newContentSpec[0] );
			$newContent->method( 'getModel' )->willReturn( $newContentSpec[1] );
			$newContent->method( 'getContentHandler' )->willReturn( $newContentSpec[2] );
		} else {
			$newContent = null;
		}

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
