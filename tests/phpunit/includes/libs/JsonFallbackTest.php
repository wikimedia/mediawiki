<?php
/**
 * Tests for the JsonFallback library.
 *
 * Copyright © 2013 Kevin Israel
 *
 * Permission is hereby granted, free of charge, to any person obtaining a copy
 * of this software and associated documentation files (the "Software"), to deal
 * in the Software without restriction, including without limitation the rights
 * to use, copy, modify, merge, publish, distribute, sublicense, and/or sell
 * copies of the Software, and to permit persons to whom the Software is
 * furnished to do so, subject to the following conditions:
 *
 * The above copyright notice and this permission notice shall be included in
 * all copies or substantial portions of the Software.
 *
 * THE SOFTWARE IS PROVIDED "AS IS", WITHOUT WARRANTY OF ANY KIND, EXPRESS OR
 * IMPLIED, INCLUDING BUT NOT LIMITED TO THE WARRANTIES OF MERCHANTABILITY,
 * FITNESS FOR A PARTICULAR PURPOSE AND NONINFRINGEMENT. IN NO EVENT SHALL THE
 * AUTHORS OR COPYRIGHT HOLDERS BE LIABLE FOR ANY CLAIM, DAMAGES OR OTHER
 * LIABILITY, WHETHER IN AN ACTION OF CONTRACT, TORT OR OTHERWISE, ARISING FROM,
 * OUT OF OR IN CONNECTION WITH THE SOFTWARE OR THE USE OR OTHER DEALINGS IN
 * THE SOFTWARE.
 *
 * @file
 * @ingroup JsonFallback
 */

class JsonFallbackTest extends MediaWikiTestCase {

	public function testNegativeZeroFloats() {
		// HipHop VM doesn't appear to have negative zero
		if ( (string)(float)'-0' !== '-0' ) {
			$this->markTestSkipped( 'This test is irrelevant to your version of PHP' );
		}

		$this->assertSame( '-0', (string)JsonFallback::decode( '-0.0' ) );
		$this->assertSame( '-0', (string)JsonFallback::decode( '-0e1' ) );
		$this->assertSame( array( '-0', '-0' ), array_map( 'strval',
			JsonFallback::decode( '[-0.0,-0e1]' ) ) );

		$this->assertSame( '-0', JsonFallback::encode( '-0.0', JsonFallback::NUMERIC_CHECK ) );
		$this->assertSame( '-0', JsonFallback::encode( '-0e1', JsonFallback::NUMERIC_CHECK ) );
		$this->assertSame( '[-0,-0]',
			JsonFallback::encode( array( '-0.0', '-0e1' ), JsonFallback::NUMERIC_CHECK ) );
	}

	public function testPositiveZeroFloats() {
		$this->assertSame( '0', (string)JsonFallback::decode( '0.0' ) );
		$this->assertSame( '0', (string)JsonFallback::decode( '0e1' ) );
		$this->assertSame( array( '0', '0' ), array_map( 'strval',
			JsonFallback::decode( '[0.0,0e1]' ) ) );

		$this->assertSame( '0', JsonFallback::encode( '0.0', JsonFallback::NUMERIC_CHECK ) );
		$this->assertSame( '0', JsonFallback::encode( '0e1', JsonFallback::NUMERIC_CHECK ) );
		$this->assertSame( '[0,0]',
			JsonFallback::encode( array( '0.0', '0e1' ), JsonFallback::NUMERIC_CHECK ) );
	}

	public function testZeroInts() {
		$this->assertSame( 0, JsonFallback::decode( '0' ) );
		$this->assertSame( 0, JsonFallback::decode( '-0' ) );
		$this->assertSame( array( 0, 0 ), JsonFallback::decode( '[0,-0]' ) );

		$this->assertSame( '0', JsonFallback::encode( '0', JsonFallback::NUMERIC_CHECK ) );
		$this->assertSame( '0', JsonFallback::encode( '-0', JsonFallback::NUMERIC_CHECK ) );
		$this->assertSame( '[0,0]',
			JsonFallback::encode( array( '0', '-0' ), JsonFallback::NUMERIC_CHECK ) );

	}

