<?php

namespace MediaWiki\Tests\Unit\Settings\Source;

use MediaWiki\Settings\SettingsBuilderException;
use MediaWiki\Settings\Source\ReflectionSchemaSource;
use PHPUnit\Framework\TestCase;

/**
 * @covers \MediaWiki\Settings\Source\ReflectionSchemaSource
 */
class ReflectionSchemaSourceTest extends TestCase {

	public const TEST_SCHEMA = [
		'type' => 'object'
	];

	private const TEST_PRIVATE = [
		'type' => 'object'
	];

	public const TEST_STRING = 'test';

	public function testLoad() {
		$source = new ReflectionSchemaSource( self::class );
		$settings = $source->load();

		$this->assertArrayHasKey( 'config-schema', $settings );
		$this->assertArrayHasKey( 'TEST_SCHEMA', $settings['config-schema'] );
		$this->assertArrayHasKey( 'type', $settings['config-schema']['TEST_SCHEMA'] );
		$this->assertSame( 'object', $settings['config-schema']['TEST_SCHEMA']['type'] );

		$this->assertArrayNotHasKey( 'TEST_PRIVATE', $settings['config-schema'] );
		$this->assertArrayNotHasKey( 'TEST_STRING', $settings['config-schema'] );
	}

	public function testLoadInvalidClass() {
		$this->expectException( SettingsBuilderException::class );

		$source = new ReflectionSchemaSource( 'ThisClassDoesNotExist' );
		$source->load();
	}

}
