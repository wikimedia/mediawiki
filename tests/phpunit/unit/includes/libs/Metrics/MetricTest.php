<?php

namespace Wikimedia\Tests\Metrics;

use PHPUnit\Framework\TestCase;
use Psr\Log\NullLogger;
use Wikimedia\Metrics\Exceptions\InvalidLabelsException;
use Wikimedia\Metrics\MetricsFactory;

/**
 * @covers \Wikimedia\Metrics\NullMetric
 * @covers \Wikimedia\Metrics\CounterMetric
 * @covers \Wikimedia\Metrics\GaugeMetric
 * @covers \Wikimedia\Metrics\TimingMetric
 * @covers \Wikimedia\Metrics\MetricUtils
 */
class MetricTest extends TestCase {

	public const FORMATS = [ 'statsd', 'dogstatsd' ];

	public const TYPES = [ 'counter', 'gauge', 'timing' ];

	public const TESTS = [
		'basic' => [
			'config' => [
				'name' => 'test.unit',
				'extension' => 'testExtension',
				'labels' => [],
			],
			'value' => 2,
			'labels' => []
		],
		'invalidLabel' => [
			'config' => [
				'name' => 'test.unit',
				'extension' => 'testExtension',
				'labels' => [ 'x' ]
			],
			'value' => 2,
			'labels' => [ ': labelOne ' ]
		],
		'oneLabel' => [
			'config' => [
				'name' => 'test.unit',
				'extension' => 'testExtension',
				'labels' => [ 'x' ]
			],
			'value' => 2,
			'labels' => [ 'labelOne' ]
		],
		'multiLabel' => [
			'config' => [
				'name' => 'test.unit',
				'extension' => 'testExtension',
				'labels' => [ 'x', 'y' ]
			],
			'value' => 2,
			'labels' => [ 'labelOne', 'labelTwo' ]
		]
	];

	public const RESULTS = [
		'statsd.counter.basic' => [ 'mediawiki.testExtension.test_unit:2|c' ],
		'statsd.counter.invalidLabel' => [ 'mediawiki.testExtension.test_unit.labelOne:2|c' ],
		'statsd.counter.oneLabel' => [ 'mediawiki.testExtension.test_unit.labelOne:2|c' ],
		'statsd.counter.multiLabel' => [ 'mediawiki.testExtension.test_unit.labelOne.labelTwo:2|c' ],
		'statsd.gauge.basic' => [ 'mediawiki.testExtension.test_unit:2|g' ],
		'statsd.gauge.invalidLabel' => [ 'mediawiki.testExtension.test_unit.labelOne:2|g' ],
		'statsd.gauge.oneLabel' => [ 'mediawiki.testExtension.test_unit.labelOne:2|g' ],
		'statsd.gauge.multiLabel' => [ 'mediawiki.testExtension.test_unit.labelOne.labelTwo:2|g' ],
		'statsd.timing.basic' => [ 'mediawiki.testExtension.test_unit:2|ms' ],
		'statsd.timing.invalidLabel' => [ 'mediawiki.testExtension.test_unit.labelOne:2|ms' ],
		'statsd.timing.oneLabel' => [ 'mediawiki.testExtension.test_unit.labelOne:2|ms' ],
		'statsd.timing.multiLabel' => [ 'mediawiki.testExtension.test_unit.labelOne.labelTwo:2|ms' ],

		'dogstatsd.counter.basic' => [ 'mediawiki.testExtension.test_unit:2|c' ],
		'dogstatsd.counter.invalidLabel' => [ 'mediawiki.testExtension.test_unit:2|c|#x:labelOne' ],
		'dogstatsd.counter.oneLabel' => [ 'mediawiki.testExtension.test_unit:2|c|#x:labelOne' ],
		'dogstatsd.counter.multiLabel' => [
			'mediawiki.testExtension.test_unit:2|c|#x:labelOne,y:labelTwo' ],
		'dogstatsd.gauge.basic' => [ 'mediawiki.testExtension.test_unit:2|g' ],
		'dogstatsd.gauge.invalidLabel' => [ 'mediawiki.testExtension.test_unit:2|g|#x:labelOne' ],
		'dogstatsd.gauge.oneLabel' => [ 'mediawiki.testExtension.test_unit:2|g|#x:labelOne' ],
		'dogstatsd.gauge.multiLabel' => [
			'mediawiki.testExtension.test_unit:2|g|#x:labelOne,y:labelTwo' ],
		'dogstatsd.timing.basic' => [ 'mediawiki.testExtension.test_unit:2|ms' ],
		'dogstatsd.timing.invalidLabel' => [ 'mediawiki.testExtension.test_unit:2|ms|#x:labelOne' ],
		'dogstatsd.timing.oneLabel' => [ 'mediawiki.testExtension.test_unit:2|ms|#x:labelOne' ],
		'dogstatsd.timing.multiLabel' => [
			'mediawiki.testExtension.test_unit:2|ms|#x:labelOne,y:labelTwo' ],
	];

