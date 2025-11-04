<?php

use MediaWiki\MainConfigNames;
use MediaWiki\Page\PageReferenceValue;
use MediaWiki\Watchlist\WatchedItemQueryService;

/**
 * @group Database
 *
 * @covers \MediaWiki\Watchlist\WatchedItemQueryService
 */
class WatchedItemQueryServiceIntegrationTest extends MediaWikiIntegrationTestCase {

	protected function setUp(): void {
		parent::setUp();

		$this->hideDeprecated( WatchedItemQueryService::class .
			'::getWatchedItemsWithRecentChangeInfo' );
		$this->overrideConfigValue( MainConfigNames::WatchlistExpiry, true );
	}

	private static function makeTitle( int $ns, string $dbKey ): PageReferenceValue {
		return PageReferenceValue::localReference( $ns, $dbKey );
	}

	public function testGetWatchedItemsForUser(): void {
		$store = $this->getServiceContainer()->getWatchedItemStore();
		$queryService = $this->getServiceContainer()->getWatchedItemQueryService();
		$user = self::getTestUser()->getUser();
		$initialCount = count( $store->getWatchedItemsForUser( $user ) );

		// Add two watched items, one of which is already expired, and check that only 1 is returned.
		$store->addWatch(
			$user,
			self::makeTitle( 0, __METHOD__ . ' no expiry 1' )
		);
		$store->addWatch(
			$user,
			self::makeTitle( 0, __METHOD__ . ' expired a week ago or in a week' ),
			'1 week ago'
		);
		$result1 = $queryService->getWatchedItemsForUser( $user );
		$this->assertCount( $initialCount + 1, $result1, "User ID: " . $user->getId() );

		// Add another of each type of item, and make sure the new results are as expected.
		$store->addWatch(
			$user,
			self::makeTitle( 0, __METHOD__ . ' no expiry 2' )
		);
		$store->addWatch(
			$user,
			self::makeTitle( 0, __METHOD__ . ' expired a week ago 2' ),
			'1 week ago'
		);
		$result2 = $queryService->getWatchedItemsForUser( $user );
		$this->assertCount( $initialCount + 2, $result2 );

		// Make one of the expired items permanent, and check again.
		$store->addWatch(
			$user,
			self::makeTitle( 0, __METHOD__ . ' expired a week ago 2' ),
			'infinity'
		);
		$result3 = $queryService->getWatchedItemsForUser( $user );
		$this->assertCount( $initialCount + 3, $result3 );

		// Make the other expired item expire in a week's time, and make sure it appears in the list.
		$store->addWatch(
			$user,
			self::makeTitle( 0, __METHOD__ . ' expired a week ago or in a week' ),
			'1 week'
		);
		$result4 = $queryService->getWatchedItemsForUser( $user );
		$this->assertCount( $initialCount + 4, $result4 );
	}

	public function testGetWatchedItemsForUserWithExpiriesDisabled() {
		$this->overrideConfigValue( MainConfigNames::WatchlistExpiry, false );
		$store = $this->getServiceContainer()->getWatchedItemStore();
		$queryService = $this->getServiceContainer()->getWatchedItemQueryService();
		$user = self::getTestUser()->getUser();
		$initialCount = count( $store->getWatchedItemsForUser( $user ) );
		$store->addWatch( $user, PageReferenceValue::localReference( 0, __METHOD__ ), '1 week ago' );
		$result = $queryService->getWatchedItemsForUser( $user );
		$this->assertCount( $initialCount + 1, $result );
	}
}
