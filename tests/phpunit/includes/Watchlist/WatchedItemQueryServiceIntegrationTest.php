<?php

use MediaWiki\Api\ApiUsageException;
use MediaWiki\MainConfigNames;
use MediaWiki\Page\PageReferenceValue;
use MediaWiki\User\Options\UserOptionsLookup;
use MediaWiki\User\User;
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

	public function testGetWatchedItemsWithRecentChangeInfo_watchlistExpiry(): void {
		$store = $this->getServiceContainer()->getWatchedItemStore();
		$queryService = $this->getServiceContainer()->getWatchedItemQueryService();
		$user = self::getTestUser()->getUser();
		$options = [];
		$startFrom = null;
		$initialCount = count( $queryService->getWatchedItemsWithRecentChangeInfo( $user,
				$options, $startFrom ) );

		// Add two watched items, one of which is already expired, and check that only 1 is returned.
		$userEditTarget1 = PageReferenceValue::localReference( 0, __METHOD__ . ' no expiry 1' );
		$this->editPage( $userEditTarget1, 'First revision' );
		$store->addWatch(
			$user,
			$userEditTarget1
		);

		$userEditTarget2 = PageReferenceValue::localReference( 0, __METHOD__ . ' expired a week ago or in a week' );
		$this->editPage( $userEditTarget2, 'First revision' );
		$store->addWatch(
			$user,
			$userEditTarget2,
			'1 week ago'
		);

		$result1 = $queryService->getWatchedItemsWithRecentChangeInfo( $user, $options, $startFrom );
		$this->assertCount( $initialCount + 1, $result1 );

		// Add another of each type of item, and make sure the new results are as expected.
		$userEditTarget3 = self::makeTitle( 0, __METHOD__ . ' no expiry 2' );
		$this->editPage( $userEditTarget3, 'First revision' );
		$store->addWatch(
			$user,
			$userEditTarget3
		);

		$userEditTarget4 = self::makeTitle( 0, __METHOD__ . ' expired a week ago 2' );
		$this->editPage( $userEditTarget4, 'First revision' );
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

	public static function invalidWatchlistTokenProvider() {
		return [
			[ 'wrongToken' ],
			[ '' ],
		];
	}

	/**
	 * @dataProvider invalidWatchlistTokenProvider
	 */
	public function testGetWatchedItemsWithRecentChangeInfo_watchlistOwnerAndInvalidToken( $token ) {
		// Moved from the Unit test because the ApiUsageException call creates a Message object
		// and down the line needs MediaWikiServices
		$user = $this->createNoOpMock(
			User::class,
			[ 'isRegistered', 'getId', 'useRCPatrol' ]
		);
		$user->method( 'isRegistered' )->willReturn( true );
		$user->method( 'getId' )->willReturn( 1 );
		$user->method( 'useRCPatrol' )->willReturn( true );

		$otherUser = $this->createNoOpMock(
			User::class,
			[ 'isRegistered', 'getId', 'useRCPatrol' ]
		);
		$otherUser->method( 'isRegistered' )->willReturn( true );
		$otherUser->method( 'getId' )->willReturn( 2 );
		$otherUser->method( 'useRCPatrol' )->willReturn( true );

		$userOptionsLookup = $this->createMock( UserOptionsLookup::class );
		$userOptionsLookup->expects( $this->once() )
			->method( 'getOption' )
			->with( $otherUser, 'watchlisttoken' )
			->willReturn( '0123456789abcdef' );

		$this->setService( 'UserOptionsLookup', $userOptionsLookup );

		$queryService = $this->getServiceContainer()->getWatchedItemQueryService();

		$this->expectException( ApiUsageException::class );
		$this->expectExceptionMessage( 'Incorrect watchlist token provided' );
		$queryService->getWatchedItemsWithRecentChangeInfo(
			$user,
			[ 'watchlistOwner' => $otherUser, 'watchlistOwnerToken' => $token ]
		);
	}
}
