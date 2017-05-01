<?php

use Wikimedia\TestingAccessWrapper;

/**
 * @covers WatchedItemQueryService
 */
class WatchedItemQueryServiceUnitTest extends PHPUnit_Framework_TestCase {

	/**
	 * @return PHPUnit_Framework_MockObject_MockObject|Database
	 */
	private function getMockDb() {
		$mock = $this->getMockBuilder( Database::class )
			->disableOriginalConstructor()
			->getMock();

		$mock->expects( $this->any() )
			->method( 'makeList' )
			->with(
				$this->isType( 'array' ),
				$this->isType( 'int' )
			)
			->will( $this->returnCallback( function( $a, $conj ) {
				$sqlConj = $conj === LIST_AND ? ' AND ' : ' OR ';
				return join( $sqlConj, array_map( function( $s ) {
					return '(' . $s . ')';
				}, $a
				) );
			} ) );

		$mock->expects( $this->any() )
			->method( 'addQuotes' )
			->will( $this->returnCallback( function( $value ) {
				return "'$value'";
			} ) );

		$mock->expects( $this->any() )
			->method( 'timestamp' )
			->will( $this->returnArgument( 0 ) );

		$mock->expects( $this->any() )
			->method( 'bitAnd' )
			->willReturnCallback( function( $a, $b ) {
				return "($a & $b)";
			} );

		return $mock;
	}

	/**
	 * @param $mockDb
	 * @return PHPUnit_Framework_MockObject_MockObject|LoadBalancer
	 */
	private function getMockLoadBalancer( $mockDb ) {
		$mock = $this->getMockBuilder( LoadBalancer::class )
			->disableOriginalConstructor()
			->getMock();
		$mock->expects( $this->any() )
			->method( 'getConnectionRef' )
			->with( DB_SLAVE )
			->will( $this->returnValue( $mockDb ) );
		return $mock;
	}

	/**
	 * @param int $id
	 * @return PHPUnit_Framework_MockObject_MockObject|User
	 */
	private function getMockNonAnonUserWithId( $id ) {
		$mock = $this->getMockBuilder( User::class )->getMock();
		$mock->expects( $this->any() )
			->method( 'isAnon' )
			->will( $this->returnValue( false ) );
		$mock->expects( $this->any() )
			->method( 'getId' )
			->will( $this->returnValue( $id ) );
		return $mock;
	}

	/**
	 * @param int $id
	 * @return PHPUnit_Framework_MockObject_MockObject|User
	 */
	private function getMockUnrestrictedNonAnonUserWithId( $id ) {
		$mock = $this->getMockNonAnonUserWithId( $id );
		$mock->expects( $this->any() )
			->method( 'isAllowed' )
			->will( $this->returnValue( true ) );
		$mock->expects( $this->any() )
			->method( 'isAllowedAny' )
			->will( $this->returnValue( true ) );
		$mock->expects( $this->any() )
			->method( 'useRCPatrol' )
			->will( $this->returnValue( true ) );
		return $mock;
	}

	/**
	 * @param int $id
	 * @param string $notAllowedAction
	 * @return PHPUnit_Framework_MockObject_MockObject|User
	 */
	private function getMockNonAnonUserWithIdAndRestrictedPermissions( $id, $notAllowedAction ) {
		$mock = $this->getMockNonAnonUserWithId( $id );

		$mock->expects( $this->any() )
			->method( 'isAllowed' )
			->will( $this->returnCallback( function( $action ) use ( $notAllowedAction ) {
				return $action !== $notAllowedAction;
			} ) );
		$mock->expects( $this->any() )
			->method( 'isAllowedAny' )
			->will( $this->returnCallback( function() use ( $notAllowedAction ) {
				$actions = func_get_args();
				return !in_array( $notAllowedAction, $actions );
			} ) );

		return $mock;
	}

	/**
	 * @param int $id
	 * @return PHPUnit_Framework_MockObject_MockObject|User
	 */
	private function getMockNonAnonUserWithIdAndNoPatrolRights( $id ) {
		$mock = $this->getMockNonAnonUserWithId( $id );

		$mock->expects( $this->any() )
			->method( 'isAllowed' )
			->will( $this->returnValue( true ) );
		$mock->expects( $this->any() )
			->method( 'isAllowedAny' )
			->will( $this->returnValue( true ) );

		$mock->expects( $this->any() )
			->method( 'useRCPatrol' )
			->will( $this->returnValue( false ) );
		$mock->expects( $this->any() )
			->method( 'useNPPatrol' )
			->will( $this->returnValue( false ) );

		return $mock;
	}

	private function getMockAnonUser() {
		$mock = $this->getMockBuilder( User::class )->getMock();
		$mock->expects( $this->any() )
			->method( 'isAnon' )
			->will( $this->returnValue( true ) );
		return $mock;
	}

	private function getFakeRow( array $rowValues ) {
		$fakeRow = new stdClass();
		foreach ( $rowValues as $valueName => $value ) {
			$fakeRow->$valueName = $value;
		}
		return $fakeRow;
	}

