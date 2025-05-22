<?php

namespace Wikimedia\Tests\Stats;

use MediaWikiUnitTestCase;
use Psr\Log\NullLogger;
use Wikimedia\Stats\IBufferingStatsdDataFactory;
use Wikimedia\Stats\Metrics\BaseMetric;
use Wikimedia\Stats\Metrics\CounterMetric;
use Wikimedia\Stats\Metrics\NullMetric;
use Wikimedia\Stats\NullStatsdDataFactory;
use Wikimedia\Stats\OutputFormats;
use Wikimedia\Stats\StatsCache;
use Wikimedia\Stats\StatsFactory;
use Wikimedia\Stats\StatsUtils;
use Wikimedia\TestingAccessWrapper;
use Wikimedia\Timestamp\ConvertibleTimestamp;

/**
 * @covers \Wikimedia\Stats\Metrics\NullMetric
 * @covers \Wikimedia\Stats\Metrics\CounterMetric
 * @covers \Wikimedia\Stats\Metrics\GaugeMetric
 * @covers \Wikimedia\Stats\Metrics\TimingMetric
 * @covers \Wikimedia\Stats\StatsUtils
 */
class MetricTest extends MediaWikiUnitTestCase {
	public static function provideMetrics() {
		$dataset = [
			'basic' => [
				'call' => [
					'component' => 'testComponent',
					'name' => 'test.unit',
					'labels' => [],
					'value' => 2,
				],
				'expected' => [
					'counter' => 'mediawiki.testComponent.test_unit:2|c',
					'gauge' => 'mediawiki.testComponent.test_unit:2|g',
					'timing' => 'mediawiki.testComponent.test_unit:2|ms',
				],
			],
			'oneLabel' => [
				'call' => [
					'component' => 'testComponent',
					'name' => 'test.unit',
					'labels' => [ 'x' => 'labelOne' ],
					'value' => 2,
				],
				'expected' => [
					'counter' => [
						'statsd' => 'mediawiki.testComponent.test_unit.labelOne:2|c',
						'dogstatsd' => 'mediawiki.testComponent.test_unit:2|c|#x:labelOne',
					],
					'gauge' => [
						'statsd' => 'mediawiki.testComponent.test_unit.labelOne:2|g',
						'dogstatsd' => 'mediawiki.testComponent.test_unit:2|g|#x:labelOne',
					],
					'timing' => [
						'statsd' => 'mediawiki.testComponent.test_unit.labelOne:2|ms',
						'dogstatsd' => 'mediawiki.testComponent.test_unit:2|ms|#x:labelOne',
					],
				],
			],
			'multiLabel' => [
				'call' => [
					'component' => 'testComponent',
					'name' => 'test.unit',
					'labels' => [ 'x' => 'labelOne', 'y' => 'labelTwo' ],
					'value' => 2,
				],
				'expected' => [
					'counter' => [
						'dogstatsd' => 'mediawiki.testComponent.test_unit:2|c|#x:labelOne,y:labelTwo',
						'statsd' => 'mediawiki.testComponent.test_unit.labelOne.labelTwo:2|c',
					],
					'gauge' => [
						'statsd' => 'mediawiki.testComponent.test_unit.labelOne.labelTwo:2|g',
						'dogstatsd' => 'mediawiki.testComponent.test_unit:2|g|#x:labelOne,y:labelTwo',
					],
					'timing' => [
						'statsd' => 'mediawiki.testComponent.test_unit.labelOne.labelTwo:2|ms',
						'dogstatsd' => 'mediawiki.testComponent.test_unit:2|ms|#x:labelOne,y:labelTwo',
					],
				],
			],
			'noComponent' => [
				'call' => [
					'component' => null,
					'name' => 'test.unit',
					'labels' => [],
					'value' => 2,
				],
				'expected' => [
					'counter' => 'mediawiki.test_unit:2|c',
					'gauge' => 'mediawiki.test_unit:2|g',
					'timing' => 'mediawiki.test_unit:2|ms',
				],
			]
		];
		foreach ( $dataset as $name => $data ) {
			foreach ( $data['expected'] as $type => $formatted ) {
				foreach ( [ 'statsd', 'dogstatsd' ] as $format ) {
					yield "$name $type in $format" => [
						$data['call'], $type, $format,
						(array)( is_string( $formatted ) ? $formatted : $formatted[$format] )
					];
				}
			}
		}
	}

