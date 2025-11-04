<?php

namespace MediaWiki\Tests\Unit\Json;

use MediaWiki\Json\FormatJson;
use MediaWikiUnitTestCase;

/**
 * @covers \MediaWiki\Json\FormatJson
 */
class FormatJsonTest extends MediaWikiUnitTestCase {

	public static function provideEncoderPrettyPrinting() {
		return [
			// Four spaces
			[ true, '    ' ],
			[ '    ', '    ' ],
			// Two spaces
			[ '  ', '  ' ],
			// One tab
			[ "\t", "\t" ],
			// Empty string
			[ '', '' ],
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
				"    []",
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
		"{\n\t\"emptyObject\": {\n\t},\n\t\"emptyArray\": [ ]\n}",
		"    []"
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
			strtolower( FormatJson::encode( "\u{20000}" ) ),
			'Test encoding of broken json_encode character (U+20000)'
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
	 * @param array|?scalar $value
	 * @return stdClass|?scalar
	 */
	public static function toObject( $value ) {
		return !is_array( $value ) ? $value : (object)array_map( __METHOD__, $value );
	}

	/**
	 * @dataProvider provideParse
	 */
	public function testParse( $value ) {
		$expected = self::toObject( $value );
		$json = FormatJson::encode( $expected, false, FormatJson::ALL_OK );
		$this->assertJson( $json );

		$st = FormatJson::parse( $json );
		$this->assertStatusGood( $st );
		$this->assertStatusValue( $expected, $st );

		$st = FormatJson::parse( $json, FormatJson::FORCE_ASSOC );
		$this->assertStatusGood( $st );
		$this->assertStatusValue( $value, $st );
	}

	public static function provideParseErrors() {
		return [
			[ 'aaa', 'json-error-syntax' ],
			[ '{"j": 1 ] }', 'json-error-state-mismatch' ],
			[ chr( 0 ), 'json-error-ctrl-char' ],
			[ '"\ud834"', 'json-error-utf16' ],
			[ '{"\u0000":1}', 'json-error-invalid-property-name' ],
		];
	}

	/**
	 * @dataProvider provideParseErrors
	 */
	public function testParseErrors( $value, $error ) {
		$this->assertStatusError( $error, FormatJson::parse( $value ) );
	}

	public static function provideStripComments() {
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
	 * @covers \MediaWiki\Json\FormatJson::stripComments
	 * @dataProvider provideStripComments
	 * @param string $json
	 * @param string $expect
	 */
	public function testStripComments( $json, $expect ) {
		$this->assertSame( $expect, FormatJson::stripComments( $json ) );
	}

	public static function provideParseStripComments() {
		return [
			[ '/* blah */true', true ],
			[ "// blah \ntrue", true ],
			[ '[ "a" , /* blah */ "b" ]', [ 'a', 'b' ] ],
		];
	}

	/**
	 * @covers \MediaWiki\Json\FormatJson::parse
	 * @covers \MediaWiki\Json\FormatJson::stripComments
	 * @dataProvider provideParseStripComments
	 */
	public function testParseStripComments( $json, $expect ) {
		$st = FormatJson::parse( $json, FormatJson::STRIP_COMMENTS );
		$this->assertStatusGood( $st );
		$this->assertStatusValue( $expect, $st );
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
				"\u{2028}" => '\u2028',
				"\u{2029}" => '\u2029',
			],
			'unicode' => [
				"\u{00E9}" => '\u00e9',
				"\u{1D49E}" => '\ud835\udc9e', // U+1D49E, outside the BMP
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

	public static function provideEmptyJsonKeyStrings() {
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
	 * @covers \MediaWiki\Json\FormatJson::encode
	 * @covers \MediaWiki\Json\FormatJson::decode
	 * @dataProvider provideEmptyJsonKeyStrings
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

	/**
	 * Test data for testParseTryFixing.
	 *
	 * Some PHP interpreters use json-c rather than the JSON.org canonical
	 * parser to avoid being encumbered by the "shall be used for Good, not
	 * Evil" clause of the JSON.org parser's license. By default, json-c
	 * parses in a non-strict mode which allows trailing commas for array and
	 * object delarations among other things, so our JSON_ERROR_SYNTAX rescue
	 * block is not always triggered. It however isn't lenient in exactly the
	 * same ways as our TRY_FIXING mode, so the assertions in this test are
	 * a bit more complicated than they ideally would be:
	 *
	 * Optional third argument: true if json-c parses the value without
	 * intervention, false otherwise. Defaults to true.
	 *
	 * Optional fourth argument: expected cannonical JSON serialization of
	 * json-c parsed result. Defaults to the second argument's value.
	 */
	public static function provideParseTryFixing() {
		return [
			[ "[,]", '[]', false ],
			[ "[ , ]", '[]', false ],
			[ "[ , }", false ],
			[ '[1],', false, true, '[1]' ],
			[ "[1,]", '[1]' ],
			[ "[1\n,]", '[1]' ],
			[ "[1,\n]", '[1]' ],
			[ "[1,]\n", '[1]' ],
			[ "[1\n,\n]\n", '[1]' ],
			[ '["a,",]', '["a,"]' ],
			[ "[[1,]\n,[2,\n],[3\n,]]", '[[1],[2],[3]]' ],
			// I wish we could parse this, but would need quote parsing
			[ '[[1,],[2,],[3,]]', false, true, '[[1],[2],[3]]' ],
			[ '[1,,]', false, false, '[1]' ],
		];
	}

	/**
	 * @dataProvider provideParseTryFixing
	 * @param string $value
	 * @param string|bool $expected Expected result with strict parser
	 * @param bool $jsoncParses Will json-c parse this value without TRY_FIXING?
	 * @param string|null $expectedJsonc Expected result with lenient parser
	 * if different from the strict expectation
	 */
	public function testParseTryFixing(
		$value, $expected,
		$jsoncParses = true, $expectedJsonc = null
	) {
		// PHP5 results are always expected to have isGood() === false
		$expectedGoodStatus = false;

		// Check to see if json parser allows trailing commas
		if ( json_decode( '[1,]' ) !== null ) {
			// Use json-c specific expected result if provided
			$expected = ( $expectedJsonc === null ) ? $expected : $expectedJsonc;
			// If json-c parses the value natively, expect isGood() === true
			$expectedGoodStatus = $jsoncParses;
		}

		$st = FormatJson::parse( $value, FormatJson::TRY_FIXING );
		if ( $expected === false ) {
			$this->assertStatusNotOK( $st );
		} else {
			if ( $expectedGoodStatus ) {
				$this->assertStatusGood( $st );
			} else {
				$this->assertStatusOK( $st );
			}
			$val = FormatJson::encode( $st->getValue(), false, FormatJson::ALL_OK );
			$this->assertEquals( $expected, $val );
		}
	}
}
