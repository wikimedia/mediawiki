<?php

namespace Wikimedia\Tests\Stats;

use PHPUnit\Framework\TestCase;
use Psr\Log\NullLogger;
use Wikimedia\Stats\Emitters\NullEmitter;
use Wikimedia\Stats\Exceptions\IllegalOperationException;
use Wikimedia\Stats\Metrics\NullMetric;
use Wikimedia\Stats\OutputFormats;
use Wikimedia\Stats\StatsCache;
use Wikimedia\Stats\StatsFactory;
use Wikimedia\TestingAccessWrapper;

/**
 * @covers \Wikimedia\Stats\Metrics\NullMetric
 * @covers \Wikimedia\Stats\Metrics\CounterMetric
 * @covers \Wikimedia\Stats\Metrics\GaugeMetric
 * @covers \Wikimedia\Stats\Metrics\TimingMetric
 * @covers \Wikimedia\Stats\StatsUtils
 */
class MetricTest extends TestCase {

	public const FORMATS = [ 'statsd', 'dogstatsd' ];

	public const TYPES = [ 'counter', 'gauge', 'timing' ];

	public const TESTS = [
		'basic' => [
			'config' => [
				'name' => 'test.unit',
				'component' => 'testComponent',
			],
			'value' => 2,
			'labels' => [],
		],
		'invalidLabel' => [
			'config' => [
				'name' => 'test.unit',
				'component' => 'testComponent',
			],
			'value' => 2,
			'labels' => [ ': x' => 'labelOne' ],
		],
		'oneLabel' => [
			'config' => [
				'name' => 'test.unit',
				'component' => 'testComponent',
			],
			'value' => 2,
			'labels' => [ 'x' => 'labelOne' ],
		],
		'multiLabel' => [
			'config' => [
				'name' => 'test.unit',
				'component' => 'testComponent',
			],
			'value' => 2,
			'labels' => [ 'x' => 'labelOne', 'y' => 'labelTwo' ],
		],
		'noComponent' => [
			'config' => [
				'name' => 'test.unit',
				'component' => null,
			],
			'value' => 2,
			'labels' => [],
		]
	];

