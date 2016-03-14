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
		$runJobs = new RunJobs;
		$runJobs->loadParamsAndArgs( null, [ 'quiet' => true, 'maxjobs' => $jobLimit ] );
		$runJobs->execute();
	}

	public function testRun() {
		$user = $this->getUser();
		$watchedItemStore = WatchedItemStore::getDefaultInstance();

		$watchedItemStore->addWatch( $user, new TitleValue( 0, 'A' ) );
		$watchedItemStore->addWatch( $user, new TitleValue( 1, 'A' ) );
		$watchedItemStore->addWatch( $user, new TitleValue( 0, 'B' ) );
		$watchedItemStore->addWatch( $user, new TitleValue( 1, 'B' ) );

		JobQueueGroup::singleton()->push(
			new ClearUserWatchlistJob(
				null,
				[ 'userId' => $user->getId(), 'batchSize' => 2 ]
			)
		);

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
