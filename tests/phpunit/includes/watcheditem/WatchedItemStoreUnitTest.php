<?php

use MediaWiki\Linker\LinkTarget;
use MediaWiki\Revision\RevisionLookup;
use MediaWiki\Revision\RevisionRecord;
use MediaWiki\User\UserIdentityValue;
use Wikimedia\Rdbms\LBFactory;
use Wikimedia\Rdbms\LoadBalancer;
use Wikimedia\TestingAccessWrapper;

/**
 * @author Addshore
 *
 * @covers WatchedItemStore
 */
class WatchedItemStoreUnitTest extends MediaWikiTestCase {

	/**
	 * @return PHPUnit_Framework_MockObject_MockObject|IDatabase
	 */
	private function getMockDb() {
		return $this->createMock( IDatabase::class );
	}

	/**
	 * @return PHPUnit_Framework_MockObject_MockObject|LoadBalancer
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
	 * @return PHPUnit_Framework_MockObject_MockObject|LBFactory
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
	 * @return PHPUnit_Framework_MockObject_MockObject|JobQueueGroup
	 */
	private function getMockJobQueueGroup() {
		$mock = $this->getMockBuilder( JobQueueGroup::class )
			->disableOriginalConstructor()
			->getMock();
		$mock->expects( $this->any() )
			->method( 'push' )
			->will( $this->returnCallback( function ( Job $job ) {
				$job->run();
			} ) );
		$mock->expects( $this->any() )
			->method( 'lazyPush' )
			->will( $this->returnCallback( function ( Job $job ) {
				$job->run();
			} ) );
		return $mock;
	}

	/**
	 * @return PHPUnit_Framework_MockObject_MockObject|HashBagOStuff
	 */
	private function getMockCache() {
		if ( defined( 'HHVM_VERSION' ) ) {
			$this->markTestSkipped( 'HHVM Reflection buggy' );
		}

		$mock = $this->getMockBuilder( HashBagOStuff::class )
			->disableOriginalConstructor()
			->setMethods( [ 'get', 'set', 'delete', 'makeKey' ] )
			->getMock();
		$mock->expects( $this->any() )
			->method( 'makeKey' )
			->will( $this->returnCallback( function ( ...$args ) {
				return implode( ':', $args );
			} ) );
		return $mock;
	}

	/**
	 * @return PHPUnit_Framework_MockObject_MockObject|ReadOnlyMode
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
	 */
	private function getMockNsInfo() : NamespaceInfo {
		$mock = $this->createMock( NamespaceInfo::class );
		$mock->method( 'getSubjectPage' )->will( $this->returnArgument( 0 ) );
		$mock->method( 'getTalkPage' )->will( $this->returnCallback(
				function ( $target ) {
					return new TitleValue( 1, $target->getDbKey() );
				}
			) );
		$mock->expects( $this->never() )
			->method( $this->anythingBut( 'getSubjectPage', 'getTalkPage' ) );
		return $mock;
	}

	/**
	 * No methods may be called except provided callbacks, if any.
	 *
	 * @param array $callbacks Keys are method names, values are callbacks
	 * @param array $counts Keys are method names, values are expected number of times to be called
	 *   (default is any number is okay)
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

	private function getFakeRow( array $rowValues ) {
		$fakeRow = new stdClass();
		foreach ( $rowValues as $valueName => $value ) {
			$fakeRow->$valueName = $value;
		}
		return $fakeRow;
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
	 */
	private function newWatchedItemStore( array $mocks = [] ) : WatchedItemStore {
		return new WatchedItemStore(
			$mocks['lbFactory'] ??
				$this->getMockLBFactory( $mocks['db'] ?? $this->getMockDb() ),
			$mocks['queueGroup'] ?? $this->getMockJobQueueGroup(),
			new HashBagOStuff(),
			$mocks['cache'] ?? $this->getMockCache(),
			$mocks['readOnlyMode'] ?? $this->getMockReadOnlyMode(),
			1000,
			$mocks['nsInfo'] ?? $this->getMockNsInfo(),
			$mocks['revisionLookup'] ?? $this->getMockRevisionLookup()
		);
	}

