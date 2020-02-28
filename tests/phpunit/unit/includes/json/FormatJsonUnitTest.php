<?php

/**
 * @covers FormatJson
 */
class FormatJsonUnitTest extends MediaWikiUnitTestCase {

	public static function provideEncoderPrettyPrinting() {
		return [
			// Four spaces
			[ true, '    ' ],
			[ '    ', '    ' ],
			// Two spaces
			[ '  ', '  ' ],
			// One tab
			[ "\t", "\t" ],
		];
	}

	/**
	 * @dataProvider provideEncoderPrettyPrinting
	 */
	public function testEncoderPrettyPrinting( $pretty, $expectedIndent ) {
		$obj = [
			'emptyObject' => (object)[],
			'emptyArray' => [],
			'string' => 'foobar\\',
			'filledArray' => [
				[
					123,
					456,
				],
				// Nested json works without problems
				'"7":["8",{"9":"10"}]',
				// Whitespace clean up doesn't touch strings that look alike
				"{\n\t\"emptyObject\": {\n\t},\n\t\"emptyArray\": [ ]\n}",
			],
		];

		// No trailing whitespace, no trailing linefeed
		$json = '{
	"emptyObject": {},
	"emptyArray": [],
	"string": "foobar\\\\",
	"filledArray": [
		[
			123,
			456
		],
		"\"7\":[\"8\",{\"9\":\"10\"}]",
		"{\n\t\"emptyObject\": {\n\t},\n\t\"emptyArray\": [ ]\n}"
	]
}';