	public function testGetWatchedItemsWithRecentChangeInfo() {
		$mockDb = $this->getMockDb();
		$mockDb->expects( $this->once() )
			->method( 'select' )
			->with(
				[ 'recentchanges', 'watchlist', 'page' ],
				[
					'rc_id',
					'rc_namespace',
					'rc_title',
					'rc_timestamp',
					'rc_type',
					'rc_deleted',
					'wl_notificationtimestamp',
					'rc_cur_id',
					'rc_this_oldid',
					'rc_last_oldid',
				],
				[
					'wl_user' => 1,
					'(rc_this_oldid=page_latest) OR (rc_type=3)',
				],
				$this->isType( 'string' ),
				[
					'LIMIT' => 3,
				],
				[
					'watchlist' => [
						'INNER JOIN',
						[
							'wl_namespace=rc_namespace',
							'wl_title=rc_title'
						]
					],
					'page' => [
						'LEFT JOIN',
						'rc_cur_id=page_id',
					],
				]
			)
			->will( $this->returnValue( [
				$this->getFakeRow( [
					'rc_id' => 1,
					'rc_namespace' => 0,
					'rc_title' => 'Foo1',
					'rc_timestamp' => '20151212010101',
					'rc_type' => RC_NEW,
					'rc_deleted' => 0,
					'wl_notificationtimestamp' => '20151212010101',
				] ),
				$this->getFakeRow( [
					'rc_id' => 2,
					'rc_namespace' => 1,
					'rc_title' => 'Foo2',
					'rc_timestamp' => '20151212010102',
					'rc_type' => RC_NEW,
					'rc_deleted' => 0,
					'wl_notificationtimestamp' => null,
				] ),
				$this->getFakeRow( [
					'rc_id' => 3,
					'rc_namespace' => 1,
					'rc_title' => 'Foo3',
					'rc_timestamp' => '20151212010103',
					'rc_type' => RC_NEW,
					'rc_deleted' => 0,
					'wl_notificationtimestamp' => null,
				] ),
			] ) );

		$queryService = new WatchedItemQueryService( $this->getMockLoadBalancer( $mockDb ) );
		$user = $this->getMockUnrestrictedNonAnonUserWithId( 1 );

		$startFrom = null;
		$items = $queryService->getWatchedItemsWithRecentChangeInfo(
			$user, [ 'limit' => 2 ], $startFrom
		);

		$this->assertInternalType( 'array', $items );
		$this->assertCount( 2, $items );

		foreach ( $items as list( $watchedItem, $recentChangeInfo ) ) {
			$this->assertInstanceOf( WatchedItem::class, $watchedItem );
			$this->assertInternalType( 'array', $recentChangeInfo );
		}

		$this->assertEquals(
			new WatchedItem( $user, new TitleValue( 0, 'Foo1' ), '20151212010101' ),
			$items[0][0]
		);
		$this->assertEquals(
			[
				'rc_id' => 1,
				'rc_namespace' => 0,
				'rc_title' => 'Foo1',
				'rc_timestamp' => '20151212010101',
				'rc_type' => RC_NEW,
				'rc_deleted' => 0,
			],
			$items[0][1]
		);

		$this->assertEquals(
			new WatchedItem( $user, new TitleValue( 1, 'Foo2' ), null ),
			$items[1][0]
		);
		$this->assertEquals(
			[
				'rc_id' => 2,
				'rc_namespace' => 1,
				'rc_title' => 'Foo2',
				'rc_timestamp' => '20151212010102',
				'rc_type' => RC_NEW,
				'rc_deleted' => 0,
			],
			$items[1][1]
		);

		$this->assertEquals( [ '20151212010103', 3 ], $startFrom );
	}

	public function testGetWatchedItemsWithRecentChangeInfo_extension() {
		$mockDb = $this->getMockDb();
		$mockDb->expects( $this->once() )
			->method( 'select' )
			->with(
				[ 'recentchanges', 'watchlist', 'page', 'extension_dummy_table' ],
				[
					'rc_id',
					'rc_namespace',
					'rc_title',
					'rc_timestamp',
					'rc_type',
					'rc_deleted',
					'wl_notificationtimestamp',
					'rc_cur_id',
					'rc_this_oldid',
					'rc_last_oldid',
					'extension_dummy_field',
				],
				[
					'wl_user' => 1,
					'(rc_this_oldid=page_latest) OR (rc_type=3)',
					'extension_dummy_cond',
				],
				$this->isType( 'string' ),
				[
					'extension_dummy_option',
				],
				[
					'watchlist' => [
						'INNER JOIN',
						[
							'wl_namespace=rc_namespace',
							'wl_title=rc_title'
						]
					],
					'page' => [
						'LEFT JOIN',
						'rc_cur_id=page_id',
					],
					'extension_dummy_join_cond' => [],
				]
			)
			->will( $this->returnValue( [
				$this->getFakeRow( [
					'rc_id' => 1,
					'rc_namespace' => 0,
					'rc_title' => 'Foo1',
					'rc_timestamp' => '20151212010101',
					'rc_type' => RC_NEW,
					'rc_deleted' => 0,
					'wl_notificationtimestamp' => '20151212010101',
				] ),
				$this->getFakeRow( [
					'rc_id' => 2,
					'rc_namespace' => 1,
					'rc_title' => 'Foo2',
					'rc_timestamp' => '20151212010102',
					'rc_type' => RC_NEW,
					'rc_deleted' => 0,
					'wl_notificationtimestamp' => null,
				] ),
			] ) );

		$user = $this->getMockUnrestrictedNonAnonUserWithId( 1 );

		$mockExtension = $this->getMockBuilder( WatchedItemQueryServiceExtension::class )
			->getMock();
		$mockExtension->expects( $this->once() )
			->method( 'modifyWatchedItemsWithRCInfoQuery' )
			->with(
				$this->identicalTo( $user ),
				$this->isType( 'array' ),
				$this->isInstanceOf( IDatabase::class ),
				$this->isType( 'array' ),
				$this->isType( 'array' ),
				$this->isType( 'array' ),
				$this->isType( 'array' ),
				$this->isType( 'array' )
			)
			->will( $this->returnCallback( function (
				$user, $options, $db, &$tables, &$fields, &$conds, &$dbOptions, &$joinConds
			) {
				$tables[] = 'extension_dummy_table';
				$fields[] = 'extension_dummy_field';
				$conds[] = 'extension_dummy_cond';
				$dbOptions[] = 'extension_dummy_option';
				$joinConds['extension_dummy_join_cond'] = [];
			} ) );
		$mockExtension->expects( $this->once() )
			->method( 'modifyWatchedItemsWithRCInfo' )
			->with(
				$this->identicalTo( $user ),
				$this->isType( 'array' ),
				$this->isInstanceOf( IDatabase::class ),
				$this->isType( 'array' ),
				$this->anything(),
				$this->anything() // Can't test for null here, PHPUnit applies this after the callback
			)
			->will( $this->returnCallback( function ( $user, $options, $db, &$items, $res, &$startFrom ) {
				foreach ( $items as $i => &$item ) {
					$item[1]['extension_dummy_field'] = $i;
				}
				unset( $item );

				$this->assertNull( $startFrom );
				$startFrom = [ '20160203123456', 42 ];
			} ) );

		$queryService = new WatchedItemQueryService( $this->getMockLoadBalancer( $mockDb ) );
		TestingAccessWrapper::newFromObject( $queryService )->extensions = [ $mockExtension ];

		$startFrom = null;
		$items = $queryService->getWatchedItemsWithRecentChangeInfo(
			$user, [], $startFrom
		);

		$this->assertInternalType( 'array', $items );
		$this->assertCount( 2, $items );

		foreach ( $items as list( $watchedItem, $recentChangeInfo ) ) {
			$this->assertInstanceOf( WatchedItem::class, $watchedItem );
			$this->assertInternalType( 'array', $recentChangeInfo );
		}

		$this->assertEquals(
			new WatchedItem( $user, new TitleValue( 0, 'Foo1' ), '20151212010101' ),
			$items[0][0]
		);
		$this->assertEquals(
			[
				'rc_id' => 1,
				'rc_namespace' => 0,
				'rc_title' => 'Foo1',
				'rc_timestamp' => '20151212010101',
				'rc_type' => RC_NEW,
				'rc_deleted' => 0,
				'extension_dummy_field' => 0,
			],
			$items[0][1]
		);

		$this->assertEquals(
			new WatchedItem( $user, new TitleValue( 1, 'Foo2' ), null ),
			$items[1][0]
		);
		$this->assertEquals(
			[
				'rc_id' => 2,
				'rc_namespace' => 1,
				'rc_title' => 'Foo2',
				'rc_timestamp' => '20151212010102',
				'rc_type' => RC_NEW,
				'rc_deleted' => 0,
				'extension_dummy_field' => 1,
			],
			$items[1][1]
		);

		$this->assertEquals( [ '20160203123456', 42 ], $startFrom );
	}

