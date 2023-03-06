<?php

namespace Wikimedia\Tests\Stats;

use PHPUnit\Framework\TestCase;
use Psr\Log\NullLogger;
use Wikimedia\Stats\Emitters\NullEmitter;
use Wikimedia\Stats\OutputFormats;
use Wikimedia\Stats\StatsCache;
use Wikimedia\Stats\StatsFactory;
use Wikimedia\TestingAccessWrapper;

/**
 * @covers \Wikimedia\Stats\Metrics\NullMetric
 * @covers \Wikimedia\Stats\Metrics\CounterMetric
 * @covers \Wikimedia\Stats\Metrics\GaugeMetric
 * @covers \Wikimedia\Stats\Metrics\TimingMetric
 * @covers \Wikimedia\Stats\MetricUtils
 */
class MetricTest extends TestCase {

	public const FORMATS = [ 'statsd', 'dogstatsd' ];

	public const TYPES = [ 'counter', 'gauge', 'timing' ];

	public const TESTS = [
		'basic' => [
			'config' => [
				'name' => 'test.unit'
			],
			'value' => 2,
			'labels' => []
		],
		'invalidLabel' => [
			'config' => [
				'name' => 'test.unit'
			],
			'value' => 2,
			'labels' => [ ': x' => 'labelOne' ]
		],
		'oneLabel' => [
			'config' => [
				'name' => 'test.unit'
			],
			'value' => 2,
			'labels' => [ 'x' => 'labelOne' ]
		],
		'multiLabel' => [
			'config' => [
				'name' => 'test.unit'
			],
			'value' => 2,
			'labels' => [ 'x' => 'labelOne', 'y' => 'labelTwo' ]
		]
	];

	public const RESULTS = [
		'statsd.counter.basic' => [ 'mediawiki.testComponent.test_unit:2|c' ],
		'statsd.counter.invalidLabel' => [ 'mediawiki.testComponent.test_unit.labelOne:2|c' ],
		'statsd.counter.oneLabel' => [ 'mediawiki.testComponent.test_unit.labelOne:2|c' ],
		'statsd.counter.multiLabel' => [ 'mediawiki.testComponent.test_unit.labelOne.labelTwo:2|c' ],
		'statsd.gauge.basic' => [ 'mediawiki.testComponent.test_unit:2|g' ],
		'statsd.gauge.invalidLabel' => [ 'mediawiki.testComponent.test_unit.labelOne:2|g' ],
		'statsd.gauge.oneLabel' => [ 'mediawiki.testComponent.test_unit.labelOne:2|g' ],
		'statsd.gauge.multiLabel' => [ 'mediawiki.testComponent.test_unit.labelOne.labelTwo:2|g' ],
		'statsd.timing.basic' => [ 'mediawiki.testComponent.test_unit:2|ms' ],
		'statsd.timing.invalidLabel' => [ 'mediawiki.testComponent.test_unit.labelOne:2|ms' ],
		'statsd.timing.oneLabel' => [ 'mediawiki.testComponent.test_unit.labelOne:2|ms' ],
		'statsd.timing.multiLabel' => [ 'mediawiki.testComponent.test_unit.labelOne.labelTwo:2|ms' ],

		'dogstatsd.counter.basic' => [ 'mediawiki.testComponent.test_unit:2|c' ],
		'dogstatsd.counter.invalidLabel' => [ 'mediawiki.testComponent.test_unit:2|c|#x:labelOne' ],
		'dogstatsd.counter.oneLabel' => [ 'mediawiki.testComponent.test_unit:2|c|#x:labelOne' ],
		'dogstatsd.counter.multiLabel' => [
			'mediawiki.testComponent.test_unit:2|c|#x:labelOne,y:labelTwo' ],
		'dogstatsd.gauge.basic' => [ 'mediawiki.testComponent.test_unit:2|g' ],
		'dogstatsd.gauge.invalidLabel' => [ 'mediawiki.testComponent.test_unit:2|g|#x:labelOne' ],
		'dogstatsd.gauge.oneLabel' => [ 'mediawiki.testComponent.test_unit:2|g|#x:labelOne' ],
		'dogstatsd.gauge.multiLabel' => [
			'mediawiki.testComponent.test_unit:2|g|#x:labelOne,y:labelTwo' ],
		'dogstatsd.timing.basic' => [ 'mediawiki.testComponent.test_unit:2|ms' ],
		'dogstatsd.timing.invalidLabel' => [ 'mediawiki.testComponent.test_unit:2|ms|#x:labelOne' ],
		'dogstatsd.timing.oneLabel' => [ 'mediawiki.testComponent.test_unit:2|ms|#x:labelOne' ],
		'dogstatsd.timing.multiLabel' => [
			'mediawiki.testComponent.test_unit:2|ms|#x:labelOne,y:labelTwo' ],
	];

