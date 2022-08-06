<?php

use MediaWiki\Revision\RevisionRecord;
use MediaWiki\User\UserIdentityValue;
use MediaWiki\User\UserOptionsLookup;
use PHPUnit\Framework\MockObject\MockObject;
use Wikimedia\Rdbms\DBConnRef;
use Wikimedia\Rdbms\IDatabase;
use Wikimedia\Rdbms\LoadBalancer;
use Wikimedia\TestingAccessWrapper;

/**
 * @covers WatchedItemQueryService
 */
class WatchedItemQueryServiceUnitTest extends MediaWikiUnitTestCase {

	/**
	 * @return MockObject&CommentStore
	 */
	private function getMockCommentStore() {
		$mockStore = $this->createMock( CommentStore::class );
		$mockStore->method( 'getFields' )
			->willReturn( [ 'commentstore' => 'fields' ] );
		$mockStore->method( 'getJoin' )
			->willReturn( [
				'tables' => [ 'commentstore' => 'table' ],
				'fields' => [ 'commentstore' => 'field' ],
				'joins' => [ 'commentstore' => 'join' ],
			] );
		return $mockStore;
	}

	/**
	 * @param DBConnRef $mockDb
	 * @param UserOptionsLookup|null $userOptionsLookup
	 * @return WatchedItemQueryService
	 */
	private function newService(
		DBConnRef $mockDb,
		UserOptionsLookup $userOptionsLookup = null
	) {
		return new WatchedItemQueryService(
			$this->getMockLoadBalancer( $mockDb ),
			$this->getMockCommentStore(),
			$this->getMockWatchedItemStore(),
			$this->createHookContainer(),
			$userOptionsLookup ?? $this->createMock( UserOptionsLookup::class ),
			false
		);
	}

