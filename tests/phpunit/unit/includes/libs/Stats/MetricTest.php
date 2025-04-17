<?php

namespace Wikimedia\Tests\Stats;

use MediaWikiCoversValidator;
use PHPUnit\Framework\TestCase;
use Psr\Log\NullLogger;
use Wikimedia\Stats\IBufferingStatsdDataFactory;
use Wikimedia\Stats\Metrics\BaseMetric;
use Wikimedia\Stats\Metrics\CounterMetric;
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
	use MediaWikiCoversValidator;

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
		'statsd.counter.basic' => [ 'mediawiki.testComponent.test_unit:2|c' ],
		'statsd.counter.invalidLabel' => [ 'mediawiki.testComponent.test_unit.labelOne:2|c' ],
		'statsd.counter.oneLabel' => [ 'mediawiki.testComponent.test_unit.labelOne:2|c' ],
		'statsd.counter.multiLabel' => [ 'mediawiki.testComponent.test_unit.labelOne.labelTwo:2|c' ],
		'statsd.counter.noComponent' => [ 'mediawiki.test_unit:2|c' ],
		'statsd.gauge.basic' => [ 'mediawiki.testComponent.test_unit:2|g' ],
		'statsd.gauge.invalidLabel' => [ 'mediawiki.testComponent.test_unit.labelOne:2|g' ],
		'statsd.gauge.oneLabel' => [ 'mediawiki.testComponent.test_unit.labelOne:2|g' ],
		'statsd.gauge.multiLabel' => [ 'mediawiki.testComponent.test_unit.labelOne.labelTwo:2|g' ],
		'statsd.gauge.noComponent' => [ 'mediawiki.test_unit:2|g' ],
		'statsd.timing.basic' => [ 'mediawiki.testComponent.test_unit:2|ms' ],
		'statsd.timing.invalidLabel' => [ 'mediawiki.testComponent.test_unit.labelOne:2|ms' ],
		'statsd.timing.oneLabel' => [ 'mediawiki.testComponent.test_unit.labelOne:2|ms' ],
		'statsd.timing.multiLabel' => [ 'mediawiki.testComponent.test_unit.labelOne.labelTwo:2|ms' ],
		'statsd.timing.noComponent' => [ 'mediawiki.test_unit:2|ms' ],

		'dogstatsd.counter.basic' => [ 'mediawiki.testComponent.test_unit:2|c' ],
		'dogstatsd.counter.invalidLabel' => [ 'mediawiki.testComponent.test_unit:2|c|#x:labelOne' ],
		'dogstatsd.counter.oneLabel' => [ 'mediawiki.testComponent.test_unit:2|c|#x:labelOne' ],
		'dogstatsd.counter.multiLabel' => [
			'mediawiki.testComponent.test_unit:2|c|#x:labelOne,y:labelTwo' ],
		'dogstatsd.counter.noComponent' => [ 'mediawiki.test_unit:2|c' ],
		'dogstatsd.gauge.basic' => [ 'mediawiki.testComponent.test_unit:2|g' ],
		'dogstatsd.gauge.invalidLabel' => [ 'mediawiki.testComponent.test_unit:2|g|#x:labelOne' ],
		'dogstatsd.gauge.oneLabel' => [ 'mediawiki.testComponent.test_unit:2|g|#x:labelOne' ],
		'dogstatsd.gauge.multiLabel' => [
			'mediawiki.testComponent.test_unit:2|g|#x:labelOne,y:labelTwo' ],
		'dogstatsd.gauge.noComponent' => [ 'mediawiki.test_unit:2|g' ],
		'dogstatsd.timing.basic' => [ 'mediawiki.testComponent.test_unit:2|ms' ],
		'dogstatsd.timing.invalidLabel' => [ 'mediawiki.testComponent.test_unit:2|ms|#x:labelOne' ],
		'dogstatsd.timing.oneLabel' => [ 'mediawiki.testComponent.test_unit:2|ms|#x:labelOne' ],
		'dogstatsd.timing.multiLabel' => [
			'mediawiki.testComponent.test_unit:2|ms|#x:labelOne,y:labelTwo' ],
		'dogstatsd.timing.noComponent' => [ 'mediawiki.test_unit:2|ms' ],
	];

	/** @var StatsCache */
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
		}
		switch ( $type ) {
			case 'counter':
				$metric = $statsFactory->getCounter( $config['config']['name'] );
				$metric->setLabels( $config['labels'] );
				$metric->incrementBy( $config['value'] );
				break;
			case 'gauge':
				$metric = $statsFactory->getGauge( $config['config']['name'] );
				$metric->setLabels( $config['labels'] );
				$metric->set( $config['value'] );
				break;
			case 'timing':
				$metric = $statsFactory->getTiming( $config['config']['name'] );
				$metric->setLabels( $config['labels'] );
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
		$m = StatsFactory::newNull();
		$ten_percent_metrics = $m->getCounter( 'test.sampled.ten' )->setSampleRate( 0.1 );
		$m = StatsFactory::newNull();
		$all_metrics = $m->getCounter( 'test.sampled.hundred' )->setSampleRate( 1.0 );
		$m = StatsFactory::newNull();
		$zero_metrics = $m->getCounter( 'test.sampled.zero' )->setSampleRate( 0.0 );
		for ( $i = 0; $i < $rounds; $i++ ) {
			$ten_percent_metrics->increment();
			$all_metrics->increment();
			$zero_metrics->increment();
		}
		$this->assertLessThan( $rounds, count( $ten_percent_metrics->getSamples() ) ); // random
		$this->assertCount( $rounds, $all_metrics->getSamples() );
		$this->assertCount( 0, $zero_metrics->getSamples() );

		# getSampleCount() should count all samples regardless of sample rate
		$this->assertEquals( 10, $ten_percent_metrics->getSampleCount() );
		$this->assertEquals( 10, $all_metrics->getSampleCount() );
		$this->assertEquals( 10, $zero_metrics->getSampleCount() );
	}

	public function testTimerNotStarted() {
		$m = StatsFactory::newNull();
		$this->expectPHPWarning(
			'Stats: stop() called before start() for metric \'test\'',
			static function () use ( $m ) {
				$m->getTiming( 'test' )->stop();
			}
		);
	}

	public function testChangingLabelsToUsedMetric() {
		$m = StatsFactory::newNull();
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

	public function testCounterHandleNotAllLabelsHaveValues() {
		$m = StatsFactory::newNull();
		$m->getCounter( 'testMetricCounter' )->setLabel( 'labelOne', 'a' )->increment();
		$this->expectPHPWarning(
			'Stats: Cannot associate label keys with label values: Not all initialized labels have an assigned value.',
			static function () use ( $m ) {
				$m->getCounter( 'testMetricCounter' )->increment();
			}
		);
	}

	public function testGaugeHandleNotAllLabelsHaveValues() {
		$m = StatsFactory::newNull();
		$m->getGauge( 'testMetricGauge' )->setLabel( 'labelOne', 'a' )->set( 1 );
		$this->expectPHPWarning(
			'Stats: Cannot associate label keys with label values: Not all initialized labels have an assigned value.',
			static function () use ( $m ) {
				$m->getGauge( 'testMetricGauge' )->set( 1 );
			}
		);
	}

	public function testTimingHandleNotAllLabelsHaveValues() {
		$m = StatsFactory::newNull();
		$m->getTiming( 'testMetricTiming' )->setLabel( 'labelOne', 'a' )->observe( 1 );
		$this->expectPHPWarning(
			'Stats: Cannot associate label keys with label values: Not all initialized labels have an assigned value.',
			static function () use ( $m ) {
				$m->getTiming( 'testMetricTiming' )->observe( 1 );
			}
		);
	}

	public function testSampleRateOOB() {
		$m = StatsFactory::newNull();
		$metric = @$m->getCounter( 'testMetricCounter' )->setSampleRate( 1.1 );
		$this->assertInstanceOf( NullMetric::class, $metric );
		$metric = @$m->getGauge( 'testMetricGauge' )->setSampleRate( -1 );
		$this->assertInstanceOf( NullMetric::class, $metric );
		$metric = @$m->getTiming( 'testMetricTimer' )->setSampleRate( 20 );
		$this->assertInstanceOf( NullMetric::class, $metric );
	}

	public function testSampleRateDisallowed() {
		$m = StatsFactory::newNull();
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

	public function testCopyToStatsdAtEmptyArrayResetsValue() {
		$baseMetric = new BaseMetric( '', 'testMetric' );
		$metric = new CounterMetric(
			$baseMetric->withStatsdDataFactory( $this->createMock( IBufferingStatsdDataFactory::class ) ),
			new NullLogger
		);
		$metric->copyToStatsdAt( 'test' );
		$this->assertEquals( [ 'test' ], $baseMetric->getStatsdNamespaces() );
		$metric->copyToStatsdAt( [] );
		$this->assertEquals( [], $baseMetric->getStatsdNamespaces() );
	}

	public function testStatsdDataFactoryCopyToStatsdAtWithGauge() {
		$statsFactory = StatsFactory::newNull();

		$gaugeArgs = [ 'test.gauge.1', 'test.gauge.2' ];
		$statsdMock = $this->createMock( IBufferingStatsdDataFactory::class );
		$statsdMock->expects( $this->exactly( 2 ) )
			->method( 'gauge' )
			->with( $this->callback( static function ( $key ) use ( &$gaugeArgs ) {
				$nextKey = array_shift( $gaugeArgs );
				return $nextKey === $key;
			} ) );

		$statsFactory = $statsFactory->withStatsdDataFactory( $statsdMock );
		$statsFactory->getGauge( 'testMetricGauge' )
			->copyToStatsdAt( [ 'test.gauge.1', 'test.gauge.2' ] )
			->set( 1 );
	}

	public function testHandleInvalidStatsdNamespace() {
		$m = StatsFactory::newNull();
		$m = $m->withStatsdDataFactory( $this->createMock( IBufferingStatsdDataFactory::class ) );
		$this->expectPHPWarning(
			'Stats: StatsD namespace must be a string.',
			function () use ( $m ) {
				$metric = $m->getCounter( 'testMetricCounter' )->copyToStatsdAt( null );
				$this->assertInstanceOf( NullMetric::class, $metric );
			}
		);
	}

	public function testHandleEmptyStatsdNamespace() {
		$m = StatsFactory::newNull();
		$m = $m->withStatsdDataFactory( $this->createMock( IBufferingStatsdDataFactory::class ) );
		$this->expectPHPWarning(
			'Stats: StatsD namespace cannot be empty.',
			function () use ( $m ) {
				$metric = $m->getCounter( 'testMetricCounter' )->copyToStatsdAt( '' );
				$this->assertInstanceOf( NullMetric::class, $metric );
			}
		);
	}

	public function testHandleNonStringStatsdNamespaceInArray() {
		$m = StatsFactory::newNull();
		$m = $m->withStatsdDataFactory( $this->createMock( IBufferingStatsdDataFactory::class ) );
		$this->expectPHPWarning(
			'Stats: StatsD namespace must be a string.',
			function () use ( $m ) {
				$metric = $m->getCounter( 'testMetricCounter' )->copyToStatsdAt( [ null ] );
				$this->assertInstanceOf( NullMetric::class, $metric );
			}
		);
	}

	public function testCanChangeLabelsWhileTimerIsStarted() {
		$cache = new StatsCache;
		$formatter = OutputFormats::getNewFormatter( OutputFormats::getFormatFromString( 'dogstatsd' ) );
		$emitter = OutputFormats::getNewEmitter( 'mediawiki', $cache, $formatter );
		$statsFactory = new StatsFactory( $cache, $emitter, new NullLogger );

		// start() and stop() called so close together here should be fractions of a millisecond
		$timer = $statsFactory->getTiming( 'test' )
			->setLabel( 'foo', 'bar' )
			->start();
		$timer->setLabel( 'foo', 'baz' );
		$timer->stop();

		$this->assertMatchesRegularExpression(
			'/^mediawiki\.test:(0\.[0-9]+)\|ms\|#foo:baz$/',
			TestingAccessWrapper::newFromObject( $emitter )->render()[0]
		);
	}

	public function testStatsdDataFactoryPersistsWithComponent() {
		$statsFactory = StatsFactory::newNull();

		$timingArgs = [ 'test.timing.1', 'test.timing.2' ];
		$statsdMock = $this->createMock( IBufferingStatsdDataFactory::class );
		$statsdMock->expects( $this->exactly( 2 ) )
			->method( 'timing' )
			->with( $this->callback( static function ( $key ) use ( &$timingArgs ) {
				$nextKey = array_shift( $timingArgs );
				return $nextKey === $key;
			}, 1.0 ) );

		$statsFactory = $statsFactory->withStatsdDataFactory( $statsdMock );

		// withComponent() returns a whole new StatsFactory instance
		$componentStatsFactory = $statsFactory->withComponent( 'TestComponent' );

		$componentStatsFactory->getTiming( 'testMetricTiming' )
			->copyToStatsdAt( [ 'test.timing.1', 'test.timing.2' ] )
			->observe( 1 );
	}

	public function testWithComponentStatsdFactoryChanges() {
		$statsFactory = StatsFactory::newNull();

		// statsdDataFactory should be null if withComponent called prior to calling withStatsdDataFactory()
		$noStatsd = TestingAccessWrapper::newFromObject( $statsFactory->withComponent( 'foo' ) );
		$this->assertNull( $noStatsd->statsdDataFactory );

		// call withStatsdDataFactory
		$statsFactory = $statsFactory->withStatsdDataFactory( $this->createMock( IBufferingStatsdDataFactory::class ) );

		// statsdDataFactory should be an instance of IBufferingStatsdDataFactory
		$yesStatsd = TestingAccessWrapper::newFromObject( $statsFactory->withComponent( 'foo' ) );
		$this->assertInstanceOf( IBufferingStatsdDataFactory::class, $yesStatsd->statsdDataFactory );

		// disable statsdDataFactory
		$statsFactory = $statsFactory->withStatsdDataFactory( null );

		// statsdDataFactory should be null again
		$noStatsdAgain = TestingAccessWrapper::newFromObject( $statsFactory->withComponent( 'foo' ) );
		$this->assertNull( $noStatsdAgain->statsdDataFactory );
	}

	/**
	 * PHPUnit 10 compatible replacement for expectWarning().
	 *
	 * @param string $msg
	 * @param callable $callback
	 * @return void
	 */
	private function expectPHPWarning( string $msg, callable $callback ): void {
		try {
			$errorEmitted = false;
			set_error_handler( function ( $_, $actualMsg ) use ( $msg, &$errorEmitted ) {
				$this->assertStringContainsString( $msg, $actualMsg );
				$errorEmitted = true;
			}, E_USER_WARNING );
			$callback();
			$this->assertTrue( $errorEmitted, "No PHP warning was emitted." );
		} finally {
			restore_error_handler();
		}
	}

	public function testMsConversion() {
		$statsFactory = StatsFactory::newNull();
		$statsFactory->getTiming( 'test_seconds' )->observeSeconds( 1 );
		$statsFactory->getTiming( 'test_seconds' )->observeNanoseconds( 1000000 );
		$samples = $statsFactory->getTiming( 'test_seconds' )->getSamples();
		$this->assertEquals(
			[ 1000, 1 ],
			array_map(
				static function ( $sample ) {
					return $sample->getValue();
				},
				$samples
			)
		);
	}

	public function testSetLabelReserved() {
		$metric = @StatsFactory::newNull()->getCounter( 'test' )->setLabel( 'Le', 'foo' );
		$this->assertInstanceOf( NullMetric::class, $metric );
	}

	public function testSetLabelsReserved() {
		$metric = @StatsFactory::newNull()->getCounter( 'test' )->setLabels( [ 'foo' => 'a', 'lE' => '1', 'bar' => 'c' ] );
		$this->assertInstanceOf( NullMetric::class, $metric );
	}

	public function testInvalidBucketValue() {
		$this->expectException( 'InvalidArgumentException' );
		StatsFactory::newNull()->getCounter( 'test' )->setBucket( 'foo' );
	}
}
