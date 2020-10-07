<?php

use MediaWiki\MediaWikiServices;

/**
 * @group Database
 *
 * @covers WatchedItemQueryService
 */
class WatchedItemQueryServiceIntegrationTest extends MediaWikiIntegrationTestCase {

	protected function setUp(): void {
		parent::setUp();
		self::$users[ 'WatchedItemQueryServiceIntegrationTestUser' ]
			= new TestUser( 'WatchedItemQueryServiceIntegrationTestUser' );

		$this->setMwGlobals( 'wgWatchlistExpiry', true );
	}

	public function testGetWatchedItemsForUser(): void {
		$store = MediaWikiServices::getInstance()->getWatchedItemStore();
		$queryService = MediaWikiServices::getInstance()->getWatchedItemQueryService();
		$user = self::$users[ 'WatchedItemQueryServiceIntegrationTestUser' ]->getUser();
		$initialCount = count( $store->getWatchedItemsForUser( $user ) );

		// Add two watched items, one of which is already expired, and check that only 1 is returned.
		$store->addWatch(
			$user,
			new TitleValue( 0, __METHOD__ . ' no expiry 1' )
		);
		$store->addWatch(
			$user,
			new TitleValue( 0, __METHOD__ . ' expired a week ago or in a week' ),
			'1 week ago'
		);
		$result1 = $queryService->getWatchedItemsForUser( $user );
		$this->assertCount( $initialCount + 1, $result1 );

		// Add another of each type of item, and make sure the new results are as expected.
		$store->addWatch(
			$user,
			new TitleValue( 0, __METHOD__ . ' no expiry 2' )
		);
		$store->addWatch(
			$user,
			new TitleValue( 0, __METHOD__ . ' expired a week ago 2' ),
			'1 week ago'
		);
		$result2 = $queryService->getWatchedItemsForUser( $user );
		$this->assertCount( $initialCount + 2, $result2 );

		// Make one of the expired items permanent, and check again.
		$store->addWatch(
			$user,
			new TitleValue( 0, __METHOD__ . ' expired a week ago 2' ),
			'infinity'
		);
		$result3 = $queryService->getWatchedItemsForUser( $user );
		$this->assertCount( $initialCount + 3, $result3 );

		// Make the other expired item expire in a week's time, and make sure it appears in the list.
		$store->addWatch(
			$user,
			new TitleValue( 0, __METHOD__ . ' expired a week ago or in a week' ),
			'1 week'
		);
		$result4 = $queryService->getWatchedItemsForUser( $user );
		$this->assertCount( $initialCount + 4, $result4 );
	}

	public function testGetWatchedItemsForUserWithExpiriesDisabled() {
		$this->setMwGlobals( 'wgWatchlistExpiry', false );
		$store = MediaWikiServices::getInstance()->getWatchedItemStore();
		$queryService = MediaWikiServices::getInstance()->getWatchedItemQueryService();
		$user = self::$users[ 'WatchedItemQueryServiceIntegrationTestUser' ]->getUser();
		$initialCount = count( $store->getWatchedItemsForUser( $user ) );
		$store->addWatch( $user, new TitleValue( 0, __METHOD__ ), '1 week ago' );
		$result = $queryService->getWatchedItemsForUser( $user );
		$this->assertCount( $initialCount + 1, $result );
	}

	public function testGetWatchedItemsWithRecentChangeInfo_watchlistExpiry(): void {
		$store = MediaWikiServices::getInstance()->getWatchedItemStore();
		$queryService = MediaWikiServices::getInstance()->getWatchedItemQueryService();
		$user = self::$users[ 'WatchedItemQueryServiceIntegrationTestUser' ]->getUser();
		$options = [];
		$startFrom = null;
		$initialCount = count( $queryService->getWatchedItemsWithRecentChangeInfo( $user,
				$options, $startFrom ) );

		// Add two watched items, one of which is already expired, and check that only 1 is returned.
		$userEditTarget1 = new TitleValue( 0, __METHOD__ . ' no expiry 1' );
		$this->editPage( $userEditTarget1->getDBkey(), 'First Revision' );
		$store->addWatch(
			$user,
			$userEditTarget1
		);

		$userEditTarget2 = new TitleValue( 0, __METHOD__ . ' expired a week ago or in a week' );
		$this->editPage( $userEditTarget2->getDBkey(), 'First Revision' );
		$store->addWatch(
			$user,
			$userEditTarget2,
			'1 week ago'
		);

		$result1 = $queryService->getWatchedItemsWithRecentChangeInfo( $user, $options, $startFrom );
		$this->assertCount( $initialCount + 1, $result1 );

		// Add another of each type of item, and make sure the new results are as expected.
		$userEditTarget3 = new TitleValue( 0, __METHOD__ . ' no expiry 2' );
		$this->editPage( $userEditTarget3->getDBkey(), 'First Revision' );
		$store->addWatch(
			$user,
			$userEditTarget3
		);

		$userEditTarget4 = new TitleValue( 0, __METHOD__ . ' expired a week ago 2' );
		$this->editPage( $userEditTarget4->getDBkey(), 'First Revision' );
		$store->addWatch(
			$user,
			$userEditTarget4,
			'1 week ago'
		);
		$result2 = $queryService->getWatchedItemsWithRecentChangeInfo( $user );
		$this->assertCount( $initialCount + 2, $result2 );

		// Make one of the expired items permanent, and check again.
		$store->addWatch(
			$user,
			$userEditTarget4,
			'infinity'
		);
		$result3 = $queryService->getWatchedItemsWithRecentChangeInfo( $user );
		$this->assertCount( $initialCount + 3, $result3 );

		// Make the other expired item expire in a week's time, and make sure it appears in the list.
		$store->addWatch(
			$user,
			$userEditTarget2,
			'1 week'
		);
		$result4 = $queryService->getWatchedItemsWithRecentChangeInfo( $user );
		$this->assertCount( $initialCount + 4, $result4 );
	}
}
