<?php

namespace Wikimedia\Tests\Metrics;

use PHPUnit\Framework\TestCase;
use Psr\Log\NullLogger;
use UDPTransport;
use Wikimedia\Metrics\CounterMetric;
use Wikimedia\Metrics\Exceptions\InvalidConfigurationException;
use Wikimedia\Metrics\Exceptions\InvalidLabelsException;
use Wikimedia\Metrics\Exceptions\UndefinedPrefixException;
use Wikimedia\Metrics\Exceptions\UnsupportedFormatException;
use Wikimedia\Metrics\GaugeMetric;
use Wikimedia\Metrics\MetricsFactory;
use Wikimedia\Metrics\NullMetric;
use Wikimedia\Metrics\TimingMetric;
use Wikimedia\TestingAccessWrapper;

/**
 * @covers \Wikimedia\Metrics\MetricsFactory
 * @covers \Wikimedia\Metrics\MetricUtils
 */
class MetricsFactoryTest extends TestCase {

	public function testGetCounter() {
		$m = new MetricsFactory( [ 'prefix' => 'mediawiki' ], new NullLogger );
		$this->assertInstanceOf( CounterMetric::class, $m->getCounter( [
			'name' => 'test',
			'extension' => 'testExtension'
		] ) );
	}

	public function testGetGauge() {
		$m = new MetricsFactory( [ 'prefix' => 'mediawiki' ], new NullLogger );
		$this->assertInstanceOf( GaugeMetric::class, $m->getGauge( [
			'name' => 'test',
			'extension' => 'testExtension'
		] ) );
	}

	public function testGetTiming() {
		$m = new MetricsFactory( [ 'prefix' => 'mediawiki' ], new NullLogger );
		$this->assertInstanceOf( TimingMetric::class, $m->getTiming( [
			'name' => 'test',
			'extension' => 'testExtension'
		] ) );
	}

	public function testUnsupportedOutputFormat() {
		$this->expectException( UnsupportedFormatException::class );
		new MetricsFactory( [ 'prefix' => 'mediawiki', 'format' => 'asdf' ], new NullLogger );
	}

	public function testMissingPrefix() {
		$this->expectException( UndefinedPrefixException::class );
		new MetricsFactory( [ 'format' => 'asdf' ], new NullLogger );
	}

	public function testUnsetNameConfig() {
		$m = new MetricsFactory( [ 'prefix' => 'mediawiki' ], new NullLogger );
		$this->expectException( InvalidConfigurationException::class );
		$m->getCounter( [ 'extension' => 'a' ] );
	}

	public function testUnsetExtensionConfig() {
		$m = new MetricsFactory( [ 'prefix' => 'mediawiki' ], new NullLogger );
		$this->expectException( InvalidConfigurationException::class );
		$m->getCounter( [ 'name' => 'a' ] );
	}

	public function testBlankNameConfig() {
		$m = new MetricsFactory( [ 'prefix' => 'mediawiki' ], new NullLogger );
		$this->expectException( InvalidConfigurationException::class );
		$m->getCounter( [ 'name' => '' ] );
	}

	public function testGetMetricWithLabelMismatch() {
		$m = new MetricsFactory( [ 'prefix' => 'mediawiki' ], new NullLogger );
		$m->getCounter( [ 'name' => 'test_metric', 'extension' => 'test', 'labels' => [ 'a' ] ] );
		$this->expectException( InvalidLabelsException::class );
		$m->getCounter( [ 'name' => 'test_metric', 'extension' => 'test', 'labels' => [ 'a', 'b' ] ] );
	}

	public function testNormalizeString() {
		$this->assertEquals(
			'new_metric_and_things',
			MetricsFactory::normalizeString( 'new metric  @#&^and *-&-*things-*&-*!@#&^%#$' )
		);
	}

	public function testNormalizeArray() {
		$this->assertEquals(
			[ 'new_test_metric', 'another_new_test_metric' ],
			MetricsFactory::normalizeArray( [ 'new.test|metric', 'another$new-test_metric' ] )
		);
	}

	public function testGetNullMetricOnNameCollision() {
		$m = new MetricsFactory( [ 'prefix' => 'mediawiki' ], new NullLogger );
		// define metric as counter 'test'
		$m->getCounter( [ 'name' => 'test', 'extension' => 'testExtension' ] );
		// redefine metric as timing 'test'
		$metric = $m->getTiming( [ 'name' => 'test', 'extension' => 'testExtension' ] );
		// gauge response must be null metric
		$this->assertInstanceOf( NullMetric::class, $metric );
		// NullMetric should not throw for any method call
		$metric->increment();
	}

	public function testSend() {
		$m = new MetricsFactory( [
			'prefix' => 'mediawiki',
			'format' => 'dogstatsd',
		], new NullLogger );

		$metric = $m->getCounter( [ 'name' => 'bar', 'extension' => 'test' ] );
		$metric->increment();
		$metric->increment();
		$metric = $m->getTiming( [ 'name' => 'foo', 'extension' => 'test' ] );
		$metric->observe( 3.14 );

		$transport = $this->createMock( UDPTransport::class );
		$transport->expects( $this->exactly( 1 ) )->method( 'emit' )
			->withConsecutive(
				[ "mediawiki.test.bar:1|c\nmediawiki.test.bar:1|c\nmediawiki.test.foo:3.14|ms\n" ]
			);

		$m = TestingAccessWrapper::newFromObject( $m );
		$m->send( $transport );
	}
}