	/**
	 * @dataProvider provideMetrics
	 */
	public function testMetrics( array $call, string $type, string $format, array $expected ) {
		$cache = new StatsCache();
		$formatter = OutputFormats::getNewFormatter( OutputFormats::getFormatFromString( $format ) );
		$emitter = OutputFormats::getNewEmitter( 'mediawiki', $cache, $formatter );
		$statsFactory = new StatsFactory( $cache, $emitter, new NullLogger );
		if ( $call['component'] !== null ) {
			$statsFactory = $statsFactory->withComponent( $call['component'] );
		}
		switch ( $type ) {
			case 'counter':
				$metric = $statsFactory->getCounter( $call['name'] );
				$metric->setLabels( $call['labels'] );
				$metric->incrementBy( $call['value'] );
				break;
			case 'gauge':
				$metric = $statsFactory->getGauge( $call['name'] );
				$metric->setLabels( $call['labels'] );
				$metric->set( $call['value'] );
				break;
			case 'timing':
				$metric = $statsFactory->getTiming( $call['name'] );
				$metric->setLabels( $call['labels'] );
				$metric->observe( $call['value'] );
				break;
		}
		$this->assertEquals( $expected, TestingAccessWrapper::newFromObject( $emitter )->render() );
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
			'Stats: (metricName) stop() called before start()',
			static function () use ( $m ) {
				$m->getTiming( 'metricName' )->stop();
			}
		);
	}

	public function testChangingLabelsToUsedMetric() {
		$m = StatsFactory::newNull();
		$counter = $m->getCounter( 'testMetricCounter' )->setLabel( 'labelOne', 'a' );
		$counter->increment();
		$callable = static function () use ( $counter ) {
			$counter->setLabel( 'labelTwo', 'b' );
		};
		$this->expectPHPWarning(
			'Stats: (testMetricCounter) Cannot add labels to a metric containing samples',
			$callable,
			true
		);
		$counter = @$m->getCounter( 'testMetricCounter' )->setLabel( 'labelTwo', 'b' );
		$this->assertInstanceOf( NullMetric::class, $counter );
		$gauge = $m->getGauge( 'testMetricGauge' )->setLabel( 'labelOne', 'a' );
		$gauge->set( 1 );
		$callable = static function () use ( $gauge ) {
			$gauge->setLabel( 'labelTwo', 'b' );
		};
		$this->expectPHPWarning(
			'Stats: (testMetricGauge) Cannot add labels to a metric containing samples',
			$callable,
			true
		);
		$gauge = @$m->getGauge( 'testMetricGauge' )->setLabel( 'labelTwo', 'b' );
		$this->assertInstanceOf( NullMetric::class, $gauge );
		$timing = $m->getTiming( 'testMetricTiming' )->setLabel( 'labelOne', 'a' );
		$timing->observe( 1 );
		$callable = static function () use ( $timing ) {
			$timing->setLabel( 'labelTwo', 'b' );
		};
		$this->expectPHPWarning(
			'Stats: (testMetricTiming) Cannot add labels to a metric containing samples',
			$callable,
			true
		);
		$timing = @$m->getTiming( 'testMetricTiming' )->setLabel( 'labelTwo', 'b' );
		$this->assertInstanceOf( NullMetric::class, $timing );
	}

	public function testCounterHandleNotAllLabelsHaveValues() {
		$m = StatsFactory::newNull();
		$m->getCounter( 'testMetricCounter' )->setLabel( 'labelOne', 'a' )->increment();
		$this->expectPHPWarning(
			'Stats: (testMetricCounter) Cannot associate label keys with label values - Not all initialized labels have an assigned value.',
			static function () use ( $m ) {
				$m->getCounter( 'testMetricCounter' )->increment();
			}
		);
	}

	public function testGaugeHandleNotAllLabelsHaveValues() {
		$m = StatsFactory::newNull();
		$m->getGauge( 'testMetricGauge' )->setLabel( 'labelOne', 'a' )->set( 1 );
		$this->expectPHPWarning(
			'Stats: (testMetricGauge) Cannot associate label keys with label values - Not all initialized labels have an assigned value.',
			static function () use ( $m ) {
				$m->getGauge( 'testMetricGauge' )->set( 1 );
			}
		);
	}

	public function testTimingHandleNotAllLabelsHaveValues() {
		$m = StatsFactory::newNull();
		$m->getTiming( 'testMetricTiming' )->setLabel( 'labelOne', 'a' )->observe( 1 );
		$this->expectPHPWarning(
			'Stats: (testMetricTiming) Cannot associate label keys with label values - '
			. 'Not all initialized labels have an assigned value.',
			static function () use ( $m ) {
				$m->getTiming( 'testMetricTiming' )->observe( 1 );
			}
		);
	}

	public function testSampleRateOOB() {
		$m = StatsFactory::newNull();
		$counter = $m->getCounter( 'CounterMetricName' );
		$gauge = $m->getCounter( 'GaugeMetricName' );
		$timing = $m->getCounter( 'TimingMetricName' );
		$callable = static function () use ( $counter ) {
			$counter->setSampleRate( 1.1 );
		};
		$this->expectPHPWarning( 'Stats: (CounterMetricName) Sample rate can only be between 0.0 and 1.0. Got: 1.1', $callable, true );
		$callable = static function () use ( $gauge ) {
			$gauge->setSampleRate( -1 );
		};
		$this->expectPHPWarning( 'Stats: (GaugeMetricName) Sample rate can only be between 0.0 and 1.0. Got: -1', $callable, true );
		$callable = static function () use ( $timing ) {
			$timing->setSampleRate( 20 );
		};
		$this->expectPHPWarning( 'Stats: (TimingMetricName) Sample rate can only be between 0.0 and 1.0. Got: 20', $callable, true );
		$metric = @$counter->setSampleRate( 1.1 );
		$this->assertInstanceOf( NullMetric::class, $metric );
		$metric = @$gauge->setSampleRate( -1 );
		$this->assertInstanceOf( NullMetric::class, $metric );
		$metric = @$timing->setSampleRate( 20 );
		$this->assertInstanceOf( NullMetric::class, $metric );
	}

	public function testSampleRateDisallowed() {
		$m = StatsFactory::newNull();
		$m->getCounter( 'CounterMetricName' )->increment();
		$callable = static function () use ( $m ) {
			$m->getCounter( 'CounterMetricName' )->setSampleRate( 0.5 );
		};
		$this->expectPHPWarning(
			'Stats: (CounterMetricName) Cannot change sample rate on metric with recorded samples.',
			$callable,
			true
		);
		$metric = @$m->getCounter( 'CounterMetricName' )->setSampleRate( 0.5 );
		$this->assertInstanceOf( NullMetric::class, $metric );
		$m->getGauge( 'GaugeMetricName' )->set( 1 );
		$metric = @$m->getGauge( 'GaugeMetricName' )->setSampleRate( 0.5 );
		$this->assertInstanceOf( NullMetric::class, $metric );
		$m->getTiming( 'TimingMetricName' )->observe( 1 );
		$metric = @$m->getTiming( 'TimingMetricName' )->setSampleRate( 0.5 );
		$this->assertInstanceOf( NullMetric::class, $metric );
	}

	public function testCopyToStatsdAtEmptyArrayResetsValue() {
		$baseMetric = new BaseMetric( '', 'testMetric' );
		$metric = new CounterMetric(
			$baseMetric->withStatsdDataFactory( new NullStatsdDataFactory() ),
			new NullLogger()
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
		$m = $m->withStatsdDataFactory( new NullStatsdDataFactory() );
		$this->expectPHPWarning(
			'Stats: (testMetricCounter) StatsD namespace must be a string.',
			function () use ( $m ) {
				$metric = $m->getCounter( 'testMetricCounter' )->copyToStatsdAt( null );
				$this->assertInstanceOf( NullMetric::class, $metric );
			},
			true
		);
	}

	public function testHandleEmptyStatsdNamespace() {
		$m = StatsFactory::newNull();
		$m = $m->withStatsdDataFactory( new NullStatsdDataFactory() );
		$this->expectPHPWarning(
			'Stats: (testMetricCounter) StatsD namespace cannot be empty.',
			function () use ( $m ) {
				$metric = $m->getCounter( 'testMetricCounter' )->copyToStatsdAt( '' );
				$this->assertInstanceOf( NullMetric::class, $metric );
			},
			true
		);
	}

	public function testHandleNonStringStatsdNamespaceInArray() {
		$m = StatsFactory::newNull();
		$m = $m->withStatsdDataFactory( new NullStatsdDataFactory() );
		$this->expectPHPWarning(
			'Stats: (testMetricCounter) StatsD namespace must be a string.',
			function () use ( $m ) {
				$metric = $m->getCounter( 'testMetricCounter' )->copyToStatsdAt( [ null ] );
				$this->assertInstanceOf( NullMetric::class, $metric );
			},
			true
		);
	}

	public function testCanChangeLabelsWhileTimerIsStarted() {
		ConvertibleTimestamp::setFakeTime( '20110401090000' );
		$statsHelper = StatsFactory::newUnitTestingHelper();
		$statsFactory = $statsHelper->getStatsFactory();

		$timer = $statsFactory->getTiming( 'test' )->setLabel( 'foo', 'bar' );
		$timer->start();
		$timer->setLabel( 'foo', 'baz' );
		$timer->stop();

		$this->assertSame(
			[ 'mediawiki.test:1|ms|#foo:baz' ],
			$statsHelper->consumeAllFormatted()
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
		$statsFactory = $statsFactory->withStatsdDataFactory( new NullStatsdDataFactory() );

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
	 * Default uses assertStringContainsString().
	 * When $strict = true, uses assertSame().
	 *
	 * @param string $msg
	 * @param callable $callback
	 * @param bool $strict
	 * @return void
	 */
	private function expectPHPWarning( string $msg, callable $callback, bool $strict = false ): void {
		try {
			$errorEmitted = false;
			set_error_handler( function ( $_, $actualMsg ) use ( $msg, &$errorEmitted, $strict ) {
				if ( $strict ) {
					$this->assertSame( $msg, $actualMsg );
				} else {
					$this->assertStringContainsString( $msg, $actualMsg );
				}
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

	public function testNormalizeStringLocaleHardening() {
		// Confirm that e.g. the Turkish capital I (U+0130) is stripped
		// It might not be e.g. when using the tr_TR locale on PHP < 8.2
		$this->assertSame( 'test_value', StatsUtils::normalizeString( "test\u{0130} value" ) );
	}

	public function testSetLabelReserved() {
		$metric = StatsFactory::newNull()->getCounter( 'metricName' );
		$callable = static function () use ( $metric ) {
			$metric->setLabel( 'Le', 'foo' );
		};
		$this->expectPHPWarning( 'Stats: (metricName) \'le\' cannot be used as a label key', $callable, true );
		$this->assertInstanceOf( NullMetric::class, @$metric->setLabel( 'le', 'foo' ) );
	}

	public function testSetLabelsReserved() {
		$metric = @StatsFactory::newNull()->getCounter( 'test' )
			->setLabels( [ 'foo' => 'a', 'lE' => '1', 'bar' => 'c' ] );
		$this->assertInstanceOf( NullMetric::class, $metric );
	}

	public function testInvalidBucketValue() {
		$this->expectException( 'InvalidArgumentException' );
		$this->expectExceptionMessage( 'Stats: (metricName) Got illegal bucket value \'foo\' - must be float or \'+Inf\'' );
		StatsFactory::newNull()->getCounter( 'metricName' )->setBucket( 'foo' );
	}

	public function testInvalidLabel() {
		$statsHelper = StatsFactory::newUnitTestingHelper();
		$statsFactory = $statsHelper->getStatsFactory();

		$metric = $statsFactory->getCounter( 'metricName' );
		$callable = static function () use ( $metric ) {
			$metric->setLabel( ': x', 'labelOne' );
		};
		$this->expectPHPWarning( 'Stats: (metricName) Non-normalized label keys are deprecated, found \': x\'', $callable, true );

		// While deprecated, the metric should not be dropped in favor of NullMetric
		$this->assertInstanceOf( CounterMetric::class, @$metric->setLabel( ': y', 'labelTwo' ) );

		$metric->increment();

		$this->assertSame(
			[ 'mediawiki.metricName:1|c|#x:labelOne,y:labelTwo' ],
			$statsHelper->consumeAllFormatted()
		);
	}

	public int $recursions = 0;
	public int $maxRecursions = 2;

	public function recur( $statsFactory ) {
		$timing = $statsFactory->getTiming( 'metricName' )->start();
		if ( $this->recursions > $this->maxRecursions ) {
			return;
		} else {
			$this->recursions += 1;
			$this->recur( $statsFactory );
		}
		$timing->stop();
	}

	public function testTimingRecursion() {
		$statsHelper = StatsFactory::newUnitTestingHelper();
		$this->recur( $statsHelper->getStatsFactory() );
		$this->assertSame( 3, $statsHelper->count( 'metricName' ) );
	}

	public function testStopRunningTimerWarning() {
		$statsFactory = StatsFactory::newNull();
		$timing = $statsFactory->getTiming( 'metricName' )->start();
		$callable = static function () use ( $timing ) {
			$timing->stop();
		};
		$callable();
		$this->expectPHPWarning( 'Stats: (metricName) cannot call stop() more than once on a RunningTimer.', $callable, true );
	}

	public function testRunningTimerNullMetric() {
		$statsHelper = StatsFactory::newUnitTestingHelper();
		$statsFactory = $statsHelper->getStatsFactory();

		// put one good metric in the cache
		$statsFactory->getTiming( 'metricName' )->start()->stop();

		// try setting the label to something invalid making RunningTimer::metric = NullMetric()
		@$timing = $statsFactory->getTiming( 'metricName' )->start()->setLabel( 'foo', '' )->stop();

		$this->assertSame( 1, $statsHelper->count( 'metricName' ) );
	}
}