	public function getWatchedItemsWithRecentChangeInfoOptionsProvider() {
		return [
			[
				[ 'includeFields' => [ WatchedItemQueryService::INCLUDE_FLAGS ] ],
				null,
				[ 'rc_type', 'rc_minor', 'rc_bot' ],
				[],
				[],
			],
			[
				[ 'includeFields' => [ WatchedItemQueryService::INCLUDE_USER ] ],
				null,
				[ 'rc_user_text' ],
				[],
				[],
			],
			[
				[ 'includeFields' => [ WatchedItemQueryService::INCLUDE_USER_ID ] ],
				null,
				[ 'rc_user' ],
				[],
				[],
			],
			[
				[ 'includeFields' => [ WatchedItemQueryService::INCLUDE_COMMENT ] ],
				null,
				[ 'rc_comment' ],
				[],
				[],
			],
			[
				[ 'includeFields' => [ WatchedItemQueryService::INCLUDE_PATROL_INFO ] ],
				null,
				[ 'rc_patrolled', 'rc_log_type' ],
				[],
				[],
			],
			[
				[ 'includeFields' => [ WatchedItemQueryService::INCLUDE_SIZES ] ],
				null,
				[ 'rc_old_len', 'rc_new_len' ],
				[],
				[],
			],
			[
				[ 'includeFields' => [ WatchedItemQueryService::INCLUDE_LOG_INFO ] ],
				null,
				[ 'rc_logid', 'rc_log_type', 'rc_log_action', 'rc_params' ],
				[],
				[],
			],
			[
				[ 'namespaceIds' => [ 0, 1 ] ],
				null,
				[],
				[ 'wl_namespace' => [ 0, 1 ] ],
				[],
			],
			[
				[ 'namespaceIds' => [ 0, "1; DROP TABLE watchlist;\n--" ] ],
				null,
				[],
				[ 'wl_namespace' => [ 0, 1 ] ],
				[],
			],
			[
				[ 'rcTypes' => [ RC_EDIT, RC_NEW ] ],
				null,
				[],
				[ 'rc_type' => [ RC_EDIT, RC_NEW ] ],
				[],
			],
			[
				[ 'dir' => WatchedItemQueryService::DIR_OLDER ],
				null,
				[],
				[],
				[ 'ORDER BY' => [ 'rc_timestamp DESC', 'rc_id DESC' ] ]
			],
			[
				[ 'dir' => WatchedItemQueryService::DIR_NEWER ],
				null,
				[],
				[],
				[ 'ORDER BY' => [ 'rc_timestamp', 'rc_id' ] ]
			],
			[
				[ 'dir' => WatchedItemQueryService::DIR_OLDER, 'start' => '20151212010101' ],
				null,
				[],
				[ "rc_timestamp <= '20151212010101'" ],
				[ 'ORDER BY' => [ 'rc_timestamp DESC', 'rc_id DESC' ] ]
			],
			[
				[ 'dir' => WatchedItemQueryService::DIR_OLDER, 'end' => '20151212010101' ],
				null,
				[],
				[ "rc_timestamp >= '20151212010101'" ],
				[ 'ORDER BY' => [ 'rc_timestamp DESC', 'rc_id DESC' ] ]
			],
			[
				[
					'dir' => WatchedItemQueryService::DIR_OLDER,
					'start' => '20151212020101',
					'end' => '20151212010101'
				],
				null,
				[],
				[ "rc_timestamp <= '20151212020101'", "rc_timestamp >= '20151212010101'" ],
				[ 'ORDER BY' => [ 'rc_timestamp DESC', 'rc_id DESC' ] ]
			],
			[
				[ 'dir' => WatchedItemQueryService::DIR_NEWER, 'start' => '20151212010101' ],
				null,
				[],
				[ "rc_timestamp >= '20151212010101'" ],
				[ 'ORDER BY' => [ 'rc_timestamp', 'rc_id' ] ]
			],
			[
				[ 'dir' => WatchedItemQueryService::DIR_NEWER, 'end' => '20151212010101' ],
				null,
				[],
				[ "rc_timestamp <= '20151212010101'" ],
				[ 'ORDER BY' => [ 'rc_timestamp', 'rc_id' ] ]
			],
			[
				[
					'dir' => WatchedItemQueryService::DIR_NEWER,
					'start' => '20151212010101',
					'end' => '20151212020101'
				],
				null,
				[],
				[ "rc_timestamp >= '20151212010101'", "rc_timestamp <= '20151212020101'" ],
				[ 'ORDER BY' => [ 'rc_timestamp', 'rc_id' ] ]
			],
			[
				[ 'limit' => 10 ],
				null,
				[],
				[],
				[ 'LIMIT' => 11 ],
			],
			[
				[ 'limit' => "10; DROP TABLE watchlist;\n--" ],
				null,
				[],
				[],
				[ 'LIMIT' => 11 ],
			],
			[
				[ 'filters' => [ WatchedItemQueryService::FILTER_MINOR ] ],
				null,
				[],
				[ 'rc_minor != 0' ],
				[],
			],
			[
				[ 'filters' => [ WatchedItemQueryService::FILTER_NOT_MINOR ] ],
				null,
				[],
				[ 'rc_minor = 0' ],
				[],
			],
			[
				[ 'filters' => [ WatchedItemQueryService::FILTER_BOT ] ],
				null,
				[],
				[ 'rc_bot != 0' ],
				[],
			],
			[
				[ 'filters' => [ WatchedItemQueryService::FILTER_NOT_BOT ] ],
				null,
				[],
				[ 'rc_bot = 0' ],
				[],
			],
			[
				[ 'filters' => [ WatchedItemQueryService::FILTER_ANON ] ],
				null,
				[],
				[ 'rc_user = 0' ],
				[],
			],
			[
				[ 'filters' => [ WatchedItemQueryService::FILTER_NOT_ANON ] ],
				null,
				[],
				[ 'rc_user != 0' ],
				[],
			],
			[
				[ 'filters' => [ WatchedItemQueryService::FILTER_PATROLLED ] ],
				null,
				[],
				[ 'rc_patrolled != 0' ],
				[],
			],
			[
				[ 'filters' => [ WatchedItemQueryService::FILTER_NOT_PATROLLED ] ],
				null,
				[],
				[ 'rc_patrolled = 0' ],
				[],
			],
			[
				[ 'filters' => [ WatchedItemQueryService::FILTER_UNREAD ] ],
				null,
				[],
				[ 'rc_timestamp >= wl_notificationtimestamp' ],
				[],
			],
			[
				[ 'filters' => [ WatchedItemQueryService::FILTER_NOT_UNREAD ] ],
				null,
				[],
				[ 'wl_notificationtimestamp IS NULL OR rc_timestamp < wl_notificationtimestamp' ],
				[],
			],
			[
				[ 'onlyByUser' => 'SomeOtherUser' ],
				null,
				[],
				[ 'rc_user_text' => 'SomeOtherUser' ],
				[],
			],
			[
				[ 'notByUser' => 'SomeOtherUser' ],
				null,
				[],
				[ "rc_user_text != 'SomeOtherUser'" ],
				[],
			],
			[
				[ 'dir' => WatchedItemQueryService::DIR_OLDER ],
				[ '20151212010101', 123 ],
				[],
				[
					"(rc_timestamp < '20151212010101') OR ((rc_timestamp = '20151212010101') AND (rc_id <= 123))"
				],
				[ 'ORDER BY' => [ 'rc_timestamp DESC', 'rc_id DESC' ] ],
			],
			[
				[ 'dir' => WatchedItemQueryService::DIR_NEWER ],
				[ '20151212010101', 123 ],
				[],
				[
					"(rc_timestamp > '20151212010101') OR ((rc_timestamp = '20151212010101') AND (rc_id >= 123))"
				],
				[ 'ORDER BY' => [ 'rc_timestamp', 'rc_id' ] ],
			],
			[
				[ 'dir' => WatchedItemQueryService::DIR_OLDER ],
				[ '20151212010101', "123; DROP TABLE watchlist;\n--" ],
				[],
				[
					"(rc_timestamp < '20151212010101') OR ((rc_timestamp = '20151212010101') AND (rc_id <= 123))"
				],
				[ 'ORDER BY' => [ 'rc_timestamp DESC', 'rc_id DESC' ] ],
			],
		];
	}

