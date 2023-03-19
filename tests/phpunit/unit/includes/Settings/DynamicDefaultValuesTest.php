<?php

namespace MediaWiki\Tests\Unit\Settings;

use LogicException;
use MediaWiki\Settings\Config\ArrayConfigBuilder;
use MediaWiki\Settings\Config\ConfigSchemaAggregator;
use MediaWiki\Settings\DynamicDefaultValues;
use MediaWikiUnitTestCase;

/**
 * @covers \MediaWiki\Settings\DynamicDefaultValues
 */
class DynamicDefaultValuesTest extends MediaWikiUnitTestCase {

	private const DEFAULT_VALUE = 'this is the default value';

	public static function getDefaultTestValue() {
		return self::DEFAULT_VALUE;
	}

	public static function identity( $v ) {
		return $v;
	}

	public static function provideDynamicDefaults() {
		yield 'global function callback' => [
			[
				'Static' => [ 'default' => 'abcd' ],
				'Dynamic' => [
					'dynamicDefault' => [
						'callback' => 'strtoupper',
						'use' => [ 'Static' ]
					]
				],
			],
			[
				'Static' => 'xyz',
			],
			[
				'Static' => 'xyz',
				'Dynamic' => 'XYZ',
			]
		];

		yield 'static method callback (string)' => [
			[
				'Dynamic' => [
					'dynamicDefault' => [ 'callback' => [ self::class, 'getDefaultTestValue' ] ]
				],
			],
			[],
			[
				'Dynamic' => self::DEFAULT_VALUE,
			]
		];

		yield 'static method callback (array)' => [
			[
				'Dynamic' => [
					'dynamicDefault' => [ 'callback' => [ self::class, 'getDefaultTestValue' ] ]
				],
			],
			[],
			[
				'Dynamic' => self::DEFAULT_VALUE,
			]
		];

		yield 'interrupted chain' => [
			[
				'A' => [ 'default' => 'a' ],
				'B' => [
					'dynamicDefault' => [
						'callback' => [ self::class, 'identity' ],
						'use' => [ 'A' ]
					]
				],
				'C' => [
					'dynamicDefault' => [
						'callback' => [ self::class, 'identity' ],
						'use' => [ 'B' ]
					]
				],
				'D' => [
					'dynamicDefault' => [
						'callback' => [ self::class, 'identity' ],
						'use' => [ 'C' ]
					]
				],
				'E' => [
					'dynamicDefault' => [
						'callback' => [ self::class, 'identity' ],
						'use' => [ 'D' ]
					]
				],
			],
			[ 'D' => 'x' ],
			[
				'A' => 'a',
				'B' => 'a',
				'C' => 'a',
				'D' => 'x',
				'E' => 'x',
			]
		];

		yield 'do not trigger on null if the default is false' => [
			[
				'Dynamic' => [
					'default' => false,
					'dynamicDefault' => [ 'callback' => [ self::class, 'getDefaultTestValue' ] ]
				],
			],
			[ 'Dynamic' => null ],
			[ 'Dynamic' => null ]
		];

		yield 'trigger on 1 if that is the default' => [
			[
				'Dynamic' => [
					'default' => 1,
					'dynamicDefault' => [ 'callback' => [ self::class, 'getDefaultTestValue' ] ]
				],
			],
			[ 'Dynamic' => 1 ],
			[ 'Dynamic' => self::DEFAULT_VALUE ]
		];
	}

	/**
	 * @dataProvider provideDynamicDefaults
	 *
	 * @param array $schema
	 * @param array $config
	 * @param array $expected
	 */
	public function testApply( array $schema, array $config, array $expected ) {
		$schemaAggregator = new ConfigSchemaAggregator();
		$schemaAggregator->addSchemaMulti( $schema );

		$configBuilder = new ArrayConfigBuilder();
		$configBuilder->setMulti( $schemaAggregator->getDefaults() );
		$configBuilder->setMulti( $config );

		$dynamicDefaults = new DynamicDefaultValues( $schemaAggregator );
		$dynamicDefaults->applyDynamicDefaults( $configBuilder );

		$result = $configBuilder->build();

		foreach ( $expected as $key => $value ) {
			$this->assertSame( $value, $result->get( $key ), $key );
		}
	}

	public static function provideBadDynamicDefaults() {
		yield 'loop' => [
			[
				'X' => [
					'dynamicDefault' => [
						'callback' => [ self::class, 'identity' ],
						'use' => [ 'X' ]
					]
				],
			],
		];

		yield 'cycle' => [
			[
				'A' => [
					'dynamicDefault' => [
						'callback' => [ self::class, 'identity' ],
						'use' => [ 'E' ]
					]
				],
				'B' => [
					'dynamicDefault' => [
						'callback' => [ self::class, 'identity' ],
						'use' => [ 'A' ]
					]
				],
				'C' => [
					'dynamicDefault' => [
						'callback' => [ self::class, 'identity' ],
						'use' => [ 'B' ]
					]
				],
				'D' => [
					'dynamicDefault' => [
						'callback' => [ self::class, 'identity' ],
						'use' => [ 'C' ]
					]
				],
				'E' => [
					'dynamicDefault' => [
						'callback' => [ self::class, 'identity' ],
						'use' => [ 'D' ]
					]
				],
			],
		];
	}

	/**
	 * @dataProvider provideBadDynamicDefaults
	 *
	 * @param array $schema
	 * @param array $config
	 */
	public function testApplyFails( array $schema, array $config = [] ) {
		$schemaAggregator = new ConfigSchemaAggregator();
		$schemaAggregator->addSchemaMulti( $schema );

		$configBuilder = new ArrayConfigBuilder();
		$configBuilder->setMulti( $schemaAggregator->getDefaults() );
		$configBuilder->setMulti( $config );

		$dynamicDefaults = new DynamicDefaultValues( $schemaAggregator );

		$this->expectException( LogicException::class );
		$dynamicDefaults->applyDynamicDefaults( $configBuilder );
	}

}
