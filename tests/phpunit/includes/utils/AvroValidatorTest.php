<?php
/**
 * Tests for IP validity functions.
 *
 * Ported from /t/inc/IP.t by avar.
 *
 * @todo Test methods in this call should be split into a method and a
 * dataprovider.
 */

/**
 * @group IP
 * @covers AvroValidator
 */
class AvroValidatorTest extends PHPUnit\Framework\TestCase {

	use MediaWikiCoversValidator;

	public function setUp() {
		if ( !class_exists( 'AvroSchema' ) ) {
			$this->markTestSkipped( 'Avro is required to run the AvroValidatorTest' );
		}
		parent::setUp();
	}

	public function getErrorsProvider() {
		$stringSchema = AvroSchema::parse( json_encode( [ 'type' => 'string' ] ) );
		$stringArraySchema = AvroSchema::parse( json_encode( [
			'type' => 'array',
			'items' => 'string',
		] ) );
		$recordSchema = AvroSchema::parse( json_encode( [
			'type' => 'record',
			'name' => 'ut',
			'fields' => [
				[ 'name' => 'id', 'type' => 'int', 'required' => true ],
			],
		] ) );
		$enumSchema = AvroSchema::parse( json_encode( [
			'type' => 'record',
			'name' => 'ut',
			'fields' => [
				[ 'name' => 'count', 'type' => [ 'int', 'null' ] ],
			],
		] ) );

		return [
			[
				'No errors with a simple string serialization',
				$stringSchema, 'foobar', [],
			],

			[
				'Cannot serialize integer into string',
				$stringSchema, 5, 'Expected string, but recieved integer',
			],

			[
				'Cannot serialize array into string',
				$stringSchema, [], 'Expected string, but recieved array',
			],

			[
				'allows and ignores extra fields',
				$recordSchema, [ 'id' => 4, 'foo' => 'bar' ], [],
			],

			[
				'detects missing fields',
				$recordSchema, [], [ 'id' => 'Missing expected field' ],
			],

			[
				'handles first element in enum',
				$enumSchema, [ 'count' => 4 ], [],
			],

			[
				'handles second element in enum',
				$enumSchema, [ 'count' => null ], [],
			],

			[
				'rejects element not in union',
				$enumSchema, [ 'count' => 'invalid' ], [ 'count' => [
					'Expected any one of these to be true',
					[
						'Expected integer, but recieved string',
						'Expected null, but recieved string',
					]
				] ]
			],
			[
				'Empty array is accepted',
				$stringArraySchema, [], []
			],
			[
				'correct array element accepted',
				$stringArraySchema, [ 'fizzbuzz' ], []
			],
			[
				'incorrect array element rejected',
				$stringArraySchema, [ '12', 34 ], [ 'Expected string, but recieved integer' ]
			],
		];
	}

	/**
	 * @dataProvider getErrorsProvider
	 */
	public function testGetErrors( $message, $schema, $datum, $expected ) {
		$this->assertEquals(
			$expected,
			AvroValidator::getErrors( $schema, $datum ),
			$message
		);
	}
}
