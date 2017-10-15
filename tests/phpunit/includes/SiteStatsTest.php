<?php

class SiteStatsTest extends MediaWikiTestCase {

	/**
	 * @covers SiteStats::jobs
	 */
	function testJobsCountGetCached() {
		$this->setService( 'MainWANObjectCache',
			new WANObjectCache( [ 'cache' => new HashBagOStuff() ] ) );
		$cache = \MediaWiki\MediaWikiServices::getInstance()->getMainWANObjectCache();
		$jobq = JobQueueGroup::singleton();

		// Delete jobs that might have been left behind by other tests
		$jobq->get( 'htmlCacheUpdate' )->delete();
		$jobq->get( 'recentChangesUpdate' )->delete();
		$jobq->get( 'userGroupExpiry' )->delete();
		$cache->delete( $cache->makeKey( 'SiteStats', 'jobscount' ) );

		$jobq->push( new NullJob( Title::newMainPage(), [] ) );
		$this->assertEquals( 1, SiteStats::jobs(),
			 'A single job enqueued bumps jobscount stat to 1' );

		$jobq->push( new NullJob( Title::newMainPage(), [] ) );
		$this->assertEquals( 1, SiteStats::jobs(),
			'SiteStats::jobs() count does not reflect addition ' .
			'of a second job (cached)'
		);

		$jobq->get( 'null' )->delete();  // clear jobqueue
		$this->assertEquals( 0, $jobq->get( 'null' )->getSize(),
			'Job queue for NullJob has been cleaned' );

		$cache->delete( $cache->makeKey( 'SiteStats', 'jobscount' ) );
		$this->assertEquals( 1, SiteStats::jobs(),
			'jobs count is kept in process cache' );

		$cache->clearProcessCache();
		$this->assertEquals( 0, SiteStats::jobs() );
	}

}
