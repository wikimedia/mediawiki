<?php

namespace Wikimedia\Tests\Stats;

use IBufferingStatsdDataFactory;
use PHPUnit\Framework\TestCase;
use Psr\Log\NullLogger;
use UDPTransport;
use Wikimedia\Stats\OutputFormats;
use Wikimedia\Stats\StatsCache;
use Wikimedia\Stats\StatsFactory;

/**
 * @covers \Wikimedia\Stats\Emitters\UDPEmitter
 * @covers \Wikimedia\Stats\StatsCache
 * @covers \Wikimedia\Stats\Formatters\DogStatsdFormatter
 * @covers \Wikimedia\Stats\OutputFormats
 */
class StatsEmitterTest extends TestCase {

	public function testSendMetrics() {
		// set up a mock statsd data factory
		$statsd = $this->createMock( IBufferingStatsdDataFactory::class );
		$statsd->expects( $this->atLeastOnce() )->method( "updateCount" );

		// initialize cache
		$cache = new StatsCache();

		// emitter
		$emitter = OutputFormats::getNewEmitter(
			'mediawiki',
			$cache,
			OutputFormats::getNewFormatter( OutputFormats::DOGSTATSD )
		);

		// transport
		$transport = $this->createMock( UDPTransport::class );
		$transport->expects( $this->once() )->method( "emit" )
			->with(
				"mediawiki.test.bar:1|c\nmediawiki.test.bar:1|c\nmediawiki.test.foo:3.14|ms\nmediawiki.test.stats_buffered_total:3|c\n"
			);
		$emitter = $emitter->withTransport( $transport );

		// initialize metrics factory
		$m = new StatsFactory( $cache, $emitter, new NullLogger, 'test' );

		// inject statsd factory
		$m->withStatsdDataFactory( $statsd );

		// populate metric with statsd copy
		$m->getCounter( 'bar' )->copyToStatsdAt( 'test.metric' )->increment();

		// fetch same metric from cache and use it
		$metric = $m->getCounter( 'bar' );
		$metric->increment();
		$metric = $m->getTiming( 'foo' );
		$metric->observe( 3.14 );

		// name collision: gauge should not appear in output nor throw exception
		$metric = @$m->getGauge( 'bar' );
		$metric->set( 42 );

		// send metrics
		$m->flush();
	}

	public function testSendGaugeMetric() {
		// set up a mock statsd data factory
		$statsd = $this->createMock( IBufferingStatsdDataFactory::class );
		$statsd->expects( $this->atLeastOnce() )->method( "gauge" );

		// initialize cache
		$cache = new StatsCache();

		// emitter
		$emitter = OutputFormats::getNewEmitter(
			'mediawiki',
			$cache,
			OutputFormats::getNewFormatter( OutputFormats::DOGSTATSD )
		);

		// transport
		$transport = $this->createMock( UDPTransport::class );
		$transport->expects( $this->once() )->method( "emit" )
			->with(
				"mediawiki.test.bar:1|g\nmediawiki.test.bar:2|g\nmediawiki.test.stats_buffered_total:2|c\n"
			);
		$emitter = $emitter->withTransport( $transport );

		// initialize metrics factory
		$m = new StatsFactory( $cache, $emitter, new NullLogger, 'test' );

		// inject statsd factory
		$m->withStatsdDataFactory( $statsd );

		// populate metric with statsd copy
		$m->getGauge( 'bar' )->copyToStatsdAt( 'test.metric' )->set( 1 );

		// fetch same metric from cache and use it
		$metric = $m->getGauge( 'bar' );
		$metric->set( 2 );

		// send metrics
		$m->flush();
	}

}
