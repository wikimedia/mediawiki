<?php

use MediaWiki\Cache\LinkBatchFactory;
use MediaWiki\Config\ServiceOptions;
use MediaWiki\Linker\LinkTarget;
use MediaWiki\Page\PageIdentityValue;
use MediaWiki\Revision\RevisionLookup;
use MediaWiki\Revision\RevisionRecord;
use MediaWiki\User\UserFactory;
use MediaWiki\User\UserIdentity;
use MediaWiki\User\UserIdentityValue;
use PHPUnit\Framework\MockObject\MockObject;
use Wikimedia\Rdbms\IDatabase;
use Wikimedia\Rdbms\LBFactory;
use Wikimedia\Rdbms\LoadBalancer;
use Wikimedia\TestingAccessWrapper;

/**
 * @author Addshore
 * @author DannyS712
 *
 * @covers WatchedItemStore
 */
class WatchedItemStoreUnitTest extends MediaWikiUnitTestCase {
	use MockTitleTrait;

	/**
	 * @return MockObject|IDatabase
	 */
	private function getMockDb() {
		return $this->createMock( IDatabase::class );
	}

	/**
	 * @param IDatabase $mockDb
	 * @param string|null $expectedConnectionType
	 * @return MockObject|LoadBalancer
	 */
	private function getMockLoadBalancer(
		$mockDb,
		$expectedConnectionType = null
	) {
		$mock = $this->getMockBuilder( LoadBalancer::class )
			->disableOriginalConstructor()
			->getMock();
		if ( $expectedConnectionType !== null ) {
			$mock->expects( $this->any() )
				->method( 'getConnectionRef' )
				->with( $expectedConnectionType )
				->will( $this->returnValue( $mockDb ) );
		} else {
			$mock->expects( $this->any() )
				->method( 'getConnectionRef' )
				->will( $this->returnValue( $mockDb ) );
		}
		return $mock;
	}

	/**
	 * @param IDatabase $mockDb
	 * @param string|null $expectedConnectionType
	 * @return MockObject|LBFactory
	 */
	private function getMockLBFactory(
		$mockDb,
		$expectedConnectionType = null
	) {
		$loadBalancer = $this->getMockLoadBalancer( $mockDb, $expectedConnectionType );
		$mock = $this->getMockBuilder( LBFactory::class )
			->disableOriginalConstructor()
			->getMock();
		$mock->expects( $this->any() )
			->method( 'getMainLB' )
			->will( $this->returnValue( $loadBalancer ) );
		return $mock;
	}

	/**
	 * The job queue is used in three different places - two "push" calls, and a
	 * "lazyPush" call - we don't test any of the "push" calls, so the callback
	 * can just run the job, but we do test the "lazyPush" call, and so the test
	 * that is using this may want to do something other than just run the job, since
	 * for ActivityUpdateJob instances this results in using global functions, which we
	 * cannot do in this unit test
	 *
	 * @param bool $mockLazyPush whether to add mock behavior for "lazyPush"
	 * @return MockObject|JobQueueGroup
	 */
	private function getMockJobQueueGroup( $mockLazyPush = true ) {
		$mock = $this->getMockBuilder( JobQueueGroup::class )
			->disableOriginalConstructor()
			->getMock();
		$mock->expects( $this->any() )
			->method( 'push' )
			->will( $this->returnCallback( static function ( Job $job ) {
				$job->run();
			} ) );
		if ( $mockLazyPush ) {
			$mock->expects( $this->any() )
				->method( 'lazyPush' )
				->will( $this->returnCallback( static function ( Job $job ) {
					$job->run();
				} ) );
		}
		return $mock;
	}

	/**
	 * @return MockObject|HashBagOStuff
	 */
	private function getMockCache() {
		$mock = $this->getMockBuilder( HashBagOStuff::class )
			->disableOriginalConstructor()
			->setMethods( [ 'get', 'set', 'delete', 'makeKey' ] )
			->getMock();
		$mock->expects( $this->any() )
			->method( 'makeKey' )
			->will( $this->returnCallback( static function ( ...$args ) {
				return implode( ':', $args );
			} ) );
		return $mock;
	}

	/**
	 * @param bool $readOnly
	 * @return MockObject|ReadOnlyMode
	 */
	private function getMockReadOnlyMode( $readOnly = false ) {
		$mock = $this->getMockBuilder( ReadOnlyMode::class )
			->disableOriginalConstructor()
			->getMock();
		$mock->expects( $this->any() )
			->method( 'isReadOnly' )
			->will( $this->returnValue( $readOnly ) );
		return $mock;
	}

	/**
	 * Assumes that only getSubjectPage and getTalkPage will ever be called, and everything passed
	 * to them will have namespace 0.
	 * @return NamespaceInfo
	 */
	private function getMockNsInfo() : NamespaceInfo {
		$mock = $this->createMock( NamespaceInfo::class );
		$mock->method( 'getSubjectPage' )->will( $this->returnArgument( 0 ) );
		$mock->method( 'getTalkPage' )->will( $this->returnCallback(
				static function ( $target ) {
					return new TitleValue( 1, $target->getDbKey() );
				}
			) );
		$mock->method( 'getSubject' )->willReturn( 0 );
		$mock->method( 'getTalk' )->willReturn( 1 );
		$mock->expects( $this->never() )
			->method( $this->anythingBut( 'getSubjectPage', 'getTalkPage', 'getSubject', 'getTalk' ) );
		return $mock;
	}

	/**
	 * No methods may be called except provided callbacks, if any.
	 *
	 * @param array $callbacks Keys are method names, values are callbacks
	 * @param array $counts Keys are method names, values are expected number of times to be called
	 *   (default is any number is okay)
	 * @return RevisionLookup
	 */
	private function getMockRevisionLookup(
		array $callbacks = [], array $counts = []
	) : RevisionLookup {
		$mock = $this->createMock( RevisionLookup::class );
		foreach ( $callbacks as $method => $callback ) {
			$count = isset( $counts[$method] ) ? $this->exactly( $counts[$method] ) : $this->any();
			$mock->expects( $count )
				->method( $method )
				->will( $this->returnCallback( $callbacks[$method] ) );
		}
		$mock->expects( $this->never() )
			->method( $this->anythingBut( ...array_keys( $callbacks ) ) );
		return $mock;
	}

	/**
	 * @param IDatabase $mockDb
	 * @return MockObject|LinkBatchFactory
	 */
	private function getMockLinkBatchFactory( $mockDb ) {
		return new LinkBatchFactory(
			$this->createMock( LinkCache::class ),
			$this->createMock( TitleFormatter::class ),
			$this->createMock( Language::class ),
			$this->createMock( GenderCache::class ),
			$this->getMockLoadBalancer( $mockDb )
		);
	}

	/**
	 * @param User[] $users
	 * @return MockObject|UserFactory
	 */
	private function getUserFactory( array $users = [] ) {
		// UserFactory is only needed for newFromId. $usersById should be an array
		// with the keys being the ids to support, and the values being User objects
		// with the corresponding id. Used for WatchedItemStore::resetNotificationTimestamp
		// which needs full User objects for a hook.
		$usersById = [];
		foreach ( $users as $user ) {
			$usersById[ $user->getId() ] = $user;
		}
		$userFactory = $this->createNoOpMock( UserFactory::class, [ 'newFromId' ] );
		$userFactory->method( 'newFromId' )
			->willReturnCallback(
				static function ( $userId ) use ( $usersById ) {
					// will result in an error if the array key is not set
					return $usersById[ $userId ];
				}
			);
		return $userFactory;
	}

	/**
	 * @param UserIdentityValue $userIdentity
	 * @return MockObject|User
	 */
	private function getMockUser( UserIdentityValue $userIdentity ) {
		// for use in the mock UserFactory. Needs to support equals() for ::resetNotificationTimestamp
		$user = $this->createMock( User::class );
		$user->method( 'getId' )->willReturn( $userIdentity->getId() );
		$user->method( 'getName' )->willReturn( $userIdentity->getName() );
		$user->method( 'equals' )->willReturnCallback(
			static function ( UserIdentity $otherUser ) use ( $userIdentity ) {
				// $user's name is the same as $userIdentity's
				return $otherUser->getName() === $userIdentity->getName();
			}
		);
		return $user;
	}

	/**
	 * @param LinkTarget|PageIdentity|null $target
	 * @param Title|null $title
	 * @return MockObject|TitleFactory
	 */
	private function getTitleFactory( $target = null, $title = null ) {
		// TitleFactory only needed for castFromLinkTarget or castFromPageIdentity - if this is
		// called with a link target or page identity and a title, the mock expects the function
		// invocation and returns the title, otherwise the mock expects never to be called.
		// If no title is provided here, we create a placeholder mock that passes the ->equals()
		// check, and thats it
		$titleFactory = $this->createNoOpMock(
			TitleFactory::class,
			[
				'castFromLinkTarget',
				'castFromPageIdentity'
			] );
		if ( $target !== null ) {
			if ( $title === null ) {
				$title = $this->makeMockTitle(
					$target->getDBkey(),
					[
						'namespace' => $target->getNamespace()
					]
				);
			}
			$title->method( 'equals' )
				->with( $target )
				->willReturn( true );
			if ( $target instanceof LinkTarget ) {
				$titleFactory->method( 'castFromLinkTarget' )
					->with( $target )
					->willReturn( $title );
				$titleFactory->expects( $this->never() )->method( 'castFromPageIdentity' );
			} else {
				$titleFactory->method( 'castFromPageIdentity' )
					->with( $target )
					->willReturn( $title );
				$titleFactory->expects( $this->never() )->method( 'castFromLinkTarget' );
			}
		} else {
			$titleFactory->expects( $this->never() )->method( 'castFromLinkTarget' );
			$titleFactory->expects( $this->never() )->method( 'castFromPageIdentity' );
		}
		return $titleFactory;
	}

	/**
	 * @param array $mocks Associative array providing mocks to use when constructing the
	 *   WatchedItemStore. Anything not provided will fall back to a default. Valid keys:
	 *     * lbFactory
	 *     * db
	 *     * queueGroup
	 *     * cache
	 *     * readOnlyMode
	 *     * nsInfo
	 *     * revisionLookup
	 *     * userFactory
	 *     * titleFactory
	 *     * expiryEnabled
	 *     * maxExpiryDuration
	 *     * watchlistPurgeRate
	 * @return WatchedItemStore
	 */
	private function newWatchedItemStore( array $mocks = [] ) : WatchedItemStore {
		$options = new ServiceOptions( WatchedItemStore::CONSTRUCTOR_OPTIONS, [
			'UpdateRowsPerQuery' => 1000,
			'WatchlistExpiry' => $mocks['expiryEnabled'] ?? true,
			'WatchlistExpiryMaxDuration' => $mocks['maxExpiryDuration'] ?? null,
			'WatchlistPurgeRate' => $mocks['watchlistPurgeRate'] ?? 0.1,
		] );

		$db = $mocks['db'] ?? $this->getMockDb();
		return new WatchedItemStore(
			$options,
			$mocks['lbFactory'] ??
				$this->getMockLBFactory( $db ),
			$mocks['queueGroup'] ?? $this->getMockJobQueueGroup(),
			new HashBagOStuff(),
			$mocks['cache'] ?? $this->getMockCache(),
			$mocks['readOnlyMode'] ?? $this->getMockReadOnlyMode(),
			$mocks['nsInfo'] ?? $this->getMockNsInfo(),
			$mocks['revisionLookup'] ?? $this->getMockRevisionLookup(),
			$this->createHookContainer(),
			$this->getMockLinkBatchFactory( $db ),
			$mocks['userFactory'] ?? $this->getUserFactory(),
			$mocks['titleFactory'] ?? $this->getTitleFactory()
		);
	}

	public function testClearWatchedItems() {
		$user = new UserIdentityValue( 7, 'MockUser' );

		$mockDb = $this->getMockDb();
		$mockDb->expects( $this->once() )
			->method( 'selectField' )
			->with(
				[ 'watchlist' ],
				'COUNT(*)',
				[
					'wl_user' => $user->getId(),
				],
				$this->isType( 'string' )
			)
			->will( $this->returnValue( 12 ) );
		$mockDb->expects( $this->once() )
			->method( 'delete' )
			->with(
				'watchlist',
				[ 'wl_user' => 7 ],
				$this->isType( 'string' )
			);

		$mockCache = $this->getMockCache();
		$mockCache->expects( $this->never() )->method( 'get' );
		$mockCache->expects( $this->never() )->method( 'set' );
		$mockCache->expects( $this->once() )
			->method( 'delete' )
			->with( 'RM-KEY' );

		$store = $this->newWatchedItemStore( [
			'db' => $mockDb,
			'cache' => $mockCache,
			'expiryEnabled' => false ] );
		TestingAccessWrapper::newFromObject( $store )
			->cacheIndex = [ 0 => [ 'F' => [ 7 => 'RM-KEY', 9 => 'KEEP-KEY' ] ] ];

		$this->assertTrue( $store->clearUserWatchedItems( $user ) );
	}

	public function testClearWatchedItems_watchlistExpiry() {
		$user = new UserIdentityValue( 7, 'MockUser' );

		$mockDb = $this->getMockDb();
		// Select watchlist IDs.
		$mockDb->expects( $this->once() )
			->method( 'selectFieldValues' )
			->willReturn( [ 1, 2 ] );

		$mockDb->expects( $this->exactly( 2 ) )
			->method( 'delete' )
			->withConsecutive(
				[
					'watchlist',
					[ 'wl_id' => [ 1, 2 ] ]
				],
				[
					'watchlist_expiry',
					[ 'we_item' => [ 1, 2 ] ]
				]
			);

		$mockCache = $this->getMockCache();
		$mockCache->expects( $this->never() )->method( 'get' );
		$mockCache->expects( $this->never() )->method( 'set' );
		$mockCache->expects( $this->once() )
			->method( 'delete' )
			->with( 'RM-KEY' );

		$store = $this->newWatchedItemStore( [
			'db' => $mockDb,
			'cache' => $mockCache,
			'expiryEnabled' => true ] );
		TestingAccessWrapper::newFromObject( $store )
			->cacheIndex = [ 0 => [ 'F' => [ 7 => 'RM-KEY', 9 => 'KEEP-KEY' ] ] ];

		$this->assertTrue( $store->clearUserWatchedItems( $user ) );
	}

	public function testClearWatchedItems_tooManyItemsWatched() {
		$user = new UserIdentityValue( 7, 'MockUser' );

		$mockDb = $this->getMockDb();
		$mockDb->expects( $this->once() )
			->method( 'selectField' )
			->with(
				[ 'watchlist' ],
				'COUNT(*)',
				[
					'wl_user' => $user->getId(),
				],
				$this->isType( 'string' )
			)
			->will( $this->returnValue( 99999 ) );

		$mockCache = $this->getMockCache();
		$mockCache->expects( $this->never() )->method( 'get' );
		$mockCache->expects( $this->never() )->method( 'set' );
		$mockCache->expects( $this->never() )->method( 'delete' );

		$store = $this->newWatchedItemStore( [
			'db' => $mockDb,
			'cache' => $mockCache,
			'expiryEnabled' => false ] );

		$this->assertFalse( $store->clearUserWatchedItems( $user ) );
	}

