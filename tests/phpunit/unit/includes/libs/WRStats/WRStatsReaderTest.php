<?php

namespace Wikimedia\WRStats;

use PHPUnit\Framework\TestCase;

/**
 * @covers \Wikimedia\WRStats\WRStatsReader
 * @covers \Wikimedia\WRStats\SequenceSpec
 * @covers \Wikimedia\WRStats\TimeRange
 * @covers \Wikimedia\WRStats\RatePromise
 * @covers \Wikimedia\WRStats\ArrayStatsStore
 */
class WRStatsReaderTest extends TestCase {
	private $store;

	public function testLatest() {
		$reader = $this->createReader( [] );
		$latest = $reader->latest( 60 );
		$this->assertInstanceOf( TimeRange::class, $latest );
		$this->assertSame( 1940, $latest->start );
		$this->assertSame( 2000, $latest->end );
		$this->assertSame( 60, $latest->getDuration() );
	}

	private function getStore() {
		if ( $this->store === null ) {
			$this->store = new ArrayStatsStore;
		}
		return $this->store;
	}

	private function createReader( $specs ) {
		$reader = new WRStatsReader(
			$this->getStore(),
			$specs,
			'prefix'
		);
		$reader->setCurrentTime( 2000 );
		return $reader;
	}

	private function createWriter( $specs ) {
		$writer = new WRStatsWriter(
			$this->getStore(),
			$specs,
			'prefix'
		);
		$writer->setCurrentTime( 1000 );
		return $writer;
	}

	public function testGetRate() {
		$specs = [
			'test' => [
				'sequences' => [ [
					'timeStep' => 10,
					'expiry' => 1000,
				] ]
			]
		];
		$writer = $this->createWriter( $specs );
		$writer->incr( 'test' );
		$writer->flush();
		$reader = $this->createReader( $specs );
		$reader->setCurrentTime( 1000.001 );
		$rate = $reader->getRate( 'test', null, $reader->latest( 10 ) );
		$this->assertInstanceOf( RatePromise::class, $rate );
		$this->assertSame( 1, $rate->total() );
	}

	public static function provideInterpolationFlatnessMagic() {
		return [
			[ 0.5 ],
			[ 1 ],
			[ 1.1 ],
			[ 2 ],
			[ 3 ],
		];
	}

	/**
	 * If the event rate is constant then the range offset w.r.t. the start of
	 * the window doesn't matter, the interpolated rate will be constant.
	 * @dataProvider provideInterpolationFlatnessMagic
	 */
	public function testInterpolationFlatnessMagic( $rangeDuration ) {
		$specs = [
			'test' => [
				'sequences' => [ [
					'timeStep' => 1,
					'expiry' => 1000,
				] ],
				'resolution' => 0.001
			]
		];
		$writer = $this->createWriter( $specs );
		for ( $t = 0; $t < 10; $t++ ) {
			$writer->setCurrentTime( 1000 + $t );
			$writer->incr( 'test' );

		}
		$writer->flush();

		$reader = $this->createReader( $specs );
		for ( $timeDeciSeconds = 10000; $timeDeciSeconds <= 10011; $timeDeciSeconds++ ) {
			$range = $reader->timeRange(
				$timeDeciSeconds / 10,
				$timeDeciSeconds / 10 + $rangeDuration
			);
			$rate = $reader->getRate( 'test', null, $range );
			$this->assertEqualsWithDelta(
				$rangeDuration,
				$rate->total(),
				1e-9,
				"Flatness at t = {$range->start}"
			);
		}
	}

	public static function provideInterpolationLinearity() {
		return [
			[ 1000.0, 1.0 ],
			[ 1000.1, 1.1 ],
			[ 1000.9, 1.9 ],
			[ 1001.0, 2.0 ]
		];
	}

	/**
	 * Test interpolation involving two unequal buckets
	 * @dataProvider provideInterpolationLinearity
	 */
	public function testInterpolationLinearity( $start, $expected ) {
		$specs = [
			'test' => [
				'sequences' => [ [
					'timeStep' => 1,
					'expiry' => 1000,
				] ],
				'resolution' => 0.001
			]
		];
		$writer = $this->createWriter( $specs );
		$writer->setCurrentTime( 1000 );
		$writer->incr( 'test' );
		$writer->setCurrentTime( 1001 );
		$writer->incr( 'test', null, 2 );
		$writer->flush();

		$reader = $this->createReader( $specs );
		$range = $reader->timeRange( $start, $start + 1 );
		$rate = $reader->getRate( 'test', null, $range );
		$this->assertEqualsWithDelta( $expected, $rate->total(), 1e-9 );
	}