	public const RESULTS = [
		'statsd.counter.basic' => [ 'mediawiki.testComponent.test_unit.test_wiki:2|c' ],
		'statsd.counter.invalidLabel' => [ 'mediawiki.testComponent.test_unit.test_wiki.labelOne:2|c' ],
		'statsd.counter.oneLabel' => [ 'mediawiki.testComponent.test_unit.test_wiki.labelOne:2|c' ],
		'statsd.counter.multiLabel' => [ 'mediawiki.testComponent.test_unit.test_wiki.labelOne.labelTwo:2|c' ],
		'statsd.counter.noComponent' => [ 'mediawiki.test_unit:2|c' ],
		'statsd.gauge.basic' => [ 'mediawiki.testComponent.test_unit.test_wiki:2|g' ],
		'statsd.gauge.invalidLabel' => [ 'mediawiki.testComponent.test_unit.test_wiki.labelOne:2|g' ],
		'statsd.gauge.oneLabel' => [ 'mediawiki.testComponent.test_unit.test_wiki.labelOne:2|g' ],
		'statsd.gauge.multiLabel' => [ 'mediawiki.testComponent.test_unit.test_wiki.labelOne.labelTwo:2|g' ],
		'statsd.gauge.noComponent' => [ 'mediawiki.test_unit:2|g' ],
		'statsd.timing.basic' => [ 'mediawiki.testComponent.test_unit.test_wiki:2|ms' ],
		'statsd.timing.invalidLabel' => [ 'mediawiki.testComponent.test_unit.test_wiki.labelOne:2|ms' ],
		'statsd.timing.oneLabel' => [ 'mediawiki.testComponent.test_unit.test_wiki.labelOne:2|ms' ],
		'statsd.timing.multiLabel' => [ 'mediawiki.testComponent.test_unit.test_wiki.labelOne.labelTwo:2|ms' ],
		'statsd.timing.noComponent' => [ 'mediawiki.test_unit:2|ms' ],

		'dogstatsd.counter.basic' => [ 'mediawiki.testComponent.test_unit:2|c|#wiki:test_wiki' ],
		'dogstatsd.counter.invalidLabel' => [ 'mediawiki.testComponent.test_unit:2|c|#wiki:test_wiki,x:labelOne' ],
		'dogstatsd.counter.oneLabel' => [ 'mediawiki.testComponent.test_unit:2|c|#wiki:test_wiki,x:labelOne' ],
		'dogstatsd.counter.multiLabel' => [
			'mediawiki.testComponent.test_unit:2|c|#wiki:test_wiki,x:labelOne,y:labelTwo' ],
		'dogstatsd.counter.noComponent' => [ 'mediawiki.test_unit:2|c' ],
		'dogstatsd.gauge.basic' => [ 'mediawiki.testComponent.test_unit:2|g|#wiki:test_wiki' ],
		'dogstatsd.gauge.invalidLabel' => [ 'mediawiki.testComponent.test_unit:2|g|#wiki:test_wiki,x:labelOne' ],
		'dogstatsd.gauge.oneLabel' => [ 'mediawiki.testComponent.test_unit:2|g|#wiki:test_wiki,x:labelOne' ],
		'dogstatsd.gauge.multiLabel' => [
			'mediawiki.testComponent.test_unit:2|g|#wiki:test_wiki,x:labelOne,y:labelTwo' ],
		'dogstatsd.gauge.noComponent' => [ 'mediawiki.test_unit:2|g' ],
		'dogstatsd.timing.basic' => [ 'mediawiki.testComponent.test_unit:2|ms|#wiki:test_wiki' ],
		'dogstatsd.timing.invalidLabel' => [ 'mediawiki.testComponent.test_unit:2|ms|#wiki:test_wiki,x:labelOne' ],
		'dogstatsd.timing.oneLabel' => [ 'mediawiki.testComponent.test_unit:2|ms|#wiki:test_wiki,x:labelOne' ],
		'dogstatsd.timing.multiLabel' => [
			'mediawiki.testComponent.test_unit:2|ms|#wiki:test_wiki,x:labelOne,y:labelTwo' ],
		'dogstatsd.timing.noComponent' => [ 'mediawiki.test_unit:2|ms' ],
	];

	private $cache;

	public function handleTest( $test, $type, $format ) {
		$config = self::TESTS[$test];
		$name = implode( '.', [ $format, $type, $test ] );
		$this->setName( $name );
		$this->cache->clear();
		$formatter = OutputFormats::getNewFormatter( OutputFormats::getFormatFromString( $format ) );
		$emitter = OutputFormats::getNewEmitter( 'mediawiki', $this->cache, $formatter );
		$statsFactory = new StatsFactory( $this->cache, $emitter, new NullLogger );
		if ( $config['config']['component'] !== null ) {
			$statsFactory = $statsFactory->withComponent( $config['config']['component'] );
			$statsFactory->addStaticLabel( 'wiki', 'test_wiki' );
		}
		switch ( $type ) {
			case 'counter':
				$metric = $statsFactory->getCounter( $config['config']['name'] );
				foreach ( $config['labels'] as $key => $value ) {
					$metric->setLabel( $key, $value );
				}
				$metric->incrementBy( $config['value'] );
				break;
			case 'gauge':
				$metric = $statsFactory->getGauge( $config['config']['name'] );
				foreach ( $config['labels'] as $key => $value ) {
					$metric->setLabel( $key, $value );
				}
				$metric->set( $config['value'] );
				break;
			case 'timing':
				$metric = $statsFactory->getTiming( $config['config']['name'] );
				foreach ( $config['labels'] as $key => $value ) {
					$metric->setLabel( $key, $value );
				}
				$metric->observe( $config['value'] );
				break;
			case 'default':
				break;
		}
		$this->assertEquals( self::RESULTS[$name], TestingAccessWrapper::newFromObject( $emitter )->render() );
	}

