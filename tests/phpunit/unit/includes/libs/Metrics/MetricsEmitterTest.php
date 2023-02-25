<?php

namespace Wikimedia\Tests\Metrics;

use PHPUnit\Framework\TestCase;
use Psr\Log\NullLogger;
use UDPTransport;
use Wikimedia\Metrics\MetricsCache;
use Wikimedia\Metrics\MetricsFactory;
use Wikimedia\Metrics\OutputFormats;

/**
 * @covers \Wikimedia\Metrics\MetricsUDPEmitter
 * @covers \Wikimedia\Metrics\MetricsCache
 * @covers \Wikimedia\Metrics\Formatters\DogStatsD
 * @covers \Wikimedia\Metrics\OutputFormats
 * @covers \Wikimedia\Metrics\MetricsRenderer
 */
class MetricsEmitterTest extends TestCase {

	public function testSend() {
		// initialize cache
		$cache = new MetricsCache();

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
		$m = new MetricsFactory( $cache, $emitter, new NullLogger );

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
