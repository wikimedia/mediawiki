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

	public function testEncoderSlashEscaping() {
		$this->assertSame( '"\/"', FormatJson::encode( '/' ) );
		$this->assertSame( '"/"', FormatJson::encode( '/', false, FormatJson::SLASH_OK ) );
	}

	/**
	 * @dataProvider provideUnicodeChars
	 */
	public function testEncoderUnicodeEscaping( $unescaped, $escaped ) {
		$this->assertSame( '"' . $escaped . '"', FormatJson::encode( $unescaped ) );
		$this->assertSame(
			'"' . $unescaped . '"',
			FormatJson::encode( $unescaped, false, FormatJson::UTF8_OK )
		);
	}

	/**
	 * @dataProvider provideXmlMetaChars
	 */
	public function testEncoderXmlMetaEscaping( $unescaped, $escaped ) {
		$this->assertSame( '"' . $escaped . '"', FormatJson::encode( $unescaped ) );
		$this->assertSame(
			'"' . $unescaped . '"',
			FormatJson::encode( $unescaped, false, FormatJson::XMLMETA_OK )
		);
	}

	/**
	 * @dataProvider provideUnconditionallyEscapedChars
	 */
	public function testEncoderUnconditionalEscaping( $unescaped, $escaped ) {
		$this->assertSame( '"' . $escaped . '"', FormatJson::encode( $unescaped ) );
		$this->assertSame(
			'"' . $escaped . '"',
			FormatJson::encode( $unescaped, false, FormatJson::ALL_OK )
		);
	}

	public static function provideUnicodeChars() {
		return array(
			array( "\xc3\xa9", '\u00e9' ),
			array( "\xf0\x9d\x92\x9e", '\ud835\udc9e' ), // U+1D49E, outside the BMP
		);
	}

	public static function provideXmlMetaChars() {
		return array(
			array( '<', '\u003C' ), // JSON_HEX_TAG uses uppercase hex digits
			array( '>', '\u003E' ),
			array( '&', '\u0026' ),
		);
	}

	public static function provideUnconditionallyEscapedChars() {
		return array(
			// Control characters
			array( "\0", '\u0000' ),
			array( "\x08", '\b' ),
			array( "\t", '\t' ),
			array( "\n", '\n' ),
			array( "\r", '\r' ),
			array( "\f", '\f' ),
			array( "\x1f", '\u001f' ), // representative example

			// Double quotes
			array( '"', '\"' ),

			// Backslashes
			array( '\\', '\\\\' ),
			array( '\\\\', '\\\\\\\\' ),
			array( '\\u00e9', '\\\u00e9' ), // security check for Unicode unescaping

			// Line terminators
			array( "\xe2\x80\xa8", '\u2028' ),
			array( "\xe2\x80\xa9", '\u2029' ),
		);
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
