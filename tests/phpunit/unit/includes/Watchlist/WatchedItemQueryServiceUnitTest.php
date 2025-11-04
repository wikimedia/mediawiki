<?php

use MediaWiki\Page\PageReferenceValue;
use MediaWiki\User\User;
use MediaWiki\User\UserIdentityValue;
use MediaWiki\Watchlist\WatchedItem;
use MediaWiki\Watchlist\WatchedItemQueryService;
use MediaWiki\Watchlist\WatchedItemStore;
use PHPUnit\Framework\MockObject\MockObject;
use Wikimedia\Rdbms\Database\DbQuoter;
use Wikimedia\Rdbms\Expression;
use Wikimedia\Rdbms\FakeResultWrapper;
use Wikimedia\Rdbms\IConnectionProvider;
use Wikimedia\Rdbms\IDatabase;
use Wikimedia\Rdbms\IExpression;
use Wikimedia\Rdbms\IReadableDatabase;
use Wikimedia\Rdbms\SelectQueryBuilder;

/**
 * @covers \MediaWiki\Watchlist\WatchedItemQueryService
 */
class WatchedItemQueryServiceUnitTest extends MediaWikiUnitTestCase {

	public function setUp(): void {
		$this->hideDeprecated( WatchedItemQueryService::class .
			'::getWatchedItemsWithRecentChangeInfo' );
	}

	/**
	 * @param IDatabase $mockDb
	 * @return WatchedItemQueryService
	 */
	private function newService( IDatabase $mockDb ) {
		return new WatchedItemQueryService(
			$this->getMockDbProvider( $mockDb ),
			$this->getMockWatchedItemStore(),
			false
		);
	}

	/**
	 * @return MockObject&IDatabase
	 */
	private function getMockDb() {
		$mock = $this->createMock( IDatabase::class );

		$mock->method( 'makeList' )
			->with(
				$this->isType( 'array' ),
				$this->isType( 'int' )
			)
			->willReturnCallback( function ( $a, $conj ) {
				$sqlConj = $conj === LIST_AND ? ' AND ' : ' OR ';
				$conds = [];
				foreach ( $a as $k => $v ) {
					if ( $v instanceof IExpression ) {
						$v = $v->toSql( $this->createMock( DbQuoter::class ) );
					}
					if ( is_int( $k ) ) {
						$conds[] = "($v)";
					} elseif ( is_array( $v ) ) {
						$conds[] = "($k IN ('" . implode( "','", $v ) . "'))";
					} else {
						$conds[] = "($k = '$v')";
					}
				}
				return implode( $sqlConj, $conds );
			} );

		$mock->method( 'expr' )
			->with(
				$this->isType( 'string' ),
				$this->isType( 'string' ),
				$this->isType( 'string' )
			)
			->willReturnCallback( function ( string $field, string $op, string $value ) {
				$mock = $this->createMock( Expression::class );
				$mock->method( 'toSql' )->willReturn( "$field $op '$value'" );
				return $mock;
			} );

		$mock->method( 'buildComparison' )
			->with(
				$this->isType( 'string' ),
				$this->isType( 'array' )
			)
			->willReturnCallback( static function ( string $op, array $conds ) {
				$sql = '';
				foreach ( array_reverse( $conds ) as $field => $value ) {
					if ( $sql === '' ) {
						$sql = "$field $op '$value'";
						$op = rtrim( $op, '=' );
					} else {
						$sql = "$field $op '$value' OR ($field = '$value' AND ($sql))";
					}
				}
				return $sql;
			} );

		$mock->method( 'timestamp' )
			->willReturnArgument( 0 );

		$mock->method( 'newSelectQueryBuilder' )->willReturnCallback( static function () use ( $mock ) {
			return new SelectQueryBuilder( $mock );
		} );

		return $mock;
	}

	private function getMockDbProvider( IReadableDatabase $mockDb ): IConnectionProvider {
		$mock = $this->createMock( IConnectionProvider::class );
		$mock->method( 'getReplicaDatabase' )
			->willReturn( $mockDb );
		return $mock;
	}

