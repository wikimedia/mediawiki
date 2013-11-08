<?php

class FormatJsonTest extends MediaWikiTestCase {

	public function testEncoderPrettyPrinting() {
		$obj = array(
			'emptyObject' => new stdClass,
			'emptyArray' => array(),
			'string' => 'foobar\\',
			'filledArray' => array(
				array(
					123,
					456,
				),
				// Nested json works without problems
				'"7":["8",{"9":"10"}]',
				// Whitespace clean up doesn't touch strings that look alike
				"{\n\t\"emptyObject\": {\n\t},\n\t\"emptyArray\": [ ]\n}",
			),
		);

		// 4 space indent, no trailing whitespace, no trailing linefeed
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
		$this->assertSame( $json, FormatJson::encode( $obj, true ) );
	}

	public static function provideEncodeDefault() {
		return self::getEncodeTestCases( array() );
	}

	/**
	 * @dataProvider provideEncodeDefault
	 */
	public function testEncodeDefault( $from, $to ) {
		$this->assertSame( $to, FormatJson::encode( $from ) );
	}

	public static function provideEncodeUtf8() {
		return self::getEncodeTestCases( array( 'unicode' ) );
	}

	/**
	 * @dataProvider provideEncodeUtf8
	 */
	public function testEncodeUtf8( $from, $to ) {
		$this->assertSame( $to, FormatJson::encode( $from, false, FormatJson::UTF8_OK ) );
	}

	public static function provideEncodeXmlMeta() {
		return self::getEncodeTestCases( array( 'xmlmeta' ) );
	}

	/**
	 * @dataProvider provideEncodeXmlMeta
	 */
	public function testEncodeXmlMeta( $from, $to ) {
		$this->assertSame( $to, FormatJson::encode( $from, false, FormatJson::XMLMETA_OK ) );
	}

	public static function provideEncodeAllOk() {
		return self::getEncodeTestCases( array( 'unicode', 'xmlmeta' ) );
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

	public function testDecodeReturnType() {
		$this->assertInternalType(
			'object',
			FormatJson::decode( '{"Name": "Cheeso", "Rank": 7}' ),
			'Default to object'
		);

		$this->assertInternalType(
			'array',
			FormatJson::decode( '{"Name": "Cheeso", "Rank": 7}', true ),
			'Optional array'
		);
	}

	/**
	 * Generate a set of test cases for a particular combination of encoder options.
	 *
	 * @param array $unescapedGroups List of character groups to leave unescaped
	 * @return array: Arrays of unencoded strings and corresponding encoded strings
	 */
	private static function getEncodeTestCases( array $unescapedGroups ) {
		$groups = array(
			'always' => array(
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
			),
			'unicode' => array(
				"\xc3\xa9" => '\u00e9',
				"\xf0\x9d\x92\x9e" => '\ud835\udc9e', // U+1D49E, outside the BMP
			),
			'xmlmeta' => array(
				'<' => '\u003C', // JSON_HEX_TAG uses uppercase hex digits
				'>' => '\u003E',
				'&' => '\u0026',
			),
		);

		$cases = array();
		foreach ( $groups as $name => $rules ) {
			$leaveUnescaped = in_array( $name, $unescapedGroups );
			foreach ( $rules as $from => $to ) {
				$cases[] = array( $from, '"' . ( $leaveUnescaped ? $from : $to ) . '"' );
			}
		}

		return $cases;
	}
}
