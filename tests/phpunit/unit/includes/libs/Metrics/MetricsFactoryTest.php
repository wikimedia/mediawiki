<?php

namespace Wikimedia\Tests\Metrics;

use PHPUnit\Framework\TestCase;
use Psr\Log\LoggerInterface;
use Wikimedia\Metrics\CounterMetric;
use Wikimedia\Metrics\Exceptions\InvalidConfigurationException;
use Wikimedia\Metrics\Exceptions\InvalidLabelsException;
use Wikimedia\Metrics\Exceptions\UndefinedPrefixException;
use Wikimedia\Metrics\Exceptions\UnsupportedFormatException;
use Wikimedia\Metrics\GaugeMetric;
use Wikimedia\Metrics\MetricsFactory;
use Wikimedia\Metrics\NullMetric;
use Wikimedia\Metrics\TimingMetric;

/**
 * @covers \Wikimedia\Metrics\MetricsFactory
 */
class MetricsFactoryTest extends TestCase {
	/** @var LoggerInterface */
	private $logger;

	public function __construct( $name = null, array $data = [], $dataName = '' ) {
		parent::__construct( $name, $data, $dataName );
		$this->logger = $this->getMockBuilder( LoggerInterface::class )->getMock();
	}

	public function testGetCounter() {
		$m = new MetricsFactory( [ 'prefix' => 'mediawiki' ], $this->logger );
		$this->assertInstanceOf( CounterMetric::class, $m->getCounter( [
			'name' => 'test',
			'extension' => 'testExtension'
		] ) );
	}

	public function testGetGauge() {
		$m = new MetricsFactory( [ 'prefix' => 'mediawiki' ], $this->logger );
		$this->assertInstanceOf( GaugeMetric::class, $m->getGauge( [
			'name' => 'test',
			'extension' => 'testExtension'
		] ) );
	}

	public function testGetTiming() {
		$m = new MetricsFactory( [ 'prefix' => 'mediawiki' ], $this->logger );
		$this->assertInstanceOf( TimingMetric::class, $m->getTiming( [
			'name' => 'test',
			'extension' => 'testExtension'
		] ) );
	}

	public function testUnsupportedOutputFormat() {
		$this->expectException( UnsupportedFormatException::class );
		new MetricsFactory( [ 'prefix' => 'mediawiki', 'format' => 'asdf' ], $this->logger );
	}

	public function testMissingPrefix() {
		$this->expectException( UndefinedPrefixException::class );
		new MetricsFactory( [ 'format' => 'asdf' ], $this->logger );
	}

	public function testUnsetNameConfig() {
		$this->expectException( InvalidConfigurationException::class );
		$m = new MetricsFactory( [ 'prefix' => 'mediawiki' ], $this->logger );
		$m->getCounter( [ 'extension' => 'a' ] );
	}

	public function testUnsetExtensionConfig() {
		$this->expectException( InvalidConfigurationException::class );
		$m = new MetricsFactory( [ 'prefix' => 'mediawiki' ], $this->logger );
		$m->getCounter( [ 'name' => 'a' ] );
	}

	public function testBlankNameConfig() {
		$this->expectException( InvalidConfigurationException::class );
		$m = new MetricsFactory( [ 'prefix' => 'mediawiki' ],  $this->logger );
		$m->getCounter( [ 'name' => '' ] );
	}

	public function testGetMetricWithLabelMismatch() {
		$this->expectException( InvalidLabelsException::class );
		$m = new MetricsFactory( [ 'prefix' => 'mediawiki' ], $this->logger );
		$m->getCounter( [ 'name' => 'test_metric', 'extension' => 'test', 'labels' => [ 'a' ] ] );
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
		$m = new MetricsFactory( [ 'prefix' => 'mediawiki' ],  $this->logger );
		// define metric as counter 'test'
		$m->getCounter( [ 'name' => 'test', 'extension' => 'testExtension' ] );
		// redefine metric as timing 'test'
		$metric = $m->getTiming( [ 'name' => 'test', 'extension' => 'testExtension' ] );
		// gauge response must be null metric
		$this->assertInstanceOf( NullMetric::class, $metric );
		// NullMetric should not throw for any method call
		$metric->increment();
	}
}
