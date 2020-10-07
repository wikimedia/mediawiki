<?php

/**
 * @covers UnsupportedSlotDiffRenderer
 */
class UnsupportedSlotDiffRendererTest extends MediaWikiIntegrationTestCase {

	public function provideDiff() {
		$oldContent = new TextContent( 'Kittens' );
		$newContent = new TextContent( 'Goats' );
		$badContent = new UnknownContent( 'Dragons', 'xyzzy' );

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
		$this->mergeMwGlobalArrayValue(
			'wgContentHandlers',
			[ 'xyzzy' => 'UnknownContentHandler' ]
		);

		$localizer = $this->createMock( MessageLocalizer::class );

		$localizer->method( 'msg' )
			->willReturnCallback( function ( $key, ...$params ) {
				return new RawMessage( "($key)", $params );
			} );

		$sdr = new UnsupportedSlotDiffRenderer( $localizer );
		$this->assertStringContainsString( $expected, $sdr->getDiff( $oldContent, $newContent ) );
	}

}