	/**
	 * @dataProvider getWatchedItemsWithRecentChangeInfoOptionsProvider
	 */
	public function testGetWatchedItemsWithRecentChangeInfo_optionsAndEmptyResult(
		array $options,
		$startFrom,
		array $expectedExtraFields,
		array $expectedExtraConds,
		array $expectedDbOptions
	) {
		$expectedFields = array_merge(
			[
				'rc_id',
				'rc_namespace',
				'rc_title',
				'rc_timestamp',
				'rc_type',
				'rc_deleted',
				'wl_notificationtimestamp',

				'rc_cur_id',
				'rc_this_oldid',
				'rc_last_oldid',
			],
			$expectedExtraFields
		);
		$expectedConds = array_merge(
			[ 'wl_user' => 1, '(rc_this_oldid=page_latest) OR (rc_type=3)', ],
			$expectedExtraConds
		);

		$mockDb = $this->getMockDb();
		$mockDb->expects( $this->once() )
			->method( 'select' )
			->with(
				[ 'recentchanges', 'watchlist', 'page' ],
				$expectedFields,
				$expectedConds,
				$this->isType( 'string' ),
				$expectedDbOptions,
				[
					'watchlist' => [
						'INNER JOIN',
						[
							'wl_namespace=rc_namespace',
							'wl_title=rc_title'
						]
					],
					'page' => [
						'LEFT JOIN',
						'rc_cur_id=page_id',
					],
				]
			)
			->will( $this->returnValue( [] ) );

		$queryService = new WatchedItemQueryService( $this->getMockLoadBalancer( $mockDb ) );
		$user = $this->getMockUnrestrictedNonAnonUserWithId( 1 );

		$items = $queryService->getWatchedItemsWithRecentChangeInfo( $user, $options, $startFrom );

		$this->assertEmpty( $items );
		$this->assertNull( $startFrom );
	}

	public function filterPatrolledOptionProvider() {
		return [
			[ WatchedItemQueryService::FILTER_PATROLLED ],
			[ WatchedItemQueryService::FILTER_NOT_PATROLLED ],
		];
	}

