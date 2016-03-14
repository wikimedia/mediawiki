<?php

/**
 * @covers ClearUserWatchlistJob
 *
 * @group JobQueue
 * @group Database
 *
 * @licence GNU GPL v2+
 * @author Addshore
 */
class ClearUserWatchlistJobTest extends MediaWikiTestCase {

	public function setUp() {
		parent::setUp();
		self::$users['ClearUserWatchlistJobTestUser']
			= new TestUser( 'ClearUserWatchlistJobTestUser' );
		$this->runJobs();
		JobQueueGroup::destroySingletons();
	}

	private function getUser() {
		return self::$users['ClearUserWatchlistJobTestUser']->getUser();
	}

	private function runJobs( $jobLimit = 9999 ) {
		wfDebug( __CLASS__ . ' run jobs limit = ' . $jobLimit );
		$runJobs = new RunJobs;
		$runJobs->loadParamsAndArgs( null, [ 'quiet' => true, 'maxjobs' => $jobLimit ] );
		$runJobs->execute();
	}

	public function testRun() {
		wfDebug( __CLASS__ . ' testRun Start' );
		$user = $this->getUser();
		$watchedItemStore = WatchedItemStore::getDefaultInstance();
		wfDebug( __CLASS__ . ' testRun before add watches' );

		$watchedItemStore->addWatch( $user, new TitleValue( 0, 'A' ) );
		wfDebug( __CLASS__ . ' added 1, now ' . $watchedItemStore->countWatchedItems( $user ) .
			' watched' );
		$watchedItemStore->addWatch( $user, new TitleValue( 1, 'A' ) );
		wfDebug( __CLASS__ . ' added 1, now ' . $watchedItemStore->countWatchedItems( $user ) .
			' watched' );
		$watchedItemStore->addWatch( $user, new TitleValue( 0, 'B' ) );
		wfDebug( __CLASS__ . ' added 1, now ' . $watchedItemStore->countWatchedItems( $user ) .
			' watched' );
		$watchedItemStore->addWatch( $user, new TitleValue( 1, 'B' ) );
		wfDebug( __CLASS__ . ' added 1, now ' . $watchedItemStore->countWatchedItems( $user ) .
			' watched' );
		wfDebug( __CLASS__ . ' testRun after add watches' );

		JobQueueGroup::singleton()->push(
			new ClearUserWatchlistJob(
				null,
				[ 'userId' => $user->getId(), 'batchSize' => 2 ]
			)
		);


		wfDebug( __CLASS__ . ' testRun after job push' );

		$this->assertEquals( 1, JobQueueGroup::singleton()->getQueueSizes()['clearUserWatchlist'] );
		$this->assertEquals( 4, $watchedItemStore->countWatchedItems( $user ) );
		$this->runJobs( 1 );
		$this->assertEquals( 1, JobQueueGroup::singleton()->getQueueSizes()['clearUserWatchlist'] );
		$this->assertEquals( 2, $watchedItemStore->countWatchedItems( $user ) );
		$this->runJobs( 1 );
		$this->assertEquals( 1, JobQueueGroup::singleton()->getQueueSizes()['clearUserWatchlist'] );
		$this->assertEquals( 0, $watchedItemStore->countWatchedItems( $user ) );
		$this->runJobs( 1 );
		$this->assertEquals( 0, JobQueueGroup::singleton()->getQueueSizes()['clearUserWatchlist'] );

	}

}