		$json = str_replace( "\r", '', $json ); // Windows compat
		$json = str_replace( "\t", $expectedIndent, $json );
		$this->assertSame( $json, FormatJson::encode( $obj, $pretty ) );
	}

	public static function provideEncodeDefault() {
		return self::getEncodeTestCases( [] );
	}

	/**
	 * @dataProvider provideEncodeDefault
	 */
	public function testEncodeDefault( $from, $to ) {
		$this->assertSame( $to, FormatJson::encode( $from ) );
	}

	public static function provideEncodeUtf8() {
		return self::getEncodeTestCases( [ 'unicode' ] );
	}

	/**
	 * @dataProvider provideEncodeUtf8
	 */
	public function testEncodeUtf8( $from, $to ) {
		$this->assertSame( $to, FormatJson::encode( $from, false, FormatJson::UTF8_OK ) );
	}

	public static function provideEncodeXmlMeta() {
		return self::getEncodeTestCases( [ 'xmlmeta' ] );
	}

	/**
	 * @dataProvider provideEncodeXmlMeta
	 */
	public function testEncodeXmlMeta( $from, $to ) {
		$this->assertSame( $to, FormatJson::encode( $from, false, FormatJson::XMLMETA_OK ) );
	}

	public static function provideEncodeAllOk() {
		return self::getEncodeTestCases( [ 'unicode', 'xmlmeta' ] );
	}

	/**
	 * @dataProvider provideEncodeAllOk
	 */
	public function testEncodeAllOk( $from, $to ) {
		$this->assertSame( $to, FormatJson::encode( $from, false, FormatJson::ALL_OK ) );
	}

	public function testEncodePhpBug46944() {
		$this->assertNotEquals(
			'\ud840\udc00',
			strtolower( FormatJson::encode( "\xf0\xa0\x80\x80" ) ),
			'Test encoding an broken json_encode character (U+20000)'
		);
	}

	public function testEncodeFail() {
		// Set up a recursive object that can't be encoded.
		$a = (object)[];
		$b = (object)[];
		$a->b = $b;
		$b->a = $a;
		$this->assertFalse( FormatJson::encode( $a ) );
	}

	public function testDecodeReturnType() {
		$this->assertIsObject(

			FormatJson::decode( '{"Name": "Cheeso", "Rank": 7}' ),
			'Default to object'
		);

		$this->assertIsArray(

			FormatJson::decode( '{"Name": "Cheeso", "Rank": 7}', true ),
			'Optional array'
		);
	}

	public static function provideParse() {
		return [
			[ null ],
			[ true ],
			[ false ],
			[ 0 ],
			[ 1 ],
			[ 1.2 ],
			[ '' ],
			[ 'str' ],
			[ [ 0, 1, 2 ] ],
			[ [ 'a' => 'b' ] ],
			[ [ 'a' => 'b' ] ],
			[ [ 'a' => 'b', 'x' => [ 'c' => 'd' ] ] ],
		];
	}

	/**
	 * Recursively convert arrays into stdClass
	 * @param array|string|bool|int|float|null $value
	 * @return stdClass|string|bool|int|float|null
	 */
	public static function toObject( $value ) {
		return !is_array( $value ) ? $value : (object)array_map( __METHOD__, $value );
	}

	/**
	 * @dataProvider provideParse
	 * @param mixed $value
	 */
	public function testParse( $value ) {
		$expected = self::toObject( $value );
		$json = FormatJson::encode( $expected, false, FormatJson::ALL_OK );
		$this->assertJson( $json );

		$st = FormatJson::parse( $json );
		$this->assertInstanceOf( Status::class, $st );
		$this->assertTrue( $st->isGood() );
		$this->assertEquals( $expected, $st->getValue() );

		$st = FormatJson::parse( $json, FormatJson::FORCE_ASSOC );
		$this->assertInstanceOf( Status::class, $st );
		$this->assertTrue( $st->isGood() );
		$this->assertEquals( $value, $st->getValue() );
	}

	public static function provideParseErrors() {
		return [
			[ 'aaa', 'json-error-syntax' ],
			[ '{"j": 1 ] }', 'json-error-state-mismatch' ],
			[ chr( 0 ), 'json-error-ctrl-char' ],
			[ '"\ud834"', 'json-error-utf16' ],
		];
	}

	/**
	 * @dataProvider provideParseErrors
	 * @param mixed $value
	 */
	public function testParseErrors( $value, $error ) {
		$st = FormatJson::parse( $value );
		$this->assertInstanceOf( Status::class, $st );
		$this->assertFalse( $st->isOK() );
		$this->assertTrue(
			$st->hasMessage( $error ),
			"Does not have $error message"
		);
	}

	public function provideStripComments() {
		return [
			[ '{"a":"b"}', '{"a":"b"}' ],
			[ "{\"a\":\"b\"}\n", "{\"a\":\"b\"}\n" ],
			[ '/*c*/{"c":"b"}', '{"c":"b"}' ],
			[ '{"a":"c"}/*c*/', '{"a":"c"}' ],
			[ '/*c//d*/{"c":"b"}', '{"c":"b"}' ],
			[ '{/*c*/"c":"b"}', '{"c":"b"}' ],
			[ "/*\nc\r\n*/{\"c\":\"b\"}", '{"c":"b"}' ],
			[ "//c\n{\"c\":\"b\"}", '{"c":"b"}' ],
			[ "//c\r\n{\"c\":\"b\"}", '{"c":"b"}' ],
			[ '{"a":"c"}//c', '{"a":"c"}' ],
			[ "{\"a-c\"://c\n\"b\"}", '{"a-c":"b"}' ],
			[ '{"/*a":"b"}', '{"/*a":"b"}' ],
			[ '{"a":"//b"}', '{"a":"//b"}' ],
			[ '{"a":"b/*c*/"}', '{"a":"b/*c*/"}' ],
			[ "{\"\\\"/*a\":\"b\"}", "{\"\\\"/*a\":\"b\"}" ],
			[ '', '' ],
			[ '/*c', '' ],
			[ '//c', '' ],
			[ '"http://example.com"', '"http://example.com"' ],
			[ "\0", "\0" ],
			[ '"Blåbærsyltetøy"', '"Blåbærsyltetøy"' ],
		];
	}

	/**
	 * @covers FormatJson::stripComments
	 * @dataProvider provideStripComments
	 * @param string $json
	 * @param string $expect
	 */
	public function testStripComments( $json, $expect ) {
		$this->assertSame( $expect, FormatJson::stripComments( $json ) );
	}

	public function provideParseStripComments() {
		return [
			[ '/* blah */true', true ],
			[ "// blah \ntrue", true ],
			[ '[ "a" , /* blah */ "b" ]', [ 'a', 'b' ] ],
		];
	}

	/**
	 * @covers FormatJson::parse
	 * @covers FormatJson::stripComments
	 * @dataProvider provideParseStripComments
	 * @param string $json
	 * @param mixed $expect
	 */
	public function testParseStripComments( $json, $expect ) {
		$st = FormatJson::parse( $json, FormatJson::STRIP_COMMENTS );
		$this->assertInstanceOf( Status::class, $st );
		$this->assertTrue( $st->isGood() );
		$this->assertEquals( $expect, $st->getValue() );
	}

	/**
	 * Generate a set of test cases for a particular combination of encoder options.
	 *
	 * @param array $unescapedGroups List of character groups to leave unescaped
	 * @return array Arrays of unencoded strings and corresponding encoded strings
	 */
	private static function getEncodeTestCases( array $unescapedGroups ) {
		$groups = [
			'always' => [
				// Forward slash (always unescaped)
				'/' => '/',

				// Control characters
				"\0" => '\u0000',
				"\x08" => '\b',
				"\t" => '\t',
				"\n" => '\n',
				"\r" => '\r',
				"\f" => '\f',
				"\x1f" => '\u001f', // representative example

				// Double quotes
				'"' => '\"',

				// Backslashes
				'\\' => '\\\\',
				'\\\\' => '\\\\\\\\',
				'\\u00e9' => '\\\u00e9', // security check for Unicode unescaping

				// Line terminators
				"\xe2\x80\xa8" => '\u2028',
				"\xe2\x80\xa9" => '\u2029',
			],
			'unicode' => [
				"\xc3\xa9" => '\u00e9',
				"\xf0\x9d\x92\x9e" => '\ud835\udc9e', // U+1D49E, outside the BMP
			],
			'xmlmeta' => [
				'<' => '\u003C', // JSON_HEX_TAG uses uppercase hex digits
				'>' => '\u003E',
				'&' => '\u0026',
			],
		];

		$cases = [];
		foreach ( $groups as $name => $rules ) {
			$leaveUnescaped = in_array( $name, $unescapedGroups );
			foreach ( $rules as $from => $to ) {
				$cases[] = [ $from, '"' . ( $leaveUnescaped ? $from : $to ) . '"' ];
			}
		}

		return $cases;
	}

	public function provideEmptyJsonKeyStrings() {
		return [
			[
				'{"":"foo"}',
				'{"":"foo"}',
				''
			],
			[
				'{"_empty_":"foo"}',
				'{"_empty_":"foo"}',
				'_empty_' ],
			[
				'{"\u005F\u0065\u006D\u0070\u0074\u0079\u005F":"foo"}',
				'{"_empty_":"foo"}',
				'_empty_'
			],
			[
				'{"_empty_":"bar","":"foo"}',
				'{"_empty_":"bar","":"foo"}',
				''
			],
			[
				'{"":"bar","_empty_":"foo"}',
				'{"":"bar","_empty_":"foo"}',
				'_empty_'
			]
		];
	}

	/**
	 * @covers FormatJson::encode
	 * @covers FormatJson::decode
	 * @dataProvider provideEmptyJsonKeyStrings
	 * @param string $json
	 *
	 * Decoding behavior with empty keys can be surprising.
	 * See https://phabricator.wikimedia.org/T206411
	 */
	public function testEmptyJsonKeyArray( $json, $expect, $php71Name ) {
		// Decoding to array is consistent across supported PHP versions
		$this->assertSame( $expect, FormatJson::encode(
			FormatJson::decode( $json, true ) ) );

		// Decoding to object differed for PHP versions less than 7.1
		$obj = FormatJson::decode( $json );
		$this->assertEquals( 'foo', $obj->{$php71Name} );
	}
}
