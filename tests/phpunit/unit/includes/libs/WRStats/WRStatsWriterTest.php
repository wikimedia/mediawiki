<?php

namespace Wikimedia\WRStats;

use PHPUnit\Framework\TestCase;

/**
 * @covers \Wikimedia\WRStats\WRStatsWriter
 * @covers \Wikimedia\WRStats\MetricSpec
 * @covers \Wikimedia\WRStats\SequenceSpec
 * @covers \Wikimedia\WRStats\EntityKey
 * @covers \Wikimedia\WRStats\GlobalEntityKey
 * @covers \Wikimedia\WRStats\LocalEntityKey
 * @covers \Wikimedia\WRStats\ArrayStatsStore
 */
class WRStatsWriterTest extends TestCase {
	public static function provideIncrFlush() {
		$specs1 = [
			'test' => [
				'sequences' => [ [
					'timeStep' => 100,
					'expiry' => 1000,
				] ],
				'resolution' => 1
			]
		];
		$specs2 = [
			'int' => [
				'sequences' => [ [
					'timeStep' => 100,
					'expiry' => 1000,
				] ],
				'resolution' => 10
			],
			'decimal' => [
				'sequences' => [ [
					'timeStep' => 200,
					'expiry' => 2000,
				] ],
				'resolution' => 0.1
			]
		];
		$specs3 = [
			'test' => [
				'sequences' => [
					[
						'timeStep' => 10,
						'expiry' => 100,
					],
					[
						'timeStep' => 100,
						'expiry' => 1000
					],
					[
						'name' => 'L',
						'timeStep' => 1000,
						'expiry' => 10000
					]
				]
			]
		];
		return [
			'no-op' => [
				$specs1,
				false,
				[],
				[]
			],
			'single incr' => [
				$specs1,
				false,
				[ [ 'test', 1, 1000 ] ],
				[ 'local:prefix:test::10' => [ 1, 1100 ] ]
			],
			'single incr global' => [
				$specs1,
				true,
				[ [ 'test', 1, 1000 ] ],
				[ 'global:prefix:test::10' => [ 1, 1100 ] ]
			],
			'two incrs' => [
				$specs1,
				false,
				[
					[ 'test', 1, 1000 ],
					[ 'test', 1, 1000 ]
				],
				[ 'local:prefix:test::10' => [ 2, 1100 ] ]
			],
			'incr by amount >1' => [
				$specs1,
				false,
				[
					[ 'test', 5, 1000 ],
					[ 'test', 7, 1000 ]
				],
				[ 'local:prefix:test::10' => [ 12, 1100 ] ]
			],
			'incr in different time buckets' => [
				$specs1,
				false,
				[
					[ 'test', 1, 1000 ],
					[ 'test', 2, 1100 ],
				],
				[
					'local:prefix:test::10' => [ 1, 1100 ],
					'local:prefix:test::11' => [ 2, 1100 ]
				]
			],
			'resolution > 1' => [
				$specs2,
				false,
				[
					[ 'int', 12.5, 1000 ],
				],
				[
					'local:prefix:int::10' => [ 1, 1100 ],
				]
			],
			'resolution < 1' => [
				$specs2,
				false,
				[
					[ 'decimal', 1.33, 1000 ],
				],
				[
					'local:prefix:decimal::5' => [ 13, 2200 ],
				]
			],
			'mixed expiry, resolution' => [
				$specs2,
				false,
				[
					[ 'int', 12.5, 1000 ],
					[ 'decimal', 1.33, 1000 ],
				],
				[
					'local:prefix:int::10' => [ 1, 1100 ],
					'local:prefix:decimal::5' => [ 13, 2200 ],
				]
			],
			'multi-sequence' => [
				$specs3,
				false,
				[
					[ 'test', 1, 1000 ],
					[ 'test', 1, 1010 ]
				],
				[
					'local:prefix:test::100' => [ 1, 110 ],
					'local:prefix:test::101' => [ 1, 110 ],
					'local:prefix:test:s1:10' => [ 2, 1100 ],
					'local:prefix:test:L:1' => [ 2, 11000 ],
				]
			]
		];
	}

	/**
	 * @dataProvider provideIncrFlush
	 */
	public function testIncrFlush( $specs, $global, $incrOps, $expected ) {
		$store = new ArrayStatsStore;
		$writer = new WRStatsWriter(
			$store,
			$specs,
			'prefix'
		);
		$entity = $global ? new GlobalEntityKey : null;
		foreach ( $incrOps as [ $name, $value, $time ] ) {
			$writer->setCurrentTime( $time );
			$writer->incr( $name, $entity, $value );
		}
		$writer = null;
		$this->assertSame( $expected, $store->getData() );
	}

	public function testResetAll() {
		$store = new ArrayStatsStore;
		$specs = [
			'int' => [
				'sequences' => [ [
					'timeStep' => 100,
					'expiry' => 1000,
				] ],
				'resolution' => 10
			],
			'decimal' => [
				'sequences' => [ [
					'timeStep' => 200,
					'expiry' => 2000,
				] ],
				'resolution' => 0.1
			]
		];
		$writer = new WRStatsWriter(
			$store,
			$specs,
			'prefix'
		);
		$writer->setCurrentTime( 1000 );
		$writer->incr( 'int', null, 20 );
		$writer->incr( 'decimal', null, 0.2 );
		$writer->flush();
		$this->assertArrayHasKey( 'local:prefix:int::10', $store->getData() );
		$this->assertArrayHasKey( 'local:prefix:decimal::5', $store->getData() );
		$writer->resetAll();
		$this->assertSame( [], $store->getData() );
	}
}