	public function handleType( $type, $format ) {
		foreach ( self::TESTS as $test => $_ ) {
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
		$m = new StatsFactory( new StatsCache, new NullEmitter, new NullLogger );
		$ten_percent_metrics = $m->getCounter( 'test.sampled.ten' )->setSampleRate( 0.1 );
		$m = new StatsFactory( new StatsCache, new NullEmitter, new NullLogger );
		$all_metrics = $m->getCounter( 'test.sampled.hundred' )->setSampleRate( 1.0 );
		$m = new StatsFactory( new StatsCache, new NullEmitter, new NullLogger );
		$zero_metrics = $m->getCounter( 'test.sampled.zero' )->setSampleRate( 0.0 );
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
		$m = new StatsFactory( new StatsCache, new NullEmitter, new NullLogger );
		$this->expectWarning();
		$this->expectWarningMessage( 'Stats: stop() called before start() for metric \'test\'' );
		$m->getTiming( 'test' )->stop();
	}

	public function testErrorOnChangingStaticLabelsWithMetricsInCache() {
		$m = new StatsFactory( new StatsCache, new NullEmitter, new NullLogger );
		$m->getCounter( 'testMetric' );
		$this->expectException( IllegalOperationException::class );
		$m->addStaticLabel( 'a', '1' );
	}

	public function testLabelCollision() {
		$m = new StatsFactory( new StatsCache, new NullEmitter, new NullLogger, 'test' );
		$m->addStaticLabel( 'collide', 'test' );
		$metric = @$m->getCounter( 'testMetricCounter' )->setLabel( 'collide', 'value' );
		$this->assertInstanceOf( NullMetric::class, $metric );
		$metric = @$m->getGauge( 'testMetricGauge' )->setLabel( 'collide', 'value' );
		$this->assertInstanceOf( NullMetric::class, $metric );
		$metric = @$m->getTiming( 'testMetricTiming' )->setLabel( 'collide', 'value' );
		$this->assertInstanceOf( NullMetric::class, $metric );
	}

	public function testChangingLabelsToUsedMetric() {
		$m = new StatsFactory( new StatsCache, new NullEmitter, new NullLogger );
		$m->getCounter( 'testMetricCounter' )->setLabel( 'labelOne', 'a' )->increment();
		$counter = @$m->getCounter( 'testMetricCounter' )->setLabel( 'labelTwo', 'b' );
		$this->assertInstanceOf( NullMetric::class, $counter );
		$m->getGauge( 'testMetricGauge' )->setLabel( 'labelOne', 'a' )->set( 1 );
		$gauge = @$m->getGauge( 'testMetricGauge' )->setLabel( 'labelTwo', 'b' );
		$this->assertInstanceOf( NullMetric::class, $gauge );
		$m->getTiming( 'testMetricTiming' )->setLabel( 'labelOne', 'a' )->observe( 1 );
		$timer = @$m->getTiming( 'testMetricTiming' )->setLabel( 'labelTwo', 'b' );
		$this->assertInstanceOf( NullMetric::class, $timer );
	}

	public function testSampleRateOOB() {
		$m = new StatsFactory( new StatsCache, new NullEmitter, new NullLogger );
		$metric = @$m->getCounter( 'testMetricCounter' )->setSampleRate( 1.1 );
		$this->assertInstanceOf( NullMetric::class, $metric );
		$metric = @$m->getGauge( 'testMetricGauge' )->setSampleRate( -1 );
		$this->assertInstanceOf( NullMetric::class, $metric );
		$metric = @$m->getTiming( 'testMetricTimer' )->setSampleRate( 20 );
		$this->assertInstanceOf( NullMetric::class, $metric );
	}

	public function testSampleRateDisallowed() {
		$m = new StatsFactory( new StatsCache, new NullEmitter, new NullLogger );
		$m->getCounter( 'testMetric' )->increment();
		$metric = @$m->getCounter( 'testMetric' )->setSampleRate( 0.5 );
		$this->assertInstanceOf( NullMetric::class, $metric );
		$m->getGauge( 'testMetricGauge' )->set( 1 );
		$metric = @$m->getGauge( 'testMetricGauge' )->setSampleRate( 0.5 );
		$this->assertInstanceOf( NullMetric::class, $metric );
		$m->getTiming( 'testMetricTiming' )->observe( 1 );
		$metric = @$m->getTiming( 'testMetricTiming' )->setSampleRate( 0.5 );
		$this->assertInstanceOf( NullMetric::class, $metric );
	}
}