	public static function provideInvalidUtf8() {
		return array(
			// Unexpected continuation byte
			array( "\x80" ),
			array( "\xc3\xa9\x80" ),

			// 2-byte sequences
			array( "\xc0\x80" ), // Overlong form of U+0000
			array( "\xc0\xbf" ), // Overlong form of U+003F
			array( "\xc1\x80" ), // Overlong form of U+0040
			array( "\xc1\xbf" ), // Overlong form of U+007F
			array( "\xc3" ),
			array( "\xc3\x7f" ),

			// 3-byte sequences
			array( "\xe0\x9f\x80" ), // Overlong form of U+0000
			array( "\xe0" ),
			array( "\xe0\xa4" ),
			array( "\xe0\x7f\x89" ),
			array( "\xe0\xa4\x7f" ),
			array( "\xed\xa0\x80" ), // Surrogate U+D800
			array( "\xed\xbf\xbf" ), // Surrogate U+DFFF

			// 4-byte sequences
			array( "\xf0\x8f\x80\x80" ), // Overlong form of U+0000
			array( "\xf0" ),
			array( "\xf0\x9d" ),
			array( "\xf0\x9d\x92" ),
			array( "\xf0\x7f\x92\x9e" ),
			array( "\xf0\x9d\x7f\x9e" ),
			array( "\xf0\x9d\x92\x7f" ),
			array( "\xf4\x90\x80\x80"), // U+110000

			// 5-byte sequences, never allowed
			array( "\xf8\x80\x80\x80\x80" ),
		);
	}

	/**
	 * @dataProvider provideInvalidUtf8
	 */
	public function testInvalidUtf8RejectedInEncoder( $from ) {
		$this->assertSame( false, JsonFallback::encode( "\"$from\"" ) );
		$this->assertSame( JsonFallback::ERROR_UTF8, JsonFallback::last_error() );
		$this->assertSame( 'null',
			JsonFallback::encode( "\"$from\"", JsonFallback::PARTIAL_OUTPUT_ON_ERROR ) );
	}

	/**
	 * @dataProvider provideInvalidUtf8
	 */
	public function testInvalidUtf8RejectedInDecoder( $from ) {
		$this->assertNull( JsonFallback::decode( "\"$from\"" ) );
		$this->assertSame( JsonFallback::ERROR_UTF8, JsonFallback::last_error() );
	}

	public static function provideValidUnescapedStrings() {
		return array(
			array( "\0", '\u0000' ),
			array( "\x1f", '\u001f' ),
			array( "\x08", '\b' ),
			array( "\t", '\t' ),
			array( "\n", '\n' ),
			array( "\f", '\f' ),
			array( "\r", '\r' ),
			array( ' ', ' ' ),
			array( '"', '\"' ),
			array( '/', '\/' ),
			array( '\\', '\\\\' ),
			array( "\x7f", "\x7f" ),
			array( "\xc2\x80", '\u0080' ),
			array( "\xc3\xa9", '\u00e9' ),
			array( "\xe0\xa4\x89", '\u0909' ),
			array( "\xe0\xa4\x890", '\u09090' ),
			array( "\xef\xbf\xbf", '\uffff' ),
			array( "\xf0\x9d\x92\x9e", '\ud835\udc9e' ), // U+1D49E
			array( "\xf0\x9f\x94\x85", '\ud83d\udd05' ), // U+1F505
			array( "\xf3\xa0\x87\xaf", '\udb40\uddef' ), // U+E01EF
			array( "\xf4\x8f\xbf\xbd", '\udbff\udffd' ), // U+10FFFD (highest character)
			array( "\xf4\x8f\xbf\xbf", '\udbff\udfff' ), // U+10FFFF (highest code point)
		);
	}