	public function testCountWatchedItems() {
		$user = new UserIdentityValue( 1, 'MockUser' );

		$mockDb = $this->getMockDb();
		$mockDb->expects( $this->once() )
			->method( 'addQuotes' )
			->willReturn( '20200101000000' );
		$mockDb->expects( $this->exactly( 1 ) )
			->method( 'selectField' )
			->with(
				[ 'watchlist', 'watchlist_expiry' ],
				'COUNT(*)',
				[
					'wl_user' => $user->getId(),
					'we_expiry IS NULL OR we_expiry > 20200101000000'
				],
				$this->isType( 'string' )
			)
			->will( $this->returnValue( '12' ) );

		$mockCache = $this->getMockCache();
		$mockCache->expects( $this->never() )->method( 'get' );
		$mockCache->expects( $this->never() )->method( 'set' );
		$mockCache->expects( $this->never() )->method( 'delete' );

		$store = $this->newWatchedItemStore( [ 'db' => $mockDb, 'cache' => $mockCache ] );

		$this->assertEquals( 12, $store->countWatchedItems( $user ) );
	}

	public function provideTestPageFactory() {
		yield [ function ( $pageId, $namespace, $dbKey ) {
			return new TitleValue( $namespace, $dbKey );
		} ];
		yield [ function ( $pageId, $namespace, $dbKey ) {
			return new PageIdentityValue( $pageId, $namespace, $dbKey, PageIdentityValue::LOCAL );
		} ];
		yield [ function ( $pageId, $namespace, $dbKey ) {
			return $this->makeMockTitle( $dbKey, [
				'id' => $pageId,
				'namespace' => $namespace
			] );
		} ];
	}

	/**
	 * @dataProvider provideTestPageFactory
	 */
	public function testCountWatchers( $testPageFactory ) {
		$titleValue = $testPageFactory( 100, 0, 'SomeDbKey' );

		$mockDb = $this->getMockDb();
		$mockDb->expects( $this->once() )
			->method( 'addQuotes' )
			->willReturn( '20200101000000' );
		$mockDb->expects( $this->exactly( 1 ) )
			->method( 'selectField' )
			->with(
				[ 'watchlist', 'watchlist_expiry' ],
				'COUNT(*)',
				[
					'wl_namespace' => $titleValue->getNamespace(),
					'wl_title' => $titleValue->getDBkey(),
					'we_expiry IS NULL OR we_expiry > 20200101000000'
				],
				$this->isType( 'string' )
			)
			->will( $this->returnValue( '7' ) );

		$mockCache = $this->getMockCache();
		$mockCache->expects( $this->never() )->method( 'get' );
		$mockCache->expects( $this->never() )->method( 'set' );
		$mockCache->expects( $this->never() )->method( 'delete' );

		$store = $this->newWatchedItemStore( [ 'db' => $mockDb, 'cache' => $mockCache ] );

		$this->assertEquals( 7, $store->countWatchers( $titleValue ) );
	}

	/**
	 * @dataProvider provideTestPageFactory
	 */
	public function testCountWatchersMultiple( $testPageFactory ) {
		$titleValues = [
			$testPageFactory( 100, 0, 'SomeDbKey' ),
			$testPageFactory( 101, 0, 'OtherDbKey' ),
			$testPageFactory( 102, 1, 'AnotherDbKey' ),
		];

		$mockDb = $this->getMockDb();

		$dbResult = [
			(object)[ 'wl_title' => 'SomeDbKey', 'wl_namespace' => '0', 'watchers' => '100' ],
			(object)[ 'wl_title' => 'OtherDbKey', 'wl_namespace' => '0', 'watchers' => '300' ],
			(object)[ 'wl_title' => 'AnotherDbKey', 'wl_namespace' => '1', 'watchers' => '500' ],
		];
		$mockDb->expects( $this->once() )
			->method( 'makeWhereFrom2d' )
			->with(
				[ [ 'SomeDbKey' => 1, 'OtherDbKey' => 1 ], [ 'AnotherDbKey' => 1 ] ],
				$this->isType( 'string' ),
				$this->isType( 'string' )
				)
			->will( $this->returnValue( 'makeWhereFrom2d return value' ) );

		$mockDb->expects( $this->once() )
			->method( 'addQuotes' )
			->willReturn( '20200101000000' );

		$mockDb->expects( $this->once() )
			->method( 'select' )
			->with(
				[ 'watchlist', 'watchlist_expiry' ],
				[ 'wl_title', 'wl_namespace', 'watchers' => 'COUNT(*)' ],
				[
					'makeWhereFrom2d return value',
					'we_expiry IS NULL OR we_expiry > 20200101000000'
				],
				$this->isType( 'string' ),
				[
					'GROUP BY' => [ 'wl_namespace', 'wl_title' ],
				]
			)
			->will(
				$this->returnValue( $dbResult )
			);

		$mockCache = $this->getMockCache();
		$mockCache->expects( $this->never() )->method( 'get' );
		$mockCache->expects( $this->never() )->method( 'set' );
		$mockCache->expects( $this->never() )->method( 'delete' );

		$store = $this->newWatchedItemStore( [ 'db' => $mockDb, 'cache' => $mockCache ] );

		$expected = [
			0 => [ 'SomeDbKey' => 100, 'OtherDbKey' => 300 ],
			1 => [ 'AnotherDbKey' => 500 ],
		];
		$this->assertEquals( $expected, $store->countWatchersMultiple( $titleValues ) );
	}

	public function provideIntWithDbUnsafeVersion() {
		return [
			[ 50 ],
			[ "50; DROP TABLE watchlist;\n--" ],
		];
	}

	public function provideTestPageFactoryAndIntWithDbUnsafeVersion() {
		foreach ( $this->provideIntWithDBUnsafeVersion() as $dbint ) {
			foreach ( $this->provideTestPageFactory() as $testPageFactory ) {
				yield [ $dbint[0], $testPageFactory[0] ];
			}
		}
	}

	/**
	 * @dataProvider provideTestPageFactoryAndIntWithDbUnsafeVersion
	 */
	public function testCountWatchersMultiple_withMinimumWatchers( $minWatchers, $testPageFactory ) {
		$titleValues = [
			$testPageFactory( 100, 0, 'SomeDbKey' ),
			$testPageFactory( 101, 0, 'OtherDbKey' ),
			$testPageFactory( 102, 1, 'AnotherDbKey' ),
		];

		$mockDb = $this->getMockDb();

		$dbResult = [
			(object)[ 'wl_title' => 'SomeDbKey', 'wl_namespace' => '0', 'watchers' => '100' ],
			(object)[ 'wl_title' => 'OtherDbKey', 'wl_namespace' => '0', 'watchers' => '300' ],
			(object)[ 'wl_title' => 'AnotherDbKey', 'wl_namespace' => '1', 'watchers' => '500' ],
		];

		$mockDb->expects( $this->once() )
			->method( 'makeWhereFrom2d' )
			->with(
				[ [ 'SomeDbKey' => 1, 'OtherDbKey' => 1 ], [ 'AnotherDbKey' => 1 ] ],
				$this->isType( 'string' ),
				$this->isType( 'string' )
			)
			->will( $this->returnValue( 'makeWhereFrom2d return value' ) );

		$mockDb->expects( $this->once() )
			->method( 'addQuotes' )
			->willReturn( '20200101000000' );

		$mockDb->expects( $this->once() )
			->method( 'select' )
			->with(
				[ 'watchlist', 'watchlist_expiry' ],
				[ 'wl_title', 'wl_namespace', 'watchers' => 'COUNT(*)' ],
				[
					'makeWhereFrom2d return value',
					'we_expiry IS NULL OR we_expiry > 20200101000000'
				],
				$this->isType( 'string' ),
				[
					'GROUP BY' => [ 'wl_namespace', 'wl_title' ],
					'HAVING' => 'COUNT(*) >= 50',
				]
			)
			->will(
				$this->returnValue( $dbResult )
			);

		$mockCache = $this->getMockCache();
		$mockCache->expects( $this->never() )->method( 'get' );
		$mockCache->expects( $this->never() )->method( 'set' );
		$mockCache->expects( $this->never() )->method( 'delete' );

		$store = $this->newWatchedItemStore( [ 'db' => $mockDb, 'cache' => $mockCache ] );

		$expected = [
			0 => [ 'SomeDbKey' => 100, 'OtherDbKey' => 300 ],
			1 => [ 'AnotherDbKey' => 500 ],
		];
		$this->assertEquals(
			$expected,
			$store->countWatchersMultiple( $titleValues, [ 'minimumWatchers' => $minWatchers ] )
		);
	}

	/**
	 * @dataProvider provideTestPageFactory
	 */
	public function testCountVisitingWatchers( $testPageFactory ) {
		$titleValue = $testPageFactory( 100, 0, 'SomeDbKey' );

		$mockDb = $this->getMockDb();

		$mockDb->expects( $this->exactly( 1 ) )
			->method( 'selectField' )
			->with(
				[ 'watchlist', 'watchlist_expiry' ],
				'COUNT(*)',
				[
					'wl_namespace' => $titleValue->getNamespace(),
					'wl_title' => $titleValue->getDBkey(),
					'wl_notificationtimestamp >= \'TS111TS\' OR wl_notificationtimestamp IS NULL',
					'we_expiry IS NULL OR we_expiry > \'20200101000000\''
				],
				$this->isType( 'string' )
			)
			->will( $this->returnValue( '7' ) );

		$mockDb->expects( $this->exactly( 2 ) )
			->method( 'addQuotes' )
			->will( $this->returnCallback( static function ( $value ) {
				return "'$value'";
			} ) );

		$mockDb->expects( $this->exactly( 2 ) )
			->method( 'timestamp' )
			->will( $this->returnCallback( static function ( $value ) {
				if ( $value === 0 ) {
					return '20200101000000';
				}
				return 'TS' . $value . 'TS';
			} ) );

		$mockCache = $this->getMockCache();
		$mockCache->expects( $this->never() )->method( 'set' );
		$mockCache->expects( $this->never() )->method( 'get' );
		$mockCache->expects( $this->never() )->method( 'delete' );

		$store = $this->newWatchedItemStore( [ 'db' => $mockDb, 'cache' => $mockCache ] );

		$this->assertEquals( 7, $store->countVisitingWatchers( $titleValue, '111' ) );
	}

	/**
	 * @dataProvider provideTestPageFactory
	 */
	public function testCountVisitingWatchersMultiple( $testPageFactory ) {
		$titleValuesWithThresholds = [
			[ $testPageFactory( 100, 0, 'SomeDbKey' ), '111' ],
			[ $testPageFactory( 101, 0, 'OtherDbKey' ), '111' ],
			[ $testPageFactory( 102, 1, 'AnotherDbKey' ), '123' ],
		];

		$dbResult = [
			(object)[ 'wl_title' => 'SomeDbKey', 'wl_namespace' => '0', 'watchers' => '100' ],
			(object)[ 'wl_title' => 'OtherDbKey', 'wl_namespace' => '0', 'watchers' => '300' ],
			(object)[ 'wl_title' => 'AnotherDbKey', 'wl_namespace' => '1', 'watchers' => '500' ],
		];
		$mockDb = $this->getMockDb();
		$mockDb->expects( $this->exactly( 2 * 3 + 1 ) )
			->method( 'addQuotes' )
			->will( $this->returnCallback( static function ( $value ) {
				return "'$value'";
			} ) );

		$mockDb->expects( $this->exactly( 4 ) )
			->method( 'timestamp' )
			->will( $this->returnCallback( static function ( $value ) {
				if ( $value === 0 ) {
					return '20200101000000';
				}
				return 'TS' . $value . 'TS';
			} ) );

		$mockDb->expects( $this->any() )
			->method( 'makeList' )
			->with(
				$this->isType( 'array' ),
				$this->isType( 'int' )
			)
			->will( $this->returnCallback( static function ( $a, $conj ) {
				$sqlConj = $conj === LIST_AND ? ' AND ' : ' OR ';
				return implode( $sqlConj, array_map( static function ( $s ) {
					return '(' . $s . ')';
				}, $a
				) );
			} ) );
		$mockDb->expects( $this->never() )
			->method( 'makeWhereFrom2d' );

		$expectedCond =
			'((wl_namespace = 0) AND (' .
			"(((wl_title = 'SomeDbKey') AND (" .
			"(wl_notificationtimestamp >= 'TS111TS') OR (wl_notificationtimestamp IS NULL)" .
			')) OR (' .
			"(wl_title = 'OtherDbKey') AND (" .
			"(wl_notificationtimestamp >= 'TS111TS') OR (wl_notificationtimestamp IS NULL)" .
			'))))' .
			') OR ((wl_namespace = 1) AND (' .
			"(((wl_title = 'AnotherDbKey') AND (" .
			"(wl_notificationtimestamp >= 'TS123TS') OR (wl_notificationtimestamp IS NULL)" .
			')))))';
		$mockDb->expects( $this->once() )
			->method( 'select' )
			->with(
				[ 'watchlist', 'watchlist_expiry' ],
				[ 'wl_namespace', 'wl_title', 'watchers' => 'COUNT(*)' ],
				[
					$expectedCond,
					'we_expiry IS NULL OR we_expiry > \'20200101000000\''
				],
				$this->isType( 'string' ),
				[
					'GROUP BY' => [ 'wl_namespace', 'wl_title' ],
				]
			)
			->will(
				$this->returnValue( $dbResult )
			);

		$mockCache = $this->getMockCache();
		$mockCache->expects( $this->never() )->method( 'get' );
		$mockCache->expects( $this->never() )->method( 'set' );
		$mockCache->expects( $this->never() )->method( 'delete' );

		$store = $this->newWatchedItemStore( [ 'db' => $mockDb, 'cache' => $mockCache ] );

		$expected = [
			0 => [ 'SomeDbKey' => 100, 'OtherDbKey' => 300 ],
			1 => [ 'AnotherDbKey' => 500 ],
		];
		$this->assertEquals(
			$expected,
			$store->countVisitingWatchersMultiple( $titleValuesWithThresholds )
		);
	}

