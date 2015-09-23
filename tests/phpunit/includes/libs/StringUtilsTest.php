<?php

class StringUtilsTest extends PHPUnit_Framework_TestCase {

	/**
	 * This tests StringUtils::isUtf8 whenever we have the mbstring extension
	 * loaded.
	 *
	 * @covers StringUtils::isUtf8
	 * @dataProvider provideStringsForIsUtf8Check
	 */
	public function testIsUtf8WithMbstring( $expected, $string ) {
		if ( !function_exists( 'mb_check_encoding' ) ) {
			$this->markTestSkipped( 'Test requires the mbstring PHP extension' );
		}
		$this->assertEquals( $expected,
			StringUtils::isUtf8( $string ),
			'Testing string "' . $this->escaped( $string ) . '" with mb_check_encoding'
		);
	}

	/**
	 * This tests StringUtils::isUtf8 making sure we use the pure PHP
	 * implementation used as a fallback when mb_check_encoding() is
	 * not available.
	 *
	 * @covers StringUtils::isUtf8
	 * @dataProvider provideStringsForIsUtf8Check
	 */
	public function testIsUtf8WithPhpFallbackImplementation( $expected, $string ) {
		$this->assertEquals( $expected,
			StringUtils::isUtf8( $string, /** disable mbstring: */true ),
			'Testing string "' . $this->escaped( $string ) . '" with pure PHP implementation'
		);
	}

