<?php

class JsonTest extends MediaWikiTestCase {

	function testPhpBug46944Test() {
		$this->assertNotEquals(
			'\ud840\udc00',
			strtolower( FormatJson::encode( "\xf0\xa0\x80\x80" ) ),
			'Test encoding an broken json_encode character (U+20000)'
		);

	}

	function testDecodeVarTypes() {
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
	 * @dataProvider provideEncoderDefaultTestCases
	 */
	public function testEncoderDefaultMode( $from, $to ) {
		$this->assertSame( $to, FormatJson::encode( $from ) );
	}

	/**
	 * @dataProvider provideEncoderUtf8OkTestCases
	 */
	public function testEncoderUtf8OkMode( $from, $to ) {
		$this->assertSame( $to, FormatJson::encode( $from, false, FormatJson::UTF8_OK ) );
	}

	/**
	 * @dataProvider provideEncoderXmlMetaOkTestCases
	 */
	public function testEncoderXmlMetaOkMode( $from, $to ) {
		$this->assertSame( $to, FormatJson::encode( $from, false, FormatJson::XMLMETA_OK ) );
	}

	/**
	 * @dataProvider provideEncoderAllOkTestCases
	 */
	public function testEncoderAllOkMode( $from, $to ) {
		$this->assertSame( $to, FormatJson::encode( $from, false, FormatJson::ALL_OK ) );
	}

	public static function provideEncoderDefaultTestCases() {
		return self::provideEncoderTestCases( false, false );
	}

	public static function provideEncoderUtf8OkTestCases() {
		return self::provideEncoderTestCases( false, true );
	}

	public static function provideEncoderXmlMetaOkTestCases() {
		return self::provideEncoderTestCases( true, false );
	}

	public static function provideEncoderAllOkTestCases() {
		return self::provideEncoderTestCases( true, true );
	}

	public static function provideEncoderTestCases( $X, $U ) {
		$rules = array(
			// Forward slash (always unescaped)
			'/' => '/',

			// Unicode characters
			"\xc3\xa9" => $U ?: '\u00e9',
			"\xf0\x9d\x92\x9e" => $U ?: '\ud835\udc9e', // U+1D49E, outside the BMP

			// XML metacharacters
			'<' => $X ?: '\u003C', // JSON_HEX_TAG uses uppercase hex digits
			'>' => $X ?: '\u003E',
			'&' => $X ?: '\u0026',

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
		);

		$cases = array();
		foreach ( $rules as $from => $to ) {
			$cases[] = array( $from, '"' . ( $to === true ? $from : $to ) . '"' );
		}
		return $cases;
	}

	public function testEncoderPrettyPrinting() {
		$obj = array(
			'emptyObject' => new stdClass,
			'emptyArray' => array(),
			'string' => 'foobar',
			'filledArray' => array(
				array(
					123,
					456,
				),
				'"7":["8",{"9":"10"}]',
			),
		);

		$json = implode( "\n", array(
			'{',
			'    "emptyObject": {',
			'', // no trailing whitespace
			'    },',
			'    "emptyArray": [',
			'', // no trailing whitespace
			'    ],',
			'    "string": "foobar",',
			'    "filledArray": [',
			'        [',
			'            123,',
			'            456',
			'        ],',
			'        "\\"7\\":[\\"8\\",{\\"9\\":\\"10\\"}]"',
			'    ]',
			"}",
			// no trailing linefeed
		) );

		$this->assertSame( $json, FormatJson::encode( $obj, true ) );
	}
}
