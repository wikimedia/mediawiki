<?php

namespace Wikimedia\Tests\Stats;

use PHPUnit\Framework\TestCase;
use Psr\Log\NullLogger;
use UDPTransport;
use Wikimedia\Stats\OutputFormats;
use Wikimedia\Stats\StatsCache;
use Wikimedia\Stats\StatsFactory;

/**
 * @covers \Wikimedia\Stats\MetricsUDPEmitter
 * @covers \Wikimedia\Stats\MetricsCache
 * @covers \Wikimedia\Stats\Formatters\DogStatsD
 * @covers \Wikimedia\Stats\OutputFormats
 * @covers \Wikimedia\Stats\MetricsRenderer
 */
class StatsEmitterTest extends TestCase {

	public function testSend() {
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
		$transport->expects( $this->exactly( 1 ) )->method( "emit" )
			->withConsecutive(
				[ "mediawiki.test.bar:1|c\nmediawiki.test.bar:1|c\nmediawiki.test.foo:3.14|ms\n" ]
			);
		$emitter = $emitter->withTransport( $transport );

		// initialize metrics factory
		$m = new StatsFactory( $cache, $emitter, new NullLogger );

		$m->getCounter( [ 'name' => 'bar', 'component' => 'test' ] )->increment();

		// fetch same metric from cache and use it
		$metric = $m->getCounter( [ 'name' => 'bar', 'component' => 'test' ] );
		$metric->increment();
		$metric = $m->getTiming( [ 'name' => 'foo', 'component' => 'test' ] );
		$metric->observe( 3.14 );

		// name collision: gauge should not appear in output nor throw exception
		$metric = @$m->getGauge( [ 'name' => 'bar', 'component' => 'test' ] );
		$metric->set( 42 );

		// send metrics
		$m->flush();
	}

}