	/**
	 * Print high range characters as a hexadecimal
	 * @param string $string
	 * @return string
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
			'some ASCII' => array( $PASS, 'Some ASCII' ),
			'euro sign' => array( $PASS, "Euro sign €" ),

			'first possible sequence 1 byte' => array( $PASS, "\x00" ),
			'first possible sequence 2 bytes' => array( $PASS, "\xc2\x80" ),
			'first possible sequence 3 bytes' => array( $PASS, "\xe0\xa0\x80" ),
			'first possible sequence 4 bytes' => array( $PASS, "\xf0\x90\x80\x80" ),
			'first possible sequence 5 bytes' => array( $FAIL, "\xf8\x88\x80\x80\x80" ),
			'first possible sequence 6 bytes' => array( $FAIL, "\xfc\x84\x80\x80\x80\x80" ),

			'last possible sequence 1 byte' => array( $PASS, "\x7f" ),
			'last possible sequence 2 bytes' => array( $PASS, "\xdf\xbf" ),
			'last possible sequence 3 bytes' => array( $PASS, "\xef\xbf\xbf" ),
			'last possible sequence 4 bytes (U+1FFFFF)' => array( $FAIL, "\xf7\xbf\xbf\xbf" ),
			'last possible sequence 5 bytes' => array( $FAIL, "\xfb\xbf\xbf\xbf\xbf" ),
			'last possible sequence 6 bytes' => array( $FAIL, "\xfd\xbf\xbf\xbf\xbf\xbf" ),

			'boundary 1' => array( $PASS, "\xed\x9f\xbf" ),
			'boundary 2' => array( $PASS, "\xee\x80\x80" ),
			'boundary 3' => array( $PASS, "\xef\xbf\xbd" ),
			'boundary 4' => array( $PASS, "\xf2\x80\x80\x80" ),
			'boundary 5 (U+FFFFF)' => array( $PASS, "\xf3\xbf\xbf\xbf" ),
			'boundary 6 (U+100000)' => array( $PASS, "\xf4\x80\x80\x80" ),
			'boundary 7 (U+10FFFF)' => array( $PASS, "\xf4\x8f\xbf\xbf" ),
			'boundary 8 (U+110000)' => array( $FAIL, "\xf4\x90\x80\x80" ),

			'malformed 1' => array( $FAIL, "\x80" ),
			'malformed 2' => array( $FAIL, "\xbf" ),
			'malformed 3' => array( $FAIL, "\x80\xbf" ),
			'malformed 4' => array( $FAIL, "\x80\xbf\x80" ),
			'malformed 5' => array( $FAIL, "\x80\xbf\x80\xbf" ),
			'malformed 6' => array( $FAIL, "\x80\xbf\x80\xbf\x80" ),
			'malformed 7' => array( $FAIL, "\x80\xbf\x80\xbf\x80\xbf" ),
			'malformed 8' => array( $FAIL, "\x80\xbf\x80\xbf\x80\xbf\x80" ),

			'last byte missing 1' => array( $FAIL, "\xc0" ),
			'last byte missing 2' => array( $FAIL, "\xe0\x80" ),
			'last byte missing 3' => array( $FAIL, "\xf0\x80\x80" ),
			'last byte missing 4' => array( $FAIL, "\xf8\x80\x80\x80" ),
			'last byte missing 5' => array( $FAIL, "\xfc\x80\x80\x80\x80" ),
			'last byte missing 6' => array( $FAIL, "\xdf" ),
			'last byte missing 7' => array( $FAIL, "\xef\xbf" ),
			'last byte missing 8' => array( $FAIL, "\xf7\xbf\xbf" ),
			'last byte missing 9' => array( $FAIL, "\xfb\xbf\xbf\xbf" ),
			'last byte missing 10' => array( $FAIL, "\xfd\xbf\xbf\xbf\xbf" ),

			'extra continuation byte 1' => array( $FAIL, "e\xaf" ),
			'extra continuation byte 2' => array( $FAIL, "\xc3\x89\xaf" ),
			'extra continuation byte 3' => array( $FAIL, "\xef\xbc\xa5\xaf" ),
			'extra continuation byte 4' => array( $FAIL, "\xf0\x9d\x99\xb4\xaf" ),

			'impossible bytes 1' => array( $FAIL, "\xfe" ),
			'impossible bytes 2' => array( $FAIL, "\xff" ),
			'impossible bytes 3' => array( $FAIL, "\xfe\xfe\xff\xff" ),

			'overlong sequences 1' => array( $FAIL, "\xc0\xaf" ),
			'overlong sequences 2' => array( $FAIL, "\xc1\xaf" ),
			'overlong sequences 3' => array( $FAIL, "\xe0\x80\xaf" ),
			'overlong sequences 4' => array( $FAIL, "\xf0\x80\x80\xaf" ),
			'overlong sequences 5' => array( $FAIL, "\xf8\x80\x80\x80\xaf" ),
			'overlong sequences 6' => array( $FAIL, "\xfc\x80\x80\x80\x80\xaf" ),

			'maximum overlong sequences 1' => array( $FAIL, "\xc1\xbf" ),
			'maximum overlong sequences 2' => array( $FAIL, "\xe0\x9f\xbf" ),
			'maximum overlong sequences 3' => array( $FAIL, "\xf0\x8f\xbf\xbf" ),
			'maximum overlong sequences 4' => array( $FAIL, "\xf8\x87\xbf\xbf" ),
			'maximum overlong sequences 5' => array( $FAIL, "\xfc\x83\xbf\xbf\xbf\xbf" ),

			'surrogates 1 (U+D799)' => array( $PASS, "\xed\x9f\xbf" ),
			'surrogates 2 (U+E000)' => array( $PASS, "\xee\x80\x80" ),
			'surrogates 3 (U+D800)' => array( $FAIL, "\xed\xa0\x80" ),
			'surrogates 4 (U+DBFF)' => array( $FAIL, "\xed\xaf\xbf" ),
			'surrogates 5 (U+DC00)' => array( $FAIL, "\xed\xb0\x80" ),
			'surrogates 6 (U+DFFF)' => array( $FAIL, "\xed\xbf\xbf" ),
			'surrogates 7 (U+D800 U+DC00)' => array( $FAIL, "\xed\xa0\x80\xed\xb0\x80" ),

			'noncharacters 1' => array( $PASS, "\xef\xbf\xbe" ),
			'noncharacters 2' => array( $PASS, "\xef\xbf\xbf" ),
		);
	}
}
