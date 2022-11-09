<?php

namespace Wikimedia\Tests\Metrics;

use InvalidArgumentException;
use PHPUnit\Framework\TestCase;
use Psr\Log\NullLogger;
use Wikimedia\Metrics\Emitters\NullEmitter;
use Wikimedia\Metrics\Exceptions\InvalidConfigurationException;
use Wikimedia\Metrics\Exceptions\UnsupportedFormatException;
use Wikimedia\Metrics\Metrics\CounterMetric;
use Wikimedia\Metrics\Metrics\GaugeMetric;
use Wikimedia\Metrics\Metrics\NullMetric;
use Wikimedia\Metrics\Metrics\TimingMetric;
use Wikimedia\Metrics\MetricsCache;
use Wikimedia\Metrics\MetricsFactory;
use Wikimedia\Metrics\MetricUtils;
use Wikimedia\Metrics\OutputFormats;

/**
 * @covers \Wikimedia\Metrics\MetricsFactory
 * @covers \Wikimedia\Metrics\MetricUtils
 */
class MetricsFactoryTest extends TestCase {

	public function testGetCounter() {
		$m = new MetricsFactory( new MetricsCache, new NullEmitter, new NullLogger );
		$this->assertInstanceOf( CounterMetric::class, $m->getCounter( [
			'name' => 'test',
			'component' => 'testComponent'
		] ) );
	}

	public function testGetGauge() {
		$m = new MetricsFactory( new MetricsCache, new NullEmitter, new NullLogger );
		$this->assertInstanceOf( GaugeMetric::class, $m->getGauge( [
			'name' => 'test',
			'component' => 'testComponent'
		] ) );
	}

	public function testGetTiming() {
		$m = new MetricsFactory( new MetricsCache, new NullEmitter, new NullLogger );
		$this->assertInstanceOf( TimingMetric::class, $m->getTiming( [
			'name' => 'test',
			'component' => 'testComponent'
		] ) );
	}

	public function testUnsupportedOutputFormat() {
		$this->expectException( UnsupportedFormatException::class );
		OutputFormats::getNewFormatter( 999 );
	}

	public function testEmptyPrefix() {
		$this->expectException( InvalidArgumentException::class );
		OutputFormats::getNewEmitter( '', new MetricsCache, OutputFormats::getNewFormatter( OutputFormats::STATSD ), '' );
	}

	public function testUnsetNameConfig() {
		$m = new MetricsFactory( new MetricsCache, new NullEmitter, new NullLogger );
		$this->expectException( InvalidConfigurationException::class );
		$m->getCounter( [ 'component' => 'a' ] );
	}

	public function testUnsetExtensionConfig() {
		$m = new MetricsFactory( new MetricsCache, new NullEmitter, new NullLogger );
		$this->expectException( InvalidConfigurationException::class );
		$m->getCounter( [ 'name' => 'a' ] );
	}

	public function testBlankNameConfig() {
		$m = new MetricsFactory( new MetricsCache, new NullEmitter, new NullLogger );
		$this->expectException( InvalidConfigurationException::class );
		$m->getCounter( [ 'name' => '' ] );
	}

	public function testGetMetricWithLabelMismatch() {
		$m = new MetricsFactory( new MetricsCache, new NullEmitter, new NullLogger );
		$m->getCounter( [ 'name' => 'test_metric', 'component' => 'test', 'labels' => [ 'a' ] ] );
		$metric = $m->getCounter( [ 'name' => 'test_metric', 'component' => 'test', 'labels' => [ 'a', 'b' ] ] );
		$this->assertInstanceOf( NullMetric::class, $metric );
	}

	public function testNormalizeString() {
		$this->assertEquals(
			'new_metric_and_things',
			MetricUtils::normalizeString( 'new metric  @#&^and *-&-*things-*&-*!@#&^%#$' )
		);
	}

	public function testNormalizeArray() {
		$this->assertEquals(
			[ 'new_test_metric', 'another_new_test_metric' ],
			MetricUtils::normalizeArray( [ 'new.test|metric', 'another$new-test_metric' ] )
		);
	}

	public function testGetNullMetricOnNameCollision() {
		$m = new MetricsFactory( new MetricsCache, new NullEmitter, new NullLogger );
		// define metric as counter 'test'
		$m->getCounter( [ 'name' => 'test', 'component' => 'testComponent' ] );
		// redefine metric as timing 'test'
		$metric = @$m->getTiming( [ 'name' => 'test', 'component' => 'testComponent' ] );
		// gauge response must be null metric
		$this->assertInstanceOf( NullMetric::class, $metric );
		// NullMetric should not throw for any method call
		$metric->increment();
	}
}