	/**
	 * @dataProvider filterPatrolledOptionProvider
	 */
	public function testGetWatchedItemsWithRecentChangeInfo_filterPatrolledAndUserWithNoPatrolRights(
		$filtersOption
	) {
		$mockDb = $this->getMockDb();
		$mockDb->expects( $this->once() )
			->method( 'select' )
			->with(
				[ 'recentchanges', 'watchlist', 'page' ],
				$this->isType( 'array' ),
				[ 'wl_user' => 1, '(rc_this_oldid=page_latest) OR (rc_type=3)' ],
				$this->isType( 'string' ),
				$this->isType( 'array' ),
				$this->isType( 'array' )
			)
			->will( $this->returnValue( [] ) );

		$user = $this->getMockNonAnonUserWithIdAndNoPatrolRights( 1 );

		$queryService = new WatchedItemQueryService( $this->getMockLoadBalancer( $mockDb ) );
		$items = $queryService->getWatchedItemsWithRecentChangeInfo(
			$user,
			[ 'filters' => [ $filtersOption ] ]
		);

		$this->assertEmpty( $items );
	}

	public function mysqlIndexOptimizationProvider() {
		return [
			[
				'mysql',
				[],
				[ "rc_timestamp > ''" ],
			],
			[
				'mysql',
				[ 'start' => '20151212010101', 'dir' => WatchedItemQueryService::DIR_OLDER ],
				[ "rc_timestamp <= '20151212010101'" ],
			],
			[
				'mysql',
				[ 'end' => '20151212010101', 'dir' => WatchedItemQueryService::DIR_OLDER ],
				[ "rc_timestamp >= '20151212010101'" ],
			],
			[
				'postgres',
				[],
				[],
			],
		];
	}

	/**
	 * @dataProvider mysqlIndexOptimizationProvider
	 */
	public function testGetWatchedItemsWithRecentChangeInfo_mysqlIndexOptimization(
		$dbType,
		array $options,
		array $expectedExtraConds
	) {
		$commonConds = [ 'wl_user' => 1, '(rc_this_oldid=page_latest) OR (rc_type=3)' ];
		$conds = array_merge( $commonConds, $expectedExtraConds );

		$mockDb = $this->getMockDb();
		$mockDb->expects( $this->once() )
			->method( 'select' )
			->with(
				[ 'recentchanges', 'watchlist', 'page' ],
				$this->isType( 'array' ),
				$conds,
				$this->isType( 'string' ),
				$this->isType( 'array' ),
				$this->isType( 'array' )
			)
			->will( $this->returnValue( [] ) );
		$mockDb->expects( $this->any() )
			->method( 'getType' )
			->will( $this->returnValue( $dbType ) );

		$queryService = new WatchedItemQueryService( $this->getMockLoadBalancer( $mockDb ) );
		$user = $this->getMockUnrestrictedNonAnonUserWithId( 1 );

		$items = $queryService->getWatchedItemsWithRecentChangeInfo( $user, $options );

		$this->assertEmpty( $items );
	}

	public function userPermissionRelatedExtraChecksProvider() {
		return [
			[
				[],
				'deletedhistory',
				[
					'(rc_type != ' . RC_LOG . ') OR ((rc_deleted & ' . LogPage::DELETED_ACTION . ') != ' .
						LogPage::DELETED_ACTION . ')'
				],
			],
			[
				[],
				'suppressrevision',
				[
					'(rc_type != ' . RC_LOG . ') OR (' .
						'(rc_deleted & ' . ( LogPage::DELETED_ACTION | LogPage::DELETED_RESTRICTED ) . ') != ' .
						( LogPage::DELETED_ACTION | LogPage::DELETED_RESTRICTED ) . ')'
				],
			],
			[
				[],
				'viewsuppressed',
				[
					'(rc_type != ' . RC_LOG . ') OR (' .
						'(rc_deleted & ' . ( LogPage::DELETED_ACTION | LogPage::DELETED_RESTRICTED ) . ') != ' .
						( LogPage::DELETED_ACTION | LogPage::DELETED_RESTRICTED ) . ')'
				],
			],
			[
				[ 'onlyByUser' => 'SomeOtherUser' ],
				'deletedhistory',
				[
					'rc_user_text' => 'SomeOtherUser',
					'(rc_deleted & ' . Revision::DELETED_USER . ') != ' . Revision::DELETED_USER,
					'(rc_type != ' . RC_LOG . ') OR ((rc_deleted & ' . LogPage::DELETED_ACTION . ') != ' .
						LogPage::DELETED_ACTION . ')'
				],
			],
			[
				[ 'onlyByUser' => 'SomeOtherUser' ],
				'suppressrevision',
				[
					'rc_user_text' => 'SomeOtherUser',
					'(rc_deleted & ' . ( Revision::DELETED_USER | Revision::DELETED_RESTRICTED ) . ') != ' .
						( Revision::DELETED_USER | Revision::DELETED_RESTRICTED ),
					'(rc_type != ' . RC_LOG . ') OR (' .
						'(rc_deleted & ' . ( LogPage::DELETED_ACTION | LogPage::DELETED_RESTRICTED ) . ') != ' .
						( LogPage::DELETED_ACTION | LogPage::DELETED_RESTRICTED ) . ')'
				],
			],
			[
				[ 'onlyByUser' => 'SomeOtherUser' ],
				'viewsuppressed',
				[
					'rc_user_text' => 'SomeOtherUser',
					'(rc_deleted & ' . ( Revision::DELETED_USER | Revision::DELETED_RESTRICTED ) . ') != ' .
						( Revision::DELETED_USER | Revision::DELETED_RESTRICTED ),
					'(rc_type != ' . RC_LOG . ') OR (' .
						'(rc_deleted & ' . ( LogPage::DELETED_ACTION | LogPage::DELETED_RESTRICTED ) . ') != ' .
						( LogPage::DELETED_ACTION | LogPage::DELETED_RESTRICTED ) . ')'
				],
			],
		];
	}

