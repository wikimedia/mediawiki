<?php

namespace Wikimedia\Tests\Stats;

use InvalidArgumentException;
use PHPUnit\Framework\TestCase;
use Psr\Log\NullLogger;
use Wikimedia\Stats\Emitters\NullEmitter;
use Wikimedia\Stats\Exceptions\InvalidConfigurationException;
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
		$this->assertInstanceOf( CounterMetric::class, $m->getCounter( [
			'name' => 'test',
			'component' => 'testComponent'
		] ) );
	}

	public function testGetGauge() {
		$m = new StatsFactory( new StatsCache, new NullEmitter, new NullLogger );
		$this->assertInstanceOf( GaugeMetric::class, $m->getGauge( [
			'name' => 'test',
			'component' => 'testComponent'
		] ) );
	}

	public function testGetTiming() {
		$m = new StatsFactory( new StatsCache, new NullEmitter, new NullLogger );
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
		OutputFormats::getNewEmitter( '', new StatsCache, OutputFormats::getNewFormatter( OutputFormats::STATSD ), '' );
	}

	public function testUnsetNameConfig() {
		$m = new StatsFactory( new StatsCache, new NullEmitter, new NullLogger );
		$this->expectException( InvalidConfigurationException::class );
		$m->getCounter( [ 'component' => 'a' ] );
	}

	public function testUnsetExtensionConfig() {
		$m = new StatsFactory( new StatsCache, new NullEmitter, new NullLogger );
		$this->expectException( InvalidConfigurationException::class );
		$m->getCounter( [ 'name' => 'a' ] );
	}

	public function testBlankNameConfig() {
		$m = new StatsFactory( new StatsCache, new NullEmitter, new NullLogger );
		$this->expectException( InvalidConfigurationException::class );
		$m->getCounter( [ 'name' => '' ] );
	}

	public function testGetMetricWithLabelMismatch() {
		$m = new StatsFactory( new StatsCache, new NullEmitter, new NullLogger );
		$m->getCounter( [ 'name' => 'test_metric', 'component' => 'test', 'labels' => [ 'a' ] ] );
		$metric = $m->getCounter( [ 'name' => 'test_metric', 'component' => 'test', 'labels' => [ 'a', 'b' ] ] );
		$this->assertInstanceOf( NullMetric::class, $metric );
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

	public function testGetNullMetricOnNameCollision() {
		$m = new StatsFactory( new StatsCache, new NullEmitter, new NullLogger );
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
