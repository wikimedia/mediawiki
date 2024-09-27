<?php

namespace Wikimedia\Tests\Stats;

use PHPUnit\Framework\TestCase;
use Psr\Log\NullLogger;
use UDPTransport;
use Wikimedia\Stats\IBufferingStatsdDataFactory;
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
		$statsd->expects( $this->exactly( 3 ) )->method( "updateCount" );
		$statsd->expects( $this->once() )->method( "gauge" );
		$statsd->expects( $this->once() )->method( "timing" );

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
				"mediawiki.test.bar:1|c\n" .
				"mediawiki.test.bar:1|c\n" .
				"mediawiki.test.foo:3.14|ms\n" .
				"mediawiki.test.gauge:1|g\n" .
				"mediawiki.test.stats_buffered_total:4|c\n"
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
		$metric = $m->getTiming( 'foo' )->copyToStatsdAt( 'test.metric.timing' );
		$metric->observe( 3.14 );

		// name collision: gauge should not appear in output nor throw exception
		$metric = @$m->getGauge( 'bar' );
		$metric->set( 42 );

		$m->getGauge( 'gauge' )->copyToStatsdAt( 'test.metric.gauge' )->set( 1 );

		// send metrics
		$m->flush();
	}

}