	/**
	 * @dataProvider provideTestPageFactory
	 */
	public function testCountVisitingWatchersMultiple_withMissingTargets( $testPageFactory ) {
		$titleValuesWithThresholds = [
			[ $testPageFactory( 100, 0, 'SomeDbKey' ), '111' ],
			[ $testPageFactory( 101, 0, 'OtherDbKey' ), '111' ],
			[ $testPageFactory( 102, 1, 'AnotherDbKey' ), '123' ],
			[ new TitleValue( 0, 'SomeNotExisitingDbKey' ), null ],
			[ new TitleValue( 0, 'OtherNotExisitingDbKey' ), null ],
		];

		$dbResult = [
			(object)[ 'wl_title' => 'SomeDbKey', 'wl_namespace' => '0', 'watchers' => '100' ],
			(object)[ 'wl_title' => 'OtherDbKey', 'wl_namespace' => '0', 'watchers' => '300' ],
			(object)[ 'wl_title' => 'AnotherDbKey', 'wl_namespace' => '1', 'watchers' => '500' ],
			(object)[ 'wl_title' => 'SomeNotExisitingDbKey', 'wl_namespace' => '0', 'watchers' => '100' ],
			(object)[ 'wl_title' => 'OtherNotExisitingDbKey', 'wl_namespace' => '0', 'watchers' => '200' ],
		];
		$mockDb = $this->getMockDb();
		$mockDb->expects( $this->exactly( 2 * 3 ) )
			->method( 'addQuotes' )
			->will( $this->returnCallback( static function ( $value ) {
				return "'$value'";
			} ) );
		$mockDb->expects( $this->exactly( 3 ) )
			->method( 'timestamp' )
			->will( $this->returnCallback( static function ( $value ) {
				return 'TS' . $value . 'TS';
			} ) );
		$mockDb->expects( $this->any() )
			->method( 'makeList' )
			->with(
				$this->isType( 'array' ),
				$this->isType( 'int' )
			)
			->will( $this->returnCallback( static function ( $a, $conj ) {
				$sqlConj = $conj === LIST_AND ? ' AND ' : ' OR ';
				return implode( $sqlConj, array_map( static function ( $s ) {
					return '(' . $s . ')';
				}, $a
				) );
			} ) );
		$mockDb->expects( $this->once() )
			->method( 'makeWhereFrom2d' )
			->with(
				[ [ 'SomeNotExisitingDbKey' => 1, 'OtherNotExisitingDbKey' => 1 ] ],
				$this->isType( 'string' ),
				$this->isType( 'string' )
			)
			->will( $this->returnValue( 'makeWhereFrom2d return value' ) );

		$expectedCond =
			'((wl_namespace = 0) AND (' .
			"(((wl_title = 'SomeDbKey') AND (" .
			"(wl_notificationtimestamp >= 'TS111TS') OR (wl_notificationtimestamp IS NULL)" .
			')) OR (' .
			"(wl_title = 'OtherDbKey') AND (" .
			"(wl_notificationtimestamp >= 'TS111TS') OR (wl_notificationtimestamp IS NULL)" .
			'))))' .
			') OR ((wl_namespace = 1) AND (' .
			"(((wl_title = 'AnotherDbKey') AND (" .
			"(wl_notificationtimestamp >= 'TS123TS') OR (wl_notificationtimestamp IS NULL)" .
			'))))' .
			') OR ' .
			'(makeWhereFrom2d return value)';
		$mockDb->expects( $this->once() )
			->method( 'select' )
			->with(
				[ 'watchlist' ],
				[ 'wl_namespace', 'wl_title', 'watchers' => 'COUNT(*)' ],
				[ $expectedCond ],
				$this->isType( 'string' ),
				[
					'GROUP BY' => [ 'wl_namespace', 'wl_title' ],
				]
			)
			->will(
				$this->returnValue( $dbResult )
			);

		$mockCache = $this->getMockCache();
		$mockCache->expects( $this->never() )->method( 'get' );
		$mockCache->expects( $this->never() )->method( 'set' );
		$mockCache->expects( $this->never() )->method( 'delete' );

		$store = $this->newWatchedItemStore( [
				'db' => $mockDb,
				'cache' => $mockCache,
				'expiryEnabled' => false
			] );

		$expected = [
			0 => [
				'SomeDbKey' => 100, 'OtherDbKey' => 300,
				'SomeNotExisitingDbKey' => 100, 'OtherNotExisitingDbKey' => 200
			],
			1 => [ 'AnotherDbKey' => 500 ],
		];
		$this->assertEquals(
			$expected,
			$store->countVisitingWatchersMultiple( $titleValuesWithThresholds )
		);
	}

	/**
	 * @dataProvider provideTestPageFactoryAndIntWithDbUnsafeVersion
	 */
	public function testCountVisitingWatchersMultiple_withMinimumWatchers( $minWatchers, $testPageFactory ) {
		$titleValuesWithThresholds = [
			[ $testPageFactory( 100, 0, 'SomeDbKey' ), '111' ],
			[ $testPageFactory( 101, 0, 'OtherDbKey' ), '111' ],
			[ $testPageFactory( 102, 1, 'AnotherDbKey' ), '123' ],
		];

		$mockDb = $this->getMockDb();
		$mockDb->expects( $this->any() )
			->method( 'makeList' )
			->will( $this->returnValue( 'makeList return value' ) );
		$mockDb->expects( $this->once() )
			->method( 'select' )
			->with(
				[ 'watchlist' ],
				[ 'wl_namespace', 'wl_title', 'watchers' => 'COUNT(*)' ],
				[ 'makeList return value' ],
				$this->isType( 'string' ),
				[
					'GROUP BY' => [ 'wl_namespace', 'wl_title' ],
					'HAVING' => 'COUNT(*) >= 50',
				]
			)
			->will(
				$this->returnValue( [] )
			);

		$mockCache = $this->getMockCache();
		$mockCache->expects( $this->never() )->method( 'get' );
		$mockCache->expects( $this->never() )->method( 'set' );
		$mockCache->expects( $this->never() )->method( 'delete' );

		$store = $this->newWatchedItemStore( [
				'db' => $mockDb,
				'cache' => $mockCache,
				'expiryEnabled' => false
			] );

		$expected = [
			0 => [ 'SomeDbKey' => 0, 'OtherDbKey' => 0 ],
			1 => [ 'AnotherDbKey' => 0 ],
		];
		$this->assertEquals(
			$expected,
			$store->countVisitingWatchersMultiple( $titleValuesWithThresholds, $minWatchers )
		);
	}

	public function testCountUnreadNotifications() {
		$user = new UserIdentityValue( 1, 'MockUser' );

		$mockDb = $this->getMockDb();
		$mockDb->expects( $this->exactly( 1 ) )
			->method( 'selectRowCount' )
			->with(
				'watchlist',
				'1',
				[
					"wl_notificationtimestamp IS NOT NULL",
					'wl_user' => 1,
				],
				$this->isType( 'string' )
			)
			->will( $this->returnValue( '9' ) );

		$mockCache = $this->getMockCache();
		$mockCache->expects( $this->never() )->method( 'set' );
		$mockCache->expects( $this->never() )->method( 'get' );
		$mockCache->expects( $this->never() )->method( 'delete' );

		$store = $this->newWatchedItemStore( [ 'db' => $mockDb, 'cache' => $mockCache ] );

		$this->assertEquals( 9, $store->countUnreadNotifications( $user ) );
	}

	/**
	 * @dataProvider provideIntWithDbUnsafeVersion
	 */
	public function testCountUnreadNotifications_withUnreadLimit_overLimit( $limit ) {
		$user = new UserIdentityValue( 1, 'MockUser' );

		$mockDb = $this->getMockDb();
		$mockDb->expects( $this->exactly( 1 ) )
			->method( 'selectRowCount' )
			->with(
				'watchlist',
				'1',
				[
					"wl_notificationtimestamp IS NOT NULL",
					'wl_user' => 1,
				],
				$this->isType( 'string' ),
				[ 'LIMIT' => 50 ]
			)
			->will( $this->returnValue( '50' ) );

		$mockCache = $this->getMockCache();
		$mockCache->expects( $this->never() )->method( 'set' );
		$mockCache->expects( $this->never() )->method( 'get' );
		$mockCache->expects( $this->never() )->method( 'delete' );

		$store = $this->newWatchedItemStore( [ 'db' => $mockDb, 'cache' => $mockCache ] );

		$this->assertSame(
			true,
			$store->countUnreadNotifications( $user, $limit )
		);
	}

	/**
	 * @dataProvider provideIntWithDbUnsafeVersion
	 */
	public function testCountUnreadNotifications_withUnreadLimit_underLimit( $limit ) {
		$user = new UserIdentityValue( 1, 'MockUser' );

		$mockDb = $this->getMockDb();
		$mockDb->expects( $this->exactly( 1 ) )
			->method( 'selectRowCount' )
			->with(
				'watchlist',
				'1',
				[
					"wl_notificationtimestamp IS NOT NULL",
					'wl_user' => 1,
				],
				$this->isType( 'string' ),
				[ 'LIMIT' => 50 ]
			)
			->will( $this->returnValue( '9' ) );

		$mockCache = $this->getMockCache();
		$mockCache->expects( $this->never() )->method( 'set' );
		$mockCache->expects( $this->never() )->method( 'get' );
		$mockCache->expects( $this->never() )->method( 'delete' );

		$store = $this->newWatchedItemStore( [ 'db' => $mockDb, 'cache' => $mockCache ] );

		$this->assertEquals(
			9,
			$store->countUnreadNotifications( $user, $limit )
		);
	}

	/**
	 * @dataProvider provideTestPageFactory
	 */
	public function testDuplicateEntry_nothingToDuplicate( $testPageFactory ) {
		$mockDb = $this->getMockDb();
		$mockDb->expects( $this->once() )
			->method( 'select' )
			->with(
				[ 'watchlist', 'watchlist_expiry' ],
				[ 'wl_user', 'wl_notificationtimestamp', 'we_expiry' ],
				[
					'wl_namespace' => 0,
					'wl_title' => 'Old_Title',
				],
				'WatchedItemStore::fetchWatchedItemsForPage',
				[ 'FOR UPDATE' ],
				[ 'watchlist_expiry' => [ 'LEFT JOIN', [ 'wl_id = we_item' ] ] ]
			)
			->will( $this->returnValue( new FakeResultWrapper( [] ) ) );

		$store = $this->newWatchedItemStore( [ 'db' => $mockDb ] );

		$store->duplicateEntry(
			$testPageFactory( 100, 0, 'Old_Title' ),
			$testPageFactory( 101, 0, 'New_Title' )
		);
	}

	/**
	 * @dataProvider provideTestPageFactory
	 */
	public function testDuplicateEntry_somethingToDuplicate( $testPageFactory ) {
		$fakeRows = [
			(object)[
				'wl_user' => '1',
				'wl_notificationtimestamp' => '20151212010101',
			],
			(object)[
				'wl_user' => '2',
				'wl_notificationtimestamp' => null,
			],
		];

		$mockDb = $this->getMockDb();
		$mockDb->expects( $this->at( 0 ) )
			->method( 'select' )
			->with(
				[ 'watchlist' ],
				[ 'wl_user', 'wl_notificationtimestamp' ],
				[
					'wl_namespace' => 0,
					'wl_title' => 'Old_Title',
				]
			)
			->will( $this->returnValue( new FakeResultWrapper( $fakeRows ) ) );
		$mockDb->expects( $this->at( 1 ) )
			->method( 'replace' )
			->with(
				'watchlist',
				[ [ 'wl_user', 'wl_namespace', 'wl_title' ] ],
				[
					[
						'wl_user' => 1,
						'wl_namespace' => 0,
						'wl_title' => 'New_Title',
						'wl_notificationtimestamp' => '20151212010101',
					],
					[
						'wl_user' => 2,
						'wl_namespace' => 0,
						'wl_title' => 'New_Title',
						'wl_notificationtimestamp' => null,
					],
				],
				$this->isType( 'string' )
			);

		$mockCache = $this->getMockCache();
		$mockCache->expects( $this->never() )->method( 'get' );
		$mockCache->expects( $this->never() )->method( 'delete' );

		$store = $this->newWatchedItemStore( [
			'db' => $mockDb,
			'cache' => $mockCache,
			'expiryEnabled' => false,
		] );

		$store->duplicateEntry(
			$testPageFactory( 100, 0, 'Old_Title' ),
			$testPageFactory( 101, 0, 'New_Title' )
		);
	}

	/**
	 * @dataProvider provideTestPageFactory
	 */
	public function testDuplicateAllAssociatedEntries_nothingToDuplicate( $testPageFactory ) {
		$mockDb = $this->getMockDb();
		$mockDb->expects( $this->at( 0 ) )
			->method( 'select' )
			->with(
				[ 'watchlist', 'watchlist_expiry' ],
				[ 'wl_user', 'wl_notificationtimestamp', 'we_expiry' ],
				[
					'wl_namespace' => 0,
					'wl_title' => 'Old_Title',
				]
			)
			->will( $this->returnValue( new FakeResultWrapper( [] ) ) );
		$mockDb->expects( $this->at( 1 ) )
			->method( 'select' )
			->with(
				[ 'watchlist', 'watchlist_expiry' ],
				[ 'wl_user', 'wl_notificationtimestamp', 'we_expiry' ],
				[
					'wl_namespace' => 1,
					'wl_title' => 'Old_Title',
				]
			)
			->will( $this->returnValue( new FakeResultWrapper( [] ) ) );

		$mockCache = $this->getMockCache();
		$mockCache->expects( $this->never() )->method( 'get' );
		$mockCache->expects( $this->never() )->method( 'delete' );

		$store = $this->newWatchedItemStore( [ 'db' => $mockDb, 'cache' => $mockCache ] );

		$store->duplicateAllAssociatedEntries(
			$testPageFactory( 100, 0, 'Old_Title' ),
			$testPageFactory( 101, 0, 'New_Title' )
		);
	}

	public function provideLinkTargetPairs() {
		foreach ( $this->provideTestPageFactory() as $testPageFactoryArray ) {
			$testPageFactory = $testPageFactoryArray[0];
			yield [ $testPageFactory( 100, 0, 'Old_Title' ), $testPageFactory( 101, 0, 'New_Title' ) ];
		}
	}

