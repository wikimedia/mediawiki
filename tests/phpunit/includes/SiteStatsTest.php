<?php

/**
 * @group Database
 */
class SiteStatsTest extends MediaWikiIntegrationTestCase {

	/**
	 * @covers SiteStats::jobs
	 */
	public function testJobsCountGetCached() {
		$cache = new WANObjectCache( [ 'cache' => new HashBagOStuff() ] );
		$this->setService( 'MainWANObjectCache', $cache );
		$jobq = $this->getServiceContainer()->getJobQueueGroup();

		$jobq->push( new NullJob( [] ) );
		$this->assertSame( 1, SiteStats::jobs(),
			'A single job enqueued bumps jobscount stat to 1' );

		$jobq->push( new NullJob( [] ) );
		$this->assertSame( 1, SiteStats::jobs(),
			'SiteStats::jobs() count does not reflect addition ' .
			'of a second job (cached)'
		);

		$jobq->get( 'null' )->delete();  // clear jobqueue
		$this->assertSame( 0, $jobq->get( 'null' )->getSize(),
			'Job queue for NullJob has been cleaned' );

		$cache->delete( $cache->makeKey( 'SiteStats', 'jobscount' ) );
		$this->assertSame( 1, SiteStats::jobs(),
			'jobs count is kept in process cache' );

		$cache->clearProcessCache();
		$this->assertSame( 0, SiteStats::jobs() );
	}

	/**
	 * @covers SiteStats
	 */
	public function testInit() {
		$this->db->delete( 'site_stats', IDatabase::ALL_ROWS, __METHOD__ );
		SiteStats::unload();

		SiteStats::edits();
		$this->assertNotFalse( $this->db->selectRow( 'site_stats', '1', IDatabase::ALL_ROWS, __METHOD__ ) );
	}
}
