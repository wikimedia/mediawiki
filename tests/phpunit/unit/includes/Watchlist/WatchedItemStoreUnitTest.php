<?php

use MediaWiki\Cache\GenderCache;
use MediaWiki\Cache\LinkBatchFactory;
use MediaWiki\Cache\LinkCache;
use MediaWiki\Config\ServiceOptions;
use MediaWiki\Deferred\DeferredUpdates;
use MediaWiki\JobQueue\Job;
use MediaWiki\JobQueue\JobQueueGroup;
use MediaWiki\Language\Language;
use MediaWiki\Linker\LinksMigration;
use MediaWiki\Linker\LinkTarget;
use MediaWiki\Logger\LoggerFactory;
use MediaWiki\MainConfigNames;
use MediaWiki\Page\PageIdentity;
use MediaWiki\Page\PageIdentityValue;
use MediaWiki\Page\PageReferenceValue;
use MediaWiki\Revision\RevisionLookup;
use MediaWiki\Revision\RevisionRecord;
use MediaWiki\Tests\Unit\DummyServicesTrait;
use MediaWiki\Tests\Unit\Libs\Rdbms\AddQuoterMock;
use MediaWiki\Title\TitleFormatter;
use MediaWiki\User\TempUser\TempUserDetailsLookup;
use MediaWiki\User\UserIdentityValue;
use MediaWiki\Watchlist\ActivityUpdateJob;
use MediaWiki\Watchlist\WatchedItem;
use MediaWiki\Watchlist\WatchedItemStore;
use PHPUnit\Framework\MockObject\MockObject;
use Wikimedia\MapCacheLRU\MapCacheLRU;
use Wikimedia\ObjectCache\HashBagOStuff;
use Wikimedia\Rdbms\DeleteQueryBuilder;
use Wikimedia\Rdbms\Expression;
use Wikimedia\Rdbms\FakeResultWrapper;
use Wikimedia\Rdbms\IDatabase;
use Wikimedia\Rdbms\IExpression;
use Wikimedia\Rdbms\InsertQueryBuilder;
use Wikimedia\Rdbms\LBFactory;
use Wikimedia\Rdbms\ReplaceQueryBuilder;
use Wikimedia\Rdbms\SelectQueryBuilder;
use Wikimedia\Rdbms\UpdateQueryBuilder;
use Wikimedia\Stats\StatsFactory;
use Wikimedia\TestingAccessWrapper;

/**
 * @author Addshore
 * @author DannyS712
 *
 * @covers \MediaWiki\Watchlist\WatchedItemStore
 */
class WatchedItemStoreUnitTest extends MediaWikiUnitTestCase {
	use DummyServicesTrait;
	use MockTitleTrait;

	private StatsFactory $statsFactory;

	protected function setUp(): void {
		parent::setUp();
		$this->statsFactory = StatsFactory::newNull();
	}

	/**
	 * @return MockObject&IDatabase
	 */
	private function getMockDb(): IDatabase {
		$mock = $this->createMock( IDatabase::class );
		$mock->method( 'newSelectQueryBuilder' )->willReturnCallback( static fn () => new SelectQueryBuilder( $mock ) );
		$mock->method( 'newUpdateQueryBuilder' )->willReturnCallback( static fn () => new UpdateQueryBuilder( $mock ) );
		$mock->method( 'newDeleteQueryBuilder' )->willReturnCallback( static fn () => new DeleteQueryBuilder( $mock ) );
		$mock->method( 'newReplaceQueryBuilder' )->willReturnCallback( static fn () => new ReplaceQueryBuilder( $mock ) );
		$mock->method( 'newInsertQueryBuilder' )->willReturnCallback( static fn () => new InsertQueryBuilder( $mock ) );
		$mock->method( 'expr' )->willReturnCallback(
			static fn ( $field, $op, $value ) => new Expression( $field, $op, $value )
		);
		return $mock;
	}

	private function expandExpr( array $conds ): array {
		foreach ( $conds as &$cond ) {
			if ( $cond instanceof IExpression ) {
				$cond = $cond->toSql( new AddQuoterMock() );
			}
		}
		return $conds;
	}