	/**
	 * @param LinkTarget|PageIdentity $oldTarget
	 * @param LinkTarget|PageIdentity $newTarget
	 * @dataProvider provideLinkTargetPairs
	 */
	public function testDuplicateAllAssociatedEntries_somethingToDuplicate(
		$oldTarget,
		$newTarget
	) {
		$fakeRows = [
			(object)[
				'wl_user' => '1',
				'wl_notificationtimestamp' => '20151212010101',
				'we_expiry' => null,
			],
		];

		$mockDb = $this->getMockDb();
		$mockDb->expects( $this->at( 0 ) )
			->method( 'select' )
			->with(
				[ 'watchlist' ],
				[ 'wl_user', 'wl_notificationtimestamp' ],
				[
					'wl_namespace' => $oldTarget->getNamespace(),
					'wl_title' => $oldTarget->getDBkey(),
				]
			)
			->will( $this->returnValue( new FakeResultWrapper( $fakeRows ) ) );
		$mockDb->expects( $this->at( 1 ) )
			->method( 'replace' )
			->with(
				'watchlist',
				[ [ 'wl_user', 'wl_namespace', 'wl_title' ] ],
				[
					[
						'wl_user' => 1,
						'wl_namespace' => $newTarget->getNamespace(),
						'wl_title' => $newTarget->getDBkey(),
						'wl_notificationtimestamp' => '20151212010101',
					],
				],
				$this->isType( 'string' )
			);
		$mockDb->expects( $this->at( 2 ) )
			->method( 'select' )
			->with(
				[ 'watchlist' ],
				[ 'wl_user', 'wl_notificationtimestamp' ],
				[
					'wl_namespace' => $oldTarget->getNamespace() + 1,
					'wl_title' => $oldTarget->getDBkey(),
				]
			)
			->will( $this->returnValue( new FakeResultWrapper( $fakeRows ) ) );
		$mockDb->expects( $this->at( 3 ) )
			->method( 'replace' )
			->with(
				'watchlist',
				[ [ 'wl_user', 'wl_namespace', 'wl_title' ] ],
				[
					[
						'wl_user' => 1,
						'wl_namespace' => $newTarget->getNamespace() + 1,
						'wl_title' => $newTarget->getDBkey(),
						'wl_notificationtimestamp' => '20151212010101',
					],
				],
				$this->isType( 'string' )
			);

		$mockCache = $this->getMockCache();
		$mockCache->expects( $this->never() )->method( 'get' );
		$mockCache->expects( $this->never() )->method( 'delete' );

		$store = $this->newWatchedItemStore( [
			'db' => $mockDb,
			'cache' => $mockCache,
			'expiryEnabled' => false,
		] );

		$store->duplicateAllAssociatedEntries(
			$oldTarget,
			$newTarget
		);
	}

	/**
	 * @dataProvider provideTestPageFactory
	 */
	public function testAddWatch_nonAnonymousUser( $testPageFactory ) {
		$mockDb = $this->getMockDb();
		$mockDb->expects( $this->once() )
			->method( 'insert' )
			->with(
				'watchlist',
				[
					[
						'wl_user' => 1,
						'wl_namespace' => 0,
						'wl_title' => 'Some_Page',
						'wl_notificationtimestamp' => null,
					]
				]
			);

		$mockCache = $this->getMockCache();
		$mockCache->expects( $this->once() )
			->method( 'delete' )
			->with( '0:Some_Page:1' );

		$store = $this->newWatchedItemStore( [ 'db' => $mockDb, 'cache' => $mockCache ] );

		$store->addWatch(
			new UserIdentityValue( 1, 'MockUser' ),
			$testPageFactory( 100, 0, 'Some_Page' )
		);
	}

	/**
	 * @dataProvider provideTestPageFactory
	 */
	public function testAddWatch_anonymousUser( $testPageFactory ) {
		$mockDb = $this->getMockDb();
		$mockDb->expects( $this->never() )
			->method( 'insert' );

		$mockCache = $this->getMockCache();
		$mockCache->expects( $this->never() )
			->method( 'delete' );

		$store = $this->newWatchedItemStore( [ 'db' => $mockDb, 'cache' => $mockCache ] );

		$store->addWatch(
			new UserIdentityValue( 0, 'AnonUser' ),
			$testPageFactory( 100, 0, 'Some_Page' )
		);
	}

	/**
	 * @dataProvider provideTestPageFactory
	 */
	public function testAddWatchBatchForUser_readOnlyDBReturnsFalse( $testPageFactory ) {
		$store = $this->newWatchedItemStore(
			[ 'readOnlyMode' => $this->getMockReadOnlyMode( true ) ] );

		$this->assertFalse(
			$store->addWatchBatchForUser(
				new UserIdentityValue( 1, 'MockUser' ),
				[ $testPageFactory( 100, 0, 'Some_Page' ), $testPageFactory( 101, 1, 'Some_Page' ) ]
			)
		);
	}

	/**
	 * @dataProvider provideTestPageFactory
	 */
	public function testAddWatchBatchForUser_nonAnonymousUser( $testPageFactory ) {
		$mockDb = $this->getMockDb();
		$mockDb->expects( $this->once() )
			->method( 'insert' )
			->with(
				'watchlist',
				[
					[
						'wl_user' => 1,
						'wl_namespace' => 0,
						'wl_title' => 'Some_Page',
						'wl_notificationtimestamp' => null,
					],
					[
						'wl_user' => 1,
						'wl_namespace' => 1,
						'wl_title' => 'Some_Page',
						'wl_notificationtimestamp' => null,
					]
				]
			);

		$mockDb->expects( $this->once() )
			->method( 'affectedRows' )
			->willReturn( 2 );

		$mockCache = $this->getMockCache();
		$mockCache->expects( $this->exactly( 2 ) )
			->method( 'delete' );
		$mockCache->expects( $this->at( 1 ) )
			->method( 'delete' )
			->with( '0:Some_Page:1' );
		$mockCache->expects( $this->at( 3 ) )
			->method( 'delete' )
			->with( '1:Some_Page:1' );

		$store = $this->newWatchedItemStore( [ 'db' => $mockDb, 'cache' => $mockCache ] );

		$mockUser = new UserIdentityValue( 1, 'MockUser' );

		$this->assertTrue(
			$store->addWatchBatchForUser(
				$mockUser,
				[ $testPageFactory( 100, 0, 'Some_Page' ), $testPageFactory( 101, 1, 'Some_Page' ) ]
			)
		);
	}

	/**
	 * @dataProvider provideTestPageFactory
	 */
	public function testAddWatchBatchForUser_anonymousUsersAreSkipped( $testPageFactory ) {
		$mockDb = $this->getMockDb();
		$mockDb->expects( $this->never() )
			->method( 'insert' );

		$mockCache = $this->getMockCache();
		$mockCache->expects( $this->never() )
			->method( 'delete' );

		$store = $this->newWatchedItemStore( [ 'db' => $mockDb, 'cache' => $mockCache ] );

		$this->assertFalse(
			$store->addWatchBatchForUser(
				new UserIdentityValue( 0, 'AnonUser' ),
				[ $testPageFactory( 100, 0, 'Other_Page' ) ]
			)
		);
	}

	public function testAddWatchBatchReturnsTrue_whenGivenEmptyList() {
		$user = new UserIdentityValue( 1, 'MockUser' );
		$mockDb = $this->getMockDb();
		$mockDb->expects( $this->never() )
			->method( 'insert' );

		$mockCache = $this->getMockCache();
		$mockCache->expects( $this->never() )
			->method( 'delete' );

		$store = $this->newWatchedItemStore( [ 'db' => $mockDb, 'cache' => $mockCache ] );

		$this->assertTrue(
			$store->addWatchBatchForUser( $user, [] )
		);
	}

	/**
	 * @dataProvider provideTestPageFactory
	 */
	public function testLoadWatchedItem_existingItem( $testPageFactory ) {
		$mockDb = $this->getMockDb();
		$mockDb->expects( $this->once() )
			->method( 'addQuotes' )
			->willReturn( '20200101000000' );
		$makeListSql = "wl_namespace = 0 AND wl_title = 'SomeDbKey'";
		$mockDb->expects( $this->exactly( 2 ) )
			->method( 'makeList' )
			->willReturnOnConsecutiveCalls( $makeListSql, $makeListSql );
		$mockDb->expects( $this->once() )
			->method( 'select' )
			->with(
				[ 'watchlist', 'watchlist_expiry' ],
				[ 'wl_namespace', 'wl_title', 'wl_notificationtimestamp', 'we_expiry' ],
				[
					'wl_user' => 1,
					$makeListSql,
					'we_expiry IS NULL OR we_expiry > 20200101000000'
				]
			)
			->will( $this->returnValue( [
				(object)[
					'wl_namespace' => 0,
					'wl_title' => 'SomeDbKey',
					'wl_notificationtimestamp' => '20151212010101',
					'we_expiry' => '20300101000000'
				]
			] ) );

		$mockCache = $this->getMockCache();
		$mockCache->expects( $this->once() )
			->method( 'set' )
			->with(
				'0:SomeDbKey:1'
			);

		$store = $this->newWatchedItemStore( [ 'db' => $mockDb, 'cache' => $mockCache ] );

		$watchedItem = $store->loadWatchedItem(
			new UserIdentityValue( 1, 'MockUser' ),
			$testPageFactory( 100, 0, 'SomeDbKey' )
		);
		$this->assertInstanceOf( WatchedItem::class, $watchedItem );
		$this->assertSame( 1, $watchedItem->getUserIdentity()->getId() );
		$this->assertEquals( 'SomeDbKey', $watchedItem->getTarget()->getDBkey() );
		$this->assertSame( '20300101000000', $watchedItem->getExpiry() );
		$this->assertSame( 0, $watchedItem->getTarget()->getNamespace() );
	}

	/**
	 * @dataProvider provideTestPageFactory
	 */
	public function testLoadWatchedItem_noItem( $testPageFactory ) {
		$mockDb = $this->getMockDb();
		$mockDb->expects( $this->once() )
			->method( 'addQuotes' )
			->willReturn( '20200101000000' );
		$mockDb->expects( $this->once() )
			->method( 'select' )
			->will( $this->returnValue( [] ) );

		$mockCache = $this->getMockCache();
		$mockCache->expects( $this->never() )->method( 'get' );
		$mockCache->expects( $this->never() )->method( 'delete' );

		$store = $this->newWatchedItemStore( [ 'db' => $mockDb, 'cache' => $mockCache ] );

		$this->assertFalse(
			$store->loadWatchedItem(
				new UserIdentityValue( 1, 'MockUser' ),
				$testPageFactory( 100, 0, 'SomeDbKey' )
			)
		);
	}

	/**
	 * @dataProvider provideTestPageFactory
	 */
	public function testLoadWatchedItem_anonymousUser( $testPageFactory ) {
		$mockDb = $this->getMockDb();
		$mockDb->expects( $this->never() )
			->method( 'select' );

		$mockCache = $this->getMockCache();
		$mockCache->expects( $this->never() )->method( 'get' );
		$mockCache->expects( $this->never() )->method( 'delete' );

		$store = $this->newWatchedItemStore( [ 'db' => $mockDb, 'cache' => $mockCache ] );

		$this->assertFalse(
			$store->loadWatchedItem(
				new UserIdentityValue( 0, 'AnonUser' ),
				$testPageFactory( 100, 0, 'SomeDbKey' )
			)
		);
	}

	/**
	 * @dataProvider provideTestPageFactory
	 */
	public function testRemoveWatch_existingItem( $testPageFactory ) {
		$mockDb = $this->getMockDb();
		$mockDb->expects( $this->once() )
			->method( 'selectFieldValues' )
			->willReturn( [ 1, 2 ] );
		$mockDb->expects( $this->exactly( 2 ) )
			->method( 'delete' )
			->withConsecutive(
				[
					'watchlist',
					[ 'wl_id' => [ 1, 2 ] ]
				],
				[
					'watchlist_expiry',
					[ 'we_item' => [ 1, 2 ] ]
				]
			);
		$mockDb->expects( $this->exactly( 2 ) )
			->method( 'affectedRows' )
			->willReturn( 2 );

		$mockCache = $this->getMockCache();
		$mockCache->expects( $this->never() )->method( 'get' );
		$mockCache->expects( $this->once() )
			->method( 'delete' )
			->withConsecutive(
				[ '0:SomeDbKey:1' ],
				[ '1:SomeDbKey:1' ]
			);

		$store = $this->newWatchedItemStore( [ 'db' => $mockDb, 'cache' => $mockCache ] );

		$this->assertTrue(
			$store->removeWatch(
				new UserIdentityValue( 1, 'MockUser' ),
				$testPageFactory( 100, 0, 'SomeDbKey' )
			)
		);
	}

	/**
	 * @dataProvider provideTestPageFactory
	 */
	public function testRemoveWatch_noItem( $testPageFactory ) {
		$mockDb = $this->getMockDb();
		$mockDb->expects( $this->once() )
			->method( 'selectFieldValues' )
			->willReturn( null );
		$mockDb->expects( $this->never() )
			->method( 'delete' );
		$mockDb->expects( $this->never() )
			->method( 'affectedRows' );

		$mockCache = $this->getMockCache();
		$mockCache->expects( $this->never() )->method( 'get' );
		$mockCache->expects( $this->once() )
			->method( 'delete' )
			->withConsecutive(
				[ '0:SomeDbKey:1' ],
				[ '1:SomeDbKey:1' ]
			);

		$store = $this->newWatchedItemStore( [ 'db' => $mockDb, 'cache' => $mockCache ] );

		$this->assertFalse(
			$store->removeWatch(
				new UserIdentityValue( 1, 'MockUser' ),
				$testPageFactory( 100, 0, 'SomeDbKey' )
			)
		);
	}

	/**
	 * @dataProvider provideTestPageFactory
	 */
	public function testRemoveWatch_anonymousUser( $testPageFactory ) {
		$mockDb = $this->getMockDb();
		$mockDb->expects( $this->never() )
			->method( 'delete' );

		$mockCache = $this->getMockCache();
		$mockCache->expects( $this->never() )->method( 'get' );
		$mockCache->expects( $this->never() )
			->method( 'delete' );

		$store = $this->newWatchedItemStore( [ 'db' => $mockDb, 'cache' => $mockCache ] );

		$this->assertFalse(
			$store->removeWatch(
				new UserIdentityValue( 0, 'AnonUser' ),
				$testPageFactory( 100, 0, 'SomeDbKey' )
			)
		);
	}

	/**
	 * @dataProvider provideTestPageFactory
	 */
	public function testGetWatchedItem_existingItem( $testPageFactory ) {
		$mockDb = $this->getMockDb();
		$mockDb->expects( $this->once() )
			->method( 'addQuotes' )
			->willReturn( '20200101000000' );
		$makeListSql = "wl_namespace = 0 AND wl_title = 'SomeDbKey'";
		$mockDb->expects( $this->exactly( 2 ) )
			->method( 'makeList' )
			->willReturnOnConsecutiveCalls( $makeListSql, $makeListSql );
		$mockDb->expects( $this->once() )
			->method( 'select' )
			->with(
				[ 'watchlist', 'watchlist_expiry' ],
				[ 'wl_namespace', 'wl_title', 'wl_notificationtimestamp', 'we_expiry' ],
				[
					'wl_user' => 1,
					$makeListSql,
					'we_expiry IS NULL OR we_expiry > 20200101000000'
				]
			)
			->will( $this->returnValue( [
				(object)[
					'wl_namespace' => 0,
					'wl_title' => 'SomeDbKey',
					'wl_notificationtimestamp' => '20151212010101',
					'we_expiry' => '20300101000000'
				]
			] ) );

		$mockCache = $this->getMockCache();
		$mockCache->expects( $this->never() )->method( 'delete' );
		$mockCache->expects( $this->once() )
			->method( 'get' )
			->with(
				'0:SomeDbKey:1'
			)
			->will( $this->returnValue( null ) );
		$mockCache->expects( $this->once() )
			->method( 'set' )
			->with(
				'0:SomeDbKey:1'
			);

		$store = $this->newWatchedItemStore( [ 'db' => $mockDb, 'cache' => $mockCache ] );

		$watchedItem = $store->getWatchedItem(
			new UserIdentityValue( 1, 'MockUser' ),
			$testPageFactory( 100, 0, 'SomeDbKey' )
		);
		$this->assertInstanceOf( WatchedItem::class, $watchedItem );
		$this->assertSame( 1, $watchedItem->getUserIdentity()->getId() );
		$this->assertEquals( 'SomeDbKey', $watchedItem->getTarget()->getDBkey() );
		$this->assertSame( '20300101000000', $watchedItem->getExpiry() );
		$this->assertSame( 0, $watchedItem->getTarget()->getNamespace() );
	}