	/**
	 * @dataProvider provideValidUnescapedStrings
	 */
	public function testStringEncode( $from, $to ) {
		$this->assertSame( "\"$to\"", JsonFallback::encode( $from ) );
	}

	public function testHexQuot() {
		$this->assertSame( '"\""', JsonFallback::encode( '"' ) );
		$this->assertSame( '"\u0022"', JsonFallback::encode( '"', JsonFallback::HEX_QUOT ) );
	}

	public function testHexAmp() {
		$this->assertSame( '"&"', JsonFallback::encode( '&' ) );
		$this->assertSame( '"\u0026"', JsonFallback::encode( '&', JsonFallback::HEX_AMP ) );
	}

	public function testHexTag() {
		$this->assertSame( '"<>"', JsonFallback::encode( '<>' ) );
		$this->assertSame( '"\u003C\u003E"', JsonFallback::encode( '<>', JsonFallback::HEX_TAG ) );
	}

	public function testHexApos() {
		$this->assertSame( '"\'"', JsonFallback::encode( "'" ) );
		$this->assertSame( '"\u0027"', JsonFallback::encode( "'", JsonFallback::HEX_APOS ) );
	}

	public function testUnescapedSlashes() {
		$this->assertSame( '"\/"', JsonFallback::encode( '/' ) );
		$this->assertSame( '"/"', JsonFallback::encode( '/', JsonFallback::UNESCAPED_SLASHES ) );
	}

	public function testUnescapedUnicode() {
		$this->assertSame( '"\u00e9"', JsonFallback::encode( "\xc3\xa9" ) );
		$this->assertSame( "\"\xc3\xa9\"",
			JsonFallback::encode( "\xc3\xa9", JsonFallback::UNESCAPED_UNICODE ) );
	}

	public function testPrettyPrint() {
		$obj = array(
			'emptyObject' => new stdClass,
			'emptyArray' => array(),
			'string' => 'foobar\\',
			'filledArray' => array(
				array(
					123,
					456,
				),
				'"7":["8",{"9":"10"}]',
			),
		);

		// 4 space indent, no trailing whitespace, no trailing linefeed
		$json = '{
    "emptyObject": {

    },
    "emptyArray": [

    ],
    "string": "foobar\\\\",
    "filledArray": [
        [
            123,
            456
        ],
        "\"7\":[\"8\",{\"9\":\"10\"}]"
    ]
}';

