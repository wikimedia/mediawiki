<?php

use MediaWiki\Tests\Json\JsonUnserializableSuperClass;

/**
 * @covers FormatJson
 */
class FormatJsonTest extends MediaWikiUnitTestCase {

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
	 * @param string|bool $expectedJsonc Expected result with lenient parser
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
		$this->assertInstanceOf( Status::class, $st );
		if ( $expected === false ) {
			$this->assertFalse( $st->isOK(), 'Expected isOK() == false' );
		} else {
			$this->assertSame( $expectedGoodStatus, $st->isGood(),
				'Expected isGood() == ' . ( $expectedGoodStatus ? 'true' : 'false' )
			);
			$this->assertTrue( $st->isOK(), 'Expected isOK == true' );
			$val = FormatJson::encode( $st->getValue(), false, FormatJson::ALL_OK );
			$this->assertEquals( $expected, $val );
		}
	}

	public function provideValidateSerializable() {
		$classInstance = new class() {
		};
		$serializableClass = new class() implements JsonSerializable {
			public function jsonSerialize() {
				return [];
			}
		};

		yield 'Number' => [ 1, true, null ];
		yield 'Null' => [ null, true, null ];
		yield 'Class' => [ $classInstance, false, '$' ];
		yield 'Empty array' => [ [], true, null ];
		yield 'Empty stdClass' => [ new stdClass(), true, null ];
		yield 'Non-empty array' => [ [ 1, 2, 3 ], true, null ];
		yield 'Non-empty map' => [ [ 'a' => 'b' ], true, null ];
		yield 'Nested, serializable' => [ [ 'a' => [ 'b' => [ 'c' => 'd' ] ] ], true, null ];
		yield 'Nested, serializable, with null' => [ [ 'a' => [ 'b' => null ] ], true, null ];
		yield 'Nested, serializable, with stdClass' => [ [ 'a' => (object)[ 'b' => [ 'c' => 'd' ] ] ], true, null ];
		yield 'Nested, serializable, with stdClass, with null' => [ [ 'a' => (object)[ 'b' => null ] ], true, null ];
		yield 'Nested, non-serializable' => [ [ 'a' => [ 'b' => $classInstance ] ], true, '$.a.b' ];
		yield 'Nested, non-serializable, in array' => [ [ 'a' => [ 1, 2, $classInstance ] ], true, '$.a.2' ];
		yield 'Nested, non-serializable, in stdClass' => [ [ 'a' => (object)[ 1, 2, $classInstance ] ], true, '$.a.2' ];
		yield 'JsonUnserializable instance' => [ new JsonUnserializableSuperClass( 'Test' ), true, null ];
		yield 'JsonUnserializable instance, in array' =>
			[ [ new JsonUnserializableSuperClass( 'Test' ) ], true, null ];
		yield 'JsonUnserializable instance, in stdClass' =>
			[ (object)[ new JsonUnserializableSuperClass( 'Test' ) ], true, null ];
		yield 'JsonSerializable instance' => [ $serializableClass, false, null ];
		yield 'JsonSerializable instance, in array' => [ [ $serializableClass ], false, null ];
		yield 'JsonSerializable instance, in stdClass' => [ (object)[ $serializableClass ], false, null ];
		yield 'JsonSerializable instance, expect unserialize' => [ $serializableClass, true, '$' ];
		yield 'JsonSerializable instance, in array, expect unserialize' => [ [ $serializableClass ], true, '$.0' ];
		yield 'JsonSerializable instance, in stdClass, expect unserialize' =>
			[ (object)[ $serializableClass ], true, '$.0' ];
	}

	/**
	 * @dataProvider provideValidateSerializable
	 * @covers FormatJson::detectNonSerializableData
	 * @covers FormatJson::detectNonSerializableDataInternal
	 * @param $value
	 * @param bool $expectUnserialize
	 * @param string|null $result
	 */
	public function testValidateSerializable( $value, bool $expectUnserialize, ?string $result ) {
		$this->assertSame( $result, FormatJson::detectNonSerializableData( $value, $expectUnserialize ) );
	}

}
