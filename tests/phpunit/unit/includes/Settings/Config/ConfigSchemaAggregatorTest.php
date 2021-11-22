<?php

namespace MediaWiki\Tests\Unit\Settings\Config;

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

	public function testGetMergeStrategyFor() {
		$aggregator = new ConfigSchemaAggregator();
		$aggregator->addSchemas( [
			'no_strategy' => [ 'type' => 'string', ],
			'has_strategy' => [ 'type' => 'string', 'mergeStrategy' => MergeStrategy::ARRAY_MERGE, ],
		] );
		$this->assertNull( $aggregator->getMergeStrategyFor( 'not_exist' ) );
		$this->assertNull( $aggregator->getMergeStrategyFor( 'no_strategy' ) );
		$this->assertSame(
			MergeStrategy::ARRAY_MERGE,
			$aggregator->getMergeStrategyFor( 'has_strategy' )->getName()
		);
	}
}