	public function testClearWatchedItems() {
		$user = new UserIdentityValue( 7, 'MockUser', 0 );

		$mockDb = $this->getMockDb();
		$mockDb->expects( $this->once() )
			->method( 'selectField' )
			->with(
				'watchlist',
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

		$store = $this->newWatchedItemStore( [ 'db' => $mockDb, 'cache' => $mockCache ] );
		TestingAccessWrapper::newFromObject( $store )
			->cacheIndex = [ 0 => [ 'F' => [ 7 => 'RM-KEY', 9 => 'KEEP-KEY' ] ] ];

		$this->assertTrue( $store->clearUserWatchedItems( $user ) );
	}

	public function testClearWatchedItems_tooManyItemsWatched() {
		$user = new UserIdentityValue( 7, 'MockUser', 0 );

		$mockDb = $this->getMockDb();
		$mockDb->expects( $this->once() )
			->method( 'selectField' )
			->with(
				'watchlist',
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

		$store = $this->newWatchedItemStore( [ 'db' => $mockDb, 'cache' => $mockCache ] );

		$this->assertFalse( $store->clearUserWatchedItems( $user ) );
	}

	public function testCountWatchedItems() {
		$user = new UserIdentityValue( 1, 'MockUser', 0 );

		$mockDb = $this->getMockDb();
		$mockDb->expects( $this->exactly( 1 ) )
			->method( 'selectField' )
			->with(
				'watchlist',
				'COUNT(*)',
				[
					'wl_user' => $user->getId(),
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

	public function testCountWatchers() {
		$titleValue = new TitleValue( 0, 'SomeDbKey' );

		$mockDb = $this->getMockDb();
		$mockDb->expects( $this->exactly( 1 ) )
			->method( 'selectField' )
			->with(
				'watchlist',
				'COUNT(*)',
				[
					'wl_namespace' => $titleValue->getNamespace(),
					'wl_title' => $titleValue->getDBkey(),
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

	public function testCountWatchersMultiple() {
		$titleValues = [
			new TitleValue( 0, 'SomeDbKey' ),
			new TitleValue( 0, 'OtherDbKey' ),
			new TitleValue( 1, 'AnotherDbKey' ),
		];

		$mockDb = $this->getMockDb();

		$dbResult = [
			$this->getFakeRow( [ 'wl_title' => 'SomeDbKey', 'wl_namespace' => '0', 'watchers' => '100' ] ),
			$this->getFakeRow( [ 'wl_title' => 'OtherDbKey', 'wl_namespace' => '0', 'watchers' => '300' ] ),
			$this->getFakeRow( [ 'wl_title' => 'AnotherDbKey', 'wl_namespace' => '1', 'watchers' => '500' ]
			),
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
			->method( 'select' )
			->with(
				'watchlist',
				[ 'wl_title', 'wl_namespace', 'watchers' => 'COUNT(*)' ],
				[ 'makeWhereFrom2d return value' ],
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

	/**
	 * @dataProvider provideIntWithDbUnsafeVersion
	 */
	public function testCountWatchersMultiple_withMinimumWatchers( $minWatchers ) {
		$titleValues = [
			new TitleValue( 0, 'SomeDbKey' ),
			new TitleValue( 0, 'OtherDbKey' ),
			new TitleValue( 1, 'AnotherDbKey' ),
		];

		$mockDb = $this->getMockDb();

		$dbResult = [
			$this->getFakeRow( [ 'wl_title' => 'SomeDbKey', 'wl_namespace' => '0', 'watchers' => '100' ] ),
			$this->getFakeRow( [ 'wl_title' => 'OtherDbKey', 'wl_namespace' => '0', 'watchers' => '300' ] ),
			$this->getFakeRow( [ 'wl_title' => 'AnotherDbKey', 'wl_namespace' => '1', 'watchers' => '500' ]
			),
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
			->method( 'select' )
			->with(
				'watchlist',
				[ 'wl_title', 'wl_namespace', 'watchers' => 'COUNT(*)' ],
				[ 'makeWhereFrom2d return value' ],
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

	public function testCountVisitingWatchers() {
		$titleValue = new TitleValue( 0, 'SomeDbKey' );

		$mockDb = $this->getMockDb();
		$mockDb->expects( $this->exactly( 1 ) )
			->method( 'selectField' )
			->with(
				'watchlist',
				'COUNT(*)',
				[
					'wl_namespace' => $titleValue->getNamespace(),
					'wl_title' => $titleValue->getDBkey(),
					'wl_notificationtimestamp >= \'TS111TS\' OR wl_notificationtimestamp IS NULL',
				],
				$this->isType( 'string' )
			)
			->will( $this->returnValue( '7' ) );
		$mockDb->expects( $this->exactly( 1 ) )
			->method( 'addQuotes' )
			->will( $this->returnCallback( function ( $value ) {
				return "'$value'";
			} ) );
		$mockDb->expects( $this->exactly( 1 ) )
			->method( 'timestamp' )
			->will( $this->returnCallback( function ( $value ) {
				return 'TS' . $value . 'TS';
			} ) );

		$mockCache = $this->getMockCache();
		$mockCache->expects( $this->never() )->method( 'set' );
		$mockCache->expects( $this->never() )->method( 'get' );
		$mockCache->expects( $this->never() )->method( 'delete' );

		$store = $this->newWatchedItemStore( [ 'db' => $mockDb, 'cache' => $mockCache ] );

		$this->assertEquals( 7, $store->countVisitingWatchers( $titleValue, '111' ) );
	}

	public function testCountVisitingWatchersMultiple() {
		$titleValuesWithThresholds = [
			[ new TitleValue( 0, 'SomeDbKey' ), '111' ],
			[ new TitleValue( 0, 'OtherDbKey' ), '111' ],
			[ new TitleValue( 1, 'AnotherDbKey' ), '123' ],
		];

		$dbResult = [
			$this->getFakeRow( [ 'wl_title' => 'SomeDbKey', 'wl_namespace' => '0', 'watchers' => '100' ] ),
			$this->getFakeRow( [ 'wl_title' => 'OtherDbKey', 'wl_namespace' => '0', 'watchers' => '300' ] ),
			$this->getFakeRow(
				[ 'wl_title' => 'AnotherDbKey', 'wl_namespace' => '1', 'watchers' => '500' ]
			),
		];
		$mockDb = $this->getMockDb();
		$mockDb->expects( $this->exactly( 2 * 3 ) )
			->method( 'addQuotes' )
			->will( $this->returnCallback( function ( $value ) {
				return "'$value'";
			} ) );
		$mockDb->expects( $this->exactly( 3 ) )
			->method( 'timestamp' )
			->will( $this->returnCallback( function ( $value ) {
				return 'TS' . $value . 'TS';
			} ) );
		$mockDb->expects( $this->any() )
			->method( 'makeList' )
			->with(
				$this->isType( 'array' ),
				$this->isType( 'int' )
			)
			->will( $this->returnCallback( function ( $a, $conj ) {
				$sqlConj = $conj === LIST_AND ? ' AND ' : ' OR ';
				return implode( $sqlConj, array_map( function ( $s ) {
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
				'watchlist',
				[ 'wl_namespace', 'wl_title', 'watchers' => 'COUNT(*)' ],
				$expectedCond,
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

	public function testCountVisitingWatchersMultiple_withMissingTargets() {
		$titleValuesWithThresholds = [
			[ new TitleValue( 0, 'SomeDbKey' ), '111' ],
			[ new TitleValue( 0, 'OtherDbKey' ), '111' ],
			[ new TitleValue( 1, 'AnotherDbKey' ), '123' ],
			[ new TitleValue( 0, 'SomeNotExisitingDbKey' ), null ],
			[ new TitleValue( 0, 'OtherNotExisitingDbKey' ), null ],
		];

		$dbResult = [
			$this->getFakeRow( [ 'wl_title' => 'SomeDbKey', 'wl_namespace' => '0', 'watchers' => '100' ] ),
			$this->getFakeRow( [ 'wl_title' => 'OtherDbKey', 'wl_namespace' => '0', 'watchers' => '300' ] ),
			$this->getFakeRow(
				[ 'wl_title' => 'AnotherDbKey', 'wl_namespace' => '1', 'watchers' => '500' ]
			),
			$this->getFakeRow(
				[ 'wl_title' => 'SomeNotExisitingDbKey', 'wl_namespace' => '0', 'watchers' => '100' ]
			),
			$this->getFakeRow(
				[ 'wl_title' => 'OtherNotExisitingDbKey', 'wl_namespace' => '0', 'watchers' => '200' ]
			),
		];
		$mockDb = $this->getMockDb();
		$mockDb->expects( $this->exactly( 2 * 3 ) )
			->method( 'addQuotes' )
			->will( $this->returnCallback( function ( $value ) {
				return "'$value'";
			} ) );
		$mockDb->expects( $this->exactly( 3 ) )
			->method( 'timestamp' )
			->will( $this->returnCallback( function ( $value ) {
				return 'TS' . $value . 'TS';
			} ) );
		$mockDb->expects( $this->any() )
			->method( 'makeList' )
			->with(
				$this->isType( 'array' ),
				$this->isType( 'int' )
			)
			->will( $this->returnCallback( function ( $a, $conj ) {
				$sqlConj = $conj === LIST_AND ? ' AND ' : ' OR ';
				return implode( $sqlConj, array_map( function ( $s ) {
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
				'watchlist',
				[ 'wl_namespace', 'wl_title', 'watchers' => 'COUNT(*)' ],
				$expectedCond,
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
	 * @dataProvider provideIntWithDbUnsafeVersion
	 */
	public function testCountVisitingWatchersMultiple_withMinimumWatchers( $minWatchers ) {
		$titleValuesWithThresholds = [
			[ new TitleValue( 0, 'SomeDbKey' ), '111' ],
			[ new TitleValue( 0, 'OtherDbKey' ), '111' ],
			[ new TitleValue( 1, 'AnotherDbKey' ), '123' ],
		];

		$mockDb = $this->getMockDb();
		$mockDb->expects( $this->any() )
			->method( 'makeList' )
			->will( $this->returnValue( 'makeList return value' ) );
		$mockDb->expects( $this->once() )
			->method( 'select' )
			->with(
				'watchlist',
				[ 'wl_namespace', 'wl_title', 'watchers' => 'COUNT(*)' ],
				'makeList return value',
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

		$store = $this->newWatchedItemStore( [ 'db' => $mockDb, 'cache' => $mockCache ] );

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
		$user = new UserIdentityValue( 1, 'MockUser', 0 );

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
		$user = new UserIdentityValue( 1, 'MockUser', 0 );

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
		$user = new UserIdentityValue( 1, 'MockUser', 0 );

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

	public function testDuplicateEntry_nothingToDuplicate() {
		$mockDb = $this->getMockDb();
		$mockDb->expects( $this->once() )
			->method( 'select' )
			->with(
				'watchlist',
				[
					'wl_user',
					'wl_notificationtimestamp',
				],
				[
					'wl_namespace' => 0,
					'wl_title' => 'Old_Title',
				],
				'WatchedItemStore::duplicateEntry',
				[ 'FOR UPDATE' ]
			)
			->will( $this->returnValue( new FakeResultWrapper( [] ) ) );

		$store = $this->newWatchedItemStore( [ 'db' => $mockDb ] );

		$store->duplicateEntry(
			new TitleValue( 0, 'Old_Title' ),
			new TitleValue( 0, 'New_Title' )
		);
	}

	public function testDuplicateEntry_somethingToDuplicate() {
		$fakeRows = [
			$this->getFakeRow( [ 'wl_user' => '1', 'wl_notificationtimestamp' => '20151212010101' ] ),
			$this->getFakeRow( [ 'wl_user' => '2', 'wl_notificationtimestamp' => null ] ),
		];

		$mockDb = $this->getMockDb();
		$mockDb->expects( $this->at( 0 ) )
			->method( 'select' )
			->with(
				'watchlist',
				[
					'wl_user',
					'wl_notificationtimestamp',
				],
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

		$store = $this->newWatchedItemStore( [ 'db' => $mockDb, 'cache' => $mockCache ] );

		$store->duplicateEntry(
			new TitleValue( 0, 'Old_Title' ),
			new TitleValue( 0, 'New_Title' )
		);
	}

	public function testDuplicateAllAssociatedEntries_nothingToDuplicate() {
		$mockDb = $this->getMockDb();
		$mockDb->expects( $this->at( 0 ) )
			->method( 'select' )
			->with(
				'watchlist',
				[
					'wl_user',
					'wl_notificationtimestamp',
				],
				[
					'wl_namespace' => 0,
					'wl_title' => 'Old_Title',
				]
			)
			->will( $this->returnValue( new FakeResultWrapper( [] ) ) );
		$mockDb->expects( $this->at( 1 ) )
			->method( 'select' )
			->with(
				'watchlist',
				[
					'wl_user',
					'wl_notificationtimestamp',
				],
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
			new TitleValue( 0, 'Old_Title' ),
			new TitleValue( 0, 'New_Title' )
		);
	}

	public function provideLinkTargetPairs() {
		return [
			[ new TitleValue( 0, 'Old_Title' ), new TitleValue( 0, 'New_Title' ) ],
			[ new TitleValue( 0, 'Old_Title' ),  new TitleValue( 0, 'New_Title' ) ],
		];
	}

	/**
	 * @dataProvider provideLinkTargetPairs
	 */
	public function testDuplicateAllAssociatedEntries_somethingToDuplicate(
		LinkTarget $oldTarget,
		LinkTarget $newTarget
	) {
		$fakeRows = [
			$this->getFakeRow( [ 'wl_user' => '1', 'wl_notificationtimestamp' => '20151212010101' ] ),
		];

		$mockDb = $this->getMockDb();
		$mockDb->expects( $this->at( 0 ) )
			->method( 'select' )
			->with(
				'watchlist',
				[
					'wl_user',
					'wl_notificationtimestamp',
				],
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
				'watchlist',
				[
					'wl_user',
					'wl_notificationtimestamp',
				],
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

		$store = $this->newWatchedItemStore( [ 'db' => $mockDb, 'cache' => $mockCache ] );

		$store->duplicateAllAssociatedEntries(
			$oldTarget,
			$newTarget
		);
	}

	public function testAddWatch_nonAnonymousUser() {
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
			new UserIdentityValue( 1, 'MockUser', 0 ),
			new TitleValue( 0, 'Some_Page' )
		);
	}

	public function testAddWatch_anonymousUser() {
		$mockDb = $this->getMockDb();
		$mockDb->expects( $this->never() )
			->method( 'insert' );

		$mockCache = $this->getMockCache();
		$mockCache->expects( $this->never() )
			->method( 'delete' );

		$store = $this->newWatchedItemStore( [ 'db' => $mockDb, 'cache' => $mockCache ] );

		$store->addWatch(
			new UserIdentityValue( 0, 'AnonUser', 0 ),
			new TitleValue( 0, 'Some_Page' )
		);
	}

	public function testAddWatchBatchForUser_readOnlyDBReturnsFalse() {
		$store = $this->newWatchedItemStore(
			[ 'readOnlyMode' => $this->getMockReadOnlyMode( true ) ] );

		$this->assertFalse(
			$store->addWatchBatchForUser(
				new UserIdentityValue( 1, 'MockUser', 0 ),
				[ new TitleValue( 0, 'Some_Page' ), new TitleValue( 1, 'Some_Page' ) ]
			)
		);
	}

	public function testAddWatchBatchForUser_nonAnonymousUser() {
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

		$mockUser = new UserIdentityValue( 1, 'MockUser', 0 );

		$this->assertTrue(
			$store->addWatchBatchForUser(
				$mockUser,
				[ new TitleValue( 0, 'Some_Page' ), new TitleValue( 1, 'Some_Page' ) ]
			)
		);
	}

	public function testAddWatchBatchForUser_anonymousUsersAreSkipped() {
		$mockDb = $this->getMockDb();
		$mockDb->expects( $this->never() )
			->method( 'insert' );

		$mockCache = $this->getMockCache();
		$mockCache->expects( $this->never() )
			->method( 'delete' );

		$store = $this->newWatchedItemStore( [ 'db' => $mockDb, 'cache' => $mockCache ] );

		$this->assertFalse(
			$store->addWatchBatchForUser(
				new UserIdentityValue( 0, 'AnonUser', 0 ),
				[ new TitleValue( 0, 'Other_Page' ) ]
			)
		);
	}

	public function testAddWatchBatchReturnsTrue_whenGivenEmptyList() {
		$user = new UserIdentityValue( 1, 'MockUser', 0 );
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

	public function testLoadWatchedItem_existingItem() {
		$mockDb = $this->getMockDb();
		$mockDb->expects( $this->once() )
			->method( 'selectRow' )
			->with(
				'watchlist',
				'wl_notificationtimestamp',
				[
					'wl_user' => 1,
					'wl_namespace' => 0,
					'wl_title' => 'SomeDbKey',
				]
			)
			->will( $this->returnValue(
				$this->getFakeRow( [ 'wl_notificationtimestamp' => '20151212010101' ] )
			) );

		$mockCache = $this->getMockCache();
		$mockCache->expects( $this->once() )
			->method( 'set' )
			->with(
				'0:SomeDbKey:1'
			);

		$store = $this->newWatchedItemStore( [ 'db' => $mockDb, 'cache' => $mockCache ] );

		$watchedItem = $store->loadWatchedItem(
			new UserIdentityValue( 1, 'MockUser', 0 ),
			new TitleValue( 0, 'SomeDbKey' )
		);
		$this->assertInstanceOf( WatchedItem::class, $watchedItem );
		$this->assertEquals( 1, $watchedItem->getUser()->getId() );
		$this->assertEquals( 'SomeDbKey', $watchedItem->getLinkTarget()->getDBkey() );
		$this->assertSame( 0, $watchedItem->getLinkTarget()->getNamespace() );
	}

	public function testLoadWatchedItem_noItem() {
		$mockDb = $this->getMockDb();
		$mockDb->expects( $this->once() )
			->method( 'selectRow' )
			->with(
				'watchlist',
				'wl_notificationtimestamp',
				[
					'wl_user' => 1,
					'wl_namespace' => 0,
					'wl_title' => 'SomeDbKey',
				]
			)
			->will( $this->returnValue( [] ) );

		$mockCache = $this->getMockCache();
		$mockCache->expects( $this->never() )->method( 'get' );
		$mockCache->expects( $this->never() )->method( 'delete' );

		$store = $this->newWatchedItemStore( [ 'db' => $mockDb, 'cache' => $mockCache ] );

		$this->assertFalse(
			$store->loadWatchedItem(
				new UserIdentityValue( 1, 'MockUser', 0 ),
				new TitleValue( 0, 'SomeDbKey' )
			)
		);
	}

	public function testLoadWatchedItem_anonymousUser() {
		$mockDb = $this->getMockDb();
		$mockDb->expects( $this->never() )
			->method( 'selectRow' );

		$mockCache = $this->getMockCache();
		$mockCache->expects( $this->never() )->method( 'get' );
		$mockCache->expects( $this->never() )->method( 'delete' );

		$store = $this->newWatchedItemStore( [ 'db' => $mockDb, 'cache' => $mockCache ] );

		$this->assertFalse(
			$store->loadWatchedItem(
				new UserIdentityValue( 0, 'AnonUser', 0 ),
				new TitleValue( 0, 'SomeDbKey' )
			)
		);
	}

	public function testRemoveWatch_existingItem() {
		$mockDb = $this->getMockDb();
		$mockDb->expects( $this->once() )
			->method( 'delete' )
			->withConsecutive(
				[
					'watchlist',
					[
						'wl_user' => 1,
						'wl_namespace' => 0,
						'wl_title' => [ 'SomeDbKey' ],
					],
				],
				[
					'watchlist',
					[
						'wl_user' => 1,
						'wl_namespace' => 1,
						'wl_title' => [ 'SomeDbKey' ],
					]
				]
			);
		$mockDb->expects( $this->exactly( 1 ) )
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
				new UserIdentityValue( 1, 'MockUser', 0 ),
				new TitleValue( 0, 'SomeDbKey' )
			)
		);
	}

	public function testRemoveWatch_noItem() {
		$mockDb = $this->getMockDb();
		$mockDb->expects( $this->once() )
			->method( 'delete' )
			->withConsecutive(
				[
					'watchlist',
					[
						'wl_user' => 1,
						'wl_namespace' => 0,
						'wl_title' => [ 'SomeDbKey' ],
					]
				],
				[
					'watchlist',
					[
						'wl_user' => 1,
						'wl_namespace' => 1,
						'wl_title' => [ 'SomeDbKey' ],
					]
				]
			);

		$mockDb->expects( $this->once() )
			->method( 'affectedRows' )
			->willReturn( 0 );

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
				new UserIdentityValue( 1, 'MockUser', 0 ),
				new TitleValue( 0, 'SomeDbKey' )
			)
		);
	}

	public function testRemoveWatch_anonymousUser() {
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
				new UserIdentityValue( 0, 'AnonUser', 0 ),
				new TitleValue( 0, 'SomeDbKey' )
			)
		);
	}

	public function testGetWatchedItem_existingItem() {
		$mockDb = $this->getMockDb();
		$mockDb->expects( $this->once() )
			->method( 'selectRow' )
			->with(
				'watchlist',
				'wl_notificationtimestamp',
				[
					'wl_user' => 1,
					'wl_namespace' => 0,
					'wl_title' => 'SomeDbKey',
				]
			)
			->will( $this->returnValue(
				$this->getFakeRow( [ 'wl_notificationtimestamp' => '20151212010101' ] )
			) );

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
			new UserIdentityValue( 1, 'MockUser', 0 ),
			new TitleValue( 0, 'SomeDbKey' )
		);
		$this->assertInstanceOf( WatchedItem::class, $watchedItem );
		$this->assertEquals( 1, $watchedItem->getUser()->getId() );
		$this->assertEquals( 'SomeDbKey', $watchedItem->getLinkTarget()->getDBkey() );
		$this->assertSame( 0, $watchedItem->getLinkTarget()->getNamespace() );
	}

	public function testGetWatchedItem_cachedItem() {
		$mockDb = $this->getMockDb();
		$mockDb->expects( $this->never() )
			->method( 'selectRow' );

		$mockUser = new UserIdentityValue( 1, 'MockUser', 0 );
		$linkTarget = new TitleValue( 0, 'SomeDbKey' );
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

	public function testGetWatchedItem_noItem() {
		$mockDb = $this->getMockDb();
		$mockDb->expects( $this->once() )
			->method( 'selectRow' )
			->with(
				'watchlist',
				'wl_notificationtimestamp',
				[
					'wl_user' => 1,
					'wl_namespace' => 0,
					'wl_title' => 'SomeDbKey',
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
				new UserIdentityValue( 1, 'MockUser', 0 ),
				new TitleValue( 0, 'SomeDbKey' )
			)
		);
	}

	public function testGetWatchedItem_anonymousUser() {
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
				new UserIdentityValue( 0, 'AnonUser', 0 ),
				new TitleValue( 0, 'SomeDbKey' )
			)
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

		$mockCache = $this->getMockCache();
		$mockCache->expects( $this->never() )->method( 'delete' );
		$mockCache->expects( $this->never() )->method( 'get' );
		$mockCache->expects( $this->never() )->method( 'set' );

		$store = $this->newWatchedItemStore( [ 'db' => $mockDb, 'cache' => $mockCache ] );
		$user = new UserIdentityValue( 1, 'MockUser', 0 );

		$watchedItems = $store->getWatchedItemsForUser( $user );

		$this->assertInternalType( 'array', $watchedItems );
		$this->assertCount( 2, $watchedItems );
		foreach ( $watchedItems as $watchedItem ) {
			$this->assertInstanceOf( WatchedItem::class, $watchedItem );
		}
		$this->assertEquals(
			new WatchedItem( $user, new TitleValue( 0, 'Foo1' ), '20151212010101' ),
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
		$user = new UserIdentityValue( 1, 'MockUser', 0 );

		$mockDb->expects( $this->once() )
			->method( 'select' )
			->with(
				'watchlist',
				[ 'wl_namespace', 'wl_title', 'wl_notificationtimestamp' ],
				[ 'wl_user' => 1 ],
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

	public function testGetWatchedItemsForUser_badSortOptionThrowsException() {
		$store = $this->newWatchedItemStore();

		$this->setExpectedException( InvalidArgumentException::class );
		$store->getWatchedItemsForUser(
			new UserIdentityValue( 1, 'MockUser', 0 ),
			[ 'sort' => 'foo' ]
		);
	}

	public function testIsWatchedItem_existingItem() {
		$mockDb = $this->getMockDb();
		$mockDb->expects( $this->once() )
			->method( 'selectRow' )
			->with(
				'watchlist',
				'wl_notificationtimestamp',
				[
					'wl_user' => 1,
					'wl_namespace' => 0,
					'wl_title' => 'SomeDbKey',
				]
			)
			->will( $this->returnValue(
				$this->getFakeRow( [ 'wl_notificationtimestamp' => '20151212010101' ] )
			) );

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
				new UserIdentityValue( 1, 'MockUser', 0 ),
				new TitleValue( 0, 'SomeDbKey' )
			)
		);
	}

	public function testIsWatchedItem_noItem() {
		$mockDb = $this->getMockDb();
		$mockDb->expects( $this->once() )
			->method( 'selectRow' )
			->with(
				'watchlist',
				'wl_notificationtimestamp',
				[
					'wl_user' => 1,
					'wl_namespace' => 0,
					'wl_title' => 'SomeDbKey',
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
				new UserIdentityValue( 1, 'MockUser', 0 ),
				new TitleValue( 0, 'SomeDbKey' )
			)
		);
	}

	public function testIsWatchedItem_anonymousUser() {
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
				new UserIdentityValue( 0, 'AnonUser', 0 ),
				new TitleValue( 0, 'SomeDbKey' )
			)
		);
	}

	public function testGetNotificationTimestampsBatch() {
		$targets = [
			new TitleValue( 0, 'SomeDbKey' ),
			new TitleValue( 1, 'AnotherDbKey' ),
		];

		$mockDb = $this->getMockDb();
		$dbResult = [
			$this->getFakeRow( [
				'wl_namespace' => '0',
				'wl_title' => 'SomeDbKey',
				'wl_notificationtimestamp' => '20151212010101',
			] ),
			$this->getFakeRow(
				[
					'wl_namespace' => '1',
					'wl_title' => 'AnotherDbKey',
					'wl_notificationtimestamp' => null,
				]
			),
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
				new UserIdentityValue( 1, 'MockUser', 0 ), $targets )
		);
	}

	public function testGetNotificationTimestampsBatch_notWatchedTarget() {
		$targets = [
			new TitleValue( 0, 'OtherDbKey' ),
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
			->will( $this->returnValue( $this->getFakeRow( [] ) ) );

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
				new UserIdentityValue( 1, 'MockUser', 0 ), $targets )
		);
	}

	public function testGetNotificationTimestampsBatch_cachedItem() {
		$targets = [
			new TitleValue( 0, 'SomeDbKey' ),
			new TitleValue( 1, 'AnotherDbKey' ),
		];

		$user = new UserIdentityValue( 1, 'MockUser', 0 );
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
				$this->getFakeRow(
					[ 'wl_namespace' => '1', 'wl_title' => 'AnotherDbKey', 'wl_notificationtimestamp' => null, ]
				)
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

	public function testGetNotificationTimestampsBatch_allItemsCached() {
		$targets = [
			new TitleValue( 0, 'SomeDbKey' ),
			new TitleValue( 1, 'AnotherDbKey' ),
		];

		$user = new UserIdentityValue( 1, 'MockUser', 0 );
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

	public function testGetNotificationTimestampsBatch_anonymousUser() {
		if ( defined( 'HHVM_VERSION' ) ) {
			$this->markTestSkipped( 'HHVM Reflection buggy' );
		}

		$targets = [
			new TitleValue( 0, 'SomeDbKey' ),
			new TitleValue( 1, 'AnotherDbKey' ),
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
				new UserIdentityValue( 0, 'AnonUser', 0 ), $targets )
		);
	}

	public function testResetNotificationTimestamp_anonymousUser() {
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
				new UserIdentityValue( 0, 'AnonUser', 0 ),
				new TitleValue( 0, 'SomeDbKey' )
			)
		);
	}

	public function testResetNotificationTimestamp_noItem() {
		$mockDb = $this->getMockDb();
		$mockDb->expects( $this->once() )
			->method( 'selectRow' )
			->with(
				'watchlist',
				'wl_notificationtimestamp',
				[
					'wl_user' => 1,
					'wl_namespace' => 0,
					'wl_title' => 'SomeDbKey',
				]
			)
			->will( $this->returnValue( [] ) );

		$mockCache = $this->getMockCache();
		$mockCache->expects( $this->never() )->method( 'get' );
		$mockCache->expects( $this->never() )->method( 'set' );
		$mockCache->expects( $this->never() )->method( 'delete' );

		$store = $this->newWatchedItemStore( [ 'db' => $mockDb, 'cache' => $mockCache ] );

		$this->assertFalse(
			$store->resetNotificationTimestamp(
				new UserIdentityValue( 1, 'MockUser', 0 ),
				new TitleValue( 0, 'SomeDbKey' )
			)
		);
	}

	public function testResetNotificationTimestamp_item() {
		$user = new UserIdentityValue( 1, 'MockUser', 0 );
		$title = new TitleValue( 0, 'SomeDbKey' );

		$mockDb = $this->getMockDb();
		$mockDb->expects( $this->once() )
			->method( 'selectRow' )
			->with(
				'watchlist',
				'wl_notificationtimestamp',
				[
					'wl_user' => 1,
					'wl_namespace' => 0,
					'wl_title' => 'SomeDbKey',
				]
			)
			->will( $this->returnValue(
				$this->getFakeRow( [ 'wl_notificationtimestamp' => '20151212010101' ] )
			) );

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

		$mockQueueGroup = $this->getMockJobQueueGroup();
		$mockQueueGroup->expects( $this->once() )
			->method( 'lazyPush' )
			->willReturnCallback( function ( ActivityUpdateJob $job ) {
				// don't run
			} );

		// We don't care if these methods actually do anything here
		$mockRevisionLookup = $this->getMockRevisionLookup( [
			'getRevisionByTitle' => function () {
				return null;
			},
			'getTimestampFromId' => function () {
				return '00000000000000';
			},
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

	public function testResetNotificationTimestamp_noItemForced() {
		$user = new UserIdentityValue( 1, 'MockUser', 0 );
		$title = new TitleValue( 0, 'SomeDbKey' );

		$mockDb = $this->getMockDb();
		$mockDb->expects( $this->never() )
			->method( 'selectRow' );

		$mockCache = $this->getMockCache();
		$mockCache->expects( $this->never() )->method( 'get' );
		$mockCache->expects( $this->never() )->method( 'set' );
		$mockCache->expects( $this->once() )
			->method( 'delete' )
			->with( '0:SomeDbKey:1' );

		$mockQueueGroup = $this->getMockJobQueueGroup();

		// We don't care if these methods actually do anything here
		$mockRevisionLookup = $this->getMockRevisionLookup( [
			'getRevisionByTitle' => function () {
				return null;
			},
			'getTimestampFromId' => function () {
				return '00000000000000';
			},
		] );

		$store = $this->newWatchedItemStore( [
			'db' => $mockDb,
			'queueGroup' => $mockQueueGroup,
			'cache' => $mockCache,
			'revisionLookup' => $mockRevisionLookup,
		] );

		$mockQueueGroup->expects( $this->any() )
			->method( 'lazyPush' )
			->will( $this->returnCallback( function ( ActivityUpdateJob $job ) {
				// don't run
			} ) );

		$this->assertTrue(
			$store->resetNotificationTimestamp(
				$user,
				$title,
				'force'
			)
		);
	}

	private function verifyCallbackJob(
		ActivityUpdateJob $job,
		LinkTarget $expectedTitle,
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

	public function testResetNotificationTimestamp_oldidSpecifiedLatestRevisionForced() {
		$user = new UserIdentityValue( 1, 'MockUser', 0 );
		$oldid = 22;
		$title = new TitleValue( 0, 'SomeTitle' );

		$mockDb = $this->getMockDb();
		$mockDb->expects( $this->never() )
			->method( 'selectRow' );

		$mockCache = $this->getMockCache();
		$mockCache->expects( $this->never() )->method( 'get' );
		$mockCache->expects( $this->never() )->method( 'set' );
		$mockCache->expects( $this->once() )
			->method( 'delete' )
			->with( '0:SomeTitle:1' );

		$mockQueueGroup = $this->getMockJobQueueGroup();

		$mockRevisionRecord = $this->createNoOpMock( RevisionRecord::class );

		$mockRevisionLookup = $this->getMockRevisionLookup( [
			'getTimestampFromId' => function () {
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

		$store = $this->newWatchedItemStore( [
			'db' => $mockDb,
			'queueGroup' => $mockQueueGroup,
			'cache' => $mockCache,
			'revisionLookup' => $mockRevisionLookup,
		] );

		$mockQueueGroup->expects( $this->any() )
			->method( 'lazyPush' )
			->will( $this->returnCallback(
				function ( ActivityUpdateJob $job ) use ( $title, $user ) {
					$this->verifyCallbackJob(
						$job,
						$title,
						$user->getId(),
						function ( $time ) {
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

	public function testResetNotificationTimestamp_oldidSpecifiedNotLatestRevisionForced() {
		$user = new UserIdentityValue( 1, 'MockUser', 0 );
		$oldid = 22;
		$title = new TitleValue( 0, 'SomeDbKey' );

		$mockRevision = $this->createNoOpMock( RevisionRecord::class );
		$mockNextRevision = $this->createNoOpMock( RevisionRecord::class );

		$mockDb = $this->getMockDb();
		$mockDb->expects( $this->once() )
			->method( 'selectRow' )
			->with(
				'watchlist',
				'wl_notificationtimestamp',
				[
					'wl_user' => 1,
					'wl_namespace' => 0,
					'wl_title' => 'SomeDbKey',
				]
			)
			->will( $this->returnValue(
				$this->getFakeRow( [ 'wl_notificationtimestamp' => '20151212010101' ] )
			) );

		$mockCache = $this->getMockCache();
		$mockCache->expects( $this->never() )->method( 'get' );
		$mockCache->expects( $this->once() )
			->method( 'set' )
			->with( '0:SomeDbKey:1', $this->isType( 'object' ) );
		$mockCache->expects( $this->once() )
			->method( 'delete' )
			->with( '0:SomeDbKey:1' );

		$mockQueueGroup = $this->getMockJobQueueGroup();

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

		$mockQueueGroup->expects( $this->any() )
			->method( 'lazyPush' )
			->will( $this->returnCallback(
				function ( ActivityUpdateJob $job ) use ( $title, $user ) {
					$this->verifyCallbackJob(
						$job,
						$title,
						$user->getId(),
						function ( $time ) {
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

	public function testResetNotificationTimestamp_notWatchedPageForced() {
		$user = new UserIdentityValue( 1, 'MockUser', 0 );
		$oldid = 22;
		$title = new TitleValue( 0, 'SomeDbKey' );

		$mockDb = $this->getMockDb();
		$mockDb->expects( $this->once() )
			->method( 'selectRow' )
			->with(
				'watchlist',
				'wl_notificationtimestamp',
				[
					'wl_user' => 1,
					'wl_namespace' => 0,
					'wl_title' => 'SomeDbKey',
				]
			)
			->will( $this->returnValue( false ) );

		$mockCache = $this->getMockCache();
		$mockCache->expects( $this->never() )->method( 'get' );
		$mockCache->expects( $this->never() )->method( 'set' );
		$mockCache->expects( $this->once() )
			->method( 'delete' )
			->with( '0:SomeDbKey:1' );

		$mockQueueGroup = $this->getMockJobQueueGroup();

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

		$mockQueueGroup->expects( $this->any() )
			->method( 'lazyPush' )
			->will( $this->returnCallback(
				function ( ActivityUpdateJob $job ) use ( $title, $user ) {
					$this->verifyCallbackJob(
						$job,
						$title,
						$user->getId(),
						function ( $time ) {
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

	public function testResetNotificationTimestamp_futureNotificationTimestampForced() {
		$user = new UserIdentityValue( 1, 'MockUser', 0 );
		$oldid = 22;
		$title = new TitleValue( 0, 'SomeDbKey' );

		$mockDb = $this->getMockDb();
		$mockDb->expects( $this->once() )
			->method( 'selectRow' )
			->with(
				'watchlist',
				'wl_notificationtimestamp',
				[
					'wl_user' => 1,
					'wl_namespace' => 0,
					'wl_title' => 'SomeDbKey',
				]
			)
			->will( $this->returnValue(
				$this->getFakeRow( [ 'wl_notificationtimestamp' => '30151212010101' ] )
			) );

		$mockCache = $this->getMockCache();
		$mockCache->expects( $this->never() )->method( 'get' );
		$mockCache->expects( $this->once() )
			->method( 'set' )
			->with( '0:SomeDbKey:1', $this->isType( 'object' ) );
		$mockCache->expects( $this->once() )
			->method( 'delete' )
			->with( '0:SomeDbKey:1' );

		$mockQueueGroup = $this->getMockJobQueueGroup();

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

		$mockQueueGroup->expects( $this->any() )
			->method( 'lazyPush' )
			->will( $this->returnCallback(
				function ( ActivityUpdateJob $job ) use ( $title, $user ) {
					$this->verifyCallbackJob(
						$job,
						$title,
						$user->getId(),
						function ( $time ) {
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

	public function testResetNotificationTimestamp_futureNotificationTimestampNotForced() {
		$user = new UserIdentityValue( 1, 'MockUser', 0 );
		$oldid = 22;
		$title = new TitleValue( 0, 'SomeDbKey' );

		$mockDb = $this->getMockDb();
		$mockDb->expects( $this->once() )
			->method( 'selectRow' )
			->with(
				'watchlist',
				'wl_notificationtimestamp',
				[
					'wl_user' => 1,
					'wl_namespace' => 0,
					'wl_title' => 'SomeDbKey',
				]
			)
			->will( $this->returnValue(
				$this->getFakeRow( [ 'wl_notificationtimestamp' => '30151212010101' ] )
			) );

		$mockCache = $this->getMockCache();
		$mockCache->expects( $this->never() )->method( 'get' );
		$mockCache->expects( $this->once() )
			->method( 'set' )
			->with( '0:SomeDbKey:1', $this->isType( 'object' ) );
		$mockCache->expects( $this->once() )
			->method( 'delete' )
			->with( '0:SomeDbKey:1' );

		$mockQueueGroup = $this->getMockJobQueueGroup();

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

		$mockQueueGroup->expects( $this->any() )
			->method( 'lazyPush' )
			->will( $this->returnCallback(
				function ( ActivityUpdateJob $job ) use ( $title, $user ) {
					$this->verifyCallbackJob(
						$job,
						$title,
						$user->getId(),
						function ( $time ) {
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
			new UserIdentityValue( 0, 'AnonUser', 0 ), '' ) );
	}

	public function testSetNotificationTimestampsForUser_allRows() {
		$user = new UserIdentityValue( 1, 'MockUser', 0 );
		$timestamp = '20100101010101';

		$store = $this->newWatchedItemStore();

		// Note: This does not actually assert the job is correct
		$callableCallCounter = 0;
		$mockCallback = function ( $callable ) use ( &$callableCallCounter ) {
			$callableCallCounter++;
			$this->assertInternalType( 'callable', $callable );
		};
		$scopedOverride = $store->overrideDeferredUpdatesAddCallableUpdateCallback( $mockCallback );

		$this->assertTrue(
			$store->setNotificationTimestampsForUser( $user, $timestamp )
		);
		$this->assertEquals( 1, $callableCallCounter );
	}

	public function testSetNotificationTimestampsForUser_nullTimestamp() {
		$user = new UserIdentityValue( 1, 'MockUser', 0 );
		$timestamp = null;

		$store = $this->newWatchedItemStore();

		// Note: This does not actually assert the job is correct
		$callableCallCounter = 0;
		$mockCallback = function ( $callable ) use ( &$callableCallCounter ) {
			$callableCallCounter++;
			$this->assertInternalType( 'callable', $callable );
		};
		$scopedOverride = $store->overrideDeferredUpdatesAddCallableUpdateCallback( $mockCallback );

		$this->assertTrue(
			$store->setNotificationTimestampsForUser( $user, $timestamp )
		);
	}

	public function testSetNotificationTimestampsForUser_specificTargets() {
		$user = new UserIdentityValue( 1, 'MockUser', 0 );
		$timestamp = '20100101010101';
		$targets = [ new TitleValue( 0, 'Foo' ), new TitleValue( 0, 'Bar' ) ];

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
			->will( $this->returnCallback( function ( $value ) {
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

	public function testUpdateNotificationTimestamp_watchersExist() {
		$mockDb = $this->getMockDb();
		$mockDb->expects( $this->once() )
			->method( 'selectFieldValues' )
			->with(
				'watchlist',
				'wl_user',
				[
					'wl_user != 1',
					'wl_namespace' => 0,
					'wl_title' => 'SomeDbKey',
					'wl_notificationtimestamp IS NULL'
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

		$this->assertEquals(
			[ 2, 3 ],
			$store->updateNotificationTimestamp(
				new UserIdentityValue( 1, 'MockUser', 0 ),
				new TitleValue( 0, 'SomeDbKey' ),
				'20151212010101'
			)
		);
	}

	public function testUpdateNotificationTimestamp_noWatchers() {
		$mockDb = $this->getMockDb();
		$mockDb->expects( $this->once() )
			->method( 'selectFieldValues' )
			->with(
				'watchlist',
				'wl_user',
				[
					'wl_user != 1',
					'wl_namespace' => 0,
					'wl_title' => 'SomeDbKey',
					'wl_notificationtimestamp IS NULL'
				]
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
			new UserIdentityValue( 1, 'MockUser', 0 ),
			new TitleValue( 0, 'SomeDbKey' ),
			'20151212010101'
		);
		$this->assertInternalType( 'array', $watchers );
		$this->assertEmpty( $watchers );
	}

	public function testUpdateNotificationTimestamp_clearsCachedItems() {
		$user = new UserIdentityValue( 1, 'MockUser', 0 );
		$titleValue = new TitleValue( 0, 'SomeDbKey' );

		$mockDb = $this->getMockDb();
		$mockDb->expects( $this->once() )
			->method( 'selectRow' )
			->will( $this->returnValue(
				$this->getFakeRow( [ 'wl_notificationtimestamp' => '20151212010101' ] )
			) );
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

		$store->updateNotificationTimestamp(
			new UserIdentityValue( 1, 'MockUser', 0 ),
			$titleValue,
			'20151212010101'
		);
	}

}