	/**
	 * @dataProvider provideTestPageFactory
	 */
	public function testGetWatchedItem_cachedItem( $testPageFactory ) {
		$mockDb = $this->getMockDb();
		$mockDb->expects( $this->never() )
			->method( 'selectRow' );

		$mockUser = new UserIdentityValue( 1, 'MockUser' );
		$linkTarget = $testPageFactory( 100, 0, 'SomeDbKey' );
		$cachedItem = new WatchedItem( $mockUser, $linkTarget, '20151212010101' );

		$mockCache = $this->getMockCache();
		$mockCache->expects( $this->never() )->method( 'delete' );
		$mockCache->expects( $this->never() )->method( 'set' );
		$mockCache->expects( $this->once() )
			->method( 'get' )
			->with(
				'0:SomeDbKey:1'
			)
			->will( $this->returnValue( $cachedItem ) );

		$store = $this->newWatchedItemStore( [ 'db' => $mockDb, 'cache' => $mockCache ] );

		$this->assertEquals(
			$cachedItem,
			$store->getWatchedItem(
				$mockUser,
				$linkTarget
			)
		);
	}

	/**
	 * @dataProvider provideTestPageFactory
	 */
	public function testGetWatchedItem_noItem( $testPageFactory ) {
		$mockDb = $this->getMockDb();
		$mockDb->expects( $this->once() )
			->method( 'addQuotes' )
			->willReturn( '20200101000000' );
		$makeListSql = "wl_namespace = 0 AND wl_title = 'SomeDbKey'";
		$mockDb->expects( $this->exactly( 2 ) )
			->method( 'makeList' )
			->willReturnOnConsecutiveCalls( $makeListSql, $makeListSql );
		$mockDb->expects( $this->once() )
			->method( 'select' )
			->with(
				[ 'watchlist', 'watchlist_expiry' ],
				[ 'wl_namespace', 'wl_title', 'wl_notificationtimestamp', 'we_expiry' ],
				[
					'wl_user' => 1,
					$makeListSql,
					'we_expiry IS NULL OR we_expiry > 20200101000000'
				]
			)
			->will( $this->returnValue( [] ) );

		$mockCache = $this->getMockCache();
		$mockCache->expects( $this->never() )->method( 'set' );
		$mockCache->expects( $this->never() )->method( 'delete' );
		$mockCache->expects( $this->once() )
			->method( 'get' )
			->with( '0:SomeDbKey:1' )
			->will( $this->returnValue( false ) );

		$store = $this->newWatchedItemStore( [ 'db' => $mockDb, 'cache' => $mockCache ] );

		$this->assertFalse(
			$store->getWatchedItem(
				new UserIdentityValue( 1, 'MockUser' ),
				$testPageFactory( 100, 0, 'SomeDbKey' )
			)
		);
	}

	/**
	 * @dataProvider provideTestPageFactory
	 */
	public function testGetWatchedItem_anonymousUser( $testPageFactory ) {
		$mockDb = $this->getMockDb();
		$mockDb->expects( $this->never() )
			->method( 'selectRow' );

		$mockCache = $this->getMockCache();
		$mockCache->expects( $this->never() )->method( 'set' );
		$mockCache->expects( $this->never() )->method( 'get' );
		$mockCache->expects( $this->never() )->method( 'delete' );

		$store = $this->newWatchedItemStore( [ 'db' => $mockDb, 'cache' => $mockCache ] );

		$this->assertFalse(
			$store->getWatchedItem(
				new UserIdentityValue( 0, 'AnonUser' ),
				$testPageFactory( 100, 0, 'SomeDbKey' )
			)
		);
	}

	public function testGetWatchedItemsForUser() {
		$mockDb = $this->getMockDb();
		$mockDb->expects( $this->once() )
			->method( 'addQuotes' )
			->willReturn( '20200101000000' );
		$mockDb->expects( $this->once() )
			->method( 'select' )
			->with(
				[ 'watchlist', 'watchlist_expiry' ],
				[ 'wl_namespace', 'wl_title', 'wl_notificationtimestamp', 'we_expiry' ],
				[ 'wl_user' => 1, 'we_expiry IS NULL OR we_expiry > 20200101000000' ]
			)
			->will( $this->returnValue( [
				(object)[
					'wl_namespace' => 0,
					'wl_title' => 'Foo1',
					'wl_notificationtimestamp' => '20151212010101',
					'we_expiry' => '20300101000000'
				],
				(object)[
					'wl_namespace' => 1,
					'wl_title' => 'Foo2',
					'wl_notificationtimestamp' => null,
				],
			] ) );

		$mockCache = $this->getMockCache();
		$mockCache->expects( $this->never() )->method( 'delete' );
		$mockCache->expects( $this->never() )->method( 'get' );
		$mockCache->expects( $this->never() )->method( 'set' );

		$store = $this->newWatchedItemStore( [ 'db' => $mockDb, 'cache' => $mockCache ] );
		$user = new UserIdentityValue( 1, 'MockUser' );

		$watchedItems = $store->getWatchedItemsForUser( $user );

		$this->assertIsArray( $watchedItems );
		$this->assertCount( 2, $watchedItems );
		foreach ( $watchedItems as $watchedItem ) {
			$this->assertInstanceOf( WatchedItem::class, $watchedItem );
		}
		$this->assertEquals(
			new WatchedItem(
				$user,
				new TitleValue( 0, 'Foo1' ),
				'20151212010101',
				'20300101000000'
			),
			$watchedItems[0]
		);
		$this->assertEquals(
			new WatchedItem( $user, new TitleValue( 1, 'Foo2' ), null ),
			$watchedItems[1]
		);
	}

	public function provideDbTypes() {
		return [
			[ false, DB_REPLICA ],
			[ true, DB_MASTER ],
		];
	}

	/**
	 * @dataProvider provideDbTypes
	 */
	public function testGetWatchedItemsForUser_optionsAndEmptyResult( $forWrite, $dbType ) {
		$mockDb = $this->getMockDb();
		$mockCache = $this->getMockCache();
		$mockLoadBalancer = $this->getMockLBFactory( $mockDb, $dbType );
		$user = new UserIdentityValue( 1, 'MockUser' );

		$mockDb->expects( $this->once() )
			->method( 'addQuotes' )
			->willReturn( '20200101000000' );
		$mockDb->expects( $this->once() )
			->method( 'select' )
			->with(
				[ 'watchlist', 'watchlist_expiry' ],
				[ 'wl_namespace', 'wl_title', 'wl_notificationtimestamp', 'we_expiry' ],
				[ 'wl_user' => 1, 'we_expiry IS NULL OR we_expiry > 20200101000000' ],
				$this->isType( 'string' ),
				[ 'ORDER BY' => [ 'wl_namespace ASC', 'wl_title ASC' ] ]
			)
			->will( $this->returnValue( [] ) );

		$store = $this->newWatchedItemStore(
			[ 'lbFactory' => $mockLoadBalancer, 'cache' => $mockCache ] );

		$watchedItems = $store->getWatchedItemsForUser(
			$user,
			[ 'forWrite' => $forWrite, 'sort' => WatchedItemStore::SORT_ASC ]
		);
		$this->assertEquals( [], $watchedItems );
	}

	public function testGetWatchedItemsForUser_sortByExpiry() {
		$mockDb = $this->getMockDb();
		$mockDb->expects( $this->once() )
			->method( 'addQuotes' )
			->willReturn( '20200101000000' );
		$mockDb->expects( $this->once() )
			->method( 'select' )
			->with(
				[ 'watchlist', 'watchlist_expiry' ],
				[
					'wl_namespace',
					'wl_title',
					'wl_notificationtimestamp',
					'we_expiry',
					'wl_has_expiry' => null
				],
				[ 'wl_user' => 1, 'we_expiry IS NULL OR we_expiry > 20200101000000' ]
			)
			->will( $this->returnValue( [
				(object)[
					'wl_namespace' => 0,
					'wl_title' => 'Foo1',
					'wl_notificationtimestamp' => '20151212010101',
					'we_expiry' => '20300101000000'
				],
				(object)[
					'wl_namespace' => 0,
					'wl_title' => 'Foo2',
					'wl_notificationtimestamp' => '20151212010101',
					'we_expiry' => '20300701000000'
				],
				(object)[
					'wl_namespace' => 1,
					'wl_title' => 'Foo3',
					'wl_notificationtimestamp' => null,
				],
			] ) );

		$mockCache = $this->getMockCache();
		$mockCache->expects( $this->never() )->method( 'delete' );
		$mockCache->expects( $this->never() )->method( 'get' );
		$mockCache->expects( $this->never() )->method( 'set' );

		$store = $this->newWatchedItemStore( [ 'db' => $mockDb, 'cache' => $mockCache ] );
		$user = new UserIdentityValue( 1, 'MockUser' );

		$watchedItems = $store->getWatchedItemsForUser(
			$user,
			[ 'sortByExpiry' => true, 'sort' => WatchedItemStore::SORT_ASC ]
		);

		$this->assertIsArray( $watchedItems );
		$this->assertCount( 3, $watchedItems );
		foreach ( $watchedItems as $watchedItem ) {
			$this->assertInstanceOf( WatchedItem::class, $watchedItem );
		}
		$this->assertEquals(
			new WatchedItem(
				$user,
				new TitleValue( 0, 'Foo1' ),
				'20151212010101',
				'20300101000000'
			),
			$watchedItems[0]
		);
		$this->assertEquals(
			new WatchedItem( $user, new TitleValue( 1, 'Foo3' ), null ),
			$watchedItems[2]
		);
	}

	public function testGetWatchedItemsForUser_badSortOptionThrowsException() {
		$store = $this->newWatchedItemStore();

		$this->expectException( InvalidArgumentException::class );
		$store->getWatchedItemsForUser(
			new UserIdentityValue( 1, 'MockUser' ),
			[ 'sort' => 'foo' ]
		);
	}

	/**
	 * @dataProvider provideTestPageFactory
	 */
	public function testIsWatchedItem_existingItem( $testPageFactory ) {
		$mockDb = $this->getMockDb();
		$mockDb->expects( $this->once() )
			->method( 'addQuotes' )
			->willReturn( '20200101000000' );
		$makeListSql = "wl_namespace = 0 AND wl_title = 'SomeDbKey'";
		$mockDb->expects( $this->exactly( 2 ) )
			->method( 'makeList' )
			->willReturnOnConsecutiveCalls( $makeListSql, $makeListSql );
		$mockDb->expects( $this->once() )
			->method( 'select' )
			->with(
				[ 'watchlist', 'watchlist_expiry' ],
				[ 'wl_namespace', 'wl_title', 'wl_notificationtimestamp', 'we_expiry' ],
				[
					'wl_user' => 1,
					$makeListSql,
					'we_expiry IS NULL OR we_expiry > 20200101000000'
				]
			)
			->will( $this->returnValue( [
				(object)[
					'wl_namespace' => 0,
					'wl_title' => 'SomeDbKey',
					'wl_notificationtimestamp' => '20151212010101',
				]
			] ) );

		$mockCache = $this->getMockCache();
		$mockCache->expects( $this->never() )->method( 'delete' );
		$mockCache->expects( $this->once() )
			->method( 'get' )
			->with( '0:SomeDbKey:1' )
			->will( $this->returnValue( false ) );
		$mockCache->expects( $this->once() )
			->method( 'set' )
			->with(
				'0:SomeDbKey:1'
			);

		$store = $this->newWatchedItemStore( [ 'db' => $mockDb, 'cache' => $mockCache ] );

		$this->assertTrue(
			$store->isWatched(
				new UserIdentityValue( 1, 'MockUser' ),
				$testPageFactory( 100, 0, 'SomeDbKey' )
			)
		);
	}

	/**
	 * @dataProvider provideTestPageFactory
	 */
	public function testIsWatchedItem_noItem( $testPageFactory ) {
		$mockDb = $this->getMockDb();
		$mockDb->expects( $this->once() )
			->method( 'addQuotes' )
			->willReturn( '20200101000000' );
		$makeListSql = "wl_namespace = 0 AND wl_title = 'SomeDbKey'";
		$mockDb->expects( $this->exactly( 2 ) )
			->method( 'makeList' )
			->willReturnOnConsecutiveCalls( $makeListSql, $makeListSql );
		$mockDb->expects( $this->once() )
			->method( 'select' )
			->with(
				[ 'watchlist', 'watchlist_expiry' ],
				[ 'wl_namespace', 'wl_title', 'wl_notificationtimestamp', 'we_expiry' ],
				[
					'wl_user' => 1,
					$makeListSql,
					'we_expiry IS NULL OR we_expiry > 20200101000000'
				]
			)
			->will( $this->returnValue( [] ) );

		$mockCache = $this->getMockCache();
		$mockCache->expects( $this->never() )->method( 'set' );
		$mockCache->expects( $this->never() )->method( 'delete' );
		$mockCache->expects( $this->once() )
			->method( 'get' )
			->with( '0:SomeDbKey:1' )
			->will( $this->returnValue( false ) );

		$store = $this->newWatchedItemStore( [ 'db' => $mockDb, 'cache' => $mockCache ] );

		$this->assertFalse(
			$store->isWatched(
				new UserIdentityValue( 1, 'MockUser' ),
				$testPageFactory( 100, 0, 'SomeDbKey' )
			)
		);
	}

	/**
	 * @dataProvider provideTestPageFactory
	 */
	public function testIsWatchedItem_anonymousUser( $testPageFactory ) {
		$mockDb = $this->getMockDb();
		$mockDb->expects( $this->never() )
			->method( 'selectRow' );

		$mockCache = $this->getMockCache();
		$mockCache->expects( $this->never() )->method( 'set' );
		$mockCache->expects( $this->never() )->method( 'get' );
		$mockCache->expects( $this->never() )->method( 'delete' );

		$store = $this->newWatchedItemStore( [ 'db' => $mockDb, 'cache' => $mockCache ] );

		$this->assertFalse(
			$store->isWatched(
				new UserIdentityValue( 0, 'AnonUser' ),
				$testPageFactory( 100, 0, 'SomeDbKey' )
			)
		);
	}

