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
		$schemaValidationFailed = new ValidationException(
			DataMessageValue::new(
				'schema-validation-failed',
				[ '' ],
				'schema-validation-failed',
				[ 'schema-validation-error' => [
					'property' => 'required',
					'pointer' => '/required',
					'message' => 'The property required is required',
					'constraint' => 'required',
					'context' => 1
				] ]
			),
			'test',
			[ 'notrequired' => 'value' ],
			$missingSchemaSettings
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
		];
		yield 'empty array is valid' => [
			[], [], [ ArrayDef::PARAM_SCHEMA => [ 'type' => 'array' ] ]
		];
		yield 'missing required in schema' => [
			[ 'notrequired' => 'value' ],
			$schemaValidationFailed,
			$missingSchemaSettings
		];
	}
}
