<?php

namespace MediaWiki\Tests\Unit;

use MediaWiki\Content\JsonContent;
use MediaWikiUnitTestCase;

/**
 * Split from \JsonContentTest integration tests
 *
 * @author Addshore
 * @covers \MediaWiki\Content\JsonContent
 */
class JsonContentTest extends MediaWikiUnitTestCase {

	public static function provideValidConstruction() {
		return [
			[ 'foo', false, null ],
			[ '[]', true, [] ],
			[ '{}', true, (object)[] ],
			[ '""', true, '' ],
			[ '"0"', true, '0' ],
			[ '"bar"', true, 'bar' ],
			[ '0', true, '0' ],
			[ '{ "0": "bar" }', true, (object)[ 'bar' ] ],
		];
	}

	/**
	 * @dataProvider provideValidConstruction
	 */
	public function testIsValid( $text, $isValid, $expected ) {
		$obj = new JsonContent( $text, CONTENT_MODEL_JSON );
		$this->assertEquals( $isValid, $obj->isValid() );
		$this->assertEquals( $expected, $obj->getData()->getValue() );
	}

	public static function provideDataToEncode() {
		return [
			[
				// Round-trip empty array
				'[]',
				'[]',
			],
			[
				// Round-trip empty object
				'{}',
				'{}',
			],
			[
				// Round-trip empty array/object (nested)
				'{ "foo": {}, "bar": [] }',
				"{\n\t\"foo\": {},\n\t\"bar\": []\n}",
			],
			[
				'{ "foo": "bar" }',
				"{\n\t\"foo\": \"bar\"\n}",
			],
			[
				'{ "foo": 1000 }',
				"{\n\t\"foo\": 1000\n}",
			],
			[
				'{ "foo": 1000, "0": "bar" }',
				"{\n\t\"foo\": 1000,\n\t\"0\": \"bar\"\n}",
			],
		];
	}

	/**
	 * @dataProvider provideDataToEncode
	 */
	public function testBeautifyJson( $input, $beautified ) {
		$obj = new JsonContent( $input );
		$this->assertEquals( $beautified, $obj->beautifyJSON() );
	}
}