	private $cache;

	public function handleTest( $test, $type, $format ) {
		$config = self::TESTS[$test];
		$name = implode( '.', [ $format, $type, $test ] );
		$this->setName( $name );
		$this->cache->clear();
		$formatter = OutputFormats::getNewFormatter( OutputFormats::getFormatFromString( $format ) );
		$emitter = OutputFormats::getNewEmitter( 'mediawiki', $this->cache, $formatter );
		$statsFactory = new StatsFactory( 'testComponent', $this->cache, $emitter, new NullLogger );
		switch ( $type ) {
			case 'counter':
				$metric = $statsFactory->getCounter( $config['config']['name'] );
				foreach ( $config['labels'] as $key => $value ) {
					$metric->withLabel( $key, $value );
				}
				$metric->incrementBy( $config['value'] );
				break;
			case 'gauge':
				$metric = $statsFactory->getGauge( $config['config']['name'] );
				foreach ( $config['labels'] as $key => $value ) {
					$metric->withLabel( $key, $value );
				}
				$metric->set( $config['value'] );
				break;
			case 'timing':
				$metric = $statsFactory->getTiming( $config['config']['name'] );
				foreach ( $config['labels'] as $key => $value ) {
					$metric->withLabel( $key, $value );
				}
				$metric->observe( $config['value'] );
				break;
			case 'default':
				break;
		}
		$this->assertEquals( self::RESULTS[$name], TestingAccessWrapper::newFromObject( $emitter )->render() );
	}

	public function handleType( $type, $format ) {
		foreach ( array_keys( self::TESTS ) as $test ) {
			$this->handleTest( $test, $type, $format );
		}
	}

	public function handleFormat( $format ) {
		$this->cache = new StatsCache();
		foreach ( self::TYPES as $type ) {
			$this->handleType( $type, $format );
		}
	}

	public function testMetrics() {
		foreach ( self::FORMATS as $format ) {
			$this->handleFormat( $format );
		}
	}

	public function testSampledMetrics() {
		$rounds = 10;
		$m = new StatsFactory( 'counter', new StatsCache, new NullEmitter, new NullLogger );
		$ten_percent_metrics = $m->getCounter( 'test.sampled.ten' )->withSampleRate( 0.1 );
		$m = new StatsFactory( 'counter', new StatsCache, new NullEmitter, new NullLogger );
		$all_metrics = $m->getCounter( 'test.sampled.hundred' )->withSampleRate( 1.0 );
		$m = new StatsFactory( 'counter', new StatsCache, new NullEmitter, new NullLogger );
		$zero_metrics = $m->getCounter( 'test.sampled.zero' )->withSampleRate( 0.0 );
		for ( $i = 0; $i < $rounds; $i++ ) {
			$ten_percent_metrics->increment();
			$all_metrics->increment();
			$zero_metrics->increment();
		}
		$this->assertLessThan( $rounds, count( $ten_percent_metrics->getSamples() ) ); // random
		$this->assertCount( $rounds,  $all_metrics->getSamples() );
		$this->assertCount( 0, $zero_metrics->getSamples() );
	}

	public function testTimerNotStarted() {
		$m = new StatsFactory( 'test', new StatsCache, new NullEmitter, new NullLogger );
		$this->expectWarning();
		$this->expectWarningMessage( 'Stats: stop() called before start() for metric \'test\'' );
		$m->getTiming( 'test' )->stop();
	}
}
