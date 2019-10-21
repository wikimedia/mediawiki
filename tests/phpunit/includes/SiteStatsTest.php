<?php

class SiteStatsTest extends MediaWikiTestCase {

	/**
	 * @covers SiteStats::jobs
	 */
	function testJobsCountGetCached() {
		$cache = new WANObjectCache( [ 'cache' => new HashBagOStuff() ] );
		$this->setService( 'MainWANObjectCache', $cache );
		$jobq = JobQueueGroup::singleton();

		$jobq->push( Job::factory( 'null', Title::newMainPage(), [] ) );
		$this->assertEquals( 1, SiteStats::jobs(),
			 'A single job enqueued bumps jobscount stat to 1' );

		$jobq->push( Job::factory( 'null', Title::newMainPage(), [] ) );
		$this->assertEquals( 1, SiteStats::jobs(),
			'SiteStats::jobs() count does not reflect addition ' .
			'of a second job (cached)'
		);

		$jobq->get( 'null' )->delete();  // clear jobqueue
		$this->assertSame( 0, $jobq->get( 'null' )->getSize(),
			'Job queue for NullJob has been cleaned' );

		$cache->delete( $cache->makeKey( 'SiteStats', 'jobscount' ) );
		$this->assertEquals( 1, SiteStats::jobs(),
			'jobs count is kept in process cache' );

		$cache->clearProcessCache();
		$this->assertSame( 0, SiteStats::jobs() );
	}

}
