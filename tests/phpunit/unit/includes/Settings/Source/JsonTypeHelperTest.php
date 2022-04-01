<?php

namespace MediaWiki\Tests\Unit\Settings\Source;

use InvalidArgumentException;
use MediaWiki\Settings\Source\JsonTypeHelper;
use PHPUnit\Framework\TestCase;

/**
 * @covers \MediaWiki\Settings\Source\JsonTypeHelper
 */
class JsonTypeHelperTest extends TestCase {

	public function providePhpDocToJson() {
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
		$helper = new JsonTypeHelper();
		$actual = $helper->phpDocToJson( $phpDoc );
		$this->assertSame( $json, $actual );
	}

	public function provideNormalizeJsonSchema() {
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
		$helper = new JsonTypeHelper();
		$actual = $helper->normalizeJsonSchema( $schema );
		$this->assertSame( $expected, $actual );
	}

	public function provideJsonToPhpDoc() {
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
		$helper = new JsonTypeHelper();
		$actual = $helper->jsonToPhpDoc( $json );
		$this->assertSame( $phpDoc, $actual );
	}

	public function provideJsonToPhpDoc_invalidArgument() {
		yield 'null' => [ null ];
		yield 'list with null' => [ [ 'int', null ] ];
	}

	/**
	 * @dataProvider provideJsonToPhpDoc_invalidArgument
	 * @param mixed $bad
	 */
	public function testJsonToPhpDoc_invalidArgument( $bad ) {
		$helper = new JsonTypeHelper();

		$this->expectException( InvalidArgumentException::class );
		$helper->jsonToPhpDoc( $bad );
	}

	public function testPhpDocToJson_invalidArgument() {
		$helper = new JsonTypeHelper();

		$this->expectException( InvalidArgumentException::class );
		$helper->phpDocToJson( null );
	}

}
