<?php

class StringUtilsTest extends PHPUnit\Framework\TestCase {

	use MediaWikiCoversValidator;

	/**
	 * @covers StringUtils::isUtf8
	 * @dataProvider provideStringsForIsUtf8Check
	 */
	public function testIsUtf8( $expected, $string ) {
		$this->assertEquals( $expected, StringUtils::isUtf8( $string ),
			'Testing string "' . $this->escaped( $string ) . '"' );
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

		return [
			'some ASCII' => [ $PASS, 'Some ASCII' ],
			'euro sign' => [ $PASS, "Euro sign €" ],

			'first possible sequence 1 byte' => [ $PASS, "\x00" ],
			'first possible sequence 2 bytes' => [ $PASS, "\xc2\x80" ],
			'first possible sequence 3 bytes' => [ $PASS, "\xe0\xa0\x80" ],
			'first possible sequence 4 bytes' => [ $PASS, "\xf0\x90\x80\x80" ],
			'first possible sequence 5 bytes' => [ $FAIL, "\xf8\x88\x80\x80\x80" ],
			'first possible sequence 6 bytes' => [ $FAIL, "\xfc\x84\x80\x80\x80\x80" ],

			'last possible sequence 1 byte' => [ $PASS, "\x7f" ],
			'last possible sequence 2 bytes' => [ $PASS, "\xdf\xbf" ],
			'last possible sequence 3 bytes' => [ $PASS, "\xef\xbf\xbf" ],
			'last possible sequence 4 bytes (U+1FFFFF)' => [ $FAIL, "\xf7\xbf\xbf\xbf" ],
			'last possible sequence 5 bytes' => [ $FAIL, "\xfb\xbf\xbf\xbf\xbf" ],
			'last possible sequence 6 bytes' => [ $FAIL, "\xfd\xbf\xbf\xbf\xbf\xbf" ],

			'boundary 1' => [ $PASS, "\xed\x9f\xbf" ],
			'boundary 2' => [ $PASS, "\xee\x80\x80" ],
			'boundary 3' => [ $PASS, "\xef\xbf\xbd" ],
			'boundary 4' => [ $PASS, "\xf2\x80\x80\x80" ],
			'boundary 5 (U+FFFFF)' => [ $PASS, "\xf3\xbf\xbf\xbf" ],
			'boundary 6 (U+100000)' => [ $PASS, "\xf4\x80\x80\x80" ],
			'boundary 7 (U+10FFFF)' => [ $PASS, "\xf4\x8f\xbf\xbf" ],
			'boundary 8 (U+110000)' => [ $FAIL, "\xf4\x90\x80\x80" ],

			'malformed 1' => [ $FAIL, "\x80" ],
			'malformed 2' => [ $FAIL, "\xbf" ],
			'malformed 3' => [ $FAIL, "\x80\xbf" ],
			'malformed 4' => [ $FAIL, "\x80\xbf\x80" ],
			'malformed 5' => [ $FAIL, "\x80\xbf\x80\xbf" ],
			'malformed 6' => [ $FAIL, "\x80\xbf\x80\xbf\x80" ],
			'malformed 7' => [ $FAIL, "\x80\xbf\x80\xbf\x80\xbf" ],
			'malformed 8' => [ $FAIL, "\x80\xbf\x80\xbf\x80\xbf\x80" ],

			'last byte missing 1' => [ $FAIL, "\xc0" ],
			'last byte missing 2' => [ $FAIL, "\xe0\x80" ],
			'last byte missing 3' => [ $FAIL, "\xf0\x80\x80" ],
			'last byte missing 4' => [ $FAIL, "\xf8\x80\x80\x80" ],
			'last byte missing 5' => [ $FAIL, "\xfc\x80\x80\x80\x80" ],
			'last byte missing 6' => [ $FAIL, "\xdf" ],
			'last byte missing 7' => [ $FAIL, "\xef\xbf" ],
			'last byte missing 8' => [ $FAIL, "\xf7\xbf\xbf" ],
			'last byte missing 9' => [ $FAIL, "\xfb\xbf\xbf\xbf" ],
			'last byte missing 10' => [ $FAIL, "\xfd\xbf\xbf\xbf\xbf" ],

			'extra continuation byte 1' => [ $FAIL, "e\xaf" ],
			'extra continuation byte 2' => [ $FAIL, "\xc3\x89\xaf" ],
			'extra continuation byte 3' => [ $FAIL, "\xef\xbc\xa5\xaf" ],
			'extra continuation byte 4' => [ $FAIL, "\xf0\x9d\x99\xb4\xaf" ],

			'impossible bytes 1' => [ $FAIL, "\xfe" ],
			'impossible bytes 2' => [ $FAIL, "\xff" ],
			'impossible bytes 3' => [ $FAIL, "\xfe\xfe\xff\xff" ],

			'overlong sequences 1' => [ $FAIL, "\xc0\xaf" ],
			'overlong sequences 2' => [ $FAIL, "\xc1\xaf" ],
			'overlong sequences 3' => [ $FAIL, "\xe0\x80\xaf" ],
			'overlong sequences 4' => [ $FAIL, "\xf0\x80\x80\xaf" ],
			'overlong sequences 5' => [ $FAIL, "\xf8\x80\x80\x80\xaf" ],
			'overlong sequences 6' => [ $FAIL, "\xfc\x80\x80\x80\x80\xaf" ],

			'maximum overlong sequences 1' => [ $FAIL, "\xc1\xbf" ],
			'maximum overlong sequences 2' => [ $FAIL, "\xe0\x9f\xbf" ],
			'maximum overlong sequences 3' => [ $FAIL, "\xf0\x8f\xbf\xbf" ],
			'maximum overlong sequences 4' => [ $FAIL, "\xf8\x87\xbf\xbf" ],
			'maximum overlong sequences 5' => [ $FAIL, "\xfc\x83\xbf\xbf\xbf\xbf" ],

			'surrogates 1 (U+D799)' => [ $PASS, "\xed\x9f\xbf" ],
			'surrogates 2 (U+E000)' => [ $PASS, "\xee\x80\x80" ],
			'surrogates 3 (U+D800)' => [ $FAIL, "\xed\xa0\x80" ],
			'surrogates 4 (U+DBFF)' => [ $FAIL, "\xed\xaf\xbf" ],
			'surrogates 5 (U+DC00)' => [ $FAIL, "\xed\xb0\x80" ],
			'surrogates 6 (U+DFFF)' => [ $FAIL, "\xed\xbf\xbf" ],
			'surrogates 7 (U+D800 U+DC00)' => [ $FAIL, "\xed\xa0\x80\xed\xb0\x80" ],

			'noncharacters 1' => [ $PASS, "\xef\xbf\xbe" ],
			'noncharacters 2' => [ $PASS, "\xef\xbf\xbf" ],
		];
	}

	/**
	 * @param strin $input
	 * @param bool $expected
	 * @dataProvider provideRegexps
	 * @covers StringUtils::isValidPCRERegex
	 */
	public function testIsValidPCRERegex( $input, $expected ) {
		$this->assertSame( $expected, StringUtils::isValidPCRERegex( $input ) );
	}

	/**
	 * Data provider for testIsValidPCRERegex
	 * @return array
	 */
	public static function provideRegexps() {
		return [
			[ 'foo', false ],
			[ '/foo/', true ],
			[ '//', true ],
			[ '/(foo/', false ],
			[ '!(f[o]{2})!', true ],
			[ '/foo\/', false ]
		];
	}
}