	/**
	 * @dataProvider provideTestPageFactory
	 */
	public function testGetNotificationTimestampsBatch( $testPageFactory ) {
		$targets = [
			$testPageFactory( 100, 0, 'SomeDbKey' ),
			$testPageFactory( 101, 1, 'AnotherDbKey' ),
		];

		$mockDb = $this->getMockDb();
		$dbResult = [
			(object)[
				'wl_namespace' => '0',
				'wl_title' => 'SomeDbKey',
				'wl_notificationtimestamp' => '20151212010101',
			],
			(object)[
				'wl_namespace' => '1',
				'wl_title' => 'AnotherDbKey',
				'wl_notificationtimestamp' => null,
			],
		];

		$mockDb->expects( $this->once() )
			->method( 'makeWhereFrom2d' )
			->with(
				[ [ 'SomeDbKey' => 1 ], [ 'AnotherDbKey' => 1 ] ],
				$this->isType( 'string' ),
				$this->isType( 'string' )
			)
			->will( $this->returnValue( 'makeWhereFrom2d return value' ) );
		$mockDb->expects( $this->once() )
			->method( 'select' )
			->with(
				'watchlist',
				[ 'wl_namespace', 'wl_title', 'wl_notificationtimestamp' ],
				[
					'makeWhereFrom2d return value',
					'wl_user' => 1
				],
				$this->isType( 'string' )
			)
			->will( $this->returnValue( $dbResult ) );

		$mockCache = $this->getMockCache();
		$mockCache->expects( $this->exactly( 2 ) )
			->method( 'get' )
			->withConsecutive(
				[ '0:SomeDbKey:1' ],
				[ '1:AnotherDbKey:1' ]
			)
			->will( $this->returnValue( null ) );
		$mockCache->expects( $this->never() )->method( 'set' );
		$mockCache->expects( $this->never() )->method( 'delete' );

		$store = $this->newWatchedItemStore( [ 'db' => $mockDb, 'cache' => $mockCache ] );

		$this->assertEquals(
			[
				0 => [ 'SomeDbKey' => '20151212010101', ],
				1 => [ 'AnotherDbKey' => null, ],
			],
			$store->getNotificationTimestampsBatch(
				new UserIdentityValue( 1, 'MockUser' ), $targets )
		);
	}

	/**
	 * @dataProvider provideTestPageFactory
	 */
	public function testGetNotificationTimestampsBatch_notWatchedTarget( $testPageFactory ) {
		$targets = [
			$testPageFactory( 100, 0, 'OtherDbKey' ),
		];

		$mockDb = $this->getMockDb();

		$mockDb->expects( $this->once() )
			->method( 'makeWhereFrom2d' )
			->with(
				[ [ 'OtherDbKey' => 1 ] ],
				$this->isType( 'string' ),
				$this->isType( 'string' )
			)
			->will( $this->returnValue( 'makeWhereFrom2d return value' ) );
		$mockDb->expects( $this->once() )
			->method( 'select' )
			->with(
				'watchlist',
				[ 'wl_namespace', 'wl_title', 'wl_notificationtimestamp' ],
				[
					'makeWhereFrom2d return value',
					'wl_user' => 1
				],
				$this->isType( 'string' )
			)
			->will( $this->returnValue( (object)[] ) );

		$mockCache = $this->getMockCache();
		$mockCache->expects( $this->once() )
			->method( 'get' )
			->with( '0:OtherDbKey:1' )
			->will( $this->returnValue( null ) );
		$mockCache->expects( $this->never() )->method( 'set' );
		$mockCache->expects( $this->never() )->method( 'delete' );

		$store = $this->newWatchedItemStore( [ 'db' => $mockDb, 'cache' => $mockCache ] );

		$this->assertEquals(
			[
				0 => [ 'OtherDbKey' => false, ],
			],
			$store->getNotificationTimestampsBatch(
				new UserIdentityValue( 1, 'MockUser' ), $targets )
		);
	}

	/**
	 * @dataProvider provideTestPageFactory
	 */
	public function testGetNotificationTimestampsBatch_cachedItem( $testPageFactory ) {
		$targets = [
			$testPageFactory( 100, 0, 'SomeDbKey' ),
			$testPageFactory( 101, 1, 'AnotherDbKey' ),
		];

		$user = new UserIdentityValue( 1, 'MockUser' );
		$cachedItem = new WatchedItem( $user, $targets[0], '20151212010101' );

		$mockDb = $this->getMockDb();

		$mockDb->expects( $this->once() )
			->method( 'makeWhereFrom2d' )
			->with(
				[ 1 => [ 'AnotherDbKey' => 1 ] ],
				$this->isType( 'string' ),
				$this->isType( 'string' )
			)
			->will( $this->returnValue( 'makeWhereFrom2d return value' ) );
		$mockDb->expects( $this->once() )
			->method( 'select' )
			->with(
				'watchlist',
				[ 'wl_namespace', 'wl_title', 'wl_notificationtimestamp' ],
				[
					'makeWhereFrom2d return value',
					'wl_user' => 1
				],
				$this->isType( 'string' )
			)
			->will( $this->returnValue( [
				(object)[ 'wl_namespace' => '1', 'wl_title' => 'AnotherDbKey', 'wl_notificationtimestamp' => null, ]
			] ) );

		$mockCache = $this->getMockCache();
		$mockCache->expects( $this->at( 1 ) )
			->method( 'get' )
			->with( '0:SomeDbKey:1' )
			->will( $this->returnValue( $cachedItem ) );
		$mockCache->expects( $this->at( 3 ) )
			->method( 'get' )
			->with( '1:AnotherDbKey:1' )
			->will( $this->returnValue( null ) );
		$mockCache->expects( $this->never() )->method( 'set' );
		$mockCache->expects( $this->never() )->method( 'delete' );

		$store = $this->newWatchedItemStore( [ 'db' => $mockDb, 'cache' => $mockCache ] );

		$this->assertEquals(
			[
				0 => [ 'SomeDbKey' => '20151212010101', ],
				1 => [ 'AnotherDbKey' => null, ],
			],
			$store->getNotificationTimestampsBatch( $user, $targets )
		);
	}

	/**
	 * @dataProvider provideTestPageFactory
	 */
	public function testGetNotificationTimestampsBatch_allItemsCached( $testPageFactory ) {
		$targets = [
			$testPageFactory( 100, 0, 'SomeDbKey' ),
			$testPageFactory( 101, 1, 'AnotherDbKey' ),
		];

		$user = new UserIdentityValue( 1, 'MockUser' );
		$cachedItems = [
			new WatchedItem( $user, $targets[0], '20151212010101' ),
			new WatchedItem( $user, $targets[1], null ),
		];
		$mockDb = $this->createNoOpMock( IDatabase::class );

		$mockCache = $this->getMockCache();
		$mockCache->expects( $this->at( 1 ) )
			->method( 'get' )
			->with( '0:SomeDbKey:1' )
			->will( $this->returnValue( $cachedItems[0] ) );
		$mockCache->expects( $this->at( 3 ) )
			->method( 'get' )
			->with( '1:AnotherDbKey:1' )
			->will( $this->returnValue( $cachedItems[1] ) );
		$mockCache->expects( $this->never() )->method( 'set' );
		$mockCache->expects( $this->never() )->method( 'delete' );

		$store = $this->newWatchedItemStore( [ 'db' => $mockDb, 'cache' => $mockCache ] );

		$this->assertEquals(
			[
				0 => [ 'SomeDbKey' => '20151212010101', ],
				1 => [ 'AnotherDbKey' => null, ],
			],
			$store->getNotificationTimestampsBatch( $user, $targets )
		);
	}

	/**
	 * @dataProvider provideTestPageFactory
	 */
	public function testGetNotificationTimestampsBatch_anonymousUser( $testPageFactory ) {
		$targets = [
			$testPageFactory( 100, 0, 'SomeDbKey' ),
			$testPageFactory( 101, 1, 'AnotherDbKey' ),
		];

		$mockDb = $this->createNoOpMock( IDatabase::class );

		$mockCache = $this->createNoOpMock( HashBagOStuff::class );

		$store = $this->newWatchedItemStore( [ 'db' => $mockDb, 'cache' => $mockCache ] );

		$this->assertEquals(
			[
				0 => [ 'SomeDbKey' => false, ],
				1 => [ 'AnotherDbKey' => false, ],
			],
			$store->getNotificationTimestampsBatch(
				new UserIdentityValue( 0, 'AnonUser' ), $targets )
		);
	}

	/**
	 * @dataProvider provideTestPageFactory
	 */
	public function testResetNotificationTimestamp_anonymousUser( $testPageFactory ) {
		$mockDb = $this->getMockDb();
		$mockDb->expects( $this->never() )
			->method( 'selectRow' );

		$mockCache = $this->getMockCache();
		$mockCache->expects( $this->never() )->method( 'get' );
		$mockCache->expects( $this->never() )->method( 'set' );
		$mockCache->expects( $this->never() )->method( 'delete' );

		$store = $this->newWatchedItemStore( [ 'db' => $mockDb, 'cache' => $mockCache ] );

		$this->assertFalse(
			$store->resetNotificationTimestamp(
				new UserIdentityValue( 0, 'AnonUser' ),
				$testPageFactory( 100, 0, 'SomeDbKey' )
			)
		);
	}

	/**
	 * @dataProvider provideTestPageFactory
	 */
	public function testResetNotificationTimestamp_noItem( $testPageFactory ) {
		$mockDb = $this->getMockDb();
		$mockDb->expects( $this->once() )
			->method( 'addQuotes' )
			->willReturn( '20200101000000' );
		$makeListSql = "wl_namespace = 0 AND wl_title = 'SomeDbKey'";
		$mockDb->expects( $this->exactly( 2 ) )
			->method( 'makeList' )
			->willReturnOnConsecutiveCalls( $makeListSql, $makeListSql );
		$mockDb->expects( $this->once() )
			->method( 'select' )
			->with(
				[ 'watchlist', 'watchlist_expiry' ],
				[ 'wl_namespace', 'wl_title', 'wl_notificationtimestamp', 'we_expiry' ],
				[
					'wl_user' => 1,
					$makeListSql,
					'we_expiry IS NULL OR we_expiry > 20200101000000'
				]
			)
			->will( $this->returnValue( [] ) );

		$mockCache = $this->getMockCache();
		$mockCache->expects( $this->never() )->method( 'get' );
		$mockCache->expects( $this->never() )->method( 'set' );
		$mockCache->expects( $this->never() )->method( 'delete' );

		$user = new UserIdentityValue( 1, 'MockUser' );
		$userFactory = $this->getUserFactory(
			[ $this->getMockUser( $user ) ]
		);

		$title = $testPageFactory( 100, 0, 'SomeDbKey' );
		$titleFactory = $this->getTitleFactory( $title );

		$store = $this->newWatchedItemStore( [
			'db' => $mockDb,
			'cache' => $mockCache,
			'userFactory' => $userFactory,
			'titleFactory' => $titleFactory,
		] );

		$this->assertFalse(
			$store->resetNotificationTimestamp(
				$user,
				$title
			)
		);
	}

	/**
	 * @dataProvider provideTestPageFactory
	 */
	public function testResetNotificationTimestamp_item( $testPageFactory ) {
		$user = new UserIdentityValue( 1, 'MockUser' );
		$title = $testPageFactory( 100, 0, 'SomeDbKey' );

		$mockDb = $this->getMockDb();
		$mockDb->expects( $this->once() )
			->method( 'addQuotes' )
			->willReturn( '20200101000000' );
		$makeListSql = "wl_namespace = 0 AND wl_title = 'SomeDbKey'";
		$mockDb->expects( $this->exactly( 2 ) )
			->method( 'makeList' )
			->willReturnOnConsecutiveCalls( $makeListSql, $makeListSql );
		$mockDb->expects( $this->once() )
			->method( 'select' )
			->with(
				[ 'watchlist', 'watchlist_expiry' ],
				[ 'wl_namespace', 'wl_title', 'wl_notificationtimestamp', 'we_expiry' ],
				[
					'wl_user' => 1,
					$makeListSql,
					'we_expiry IS NULL OR we_expiry > 20200101000000'
				]
			)
			->will( $this->returnValue( [
				(object)[
					'wl_namespace' => 0,
					'wl_title' => 'SomeDbKey',
					'wl_notificationtimestamp' => '20151212010101',
				]
			] ) );

		$mockCache = $this->getMockCache();
		$mockCache->expects( $this->never() )->method( 'get' );
		$mockCache->expects( $this->once() )
			->method( 'set' )
			->with(
				'0:SomeDbKey:1',
				$this->isInstanceOf( WatchedItem::class )
			);
		$mockCache->expects( $this->once() )
			->method( 'delete' )
			->with( '0:SomeDbKey:1' );

		$mockQueueGroup = $this->getMockJobQueueGroup( false );
		$mockQueueGroup->expects( $this->once() )
			->method( 'lazyPush' )
			->willReturnCallback( static function ( ActivityUpdateJob $job ) {
				// don't run
			} );

		// We don't care if these methods actually do anything here
		$mockRevisionLookup = $this->getMockRevisionLookup( [
			'getRevisionByTitle' => static function () {
				return null;
			},
			'getTimestampFromId' => static function () {
				return '00000000000000';
			},
		] );

		$userFactory = $this->getUserFactory(
			[ $this->getMockUser( $user ) ]
		);

		$titleFactory = $this->getTitleFactory( $title );

		$store = $this->newWatchedItemStore( [
			'db' => $mockDb,
			'queueGroup' => $mockQueueGroup,
			'cache' => $mockCache,
			'revisionLookup' => $mockRevisionLookup,
			'userFactory' => $userFactory,
			'titleFactory' => $titleFactory,
		] );

		$this->assertTrue(
			$store->resetNotificationTimestamp(
				$user,
				$title
			)
		);
	}

	/**
	 * @dataProvider provideTestPageFactory
	 */
	public function testResetNotificationTimestamp_noItemForced( $testPageFactory ) {
		$user = new UserIdentityValue( 1, 'MockUser' );
		$title = $testPageFactory( 100, 0, 'SomeDbKey' );

		$mockDb = $this->getMockDb();
		$mockDb->expects( $this->never() )
			->method( 'selectRow' );

		$mockCache = $this->getMockCache();
		$mockCache->expects( $this->never() )->method( 'get' );
		$mockCache->expects( $this->never() )->method( 'set' );
		$mockCache->expects( $this->once() )
			->method( 'delete' )
			->with( '0:SomeDbKey:1' );

		$mockQueueGroup = $this->getMockJobQueueGroup( false );

		// We don't care if these methods actually do anything here
		$mockRevisionLookup = $this->getMockRevisionLookup( [
			'getRevisionByTitle' => static function () {
				return null;
			},
			'getTimestampFromId' => static function () {
				return '00000000000000';
			},
		] );

		$userFactory = $this->getUserFactory(
			[ $this->getMockUser( $user ) ]
		);

		$titleFactory = $this->getTitleFactory( $title );

		$store = $this->newWatchedItemStore( [
			'db' => $mockDb,
			'queueGroup' => $mockQueueGroup,
			'cache' => $mockCache,
			'revisionLookup' => $mockRevisionLookup,
			'userFactory' => $userFactory,
			'titleFactory' => $titleFactory,
		] );

		$mockQueueGroup->expects( $this->any() )
			->method( 'lazyPush' )
			->willReturnCallback( static function ( ActivityUpdateJob $job ) {
				// don't run
			} );

		$this->assertTrue(
			$store->resetNotificationTimestamp(
				$user,
				$title,
				'force'
			)
		);
	}

