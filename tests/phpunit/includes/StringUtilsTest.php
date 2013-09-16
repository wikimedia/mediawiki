<?php

class StringUtilsTest extends MediaWikiTestCase {

	/**
	 * This test StringUtils::isUtf8 whenever we have mbstring extension
	 * loaded.
	 *
	 * @covers StringUtils::isUtf8
	 * @dataProvider provideStringsForIsUtf8Check
	 */
	function testIsUtf8WithMbstring( $expected, $string ) {
		if ( !function_exists( 'mb_check_encoding' ) ) {
			$this->markTestSkipped( 'Test requires the mbstring PHP extension' );
		}
		$this->assertEquals( $expected,
			StringUtils::isUtf8( $string ),
			'Testing string "' . $this->escaped( $string ) . '" with mb_check_encoding'
		);
	}

	/**
	 * This test StringUtils::isUtf8 making sure we use the pure PHP
	 * implementation used as a fallback when mb_check_encoding() is
	 * not available.
	 *
	 * @covers StringUtils::isUtf8
	 * @dataProvider provideStringsForIsUtf8Check
	 */
	function testIsUtf8WithPhpFallbackImplementation( $expected, $string ) {
		$this->assertEquals( $expected,
			StringUtils::isUtf8( $string, /** disable mbstring: */true ),
			'Testing string "' . $this->escaped( $string ) . '" with pure PHP implementation'
		);
	}

	/**
	 * Print high range characters as an hexadecimal
	 */
	function escaped( $string ) {
		$escaped = '';
		$length = strlen( $string );
		for ( $i = 0; $i < $length; $i++ ) {
			$char = $string[$i];
			$val = ord( $char );
			if ( $val > 127 ) {
				$escaped .= '\x' . dechex( $val );
			} else {
				$escaped .= $char;
			}
		}

		return $escaped;
	}

	/**
	 * See also "UTF-8 decoder capability and stress test" by
	 * Markus Kuhn:
	 * http://www.cl.cam.ac.uk/~mgk25/ucs/examples/UTF-8-test.txt
	 */
	public static function provideStringsForIsUtf8Check() {
		// Expected return values for StringUtils::isUtf8()
		$PASS = true;
		$FAIL = false;

		return array(
			array( $PASS, 'Some ASCII' ),
			array( $PASS, "Euro sign €" ),

			// First possible sequences
			array( $PASS, "\x00" ),
			array( $PASS, "\xc2\x80" ),
			array( $PASS, "\xe0\xa0\x80" ),
			array( $PASS, "\xf0\x90\x80\x80" ),
			array( $FAIL, "\xf8\x88\x80\x80\x80" ),
			array( $FAIL, "\xfc\x84\x80\x80\x80\x80" ),

			// Last possible sequence
			array( $PASS, "\x7f" ),
			array( $PASS, "\xdf\xbf" ),
			array( $PASS, "\xef\xbf\xbf" ),
			array( $FAIL, "\xf7\xbf\xbf\xbf" ), // U+1FFFFF
			array( $FAIL, "\xfb\xbf\xbf\xbf\xbf" ),
			array( $FAIL, "\xfd\xbf\xbf\xbf\xbf\xbf" ),

			// Boundaries
			array( $PASS, "\xed\x9f\xbf" ),
			array( $PASS, "\xee\x80\x80" ),
			array( $PASS, "\xef\xbf\xbd" ),
			array( $PASS, "\xf2\x80\x80\x80" ),
			array( $PASS, "\xf3\xbf\xbf\xbf" ), // U+FFFFF
			array( $PASS, "\xf4\x80\x80\x80" ), // U+100000
			array( $PASS, "\xf4\x8f\xbf\xbf" ), // U+10FFFF
			array( $FAIL, "\xf4\x90\x80\x80" ), // U+110000

			// Malformed
			array( $FAIL, "\x80" ),
			array( $FAIL, "\xbf" ),
			array( $FAIL, "\x80\xbf" ),
			array( $FAIL, "\x80\xbf\x80" ),
			array( $FAIL, "\x80\xbf\x80\xbf" ),
			array( $FAIL, "\x80\xbf\x80\xbf\x80" ),
			array( $FAIL, "\x80\xbf\x80\xbf\x80\xbf" ),
			array( $FAIL, "\x80\xbf\x80\xbf\x80\xbf\x80" ),

			// Last byte missing
			array( $FAIL, "\xc0" ),
			array( $FAIL, "\xe0\x80" ),
			array( $FAIL, "\xf0\x80\x80" ),
			array( $FAIL, "\xf8\x80\x80\x80" ),
			array( $FAIL, "\xfc\x80\x80\x80\x80" ),
			array( $FAIL, "\xdf" ),
			array( $FAIL, "\xef\xbf" ),
			array( $FAIL, "\xf7\xbf\xbf" ),
			array( $FAIL, "\xfb\xbf\xbf\xbf" ),
			array( $FAIL, "\xfd\xbf\xbf\xbf\xbf" ),

			// Extra continuation byte
			array( $FAIL, "e\xaf" ),
			array( $FAIL, "\xc3\x89\xaf" ),
			array( $FAIL, "\xef\xbc\xa5\xaf" ),
			array( $FAIL, "\xf0\x9d\x99\xb4\xaf" ),

			// Impossible bytes
			array( $FAIL, "\xfe" ),
			array( $FAIL, "\xff" ),
			array( $FAIL, "\xfe\xfe\xff\xff" ),

			// Overlong sequences
			array( $FAIL, "\xc0\xaf" ),
			array( $FAIL, "\xc1\xaf" ),
			array( $FAIL, "\xe0\x80\xaf" ),
			array( $FAIL, "\xf0\x80\x80\xaf" ),
			array( $FAIL, "\xf8\x80\x80\x80\xaf" ),
			array( $FAIL, "\xfc\x80\x80\x80\x80\xaf" ),

			// Maximum overlong sequences
			array( $FAIL, "\xc1\xbf" ),
			array( $FAIL, "\xe0\x9f\xbf" ),
			array( $FAIL, "\xf0\x8f\xbf\xbf" ),
			array( $FAIL, "\xf8\x87\xbf\xbf" ),
			array( $FAIL, "\xfc\x83\xbf\xbf\xbf\xbf" ),

			// Surrogates
			array( $PASS, "\xed\x9f\xbf" ), // U+D799
			array( $PASS, "\xee\x80\x80" ), // U+E000
			array( $FAIL, "\xed\xa0\x80" ), // U+D800
			array( $FAIL, "\xed\xaf\xbf" ), // U+DBFF
			array( $FAIL, "\xed\xb0\x80" ), // U+DC00
			array( $FAIL, "\xed\xbf\xbf" ), // U+DFFF
			array( $FAIL, "\xed\xa0\x80\xed\xb0\x80" ), // U+D800 U+DC00

			// Noncharacters
			array( $PASS, "\xef\xbf\xbe" ),
			array( $PASS, "\xef\xbf\xbf" ),
		);
	}
}