	public function testValidateLabels() {
		$this->expectException( InvalidLabelsException::class );
		$m = new MetricsFactory( [ 'prefix' => 'mediawiki' ], new NullLogger );
		$counter = $m->getCounter( [
			'name' => 'test',
			'extension' => 'testExtension',
			'labels' => [ 'a', 'b' ]
		] );
		$counter->increment( [ 'a' ] );
	}

	public function handleTest( $test, $type, $format, $metricsFactory ) {
		$config = self::TESTS[$test];
		// null target destroys cache
		$metricsFactory->flush();
		$name = implode( '.', [ $format, $type, $test ] );
		$this->setName( $name );
		switch ( $type ) {
			case 'counter':
				$metric = $metricsFactory->getCounter( $config['config'] );
				$metric->incrementBy( $config['value'], $config['labels'] );
				break;
			case 'gauge':
				$metric = $metricsFactory->getGauge( self::TESTS[$test]['config'] );
				$metric->set( $config['value'], $config['labels'] );
				break;
			case 'timing':
				$metric = $metricsFactory->getTiming( self::TESTS[$test]['config'] );
				$metric->observe( $config['value'], $config['labels'] );
				break;
			case 'default':
				break;
		}
		$this->assertEquals( self::RESULTS[$name], $metric->render() );
	}

	public function handleType( $type, $format, $metricsFactory ) {
		foreach ( array_keys( self::TESTS ) as $test ) {
			$this->handleTest( $test, $type, $format, $metricsFactory );
		}
	}

	public function handleFormat( $format ) {
		$metricsFactory = new MetricsFactory( [ 'prefix' => 'mediawiki', 'format' => $format ], new NullLogger );
		foreach ( self::TYPES as $type ) {
			$this->handleType( $type, $format, $metricsFactory );
		}
	}

	public function testMetrics() {
		foreach ( self::FORMATS as $format ) {
			$this->handleFormat( $format );
		}
	}

	public function testSampledMetrics() {
		$rounds = 10;
		foreach ( self::FORMATS as $format ) {
			$m = new MetricsFactory( [ 'prefix' => $format, 'format' => $format ], new NullLogger );
			$ten_percent = $m->getCounter(
				[
					'name' => 'test.sampled.ten',
					'extension' => 'counter',
					'sampleRate' => 0.1
				]
			);
			$hundred_percent = $m->getCounter(
				[
					'name' => 'test.sampled.hundred',
					'extension' => 'counter',
					'sampleRate' => 1.0
				]
			);
			$zero_percent = $m->getCounter(
				[
					'name' => 'test.sampled.zero',
					'extension' => 'counter',
					'sampleRate' => 0.0
				]
			);
			for ( $i = 0; $i < $rounds; $i++ ) {
				$ten_percent->increment();
				$hundred_percent->increment();
				$zero_percent->increment();
			}
			$this->assertTrue( count( $ten_percent->render() ) <= $rounds );  // random
			$this->assertEquals( count( $hundred_percent->render() ), $rounds );
			$this->assertEquals( count( $zero_percent->render() ), 0 );
		}
	}
}
