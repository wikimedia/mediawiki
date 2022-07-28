<?php

use MediaWiki\MainConfigNames;

/**
 * @covers ClearUserWatchlistJob
 *
 * @group JobQueue
 * @group Database
 *
 * @license GPL-2.0-or-later
 * @author Addshore
 */
class ClearUserWatchlistJobTest extends MediaWikiIntegrationTestCase {

	protected function setUp(): void {
		parent::setUp();
		self::$users['ClearUserWatchlistJobTestUser']
			= new TestUser( 'ClearUserWatchlistJobTestUser' );
	}

	private function getUser() {
		return self::$users['ClearUserWatchlistJobTestUser']->getUser();
	}

	private function getWatchedItemStore() {
		return $this->getServiceContainer()->getWatchedItemStore();
	}

	public function testRun() {
		$user = $this->getUser();
		$watchedItemStore = $this->getWatchedItemStore();

		$watchedItemStore->addWatch( $user, new TitleValue( 0, 'A' ) );
		$watchedItemStore->addWatch( $user, new TitleValue( 1, 'A' ) );
		$watchedItemStore->addWatch( $user, new TitleValue( 0, 'B' ) );
		$watchedItemStore->addWatch( $user, new TitleValue( 1, 'B' ) );

		$maxId = $watchedItemStore->getMaxId();

		$watchedItemStore->addWatch( $user, new TitleValue( 0, 'C' ) );
		$watchedItemStore->addWatch( $user, new TitleValue( 1, 'C' ) );

		$this->overrideConfigValue( MainConfigNames::UpdateRowsPerQuery, 2 );

		$jobQueueGroup = $this->getServiceContainer()->getJobQueueGroup();
		$jobQueueGroup->push(
			new ClearUserWatchlistJob( [
				'userId' => $user->getId(), 'maxWatchlistId' => $maxId,
			] )
		);

		$this->assertSame( 1, $jobQueueGroup->getQueueSizes()['clearUserWatchlist'] );
		$this->assertEquals( 6, $watchedItemStore->countWatchedItems( $user ) );
		$this->runJobs( [ 'complete' => false ], [ 'maxJobs' => 1 ] );
		$this->assertSame( 1, $jobQueueGroup->getQueueSizes()['clearUserWatchlist'] );
		$this->assertEquals( 4, $watchedItemStore->countWatchedItems( $user ) );
		$this->runJobs( [ 'complete' => false ], [ 'maxJobs' => 1 ] );
		$this->assertSame( 1, $jobQueueGroup->getQueueSizes()['clearUserWatchlist'] );
		$this->assertEquals( 2, $watchedItemStore->countWatchedItems( $user ) );
		$this->runJobs( [ 'complete' => false ], [ 'maxJobs' => 1 ] );
		$this->assertSame( 0, $jobQueueGroup->getQueueSizes()['clearUserWatchlist'] );
		$this->assertEquals( 2, $watchedItemStore->countWatchedItems( $user ) );

		$this->assertTrue( $watchedItemStore->isWatched( $user, new TitleValue( 0, 'C' ) ) );
		$this->assertTrue( $watchedItemStore->isWatched( $user, new TitleValue( 1, 'C' ) ) );
	}

	public function testRunWithWatchlistExpiry() {
		// Set up.
		$this->overrideConfigValue( MainConfigNames::WatchlistExpiry, true );
		$user = $this->getUser();
		$watchedItemStore = $this->getWatchedItemStore();

		// Add two watched items, one with an expiry.
		$watchedItemStore->addWatch( $user, new TitleValue( 0, __METHOD__ . 'no expiry' ) );
		$watchedItemStore->addWatch( $user, new TitleValue( 0, __METHOD__ . 'has expiry' ), '1 week' );

		// Get the IDs of these items.
		$itemIds = $this->db->selectFieldValues(
			[ 'watchlist' ],
			'wl_id',
			[ 'wl_user' => $user->getId() ],
			__METHOD__
		);

		// Clear the watchlist by running the job.
		$job = new ClearUserWatchlistJob( [
			'userId' => $user->getId(),
			'maxWatchlistId' => max( $itemIds ),
		] );
		$this->getServiceContainer()->getJobQueueGroup()->push( $job );
		$this->runJobs( [ 'complete' => false ], [ 'maxJobs' => 1 ] );

		// Confirm that there are now no expiry records.
		$watchedCount = $this->db->selectRowCount(
			'watchlist_expiry',
			'*',
			[ 'we_item' => $itemIds ],
			__METHOD__
		);
		$this->assertSame( 0, $watchedCount );
	}
}
