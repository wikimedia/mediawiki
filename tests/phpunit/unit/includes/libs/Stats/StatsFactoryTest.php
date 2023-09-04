<?php

namespace Wikimedia\Tests\Stats;

use InvalidArgumentException;
use PHPUnit\Framework\TestCase;
use Psr\Log\NullLogger;
use Wikimedia\Stats\Emitters\NullEmitter;
use Wikimedia\Stats\Exceptions\IllegalOperationException;
use Wikimedia\Stats\Exceptions\UnsupportedFormatException;
use Wikimedia\Stats\Metrics\CounterMetric;
use Wikimedia\Stats\Metrics\GaugeMetric;
use Wikimedia\Stats\Metrics\NullMetric;
use Wikimedia\Stats\Metrics\TimingMetric;
use Wikimedia\Stats\OutputFormats;
use Wikimedia\Stats\StatsCache;
use Wikimedia\Stats\StatsFactory;
use Wikimedia\Stats\StatsUtils;

/**
 * @covers \Wikimedia\Stats\StatsFactory
 * @covers \Wikimedia\Stats\StatsUtils
 */
class StatsFactoryTest extends TestCase {

	public function testGetCounter() {
		$m = new StatsFactory( new StatsCache, new NullEmitter, new NullLogger );
		$this->assertInstanceOf( CounterMetric::class, $m->getCounter( 'test' ) );
	}

	public function testGetGauge() {
		$m = new StatsFactory( new StatsCache, new NullEmitter, new NullLogger );
		$this->assertInstanceOf( GaugeMetric::class, $m->getGauge( 'test' ) );
	}

	public function testGetTiming() {
		$m = new StatsFactory( new StatsCache, new NullEmitter, new NullLogger );
		$this->assertInstanceOf( TimingMetric::class, $m->getTiming( 'test' ) );
	}

	public function testUnsupportedOutputFormat() {
		$this->expectException( UnsupportedFormatException::class );
		OutputFormats::getNewFormatter( 999 );
	}

	public function testEmptyPrefix() {
		$this->expectException( InvalidArgumentException::class );
		OutputFormats::getNewEmitter( '', new StatsCache, OutputFormats::getNewFormatter( OutputFormats::STATSD ), '' );
	}

	public function testUnsetNameConfig() {
		$m = new StatsFactory( new StatsCache, new NullEmitter, new NullLogger );
		$this->expectException( InvalidArgumentException::class );
		$m->getCounter( '' );
	}

	public function testDisallowSettingStaticLabelsOnUndefinedComponent() {
		$m = new StatsFactory( new StatsCache, new NullEmitter, new NullLogger );
		$this->expectException( IllegalOperationException::class );
		$m->addStaticLabel( 'a', 'a' );
	}

	public function testNormalizeString() {
		$this->assertEquals(
			'new_metric_and_things',
			StatsUtils::normalizeString( 'new metric  @#&^and *-&-*things-*&-*!@#&^%#$' )
		);
	}

	public function testNormalizeArray() {
		$this->assertEquals(
			[ 'new_test_metric', 'another_new_test_metric' ],
			StatsUtils::normalizeArray( [ 'new.test|metric', 'another$new-test_metric' ] )
		);
	}

	public function testGetNullMetricWithLabelMismatch() {
		$m = new StatsFactory( new StatsCache, new NullEmitter, new NullLogger );
		// initialize a counter and add a sample
		$m->getCounter( 'test_metric' )->setLabel( 'a', 'a' )->increment();
		// get the same counter and attempt to add a different label key
		$metric = @$m->getCounter( 'test_metric' )->setLabel( 'b', 'b' );
		$this->assertInstanceOf( NullMetric::class, $metric );
	}

	public function testGetNullMetricOnNameCollision() {
		$m = new StatsFactory( new StatsCache, new NullEmitter, new NullLogger );
		// define metric as counter 'test'
		$m->getCounter( 'test' );
		// redefine metric as timing 'test'
		$metric = @$m->getTiming( 'test' );
		// gauge response must be null metric
		$this->assertInstanceOf( NullMetric::class, $metric );
		// NullMetric should not throw for any method call
		$metric->increment();
	}
}
