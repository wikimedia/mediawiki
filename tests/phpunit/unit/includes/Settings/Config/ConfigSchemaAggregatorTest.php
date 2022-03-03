<?php

namespace MediaWiki\Tests\Unit\Settings\Config;

use HashConfig;
use InvalidArgumentException;
use MediaWiki\Settings\Config\ConfigSchemaAggregator;
use MediaWiki\Settings\Config\MergeStrategy;
use MediaWiki\Settings\SettingsBuilderException;
use PHPUnit\Framework\TestCase;

/**
 * @covers \MediaWiki\Settings\Config\ConfigSchemaAggregator
 */
class ConfigSchemaAggregatorTest extends TestCase {

	public function testAddSchema() {
		$aggregator = new ConfigSchemaAggregator();
		$aggregator->addSchemas( [ 'foo' => [ 'type' => 'string', ], ] );
		$aggregator->addSchemas( [ 'bar' => [ 'type' => 'string', ], ] );
		$this->assertTrue( $aggregator->hasSchemaFor( 'foo' ) );
		$this->assertTrue( $aggregator->hasSchemaFor( 'foo' ) );
	}

	public function testAddSchemaOverride() {
		$aggregator = new ConfigSchemaAggregator();
		$aggregator->addSchemas( [ 'foo' => [ 'type' => 'string', ], ] );
		$this->expectException( SettingsBuilderException::class );
		$aggregator->addSchemas( [ 'foo' => [ 'type' => 'string', ], ] );
	}

	public function testGetDefaultFor() {
		$aggregator = new ConfigSchemaAggregator();
		$aggregator->addSchemas( [
			'no_default' => [ 'type' => 'string', ],
			'with_default' => [ 'type' => 'string', 'default' => 'bla', ],
		] );
		$this->assertFalse( $aggregator->hasDefaultFor( 'does_not_exist' ) );
		$this->assertFalse( $aggregator->hasDefaultFor( 'no_default' ) );
		$this->assertTrue( $aggregator->hasDefaultFor( 'with_default' ) );
		$this->assertSame( 'bla', $aggregator->getDefaultFor( 'with_default' ) );
	}

	public function testGetDefauts() {
		$aggregator = new ConfigSchemaAggregator();
		$aggregator->addSchemas( [
			'no_default' => [ 'type' => 'string', ],
			'with_default' => [ 'type' => 'string', 'default' => 'bla', ],
			'another_with_default' => [ 'type' => 'string', 'default' => 'blabla', ],
		] );
		$this->assertEquals( [
			'with_default' => 'bla',
			'another_with_default' => 'blabla',
		], $aggregator->getDefaults() );
		$this->assertFalse( $aggregator->hasDefaultFor( 'no_default' ) );
		$this->assertTrue( $aggregator->hasDefaultFor( 'with_default' ) );
		$this->assertSame( 'bla', $aggregator->getDefaultFor( 'with_default' ) );
	}

	public function provideGetMergeStrategiesFor() {
		yield 'no schema' => [ null, null ];
		yield 'no strategy' => [ [ 'default' => '' ], null ];
		yield 'with strategy' => [ [ 'mergeStrategy' => 'array_merge' ], 'array_merge' ];

		yield 'with strategy and type=array' => [
			[
				'type' => 'array',
				'mergeStrategy' => 'replace'
			],
			'replace'
		];

		yield 'without strategy and type=array' => [
			[ 'type' => 'array' ],
			'array_merge'
		];

		yield 'with strategy and type=object' => [
			[
				'type' => 'object',
				'mergeStrategy' => 'array_plus_2d'
			],
			'array_plus_2d'
		];

		yield 'without strategy and type=object' => [
			[ 'type' => 'object' ],
			'array_plus'
		];
	}

	/**
	 * @dataProvider provideGetMergeStrategiesFor
	 */
	public function testGetMergeStrategyFor( $schema, $expected ) {
		$aggregator = new ConfigSchemaAggregator();

		if ( $schema ) {
			$aggregator->addSchemas( [ 'test' => $schema, ] );
		}

		$strategy = $aggregator->getMergeStrategyFor( 'test' );
		$this->assertSame(
			$expected,
			$strategy ? $strategy->getName() : null
		);
	}

	public function provideValidate() {
		yield 'invalid config' => [
			'config-schema' => [ 'foo' => [ 'type' => 'string', ], ],
			'config' => [ 'foo' => 1 ],
			'valid' => false,
		];
		yield 'all good' => [
			'config-schema' => [ 'foo' => [ 'type' => 'string', ], ],
			'config' => [ 'foo' => 'bar', ],
			'valid' => true,
		];
		yield 'missing key' => [
			'config-schema' => [ 'foo' => [ 'type' => 'string', ], ],
			'config' => [ 'bar' => 'bar' ],
			'valid' => false,
		];
		yield 'no schema was added' => [
			'config-schema' => [],
			'config' => [ 'foo' => 'bar', ],
			'valid' => true,
		];
		yield 'key is in config but has no schema' => [
			'config-schema' => [ 'foo' => [ 'type' => 'array', 'mergeStrategy' => MergeStrategy::ARRAY_MERGE ], ],
			'config' => [ 'foo' => [], 'baz' => false, ],
			'valid' => true,
		];
		yield 'assoc array where list is expected' => [
			'config-schema' => [ 'foo' => [ 'type' => 'array', ], ],
			'config' => [ 'foo' => [ 'x' => 1 ] ],
			'valid' => false,
		];
		yield 'map with numeric keys' => [
			'config-schema' => [ 'foo' => [ 'type' => 'object', ], ],
			'config' => [ 'foo' => [ 0 => 'x', 1 => 'y' ] ],
			'valid' => true,
		];
		yield 'array with ignoreKeys' => [
			'config-schema' => [ 'foo' => [ 'type' => 'array', 'ignoreKeys' => true ], ],
			'config' => [ 'foo' => [ 'x' => 'bla', 'blabla' ] ],
			'valid' => true,
		];
	}

	/**
	 * @dataProvider provideValidate
	 */
	public function testValidateConfig( array $schemas, array $config, bool $valid ) {
		$aggregator = new ConfigSchemaAggregator();
		$aggregator->addSchemas( $schemas );
		$status = $aggregator->validateConfig( new HashConfig( $config ) );
		$this->assertSame( $valid, $status->isOK() );
	}

	public function testValidateInvalidSchema() {
		$this->expectException( InvalidArgumentException::class );
		$aggregator = new ConfigSchemaAggregator();
		$aggregator->addSchemas( [ 'foo' => [ 'type' => 1 ] ] );
		$aggregator->validateConfig( new HashConfig( [ 'foo' => 'bar' ] ) );
	}
}
