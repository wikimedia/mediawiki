<?php

/**
 * @requires extension xdiff
 */
class DiffHistoryBlobTest extends MediaWikiUnitTestCase {
	/**
	 * @dataProvider provideXdiffAdler32
	 * @covers \DiffHistoryBlob::xdiffAdler32
	 */
	public function testXdiffAdler32( $input ) {
		$xdiffHash = substr( xdiff_string_rabdiff( $input, '' ), 0, 4 );
		$dhb = new DiffHistoryBlob;
		$myHash = $dhb->xdiffAdler32( $input );
		$this->assertSame( bin2hex( $xdiffHash ), bin2hex( $myHash ),
			"Hash of " . addcslashes( $input, "\0..\37!@\@\177..\377" ) );
	}

	public static function provideXdiffAdler32() {
		return [
			[ '', 'Empty string' ],
			[ "\0", 'Null' ],
			[ "\0\0\0", "Several nulls" ],
			[ "Hello", "An ASCII string" ],
			[ str_repeat( "x", 6000 ), "A string larger than xdiff's NMAX (5552)" ]
		];
	}
}