	/**
	 * @return MockObject|WatchedItemStore
	 */
	private function getMockWatchedItemStore() {
		$mock = $this->createMock( WatchedItemStore::class );
		$mock->method( 'getLatestNotificationTimestamp' )
			->willReturnArgument( 0 );
		return $mock;
	}

	/**
	 * @param int $id
	 * @param bool $canPatrol result for User::useRCPatrol() and User::useNPPatrol()
	 * @param string|null $notAllowedAction for permission checks, the user has all other rights
	 * @param string[] $extraMethods Extra methods that are expected might be called
	 * @return MockObject|User
	 */
	private function getMockUserWithId(
		$id,
		bool $canPatrol = true,
		$notAllowedAction = null,
		array $extraMethods = []
	) {
		$methods = array_merge(
			[ 'isRegistered', 'getId', 'useRCPatrol', 'useNPPatrol', 'isAllowed', 'isAllowedAny' ],
			$extraMethods
		);
		$mock = $this->createNoOpMock(
			User::class,
			$methods
		);
		$mock->method( 'isRegistered' )->willReturn( true );
		$mock->method( 'getId' )->willReturn( $id );
		$mock->method( 'useRCPatrol' )->willReturn( $canPatrol );
		$mock->method( 'useNPPatrol' )->willReturn( $canPatrol );
		$mock->method( 'isAllowed' )->willReturnCallback(
			static function ( $permission ) use ( $notAllowedAction ) {
				return $permission !== $notAllowedAction;
			}
		);
		$mock->method( 'isAllowedAny' )->willReturnCallback(
			static function ( ...$permissions ) use ( $notAllowedAction ) {
				return !in_array( $notAllowedAction, $permissions );
			}
		);
		return $mock;
	}

	private static function makeTitle( int $ns, string $dbKey ): PageReferenceValue {
		return PageReferenceValue::localReference( $ns, $dbKey );
	}

	public function testGetWatchedItemsForUser() {
		$mockDb = $this->getMockDb();
		$mockDb->expects( $this->once() )
			->method( 'select' )
			->with(
				[ 'watchlist' ],
				[ 'wl_namespace', 'wl_title', 'wl_notificationtimestamp' ],
				[ 'wl_user' => 1 ]
			)
			->willReturn( new FakeResultWrapper( [
				(object)[
					'wl_namespace' => 0,
					'wl_title' => 'Foo1',
					'wl_notificationtimestamp' => '20151212010101',
				],
				(object)[
					'wl_namespace' => 1,
					'wl_title' => 'Foo2',
					'wl_notificationtimestamp' => null,
				],
			] ) );

		$queryService = $this->newService( $mockDb );
		$user = $this->getMockUserWithId( 1 );

		$items = $queryService->getWatchedItemsForUser( $user );

		$this->assertIsArray( $items );
		$this->assertCount( 2, $items );
		$this->assertContainsOnlyInstancesOf( WatchedItem::class, $items );
		$this->assertEquals(
			new WatchedItem( $user, self::makeTitle( 0, 'Foo1' ), '20151212010101' ),
			$items[0]
		);
		$this->assertEquals(
			new WatchedItem( $user, self::makeTitle( 1, 'Foo2' ), null ),
			$items[1]
		);
	}

	public static function provideGetWatchedItemsForUserOptions() {
		return [
			[
				[ 'namespaceIds' => [ 0, 1 ], ],
				[ 'wl_namespace' => [ 0, 1 ], ],
				[]
			],
			[
				[ 'sort' => WatchedItemQueryService::SORT_ASC, ],
				[],
				[ 'ORDER BY' => [ 'wl_namespace ASC', 'wl_title ASC' ] ]
			],
			[
				[
					'namespaceIds' => [ 0 ],
					'sort' => WatchedItemQueryService::SORT_ASC,
				],
				[ 'wl_namespace' => [ 0 ], ],
				[ 'ORDER BY' => [ 'wl_title ASC' ] ]
			],
			[
				[ 'limit' => 10 ],
				[],
				[ 'LIMIT' => 10 ]
			],
			[
				[
					'namespaceIds' => [ 0, "1; DROP TABLE watchlist;\n--" ],
					'limit' => "10; DROP TABLE watchlist;\n--",
				],
				[ 'wl_namespace' => [ 0, 1 ], ],
				[ 'LIMIT' => 10 ]
			],
			[
				[ 'filter' => WatchedItemQueryService::FILTER_CHANGED ],
				[ 'wl_notificationtimestamp IS NOT NULL' ],
				[]
			],
			[
				[ 'filter' => WatchedItemQueryService::FILTER_NOT_CHANGED ],
				[ 'wl_notificationtimestamp IS NULL' ],
				[]
			],
			[
				[ 'sort' => WatchedItemQueryService::SORT_DESC, ],
				[],
				[ 'ORDER BY' => [ 'wl_namespace DESC', 'wl_title DESC' ] ]
			],
			[
				[
					'namespaceIds' => [ 0 ],
					'sort' => WatchedItemQueryService::SORT_DESC,
				],
				[ 'wl_namespace' => [ 0 ], ],
				[ 'ORDER BY' => [ 'wl_title DESC' ] ]
			],
		];
	}

