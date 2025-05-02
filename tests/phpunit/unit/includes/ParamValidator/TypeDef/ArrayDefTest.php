<?php

namespace MediaWiki\Tests\ParamValidator\TypeDef;

use MediaWiki\ParamValidator\TypeDef\ArrayDef;
use Wikimedia\Message\DataMessageValue;
use Wikimedia\ParamValidator\SimpleCallbacks;
use Wikimedia\ParamValidator\ValidationException;

/**
 * @covers \MediaWiki\ParamValidator\TypeDef\ArrayDef
 */
class ArrayDefTest extends TypeDefUnitTestCase {

	protected function getInstance( SimpleCallbacks $callbacks, array $options ) {
		return new ArrayDef( $callbacks );
	}

	private function makeValidationException( $schemaSettings, $property, $pointer, $message, $constraint ) {
		return new ValidationException(
			DataMessageValue::new(
				'schema-validation-failed',
				[ '' ],
				'schema-validation-failed',
				[ 'schema-validation-error' => [
					'property' => $property,
					'pointer' => $pointer,
					'message' => $message,
					'constraint' => $constraint,
					'context' => 1
				] ]
			),
			'test',
			[ 'notrequired' => 'value' ],
			$schemaSettings
		);
	}

	public function provideValidate() {
		yield 'assoc array' => [ [ 'x' => 1 ], [ 'x' => 1 ], ];
		yield 'indexed array' => [ [ 'x' ], [ 'x' ], ];
		yield 'array' => [ [], [], ];

		$notComplex = self::getValidationException( 'notarray', null );
		yield 'null' => [ null, $notComplex ];
		yield 'string' => [ 'foo', $notComplex ];
		yield 'zero' => [ 0, $notComplex ];

		// test json with schema passed in $settings array
		yield 'valid structure' => [
			[ 'key1' => 'value1' ],
			[ 'key1' => 'value1' ],
			[ ArrayDef::PARAM_SCHEMA =>
				[
					'type' => 'object',
					'required' => [ 'key1' ]
				]
			]
		];

		yield 'nested one level valid structure' => [
			[ 'key1' => [ 'nested1' => 'value1' ] ],
			[ 'key1' => [ 'nested1' => 'value1' ] ],
			[ ArrayDef::PARAM_SCHEMA =>
				[
					'type' => 'object',
					'required' => [ 'key1' ]
				]
			]
		];

		yield 'nested two levels valid structure' => [
			[ 'key1' => [ 'nested1' => [ 'nested2' => 'value1' ] ] ],
			[ 'key1' => [ 'nested1' => [ 'nested2' => 'value1' ] ] ],
			[ ArrayDef::PARAM_SCHEMA =>
				[
					'type' => [ 'object', 'array' ], // type can be an array
					'required' => [ 'key1' ]
				]
			]
		];
		yield 'object with numeric keys' => [
			[ 1 => 'value 1', 'key1' => 'value' ],
			[ 1 => 'value 1', 'key1' => 'value' ],
			[ ArrayDef::PARAM_SCHEMA => [ 'type' => [ 'object' ], 'required' => [ 'key1' ] ] ]
		];
		$missingSchemaSettings = [ ArrayDef::PARAM_SCHEMA => [ 'type' => [ 'object' ], 'required' => [ 'required' ] ] ];
		$schemaValidationFailed = $this->makeValidationException(
			$missingSchemaSettings, 'required', '/required', 'The property required is required', 'required'
		);

		yield 'empty object is not valid' => [
			[],
			$schemaValidationFailed,
			[
				ArrayDef::PARAM_SCHEMA => [
					'type' => [ 'object' ],
					'required' => [ 'required' ],
				],
			],
			[ ArrayDef::PARAM_SCHEMA => [ 'type' => [ 'object' ], 'required' => [ 'required' ] ] ]
		];
		yield 'empty array is valid' => [
			[], [], [ ArrayDef::PARAM_SCHEMA => [ 'type' => 'array' ] ]
		];
		yield 'missing required in schema' => [
			[ 'notrequired' => 'value' ],
			$schemaValidationFailed,
			$missingSchemaSettings
		];
		yield 'default values get applied' => [
			[],
			[ 'key2' => 'default-key-2' ],
			[ ArrayDef::PARAM_SCHEMA =>
				[
					'type' => 'object',
					'properties' => [
						'key2' => [
							'type' => 'string',
							'default' => 'default-key-2'
						]
					],
					'required' => [ 'key2' ],
				]
			]
		];
	}