	/**
	 * @dataProvider userPermissionRelatedExtraChecksProvider
	 */
	public function testGetWatchedItemsWithRecentChangeInfo_userPermissionRelatedExtraChecks(
		array $options,
		$notAllowedAction,
		array $expectedExtraConds
	) {
		$commonConds = [ 'wl_user' => 1, '(rc_this_oldid=page_latest) OR (rc_type=3)' ];
		$conds = array_merge( $commonConds, $expectedExtraConds );

		$mockDb = $this->getMockDb();
		$mockDb->expects( $this->once() )
			->method( 'select' )
			->with(
				[ 'recentchanges', 'watchlist', 'page' ],
				$this->isType( 'array' ),
				$conds,
				$this->isType( 'string' ),
				$this->isType( 'array' ),
				$this->isType( 'array' )
			)
			->will( $this->returnValue( [] ) );

		$user = $this->getMockNonAnonUserWithIdAndRestrictedPermissions( 1, $notAllowedAction );

		$queryService = new WatchedItemQueryService( $this->getMockLoadBalancer( $mockDb ) );
		$items = $queryService->getWatchedItemsWithRecentChangeInfo( $user, $options );

		$this->assertEmpty( $items );
	}

	public function testGetWatchedItemsWithRecentChangeInfo_allRevisionsOptionAndEmptyResult() {
		$mockDb = $this->getMockDb();
		$mockDb->expects( $this->once() )
			->method( 'select' )
			->with(
				[ 'recentchanges', 'watchlist' ],
				[
					'rc_id',
					'rc_namespace',
					'rc_title',
					'rc_timestamp',
					'rc_type',
					'rc_deleted',
					'wl_notificationtimestamp',

					'rc_cur_id',
					'rc_this_oldid',
					'rc_last_oldid',
				],
				[ 'wl_user' => 1, ],
				$this->isType( 'string' ),
				[],
				[
					'watchlist' => [
						'INNER JOIN',
						[
							'wl_namespace=rc_namespace',
							'wl_title=rc_title'
						]
					],
				]
			)
			->will( $this->returnValue( [] ) );

		$queryService = new WatchedItemQueryService( $this->getMockLoadBalancer( $mockDb ) );
		$user = $this->getMockUnrestrictedNonAnonUserWithId( 1 );

		$items = $queryService->getWatchedItemsWithRecentChangeInfo( $user, [ 'allRevisions' => true ] );

		$this->assertEmpty( $items );
	}

	public function getWatchedItemsWithRecentChangeInfoInvalidOptionsProvider() {
		return [
			[
				[ 'rcTypes' => [ 1337 ] ],
				null,
				'Bad value for parameter $options[\'rcTypes\']',
			],
			[
				[ 'rcTypes' => [ 'edit' ] ],
				null,
				'Bad value for parameter $options[\'rcTypes\']',
			],
			[
				[ 'rcTypes' => [ RC_EDIT, 1337 ] ],
				null,
				'Bad value for parameter $options[\'rcTypes\']',
			],
			[
				[ 'dir' => 'foo' ],
				null,
				'Bad value for parameter $options[\'dir\']',
			],
			[
				[ 'start' => '20151212010101' ],
				null,
				'Bad value for parameter $options[\'dir\']: must be provided',
			],
			[
				[ 'end' => '20151212010101' ],
				null,
				'Bad value for parameter $options[\'dir\']: must be provided',
			],
			[
				[],
				[ '20151212010101', 123 ],
				'Bad value for parameter $options[\'dir\']: must be provided',
			],
			[
				[ 'dir' => WatchedItemQueryService::DIR_OLDER ],
				'20151212010101',
				'Bad value for parameter $startFrom: must be a two-element array',
			],
			[
				[ 'dir' => WatchedItemQueryService::DIR_OLDER ],
				[ '20151212010101' ],
				'Bad value for parameter $startFrom: must be a two-element array',
			],
			[
				[ 'dir' => WatchedItemQueryService::DIR_OLDER ],
				[ '20151212010101', 123, 'foo' ],
				'Bad value for parameter $startFrom: must be a two-element array',
			],
			[
				[ 'watchlistOwner' => $this->getMockUnrestrictedNonAnonUserWithId( 2 ) ],
				null,
				'Bad value for parameter $options[\'watchlistOwnerToken\']',
			],
			[
				[ 'watchlistOwner' => 'Other User', 'watchlistOwnerToken' => 'some-token' ],
				null,
				'Bad value for parameter $options[\'watchlistOwner\']',
			],
		];
	}

	/**
	 * @dataProvider getWatchedItemsWithRecentChangeInfoInvalidOptionsProvider
	 */
	public function testGetWatchedItemsWithRecentChangeInfo_invalidOptions(
		array $options,
		$startFrom,
		$expectedInExceptionMessage
	) {
		$mockDb = $this->getMockDb();
		$mockDb->expects( $this->never() )
			->method( $this->anything() );

		$queryService = new WatchedItemQueryService( $this->getMockLoadBalancer( $mockDb ) );
		$user = $this->getMockUnrestrictedNonAnonUserWithId( 1 );

		$this->setExpectedException( InvalidArgumentException::class, $expectedInExceptionMessage );
		$queryService->getWatchedItemsWithRecentChangeInfo( $user, $options, $startFrom );
	}

	public function testGetWatchedItemsWithRecentChangeInfo_usedInGeneratorOptionAndEmptyResult() {
		$mockDb = $this->getMockDb();
		$mockDb->expects( $this->once() )
			->method( 'select' )
			->with(
				[ 'recentchanges', 'watchlist', 'page' ],
				[
					'rc_id',
					'rc_namespace',
					'rc_title',
					'rc_timestamp',
					'rc_type',
					'rc_deleted',
					'wl_notificationtimestamp',
					'rc_cur_id',
				],
				[ 'wl_user' => 1, '(rc_this_oldid=page_latest) OR (rc_type=3)' ],
				$this->isType( 'string' ),
				[],
				[
					'watchlist' => [
						'INNER JOIN',
						[
							'wl_namespace=rc_namespace',
							'wl_title=rc_title'
						]
					],
					'page' => [
						'LEFT JOIN',
						'rc_cur_id=page_id',
					],
				]
			)
			->will( $this->returnValue( [] ) );

		$queryService = new WatchedItemQueryService( $this->getMockLoadBalancer( $mockDb ) );
		$user = $this->getMockUnrestrictedNonAnonUserWithId( 1 );

		$items = $queryService->getWatchedItemsWithRecentChangeInfo(
			$user,
			[ 'usedInGenerator' => true ]
		);

		$this->assertEmpty( $items );
	}

