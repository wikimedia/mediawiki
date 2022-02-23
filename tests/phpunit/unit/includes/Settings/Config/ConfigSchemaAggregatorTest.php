<?php

namespace MediaWiki\Tests\Unit\Settings\Config;

use MediaWiki\Settings\Config\ConfigSchemaAggregator;
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
}