	/**
	 * @return MockObject&DBConnRef
	 */
	private function getMockDb() {
		$mock = $this->createMock( DBConnRef::class );

		$mock->method( 'makeList' )
			->with(
				$this->isType( 'array' ),
				$this->isType( 'int' )
			)
			->willReturnCallback( static function ( $a, $conj ) {
				$sqlConj = $conj === LIST_AND ? ' AND ' : ' OR ';
				$conds = [];
				foreach ( $a as $k => $v ) {
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

		$mock->method( 'addQuotes' )
			->willReturnCallback( static function ( $value ) {
				return "'$value'";
			} );

		$mock->method( 'timestamp' )
			->will( $this->returnArgument( 0 ) );

		$mock->method( 'bitAnd' )
			->willReturnCallback( static function ( $a, $b ) {
				return "($a & $b)";
			} );

		return $mock;
	}

	/**
	 * @param DBConnRef $mockDb
	 * @return LoadBalancer
	 */
	private function getMockLoadBalancer( DBConnRef $mockDb ) {
		$mock = $this->createMock( LoadBalancer::class );
		$mock->method( 'getConnectionRef' )
			->with( DB_REPLICA )
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
						'JOIN',
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
			->willReturn( [
				(object)[
					'rc_id' => 1,
					'rc_namespace' => 0,
					'rc_title' => 'Foo1',
					'rc_timestamp' => '20151212010101',
					'rc_type' => RC_NEW,
					'rc_deleted' => 0,
					'wl_notificationtimestamp' => '20151212010101',
				],
				(object)[
					'rc_id' => 2,
					'rc_namespace' => 1,
					'rc_title' => 'Foo2',
					'rc_timestamp' => '20151212010102',
					'rc_type' => RC_NEW,
					'rc_deleted' => 0,
					'wl_notificationtimestamp' => null,
				],
				(object)[
					'rc_id' => 3,
					'rc_namespace' => 1,
					'rc_title' => 'Foo3',
					'rc_timestamp' => '20151212010103',
					'rc_type' => RC_NEW,
					'rc_deleted' => 0,
					'wl_notificationtimestamp' => null,
				],
			] );

		$queryService = $this->newService( $mockDb );
		$user = $this->getMockUserWithId( 1 );

		$startFrom = null;
		$items = $queryService->getWatchedItemsWithRecentChangeInfo(
			$user, [ 'limit' => 2 ], $startFrom
		);

		$this->assertIsArray( $items );
		$this->assertCount( 2, $items );

		foreach ( $items as list( $watchedItem, $recentChangeInfo ) ) {
			$this->assertInstanceOf( WatchedItem::class, $watchedItem );
			$this->assertIsArray( $recentChangeInfo );
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
						'JOIN',
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
			->willReturn( [
				(object)[
					'rc_id' => 1,
					'rc_namespace' => 0,
					'rc_title' => 'Foo1',
					'rc_timestamp' => '20151212010101',
					'rc_type' => RC_NEW,
					'rc_deleted' => 0,
					'wl_notificationtimestamp' => '20151212010101',
				],
				(object)[
					'rc_id' => 2,
					'rc_namespace' => 1,
					'rc_title' => 'Foo2',
					'rc_timestamp' => '20151212010102',
					'rc_type' => RC_NEW,
					'rc_deleted' => 0,
					'wl_notificationtimestamp' => null,
				],
			] );

		$user = $this->getMockUserWithId( 1 );

		$mockExtension = $this->createMock( WatchedItemQueryServiceExtension::class );
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
			->willReturnCallback( static function (
				$user, $options, $db, &$tables, &$fields, &$conds, &$dbOptions, &$joinConds
			) {
				$tables[] = 'extension_dummy_table';
				$fields[] = 'extension_dummy_field';
				$conds[] = 'extension_dummy_cond';
				$dbOptions[] = 'extension_dummy_option';
				$joinConds['extension_dummy_join_cond'] = [];
			} );
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
			->willReturnCallback( function ( $user, $options, $db, &$items, $res, &$startFrom ) {
				foreach ( $items as $i => &$item ) {
					$item[1]['extension_dummy_field'] = $i;
				}
				unset( $item );

				$this->assertNull( $startFrom );
				$startFrom = [ '20160203123456', 42 ];
			} );

		$queryService = $this->newService( $mockDb );
		TestingAccessWrapper::newFromObject( $queryService )->extensions = [ $mockExtension ];

		$startFrom = null;
		$items = $queryService->getWatchedItemsWithRecentChangeInfo(
			$user, [], $startFrom
		);

		$this->assertIsArray( $items );
		$this->assertCount( 2, $items );

		foreach ( $items as list( $watchedItem, $recentChangeInfo ) ) {
			$this->assertInstanceOf( WatchedItem::class, $watchedItem );
			$this->assertIsArray( $recentChangeInfo );
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
				[],
				[ 'rc_type', 'rc_minor', 'rc_bot' ],
				[],
				[],
				[],
			],
			[
				[ 'includeFields' => [ WatchedItemQueryService::INCLUDE_USER ] ],
				null,
				[ 'watchlist_actor' => 'actor' ],
				[ 'rc_user_text' => 'watchlist_actor.actor_name' ],
				[],
				[],
				[ 'watchlist_actor' => [ 'JOIN', 'actor_id=rc_actor' ] ],
			],
			[
				[ 'includeFields' => [ WatchedItemQueryService::INCLUDE_USER_ID ] ],
				null,
				[ 'watchlist_actor' => 'actor' ],
				[ 'rc_user' => 'watchlist_actor.actor_user' ],
				[],
				[],
				[ 'watchlist_actor' => [ 'JOIN', 'actor_id=rc_actor' ] ],
			],
			[
				[ 'includeFields' => [ WatchedItemQueryService::INCLUDE_COMMENT ] ],
				null,
				[ 'commentstore' => 'table' ],
				[ 'commentstore' => 'field' ],
				[],
				[],
				[ 'commentstore' => 'join' ],
			],
			[
				[ 'includeFields' => [ WatchedItemQueryService::INCLUDE_PATROL_INFO ] ],
				null,
				[],
				[ 'rc_patrolled', 'rc_log_type' ],
				[],
				[],
				[],
			],
			[
				[ 'includeFields' => [ WatchedItemQueryService::INCLUDE_SIZES ] ],
				null,
				[],
				[ 'rc_old_len', 'rc_new_len' ],
				[],
				[],
				[],
			],
			[
				[ 'includeFields' => [ WatchedItemQueryService::INCLUDE_LOG_INFO ] ],
				null,
				[],
				[ 'rc_logid', 'rc_log_type', 'rc_log_action', 'rc_params' ],
				[],
				[],
				[],
			],
			[
				[ 'namespaceIds' => [ 0, 1 ] ],
				null,
				[],
				[],
				[ 'wl_namespace' => [ 0, 1 ] ],
				[],
				[],
			],
			[
				[ 'namespaceIds' => [ 0, "1; DROP TABLE watchlist;\n--" ] ],
				null,
				[],
				[],
				[ 'wl_namespace' => [ 0, 1 ] ],
				[],
				[],
			],
			[
				[ 'rcTypes' => [ RC_EDIT, RC_NEW ] ],
				null,
				[],
				[],
				[ 'rc_type' => [ RC_EDIT, RC_NEW ] ],
				[],
				[],
			],
			[
				[ 'dir' => WatchedItemQueryService::DIR_OLDER ],
				null,
				[],
				[],
				[],
				[ 'ORDER BY' => [ 'rc_timestamp DESC', 'rc_id DESC' ] ],
				[],
			],
			[
				[ 'dir' => WatchedItemQueryService::DIR_NEWER ],
				null,
				[],
				[],
				[],
				[ 'ORDER BY' => [ 'rc_timestamp', 'rc_id' ] ],
				[],
			],
			[
				[ 'dir' => WatchedItemQueryService::DIR_OLDER, 'start' => '20151212010101' ],
				null,
				[],
				[],
				[ "rc_timestamp <= '20151212010101'" ],
				[ 'ORDER BY' => [ 'rc_timestamp DESC', 'rc_id DESC' ] ],
				[],
			],
			[
				[ 'dir' => WatchedItemQueryService::DIR_OLDER, 'end' => '20151212010101' ],
				null,
				[],
				[],
				[ "rc_timestamp >= '20151212010101'" ],
				[ 'ORDER BY' => [ 'rc_timestamp DESC', 'rc_id DESC' ] ],
				[],
			],
			[
				[
					'dir' => WatchedItemQueryService::DIR_OLDER,
					'start' => '20151212020101',
					'end' => '20151212010101'
				],
				null,
				[],
				[],
				[ "rc_timestamp <= '20151212020101'", "rc_timestamp >= '20151212010101'" ],
				[ 'ORDER BY' => [ 'rc_timestamp DESC', 'rc_id DESC' ] ],
				[],
			],
			[
				[ 'dir' => WatchedItemQueryService::DIR_NEWER, 'start' => '20151212010101' ],
				null,
				[],
				[],
				[ "rc_timestamp >= '20151212010101'" ],
				[ 'ORDER BY' => [ 'rc_timestamp', 'rc_id' ] ],
				[],
			],
			[
				[ 'dir' => WatchedItemQueryService::DIR_NEWER, 'end' => '20151212010101' ],
				null,
				[],
				[],
				[ "rc_timestamp <= '20151212010101'" ],
				[ 'ORDER BY' => [ 'rc_timestamp', 'rc_id' ] ],
				[],
			],
			[
				[
					'dir' => WatchedItemQueryService::DIR_NEWER,
					'start' => '20151212010101',
					'end' => '20151212020101'
				],
				null,
				[],
				[],
				[ "rc_timestamp >= '20151212010101'", "rc_timestamp <= '20151212020101'" ],
				[ 'ORDER BY' => [ 'rc_timestamp', 'rc_id' ] ],
				[],
			],
			[
				[ 'limit' => 10 ],
				null,
				[],
				[],
				[],
				[ 'LIMIT' => 11 ],
				[],
			],
			[
				[ 'limit' => "10; DROP TABLE watchlist;\n--" ],
				null,
				[],
				[],
				[],
				[ 'LIMIT' => 11 ],
				[],
			],
			[
				[ 'filters' => [ WatchedItemQueryService::FILTER_MINOR ] ],
				null,
				[],
				[],
				[ 'rc_minor != 0' ],
				[],
				[],
			],
			[
				[ 'filters' => [ WatchedItemQueryService::FILTER_NOT_MINOR ] ],
				null,
				[],
				[],
				[ 'rc_minor = 0' ],
				[],
				[],
			],
			[
				[ 'filters' => [ WatchedItemQueryService::FILTER_BOT ] ],
				null,
				[],
				[],
				[ 'rc_bot != 0' ],
				[],
				[],
			],
			[
				[ 'filters' => [ WatchedItemQueryService::FILTER_NOT_BOT ] ],
				null,
				[],
				[],
				[ 'rc_bot = 0' ],
				[],
				[],
			],
			[
				[ 'filters' => [ WatchedItemQueryService::FILTER_ANON ] ],
				null,
				[ 'watchlist_actor' => 'actor' ],
				[],
				[ 'watchlist_actor.actor_user IS NULL' ],
				[],
				[ 'watchlist_actor' => [ 'JOIN', 'actor_id=rc_actor' ] ],
			],
			[
				[ 'filters' => [ WatchedItemQueryService::FILTER_NOT_ANON ] ],
				null,
				[ 'watchlist_actor' => 'actor' ],
				[],
				[ 'watchlist_actor.actor_user IS NOT NULL' ],
				[],
				[ 'watchlist_actor' => [ 'JOIN', 'actor_id=rc_actor' ] ],
			],
			[
				[ 'filters' => [ WatchedItemQueryService::FILTER_PATROLLED ] ],
				null,
				[],
				[],
				[ 'rc_patrolled != 0' ],
				[],
				[],
			],
			[
				[ 'filters' => [ WatchedItemQueryService::FILTER_NOT_PATROLLED ] ],
				null,
				[],
				[],
				[ 'rc_patrolled' => 0 ],
				[],
				[],
			],
			[
				[ 'filters' => [ WatchedItemQueryService::FILTER_UNREAD ] ],
				null,
				[],
				[],
				[ 'rc_timestamp >= wl_notificationtimestamp' ],
				[],
				[],
			],
			[
				[ 'filters' => [ WatchedItemQueryService::FILTER_NOT_UNREAD ] ],
				null,
				[],
				[],
				[ 'wl_notificationtimestamp IS NULL OR rc_timestamp < wl_notificationtimestamp' ],
				[],
				[],
			],
			[
				[ 'onlyByUser' => 'SomeOtherUser' ],
				null,
				[ 'watchlist_actor' => 'actor' ],
				[],
				[ 'watchlist_actor.actor_name' => 'SomeOtherUser' ],
				[],
				[ 'watchlist_actor' => [ 'JOIN', 'actor_id=rc_actor' ] ],
			],
			[
				[ 'notByUser' => 'SomeOtherUser' ],
				null,
				[ 'watchlist_actor' => 'actor' ],
				[],
				[ 'watchlist_actor.actor_name<>\'SomeOtherUser\'' ],
				[],
				[ 'watchlist_actor' => [ 'JOIN', 'actor_id=rc_actor' ] ],
			],
			[
				[ 'dir' => WatchedItemQueryService::DIR_OLDER ],
				[ '20151212010101', 123 ],
				[],
				[],
				[
					"(rc_timestamp < '20151212010101') OR ((rc_timestamp = '20151212010101') AND (rc_id <= 123))"
				],
				[ 'ORDER BY' => [ 'rc_timestamp DESC', 'rc_id DESC' ] ],
				[],
			],
			[
				[ 'dir' => WatchedItemQueryService::DIR_NEWER ],
				[ '20151212010101', 123 ],
				[],
				[],
				[
					"(rc_timestamp > '20151212010101') OR ((rc_timestamp = '20151212010101') AND (rc_id >= 123))"
				],
				[ 'ORDER BY' => [ 'rc_timestamp', 'rc_id' ] ],
				[],
			],
			[
				[ 'dir' => WatchedItemQueryService::DIR_OLDER ],
				[ '20151212010101', "123; DROP TABLE watchlist;\n--" ],
				[],
				[],
				[
					"(rc_timestamp < '20151212010101') OR ((rc_timestamp = '20151212010101') AND (rc_id <= 123))"
				],
				[ 'ORDER BY' => [ 'rc_timestamp DESC', 'rc_id DESC' ] ],
				[],
			],
		];
	}

	/**
	 * @dataProvider getWatchedItemsWithRecentChangeInfoOptionsProvider
	 */
	public function testGetWatchedItemsWithRecentChangeInfo_optionsAndEmptyResult(
		array $options,
		$startFrom,
		array $expectedExtraTables,
		array $expectedExtraFields,
		array $expectedExtraConds,
		array $expectedDbOptions,
		array $expectedExtraJoinConds
	) {
		$expectedTables = array_merge( [ 'recentchanges', 'watchlist', 'page' ], $expectedExtraTables );
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
		$expectedJoinConds = array_merge(
			[
				'watchlist' => [
					'JOIN',
					[
						'wl_namespace=rc_namespace',
						'wl_title=rc_title'
					]
				],
				'page' => [
					'LEFT JOIN',
					'rc_cur_id=page_id',
				],
			],
			$expectedExtraJoinConds
		);

		$mockDb = $this->getMockDb();
		$mockDb->expects( $this->once() )
			->method( 'select' )
			->with(
				$expectedTables,
				$expectedFields,
				$expectedConds,
				$this->isType( 'string' ),
				$expectedDbOptions,
				$expectedJoinConds
			)
			->willReturn( [] );

		$queryService = $this->newService( $mockDb );
		$user = $this->getMockUserWithId( 1 );

		$items = $queryService->getWatchedItemsWithRecentChangeInfo( $user, $options, $startFrom );

		$this->assertSame( [], $items );
		$this->assertNull( $startFrom );
	}

	public function filterPatrolledOptionProvider() {
		return [
			'Patrolled' => [ WatchedItemQueryService::FILTER_PATROLLED ],
			'Not patrolled' => [ WatchedItemQueryService::FILTER_NOT_PATROLLED ],
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
			->willReturn( [] );

		$user = $this->getMockUserWithId( 1, false );

		$queryService = $this->newService( $mockDb );
		$items = $queryService->getWatchedItemsWithRecentChangeInfo(
			$user,
			[ 'filters' => [ $filtersOption ] ]
		);

		$this->assertSame( [], $items );
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
			->willReturn( [] );
		$mockDb->method( 'getType' )
			->willReturn( $dbType );

		$queryService = $this->newService( $mockDb );
		$user = $this->getMockUserWithId( 1 );

		$items = $queryService->getWatchedItemsWithRecentChangeInfo( $user, $options );

		$this->assertSame( [], $items );
	}

	public function userPermissionRelatedExtraChecksProvider() {
		return [
			[
				[],
				'deletedhistory',
				[],
				[
					'(rc_type != ' . RC_LOG . ') OR ((rc_deleted & ' . LogPage::DELETED_ACTION . ') != ' .
						LogPage::DELETED_ACTION . ')'
				],
				[],
			],
			[
				[],
				'suppressrevision',
				[],
				[
					'(rc_type != ' . RC_LOG . ') OR (' .
						'(rc_deleted & ' . ( LogPage::DELETED_ACTION | LogPage::DELETED_RESTRICTED ) . ') != ' .
						( LogPage::DELETED_ACTION | LogPage::DELETED_RESTRICTED ) . ')'
				],
				[],
			],
			[
				[],
				'viewsuppressed',
				[],
				[
					'(rc_type != ' . RC_LOG . ') OR (' .
						'(rc_deleted & ' . ( LogPage::DELETED_ACTION | LogPage::DELETED_RESTRICTED ) . ') != ' .
						( LogPage::DELETED_ACTION | LogPage::DELETED_RESTRICTED ) . ')'
				],
				[],
			],
			[
				[ 'onlyByUser' => 'SomeOtherUser' ],
				'deletedhistory',
				[ 'watchlist_actor' => 'actor' ],
				[
					'watchlist_actor.actor_name' => 'SomeOtherUser',
					'(rc_deleted & ' . RevisionRecord::DELETED_USER . ') != ' . RevisionRecord::DELETED_USER,
					'(rc_type != ' . RC_LOG . ') OR ((rc_deleted & ' . LogPage::DELETED_ACTION . ') != ' .
						LogPage::DELETED_ACTION . ')'
				],
				[ 'watchlist_actor' => [ 'JOIN', 'actor_id=rc_actor' ] ],
			],
			[
				[ 'onlyByUser' => 'SomeOtherUser' ],
				'suppressrevision',
				[ 'watchlist_actor' => 'actor' ],
				[
					'watchlist_actor.actor_name' => 'SomeOtherUser',
					'(rc_deleted & ' . ( RevisionRecord::DELETED_USER | RevisionRecord::DELETED_RESTRICTED ) . ') != ' .
						( RevisionRecord::DELETED_USER | RevisionRecord::DELETED_RESTRICTED ),
					'(rc_type != ' . RC_LOG . ') OR (' .
						'(rc_deleted & ' . ( LogPage::DELETED_ACTION | LogPage::DELETED_RESTRICTED ) . ') != ' .
						( LogPage::DELETED_ACTION | LogPage::DELETED_RESTRICTED ) . ')'
				],
				[ 'watchlist_actor' => [ 'JOIN', 'actor_id=rc_actor' ] ],
			],
			[
				[ 'onlyByUser' => 'SomeOtherUser' ],
				'viewsuppressed',
				[ 'watchlist_actor' => 'actor' ],
				[
					'watchlist_actor.actor_name' => 'SomeOtherUser',
					'(rc_deleted & ' . ( RevisionRecord::DELETED_USER | RevisionRecord::DELETED_RESTRICTED ) . ') != ' .
						( RevisionRecord::DELETED_USER | RevisionRecord::DELETED_RESTRICTED ),
					'(rc_type != ' . RC_LOG . ') OR (' .
						'(rc_deleted & ' . ( LogPage::DELETED_ACTION | LogPage::DELETED_RESTRICTED ) . ') != ' .
						( LogPage::DELETED_ACTION | LogPage::DELETED_RESTRICTED ) . ')'
				],
				[ 'watchlist_actor' => [ 'JOIN', 'actor_id=rc_actor' ] ],
			],
		];
	}

	/**
	 * @dataProvider userPermissionRelatedExtraChecksProvider
	 */
	public function testGetWatchedItemsWithRecentChangeInfo_userPermissionRelatedExtraChecks(
		array $options,
		$notAllowedAction,
		array $expectedExtraTables,
		array $expectedExtraConds,
		array $expectedExtraJoins
	) {
		$commonConds = [ 'wl_user' => 1, '(rc_this_oldid=page_latest) OR (rc_type=3)' ];
		$conds = array_merge( $commonConds, $expectedExtraConds );

		$mockDb = $this->getMockDb();
		$mockDb->expects( $this->once() )
			->method( 'select' )
			->with(
				array_merge( [ 'recentchanges', 'watchlist', 'page' ], $expectedExtraTables ),
				$this->isType( 'array' ),
				$conds,
				$this->isType( 'string' ),
				$this->isType( 'array' ),
				array_merge( [
					'watchlist' => [ 'JOIN', [ 'wl_namespace=rc_namespace', 'wl_title=rc_title' ] ],
					'page' => [ 'LEFT JOIN', 'rc_cur_id=page_id' ],
				], $expectedExtraJoins )
			)
			->willReturn( [] );

		$user = $this->getMockUserWithId( 1, false, $notAllowedAction );

		$queryService = $this->newService( $mockDb );
		$items = $queryService->getWatchedItemsWithRecentChangeInfo( $user, $options );

		$this->assertSame( [], $items );
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
						'JOIN',
						[
							'wl_namespace=rc_namespace',
							'wl_title=rc_title'
						]
					],
				]
			)
			->willReturn( [] );

		$queryService = $this->newService( $mockDb );
		$user = $this->getMockUserWithId( 1 );

		$items = $queryService->getWatchedItemsWithRecentChangeInfo( $user, [ 'allRevisions' => true ] );

		$this->assertSame( [], $items );
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
				[ 'watchlistOwner' => $this->getMockUserWithId( 2 ) ],
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

		$queryService = $this->newService( $mockDb );
		$user = $this->getMockUserWithId( 1 );

		$this->expectException( InvalidArgumentException::class );
		$this->expectExceptionMessage( $expectedInExceptionMessage );
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
						'JOIN',
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
			->willReturn( [] );

		$queryService = $this->newService( $mockDb );
		$user = $this->getMockUserWithId( 1 );

		$items = $queryService->getWatchedItemsWithRecentChangeInfo(
			$user,
			[ 'usedInGenerator' => true ]
		);

		$this->assertSame( [], $items );
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
						'JOIN',
						[
							'wl_namespace=rc_namespace',
							'wl_title=rc_title'
						]
					],
				]
			)
			->willReturn( [] );

		$queryService = $this->newService( $mockDb );
		$user = $this->getMockUserWithId( 1 );

		$items = $queryService->getWatchedItemsWithRecentChangeInfo(
			$user,
			[ 'usedInGenerator' => true, 'allRevisions' => true, ]
		);

		$this->assertSame( [], $items );
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
			->willReturn( [] );

		$user = $this->getMockUserWithId( 1 );
		$otherUser = $this->getMockUserWithId( 2, true );
		$userOptionsLookup = $this->createMock( UserOptionsLookup::class );
		$userOptionsLookup->expects( $this->once() )
			->method( 'getOption' )
			->with( $otherUser, 'watchlisttoken' )
			->willReturn( '0123456789abcdef' );
		$queryService = $this->newService( $mockDb, $userOptionsLookup );

		$items = $queryService->getWatchedItemsWithRecentChangeInfo(
			$user,
			[ 'watchlistOwner' => $otherUser, 'watchlistOwnerToken' => '0123456789abcdef' ]
		);

		$this->assertSame( [], $items );
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
			->willReturn( [
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
			] );

		$queryService = $this->newService( $mockDb );
		$user = $this->getMockUserWithId( 1 );

		$items = $queryService->getWatchedItemsForUser( $user );

		$this->assertIsArray( $items );
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
		$user = $this->getMockUserWithId( 1 );

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
			->willReturn( [] );

		$queryService = $this->newService( $mockDb );

		$items = $queryService->getWatchedItemsForUser( $user, $options );
		$this->assertSame( [], $items );
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
		$user = $this->getMockUserWithId( 1 );

		$expectedConds = array_merge( [ 'wl_user' => 1 ], $expectedConds );

		$mockDb = $this->getMockDb();
		$mockDb->method( 'addQuotes' )
			->willReturnCallback( static function ( $value ) {
				return "'$value'";
			} );
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
				'watchlist',
				[ 'wl_namespace', 'wl_title', 'wl_notificationtimestamp' ],
				$expectedConds,
				$this->isType( 'string' ),
				$expectedDbOptions
			)
			->willReturn( [] );

		$queryService = $this->newService( $mockDb );

		$items = $queryService->getWatchedItemsForUser( $user, $options );
		$this->assertSame( [], $items );
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
