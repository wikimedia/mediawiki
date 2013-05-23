<?php

class StringUtilsTest extends MediaWikiTestCase {

	/**
	 * This tests StringUtils::isUtf8 whenever we have mbstring extension
	 * loaded.
	 *
	 * @covers StringUtils::isUtf8
	 * @dataProvider provideStringsForIsUtf8CheckWithMbstring
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
	 * This tests StringUtils::isUtf8 making sure we use the pure PHP
	 * implementation used as a fallback when mb_check_encoding() is
	 * not available.
	 *
	 * @covers StringUtils::isUtf8
	 * @dataProvider provideStringsForIsUtf8CheckWithPhpFallback
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

	public static function provideStringsForIsUtf8CheckWithMbstring() {
		return self::provideStringsForIsUtf8Check(true);
	}

	public static function provideStringsForIsUtf8CheckWithPhpFallback() {
		return self::provideStringsForIsUtf8Check(false);
	}

	/**
	 * See also "UTF-8 decoder capability and stress test" by
	 * Markus Kuhn:
	 * http://www.cl.cam.ac.uk/~mgk25/ucs/examples/UTF-8-test.txt
	 */
	public static function provideStringsForIsUtf8Check($mbstring) {
		// Expected return values for StringUtils::isUtf8()
		$PASS = true;
		$FAIL = false;
		/* since PHP 5.4 the mbstring extension rejects some strings that
		 * it considered UTF-8 before
		 */
		$passBeforePhp54 = version_compare(PHP_VERSION, '5.4', '<');
		if (false === $mbstring) {
			$passBeforePhp54 = true;
		}

		return array(
			'some ASCII' => array( $PASS, 'Some ASCII' ),
			'euro sign' => array( $PASS, "Euro sign €" ),

			'first possible sequence 1 byte' => array( $PASS, "\x00" ),
			'first possible sequence 2 bytes' => array( $PASS, "\xc2\x80" ),
			'first possible sequence 3 bytes' => array( $PASS, "\xe0\xa0\x80" ),
			'first possible sequence 4 bytes' => array( $PASS, "\xf0\x90\x80\x80" ),
			'first possible sequence 5 bytes' => array( $passBeforePhp54, "\xf8\x88\x80\x80\x80" ),
			'first possible sequence 6 bytes' => array( $passBeforePhp54, "\xfc\x84\x80\x80\x80\x80" ),

			'last possible sequence 1 byte' => array( $PASS, "\x7f" ),
			'last possible sequence 2 bytes' => array( $PASS, "\xdf\xbf" ),
			'last possible sequence 3 bytes' => array( $PASS, "\xef\xbf\xbf" ),
			'last possible sequence 4 bytes' => array( $passBeforePhp54, "\xf7\xbf\xbf\xbf" ),
			'last possible sequence 5 bytes' => array( $passBeforePhp54, "\xfb\xbf\xbf\xbf\xbf" ),
			'last possible sequence 6 bytes' => array( $FAIL, "\xfd\xbf\xbf\xbf\xbf\xbf" ),

			'boundary 1' => array( $PASS, "\xed\x9f\xbf" ),
			'boundary 2' => array( $PASS, "\xee\x80\x80" ),
			'boundary 3' => array( $PASS, "\xef\xbf\xbd" ),
			'boundary 4' => array( $PASS, "\xf4\x8f\xbf\xbf" ),
			'boundary 5' => array( $passBeforePhp54, "\xf4\x90\x80\x80" ),

			'malformed 1' => array( $FAIL, "\x80" ),
			'malformed 2' => array( $FAIL, "\xBF" ),
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

			'impossible bytes 1' => array( $FAIL, "\xfe" ),
			'impossible bytes 2' => array( $FAIL, "\xff" ),
			'impossible bytes 3' => array( $FAIL, "\xfe\xfe\xff\xff" ),

			/*
			# The PHP implementation does not handle characters
			# being represented in a form which is too long :(

			'overlong sequence 1' => array( $FAIL, "\xc0\xaf" ),
			'overlong sequence 2' => array( $FAIL, "\xe0\x80\xaf" ),
			'overlong sequence 3' => array( $FAIL, "\xf0\x80\x80\xaf" ),
			'overlong sequence 4' => array( $FAIL, "\xf8\x80\x80\x80\xaf" ),
			'overlong sequence 5' => array( $FAIL, "\xfc\x80\x80\x80\x80\xaf" ),

			'maximum overlong sequence 1' => array( $FAIL, "\xc1\xbf" ),
			'maximum overlong sequence 2' => array( $FAIL, "\xe0\x9f\xbf" ),
			'maximum overlong sequence 3' => array( $FAIL, "\xf0\x8F\xbf\xbf" ),
			'maximum overlong sequence 4' => array( $FAIL, "\xf8\x87\xbf\xbf" ),
			'maximum overlong sequence 5' => array( $FAIL, "\xfc\x83\xbf\xbf\xbf\xbf" ),
			*/

			'non characters 1' => array( $PASS, "\xef\xbf\xbe" ),
			'non characters 2' => array( $PASS, "\xef\xbf\xbf" ),
		);
	}
}
