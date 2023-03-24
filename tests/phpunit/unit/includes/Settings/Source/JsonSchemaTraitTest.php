<?php

namespace MediaWiki\Tests\Unit\Settings\Source;

use InvalidArgumentException;
use MediaWiki\Settings\Source\JsonSchemaTrait;
use PHPUnit\Framework\TestCase;

/**
 * @covers \MediaWiki\Settings\Source\JsonSchemaTrait
 */
class JsonSchemaTraitTest extends TestCase {
	use JsonSchemaTrait;

	public static function providePhpDocToJson() {
		yield 'int' => [ 'int', 'integer' ];
		yield 'nullable int' => [ '?int', [ 'integer', 'null' ] ];
		yield 'array input' => [ [ 'list', 'number' ], [ 'array', 'number' ] ];
		yield 'false or null' => [ 'false|null', [ 'boolean', 'null' ] ];
		yield 'string or float' => [ 'string|float', [ 'string', 'number' ] ];
		yield 'array or object' => [ 'array|object', [ 'array', 'object' ] ];
		yield 'dict or map' => [ 'dict|map', 'object' ];
		yield 'stdClass' => [ 'stdClass', 'object' ];
		yield 'nullable list' => [ '?list', [ 'array', 'null' ] ];
		yield 'Class' => [ 'Something', 'Something' ];
		yield 'keep integer' => [ 'integer', 'integer' ];
		yield 'keep number' => [ 'number', 'number' ];
		yield 'keep null' => [ 'null', 'null' ];
	}

	/**
	 * @dataProvider providePhpDocToJson
	 * @param string $phpDoc
	 * @param string|string[] $json
	 */
	public function testPhpDocToJson( $phpDoc, $json ) {
		$actual = self::phpDocToJson( $phpDoc );
		$this->assertSame( $json, $actual );
	}

	public static function provideNormalizeJsonSchema() {
		yield 'nullable int' => [
			[ 'type' => '?int' ],
			[ 'type' => [ 'integer', 'null' ] ]
		];

		yield 'additionalProperties' => [
			[
				'type' => '?map',
				'additionalProperties' => [ 'type' => 'list' ]
			],
			[
				'type' => [ 'object', 'null' ],
				'additionalProperties' => [ 'type' => 'array' ]
			]
		];

		yield 'items' => [
			[
				'type' => 'list|false',
				'items' => [ 'type' => 'float' ]
			],
			[
				'type' => [ 'array', 'boolean' ],
				'items' => [ 'type' => 'number' ]
			]
		];

		yield 'properties' => [
			[
				'type' => 'object',
				'properties' => [
					'foo' => [ 'type' => 'float' ],
					'bar' => [ 'type' => '?list', 'items' => [ 'type' => 'float' ] ],
				]
			],
			[
				'type' => 'object',
				'properties' => [
					'foo' => [ 'type' => 'number' ],
					'bar' => [ 'type' => [ 'array', 'null' ], 'items' => [ 'type' => 'number' ] ],
				]
			]
		];

		yield 'nested' => [
			[
				'type' => '?dict',
				'additionalProperties' => [
					'type' => 'string|list',
					'items' => [
						'type' => 'float',
					]
				]
			],
			[
				'type' => [ 'object', 'null' ],
				'additionalProperties' => [
					'type' => [ 'string', 'array' ],
					'items' => [
						'type' => 'number',
					]
				]
			]
		];
	}

	/**
	 * @dataProvider provideNormalizeJsonSchema
	 * @param array $schema
	 * @param array $expected
	 */
	public function testNormalizeJsonSchema( $schema, $expected ) {
		$actual = self::normalizeJsonSchema( $schema );
		$this->assertSame( $expected, $actual );
	}

	public static function provideJsonToPhpDoc() {
		yield 'integer' => [ 'integer', 'int' ];
		yield 'double' => [ 'double', 'float' ]; // For good measure.
		yield 'integer or null' => [ [ 'integer', 'null' ], '?int' ];
		yield 'boolean' => [ 'boolean', 'bool' ];
		yield 'string or number' => [ [ 'string', 'number' ], 'string|float' ];
		yield 'array' => [ 'array', 'array' ];
		yield 'object' => [ 'object', 'array' ]; // Assoc array. Could be optional.
		yield 'Class' => [ 'Something', 'Something' ];
		yield 'keep int' => [ 'int', 'int' ];
		yield 'keep float' => [ 'float', 'float' ];
		yield 'keep null' => [ 'null', 'null' ];
	}

	/**
	 * @dataProvider provideJsonToPhpDoc
	 * @param string|string[] $json
	 * @param string $phpDoc
	 */
	public function testJsonToPhpDoc( $json, $phpDoc ) {
		$actual = self::jsonToPhpDoc( $json );
		$this->assertSame( $phpDoc, $actual );
	}

	public static function provideJsonToPhpDoc_invalidArgument() {
		yield 'null' => [ null ];
		yield 'list with null' => [ [ 'int', null ] ];
	}

	/**
	 * @dataProvider provideJsonToPhpDoc_invalidArgument
	 * @param mixed $bad
	 */
	public function testJsonToPhpDoc_invalidArgument( $bad ) {
		$this->expectException( InvalidArgumentException::class );
		self::jsonToPhpDoc( $bad );
	}

	public function testPhpDocToJson_invalidArgument() {
		$this->expectException( InvalidArgumentException::class );
		self::phpDocToJson( null );
	}

	public static function provideGetDefaultFromJsonSchema() {
		yield 'empty' => [ [], null ];

		yield 'no default, no properties' => [
			[ 'type' => 'string' ],
			null
		];

		yield 'simple default, no properties' => [
			[ 'type' => 'string', 'default' => 'kitten' ],
			'kitten'
		];

		yield 'no default, but properties' => [
			[
				'properties' => [
					'a' => [ 'type' => 'int' ],
					'b' => [ 'type' => 'int' ],
				]
			],
			[ 'a' => null, 'b' => null ]
		];

		yield 'default from properties' => [
			[
				'properties' => [
					'a' => [ 'default' => 1 ],
					'b' => [ 'default' => 2 ],
				]
			],
			[ 'a' => 1, 'b' => 2 ]
		];

		yield 'combined default' => [
			[
				'default' => [
					'a' => 11,
					'x' => 99
				],
				'properties' => [
					'a' => [ 'default' => 1 ],
					'b' => [ 'default' => 2 ],
				]
			],
			[ 'a' => 1, 'x' => 99, 'b' => 2 ]
		];

		yield 'nested properties' => [
			[
				'properties' => [
					'a' => [ 'default' => 1 ],
					'b' => [
						'default' => [],
						'properties' => [
							'x' => [ 'default' => 7 ],
							'y' => [
								'properties' => [
									'foo' => [ 'default' => 13 ],
									'bar' => [ 'type' => 'int' ]
								]
							],
						]
					],
				]
			],
			[
				'a' => 1,
				'b' => [
					'x' => 7,
					'y' => [ 'foo' => 13, 'bar' => null ]
				]
			]
		];
	}

	/**
	 * @dataProvider provideGetDefaultFromJsonSchema
	 * @param array $schema
	 * @param mixed $default
	 */
	public function testGetDefaultFromJsonSchema( $schema, $default ) {
		$actual = self::getDefaultFromJsonSchema( $schema );
		$this->assertSame( $default, $actual );
	}

}