	/**
	 * @param ActivityUpdateJob $job
	 * @param LinkTarget|PageIdentity $expectedTitle
	 * @param string $expectedUserId
	 * @param callable $notificationTimestampCondition
	 */
	private function verifyCallbackJob(
		ActivityUpdateJob $job,
		$expectedTitle,
		$expectedUserId,
		callable $notificationTimestampCondition
	) {
		$this->assertEquals( $expectedTitle->getDBkey(), $job->getTitle()->getDBkey() );
		$this->assertEquals( $expectedTitle->getNamespace(), $job->getTitle()->getNamespace() );

		$jobParams = $job->getParams();
		$this->assertArrayHasKey( 'type', $jobParams );
		$this->assertEquals( 'updateWatchlistNotification', $jobParams['type'] );
		$this->assertArrayHasKey( 'userid', $jobParams );
		$this->assertEquals( $expectedUserId, $jobParams['userid'] );
		$this->assertArrayHasKey( 'notifTime', $jobParams );
		$this->assertTrue( $notificationTimestampCondition( $jobParams['notifTime'] ) );
	}

	/**
	 * @dataProvider provideTestPageFactory
	 */
	public function testResetNotificationTimestamp_oldidSpecifiedLatestRevisionForced( $testPageFactory ) {
		$user = new UserIdentityValue( 1, 'MockUser' );
		$oldid = 22;
		$title = $testPageFactory( 100, 0, 'SomeTitle' );

		$mockDb = $this->getMockDb();
		$mockDb->expects( $this->never() )
			->method( 'selectRow' );

		$mockCache = $this->getMockCache();
		$mockCache->expects( $this->never() )->method( 'get' );
		$mockCache->expects( $this->never() )->method( 'set' );
		$mockCache->expects( $this->once() )
			->method( 'delete' )
			->with( '0:SomeTitle:1' );

		$mockQueueGroup = $this->getMockJobQueueGroup( false );

		$mockRevisionRecord = $this->createNoOpMock( RevisionRecord::class );

		$mockRevisionLookup = $this->getMockRevisionLookup( [
			'getTimestampFromId' => static function () {
				return '00000000000000';
			},
			'getRevisionById' => function ( $id, $flags ) use ( $oldid, $mockRevisionRecord ) {
				$this->assertSame( $oldid, $id );
				$this->assertSame( 0, $flags );
				return $mockRevisionRecord;
			},
			'getNextRevision' =>
			function ( $oldRev ) use ( $mockRevisionRecord ) {
				$this->assertSame( $mockRevisionRecord, $oldRev );
				return false;
			},
		], [
			'getNextRevision' => 1,
		] );

		$userFactory = $this->getUserFactory(
			[ $this->getMockUser( $user ) ]
		);

		$titleFactory = $this->getTitleFactory( $title );

		$store = $this->newWatchedItemStore( [
			'db' => $mockDb,
			'queueGroup' => $mockQueueGroup,
			'cache' => $mockCache,
			'revisionLookup' => $mockRevisionLookup,
			'userFactory' => $userFactory,
			'titleFactory' => $titleFactory,
		] );

		$mockQueueGroup->expects( $this->any() )
			->method( 'lazyPush' )
			->will( $this->returnCallback(
				function ( ActivityUpdateJob $job ) use ( $title, $user ) {
					$this->verifyCallbackJob(
						$job,
						$title,
						$user->getId(),
						static function ( $time ) {
							return $time === null;
						}
					);
				}
			) );

		$this->assertTrue(
			$store->resetNotificationTimestamp(
				$user,
				$title,
				'force',
				$oldid
			)
		);
	}

	/**
	 * @dataProvider provideTestPageFactory
	 */
	public function testResetNotificationTimestamp_oldidSpecifiedNotLatestRevisionForced( $testPageFactory ) {
		$user = new UserIdentityValue( 1, 'MockUser' );
		$oldid = 22;
		$title = $testPageFactory( 100, 0, 'SomeDbKey' );

		$mockRevision = $this->createNoOpMock( RevisionRecord::class );
		$mockNextRevision = $this->createNoOpMock( RevisionRecord::class );

		$mockDb = $this->getMockDb();
		$mockDb->expects( $this->once() )
			->method( 'addQuotes' )
			->willReturn( '20200101000000' );
		$makeListSql = "wl_namespace = 0 AND wl_title = 'SomeDbKey'";
		$mockDb->expects( $this->exactly( 2 ) )
			->method( 'makeList' )
			->willReturnOnConsecutiveCalls( $makeListSql, $makeListSql );
		$mockDb->expects( $this->once() )
			->method( 'select' )
			->with(
				[ 'watchlist', 'watchlist_expiry' ],
				[ 'wl_namespace', 'wl_title', 'wl_notificationtimestamp', 'we_expiry' ],
				[
					'wl_user' => 1,
					$makeListSql,
					'we_expiry IS NULL OR we_expiry > 20200101000000'
				]
			)
			->will( $this->returnValue( [
				(object)[
					'wl_namespace' => 0,
					'wl_title' => 'SomeDbKey',
					'wl_notificationtimestamp' => '20151212010101',
				]
			] ) );

		$mockCache = $this->getMockCache();
		$mockCache->expects( $this->never() )->method( 'get' );
		$mockCache->expects( $this->once() )
			->method( 'set' )
			->with( '0:SomeDbKey:1', $this->isType( 'object' ) );
		$mockCache->expects( $this->once() )
			->method( 'delete' )
			->with( '0:SomeDbKey:1' );

		$mockQueueGroup = $this->getMockJobQueueGroup( false );

		$mockRevisionLookup = $this->getMockRevisionLookup(
			[
				'getTimestampFromId' => function ( $oldidParam ) use ( $oldid ) {
					$this->assertSame( $oldid, $oldidParam );
				},
				'getRevisionById' => function ( $id ) use ( $oldid, $mockRevision ) {
					$this->assertSame( $oldid, $id );
					return $mockRevision;
				},
				'getNextRevision' =>
				function ( RevisionRecord $rev ) use ( $mockRevision, $mockNextRevision ) {
					$this->assertSame( $mockRevision, $rev );
					return $mockNextRevision;
				},
			],
			[
				'getTimestampFromId' => 2,
				'getRevisionById' => 1,
				'getNextRevision' => 1,
			]
		);

		$userFactory = $this->getUserFactory(
			[ $this->getMockUser( $user ) ]
		);

		$titleFactory = $this->getTitleFactory( $title );

		$store = $this->newWatchedItemStore( [
			'db' => $mockDb,
			'queueGroup' => $mockQueueGroup,
			'cache' => $mockCache,
			'revisionLookup' => $mockRevisionLookup,
			'userFactory' => $userFactory,
			'titleFactory' => $titleFactory,
		] );

		$mockQueueGroup->expects( $this->any() )
			->method( 'lazyPush' )
			->will( $this->returnCallback(
				function ( ActivityUpdateJob $job ) use ( $title, $user ) {
					$this->verifyCallbackJob(
						$job,
						$title,
						$user->getId(),
						static function ( $time ) {
							return $time !== null && $time > '20151212010101';
						}
					);
				}
			) );

		$this->assertTrue(
			$store->resetNotificationTimestamp(
				$user,
				$title,
				'force',
				$oldid
			)
		);
	}

	/**
	 * @dataProvider provideTestPageFactory
	 */
	public function testResetNotificationTimestamp_notWatchedPageForced( $testPageFactory ) {
		$user = new UserIdentityValue( 1, 'MockUser' );
		$oldid = 22;
		$title = $testPageFactory( 100, 0, 'SomeDbKey' );

		$mockDb = $this->getMockDb();
		$mockDb->expects( $this->once() )
			->method( 'addQuotes' )
			->willReturn( '20200101000000' );
		$makeListSql = "wl_namespace = 0 AND wl_title = 'SomeDbKey'";
		$mockDb->expects( $this->exactly( 2 ) )
			->method( 'makeList' )
			->willReturnOnConsecutiveCalls( $makeListSql, $makeListSql );
		$mockDb->expects( $this->once() )
			->method( 'select' )
			->with(
				[ 'watchlist', 'watchlist_expiry' ],
				[ 'wl_namespace', 'wl_title', 'wl_notificationtimestamp', 'we_expiry' ],
				[
					'wl_user' => 1,
					$makeListSql,
					'we_expiry IS NULL OR we_expiry > 20200101000000'
				]
			)
			->will( $this->returnValue( false ) );

		$mockCache = $this->getMockCache();
		$mockCache->expects( $this->never() )->method( 'get' );
		$mockCache->expects( $this->never() )->method( 'set' );
		$mockCache->expects( $this->once() )
			->method( 'delete' )
			->with( '0:SomeDbKey:1' );

		$mockQueueGroup = $this->getMockJobQueueGroup( false );

		$mockRevision = $this->createNoOpMock( RevisionRecord::class );
		$mockNextRevision = $this->createNoOpMock( RevisionRecord::class );

		$mockRevisionLookup = $this->getMockRevisionLookup(
			[
				'getTimestampFromId' => function ( $oldidParam ) use ( $oldid ) {
					$this->assertSame( $oldid, $oldidParam );
				},
				'getRevisionById' => function ( $id ) use ( $oldid, $mockRevision ) {
					$this->assertSame( $oldid, $id );
					return $mockRevision;
				},
				'getNextRevision' =>
				function ( RevisionRecord $rev ) use ( $mockRevision, $mockNextRevision ) {
					$this->assertSame( $mockRevision, $rev );
					return $mockNextRevision;
				},
			],
			[
				'getTimestampFromId' => 1,
				'getRevisionById' => 1,
				'getNextRevision' => 1,
			]
		);

		$userFactory = $this->getUserFactory(
			[ $this->getMockUser( $user ) ]
		);

		$titleFactory = $this->getTitleFactory( $title );

		$store = $this->newWatchedItemStore( [
			'db' => $mockDb,
			'queueGroup' => $mockQueueGroup,
			'cache' => $mockCache,
			'revisionLookup' => $mockRevisionLookup,
			'userFactory' => $userFactory,
			'titleFactory' => $titleFactory,
		] );

		$mockQueueGroup->expects( $this->any() )
			->method( 'lazyPush' )
			->will( $this->returnCallback(
				function ( ActivityUpdateJob $job ) use ( $title, $user ) {
					$this->verifyCallbackJob(
						$job,
						$title,
						$user->getId(),
						static function ( $time ) {
							return $time === null;
						}
					);
				}
			) );

		$this->assertTrue(
			$store->resetNotificationTimestamp(
				$user,
				$title,
				'force',
				$oldid
			)
		);
	}

	/**
	 * @dataProvider provideTestPageFactory
	 */
	public function testResetNotificationTimestamp_futureNotificationTimestampForced( $testPageFactory ) {
		$user = new UserIdentityValue( 1, 'MockUser' );
		$oldid = 22;
		$title = $testPageFactory( 100, 0, 'SomeDbKey' );

		$mockDb = $this->getMockDb();
		$mockDb->expects( $this->once() )
			->method( 'addQuotes' )
			->willReturn( '20200101000000' );
		$makeListSql = "wl_namespace = 0 AND wl_title = 'SomeDbKey'";
		$mockDb->expects( $this->exactly( 2 ) )
			->method( 'makeList' )
			->willReturnOnConsecutiveCalls( $makeListSql, $makeListSql );
		$mockDb->expects( $this->once() )
			->method( 'select' )
			->with(
				[ 'watchlist', 'watchlist_expiry' ],
				[ 'wl_namespace', 'wl_title', 'wl_notificationtimestamp', 'we_expiry' ],
				[
					'wl_user' => 1,
					$makeListSql,
					'we_expiry IS NULL OR we_expiry > 20200101000000'
				]
			)
			->will( $this->returnValue( [
				(object)[
					'wl_namespace' => 0,
					'wl_title' => 'SomeDbKey',
					'wl_notificationtimestamp' => '30151212010101',
				]
			] ) );

		$mockCache = $this->getMockCache();
		$mockCache->expects( $this->never() )->method( 'get' );
		$mockCache->expects( $this->once() )
			->method( 'set' )
			->with( '0:SomeDbKey:1', $this->isType( 'object' ) );
		$mockCache->expects( $this->once() )
			->method( 'delete' )
			->with( '0:SomeDbKey:1' );

		$mockQueueGroup = $this->getMockJobQueueGroup( false );

		$mockRevision = $this->createNoOpMock( RevisionRecord::class );
		$mockNextRevision = $this->createNoOpMock( RevisionRecord::class );

		$mockRevisionLookup = $this->getMockRevisionLookup(
			[
				'getTimestampFromId' => function ( $oldidParam ) use ( $oldid ) {
					$this->assertEquals( $oldid, $oldidParam );
				},
				'getRevisionById' => function ( $id ) use ( $oldid, $mockRevision ) {
					$this->assertSame( $oldid, $id );
					return $mockRevision;
				},
				'getNextRevision' =>
				function ( RevisionRecord $rev ) use ( $mockRevision, $mockNextRevision ) {
					$this->assertSame( $mockRevision, $rev );
					return $mockNextRevision;
				},
			],
			[
				'getTimestampFromId' => 2,
				'getRevisionById' => 1,
				'getNextRevision' => 1,
			]
		);

		$userFactory = $this->getUserFactory(
			[ $this->getMockUser( $user ) ]
		);

		$titleFactory = $this->getTitleFactory( $title );

		$store = $this->newWatchedItemStore( [
			'db' => $mockDb,
			'queueGroup' => $mockQueueGroup,
			'cache' => $mockCache,
			'revisionLookup' => $mockRevisionLookup,
			'userFactory' => $userFactory,
			'titleFactory' => $titleFactory,
		] );

		$mockQueueGroup->expects( $this->any() )
			->method( 'lazyPush' )
			->will( $this->returnCallback(
				function ( ActivityUpdateJob $job ) use ( $title, $user ) {
					$this->verifyCallbackJob(
						$job,
						$title,
						$user->getId(),
						static function ( $time ) {
							return $time === '30151212010101';
						}
					);
				}
			) );

		$this->assertTrue(
			$store->resetNotificationTimestamp(
				$user,
				$title,
				'force',
				$oldid
			)
		);
	}

