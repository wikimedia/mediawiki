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
		'type' => '?map',
		'additionalProperties' => [
			'type' => 'string|list',
			'items' => [
				'type' => 'float',
			]
		]
	];

	public const TEST_DYNAMIC_DEFAULT_AUTO = [
		'type' => 'string',
		'dynamicDefault' => true
	];

	public const TEST_DYNAMIC_DEFAULT_STRING = [
		'type' => 'string',
		'dynamicDefault' => 'get_include_path'
	];

	public const TEST_DYNAMIC_DEFAULT_IMPLIED = [
		'type' => 'string',
		'dynamicDefault' => [
			'use' => [ 'A' ],
		]
	];

	public const TEST_DYNAMIC_DEFAULT = [
		'type' => 'string',
		'dynamicDefault' => [
			'use' => [ 'A' ],
			'callback' => [ self::class, 'getTestDefault' ]
		]
	];

	public const TEST_OBSOLETE = [
		'type' => 'string',
		'obsolete' => 'should be excluded'
	];

	public static function getDefaultTEST_DYNAMIC_DEFAULT_AUTO() {
		// noop
	}

	public static function getDefaultTEST_DYNAMIC_DEFAULT_IMPLIED() {
		// noop
	}

	public static function getTestDefault() {
		// noop
	}

	public function testLoad() {
		$source = new ReflectionSchemaSource( self::class );
		$settings = $source->load();

		$this->assertArrayHasKey( 'config-schema', $settings );
		$schemas = $settings['config-schema'];

		$this->assertArrayNotHasKey( 'NOT_PUBLIC', $schemas );
		$this->assertArrayNotHasKey( 'NOT_A_SCHEMA', $schemas );

		$this->assertArrayNotHasKey( 'TEST_OBSOLETE', $schemas );
		$this->assertArrayHasKey( 'TEST_OBSOLETE', $settings['obsolete-config'] );

		$this->assertArrayHasKey( 'TEST_INTEGER', $schemas );
		$this->assertArrayHasKey( 'default', $schemas['TEST_INTEGER'] );
		$this->assertArrayHasKey( 'type', $schemas['TEST_INTEGER'] );
		$this->assertSame( 'integer', $schemas['TEST_INTEGER']['type'] );

		$this->assertArrayHasKey( 'TEST_MAP_TYPE', $schemas );
		$this->assertArrayHasKey( 'default', $schemas['TEST_MAP_TYPE'] );
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

	public function testDynamicDefault() {
		$source = new ReflectionSchemaSource( self::class );
		$settings = $source->load();

		$this->assertArrayHasKey( 'config-schema', $settings );
		$schemas = $settings['config-schema'];

		$this->assertSame(
			[ self::class, 'getDefaultTEST_DYNAMIC_DEFAULT_AUTO' ],
			$schemas['TEST_DYNAMIC_DEFAULT_AUTO']['dynamicDefault']['callback']
		);

		$this->assertSame(
			'get_include_path',
			$schemas['TEST_DYNAMIC_DEFAULT_STRING']['dynamicDefault']['callback']
		);

		$this->assertSame(
			[ self::class, 'getDefaultTEST_DYNAMIC_DEFAULT_IMPLIED' ],
			$schemas['TEST_DYNAMIC_DEFAULT_IMPLIED']['dynamicDefault']['callback']
		);
		$this->assertSame(
			[ 'A' ],
			$schemas['TEST_DYNAMIC_DEFAULT_IMPLIED']['dynamicDefault']['use']
		);

		$this->assertSame(
			[ self::class, 'getTestDefault' ],
			$schemas['TEST_DYNAMIC_DEFAULT']['dynamicDefault']['callback']
		);
		$this->assertSame(
			[ 'A' ],
			$schemas['TEST_DYNAMIC_DEFAULT']['dynamicDefault']['use']
		);
	}

	public function testLoadInvalidClass() {
		$this->expectException( SettingsBuilderException::class );

		$source = new ReflectionSchemaSource( 'ThisClassDoesNotExist' );
		$source->load();
	}

}