	public function testGetWatchedItemsWithRecentChangeInfo_usedInGeneratorAllRevisionsOptions() {
		$mockDb = $this->getMockDb();
		$mockDb->expects( $this->once() )
			->method( 'select' )
			->with(
				[ 'recentchanges', 'watchlist' ],
				[
					'rc_id',
					'rc_namespace',
					'rc_title',
					'rc_timestamp',
					'rc_type',
					'rc_deleted',
					'wl_notificationtimestamp',
					'rc_this_oldid',
				],
				[ 'wl_user' => 1 ],
				$this->isType( 'string' ),
				[],
				[
					'watchlist' => [
						'INNER JOIN',
						[
							'wl_namespace=rc_namespace',
							'wl_title=rc_title'
						]
					],
				]
			)
			->will( $this->returnValue( [] ) );

		$queryService = new WatchedItemQueryService( $this->getMockLoadBalancer( $mockDb ) );
		$user = $this->getMockUnrestrictedNonAnonUserWithId( 1 );

		$items = $queryService->getWatchedItemsWithRecentChangeInfo(
			$user,
			[ 'usedInGenerator' => true, 'allRevisions' => true, ]
		);

		$this->assertEmpty( $items );
	}

	public function testGetWatchedItemsWithRecentChangeInfo_watchlistOwnerOptionAndEmptyResult() {
		$mockDb = $this->getMockDb();
		$mockDb->expects( $this->once() )
			->method( 'select' )
			->with(
				$this->isType( 'array' ),
				$this->isType( 'array' ),
				[
					'wl_user' => 2,
					'(rc_this_oldid=page_latest) OR (rc_type=3)',
				],
				$this->isType( 'string' ),
				$this->isType( 'array' ),
				$this->isType( 'array' )
			)
			->will( $this->returnValue( [] ) );

		$queryService = new WatchedItemQueryService( $this->getMockLoadBalancer( $mockDb ) );
		$user = $this->getMockUnrestrictedNonAnonUserWithId( 1 );
		$otherUser = $this->getMockUnrestrictedNonAnonUserWithId( 2 );
		$otherUser->expects( $this->once() )
			->method( 'getOption' )
			->with( 'watchlisttoken' )
			->willReturn( '0123456789abcdef' );

		$items = $queryService->getWatchedItemsWithRecentChangeInfo(
			$user,
			[ 'watchlistOwner' => $otherUser, 'watchlistOwnerToken' => '0123456789abcdef' ]
		);

		$this->assertEmpty( $items );
	}

	public function invalidWatchlistTokenProvider() {
		return [
			[ 'wrongToken' ],
			[ '' ],
		];
	}

	/**
	 * @dataProvider invalidWatchlistTokenProvider
	 */
	public function testGetWatchedItemsWithRecentChangeInfo_watchlistOwnerAndInvalidToken( $token ) {
		$mockDb = $this->getMockDb();
		$mockDb->expects( $this->never() )
			->method( $this->anything() );

		$queryService = new WatchedItemQueryService( $this->getMockLoadBalancer( $mockDb ) );
		$user = $this->getMockUnrestrictedNonAnonUserWithId( 1 );
		$otherUser = $this->getMockUnrestrictedNonAnonUserWithId( 2 );
		$otherUser->expects( $this->once() )
			->method( 'getOption' )
			->with( 'watchlisttoken' )
			->willReturn( '0123456789abcdef' );

		$this->setExpectedException( ApiUsageException::class, 'Incorrect watchlist token provided' );
		$queryService->getWatchedItemsWithRecentChangeInfo(
			$user,
			[ 'watchlistOwner' => $otherUser, 'watchlistOwnerToken' => $token ]
		);
	}

	public function testGetWatchedItemsForUser() {
		$mockDb = $this->getMockDb();
		$mockDb->expects( $this->once() )
			->method( 'select' )
			->with(
				'watchlist',
				[ 'wl_namespace', 'wl_title', 'wl_notificationtimestamp' ],
				[ 'wl_user' => 1 ]
			)
			->will( $this->returnValue( [
				$this->getFakeRow( [
					'wl_namespace' => 0,
					'wl_title' => 'Foo1',
					'wl_notificationtimestamp' => '20151212010101',
				] ),
				$this->getFakeRow( [
					'wl_namespace' => 1,
					'wl_title' => 'Foo2',
					'wl_notificationtimestamp' => null,
				] ),
			] ) );

		$queryService = new WatchedItemQueryService( $this->getMockLoadBalancer( $mockDb ) );
		$user = $this->getMockNonAnonUserWithId( 1 );

		$items = $queryService->getWatchedItemsForUser( $user );

		$this->assertInternalType( 'array', $items );
		$this->assertCount( 2, $items );
		$this->assertContainsOnlyInstancesOf( WatchedItem::class, $items );
		$this->assertEquals(
			new WatchedItem( $user, new TitleValue( 0, 'Foo1' ), '20151212010101' ),
			$items[0]
		);
		$this->assertEquals(
			new WatchedItem( $user, new TitleValue( 1, 'Foo2' ), null ),
			$items[1]
		);
	}

	public function provideGetWatchedItemsForUserOptions() {
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
				[ 'ORDER BY' => 'wl_title ASC' ]
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
				[ 'ORDER BY' => 'wl_title DESC' ]
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
		$user = $this->getMockNonAnonUserWithId( 1 );

		$expectedConds = array_merge( [ 'wl_user' => 1 ], $expectedConds );
		$mockDb->expects( $this->once() )
			->method( 'select' )
			->with(
				'watchlist',
				[ 'wl_namespace', 'wl_title', 'wl_notificationtimestamp' ],
				$expectedConds,
				$this->isType( 'string' ),
				$expectedDbOptions
			)
			->will( $this->returnValue( [] ) );

		$queryService = new WatchedItemQueryService( $this->getMockLoadBalancer( $mockDb ) );

		$items = $queryService->getWatchedItemsForUser( $user, $options );
		$this->assertEmpty( $items );
	}