		$json = str_replace( "\r", '', $json ); // Windows compat
		$this->assertSame( $json, JsonFallback::encode( $obj, JsonFallback::PRETTY_PRINT ) );
	}

	public function testSimpleKeywordEncode() {
		$this->assertSame( 'true', JsonFallback::encode( true ) );
		$this->assertSame( 'false', JsonFallback::encode( false ) );
		$this->assertSame( 'null', JsonFallback::encode( null ) );
	}

	public function testNonzeroIntEncode() {
		$this->assertSame( '42', JsonFallback::encode( 42 ) );
		$this->assertSame( (string)PHP_INT_MAX, JsonFallback::encode( PHP_INT_MAX ) );
		$min = -1 - PHP_INT_MAX;
		$this->assertSame( (string)$min, JsonFallback::encode( $min ) );
	}

	public function testNonzeroFloatEncode() {
		$this->assertSame( '3.14', JsonFallback::encode( 3.14 ) );
		$this->assertSame( '1.0e+20', JsonFallback::encode( 1e20 ) );
		$this->assertSame( '1.0e-20', JsonFallback::encode( 1e-20 ) );
	}

	public static function provideNonfiniteFloats() {
		return array(
			array( INF ),
			array( -INF ),
			array( NAN ),
		);
	}

	/**
	 * @dataProvider provideNonfiniteFloats
	 */
	public function testNonfiniteFloatEncode( $n ) {
		$this->assertSame( false, JsonFallback::encode( $n ) );
		$this->assertSame( JsonFallback::ERROR_INF_OR_NAN, JsonFallback::last_error() );
		$this->assertSame( '0', JsonFallback::encode( $n, JsonFallback::PARTIAL_OUTPUT_ON_ERROR ) );
	}

	public function testNumericCheckDetectsFiniteFloats() {
		$this->assertSame( '3.14', JsonFallback::encode( '3.14', JsonFallback::NUMERIC_CHECK ) );
		$this->assertSame( '3.14', JsonFallback::encode( '003.14', JsonFallback::NUMERIC_CHECK ) );
		$this->assertSame( '1.0e+20', JsonFallback::encode( '1e20', JsonFallback::NUMERIC_CHECK ) );
		$this->assertSame( '1.0e-20', JsonFallback::encode( '1e-20', JsonFallback::NUMERIC_CHECK ) );
	}

	public function testNumericCheckIgnoresNonfiniteFloats() {
		$this->assertSame( '"INF"', JsonFallback::encode( 'INF', JsonFallback::NUMERIC_CHECK ) );
		$this->assertSame( '"-INF"', JsonFallback::encode( '-INF', JsonFallback::NUMERIC_CHECK ) );
		$this->assertSame( '"NAN"', JsonFallback::encode( 'NAN', JsonFallback::NUMERIC_CHECK ) );
	}

	public function testObjectCycleCheck() {
		$a = new stdClass;
		$b = new stdClass;
		$b->a = $a;
		$a->b = $b;
		$this->assertSame( false, JsonFallback::encode( $a ) );
		$this->assertSame( JsonFallback::ERROR_RECURSION, JsonFallback::last_error() );
	}

	public function testEncoderDepthCheck() {
		$this->assertSame( '1', JsonFallback::encode( 1, 0, 0 ) );

		$this->assertSame( false, JsonFallback::encode( array(), 0, 0 ) );
		$this->assertSame( JsonFallback::ERROR_DEPTH, JsonFallback::last_error() );

		$this->assertSame( '[]', JsonFallback::encode( array(), 0, 1 ) );

		$this->assertSame( '[1]', JsonFallback::encode( array( 1 ), 0, 1 ) );

		$this->assertSame( false, JsonFallback::encode( array( array() ), 0, 1 ) );
		$this->assertSame( JsonFallback::ERROR_DEPTH, JsonFallback::last_error() );
		$this->assertSame( '[[]]',
			JsonFallback::encode( array( array() ), JsonFallback::PARTIAL_OUTPUT_ON_ERROR, 1 ) );
	}

	public function testEncoderUnsupportedType() {
		$res = fopen( 'php://memory', 'r+' );
		$this->assertSame( false, JsonFallback::encode( array( $res ) ) );
		$this->assertSame( JsonFallback::ERROR_UNSUPPORTED_TYPE, JsonFallback::last_error() );
		$this->assertSame( '[null]',
			JsonFallback::encode( array( $res ), JsonFallback::PARTIAL_OUTPUT_ON_ERROR ) );
	}

	public function testBasicObjectEncoding() {
		$this->assertSame( '{}', JsonFallback::encode( new stdClass ) );

		$a = new stdClass;
		$a->{0} = 'a';
		$this->assertSame( '{"0":"a"}', JsonFallback::encode( $a ) );

		$a->{1} = 'b';
		$this->assertSame( '{"0":"a","1":"b"}', JsonFallback::encode( $a ) );
	}

	public function testPrivateProtectedPropertiesIgnored() {
		$this->assertSame( '{"abc":"def"}', JsonFallback::encode( new JsonFallbackTestFoo ) );
	}

	public function testIntegerPropertiesTolerated() {
		$this->assertSame( '{"0":"foo"}', JsonFallback::encode( (object)array( 'foo' ) ) );
	}

	public function testIllegalPropertiesIgnored() {
		$this->assertSame( '{"abc":"def"}', JsonFallback::encode( (object)array(
			'' => 'empty',
			'abc' => 'def',
			"\0" => 'null',
			"\0junk" => 'nulljunk',
		) ) );
	}

	public function testJsonSerializeReturningThis() {
		$this->assertSame( '{"abc":"def"}', JsonFallback::encode( new JsonFallbackTestBar ) );
	}

	public function testJsonSerializeReturningArray() {
		$this->assertSame( '{"abc":"def"}', JsonFallback::encode( new JsonFallbackTestBaz ) );
	}

	public function testJsonSerializeReturningOtherObject() {
		$this->assertSame( '{"abc":"def"}', JsonFallback::encode( new JsonFallbackTestQuux ) );
	}

	public function testArrayEncoding() {
		$this->assertSame( '[]', JsonFallback::encode( array() ) );
		$this->assertSame( '[123]', JsonFallback::encode( array( 123 ) ) );
		$this->assertSame( '{"1":23}', JsonFallback::encode( array( 1 => 23 ) ) );
		$this->assertSame( '{"0":123,"4":56}', JsonFallback::encode( array( 0 => 123, 4 => 56 ) ) );
	}

	public function testWrongTypedArrayKeysTolerated() {
		$obj = new stdClass;
		$obj->{0} = 'foo';
		$this->assertSame( '{"0":"foo"}', JsonFallback::encode( (array)$obj ) );
	}

	public function testInvalidUtf8InKey() {
		$a = array( "\xff" => 1 );

		$this->assertSame( false, JsonFallback::encode( $a ) );
		$this->assertSame( JsonFallback::ERROR_UTF8, JsonFallback::last_error() );

		$this->assertSame( '{null:1}',
			JsonFallback::encode( $a, JsonFallback::PARTIAL_OUTPUT_ON_ERROR ) );
	}

	public function testForceObject() {
		$this->assertSame( '{"0":{},"1":"foo"}',
			JsonFallback::encode( array( array(), 'foo' ), JsonFallback::FORCE_OBJECT ) );
	}

	public function testWhitespaceAcceptedBeforeEndToken() {
		$this->assertEquals( new stdClass, JsonFallback::decode( '{ }' ) );
		$this->assertSame( array(), JsonFallback::decode( '[ ]' ) );
	}

	public function testEmptyStringDecodedAsNull() {
		JsonFallback::encode( 1 ); // Clear any error
		$this->assertNull( JsonFallback::decode( '' ) );
		$this->assertSame( JsonFallback::ERROR_NONE, JsonFallback::last_error() );
	}


	public function testSimpleKeywordDecode() {
		$this->assertSame( true, JsonFallback::decode( 'true' ) );
		$this->assertSame( false, JsonFallback::decode( 'false' ) );
		$this->assertNull( JsonFallback::decode( 'null' ) );
		$this->assertSame( JsonFallback::ERROR_NONE, JsonFallback::last_error() );

		$this->assertSame( true, JsonFallback::decode( 'tRUe' ) );
		$this->assertSame( false, JsonFallback::decode( 'fAlSe' ) );
		$this->assertNull( JsonFallback::decode( 'NuLL' ) );
		$this->assertSame( JsonFallback::ERROR_NONE, JsonFallback::last_error() );

		JsonFallback::decode( ' true' );
		$this->assertSame( JsonFallback::ERROR_SYNTAX, JsonFallback::last_error() );
		JsonFallback::decode( ' false' );
		$this->assertSame( JsonFallback::ERROR_SYNTAX, JsonFallback::last_error() );
		JsonFallback::decode( ' null' );
		$this->assertSame( JsonFallback::ERROR_SYNTAX, JsonFallback::last_error() );

		JsonFallback::decode( 'true ' );
		$this->assertSame( JsonFallback::ERROR_SYNTAX, JsonFallback::last_error() );
		JsonFallback::decode( 'false ' );
		$this->assertSame( JsonFallback::ERROR_SYNTAX, JsonFallback::last_error() );
		JsonFallback::decode( 'null ' );
		$this->assertSame( JsonFallback::ERROR_SYNTAX, JsonFallback::last_error() );
	}

	public function testSimpleKeywordInStructureDecode() {
		$this->assertSame( array( true ), JsonFallback::decode( '[true]' ) );
		$this->assertSame( array( false ), JsonFallback::decode( '[false]' ) );
		$this->assertSame( array( null ), JsonFallback::decode( '[null]' ) );

		JsonFallback::decode( '[tRUe]' );
		$this->assertSame( JsonFallback::ERROR_SYNTAX, JsonFallback::last_error() );
		JsonFallback::decode( '[fAlSe]' );
		$this->assertSame( JsonFallback::ERROR_SYNTAX, JsonFallback::last_error() );
		JsonFallback::decode( '[NuLL]' );
		$this->assertSame( JsonFallback::ERROR_SYNTAX, JsonFallback::last_error() );
	}

	public function testVerticalTabBeforeLoneNumberAccepted() {
		$this->assertSame( 123, JsonFallback::decode( "\v123" ) );
	}

	public function testWhitespaceAfterLoneNumberRejected() {
		$this->assertSame( null, JsonFallback::decode( '123 ' ) );
		$this->assertSame( JsonFallback::ERROR_SYNTAX, JsonFallback::last_error() );
	}

	public function testDecodeObject() {
		$arr = array( 'foo', 'bar' );
		$obj = new stdClass;
		$obj->{0} = 'foo';
		$obj->{1} = 'bar';

		$this->assertSame( $arr, JsonFallback::decode( '{"0":"foo","1":"bar"}', true ) );
		$this->assertEquals( $obj, JsonFallback::decode( '{"0":"foo","1":"bar"}' ) );
	}

	public function testDecodeEmptyKey() {
		$this->assertSame( array( '' => 'foo' ), JsonFallback::decode( '{"":"foo"}', true ) );
	}

	public function testDecodeEmptyProperty() {
		$obj = new stdClass;
		$obj->_empty_ = 'foo';
		$this->assertEquals( $obj, JsonFallback::decode( '{"":"foo"}' ) );
	}

	public function testBigintAsString() {
		$min = -1 - PHP_INT_MAX; // int
		$maxPlus1 = substr( $min, 1 ); // string

		$res = JsonFallback::decode( PHP_INT_MAX, false, 512, JsonFallback::BIGINT_AS_STRING );
		$this->assertSame( PHP_INT_MAX, $res );

		$res = JsonFallback::decode( $min, false, 512, JsonFallback::BIGINT_AS_STRING );
		$this->assertSame( $min, $res );

		$res = JsonFallback::decode( $maxPlus1, false, 512, JsonFallback::BIGINT_AS_STRING );
		$this->assertSame( $maxPlus1, $res );

		$res = JsonFallback::decode( '[' . PHP_INT_MAX . ']', false, 512,
			JsonFallback::BIGINT_AS_STRING );
		$this->assertSame( PHP_INT_MAX, $res[0] );

		$res = JsonFallback::decode( "[$min]", false, 512, JsonFallback::BIGINT_AS_STRING );
		$this->assertSame( $min, $res[0] );

		$res = JsonFallback::decode( "[$maxPlus1]", false, 512, JsonFallback::BIGINT_AS_STRING );
		$this->assertSame( $maxPlus1, $res[0] );
	}

	public static function provideMalformedJson() {
		$syntax = JsonFallback::ERROR_SYNTAX;
		$state = JsonFallback::ERROR_STATE_MISMATCH;

		return array(
			array( '"', $syntax ),
			array( '"\\', $syntax ),
			array( '"\\u', $syntax ),
			array( "[", $syntax ),
			array( '[1,]', $syntax ),
			array( '[1][1]', $syntax ),
			array( '[1],[1]', $syntax ),
			array( '{"a":1,}', $syntax ),
			array( '{"a":1}{"a":1}', $syntax ),
			array( '{"a":1},{"a":1}', $syntax ),
			array( '{"a"1}', $syntax ),
			array( '{]', $syntax ),
			array( '[}', $syntax ),
			array( '[1,}', $syntax ),
			array( '{"a":1,]', $syntax ),
			array( '{"a":1e}', $syntax ),
			array( '{"a":1 1}', $syntax ),
			array( '[t', $syntax ),
			array( '[f', $syntax ),
			array( '[n', $syntax ),
			array( '[,]', $syntax ),
			array( '[1e]', $syntax ),
			array( '[1 1]', $syntax ),
			array( '[01]', $syntax ),
			array( "[\v]", $syntax ),
			array( '{}}', $state ),
			array( '{}]', $state ),
			array( '[]]', $state ),
			array( '[1}', $state ),
			array( '{"a":1]', $state ),
			array( '""}', $state ),
			array( '""]', $state ),
		);
	}

	/**
	 * @dataProvider provideMalformedJson
	 */
	public function testDecodeMalformedJson( $from, $to ) {
		JsonFallback::decode( $from );
		$this->assertSame( $to, JsonFallback::last_error() );
	}

	public static function provideValidEscapedStrings() {
		return array(
			array( '\u0000', "\0" ),
			array( '\u001f', "\x1f" ),
			array( '\u001F', "\x1f" ),
			array( '\b', "\x08" ),
			array( '\t', "\t" ),
			array( '\n', "\n" ),
			array( '\f', "\f" ),
			array( '\r', "\r" ),
			array( ' ', ' ' ),
			array( '\"', '"' ),
			array( '\/', '/' ),
			array( '\\\\', '\\' ),
			array( '\u007f', "\x7f" ),
			array( '\u0080', "\xc2\x80" ),
			array( '\u00e9', "\xc3\xa9" ),
			array( '\u0909', "\xe0\xa4\x89" ),
			array( '\u09090', "\xe0\xa4\x890" ),
			array( '\uffff', "\xef\xbf\xbf" ),
			array( '\ud835\udc9e', "\xf0\x9d\x92\x9e" ), // U+1D49E
			array( '\ud835\uDc9e', "\xf0\x9d\x92\x9e" ), // U+1D49E again
			array( '\ud83d\udd05', "\xf0\x9f\x94\x85" ), // U+1F505
			array( '\udb40\uddef', "\xf3\xa0\x87\xaf" ), // U+E01EF
			array( '\udbff\udffd', "\xf4\x8f\xbf\xbd" ), // U+10FFFD (highest character)
			array( '\udbff\udfff', "\xf4\x8f\xbf\xbf" ), // U+10FFFF (highest code point)

			// Unescaped code points U+0020 and above (except " and \) allowed too
			array( '/', '/' ),
			array( "\x7f", "\x7f" ),
			array( "\xc2\x80", "\xc2\x80" ),
			array( "\xc3\xa9", "\xc3\xa9" ),
			array( "\xf0\x9d\x92\x9e", "\xf0\x9d\x92\x9e" ), // U+1D49E
			array( "\xf4\x8f\xbf\xbf", "\xf4\x8f\xbf\xbf" ), // U+10FFFF (highest code point)

			// Unpaired surrogates
			array( '\ud835', "\xed\xa0\xb5" ),
			array( '\udc9e', "\xed\xb2\x9e" ),
			array( '\ud835\u0000', "\xed\xa0\xb5\x00" ),
			array( '\ud835\ud835', "\xed\xa0\xb5\xed\xa0\xb5" ),
			array( '\udc9e\ud835', "\xed\xb2\x9e\xed\xa0\xb5" ),
		);
	}

	/**
	 * @dataProvider provideValidEscapedStrings
	 */
	public function testValidStringsAccepted( $from, $to ) {
		$this->assertSame( $to, JsonFallback::decode( "\"$from\"" ) );
	}

	public static function provideSyntacticallyInvalidStrings() {
		return array(
			// Control characters that must always be escaped
			array( "\0" ),
			array( "\x1f" ),
			array( "\x08" ),
			array( "\t" ),
			array( "\n" ),
			array( "\f" ),
			array( "\r" ),
			array( "a\n" ),

			// JSON tokens
			array( '"' ),
			array( '\\' ),

			// Invalid two-character escapes
			array( '\v' ),
			array( '\?' ),

			// Invalid hex escapes
			array( '\u000' ),
			array( '\u1xyz' ),
			array( '\u-100' ),
			array( "\\u00e\0" ),
			array( '\u000/' ), // Just below 0
			array( '\u000:' ), // Just above 9
			array( '\u000@' ), // Just below A
			array( '\u000G' ), // Just above F
			array( '\u000`' ), // Just below a
			array( '\u000g' ), // Just above f

			// Same for when a low surrogate is parsed
			array( '\ud800\u000' ),
			array( '\ud800\u1xyz' ),
			array( '\ud800\u-100' ),
			array( "\\ud800\\u00e\0" ),
			array( '\ud800\u000/' ), // Just below 0
			array( '\ud800\u000:' ), // Just above 9
			array( '\ud800\u000@' ), // Just below A
			array( '\ud800\u000G' ), // Just above F
			array( '\ud800\u000`' ), // Just below a
			array( '\ud800\u000g' ), // Just above f
		);
	}

	/**
	 * @dataProvider provideSyntacticallyInvalidStrings
	 */
	public function testSyntacticallyInvalidStringsRejected( $from ) {
		JsonFallback::decode( '1' );
		$this->assertNull( JsonFallback::decode( "\"$from\"" ) );
		$this->assertSame( JsonFallback::ERROR_SYNTAX, JsonFallback::last_error() );
	}

	public function testDecoderDepthCheck() {
		$this->assertSame( 'foo', JsonFallback::decode( '"foo"', true, 1 ) );
		$this->assertNull( JsonFallback::decode( '[]', true, 1 ) );
		$this->assertSame( JsonFallback::ERROR_DEPTH, JsonFallback::last_error() );
		$this->assertSame( array(), JsonFallback::decode( '[]', true, 2 ) );
		$this->assertSame( array( 'foo' ), JsonFallback::decode( '["foo"]', true, 2 ) );
		$this->assertNull( JsonFallback::decode( '[[]]', true, 2 ) );
		$this->assertSame( JsonFallback::ERROR_DEPTH, JsonFallback::last_error() );
		$this->assertSame( array( array() ), JsonFallback::decode( '[[]]', true, 3 ) );
	}

}

/**
 * Test class having properties with various visibilities.
 */
class JsonFallbackTestFoo {
	public $abc = 'def';
	protected $ghi = 'jkl';
	private $jkl = 'mno';
}

/**
 * Test class having a jsonSerialize() method that returns the object itself.
 */
class JsonFallbackTestBar implements JsonSerializable {
	public $abc = 'def';

	public function jsonSerialize() {
		return $this;
	}
}

/**
 * Test class having a jsonSerialize() method that returns an array.
 */
class JsonFallbackTestBaz implements JsonSerializable {
	public function jsonSerialize() {
		return array( 'abc' => 'def' );
	}
}

/**
 * Test class having a jsonSerialize() method that returns a different object, itself
 * having its own jsonSerialize() method.
 */
class JsonFallbackTestQuux implements JsonSerializable {
	public function jsonSerialize() {
		return new JsonFallbackTestBar;
	}
}
