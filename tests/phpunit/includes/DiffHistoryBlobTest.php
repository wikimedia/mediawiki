<?php

class DiffHistoryBlobTest extends MediaWikiIntegrationTestCase {

	protected function setUp() : void {
		parent::setUp();

		$this->checkPHPExtension( 'hash' );
		$this->checkPHPExtension( 'xdiff' );

		if ( !function_exists( 'xdiff_string_rabdiff' ) ) {
			$this->markTestSkipped( 'The version of xdiff extension is lower than 1.5.0' );
			return;
		}
	}

	/**
	 * @dataProvider provideXdiffAdler32
	 * @covers DiffHistoryBlob::xdiffAdler32
	 */
	public function testXdiffAdler32( $input ) {
		$xdiffHash = substr( xdiff_string_rabdiff( $input, '' ), 0, 4 );
		$dhb = new DiffHistoryBlob;
		$myHash = $dhb->xdiffAdler32( $input );
		$this->assertSame( bin2hex( $xdiffHash ), bin2hex( $myHash ),
			"Hash of " . addcslashes( $input, "\0..\37!@\@\177..\377" ) );
	}

	public function provideXdiffAdler32() {
		// Hack non-empty early return since PHPUnit expands this provider before running
		// the setUp() which marks the test as skipped.
		if ( !function_exists( 'xdiff_string_rabdiff' ) ) {
			return [ [ '', 'Empty string' ] ];
		}

		return [
			[ '', 'Empty string' ],
			[ "\0", 'Null' ],
			[ "\0\0\0", "Several nulls" ],
			[ "Hello", "An ASCII string" ],
			[ str_repeat( "x", 6000 ), "A string larger than xdiff's NMAX (5552)" ]
		];
	}
}
