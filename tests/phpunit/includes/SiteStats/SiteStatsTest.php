<?php

use MediaWiki\JobQueue\Jobs\NullJob;
use MediaWiki\SiteStats\SiteStats;
use Wikimedia\Rdbms\Platform\ISQLPlatform;

/**
 * @group Database
 */
class SiteStatsTest extends MediaWikiIntegrationTestCase {

	/**
	 * @covers \MediaWiki\SiteStats\SiteStats::jobs
	 */
	public function testJobsCountGetCached() {
		$cache = $this->getServiceContainer()->getMainWANObjectCache();
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
	 * @covers \MediaWiki\SiteStats\SiteStats
	 */
	public function testInit() {
		$this->getDb()->newDeleteQueryBuilder()
			->deleteFrom( 'site_stats' )
			->where( ISQLPlatform::ALL_ROWS )
			->caller( __METHOD__ )
			->execute();
		SiteStats::unload();

		SiteStats::edits();
		$row = $this->getDb()->newSelectQueryBuilder()
			->select( '1' )
			->from( 'site_stats' )
			->caller( __METHOD__ )->fetchRow();

		$this->assertNotFalse( $row );
	}
}