	/**
	 * @dataProvider provideGetWatchedItemsForUserOptions
	 */
	public function testGetWatchedItemsForUser_optionsAndEmptyResult(
		array $options,
		array $expectedConds,
		array $expectedDbOptions
	) {
		$mockDb = $this->getMockDb();
		$user = $this->getMockUserWithId( 1 );

		$expectedConds = array_merge( [ 'wl_user' => 1 ], $expectedConds );
		$mockDb->expects( $this->once() )
			->method( 'select' )
			->with(
				[ 'watchlist' ],
				[ 'wl_namespace', 'wl_title', 'wl_notificationtimestamp' ],
				$expectedConds,
				$this->isType( 'string' ),
				$expectedDbOptions
			)
			->willReturn( new FakeResultWrapper( [] ) );

		$queryService = $this->newService( $mockDb );

		$items = $queryService->getWatchedItemsForUser( $user, $options );
		$this->assertSame( [], $items );
	}

	public static function provideGetWatchedItemsForUser_fromUntilStartFromOptions() {
		return [
			[
				[
					'from' => self::makeTitle( 0, 'SomeDbKey' ),
					'sort' => WatchedItemQueryService::SORT_ASC
				],
				[ "wl_namespace > '0' OR (wl_namespace = '0' AND (wl_title >= 'SomeDbKey'))", ],
				[ 'ORDER BY' => [ 'wl_namespace ASC', 'wl_title ASC' ] ]
			],
			[
				[
					'from' => self::makeTitle( 0, 'SomeDbKey' ),
					'sort' => WatchedItemQueryService::SORT_DESC,
				],
				[ "wl_namespace < '0' OR (wl_namespace = '0' AND (wl_title <= 'SomeDbKey'))", ],
				[ 'ORDER BY' => [ 'wl_namespace DESC', 'wl_title DESC' ] ]
			],
			[
				[
					'until' => self::makeTitle( 0, 'SomeDbKey' ),
					'sort' => WatchedItemQueryService::SORT_ASC
				],
				[ "wl_namespace < '0' OR (wl_namespace = '0' AND (wl_title <= 'SomeDbKey'))", ],
				[ 'ORDER BY' => [ 'wl_namespace ASC', 'wl_title ASC' ] ]
			],
			[
				[
					'until' => self::makeTitle( 0, 'SomeDbKey' ),
					'sort' => WatchedItemQueryService::SORT_DESC
				],
				[ "wl_namespace > '0' OR (wl_namespace = '0' AND (wl_title >= 'SomeDbKey'))", ],
				[ 'ORDER BY' => [ 'wl_namespace DESC', 'wl_title DESC' ] ]
			],
			[
				[
					'from' => self::makeTitle( 0, 'AnotherDbKey' ),
					'until' => self::makeTitle( 0, 'SomeOtherDbKey' ),
					'startFrom' => self::makeTitle( 0, 'SomeDbKey' ),
					'sort' => WatchedItemQueryService::SORT_ASC
				],
				[
					"wl_namespace > '0' OR (wl_namespace = '0' AND (wl_title >= 'AnotherDbKey'))",
					"wl_namespace < '0' OR (wl_namespace = '0' AND (wl_title <= 'SomeOtherDbKey'))",
					"wl_namespace > '0' OR (wl_namespace = '0' AND (wl_title >= 'SomeDbKey'))",
				],
				[ 'ORDER BY' => [ 'wl_namespace ASC', 'wl_title ASC' ] ]
			],
			[
				[
					'from' => self::makeTitle( 0, 'SomeOtherDbKey' ),
					'until' => self::makeTitle( 0, 'AnotherDbKey' ),
					'startFrom' => self::makeTitle( 0, 'SomeDbKey' ),
					'sort' => WatchedItemQueryService::SORT_DESC
				],
				[
					"wl_namespace < '0' OR (wl_namespace = '0' AND (wl_title <= 'SomeOtherDbKey'))",
					"wl_namespace > '0' OR (wl_namespace = '0' AND (wl_title >= 'AnotherDbKey'))",
					"wl_namespace < '0' OR (wl_namespace = '0' AND (wl_title <= 'SomeDbKey'))",
				],
				[ 'ORDER BY' => [ 'wl_namespace DESC', 'wl_title DESC' ] ]
			],
		];
	}