	/**
	 * @param IDatabase $mockDb
	 * @return MockObject&LBFactory
	 */
	private function getMockLBFactory( IDatabase $mockDb ): LBFactory {
		$mock = $this->createMock( LBFactory::class );
		$mock->method( 'getLocalDomainID' )
			->willReturn( 'phpunitdb' );
		$mock->method( 'getPrimaryDatabase' )
			->willReturn( $mockDb );
		$mock->method( 'getReplicaDatabase' )
			->willReturn( $mockDb );
		$mock->method( 'getLBsForOwner' )
			->willReturn( [] );
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
	 * @return MockObject&JobQueueGroup
	 */
	private function getMockJobQueueGroup( bool $mockLazyPush = true ): JobQueueGroup {
		$mock = $this->createMock( JobQueueGroup::class );
		$mock->method( 'push' )
			->willReturnCallback( static function ( Job $job ) {
				$job->run();
			} );
		if ( $mockLazyPush ) {
			$mock->method( 'lazyPush' )
				->willReturnCallback( static function ( Job $job ) {
					$job->run();
				} );
		}
		return $mock;
	}

	/**
	 * @return MockObject&HashBagOStuff
	 */
	private function getMockCache( array $allow = [] ): HashBagOStuff {
		$mock = $this->createNoOpMock( HashBagOStuff::class, [ 'makeKey', ...$allow ] );
		$mock->method( 'makeKey' )
			->willReturnCallback( static fn ( ...$args ) => implode( ':', $args ) );
		return $mock;
	}

	/**
	 * No methods may be called except provided callbacks, if any.
	 *
	 * @param array $callbacks Keys are method names, values are callbacks
	 * @param array $counts Keys are method names, values are expected number of times to be called
	 *   (default is any number is okay)
	 * @return MockObject&RevisionLookup
	 */
	private function getMockRevisionLookup(
		array $callbacks = [],
		array $counts = []
	): RevisionLookup {
		$mock = $this->createNoOpMock( RevisionLookup::class, array_keys( $callbacks ) );
		foreach ( $callbacks as $method => $callback ) {
			$count = isset( $counts[$method] ) ? $this->exactly( $counts[$method] ) : $this->any();
			$mock->expects( $count )
				->method( $method )
				->willReturnCallback( $callbacks[$method] );
		}
		return $mock;
	}

	private function getMockLinkBatchFactory( IDatabase $mockDb ): LinkBatchFactory {
		return new LinkBatchFactory(
			$this->createMock( LinkCache::class ),
			$this->createMock( TitleFormatter::class ),
			$this->createMock( Language::class ),
			$this->createMock( GenderCache::class ),
			$this->getMockLBFactory( $mockDb ),
			$this->createMock( LinksMigration::class ),
			$this->createMock( TempUserDetailsLookup::class ),
			LoggerFactory::getInstance( 'LinkBatch' )
		);
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
	 *     * expiryEnabled
	 *     * maxExpiryDuration
	 *     * watchlistPurgeRate
	 * @return WatchedItemStore
	 */
	private function newWatchedItemStore( array $mocks = [] ): WatchedItemStore {
		$options = new ServiceOptions( WatchedItemStore::CONSTRUCTOR_OPTIONS, [
			MainConfigNames::UpdateRowsPerQuery => 1000,
			MainConfigNames::WatchlistExpiry => $mocks['expiryEnabled'] ?? true,
			MainConfigNames::WatchlistExpiryMaxDuration => $mocks['maxExpiryDuration'] ?? null,
			MainConfigNames::WatchlistPurgeRate => $mocks['watchlistPurgeRate'] ?? 0.1,
		] );

		$db = $mocks['db'] ?? $this->getMockDb();

		// If we don't use a manual mock for something specific, get a full
		// NamespaceInfo service from DummyServicesTrait::getDummyNamespaceInfo
		$nsInfo = $mocks['nsInfo'] ?? $this->getDummyNamespaceInfo();

		return new WatchedItemStore(
			$options,
			$mocks['lbFactory'] ?? $this->getMockLBFactory( $db ),
			$mocks['queueGroup'] ?? $this->getMockJobQueueGroup(),
			$mocks['stash'] ?? new HashBagOStuff(),
			$mocks['cache'] ?? $this->createNoOpMock( HashBagOStuff::class ),
			$mocks['readOnlyMode'] ?? $this->getDummyReadOnlyMode( false ),
			$nsInfo,
			$mocks['revisionLookup'] ?? $this->getMockRevisionLookup(),
			$this->getMockLinkBatchFactory( $db ),
			$this->statsFactory
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
			->willReturn( 12 );
		$mockDb->expects( $this->once() )
			->method( 'delete' )
			->with(
				'watchlist',
				[ 'wl_user' => 7 ],
				$this->isType( 'string' )
			);

		$mockCache = $this->getMockCache( [ 'delete' ] );
		$mockCache->expects( $this->once() )
			->method( 'delete' )
			->with( 'RM-KEY' );

		$store = $this->newWatchedItemStore( [
			'db' => $mockDb,
			'cache' => $mockCache,
			'expiryEnabled' => false,
		] );
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

		$deleteArgs = [
			[
				'watchlist',
				[ 'wl_id' => [ 1, 2 ] ]
			],
			[
				'watchlist_expiry',
				[ 'we_item' => [ 1, 2 ] ]
			]
		];
		$mockDb->expects( $this->exactly( 2 ) )
			->method( 'delete' )
			->willReturnCallback( function ( $table, $conds ) use ( &$deleteArgs ): void {
				[ $nextTable, $nextConds ] = array_shift( $deleteArgs );
				$this->assertSame( $nextTable, $table );
				$this->assertSame( $nextConds, $conds );
			} );
		$mockDb->method( 'timestamp' )
			->willReturnArgument( 0 );

		$mockCache = $this->getMockCache( [ 'delete' ] );
		$mockCache->expects( $this->once() )
			->method( 'delete' )
			->with( 'RM-KEY' );

		$store = $this->newWatchedItemStore( [
			'db' => $mockDb,
			'cache' => $mockCache,
			'expiryEnabled' => true,
		] );
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
			->willReturn( 99999 );

		$mockCache = $this->createNoOpMock( HashBagOStuff::class );

		$store = $this->newWatchedItemStore( [
			'db' => $mockDb,
			'cache' => $mockCache,
			'expiryEnabled' => false,
		] );

		$this->assertFalse( $store->clearUserWatchedItems( $user ) );
	}

	public function testCountWatchedItems() {
		$user = new UserIdentityValue( 1, 'MockUser' );

		$mockDb = $this->getMockDb();
		$mockDb->expects( $this->once() )
			->method( 'timestamp' )
			->willReturn( '20200101000000' );
		$selectArgs = [
			[
				[ 'watchlist', 'watchlist_expiry' => 'watchlist_expiry' ],
				'COUNT(*)',
				[
					'wl_user' => $user->getId(),
					'(we_expiry IS NULL OR we_expiry > \'20200101000000\')'
				],
				'12',
			],
		];
		$mockDb->expects( $this->once() )
			->method( 'selectField' )
			->willReturnCallback( function ( $table, $vars, $conds, $fname, $options, $join_conds ) use ( &$selectArgs ) {
				[ $nextTable, $nextVars, $nextConds, $returnValue ] = array_shift( $selectArgs );
				$this->assertSame( $nextTable, $table );
				$this->assertSame( $nextVars, $vars );
				$this->assertSame( $nextConds, $this->expandExpr( $conds ) );
				return $returnValue;
			} );

		$mockCache = $this->createNoOpMock( HashBagOStuff::class );

		$store = $this->newWatchedItemStore( [ 'db' => $mockDb, 'cache' => $mockCache ] );

		$this->assertEquals( 12, $store->countWatchedItems( $user ) );
	}

	public static function provideTestPageFactory() {
		yield [ static function ( $pageId, $namespace, $dbKey ) {
			return PageIdentityValue::localIdentity( $pageId, $namespace, $dbKey );
		} ];
		yield [ static function ( $pageId, $namespace, $dbKey, $testCase ) {
			return $testCase->makeMockTitle( $dbKey, [
				'id' => $pageId,
				'namespace' => $namespace
			] );
		} ];
	}

	/**
	 * @dataProvider provideTestPageFactory
	 */
	public function testCountWatchers( $testPageFactory ) {
		$titleValue = $testPageFactory( 100, 0, 'SomeDbKey', $this );

		$mockDb = $this->getMockDb();
		$mockDb->expects( $this->once() )
			->method( 'timestamp' )
			->willReturn( '20200101000000' );
		$selectArgs = [
			[
				[ 'watchlist', 'watchlist_expiry' => 'watchlist_expiry' ],
				'COUNT(*)',
				[
					'wl_namespace' => $titleValue->getNamespace(),
					'wl_title' => $titleValue->getDBkey(),
					'(we_expiry IS NULL OR we_expiry > \'20200101000000\')'
				],
				'7',
			],
		];
		$mockDb->expects( $this->once() )
			->method( 'selectField' )
			->willReturnCallback( function ( $table, $vars, $conds, $fname, $options, $join_conds ) use ( &$selectArgs ) {
				[ $nextTable, $nextVars, $nextConds, $returnValue ] = array_shift( $selectArgs );
				$this->assertSame( $nextTable, $table );
				$this->assertSame( $nextVars, $vars );
				$this->assertSame( $nextConds, $this->expandExpr( $conds ) );
				return $returnValue;
			} );

		$mockCache = $this->createNoOpMock( HashBagOStuff::class );

		$store = $this->newWatchedItemStore( [ 'db' => $mockDb, 'cache' => $mockCache ] );

		$this->assertEquals( 7, $store->countWatchers( $titleValue ) );
	}

	/**
	 * @dataProvider provideTestPageFactory
	 */
	public function testCountWatchersMultiple( $testPageFactory ) {
		$titleValues = [
			$testPageFactory( 100, 0, 'SomeDbKey', $this ),
			$testPageFactory( 101, 0, 'OtherDbKey', $this ),
			$testPageFactory( 102, 1, 'AnotherDbKey', $this ),
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
			->willReturn( 'makeWhereFrom2d return value' );

		$mockDb->expects( $this->once() )
			->method( 'timestamp' )
			->willReturn( '20200101000000' );

		$selectArgs = [
			[
				[ 'watchlist', 'watchlist_expiry' => 'watchlist_expiry' ],
				[ 'wl_title', 'wl_namespace', 'watchers' => 'COUNT(*)' ],
				[
					'makeWhereFrom2d return value',
					'(we_expiry IS NULL OR we_expiry > \'20200101000000\')'
				],
				[
					'GROUP BY' => [ 'wl_namespace', 'wl_title' ],
				],
				new FakeResultWrapper( $dbResult ),
			],
		];
		$mockDb->expects( $this->once() )
			->method( 'select' )
			->willReturnCallback( function ( $table, $vars, $conds, $fname, $options, $join_conds ) use ( &$selectArgs ) {
				[ $nextTable, $nextVars, $nextConds, $nextOptions, $returnValue ] = array_shift( $selectArgs );
				$this->assertSame( $nextTable, $table );
				$this->assertSame( $nextVars, $vars );
				$this->assertSame( $nextConds, $this->expandExpr( $conds ) );
				$this->assertSame( $nextOptions, $options );
				return $returnValue;
			} );

		$mockCache = $this->createNoOpMock( HashBagOStuff::class );

		$store = $this->newWatchedItemStore( [ 'db' => $mockDb, 'cache' => $mockCache ] );

		$expected = [
			0 => [ 'SomeDbKey' => 100, 'OtherDbKey' => 300 ],
			1 => [ 'AnotherDbKey' => 500 ],
		];
		$this->assertEquals( $expected, $store->countWatchersMultiple( $titleValues ) );
	}

	public static function provideIntWithDbUnsafeVersion() {
		return [
			[ 50 ],
			[ "50; DROP TABLE watchlist;\n--" ],
		];
	}

	public static function provideTestPageFactoryAndIntWithDbUnsafeVersion() {
		foreach ( self::provideIntWithDbUnsafeVersion() as [ $dbint ] ) {
			foreach ( self::provideTestPageFactory() as [ $testPageFactory ] ) {
				yield [ $dbint, $testPageFactory ];
			}
		}
	}

	/**
	 * @dataProvider provideTestPageFactoryAndIntWithDbUnsafeVersion
	 */
	public function testCountWatchersMultiple_withMinimumWatchers( $minWatchers, $testPageFactory ) {
		$titleValues = [
			$testPageFactory( 100, 0, 'SomeDbKey', $this ),
			$testPageFactory( 101, 0, 'OtherDbKey', $this ),
			$testPageFactory( 102, 1, 'AnotherDbKey', $this ),
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
			->willReturn( 'makeWhereFrom2d return value' );

		$mockDb->expects( $this->once() )
			->method( 'timestamp' )
			->willReturn( '20200101000000' );

		$selectArgs = [
			[
				[ 'watchlist', 'watchlist_expiry' => 'watchlist_expiry' ],
				[ 'wl_title', 'wl_namespace', 'watchers' => 'COUNT(*)' ],
				[
					'makeWhereFrom2d return value',
					'(we_expiry IS NULL OR we_expiry > \'20200101000000\')'
				],
				[
					'GROUP BY' => [ 'wl_namespace', 'wl_title' ],
					'HAVING' => [ 'COUNT(*) >= 50' ],
				],
				new FakeResultWrapper( $dbResult ),
			],
		];
		$mockDb->expects( $this->once() )
			->method( 'select' )
			->willReturnCallback( function ( $table, $vars, $conds, $fname, $options, $join_conds ) use ( &$selectArgs ) {
				[ $nextTable, $nextVars, $nextConds, $nextOptions, $returnValue ] = array_shift( $selectArgs );
				$this->assertSame( $nextTable, $table );
				$this->assertSame( $nextVars, $vars );
				$this->assertSame( $nextConds, $this->expandExpr( $conds ) );
				$this->assertSame( $nextOptions, $options );
				return $returnValue;
			} );

		$mockCache = $this->createNoOpMock( HashBagOStuff::class );

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
		$titleValue = $testPageFactory( 100, 0, 'SomeDbKey', $this );

		$mockDb = $this->getMockDb();

		$selectArgs = [
			[
				[ 'watchlist', 'watchlist_expiry' => 'watchlist_expiry' ],
				'COUNT(*)',
				[
					'wl_namespace' => $titleValue->getNamespace(),
					'wl_title' => $titleValue->getDBkey(),
					'(wl_notificationtimestamp >= \'TS111TS\' OR wl_notificationtimestamp IS NULL)',
					'(we_expiry IS NULL OR we_expiry > \'20200101000000\')'
				],
				'7',
			],
		];
		$mockDb->expects( $this->once() )
			->method( 'selectField' )
			->willReturnCallback( function ( $table, $vars, $conds, $fname, $options, $join_conds ) use ( &$selectArgs ) {
				[ $nextTable, $nextVars, $nextConds, $returnValue ] = array_shift( $selectArgs );
				$this->assertSame( $nextTable, $table );
				$this->assertSame( $nextVars, $vars );
				$this->assertSame( $nextConds, $this->expandExpr( $conds ) );
				return $returnValue;
			} );

		$mockDb->expects( $this->exactly( 2 ) )
			->method( 'timestamp' )
			->willReturnCallback( static fn ( $ts ) => $ts ? "TS{$ts}TS" : '20200101000000' );

		$mockCache = $this->createNoOpMock( HashBagOStuff::class );

		$store = $this->newWatchedItemStore( [ 'db' => $mockDb, 'cache' => $mockCache ] );

		$this->assertEquals( 7, $store->countVisitingWatchers( $titleValue, '111' ) );
	}

	/**
	 * @dataProvider provideTestPageFactory
	 */
	public function testCountVisitingWatchersMultiple( $testPageFactory ) {
		$titleValuesWithThresholds = [
			[ $testPageFactory( 100, 0, 'SomeDbKey', $this ), '111' ],
			[ $testPageFactory( 101, 0, 'OtherDbKey', $this ), '111' ],
			[ $testPageFactory( 102, 1, 'AnotherDbKey', $this ), '123' ],
		];

		$dbResult = [
			(object)[ 'wl_title' => 'SomeDbKey', 'wl_namespace' => '0', 'watchers' => '100' ],
			(object)[ 'wl_title' => 'OtherDbKey', 'wl_namespace' => '0', 'watchers' => '300' ],
			(object)[ 'wl_title' => 'AnotherDbKey', 'wl_namespace' => '1', 'watchers' => '500' ],
		];
		$mockDb = $this->getMockDb();
		$mockDb->expects( $this->exactly( 4 ) )
			->method( 'timestamp' )
			->willReturnCallback( static fn ( $ts ) => $ts ? "TS{$ts}TS" : '20200101000000' );

		$mockDb->method( 'makeList' )
			->with(
				$this->isType( 'array' ),
				$this->isType( 'int' )
			)
			->willReturnCallback( static function ( $a, $conj ) {
				$sqlConj = $conj === LIST_AND ? ' AND ' : ' OR ';
				return implode( $sqlConj, array_map( static function ( $s ) {
					return $s instanceof IExpression ? $s->toSql( new AddQuoterMock() ) : "($s)";
				}, $a
				) );
			} );
		$mockDb->expects( $this->never() )
			->method( 'makeWhereFrom2d' );

		$expectedCond =
			'((wl_namespace = 0) AND (' .
			"((wl_title = 'SomeDbKey' AND (" .
			"wl_notificationtimestamp >= 'TS111TS' OR wl_notificationtimestamp IS NULL" .
			')) OR (' .
			"wl_title = 'OtherDbKey' AND (" .
			"wl_notificationtimestamp >= 'TS111TS' OR wl_notificationtimestamp IS NULL" .
			'))))' .
			') OR ((wl_namespace = 1) AND (' .
			"((wl_title = 'AnotherDbKey' AND (" .
			"wl_notificationtimestamp >= 'TS123TS' OR wl_notificationtimestamp IS NULL" .
			')))))';
		$selectArgs = [
			[
				[ 'watchlist', 'watchlist_expiry' => 'watchlist_expiry' ],
				[ 'wl_namespace', 'wl_title', 'watchers' => 'COUNT(*)' ],
				[
					$expectedCond,
					'(we_expiry IS NULL OR we_expiry > \'20200101000000\')'
				],
				[
					'GROUP BY' => [ 'wl_namespace', 'wl_title' ],
				],
				new FakeResultWrapper( $dbResult ),
			],
		];
		$mockDb->expects( $this->once() )
			->method( 'select' )
			->willReturnCallback( function ( $table, $vars, $conds, $fname, $options, $join_conds ) use ( &$selectArgs ) {
				[ $nextTable, $nextVars, $nextConds, $nextOptions, $returnValue ] = array_shift( $selectArgs );
				$this->assertSame( $nextTable, $table );
				$this->assertSame( $nextVars, $vars );
				$this->assertSame( $nextConds, $this->expandExpr( $conds ) );
				$this->assertSame( $nextOptions, $options );
				return $returnValue;
			} );

		$mockCache = $this->createNoOpMock( HashBagOStuff::class );

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
			[ $testPageFactory( 100, 0, 'SomeDbKey', $this ), '111' ],
			[ $testPageFactory( 101, 0, 'OtherDbKey', $this ), '111' ],
			[ $testPageFactory( 102, 1, 'AnotherDbKey', $this ), '123' ],
			[ PageReferenceValue::localReference( 0, 'SomeNotExisitingDbKey' ), null ],
			[ PageReferenceValue::localReference( 0, 'OtherNotExisitingDbKey' ), null ],
		];

		$dbResult = [
			(object)[ 'wl_title' => 'SomeDbKey', 'wl_namespace' => '0', 'watchers' => '100' ],
			(object)[ 'wl_title' => 'OtherDbKey', 'wl_namespace' => '0', 'watchers' => '300' ],
			(object)[ 'wl_title' => 'AnotherDbKey', 'wl_namespace' => '1', 'watchers' => '500' ],
			(object)[ 'wl_title' => 'SomeNotExisitingDbKey', 'wl_namespace' => '0', 'watchers' => '100' ],
			(object)[ 'wl_title' => 'OtherNotExisitingDbKey', 'wl_namespace' => '0', 'watchers' => '200' ],
		];
		$mockDb = $this->getMockDb();
		$mockDb->expects( $this->exactly( 3 ) )
			->method( 'timestamp' )
			->willReturnCallback( static fn ( $ts ) => "TS{$ts}TS" );
		$mockDb->method( 'makeList' )
			->with(
				$this->isType( 'array' ),
				$this->isType( 'int' )
			)
			->willReturnCallback( static function ( $a, $conj ) {
				$sqlConj = $conj === LIST_AND ? ' AND ' : ' OR ';
				return implode( $sqlConj, array_map( static function ( $s ) {
					return $s instanceof IExpression ? $s->toSql( new AddQuoterMock() ) : "($s)";
				}, $a
				) );
			} );
		$mockDb->expects( $this->once() )
			->method( 'makeWhereFrom2d' )
			->with(
				[ [ 'SomeNotExisitingDbKey' => 1, 'OtherNotExisitingDbKey' => 1 ] ],
				$this->isType( 'string' ),
				$this->isType( 'string' )
			)
			->willReturn( 'makeWhereFrom2d return value' );

		$expectedCond =
			'((wl_namespace = 0) AND (' .
			"((wl_title = 'SomeDbKey' AND (" .
			"wl_notificationtimestamp >= 'TS111TS' OR wl_notificationtimestamp IS NULL" .
			')) OR (' .
			"wl_title = 'OtherDbKey' AND (" .
			"wl_notificationtimestamp >= 'TS111TS' OR wl_notificationtimestamp IS NULL" .
			'))))' .
			') OR ((wl_namespace = 1) AND (' .
			"((wl_title = 'AnotherDbKey' AND (" .
			"wl_notificationtimestamp >= 'TS123TS' OR wl_notificationtimestamp IS NULL" .
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
			->willReturn( new FakeResultWrapper( $dbResult ) );

		$mockCache = $this->createNoOpMock( HashBagOStuff::class );

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
			[ $testPageFactory( 100, 0, 'SomeDbKey', $this ), '111' ],
			[ $testPageFactory( 101, 0, 'OtherDbKey', $this ), '111' ],
			[ $testPageFactory( 102, 1, 'AnotherDbKey', $this ), '123' ],
		];

		$mockDb = $this->getMockDb();
		$mockDb->method( 'makeList' )
			->willReturn( 'makeList return value' );
		$mockDb->method( 'timestamp' )
			->willReturnArgument( 0 );
		$mockDb->expects( $this->once() )
			->method( 'select' )
			->with(
				[ 'watchlist' ],
				[ 'wl_namespace', 'wl_title', 'watchers' => 'COUNT(*)' ],
				[ 'makeList return value' ],
				$this->isType( 'string' ),
				[
					'GROUP BY' => [ 'wl_namespace', 'wl_title' ],
					'HAVING' => [ 'COUNT(*) >= 50' ],
				]
			)
			->willReturn( new FakeResultWrapper( [] ) );

		$mockCache = $this->createNoOpMock( HashBagOStuff::class );

		$store = $this->newWatchedItemStore( [
			'db' => $mockDb,
			'cache' => $mockCache,
			'expiryEnabled' => false,
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
		$mockDb->expects( $this->once() )
			->method( 'selectRowCount' )
			->with(
				[ 'watchlist' ],
				'1',
				[
					"wl_notificationtimestamp IS NOT NULL",
					'wl_user' => 1,
				],
				$this->isType( 'string' )
			)
			->willReturn( 9 );

		$mockCache = $this->createNoOpMock( HashBagOStuff::class );

		$store = $this->newWatchedItemStore( [ 'db' => $mockDb, 'cache' => $mockCache ] );

		$this->assertEquals( 9, $store->countUnreadNotifications( $user ) );
	}

	/**
	 * @dataProvider provideIntWithDbUnsafeVersion
	 */
	public function testCountUnreadNotifications_withUnreadLimit_overLimit( $limit ) {
		$user = new UserIdentityValue( 1, 'MockUser' );

		$mockDb = $this->getMockDb();
		$mockDb->expects( $this->once() )
			->method( 'selectRowCount' )
			->with(
				[ 'watchlist' ],
				'1',
				[
					"wl_notificationtimestamp IS NOT NULL",
					'wl_user' => 1,
				],
				$this->isType( 'string' ),
				[ 'LIMIT' => 50 ]
			)
			->willReturn( 50 );

		$mockCache = $this->createNoOpMock( HashBagOStuff::class );

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
		$mockDb->expects( $this->once() )
			->method( 'selectRowCount' )
			->with(
				[ 'watchlist' ],
				'1',
				[
					"wl_notificationtimestamp IS NOT NULL",
					'wl_user' => 1,
				],
				$this->isType( 'string' ),
				[ 'LIMIT' => 50 ]
			)
			->willReturn( 9 );

		$mockCache = $this->createNoOpMock( HashBagOStuff::class );

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
				[ 'watchlist', 'watchlist_expiry' => 'watchlist_expiry' ],
				[ 'wl_user', 'wl_notificationtimestamp', 'we_expiry' ],
				[
					'wl_namespace' => 0,
					'wl_title' => 'Old_Title',
				],
				'MediaWiki\Watchlist\WatchedItemStore::fetchWatchedItemsForPage',
				[ 'FOR UPDATE' ],
				[ 'watchlist_expiry' => [ 'LEFT JOIN', [ 'wl_id = we_item' ] ] ]
			)
			->willReturn( new FakeResultWrapper( [] ) );

		$store = $this->newWatchedItemStore( [ 'db' => $mockDb ] );

		$store->duplicateEntry(
			$testPageFactory( 100, 0, 'Old_Title', $this ),
			$testPageFactory( 101, 0, 'New_Title', $this )
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
		$mockDb->expects( $this->once() )
			->method( 'select' )
			->with(
				[ 'watchlist' ],
				[ 'wl_user', 'wl_notificationtimestamp' ],
				[
					'wl_namespace' => 0,
					'wl_title' => 'Old_Title',
				]
			)
			->willReturn(
				new FakeResultWrapper( $fakeRows ),
			);

		$mockCache = $this->createNoOpMock( HashBagOStuff::class );

		$store = $this->newWatchedItemStore( [
			'db' => $mockDb,
			'cache' => $mockCache,
			'expiryEnabled' => false,
		] );

		$store->duplicateEntry(
			$testPageFactory( 100, 0, 'Old_Title', $this ),
			$testPageFactory( 101, 0, 'New_Title', $this )
		);
	}

	/**
	 * @dataProvider provideTestPageFactory
	 */
	public function testDuplicateAllAssociatedEntries_nothingToDuplicate( $testPageFactory ) {
		$selectArgs = [
			[
				[ 'watchlist', 'watchlist_expiry' => 'watchlist_expiry' ],
				[ 'wl_user', 'wl_notificationtimestamp', 'we_expiry' ],
				[
					'wl_namespace' => 0,
					'wl_title' => 'Old_Title',
				]
			],
			[
				[ 'watchlist', 'watchlist_expiry' => 'watchlist_expiry' ],
				[ 'wl_user', 'wl_notificationtimestamp', 'we_expiry' ],
				[
					'wl_namespace' => 1,
					'wl_title' => 'Old_Title',
				]
			]
		];
		$mockDb = $this->getMockDb();
		$mockDb->expects( $this->exactly( 2 ) )
			->method( 'select' )
			->willReturnCallback( function ( $table, $vars, $conds ) use ( &$selectArgs ) {
				[ $nextTable, $nextVars, $nextConds ] = array_shift( $selectArgs );
				$this->assertSame( $nextTable, $table );
				$this->assertSame( $nextVars, $vars );
				$this->assertSame( $nextConds, $conds );
				return new FakeResultWrapper( [] );
			} );

		$mockCache = $this->createNoOpMock( HashBagOStuff::class );

		$store = $this->newWatchedItemStore( [ 'db' => $mockDb, 'cache' => $mockCache ] );

		$store->duplicateAllAssociatedEntries(
			$testPageFactory( 100, 0, 'Old_Title', $this ),
			$testPageFactory( 101, 0, 'New_Title', $this )
		);
	}

	/**
	 * @dataProvider provideTestPageFactory
	 */
	public function testDuplicateAllAssociatedEntries_somethingToDuplicate( $testPageFactory ) {
		$oldTarget = $testPageFactory( 100, 0, 'Old_Title', $this );
		$newTarget = $testPageFactory( 101, 0, 'New_Title', $this );

		$fakeRows = [
			(object)[
				'wl_user' => '1',
				'wl_notificationtimestamp' => '20151212010101',
				'we_expiry' => null,
			],
		];

		$selectArgs = [
			[
				[ 'watchlist' ],
				[ 'wl_user', 'wl_notificationtimestamp' ],
				[
					'wl_namespace' => $oldTarget->getNamespace(),
					'wl_title' => $oldTarget->getDBkey(),
				]
			],
			[
				[ 'watchlist' ],
				[ 'wl_user', 'wl_notificationtimestamp' ],
				[
					'wl_namespace' => $oldTarget->getNamespace() + 1,
					'wl_title' => $oldTarget->getDBkey(),
				]
			]
		];
		$mockDb = $this->getMockDb();
		$mockDb->expects( $this->exactly( 2 ) )
			->method( 'select' )
			->willReturnCallback( function ( $table, $vars, $conds ) use ( &$selectArgs, $fakeRows ) {
				[ $nextTable, $nextVars, $nextConds ] = array_shift( $selectArgs );
				$this->assertSame( $nextTable, $table );
				$this->assertSame( $nextVars, $vars );
				$this->assertSame( $nextConds, $conds );
				return new FakeResultWrapper( $fakeRows );
			} );
		$replaceArgs = [
			[
				'watchlist',
				[ [ 'wl_user', 'wl_namespace', 'wl_title' ] ],
				[
					[
						'wl_user' => '1',
						'wl_namespace' => $newTarget->getNamespace(),
						'wl_title' => $newTarget->getDBkey(),
						'wl_notificationtimestamp' => '20151212010101',
					],
				],
			],
			[
				'watchlist',
				[ [ 'wl_user', 'wl_namespace', 'wl_title' ] ],
				[
					[
						'wl_user' => '1',
						'wl_namespace' => $newTarget->getNamespace() + 1,
						'wl_title' => $newTarget->getDBkey(),
						'wl_notificationtimestamp' => '20151212010101',
					],
				],
			],
		];
		$mockDb->expects( $this->exactly( 2 ) )
			->method( 'replace' )
			->willReturnCallback( function ( $table, $uniqueKeys, $rows ) use ( &$replaceArgs ): void {
				[ $nextTable, $nextUniqueKeys, $nextRows ] = array_shift( $replaceArgs );
				$this->assertSame( $nextTable, $table );
				$this->assertSame( $nextUniqueKeys, $uniqueKeys );
				$this->assertSame( $nextRows, $rows );
			} );
		$mockDb
			->method( 'newSelectQueryBuilder' )
			->willReturn( new SelectQueryBuilder( $mockDb ) );

		$mockCache = $this->createNoOpMock( HashBagOStuff::class );

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

		$mockCache = $this->getMockCache( [ 'delete' ] );
		$mockCache->expects( $this->once() )
			->method( 'delete' )
			->with( '0:Some_Page:1' );
		$mockDb->method( 'select' )->willReturn( new FakeResultWrapper( [] ) );
		$mockDb->method( 'timestamp' )
			->willReturnArgument( 0 );

		$store = $this->newWatchedItemStore( [ 'db' => $mockDb, 'cache' => $mockCache ] );

		$store->addWatch(
			new UserIdentityValue( 1, 'MockUser' ),
			$testPageFactory( 100, 0, 'Some_Page', $this )
		);

		$this->assertSame(
			1,
			$this->statsFactory->getCounter( 'WatchedItemStore_uncache_total' )->getSampleCount()
		);
		$this->assertSame(
			0,
			$this->statsFactory->getCounter( 'WatchedItemStore_cache_total' )->getSampleCount()
		);
	}

	/**
	 * @dataProvider provideTestPageFactory
	 */
	public function testAddWatch_anonymousUser( $testPageFactory ) {
		$mockDb = $this->getMockDb();
		$mockDb->expects( $this->never() )
			->method( 'insert' );

		$mockCache = $this->createNoOpMock( HashBagOStuff::class );

		$store = $this->newWatchedItemStore( [ 'db' => $mockDb, 'cache' => $mockCache ] );

		$store->addWatch(
			new UserIdentityValue( 0, 'AnonUser' ),
			$testPageFactory( 100, 0, 'Some_Page', $this )
		);
		$this->assertSame(
			0,
			$this->statsFactory->getCounter( 'WatchedItemStore_uncache_total' )->getSampleCount()
		);
		$this->assertSame(
			0,
			$this->statsFactory->getCounter( 'WatchedItemStore_cache_total' )->getSampleCount()
		);
	}

	/**
	 * @dataProvider provideTestPageFactory
	 */
	public function testAddWatchBatchForUser_readOnlyDBReturnsFalse( $testPageFactory ) {
		$store = $this->newWatchedItemStore(
			[ 'readOnlyMode' => $this->getDummyReadOnlyMode( true ) ]
		);

		$this->assertFalse(
			$store->addWatchBatchForUser(
				new UserIdentityValue( 1, 'MockUser' ),
				[ $testPageFactory( 100, 0, 'Some_Page', $this ), $testPageFactory( 101, 1, 'Some_Page', $this ) ]
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

		$cacheKeys = [ '0:Some_Page:1', '1:Some_Page:1' ];
		$mockCache = $this->getMockCache( [ 'delete' ] );
		$mockCache->expects( $this->exactly( 2 ) )
			->method( 'delete' )
			->with( $this->callback( static function ( $key ) use ( &$cacheKeys ) {
				$nextKey = array_shift( $cacheKeys );
				return $nextKey === $key;
			} ) );

		$store = $this->newWatchedItemStore( [ 'db' => $mockDb, 'cache' => $mockCache ] );

		$mockUser = new UserIdentityValue( 1, 'MockUser' );

		$this->assertTrue(
			$store->addWatchBatchForUser(
				$mockUser,
				[ $testPageFactory( 100, 0, 'Some_Page', $this ), $testPageFactory( 101, 1, 'Some_Page', $this ) ]
			)
		);
		$this->assertSame(
			2,
			$this->statsFactory->getCounter( 'WatchedItemStore_uncache_total' )->getSampleCount()
		);
	}

	/**
	 * @dataProvider provideTestPageFactory
	 */
	public function testAddWatchBatchForUser_anonymousUsersAreSkipped( $testPageFactory ) {
		$mockDb = $this->getMockDb();
		$mockDb->expects( $this->never() )
			->method( 'insert' );

		$mockCache = $this->createNoOpMock( HashBagOStuff::class );

		$store = $this->newWatchedItemStore( [ 'db' => $mockDb, 'cache' => $mockCache ] );

		$this->assertFalse(
			$store->addWatchBatchForUser(
				new UserIdentityValue( 0, 'AnonUser' ),
				[ $testPageFactory( 100, 0, 'Other_Page', $this ) ]
			)
		);
		$this->assertSame(
			0,
			$this->statsFactory->getCounter( 'WatchedItemStore_uncache_total' )->getSampleCount()
		);
	}

	public function testAddWatchBatchReturnsTrue_whenGivenEmptyList() {
		$user = new UserIdentityValue( 1, 'MockUser' );
		$mockDb = $this->getMockDb();
		$mockDb->expects( $this->never() )
			->method( 'insert' );

		$mockCache = $this->createNoOpMock( HashBagOStuff::class );

		$store = $this->newWatchedItemStore( [ 'db' => $mockDb, 'cache' => $mockCache ] );

		$this->assertTrue(
			$store->addWatchBatchForUser( $user, [] )
		);
		$this->assertSame(
			0,
			$this->statsFactory->getCounter( 'WatchedItemStore_uncache_total' )->getSampleCount()
		);
	}

	/**
	 * @dataProvider provideTestPageFactory
	 */
	public function testLoadWatchedItem_existingItem( $testPageFactory ) {
		$mockDb = $this->getMockDb();
		$mockDb->expects( $this->once() )
			->method( 'timestamp' )
			->willReturn( '20200101000000' );
		$makeListSql = "wl_namespace = 0 AND wl_title = 'SomeDbKey'";
		$mockDb->expects( $this->exactly( 2 ) )
			->method( 'makeList' )
			->willReturnOnConsecutiveCalls( $makeListSql, $makeListSql );
		$selectArgs = [
			[
				[ 'watchlist', 'watchlist_expiry' => 'watchlist_expiry' ],
				[ 'wl_namespace', 'wl_title', 'wl_notificationtimestamp', 'we_expiry' ],
				[
					'wl_user' => 1,
					$makeListSql,
					'(we_expiry IS NULL OR we_expiry > \'20200101000000\')'
				],
				new FakeResultWrapper( [
					(object)[
						'wl_namespace' => 0,
						'wl_title' => 'SomeDbKey',
						'wl_notificationtimestamp' => '20151212010101',
						'we_expiry' => '20300101000000'
					]
				] ),
			],
		];
		$mockDb->expects( $this->once() )
			->method( 'select' )
			->willReturnCallback( function ( $table, $vars, $conds, $fname, $options, $join_conds ) use ( &$selectArgs ) {
				[ $nextTable, $nextVars, $nextConds, $returnValue ] = array_shift( $selectArgs );
				$this->assertSame( $nextTable, $table );
				$this->assertSame( $nextVars, $vars );
				$this->assertSame( $nextConds, $this->expandExpr( $conds ) );
				return $returnValue;
			} );

		$mockCache = $this->getMockCache( [ 'set' ] );
		$mockCache->expects( $this->once() )
			->method( 'set' )
			->with(
				'0:SomeDbKey:1'
			);

		$store = $this->newWatchedItemStore( [ 'db' => $mockDb, 'cache' => $mockCache ] );

		$watchedItem = $store->loadWatchedItem(
			new UserIdentityValue( 1, 'MockUser' ),
			$testPageFactory( 100, 0, 'SomeDbKey', $this )
		);
		$this->assertInstanceOf( WatchedItem::class, $watchedItem );
		$this->assertSame( 1, $watchedItem->getUserIdentity()->getId() );
		$this->assertEquals( 'SomeDbKey', $watchedItem->getTarget()->getDBkey() );
		$this->assertSame( '20300101000000', $watchedItem->getExpiry() );
		$this->assertSame( 0, $watchedItem->getTarget()->getNamespace() );

		$this->assertSame(
			1,
			$this->statsFactory->getCounter( 'WatchedItemStore_cache_total' )->getSampleCount()
		);
	}

	/**
	 * @dataProvider provideTestPageFactory
	 */
	public function testLoadWatchedItem_noItem( $testPageFactory ) {
		$mockDb = $this->getMockDb();
		$mockDb->expects( $this->once() )
			->method( 'timestamp' )
			->willReturn( '20200101000000' );
		$mockDb->expects( $this->once() )
			->method( 'select' )
			->willReturn( new FakeResultWrapper( [] ) );

		$mockCache = $this->createNoOpMock( HashBagOStuff::class );

		$store = $this->newWatchedItemStore( [ 'db' => $mockDb, 'cache' => $mockCache ] );

		$this->assertFalse(
			$store->loadWatchedItem(
				new UserIdentityValue( 1, 'MockUser' ),
				$testPageFactory( 100, 0, 'SomeDbKey', $this )
			)
		);
		$this->assertSame(
			0,
			$this->statsFactory->getCounter( 'WatchedItemStore_cache_total' )->getSampleCount()
		);
	}

	/**
	 * @dataProvider provideTestPageFactory
	 */
	public function testLoadWatchedItem_anonymousUser( $testPageFactory ) {
		$mockDb = $this->getMockDb();
		$mockDb->expects( $this->never() )
			->method( 'select' );

		$mockCache = $this->createNoOpMock( HashBagOStuff::class );

		$store = $this->newWatchedItemStore( [ 'db' => $mockDb, 'cache' => $mockCache ] );

		$this->assertFalse(
			$store->loadWatchedItem(
				new UserIdentityValue( 0, 'AnonUser' ),
				$testPageFactory( 100, 0, 'SomeDbKey', $this )
			)
		);
		$this->assertSame(
			0,
			$this->statsFactory->getCounter( 'WatchedItemStore_cache_total' )->getSampleCount()
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
		$deleteArgs = [
			[
				'watchlist',
				[ 'wl_id' => [ 1, 2 ] ]
			],
			[
				'watchlist_expiry',
				[ 'we_item' => [ 1, 2 ] ]
			]
		];
		$mockDb->expects( $this->exactly( 2 ) )
			->method( 'delete' )
			->willReturnCallback( function ( $table, $conds ) use ( &$deleteArgs ): void {
				[ $nextTable, $nextConds ] = array_shift( $deleteArgs );
				$this->assertSame( $nextTable, $table );
				$this->assertSame( $nextConds, $conds );
			} );
		$mockDb->expects( $this->exactly( 2 ) )
			->method( 'affectedRows' )
			->willReturn( 2 );

		$mockCache = $this->getMockCache( [ 'delete' ] );
		$mockCache->expects( $this->once() )
			->method( 'delete' )
			->with( '0:SomeDbKey:1' );

		$store = $this->newWatchedItemStore( [ 'db' => $mockDb, 'cache' => $mockCache ] );

		$this->assertTrue(
			$store->removeWatch(
				new UserIdentityValue( 1, 'MockUser' ),
				$testPageFactory( 100, 0, 'SomeDbKey', $this )
			)
		);
		$this->assertSame(
			1,
			$this->statsFactory->getCounter( 'WatchedItemStore_uncache_total' )->getSampleCount()
		);
	}

	/**
	 * @dataProvider provideTestPageFactory
	 */
	public function testRemoveWatch_noItem( $testPageFactory ) {
		$mockDb = $this->getMockDb();
		$mockDb->expects( $this->once() )
			->method( 'selectFieldValues' )
			->willReturn( [] );
		$mockDb->expects( $this->never() )
			->method( 'delete' );
		$mockDb->expects( $this->never() )
			->method( 'affectedRows' );

		$mockCache = $this->getMockCache( [ 'delete' ] );
		$mockCache->expects( $this->once() )
			->method( 'delete' )
			->with( '0:SomeDbKey:1' );

		$store = $this->newWatchedItemStore( [ 'db' => $mockDb, 'cache' => $mockCache ] );

		$this->assertFalse(
			$store->removeWatch(
				new UserIdentityValue( 1, 'MockUser' ),
				$testPageFactory( 100, 0, 'SomeDbKey', $this )
			)
		);
		$this->assertSame(
			1,
			$this->statsFactory->getCounter( 'WatchedItemStore_uncache_total' )->getSampleCount()
		);
	}

	/**
	 * @dataProvider provideTestPageFactory
	 */
	public function testRemoveWatch_anonymousUser( $testPageFactory ) {
		$mockDb = $this->getMockDb();
		$mockDb->expects( $this->never() )
			->method( 'delete' );

		$mockCache = $this->createNoOpMock( HashBagOStuff::class );

		$store = $this->newWatchedItemStore( [ 'db' => $mockDb, 'cache' => $mockCache ] );

		$this->assertFalse(
			$store->removeWatch(
				new UserIdentityValue( 0, 'AnonUser' ),
				$testPageFactory( 100, 0, 'SomeDbKey', $this )
			)
		);
		$this->assertSame(
			0,
			$this->statsFactory->getCounter( 'WatchedItemStore_uncache_total' )->getSampleCount()
		);
	}

	/**
	 * @dataProvider provideTestPageFactory
	 */
	public function testGetWatchedItem_existingItem( $testPageFactory ) {
		$mockDb = $this->getMockDb();
		$mockDb->expects( $this->once() )
			->method( 'timestamp' )
			->willReturn( '20200101000000' );
		$makeListSql = "wl_namespace = 0 AND wl_title = 'SomeDbKey'";
		$mockDb->expects( $this->exactly( 2 ) )
			->method( 'makeList' )
			->willReturnOnConsecutiveCalls( $makeListSql, $makeListSql );
		$selectArgs = [
			[
				[ 'watchlist', 'watchlist_expiry' => 'watchlist_expiry' ],
				[ 'wl_namespace', 'wl_title', 'wl_notificationtimestamp', 'we_expiry' ],
				[
					'wl_user' => 1,
					$makeListSql,
					'(we_expiry IS NULL OR we_expiry > \'20200101000000\')'
				],
				new FakeResultWrapper( [
					(object)[
						'wl_namespace' => 0,
						'wl_title' => 'SomeDbKey',
						'wl_notificationtimestamp' => '20151212010101',
						'we_expiry' => '20300101000000'
					]
				] ),
			],
		];
		$mockDb->expects( $this->once() )
			->method( 'select' )
			->willReturnCallback( function ( $table, $vars, $conds, $fname, $options, $join_conds ) use ( &$selectArgs ) {
				[ $nextTable, $nextVars, $nextConds, $returnValue ] = array_shift( $selectArgs );
				$this->assertSame( $nextTable, $table );
				$this->assertSame( $nextVars, $vars );
				$this->assertSame( $nextConds, $this->expandExpr( $conds ) );
				return $returnValue;
			} );

		$mockCache = $this->getMockCache( [ 'get', 'set' ] );
		$mockCache->expects( $this->once() )
			->method( 'get' )
			->with(
				'0:SomeDbKey:1'
			)
			->willReturn( null );
		$mockCache->expects( $this->once() )
			->method( 'set' )
			->with(
				'0:SomeDbKey:1'
			);

		$store = $this->newWatchedItemStore( [ 'db' => $mockDb, 'cache' => $mockCache ] );

		$watchedItem = $store->getWatchedItem(
			new UserIdentityValue( 1, 'MockUser' ),
			$testPageFactory( 100, 0, 'SomeDbKey', $this )
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
		$linkTarget = $testPageFactory( 100, 0, 'SomeDbKey', $this );
		$cachedItem = new WatchedItem( $mockUser, $linkTarget, '20151212010101' );

		$mockCache = $this->getMockCache( [ 'get' ] );
		$mockCache->expects( $this->once() )
			->method( 'get' )
			->with(
				'0:SomeDbKey:1'
			)
			->willReturn( $cachedItem );

		$store = $this->newWatchedItemStore( [ 'db' => $mockDb, 'cache' => $mockCache ] );

		$this->assertEquals(
			$cachedItem,
			$store->getWatchedItem(
				$mockUser,
				$linkTarget
			)
		);
		$this->assertSame(
			1,
			$this->statsFactory->getCounter( 'WatchedItemStore_getWatchedItem_accesses_total' )
				->getSampleCount()
		);
	}

	/**
	 * @dataProvider provideTestPageFactory
	 */
	public function testGetWatchedItem_noItem( $testPageFactory ) {
		$mockDb = $this->getMockDb();
		$mockDb->expects( $this->once() )
			->method( 'timestamp' )
			->willReturn( '20200101000000' );
		$makeListSql = "wl_namespace = 0 AND wl_title = 'SomeDbKey'";
		$mockDb->expects( $this->exactly( 2 ) )
			->method( 'makeList' )
			->willReturnOnConsecutiveCalls( $makeListSql, $makeListSql );
		$selectArgs = [
			[
				[ 'watchlist', 'watchlist_expiry' => 'watchlist_expiry' ],
				[ 'wl_namespace', 'wl_title', 'wl_notificationtimestamp', 'we_expiry' ],
				[
					'wl_user' => 1,
					$makeListSql,
					'(we_expiry IS NULL OR we_expiry > \'20200101000000\')'
				],
				new FakeResultWrapper( [] ),
			],
		];
		$mockDb->expects( $this->once() )
			->method( 'select' )
			->willReturnCallback( function ( $table, $vars, $conds, $fname, $options, $join_conds ) use ( &$selectArgs ) {
				[ $nextTable, $nextVars, $nextConds, $returnValue ] = array_shift( $selectArgs );
				$this->assertSame( $nextTable, $table );
				$this->assertSame( $nextVars, $vars );
				$this->assertSame( $nextConds, $this->expandExpr( $conds ) );
				return $returnValue;
			} );

		$mockCache = $this->getMockCache( [ 'get' ] );
		$mockCache->expects( $this->once() )
			->method( 'get' )
			->with( '0:SomeDbKey:1' )
			->willReturn( false );

		$store = $this->newWatchedItemStore( [ 'db' => $mockDb, 'cache' => $mockCache ] );

		$this->assertFalse(
			$store->getWatchedItem(
				new UserIdentityValue( 1, 'MockUser' ),
				$testPageFactory( 100, 0, 'SomeDbKey', $this )
			)
		);

		$this->assertSame(
			1,
			$this->statsFactory->getCounter( 'WatchedItemStore_getWatchedItem_accesses_total' )
				->getSampleCount()
		);
	}

	/**
	 * @dataProvider provideTestPageFactory
	 */
	public function testGetWatchedItem_anonymousUser( $testPageFactory ) {
		$mockDb = $this->getMockDb();
		$mockDb->expects( $this->never() )
			->method( 'selectRow' );

		$mockCache = $this->createNoOpMock( HashBagOStuff::class );

		$store = $this->newWatchedItemStore( [ 'db' => $mockDb, 'cache' => $mockCache ] );

		$this->assertFalse(
			$store->getWatchedItem(
				new UserIdentityValue( 0, 'AnonUser' ),
				$testPageFactory( 100, 0, 'SomeDbKey', $this )
			)
		);
		$this->assertSame(
			0,
			$this->statsFactory->getCounter( 'WatchedItemStore_getWatchedItem_accesses_total' )
				->getSampleCount()
		);
	}

	public function testGetWatchedItemsForUser() {
		$mockDb = $this->getMockDb();
		$mockDb->expects( $this->once() )
			->method( 'timestamp' )
			->willReturn( '20200101000000' );
		$selectArgs = [
			[
				[ 'watchlist', 'watchlist_expiry' => 'watchlist_expiry' ],
				[ 'wl_namespace', 'wl_title', 'wl_notificationtimestamp', 'we_expiry' ],
				[ 'wl_user' => 1, '(we_expiry IS NULL OR we_expiry > \'20200101000000\')' ],
				new FakeResultWrapper( [
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
				] ),
			],
		];
		$mockDb->expects( $this->once() )
			->method( 'select' )
			->willReturnCallback( function ( $table, $vars, $conds, $fname, $options, $join_conds ) use ( &$selectArgs ) {
				[ $nextTable, $nextVars, $nextConds, $returnValue ] = array_shift( $selectArgs );
				$this->assertSame( $nextTable, $table );
				$this->assertSame( $nextVars, $vars );
				$this->assertSame( $nextConds, $this->expandExpr( $conds ) );
				return $returnValue;
			} );

		$mockCache = $this->createNoOpMock( HashBagOStuff::class );

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
				PageReferenceValue::localReference( 0, 'Foo1' ),
				'20151212010101',
				'20300101000000'
			),
			$watchedItems[0]
		);
		$this->assertEquals(
			new WatchedItem( $user, PageReferenceValue::localReference( 1, 'Foo2' ), null ),
			$watchedItems[1]
		);
	}

	public static function provideDbTypes() {
		return [
			[ false ],
			[ true ],
		];
	}

	/**
	 * @dataProvider provideDbTypes
	 */
	public function testGetWatchedItemsForUser_optionsAndEmptyResult( bool $forWrite ) {
		$mockDb = $this->getMockDb();
		$mockCache = $this->createNoOpMock( HashBagOStuff::class );
		$mockLoadBalancer = $this->getMockLBFactory( $mockDb );
		$user = new UserIdentityValue( 1, 'MockUser' );

		$mockDb->expects( $this->atLeastOnce() )
			->method( 'timestamp' )
			->willReturn( '20200101000000' );
		$selectArgs = [
			[
				[ 'watchlist', 'watchlist_expiry' => 'watchlist_expiry' ],
				[ 'wl_namespace', 'wl_title', 'wl_notificationtimestamp', 'we_expiry' ],
				[ 'wl_user' => 1, '(we_expiry IS NULL OR we_expiry > \'20200101000000\')', ],
				[ 'ORDER BY' => [ 'wl_namespace', 'wl_title' ] ],
				new FakeResultWrapper( [] ),
			],
		];
		$mockDb->expects( $this->once() )
			->method( 'select' )
			->willReturnCallback( function ( $table, $vars, $conds, $fname, $options, ) use ( &$selectArgs ) {
				[ $nextTable, $nextVars, $nextConds, $nextOptions, $returnValue ] = array_shift( $selectArgs );
				$this->assertSame( $nextTable, $table );
				$this->assertSame( $nextVars, $vars );
				$this->assertSame( $nextConds, $this->expandExpr( $conds ) );
				$this->assertSame( $nextOptions, $options );
				return $returnValue;
			} );

		$store = $this->newWatchedItemStore(
			[ 'lbFactory' => $mockLoadBalancer, 'cache' => $mockCache ] );

		$watchedItems = $store->getWatchedItemsForUser(
			$user,
			[ 'forWrite' => $forWrite ]
		);
		$this->assertEquals( [], $watchedItems );
	}

	public function testGetWatchedItemsForUser_nonEmptyResult() {
		$mockDb = $this->getMockDb();
		$mockDb->expects( $this->atLeastOnce() )
			->method( 'timestamp' )
			->willReturn( '20200101000000' );
		$selectArgs = [
			[
				[ 'watchlist', 'watchlist_expiry' => 'watchlist_expiry' ],
				[
					'wl_namespace',
					'wl_title',
					'wl_notificationtimestamp',
					'we_expiry',
				],
				[ 'wl_user' => 1, '(we_expiry IS NULL OR we_expiry > \'20200101000000\')' ],
				[ 'ORDER BY' => [ 'wl_namespace', 'wl_title' ], 'LIMIT' => 4 ],
				new FakeResultWrapper( [
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
						'wl_notificationtimestamp' => '20151212010101',
						'we_expiry' => '20301201000000'
					],
					(object)[
						'wl_namespace' => 1,
						'wl_title' => 'Foo4',
						'wl_notificationtimestamp' => null,
						'we_expiry' => null,
					],
				] ),
			],
		];
		$mockDb->expects( $this->once() )
			->method( 'select' )
			->willReturnCallback( function ( $table, $vars, $conds, $fname, $options, ) use ( &$selectArgs ) {
				[ $nextTable, $nextVars, $nextConds, $nextOptions, $returnValue ] = array_shift( $selectArgs );
				$this->assertSame( $nextTable, $table );
				$this->assertSame( $nextVars, $vars );
				$this->assertSame( $nextConds, $this->expandExpr( $conds ) );
				$this->assertSame( $nextOptions, $options );
				return $returnValue;
			} );
		$mockCache = $this->createNoOpMock( HashBagOStuff::class );
		$store = $this->newWatchedItemStore( [ 'db' => $mockDb, 'cache' => $mockCache ] );
		$user = new UserIdentityValue( 1, 'MockUser' );
		$watchedItems = $store->getWatchedItemsForUser(
			$user,
			[ 'sort' => WatchedItemStore::SORT_ASC, 'limit' => 4 ]
		);
		$this->assertIsArray( $watchedItems );
		$this->assertCount( 4, $watchedItems );
		foreach ( $watchedItems as $watchedItem ) {
			$this->assertInstanceOf( WatchedItem::class, $watchedItem );
		}
		$this->assertEquals(
			new WatchedItem(
				$user,
				PageReferenceValue::localReference( 0, 'Foo1' ),
				'20151212010101',
				'20300101000000'
			),
			$watchedItems[0]
		);
		$this->assertEquals(
			new WatchedItem( $user, PageReferenceValue::localReference( 1, 'Foo4' ), null ),
			$watchedItems[3]
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
			->method( 'timestamp' )
			->willReturn( '20200101000000' );
		$makeListSql = "wl_namespace = 0 AND wl_title = 'SomeDbKey'";
		$mockDb->expects( $this->exactly( 2 ) )
			->method( 'makeList' )
			->willReturnOnConsecutiveCalls( $makeListSql, $makeListSql );
		$selectArgs = [
			[
				[ 'watchlist', 'watchlist_expiry' => 'watchlist_expiry' ],
				[ 'wl_namespace', 'wl_title', 'wl_notificationtimestamp', 'we_expiry' ],
				[
					'wl_user' => 1,
					$makeListSql,
					'(we_expiry IS NULL OR we_expiry > \'20200101000000\')'
				],
				new FakeResultWrapper( [
					(object)[
						'wl_namespace' => 0,
						'wl_title' => 'SomeDbKey',
						'wl_notificationtimestamp' => '20151212010101',
					]
				] ),
			],
		];
		$mockDb->expects( $this->once() )
			->method( 'select' )
			->willReturnCallback( function ( $table, $vars, $conds, $fname, $options, $join_conds ) use ( &$selectArgs ) {
				[ $nextTable, $nextVars, $nextConds, $returnValue ] = array_shift( $selectArgs );
				$this->assertSame( $nextTable, $table );
				$this->assertSame( $nextVars, $vars );
				$this->assertSame( $nextConds, $this->expandExpr( $conds ) );
				return $returnValue;
			} );

		$mockCache = $this->getMockCache( [ 'get', 'set' ] );
		$mockCache->expects( $this->once() )
			->method( 'get' )
			->with( '0:SomeDbKey:1' )
			->willReturn( false );
		$mockCache->expects( $this->once() )
			->method( 'set' )
			->with(
				'0:SomeDbKey:1'
			);

		$store = $this->newWatchedItemStore( [ 'db' => $mockDb, 'cache' => $mockCache ] );

		$this->assertTrue(
			$store->isWatched(
				new UserIdentityValue( 1, 'MockUser' ),
				$testPageFactory( 100, 0, 'SomeDbKey', $this )
			)
		);
	}

	/**
	 * @dataProvider provideTestPageFactory
	 */
	public function testIsWatchedItem_noItem( $testPageFactory ) {
		$mockDb = $this->getMockDb();
		$mockDb->expects( $this->once() )
			->method( 'timestamp' )
			->willReturn( '20200101000000' );
		$makeListSql = "wl_namespace = 0 AND wl_title = 'SomeDbKey'";
		$mockDb->expects( $this->exactly( 2 ) )
			->method( 'makeList' )
			->willReturnOnConsecutiveCalls( $makeListSql, $makeListSql );
		$selectArgs = [
			[
				[ 'watchlist', 'watchlist_expiry' => 'watchlist_expiry' ],
				[ 'wl_namespace', 'wl_title', 'wl_notificationtimestamp', 'we_expiry' ],
				[
					'wl_user' => 1,
					$makeListSql,
					'(we_expiry IS NULL OR we_expiry > \'20200101000000\')'
				],
				new FakeResultWrapper( [] ),
			],
		];
		$mockDb->expects( $this->once() )
			->method( 'select' )
			->willReturnCallback( function ( $table, $vars, $conds, $fname, $options, $join_conds ) use ( &$selectArgs ) {
				[ $nextTable, $nextVars, $nextConds, $returnValue ] = array_shift( $selectArgs );
				$this->assertSame( $nextTable, $table );
				$this->assertSame( $nextVars, $vars );
				$this->assertSame( $nextConds, $this->expandExpr( $conds ) );
				return $returnValue;
			} );

		$mockCache = $this->getMockCache( [ 'get' ] );
		$mockCache->expects( $this->once() )
			->method( 'get' )
			->with( '0:SomeDbKey:1' )
			->willReturn( false );

		$store = $this->newWatchedItemStore( [ 'db' => $mockDb, 'cache' => $mockCache ] );

		$this->assertFalse(
			$store->isWatched(
				new UserIdentityValue( 1, 'MockUser' ),
				$testPageFactory( 100, 0, 'SomeDbKey', $this )
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

		$mockCache = $this->createNoOpMock( HashBagOStuff::class );

		$store = $this->newWatchedItemStore( [ 'db' => $mockDb, 'cache' => $mockCache ] );

		$this->assertFalse(
			$store->isWatched(
				new UserIdentityValue( 0, 'AnonUser' ),
				$testPageFactory( 100, 0, 'SomeDbKey', $this )
			)
		);
	}

	/**
	 * @dataProvider provideTestPageFactory
	 */
	public function testGetNotificationTimestampsBatch( $testPageFactory ) {
		$targets = [
			$testPageFactory( 100, 0, 'SomeDbKey', $this ),
			$testPageFactory( 101, 1, 'AnotherDbKey', $this ),
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
			->willReturn( 'makeWhereFrom2d return value' );
		$mockDb->expects( $this->once() )
			->method( 'select' )
			->with(
				[ 'watchlist' ],
				[ 'wl_namespace', 'wl_title', 'wl_notificationtimestamp' ],
				[
					'makeWhereFrom2d return value',
					'wl_user' => 1
				],
				$this->isType( 'string' )
			)
			->willReturn( new FakeResultWrapper( $dbResult ) );

		$cacheKeys = [ '0:SomeDbKey:1', '1:AnotherDbKey:1' ];
		$mockCache = $this->getMockCache( [ 'get' ] );
		$mockCache->expects( $this->exactly( 2 ) )
			->method( 'get' )
			->willReturnCallback( function ( $key ) use ( &$cacheKeys ) {
				$nextKey = array_shift( $cacheKeys );
				$this->assertSame( $nextKey, $key );
				return null;
			} );

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
			$testPageFactory( 100, 0, 'OtherDbKey', $this ),
		];

		$mockDb = $this->getMockDb();

		$mockDb->expects( $this->once() )
			->method( 'makeWhereFrom2d' )
			->with(
				[ [ 'OtherDbKey' => 1 ] ],
				$this->isType( 'string' ),
				$this->isType( 'string' )
			)
			->willReturn( 'makeWhereFrom2d return value' );
		$mockDb->expects( $this->once() )
			->method( 'select' )
			->with(
				[ 'watchlist' ],
				[ 'wl_namespace', 'wl_title', 'wl_notificationtimestamp' ],
				[
					'makeWhereFrom2d return value',
					'wl_user' => 1
				],
				$this->isType( 'string' )
			)
			->willReturn( new FakeResultWrapper( [] ) );

		$mockCache = $this->getMockCache( [ 'get' ] );
		$mockCache->expects( $this->once() )
			->method( 'get' )
			->with( '0:OtherDbKey:1' )
			->willReturn( null );

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
			$testPageFactory( 100, 0, 'SomeDbKey', $this ),
			$testPageFactory( 101, 1, 'AnotherDbKey', $this ),
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
			->willReturn( 'makeWhereFrom2d return value' );
		$mockDb->expects( $this->once() )
			->method( 'select' )
			->with(
				[ 'watchlist' ],
				[ 'wl_namespace', 'wl_title', 'wl_notificationtimestamp' ],
				[
					'makeWhereFrom2d return value',
					'wl_user' => 1
				],
				$this->isType( 'string' )
			)
			->willReturn( new FakeResultWrapper( [
				(object)[ 'wl_namespace' => '1', 'wl_title' => 'AnotherDbKey', 'wl_notificationtimestamp' => null, ]
			] ) );

		$cacheKeys = [
			[ '0:SomeDbKey:1', $cachedItem ],
			[ '1:AnotherDbKey:1', null ]
		];
		$mockCache = $this->getMockCache( [ 'get' ] );
		$mockCache->expects( $this->exactly( 2 ) )
			->method( 'get' )
			->willReturnCallback( function ( $key ) use ( &$cacheKeys ) {
				[ $nextKey, $returnValue ] = array_shift( $cacheKeys );
				$this->assertSame( $nextKey, $key );
				return $returnValue;
			} );

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
			$testPageFactory( 100, 0, 'SomeDbKey', $this ),
			$testPageFactory( 101, 1, 'AnotherDbKey', $this ),
		];

		$user = new UserIdentityValue( 1, 'MockUser' );
		$cachedItems = [
			new WatchedItem( $user, $targets[0], '20151212010101' ),
			new WatchedItem( $user, $targets[1], null ),
		];
		$mockDb = $this->createNoOpMock( IDatabase::class );

		$cacheKeys = [
			[ '0:SomeDbKey:1', $cachedItems[0] ],
			[ '1:AnotherDbKey:1', $cachedItems[1] ],
		];
		$mockCache = $this->getMockCache( [ 'get' ] );
		$mockCache->expects( $this->exactly( 2 ) )
			->method( 'get' )
			->willReturnCallback( function ( $key ) use ( &$cacheKeys ) {
				[ $nextKey, $returnValue ] = array_shift( $cacheKeys );
				$this->assertSame( $nextKey, $key );
				return $returnValue;
			} );

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
			$testPageFactory( 100, 0, 'SomeDbKey', $this ),
			$testPageFactory( 101, 1, 'AnotherDbKey', $this ),
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

		$mockCache = $this->createNoOpMock( HashBagOStuff::class );

		$store = $this->newWatchedItemStore( [ 'db' => $mockDb, 'cache' => $mockCache ] );

		$this->assertFalse(
			$store->resetNotificationTimestamp(
				new UserIdentityValue( 0, 'AnonUser' ),
				$testPageFactory( 100, 0, 'SomeDbKey', $this )
			)
		);
	}

	/**
	 * @dataProvider provideTestPageFactory
	 */
	public function testResetNotificationTimestamp_noItem( $testPageFactory ) {
		$mockDb = $this->getMockDb();
		$mockDb->expects( $this->once() )
			->method( 'timestamp' )
			->willReturn( '20200101000000' );
		$makeListSql = "wl_namespace = 0 AND wl_title = 'SomeDbKey'";
		$mockDb->expects( $this->exactly( 2 ) )
			->method( 'makeList' )
			->willReturnOnConsecutiveCalls( $makeListSql, $makeListSql );
		$selectArgs = [
			[
				[ 'watchlist', 'watchlist_expiry' => 'watchlist_expiry' ],
				[ 'wl_namespace', 'wl_title', 'wl_notificationtimestamp', 'we_expiry' ],
				[
					'wl_user' => 1,
					$makeListSql,
					'(we_expiry IS NULL OR we_expiry > \'20200101000000\')'
				],
				new FakeResultWrapper( [] ),
			],
		];
		$mockDb->expects( $this->once() )
			->method( 'select' )
			->willReturnCallback( function ( $table, $vars, $conds, $fname, $options, $join_conds ) use ( &$selectArgs ) {
				[ $nextTable, $nextVars, $nextConds, $returnValue ] = array_shift( $selectArgs );
				$this->assertSame( $nextTable, $table );
				$this->assertSame( $nextVars, $vars );
				$this->assertSame( $nextConds, $this->expandExpr( $conds ) );
				return $returnValue;
			} );

		$mockCache = $this->getMockCache( [ 'get' ] );
		$mockCache->expects( $this->once() )->method( 'get' );

		$user = new UserIdentityValue( 1, 'MockUser' );

		$title = $testPageFactory( 100, 0, 'SomeDbKey', $this );

		$store = $this->newWatchedItemStore( [
			'db' => $mockDb,
			'cache' => $mockCache,
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
		$title = $testPageFactory( 100, 0, 'SomeDbKey', $this );

		$mockDb = $this->getMockDb();
		$mockDb->expects( $this->once() )
			->method( 'timestamp' )
			->willReturn( '20200101000000' );
		$makeListSql = "wl_namespace = 0 AND wl_title = 'SomeDbKey'";
		$mockDb->expects( $this->exactly( 2 ) )
			->method( 'makeList' )
			->willReturnOnConsecutiveCalls( $makeListSql, $makeListSql );
		$selectArgs = [
			[
				[ 'watchlist', 'watchlist_expiry' => 'watchlist_expiry' ],
				[ 'wl_namespace', 'wl_title', 'wl_notificationtimestamp', 'we_expiry' ],
				[
					'wl_user' => 1,
					$makeListSql,
					'(we_expiry IS NULL OR we_expiry > \'20200101000000\')'
				],
				new FakeResultWrapper( [
					(object)[
						'wl_namespace' => 0,
						'wl_title' => 'SomeDbKey',
						'wl_notificationtimestamp' => '20151212010101',
					]
				] ),
			],
		];
		$mockDb->expects( $this->once() )
			->method( 'select' )
			->willReturnCallback( function ( $table, $vars, $conds, $fname, $options, $join_conds ) use ( &$selectArgs ) {
				[ $nextTable, $nextVars, $nextConds, $returnValue ] = array_shift( $selectArgs );
				$this->assertSame( $nextTable, $table );
				$this->assertSame( $nextVars, $vars );
				$this->assertSame( $nextConds, $this->expandExpr( $conds ) );
				return $returnValue;
			} );

		$mockCache = $this->getMockCache( [ 'get', 'set', 'delete' ] );
		$mockCache->expects( $this->once() )->method( 'get' );
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
			'getRevisionByTitle' => static fn () => null,
			'getTimestampFromId' => static fn () => '00000000000000',
		] );

		$store = $this->newWatchedItemStore( [
			'db' => $mockDb,
			'queueGroup' => $mockQueueGroup,
			'cache' => $mockCache,
			'revisionLookup' => $mockRevisionLookup,
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
		$title = $testPageFactory( 100, 0, 'SomeDbKey', $this );

		$mockDb = $this->getMockDb();
		$mockDb->expects( $this->never() )
			->method( 'selectRow' );

		$mockCache = $this->getMockCache( [ 'delete' ] );
		$mockCache->expects( $this->once() )
			->method( 'delete' )
			->with( '0:SomeDbKey:1' );

		$mockQueueGroup = $this->getMockJobQueueGroup( false );

		// We don't care if these methods actually do anything here
		$mockRevisionLookup = $this->getMockRevisionLookup( [
			'getRevisionByTitle' => static fn () => null,
			'getTimestampFromId' => static fn () => '00000000000000',
		] );

		$store = $this->newWatchedItemStore( [
			'db' => $mockDb,
			'queueGroup' => $mockQueueGroup,
			'cache' => $mockCache,
			'revisionLookup' => $mockRevisionLookup,
		] );

		$mockQueueGroup->method( 'lazyPush' )
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
		$title = $testPageFactory( 100, 0, 'SomeTitle', $this );

		$mockDb = $this->getMockDb();
		$mockDb->expects( $this->never() )
			->method( 'selectRow' );

		$mockCache = $this->getMockCache( [ 'delete' ] );
		$mockCache->expects( $this->once() )
			->method( 'delete' )
			->with( '0:SomeTitle:1' );

		$mockQueueGroup = $this->getMockJobQueueGroup( false );

		$mockRevisionRecord = $this->createNoOpMock( RevisionRecord::class );

		$mockRevisionLookup = $this->getMockRevisionLookup( [
			'getTimestampFromId' => static fn () => '00000000000000',
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

		$store = $this->newWatchedItemStore( [
			'db' => $mockDb,
			'queueGroup' => $mockQueueGroup,
			'cache' => $mockCache,
			'revisionLookup' => $mockRevisionLookup,
		] );

		$mockQueueGroup->method( 'lazyPush' )
			->willReturnCallback(
				function ( ActivityUpdateJob $job ) use ( $title, $user ) {
					$this->verifyCallbackJob(
						$job,
						$title,
						$user->getId(),
						static fn ( $time ) => $time === null
					);
				}
			);

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
		$title = $testPageFactory( 100, 0, 'SomeDbKey', $this );

		$mockRevision = $this->createNoOpMock( RevisionRecord::class );
		$mockNextRevision = $this->createNoOpMock( RevisionRecord::class );

		$mockDb = $this->getMockDb();
		$mockDb->expects( $this->once() )
			->method( 'timestamp' )
			->willReturn( '20200101000000' );
		$makeListSql = "wl_namespace = 0 AND wl_title = 'SomeDbKey'";
		$mockDb->expects( $this->exactly( 2 ) )
			->method( 'makeList' )
			->willReturnOnConsecutiveCalls( $makeListSql, $makeListSql );
		$selectArgs = [
			[
				[ 'watchlist', 'watchlist_expiry' => 'watchlist_expiry' ],
				[ 'wl_namespace', 'wl_title', 'wl_notificationtimestamp', 'we_expiry' ],
				[
					'wl_user' => 1,
					$makeListSql,
					'(we_expiry IS NULL OR we_expiry > \'20200101000000\')'
				],
				new FakeResultWrapper( [
					(object)[
						'wl_namespace' => 0,
						'wl_title' => 'SomeDbKey',
						'wl_notificationtimestamp' => '20151212010101',
					]
				] ),
			],
		];
		$mockDb->expects( $this->once() )
			->method( 'select' )
			->willReturnCallback( function ( $table, $vars, $conds, $fname, $options, $join_conds ) use ( &$selectArgs ) {
				[ $nextTable, $nextVars, $nextConds, $returnValue ] = array_shift( $selectArgs );
				$this->assertSame( $nextTable, $table );
				$this->assertSame( $nextVars, $vars );
				$this->assertSame( $nextConds, $this->expandExpr( $conds ) );
				return $returnValue;
			} );

		$mockCache = $this->getMockCache( [ 'set', 'delete' ] );
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

		$store = $this->newWatchedItemStore( [
			'db' => $mockDb,
			'queueGroup' => $mockQueueGroup,
			'cache' => $mockCache,
			'revisionLookup' => $mockRevisionLookup,
		] );

		$mockQueueGroup->method( 'lazyPush' )
			->willReturnCallback(
				function ( ActivityUpdateJob $job ) use ( $title, $user ) {
					$this->verifyCallbackJob(
						$job,
						$title,
						$user->getId(),
						static fn ( $time ) => $time && $time > '20151212010101'
					);
				}
			);

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
		$title = $testPageFactory( 100, 0, 'SomeDbKey', $this );

		$mockDb = $this->getMockDb();
		$mockDb->expects( $this->once() )
			->method( 'timestamp' )
			->willReturn( '20200101000000' );
		$makeListSql = "wl_namespace = 0 AND wl_title = 'SomeDbKey'";
		$mockDb->expects( $this->exactly( 2 ) )
			->method( 'makeList' )
			->willReturnOnConsecutiveCalls( $makeListSql, $makeListSql );
		$selectArgs = [
			[
				[ 'watchlist', 'watchlist_expiry' => 'watchlist_expiry' ],
				[ 'wl_namespace', 'wl_title', 'wl_notificationtimestamp', 'we_expiry' ],
				[
					'wl_user' => 1,
					$makeListSql,
					'(we_expiry IS NULL OR we_expiry > \'20200101000000\')'
				],
				new FakeResultWrapper( [] ),
			],
		];
		$mockDb->expects( $this->once() )
			->method( 'select' )
			->willReturnCallback( function ( $table, $vars, $conds, $fname, $options, $join_conds ) use ( &$selectArgs ) {
				[ $nextTable, $nextVars, $nextConds, $returnValue ] = array_shift( $selectArgs );
				$this->assertSame( $nextTable, $table );
				$this->assertSame( $nextVars, $vars );
				$this->assertSame( $nextConds, $this->expandExpr( $conds ) );
				return $returnValue;
			} );

		$mockCache = $this->getMockCache( [ 'delete' ] );
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

		$store = $this->newWatchedItemStore( [
			'db' => $mockDb,
			'queueGroup' => $mockQueueGroup,
			'cache' => $mockCache,
			'revisionLookup' => $mockRevisionLookup,
		] );

		$mockQueueGroup->method( 'lazyPush' )
			->willReturnCallback(
				function ( ActivityUpdateJob $job ) use ( $title, $user ) {
					$this->verifyCallbackJob(
						$job,
						$title,
						$user->getId(),
						static fn ( $time ) => $time === null
					);
				}
			);

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
		$title = $testPageFactory( 100, 0, 'SomeDbKey', $this );

		$mockDb = $this->getMockDb();
		$mockDb->expects( $this->once() )
			->method( 'timestamp' )
			->willReturn( '20200101000000' );
		$makeListSql = "wl_namespace = 0 AND wl_title = 'SomeDbKey'";
		$mockDb->expects( $this->exactly( 2 ) )
			->method( 'makeList' )
			->willReturnOnConsecutiveCalls( $makeListSql, $makeListSql );
		$selectArgs = [
			[
				[ 'watchlist', 'watchlist_expiry' => 'watchlist_expiry' ],
				[ 'wl_namespace', 'wl_title', 'wl_notificationtimestamp', 'we_expiry' ],
				[
					'wl_user' => 1,
					$makeListSql,
					'(we_expiry IS NULL OR we_expiry > \'20200101000000\')'
				],
				new FakeResultWrapper( [
					(object)[
						'wl_namespace' => 0,
						'wl_title' => 'SomeDbKey',
						'wl_notificationtimestamp' => '30151212010101',
					]
				] ),
			],
		];
		$mockDb->expects( $this->once() )
			->method( 'select' )
			->willReturnCallback( function ( $table, $vars, $conds, $fname, $options, $join_conds ) use ( &$selectArgs ) {
				[ $nextTable, $nextVars, $nextConds, $returnValue ] = array_shift( $selectArgs );
				$this->assertSame( $nextTable, $table );
				$this->assertSame( $nextVars, $vars );
				$this->assertSame( $nextConds, $this->expandExpr( $conds ) );
				return $returnValue;
			} );

		$mockCache = $this->getMockCache( [ 'set', 'delete' ] );
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

		$store = $this->newWatchedItemStore( [
			'db' => $mockDb,
			'queueGroup' => $mockQueueGroup,
			'cache' => $mockCache,
			'revisionLookup' => $mockRevisionLookup,
		] );

		$mockQueueGroup->method( 'lazyPush' )
			->willReturnCallback(
				function ( ActivityUpdateJob $job ) use ( $title, $user ) {
					$this->verifyCallbackJob(
						$job,
						$title,
						$user->getId(),
						static fn ( $time ) => $time === '30151212010101'
					);
				}
			);

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
		$title = $testPageFactory( 100, 0, 'SomeDbKey', $this );

		$mockDb = $this->getMockDb();
		$mockDb->expects( $this->once() )
			->method( 'timestamp' )
			->willReturn( '20200101000000' );
		$makeListSql = "wl_namespace = 0 AND wl_title = 'SomeDbKey'";
		$mockDb->expects( $this->exactly( 2 ) )
			->method( 'makeList' )
			->willReturnOnConsecutiveCalls( $makeListSql, $makeListSql );
		$selectArgs = [
			[
				[ 'watchlist', 'watchlist_expiry' => 'watchlist_expiry' ],
				[ 'wl_namespace', 'wl_title', 'wl_notificationtimestamp', 'we_expiry' ],
				[
					'wl_user' => 1,
					$makeListSql,
					'(we_expiry IS NULL OR we_expiry > \'20200101000000\')',
				],
				new FakeResultWrapper( [
					(object)[
						'wl_namespace' => 0,
						'wl_title' => 'SomeDbKey',
						'wl_notificationtimestamp' => '30151212010101',
					]
				] ),
			],
		];
		$mockDb->expects( $this->once() )
			->method( 'select' )
			->willReturnCallback( function ( $table, $vars, $conds, $fname, $options, $join_conds ) use ( &$selectArgs ) {
				[ $nextTable, $nextVars, $nextConds, $returnValue ] = array_shift( $selectArgs );
				$this->assertSame( $nextTable, $table );
				$this->assertSame( $nextVars, $vars );
				$this->assertSame( $nextConds, $this->expandExpr( $conds ) );
				return $returnValue;
			} );

		$mockCache = $this->getMockCache( [ 'get', 'set', 'delete' ] );
		$mockCache->expects( $this->once() )->method( 'get' );
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

		$store = $this->newWatchedItemStore( [
			'db' => $mockDb,
			'queueGroup' => $mockQueueGroup,
			'cache' => $mockCache,
			'revisionLookup' => $mockRevisionLookup,
		] );

		$mockQueueGroup->method( 'lazyPush' )
			->willReturnCallback(
				function ( ActivityUpdateJob $job ) use ( $title, $user ) {
					$this->verifyCallbackJob(
						$job,
						$title,
						$user->getId(),
						static fn ( $time ) => $time === false
					);
				}
			);

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
		$targets = [ $testPageFactory( 100, 0, 'Foo', $this ), $testPageFactory( 101, 0, 'Bar', $this ) ];

		$mockDb = $this->getMockDb();
		$mockDb->expects( $this->once() )
			->method( 'selectFieldValues' )
			->with(
				[ 'watchlist' ],
				'wl_id',
				[ 'wl_user' => 1, 'wl_namespace' => 0, 'wl_title' => [ 'Foo', 'Bar' ] ]
			)
			->willReturn( [ '2', '3' ] );
		$mockDb->expects( $this->once() )
			->method( 'update' )
			->with(
				'watchlist',
				[ 'wl_notificationtimestamp' => 'TS' . $timestamp . 'TS' ],
				[ 'wl_id' => [ 2, 3 ] ]
			)
			->willReturn( true );
		$mockDb->expects( $this->once() )
			->method( 'timestamp' )
			->willReturnCallback( static fn ( $ts ) => "TS{$ts}TS" );
		$mockDb->expects( $this->once() )
			->method( 'affectedRows' )
			->willReturn( 2 );

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
		$mockDb->method( 'timestamp' )
			->willReturn( '20200101000000' );
		$selectArgs = [
			[
				[ 'watchlist', 'watchlist_expiry' => 'watchlist_expiry' ],
				[ 'wl_id', 'wl_user' ],
				[
					'wl_user != 1',
					'wl_namespace' => 0,
					'wl_title' => 'SomeDbKey',
					'wl_notificationtimestamp' => null,
					'(we_expiry IS NULL OR we_expiry > \'20200101000000\')',
				],
				new FakeResultWrapper( [
					(object)[
						'wl_id' => '4',
						'wl_user' => '2',
					],
					(object)[
						'wl_id' => '5',
						'wl_user' => '3',
					],
				] ),
			],
		];
		$mockDb->expects( $this->once() )
			->method( 'select' )
			->willReturnCallback( function ( $table, $vars, $conds, $fname, $options, $join_conds ) use ( &$selectArgs ) {
				[ $nextTable, $nextVars, $nextConds, $returnValue ] = array_shift( $selectArgs );
				$this->assertSame( $nextTable, $table );
				$this->assertSame( $nextVars, $vars );
				$this->assertSame( $nextConds, $this->expandExpr( $conds ) );
				return $returnValue;
			} );
		$mockDb->expects( $this->once() )
			->method( 'update' )
			->with(
				'watchlist',
				[ 'wl_notificationtimestamp' => '20200101000000' ],
				[
					'wl_id' => [ 4, 5 ],
				]
			);

		$mockCache = $this->createNoOpMock( HashBagOStuff::class );

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
				$testPageFactory( 100, 0, 'SomeDbKey', $this ),
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
			->method( 'timestamp' )
			->willReturn( '20200101000000' );
		$selectArgs = [
			[
				[ 'watchlist', 'watchlist_expiry' => 'watchlist_expiry' ],
				[ 'wl_id', 'wl_user' ],
				[
					'wl_user != 1',
					'wl_namespace' => 0,
					'wl_title' => 'SomeDbKey',
					'wl_notificationtimestamp' => null,
					'(we_expiry IS NULL OR we_expiry > \'20200101000000\')',
				],
				[],
				[ 'watchlist_expiry' => [ 'LEFT JOIN', 'wl_id = we_item' ] ],
				new FakeResultWrapper( [] ),
			],
		];
		$mockDb->expects( $this->once() )
			->method( 'select' )
			->willReturnCallback( function ( $table, $vars, $conds, $fname, $options, $join_conds ) use ( &$selectArgs ) {
				[ $nextTable, $nextVars, $nextConds, $nextOptions, $nextJoinConds, $returnValue ] = array_shift( $selectArgs );
				$this->assertSame( $nextTable, $table );
				$this->assertSame( $nextVars, $vars );
				$this->assertSame( $nextConds, $this->expandExpr( $conds ) );
				$this->assertSame( $nextOptions, $options );
				$this->assertSame( $nextJoinConds, $join_conds );
				return $returnValue;
			} );
		$mockDb->expects( $this->never() )
			->method( 'update' );

		$mockCache = $this->createNoOpMock( HashBagOStuff::class );

		$store = $this->newWatchedItemStore( [ 'db' => $mockDb, 'cache' => $mockCache ] );

		$watchers = $store->updateNotificationTimestamp(
			new UserIdentityValue( 1, 'MockUser' ),
			$testPageFactory( 100, 0, 'SomeDbKey', $this ),
			'20151212010101'
		);
		$this->assertSame( [], $watchers );
	}

	/**
	 * @dataProvider provideTestPageFactory
	 */
	public function testUpdateNotificationTimestamp_clearsCachedItems( $testPageFactory ) {
		$user = new UserIdentityValue( 1, 'MockUser' );
		$titleValue = $testPageFactory( 100, 0, 'SomeDbKey', $this );

		$mockDb = $this->getMockDb();
		$mockDb->method( 'timestamp' )
			->willReturnArgument( 0 );
		$mockDb->expects( $this->exactly( 2 ) )
			->method( 'select' )
			->willReturnOnConsecutiveCalls( new FakeResultWrapper( [
				(object)[
					'wl_namespace' => 0,
					'wl_title' => 'SomeDbKey',
					'wl_notificationtimestamp' => '20151212010101'
				]
			] ),
			new FakeResultWrapper( [
				(object)[
					'wl_id' => '4',
					'wl_user' => '2',
				],
				(object)[
					'wl_id' => '5',
					'wl_user' => '3',
				],
			] ) );
		$mockDb->expects( $this->once() )
			->method( 'update' );

		$mockCache = $this->getMockCache( [ 'set', 'get', 'delete' ] );
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

		$mockDb->expects( $this->once() )
			->method( 'timestamp' )
			->willReturn( '20200101000000' );

		$selectFieldValuesArgs = [
			// Select expired items.
			[
				[ 'watchlist_expiry' ],
				'we_item',
				[ 'we_expiry <= \'20200101000000\'' ],
				[ 'LIMIT' => 2 ],
				[],
				[ 1, 2 ],
			],
			// Select orphaned items.
			[
				[ 'watchlist_expiry', 'watchlist' => 'watchlist' ],
				'we_item',
				[ 'wl_id' => null, 'we_expiry' => null ],
				[],
				[ 'watchlist' => [ 'LEFT JOIN', 'wl_id = we_item' ] ],
				[ 3 ]
			]
		];
		// Select watchlist IDs.
		$mockDb->expects( $this->exactly( 2 ) )
			->method( 'selectFieldValues' )
			->willReturnCallback( function ( $table, $vars, $conds, $fname, $options, $join_conds ) use ( &$selectFieldValuesArgs ) {
				[ $nextTable, $nextVars, $nextConds, $nextOptions, $nextJoinConds, $returnValue ] = array_shift( $selectFieldValuesArgs );
				$this->assertSame( $nextTable, $table );
				$this->assertSame( $nextVars, $vars );
				$this->assertSame( $nextConds, $this->expandExpr( $conds ) );
				$this->assertSame( $nextOptions, $options );
				$this->assertSame( $nextJoinConds, $join_conds );
				return $returnValue;
			} );

		// Return whatever is passed to makeList, to be tested below.
		$mockDb->expects( $this->once() )
			->method( 'makeList' )
			->willReturnArgument( 0 );

		// Delete from watchlist and watchlist_expiry.
		$deleteArgs = [
			// Delete expired items from watchlist
			[
				'watchlist',
				[ 'wl_id' => [ 1, 2 ] ],
			],
			// Delete expired items from watchlist_expiry
			[
				'watchlist_expiry',
				[ 'we_item' => [ 1, 2 ] ],
			],
			// Delete orphaned items
			[
				'watchlist_expiry',
				[ 'we_item' => [ 3 ] ],
			]
		];
		$mockDb->expects( $this->exactly( 3 ) )
			->method( 'delete' )
			->willReturnCallback( function ( $table, $conds ) use ( &$deleteArgs ): void {
				[ $nextTable, $nextConds ] = array_shift( $deleteArgs );
				$this->assertSame( $nextTable, $table );
				$this->assertSame( $nextConds, $conds );
			} );

		$mockCache = $this->createNoOpMock( HashBagOStuff::class );
		$store = $this->newWatchedItemStore( [ 'db' => $mockDb, 'cache' => $mockCache ] );
		$store->removeExpired( 2, true );
	}

	public static function provideGetLatestNotificationTimestamp() {
		$emptyMap = new MapCacheLRU( 300 );
		$oldMap = new MapCacheLRU( 300 );
		$oldMap->set( '0:Title', '20090101000000' );
		$newMap = new MapCacheLRU( 300 );
		$newMap->set( '0:Title', '20110101000000' );
		$wrongKeyMap = new MapCacheLRU( 300 );
		$wrongKeyMap->set( '0:Wrong', '20110101000000' );
		// Arrays are used for stash values after T282105. We test forwards and
		// backwards compatibility. The MapCacheLRU cases can be removed after
		// deployment of T282105 has finished.
		return [
			'empty cache' => [
				null,
				true
			],
			'empty MapCacheLRU' => [
				$emptyMap,
				true
			],
			'empty array' => [
				$emptyMap->toArray(),
				true
			],
			'old MapCacheLRU' => [
				$oldMap,
				true,
			],
			'old array' => [
				$oldMap->toArray(),
				true
			],
			'new MapCacheLRU' => [
				$newMap,
				false
			],
			'new array' => [
				$newMap->toArray(),
				false
			],
			'wrong key MapCacheLRU' => [
				$wrongKeyMap,
				true
			],
			'wrong key array' => [
				$wrongKeyMap->toArray(),
				true
			],
		];
	}

	/** @dataProvider provideGetLatestNotificationTimestamp */
	public function testGetLatestNotificationTimestamp( $cacheValue, $expectNonNull ) {
		$user = new UserIdentityValue( 1, 'User' );
		$title = PageReferenceValue::localReference( 0, 'Title' );
		$stash = new HashBagOStuff;
		$stash->set(
			$stash->makeGlobalKey(
				'watchlist-recent-updates',
				'phpunitdb',
				$user->getId()
			),
			$cacheValue
		);
		$store = $this->newWatchedItemStore( [ 'stash' => $stash ] );
		$result = $store->getLatestNotificationTimestamp(
			'20100101000000', $user, $title );
		$this->assertSame( $expectNonNull, $result !== null );
	}

	/**
	 * @dataProvider provideTestPageFactory
	 */
	public function testResetNotificationTimestamp_stashItemTypeCheck( $testPageFactory ) {
		$user = new UserIdentityValue( 1, 'MockUser' );
		$oldid = 22;
		$title = $testPageFactory( 100, 0, 'SomeDbKey', $this );
		$stash = new HashBagOStuff;
		$mockRevision = $this->createNoOpMock( RevisionRecord::class );
		$mockNextRevision = $this->createNoOpMock( RevisionRecord::class );
		$mockRevisionLookup = $this->getMockRevisionLookup(
			[
				'getTimestampFromId' => static fn () => '00000000000000',
				'getRevisionByTitle' => static fn () => null,
				'getRevisionById' => static fn () => $mockRevision,
				'getNextRevision' => static fn () => $mockNextRevision,
			]
		);
		$mockDb = $this->getMockDb();
		$mockDb->method( 'timestamp' )
			->willReturnArgument( 0 );
		$mockDb->method( 'select' )->willReturn( new FakeResultWrapper( [] ) );
		$store = $this->newWatchedItemStore( [
			'db' => $mockDb,
			'cache' => new HashBagOStuff(),
			'revisionLookup' => $mockRevisionLookup,
			'stash' => $stash,
			'queueGroup' => $this->getMockJobQueueGroup( false ),
		] );
		$store->resetNotificationTimestamp( $user,
			$title,
			'force',
			$oldid );
		$this->assertIsArray( $stash->get( 'global:watchlist-recent-updates:phpunitdb:1' ) );
	}
}
