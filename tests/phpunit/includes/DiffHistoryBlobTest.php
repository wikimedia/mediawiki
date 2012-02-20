<?php

class DiffHistoryBlobTest extends MediaWikiTestCase {
	function setUp() {
		if ( !extension_loaded( 'xdiff' ) ) {
			$this->markTestSkipped( 'The xdiff extension is not available' );
			return;
		}
		if ( !function_exists( 'xdiff_string_rabdiff' ) ) {
			$this->markTestSkipped( 'The version of xdiff extension is lower than 1.5.0' );
			return;
		}
		if ( !extension_loaded( 'hash' ) && !extension_loaded( 'mhash' ) ) {
			$this->markTestSkipped( 'Neither the hash nor mhash extension is available' );
			return;
		}
	}

	/**
	 * Test for DiffHistoryBlob::xdiffAdler32()
	 * @dataProvider provideXdiffAdler32
	 */
	function testXdiffAdler32( $input ) {
		$xdiffHash = substr( xdiff_string_rabdiff( $input, '' ),  0, 4 );
		$dhb = new DiffHistoryBlob;
		$myHash = $dhb->xdiffAdler32( $input );
		$this->assertSame( bin2hex( $xdiffHash ), bin2hex( $myHash ),
			"Hash of " . addcslashes( $input, "\0..\37!@\@\177..\377" ) );
	}

	function provideXdiffAdler32() {
		return array(
			array( '', 'Empty string' ),
			array( "\0", 'Null' ),
			array( "\0\0\0", "Several nulls" ),
			array( "Hello", "An ASCII string" ),
			array( str_repeat( "x", 6000 ), "A string larger than xdiff's NMAX (5552)" )
		);
	}
}