	/**
	 * @dataProvider provideGetWatchedItemsForUser_fromUntilStartFromOptions
	 */
	public function testGetWatchedItemsForUser_fromUntilStartFromOptions(
		array $options,
		array $expectedConds,
		array $expectedDbOptions
	) {
		$user = $this->getMockUserWithId( 1 );

		$expectedConds = array_merge( [ 'wl_user' => 1 ], $expectedConds );

		$mockDb = $this->getMockDb();
		$mockDb->method( 'makeList' )
			->with(
				$this->isType( 'array' ),
				$this->isType( 'int' )
			)
			->willReturnCallback( static function ( $a, $conj ) {
				$sqlConj = $conj === LIST_AND ? ' AND ' : ' OR ';
				return implode( $sqlConj, array_map( static function ( $s ) {
					return '(' . $s . ')';
				}, $a
				) );
			} );
		$mockDb->expects( $this->once() )
			->method( 'select' )
			->with(
				[ 'watchlist' ],
				[ 'wl_namespace', 'wl_title', 'wl_notificationtimestamp' ],
				$expectedConds,
				$this->isType( 'string' ),
				$expectedDbOptions
			)
			->willReturn( new FakeResultWrapper( [] ) );

		$queryService = $this->newService( $mockDb );

		$items = $queryService->getWatchedItemsForUser( $user, $options );
		$this->assertSame( [], $items );
	}

	public static function getWatchedItemsForUserInvalidOptionsProvider() {
		return [
			[
				[ 'sort' => 'foo' ],
				'Bad value for parameter $options[\'sort\']'
			],
			[
				[ 'filter' => 'foo' ],
				'Bad value for parameter $options[\'filter\']'
			],
			[
				[ 'from' => self::makeTitle( 0, 'SomeDbKey' ), ],
				'Bad value for parameter $options[\'sort\']: must be provided'
			],
			[
				[ 'until' => self::makeTitle( 0, 'SomeDbKey' ), ],
				'Bad value for parameter $options[\'sort\']: must be provided'
			],
			[
				[ 'startFrom' => self::makeTitle( 0, 'SomeDbKey' ), ],
				'Bad value for parameter $options[\'sort\']: must be provided'
			],
		];
	}

	/**
	 * @dataProvider getWatchedItemsForUserInvalidOptionsProvider
	 */
	public function testGetWatchedItemsForUser_invalidOptionThrowsException(
		array $options,
		$expectedInExceptionMessage
	) {
		$queryService = $this->newService( $this->getMockDb() );

		$this->expectException( InvalidArgumentException::class );
		$this->expectExceptionMessage( $expectedInExceptionMessage );
		$queryService->getWatchedItemsForUser( $this->getMockUserWithId( 1 ), $options );
	}

	public function testGetWatchedItemsForUser_userNotAllowedToViewWatchlist() {
		$mockDb = $this->getMockDb();

		$mockDb->expects( $this->never() )
			->method( $this->anything() );

		$queryService = $this->newService( $mockDb );

		$items = $queryService->getWatchedItemsForUser(
			new UserIdentityValue( 0, 'AnonUser' ) );
		$this->assertSame( [], $items );
	}

}
