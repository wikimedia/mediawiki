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
				"mediawiki.test.bucketed:1|c|#le:0.1\n" .
				"mediawiki.test.quux:1|g\n" .
				"mediawiki.test.zot_count:1|c\n" .
				"mediawiki.test.zot_count:1|c\n" .
				"mediawiki.test.zot_count:1|c\n" .
				"mediawiki.test.zot_count:1|c\n" .
				"mediawiki.test.zot_bucket:0|c|#le:+Inf\n" .
				"mediawiki.test.zot_bucket:0|c|#le:1\n" .
				"mediawiki.test.zot_bucket:0|c|#le:10\n" .
				"mediawiki.test.zot_bucket:1|c|#le:+Inf\n" .
				"mediawiki.test.zot_bucket:1|c|#le:10\n" .
				"mediawiki.test.zot_bucket:1|c|#le:+Inf\n" .
				"mediawiki.test.zot_bucket:1|c|#le:+Inf\n" .
				"mediawiki.test.zot_bucket:1|c|#le:+Inf\n" .
				"mediawiki.test.zot_bucket:1|c|#le:1\n" .
				"mediawiki.test.zot_bucket:1|c|#le:10\n" .
				"mediawiki.test.zot_sum:8|c\n" .
				"mediawiki.test.zot_sum:12|c\n" .
				"mediawiki.test.zot_sum:50|c\n" .
				"mediawiki.test.zot_sum:1|c\n" .
				"mediawiki.test.stats_buffered_total:24|c\n"
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

		// setting a bucket manages the 'le' label
		$m->getCounter( 'bucketed' )
			->setBucket( 0.1 )
			->incrementBy( 1 );

		// name collision: bar as gauge should not appear in output nor throw exception
		@$m->getGauge( 'bar' )
			->set( 42 );

		$m->getGauge( 'quux' )
			->copyToStatsdAt( 'test.old_quux' )
			->set( 1 );

		$histo = $m->getHistogram( 'zot', [ 1, 10 ] );
		$histo->observe( 8 );
		$histo->observe( 12 );
		$histo->observe( 50 );
		$histo->observe( 1 );

		// send metrics
		$m->flush();
	}

	public function testStatsdEmitterIgnoresHistograms() {
		// initialize cache
		$cache = new StatsCache();

		// emitter
		$emitter = OutputFormats::getNewEmitter(
			'mediawiki',
			$cache,
			OutputFormats::getNewFormatter( OutputFormats::STATSD )
		);

		// transport
		$transport = $this->createMock( UDPTransport::class );
		$transport->expects( $this->once() )->method( "emit" )
			->with(
				"mediawiki.test.foo:1|c\n" .
				"mediawiki.test.stats_buffered_total:2|c\n"
			);
		$emitter = $emitter->withTransport( $transport );

		// initialize metrics factory
		$m = new StatsFactory( $cache, $emitter, new NullLogger, 'test' );
		$m->getCounter( 'foo' )->increment();
		// this will get dropped from the output
		$m->getCounter( 'bar' )->setBucket( 1 )->increment();

		// send metrics
		$m->flush();
	}

}