	/**
	 * @dataProvider provideTestPageFactory
	 */
	public function testResetNotificationTimestamp_futureNotificationTimestampNotForced( $testPageFactory ) {
		$user = new UserIdentityValue( 1, 'MockUser' );
		$oldid = 22;
		$title = $testPageFactory( 100, 0, 'SomeDbKey' );

		$mockDb = $this->getMockDb();
		$mockDb->expects( $this->once() )
			->method( 'addQuotes' )
			->willReturn( '20200101000000' );
		$makeListSql = "wl_namespace = 0 AND wl_title = 'SomeDbKey'";
		$mockDb->expects( $this->exactly( 2 ) )
			->method( 'makeList' )
			->willReturnOnConsecutiveCalls( $makeListSql, $makeListSql );
		$mockDb->expects( $this->once() )
			->method( 'select' )
			->with(
				[ 'watchlist', 'watchlist_expiry' ],
				[ 'wl_namespace', 'wl_title', 'wl_notificationtimestamp', 'we_expiry' ],
				[
					'wl_user' => 1,
					$makeListSql,
					'we_expiry IS NULL OR we_expiry > 20200101000000',
				]
			)
			->will( $this->returnValue( [
				(object)[
					'wl_namespace' => 0,
					'wl_title' => 'SomeDbKey',
					'wl_notificationtimestamp' => '30151212010101',
				]
			] ) );

		$mockCache = $this->getMockCache();
		$mockCache->expects( $this->never() )->method( 'get' );
		$mockCache->expects( $this->once() )
			->method( 'set' )
			->with( '0:SomeDbKey:1', $this->isType( 'object' ) );
		$mockCache->expects( $this->once() )
			->method( 'delete' )
			->with( '0:SomeDbKey:1' );

		$mockQueueGroup = $this->getMockJobQueueGroup( false );

		$mockRevision = $this->createNoOpMock( RevisionRecord::class );
		$mockNextRevision = $this->createNoOpMock( RevisionRecord::class );

		$mockRevisionLookup = $this->getMockRevisionLookup(
			[
				'getTimestampFromId' => function ( $oldidParam ) use ( $oldid ) {
					$this->assertEquals( $oldid, $oldidParam );
				},
				'getRevisionById' => function ( $id ) use ( $oldid, $mockRevision ) {
					$this->assertSame( $oldid, $id );
					return $mockRevision;
				},
				'getNextRevision' =>
				function ( RevisionRecord $rev ) use ( $mockRevision, $mockNextRevision ) {
					$this->assertSame( $mockRevision, $rev );
					return $mockNextRevision;
				},
			],
			[
				'getTimestampFromId' => 2,
				'getRevisionById' => 1,
				'getNextRevision' => 1,
			]
		);

		$userFactory = $this->getUserFactory(
			[ $this->getMockUser( $user ) ]
		);

		$titleFactory = $this->getTitleFactory( $title );

		$store = $this->newWatchedItemStore( [
			'db' => $mockDb,
			'queueGroup' => $mockQueueGroup,
			'cache' => $mockCache,
			'revisionLookup' => $mockRevisionLookup,
			'userFactory' => $userFactory,
			'titleFactory' => $titleFactory,
		] );

		$mockQueueGroup->expects( $this->any() )
			->method( 'lazyPush' )
			->will( $this->returnCallback(
				function ( ActivityUpdateJob $job ) use ( $title, $user ) {
					$this->verifyCallbackJob(
						$job,
						$title,
						$user->getId(),
						static function ( $time ) {
							return $time === false;
						}
					);
				}
			) );

		$this->assertTrue(
			$store->resetNotificationTimestamp(
				$user,
				$title,
				'',
				$oldid
			)
		);
	}

	public function testSetNotificationTimestampsForUser_anonUser() {
		$store = $this->newWatchedItemStore();
		$this->assertFalse( $store->setNotificationTimestampsForUser(
			new UserIdentityValue( 0, 'AnonUser' ), '' ) );
	}

	public function testSetNotificationTimestampsForUser_allRows() {
		$user = new UserIdentityValue( 1, 'MockUser' );
		$timestamp = '20100101010101';

		$store = $this->newWatchedItemStore();

		// Note: This does not actually assert the job is correct
		$callableCallCounter = 0;
		$mockCallback = function ( $callable ) use ( &$callableCallCounter ) {
			$callableCallCounter++;
			$this->assertIsCallable( $callable );
		};
		$scopedOverride = $store->overrideDeferredUpdatesAddCallableUpdateCallback( $mockCallback );

		$this->assertTrue(
			$store->setNotificationTimestampsForUser( $user, $timestamp )
		);
		$this->assertSame( 1, $callableCallCounter );
	}

	public function testSetNotificationTimestampsForUser_nullTimestamp() {
		$user = new UserIdentityValue( 1, 'MockUser' );
		$timestamp = null;

		$store = $this->newWatchedItemStore();

		// Note: This does not actually assert the job is correct
		$callableCallCounter = 0;
		$mockCallback = function ( $callable ) use ( &$callableCallCounter ) {
			$callableCallCounter++;
			$this->assertIsCallable( $callable );
		};
		$scopedOverride = $store->overrideDeferredUpdatesAddCallableUpdateCallback( $mockCallback );

		$this->assertTrue(
			$store->setNotificationTimestampsForUser( $user, $timestamp )
		);
	}

	/**
	 * @dataProvider provideTestPageFactory
	 */
	public function testSetNotificationTimestampsForUser_specificTargets( $testPageFactory ) {
		$user = new UserIdentityValue( 1, 'MockUser' );
		$timestamp = '20100101010101';
		$targets = [ $testPageFactory( 100, 0, 'Foo' ), $testPageFactory( 101, 0, 'Bar' ) ];

		$mockDb = $this->getMockDb();
		$mockDb->expects( $this->once() )
			->method( 'update' )
			->with(
				'watchlist',
				[ 'wl_notificationtimestamp' => 'TS' . $timestamp . 'TS' ],
				[ 'wl_user' => 1, 'wl_namespace' => 0, 'wl_title' => [ 'Foo', 'Bar' ] ]
			)
			->will( $this->returnValue( true ) );
		$mockDb->expects( $this->exactly( 1 ) )
			->method( 'timestamp' )
			->will( $this->returnCallback( static function ( $value ) {
				return 'TS' . $value . 'TS';
			} ) );
		$mockDb->expects( $this->once() )
			->method( 'affectedRows' )
			->will( $this->returnValue( 2 ) );

		$store = $this->newWatchedItemStore( [ 'db' => $mockDb ] );

		$this->assertTrue(
			$store->setNotificationTimestampsForUser( $user, $timestamp, $targets )
		);
	}

	/**
	 * @dataProvider provideTestPageFactory
	 */
	public function testUpdateNotificationTimestamp_watchersExist( $testPageFactory ) {
		$mockDb = $this->getMockDb();
		$mockDb->expects( $this->once() )
			->method( 'addQuotes' )
			->willReturn( '20200101000000' );
		$mockDb->expects( $this->once() )
			->method( 'selectFieldValues' )
			->with(
				[ 'watchlist', 'watchlist_expiry' ],
				'wl_user',
				[
					'wl_user != 1',
					'wl_namespace' => 0,
					'wl_title' => 'SomeDbKey',
					'wl_notificationtimestamp IS NULL',
					'we_expiry IS NULL OR we_expiry > 20200101000000',
				]
			)
			->will( $this->returnValue( [ '2', '3' ] ) );
		$mockDb->expects( $this->once() )
			->method( 'update' )
			->with(
				'watchlist',
				[ 'wl_notificationtimestamp' => null ],
				[
					'wl_user' => [ 2, 3 ],
					'wl_namespace' => 0,
					'wl_title' => 'SomeDbKey',
				]
			);

		$mockCache = $this->getMockCache();
		$mockCache->expects( $this->never() )->method( 'set' );
		$mockCache->expects( $this->never() )->method( 'get' );
		$mockCache->expects( $this->never() )->method( 'delete' );

		$store = $this->newWatchedItemStore( [ 'db' => $mockDb, 'cache' => $mockCache ] );

		// updateNotificationTimestamp calls DeferredUpdates::addCallableUpdate
		// in normal operation, but we want to test that update actually running, so
		// override it
		$mockCallback = function ( $callable, $stage, $dbw ) use ( $mockDb ) {
			$this->assertIsCallable( $callable );
			$this->assertSame( DeferredUpdates::POSTSEND, $stage );
			$this->assertSame( $mockDb, $dbw );
			( $callable )();
		};
		$scopedOverride = $store->overrideDeferredUpdatesAddCallableUpdateCallback( $mockCallback );

		$this->assertEquals(
			[ 2, 3 ],
			$store->updateNotificationTimestamp(
				new UserIdentityValue( 1, 'MockUser' ),
				$testPageFactory( 100, 0, 'SomeDbKey' ),
				'20151212010101'
			)
		);
	}

	/**
	 * @dataProvider provideTestPageFactory
	 */
	public function testUpdateNotificationTimestamp_noWatchers( $testPageFactory ) {
		$mockDb = $this->getMockDb();
		$mockDb->expects( $this->once() )
			->method( 'addQuotes' )
			->willReturn( '20200101000000' );
		$mockDb->expects( $this->once() )
			->method( 'selectFieldValues' )
			->with(
				[ 'watchlist', 'watchlist_expiry' ],
				'wl_user',
				[
					'wl_user != 1',
					'wl_namespace' => 0,
					'wl_title' => 'SomeDbKey',
					'wl_notificationtimestamp IS NULL',
					'we_expiry IS NULL OR we_expiry > 20200101000000',
				],
				'WatchedItemStore::updateNotificationTimestamp',
				[],
				[ 'watchlist_expiry' => [ 'LEFT JOIN', 'wl_id = we_item' ] ]
			)
			->will(
				$this->returnValue( [] )
			);
		$mockDb->expects( $this->never() )
			->method( 'update' );

		$mockCache = $this->getMockCache();
		$mockCache->expects( $this->never() )->method( 'set' );
		$mockCache->expects( $this->never() )->method( 'get' );
		$mockCache->expects( $this->never() )->method( 'delete' );

		$store = $this->newWatchedItemStore( [ 'db' => $mockDb, 'cache' => $mockCache ] );

		$watchers = $store->updateNotificationTimestamp(
			new UserIdentityValue( 1, 'MockUser' ),
			$testPageFactory( 100, 0, 'SomeDbKey' ),
			'20151212010101'
		);
		$this->assertSame( [], $watchers );
	}

	/**
	 * @dataProvider provideTestPageFactory
	 */
	public function testUpdateNotificationTimestamp_clearsCachedItems( $testPageFactory ) {
		$user = new UserIdentityValue( 1, 'MockUser' );
		$titleValue = $testPageFactory( 100, 0, 'SomeDbKey' );

		$mockDb = $this->getMockDb();
		$mockDb->expects( $this->once() )
			->method( 'select' )
			->will( $this->returnValue( [
				(object)[
					'wl_namespace' => 0,
					'wl_title' => 'SomeDbKey',
					'wl_notificationtimestamp' => '20151212010101'
				]
			] ) );
		$mockDb->expects( $this->once() )
			->method( 'selectFieldValues' )
			->will(
				$this->returnValue( [ '2', '3' ] )
			);
		$mockDb->expects( $this->once() )
			->method( 'update' );

		$mockCache = $this->getMockCache();
		$mockCache->expects( $this->once() )
			->method( 'set' )
			->with( '0:SomeDbKey:1', $this->isType( 'object' ) );
		$mockCache->expects( $this->once() )
			->method( 'get' )
			->with( '0:SomeDbKey:1' );
		$mockCache->expects( $this->once() )
			->method( 'delete' )
			->with( '0:SomeDbKey:1' );

		$store = $this->newWatchedItemStore( [ 'db' => $mockDb, 'cache' => $mockCache ] );

		// This will add the item to the cache
		$store->getWatchedItem( $user, $titleValue );

		// updateNotificationTimestamp calls DeferredUpdates::addCallableUpdate
		// in normal operation, but we want to test that update actually running, so
		// override it
		$mockCallback = function ( $callable, $stage, $dbw ) use ( $mockDb ) {
			$this->assertIsCallable( $callable );
			$this->assertSame( DeferredUpdates::POSTSEND, $stage );
			$this->assertSame( $mockDb, $dbw );
			( $callable )();
		};
		$scopedOverride = $store->overrideDeferredUpdatesAddCallableUpdateCallback( $mockCallback );

		$store->updateNotificationTimestamp(
			new UserIdentityValue( 1, 'MockUser' ),
			$titleValue,
			'20151212010101'
		);
	}

	public function testRemoveExpired() {
		$mockDb = $this->getMockDb();

		// addQuotes is used for the expiry value.
		$mockDb->expects( $this->once() )
			->method( 'addQuotes' )
			->willReturn( '20200101000000' );

		// Select watchlist IDs.
		$mockDb->expects( $this->exactly( 2 ) )
			->method( 'selectFieldValues' )
			->withConsecutive(
				// Select expired items.
				[
					'watchlist_expiry',
					'we_item',
					[ 'we_expiry <= 20200101000000' ],
					'WatchedItemStore::removeExpired',
					[ 'LIMIT' => 2 ]
				],
				// Select orphaned items.
				[
					[ 'watchlist_expiry', 'watchlist' ],
					'we_item',
					[ 'wl_id' => null, 'we_expiry' => null ],
					'WatchedItemStore::removeExpired',
					[],
					[ 'watchlist' => [ 'LEFT JOIN', 'wl_id = we_item' ] ]
				]
			)
			->willReturnOnConsecutiveCalls(
				[ 1, 2 ],
				[ 3 ]
			);

		// Return whatever is passed to makeList, to be tested below.
		$mockDb->expects( $this->once() )
			->method( 'makeList' )
			->willReturnArgument( 0 );

		// Delete from watchlist and watchlist_expiry.
		$mockDb->expects( $this->exactly( 3 ) )
			->method( 'delete' )
			->withConsecutive(
				// Delete expired items from watchlist
				[
					'watchlist',
					[ 'wl_id' => [ 1, 2 ] ],
					'WatchedItemStore::removeExpired'
				],
				// Delete expired items from watchlist_expiry
				[
					'watchlist_expiry',
					[ 'we_item' => [ 1, 2 ] ],
					'WatchedItemStore::removeExpired'
				],
				// Delete orphaned items
				[
					'watchlist_expiry',
					[ 'we_item' => [ 3 ] ],
					'WatchedItemStore::removeExpired'
				]
			);

		$mockCache = $this->getMockCache();
		$store = $this->newWatchedItemStore( [ 'db' => $mockDb, 'cache' => $mockCache ] );
		$store->removeExpired( 2, true );
	}
}
