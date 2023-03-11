<?php

/**
 * @author Addshore
 * @covers ApiSetNotificationTimestamp
 * @group API
 * @group medium
 * @group Database
 */
class ApiSetNotificationTimestampIntegrationTest extends ApiTestCase {

	protected function setUp(): void {
		parent::setUp();

		$this->tablesUsed = array_merge(
			$this->tablesUsed,
			[ 'watchlist', 'watchlist_expiry' ]
		);
	}

	public function testStuff() {
		$user = $this->getTestUser()->getUser();
		$pageWatched = $this->getExistingTestPage( 'UTPage' );
		$pageNotWatched = $this->getExistingTestPage( 'UTPageNotWatched' );

		$watchlistManager = $this->getServiceContainer()->getWatchlistManager();
		$watchlistManager->addWatch( $user, $pageWatched );

		$result = $this->doApiRequestWithToken(
			[
				'action' => 'setnotificationtimestamp',
				'timestamp' => '20160101020202',
				'titles' => 'UTPage|UTPageNotWatched',
			],
			null,
			$user
		);

		$this->assertEquals(
			[
				'batchcomplete' => true,
				'setnotificationtimestamp' => [
					[ 'ns' => 0, 'title' => 'UTPage', 'notificationtimestamp' => '2016-01-01T02:02:02Z' ],
					[ 'ns' => 0, 'title' => 'UTPageNotWatched', 'notwatched' => true ]
				],
			],
			$result[0]
		);

		$watchedItemStore = $this->getServiceContainer()->getWatchedItemStore();
		$this->assertEquals(
			[ [ 'UTPage' => '20160101020202', 'UTPageNotWatched' => false, ] ],
			$watchedItemStore->getNotificationTimestampsBatch(
				$user, [ $pageWatched->getTitle(), $pageNotWatched->getTitle() ] )
		);
	}

}