	public static function provideListSchema() {
		yield 'simple list of strings' => [
			'string',
			[
				'type' => 'array',
				'items' => [
					'type' => 'string'
				]
			]
		];

		yield 'list where each value must be either in an enum' => [
			[ 'enum' => [ 'a', 'b' ] ],
			[
				'type' => 'array',
				'items' => [ 'enum' => [ 'a', 'b' ] ]
			]
		];
	}

	/**
	 * @dataProvider provideListSchema
	 * @param mixed $schema Schema to test
	 * @param array $expect Expected schema array
	 */
	public function testListSchema( $schema, array $expect ) {
		$paramSchema = ArrayDef::makeListSchema( $schema );
		$this->assertArrayEquals( $expect, $paramSchema );
	}

	public function testNestedListSchema() {
		$expect = [
			'type' => 'array',
			'items' => [
				'type' => 'array',
				'items' => [
					'type' => 'string'
				]
			]
		];

		$paramSchema = ArrayDef::makeListSchema( ArrayDef::makeListSchema( 'string' ) );
		$this->assertArrayEquals( $expect, $paramSchema );
	}

	public static function provideMapSchema() {
		yield 'simple map of strings' => [
			'string',
			[
				'type' => 'object',
				'additionalProperties' => [
					'type' => 'string'
				]
			]
		];

		yield 'map where each value must be in an enum' => [
			[ 'enum' => [ 0, 1 ] ],
			[
				'type' => 'object',
				'additionalProperties' => [ 'enum' => [ 0, 1 ] ]
			]
		];
	}

	/**
	 * @dataProvider provideMapSchema
	 * @param mixed $schema Schema to test
	 * @param array $expect Expected schema array
	 */
	public function testMapSchema( $schema, array $expect ) {
		$paramSchema = ArrayDef::makeMapSchema( $schema );
		$this->assertArrayEquals( $expect, $paramSchema );
	}

	public function testNestedMapSchema() {
		$expect = [
			'type' => 'object',
			'items' => [
				'type' => 'object',
				'additionalProperties' => [
					'type' => 'string'
				]
			]
		];

		$paramSchema = ArrayDef::makeMapSchema( ArrayDef::makeMapSchema( 'string' ) );
		$this->assertArrayEquals( $expect, $paramSchema );
	}

	public static function provideObjectSchema() {
		yield 'object with two required properties, one an integer and another an enum' => [
			[ 'a' => 'integer', 'b' => [ 'enum' => [ 'x', 'y', 'z' ] ] ],
			[],
			false,
			[
				'type' => 'object',
				'required' => [ 'a', 'b' ],
				'properties' => [
					'a' => [ 'type' => 'integer' ],
					'b' => [ 'enum' => [ 'x', 'y', 'z' ] ],
				],
				'additionalProperties' => false
			]
		];

		yield 'object with two properties, one an integer an another an enum, only one is required' => [
			[ 'a' => 'integer' ],
			[ 'b' => [ 'enum' => [ 'x', 'y', 'z' ] ] ],
			false,
			[
				'type' => 'object',
				'required' => [ 'a' ],
				'properties' => [
					'a' => [ 'type' => 'integer' ],
					'b' => [ 'enum' => [ 'x', 'y', 'z' ] ],
				],
				'additionalProperties' => false
			]
		];

		yield 'object with one optional property of type string, additional properties allowed' => [
			[],
			[ 'a' => 'string' ],
			true,
			[
				'type' => 'object',
				'properties' => [
					'a' => [ 'type' => 'string' ],
				]
			]
		];

		yield 'object with properties of any name, but all properties must be lists of strings' => [
			[],
			[],
			[
				'type' => 'array',
				'items' => [
					'type' => 'string',
				],
			],
			[
				'type' => 'object',
				'additionalProperties' => [
					'type' => 'array',
					'items' => [
						'type' => 'string',
					]
				]
			]
		];
	}

	/**
	 * @dataProvider provideObjectSchema
	 * @param array $required
	 * @param array $optional
	 * @param array|bool|string $additional
	 * @param array $expect Expected schema array
	 */
	public function testObjectSchema( array $required, array $optional, $additional, array $expect ) {
		$paramSchema = ArrayDef::makeObjectSchema( $required, $optional, $additional );
		$this->assertArrayEquals( $expect, $paramSchema );
	}

	public static function provideInvalidObjectSchema() {
		yield 'object with property defined as both required and optional' => [
			[ 'a' => 'integer' ],
			[ 'a' => 'integer' ],
		];
	}

	/**
	 * @dataProvider provideInvalidObjectSchema
	 * @param array $required
	 * @param array $optional
	 */
	public function testInvalidObjectSchema( array $required, array $optional ) {
		$this->expectException( \InvalidArgumentException::class );
		ArrayDef::makeObjectSchema( $required, $optional, false );
	}
}
