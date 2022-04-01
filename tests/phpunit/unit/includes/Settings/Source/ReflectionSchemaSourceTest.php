<?php

namespace MediaWiki\Tests\Unit\Settings\Source;

use MediaWiki\Settings\SettingsBuilderException;
use MediaWiki\Settings\Source\ReflectionSchemaSource;
use PHPUnit\Framework\TestCase;

/**
 * @covers \MediaWiki\Settings\Source\ReflectionSchemaSource
 */
class ReflectionSchemaSourceTest extends TestCase {

	private const NOT_PUBLIC = [
		'type' => 'object'
	];

	public const NOT_A_SCHEMA = 'test';

	public const TEST_INTEGER = [
		'type' => 'integer',
		'default' => 7
	];

	public const TEST_MAP_TYPE = [
		'type' => '?dict',
		'additionalProperties' => [
			'type' => 'string|list',
			'items' => [
				'type' => 'float',
			]
		]
	];

	public function testLoad() {
		$source = new ReflectionSchemaSource( self::class );
		$settings = $source->load();

		$this->assertArrayHasKey( 'config-schema', $settings );
		$schemas = $settings['config-schema'];

		$this->assertArrayNotHasKey( 'NOT_PUBLIC', $schemas );
		$this->assertArrayNotHasKey( 'NOT_A_SCHEMA', $schemas );

		$this->assertArrayHasKey( 'TEST_INTEGER', $schemas );
		$this->assertArrayHasKey( 'type', $schemas['TEST_INTEGER'] );
		$this->assertSame( 'integer', $schemas['TEST_INTEGER']['type'] );

		$this->assertArrayHasKey( 'TEST_MAP_TYPE', $schemas );
		$this->assertArrayHasKey( 'additionalProperties', $schemas['TEST_MAP_TYPE'] );
		$this->assertSame(
			[ 'object', 'null' ],
			$schemas['TEST_MAP_TYPE']['type']
		);
		$this->assertSame(
			[ 'string', 'array' ],
			$schemas['TEST_MAP_TYPE']['additionalProperties']['type']
		);
		$this->assertSame(
			'number',
			$schemas['TEST_MAP_TYPE']['additionalProperties']['items']['type']
		);
	}

	public function testLoadInvalidClass() {
		$this->expectException( SettingsBuilderException::class );

		$source = new ReflectionSchemaSource( 'ThisClassDoesNotExist' );
		$source->load();
	}

}
