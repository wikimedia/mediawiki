<?php

namespace Wikimedia\Tests\Stats;

use MediaWikiCoversValidator;
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
	use MediaWikiCoversValidator;

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
				"mediawiki.test.foo:1|c\n" .
				"mediawiki.test.bar:1|c|#mykey:value1\n" .
				"mediawiki.test.bar:1|c|#mykey:value2\n" .
				"mediawiki.test.baz:3.14|ms\n" .
				"mediawiki.test.quux:1|g\n" .
				"mediawiki.test.stats_buffered_total:5|c\n"
			);
		$emitter = $emitter->withTransport( $transport );

		// initialize metrics factory
		$m = new StatsFactory( $cache, $emitter, new NullLogger, 'test' );

		// inject statsd factory
		$m->withStatsdDataFactory( $statsd );

		// simple counter
		$m->getCounter( 'foo' )
			->increment();

		// counter with statsd copy
		// then fetch the same metric from cache and re-use it
		$m->getCounter( 'bar' )
			->setLabels( [
				'mykey' => 'value1'
			] )
			->copyToStatsdAt( 'test.old_bar' )
			->increment();

		$m->getCounter( 'bar' )
			->setLabels( [
				'mykey' => 'value2'
			] )
			->increment();

		// timer with statsd copy
		$m->getTiming( 'baz' )
			->copyToStatsdAt( 'test.old_baz' )
			->observe( 3.14 );

		// name collision: bar as gauge should not appear in output nor throw exception
		@$m->getGauge( 'bar' )
			->set( 42 );

		$m->getGauge( 'quux' )
			->copyToStatsdAt( 'test.old_quux' )
			->set( 1 );

		// send metrics
		$m->flush();
	}

}