	public static function provideCurrentTimeAdjustment() {
		return [
			[ 1000.1, 1000.1, 1 ],
			[ 1000.2, 1000.1, 0.5 ],
			[ 1000.5, 1000.1, 0.2 ],
			[ 1001, 1000.1, 0.1 ],
			[ 1002, 1000.1, 0.1 ],
		];
	}

	/**
	 * Test the interpolation adjustment which occurs when the reader's current
	 * time falls within the last relevant bucket
	 *
	 * @dataProvider provideCurrentTimeAdjustment
	 */
	public function testCurrentTimeAdjustment( $currentTime, $rangeEnd, $expected ) {
		$specs = [
			'test' => [
				'sequences' => [ [
					'timeStep' => 1,
					'expiry' => 1000,
				] ],
				'resolution' => 0.001
			]
		];
		$writer = $this->createWriter( $specs );
		$writer->incr( 'test' );
		$writer->flush();

		$reader = $this->createReader( $specs );
		$reader->setCurrentTime( $currentTime );
		$range = $reader->timeRange( 999, $rangeEnd );
		$rate = $reader->getRate( 'test', null, $range );
		$this->assertEqualsWithDelta( $expected, $rate->total(), 1e-9 );
	}

	/**
	 * When the metric has multiple sequences, we select the one with the
	 * shortest expiry which contains the whole range
	 */
	public function testMultiSequence() {
		$specs = [
			'test' => [
				'sequences' => [
					[
						'timeStep' => 1,
						'expiry' => 10,
					],
					[
						'timeStep' => 10,
						'expiry' => 100,
					]
				],
				'resolution' => 0.001
			]
		];
		$store = $this->getStore();
		$store->incr(
			[
				'local:prefix:test::1999' => 1000,
				'local:prefix:test:s1:199' => 555000
			],
			1000
		);
		$reader = $this->createReader( $specs );
		$range = $reader->timeRange( 1990, 2000 );
		$rate = $reader->getRate( 'test', null, $range );
		$this->assertSame( 1.0, $rate->total() );

		$range = $reader->timeRange( 1900, 2000 );
		$rate = $reader->getRate( 'test', null, $range );
		$this->assertSame( 555.0, $rate->total() );
	}

	/**
	 * Test multiple entity keys
	 */
	public function testEntityKey() {
		$specs = [
			'test' => [
				'sequences' => [ [
					'timeStep' => 1,
					'expiry' => 1000,
				] ],
				'resolution' => 1
			]
		];
		$writer = $this->createWriter( $specs );
		$entity1 = new GlobalEntityKey( [ 'entity', 1 ] );
		$entity2 = new GlobalEntityKey( [ 'entity', 3 ] );
		$writer->incr( 'test', $entity1, 1 );
		$writer->incr( 'test', $entity2, 2 );
		$writer->flush();

		$reader = $this->createReader( $specs );
		$range = $reader->timeRange( 1000, 1001 );

		$rate0 = $reader->getRate( 'test', null, $range );
		$this->assertSame( 0, $rate0->total() );

		$rate1 = $reader->getRate( 'test', $entity1, $range );
		$this->assertSame( 1, $rate1->total() );

		$rate2 = $reader->getRate( 'test', $entity2, $range );
		$this->assertSame( 2, $rate2->total() );
	}

	/**
	 * Test the batch rate resolve functions total() and per*()
	 */
	public function testBatchResolve() {
		$specs = [
			'test' => [
				'sequences' => [ [
					'timeStep' => 10,
					'expiry' => 1000,
				] ],
				'resolution' => 0.001
			]
		];
		$writer = $this->createWriter( $specs );
		$writer->incr( 'test', null, 10 );
		$writer->flush();

		$reader = $this->createReader( $specs );
		$reader->setCurrentTime( 1010 );
		$rates = [
			1 => $reader->getRate( 'test', null, $reader->latest( 1 ) ),
			2 => $reader->getRate( 'test', null, $reader->latest( 2 ) ),
			3 => $reader->getRate( 'test', null, $reader->latest( 3 ) ),
		];
		$this->assertSame(
			[ 1 => 1.0, 2 => 2.0, 3 => 3.0 ],
			$reader->total( $rates )
		);
		$this->assertSame(
			[ 1 => 1.0, 2 => 1.0, 3 => 1.0 ],
			$reader->perSecond( $rates )
		);
		$this->assertSame(
			[ 1 => 60.0, 2 => 60.0, 3 => 60.0 ],
			$reader->perMinute( $rates )
		);
		$this->assertSame(
			[ 1 => 3600.0, 2 => 3600.0, 3 => 3600.0 ],
			$reader->perHour( $rates )
		);
		$this->assertSame(
			[ 1 => 86400.0, 2 => 86400.0, 3 => 86400.0 ],
			$reader->perDay( $rates )
		);
	}
}
