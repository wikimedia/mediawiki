<?php

namespace MediaWiki\Tests\Api;

/**
 * @author Addshore
 * @covers \MediaWiki\Api\ApiSetNotificationTimestamp
 * @group API
 * @group medium
 * @group Database
 */
class ApiSetNotificationTimestampIntegrationTest extends ApiTestCase {

	public function testStuff() {
		$user = $this->getTestUser()->getUser();
		$watchedPageTitle = 'PageWatched';
		$pageWatched = $this->getExistingTestPage( $watchedPageTitle );
		$notWatchedPageTitle = 'PageNotWatched';
		$pageNotWatched = $this->getExistingTestPage( $notWatchedPageTitle );

		$watchlistManager = $this->getServiceContainer()->getWatchlistManager();
		$watchlistManager->addWatch( $user, $pageWatched );

		$result = $this->doApiRequestWithToken(
			[
				'action' => 'setnotificationtimestamp',
				'timestamp' => '20160101020202',
				'titles' => "$watchedPageTitle|$notWatchedPageTitle",
			],
			null,
			$user
		);

		$this->assertTrue( $result[0]['batchcomplete'] );
		$this->assertArrayEquals(
			[
				[
					'ns' => NS_MAIN,
					'title' => $watchedPageTitle,
					'notificationtimestamp' => '2016-01-01T02:02:02Z'
				],
				[
					'ns' => NS_MAIN,
					'title' => $notWatchedPageTitle,
					'notwatched' => true
				],
			],
			$result[0]['setnotificationtimestamp']
		);

		$watchedItemStore = $this->getServiceContainer()->getWatchedItemStore();
		$this->assertEquals(
			[ [ $watchedPageTitle => '20160101020202', $notWatchedPageTitle => false, ] ],
			$watchedItemStore->getNotificationTimestampsBatch(
				$user, [ $pageWatched->getTitle(), $pageNotWatched->getTitle() ] )
		);
	}

}