	public function provideGetWatchedItemsForUser_fromUntilStartFromOptions() {
		return [
			[
				[
					'from' => new TitleValue( 0, 'SomeDbKey' ),
					'sort' => WatchedItemQueryService::SORT_ASC
				],
				[ "(wl_namespace > 0) OR ((wl_namespace = 0) AND (wl_title >= 'SomeDbKey'))", ],
				[ 'ORDER BY' => [ 'wl_namespace ASC', 'wl_title ASC' ] ]
			],
			[
				[
					'from' => new TitleValue( 0, 'SomeDbKey' ),
					'sort' => WatchedItemQueryService::SORT_DESC,
				],
				[ "(wl_namespace < 0) OR ((wl_namespace = 0) AND (wl_title <= 'SomeDbKey'))", ],
				[ 'ORDER BY' => [ 'wl_namespace DESC', 'wl_title DESC' ] ]
			],
			[
				[
					'until' => new TitleValue( 0, 'SomeDbKey' ),
					'sort' => WatchedItemQueryService::SORT_ASC
				],
				[ "(wl_namespace < 0) OR ((wl_namespace = 0) AND (wl_title <= 'SomeDbKey'))", ],
				[ 'ORDER BY' => [ 'wl_namespace ASC', 'wl_title ASC' ] ]
			],
			[
				[
					'until' => new TitleValue( 0, 'SomeDbKey' ),
					'sort' => WatchedItemQueryService::SORT_DESC
				],
				[ "(wl_namespace > 0) OR ((wl_namespace = 0) AND (wl_title >= 'SomeDbKey'))", ],
				[ 'ORDER BY' => [ 'wl_namespace DESC', 'wl_title DESC' ] ]
			],
			[
				[
					'from' => new TitleValue( 0, 'AnotherDbKey' ),
					'until' => new TitleValue( 0, 'SomeOtherDbKey' ),
					'startFrom' => new TitleValue( 0, 'SomeDbKey' ),
					'sort' => WatchedItemQueryService::SORT_ASC
				],
				[
					"(wl_namespace > 0) OR ((wl_namespace = 0) AND (wl_title >= 'AnotherDbKey'))",
					"(wl_namespace < 0) OR ((wl_namespace = 0) AND (wl_title <= 'SomeOtherDbKey'))",
					"(wl_namespace > 0) OR ((wl_namespace = 0) AND (wl_title >= 'SomeDbKey'))",
				],
				[ 'ORDER BY' => [ 'wl_namespace ASC', 'wl_title ASC' ] ]
			],
			[
				[
					'from' => new TitleValue( 0, 'SomeOtherDbKey' ),
					'until' => new TitleValue( 0, 'AnotherDbKey' ),
					'startFrom' => new TitleValue( 0, 'SomeDbKey' ),
					'sort' => WatchedItemQueryService::SORT_DESC
				],
				[
					"(wl_namespace < 0) OR ((wl_namespace = 0) AND (wl_title <= 'SomeOtherDbKey'))",
					"(wl_namespace > 0) OR ((wl_namespace = 0) AND (wl_title >= 'AnotherDbKey'))",
					"(wl_namespace < 0) OR ((wl_namespace = 0) AND (wl_title <= 'SomeDbKey'))",
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
		$user = $this->getMockNonAnonUserWithId( 1 );

		$expectedConds = array_merge( [ 'wl_user' => 1 ], $expectedConds );

		$mockDb = $this->getMockDb();
		$mockDb->expects( $this->any() )
			->method( 'addQuotes' )
			->will( $this->returnCallback( function( $value ) {
				return "'$value'";
			} ) );
		$mockDb->expects( $this->any() )
			->method( 'makeList' )
			->with(
				$this->isType( 'array' ),
				$this->isType( 'int' )
			)
			->will( $this->returnCallback( function( $a, $conj ) {
				$sqlConj = $conj === LIST_AND ? ' AND ' : ' OR ';
				return join( $sqlConj, array_map( function( $s ) {
					return '(' . $s . ')';
				}, $a
				) );
			} ) );
		$mockDb->expects( $this->once() )
			->method( 'select' )
			->with(
				'watchlist',
				[ 'wl_namespace', 'wl_title', 'wl_notificationtimestamp' ],
				$expectedConds,
				$this->isType( 'string' ),
				$expectedDbOptions
			)
			->will( $this->returnValue( [] ) );

		$queryService = new WatchedItemQueryService( $this->getMockLoadBalancer( $mockDb ) );

		$items = $queryService->getWatchedItemsForUser( $user, $options );
		$this->assertEmpty( $items );
	}

	public function getWatchedItemsForUserInvalidOptionsProvider() {
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
				[ 'from' => new TitleValue( 0, 'SomeDbKey' ), ],
				'Bad value for parameter $options[\'sort\']: must be provided'
			],
			[
				[ 'until' => new TitleValue( 0, 'SomeDbKey' ), ],
				'Bad value for parameter $options[\'sort\']: must be provided'
			],
			[
				[ 'startFrom' => new TitleValue( 0, 'SomeDbKey' ), ],
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
		$queryService = new WatchedItemQueryService( $this->getMockLoadBalancer( $this->getMockDb() ) );

		$this->setExpectedException( InvalidArgumentException::class, $expectedInExceptionMessage );
		$queryService->getWatchedItemsForUser( $this->getMockNonAnonUserWithId( 1 ), $options );
	}

	public function testGetWatchedItemsForUser_userNotAllowedToViewWatchlist() {
		$mockDb = $this->getMockDb();

		$mockDb->expects( $this->never() )
			->method( $this->anything() );

		$queryService = new WatchedItemQueryService( $this->getMockLoadBalancer( $mockDb ) );

		$items = $queryService->getWatchedItemsForUser( $this->getMockAnonUser() );
		$this->assertEmpty( $items );
	}

}
