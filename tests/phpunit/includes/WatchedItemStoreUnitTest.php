<?php
use MediaWiki\Linker\LinkTarget;

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
		return $this->getMock( IDatabase::class );
	}

	/**
	 * @return PHPUnit_Framework_MockObject_MockObject|LoadBalancer
	 */
	private function getMockLoadBalancer(
		$mockDb,
		$expectedConnectionType = null,
		$readOnlyReason = false
	) {
		$mock = $this->getMockBuilder( LoadBalancer::class )
			->disableOriginalConstructor()
			->getMock();
		if ( $expectedConnectionType !== null ) {
			$mock->expects( $this->any() )
				->method( 'getConnection' )
				->with( $expectedConnectionType )
				->will( $this->returnValue( $mockDb ) );
		} else {
			$mock->expects( $this->any() )
				->method( 'getConnection' )
				->will( $this->returnValue( $mockDb ) );
		}
		$mock->expects( $this->any() )
			->method( 'getReadOnlyReason' )
			->will( $this->returnValue( $readOnlyReason ) );
		return $mock;
	}

	/**
	 * @param int $id
	 * @return PHPUnit_Framework_MockObject_MockObject|User
	 */
	private function getMockNonAnonUserWithId( $id ) {
		$mock = $this->getMock( User::class );
		$mock->expects( $this->any() )
			->method( 'isAnon' )
			->will( $this->returnValue( false ) );
		$mock->expects( $this->any() )
			->method( 'getId' )
			->will( $this->returnValue( $id ) );
		return $mock;
	}

	/**
	 * @return User
	 */
	private function getAnonUser() {
		return User::newFromName( 'Anon_User' );
	}

	private function getFakeRow( array $rowValues ) {
		$fakeRow = new stdClass();
		foreach ( $rowValues as $valueName => $value ) {
			$fakeRow->$valueName = $value;
		}
		return $fakeRow;
	}

	private function newWatchedItemStore( LoadBalancer $loadBalancer ) {
		return new WatchedItemStore( $loadBalancer );
	}

	public function testCountWatchedItems() {
		$user = $this->getMockNonAnonUserWithId( 1 );

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
			->will( $this->returnValue( 12 ) );

		$store = $this->newWatchedItemStore( $this->getMockLoadBalancer( $mockDb ) );

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
			->will( $this->returnValue( 7 ) );

		$store = $this->newWatchedItemStore( $this->getMockLoadBalancer( $mockDb ) );

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
			$this->getFakeRow( [ 'wl_title' => 'SomeDbKey', 'wl_namespace' => 0, 'watchers' => 100 ] ),
			$this->getFakeRow( [ 'wl_title' => 'OtherDbKey', 'wl_namespace' => 0, 'watchers' => 300 ] ),
			$this->getFakeRow( [ 'wl_title' => 'AnotherDbKey', 'wl_namespace' => 1, 'watchers' => 500 ]
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

		$store = $this->newWatchedItemStore( $this->getMockLoadBalancer( $mockDb ) );

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
			$this->getFakeRow( [ 'wl_title' => 'SomeDbKey', 'wl_namespace' => 0, 'watchers' => 100 ] ),
			$this->getFakeRow( [ 'wl_title' => 'OtherDbKey', 'wl_namespace' => 0, 'watchers' => 300 ] ),
			$this->getFakeRow( [ 'wl_title' => 'AnotherDbKey', 'wl_namespace' => 1, 'watchers' => 500 ]
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

		$store = $this->newWatchedItemStore( $this->getMockLoadBalancer( $mockDb ) );

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
			->will( $this->returnValue( 7 ) );
		$mockDb->expects( $this->exactly( 1 ) )
			->method( 'addQuotes' )
			->will( $this->returnCallback( function( $value ) {
				return "'$value'";
			} ) );
		$mockDb->expects( $this->exactly( 1 ) )
			->method( 'timestamp' )
			->will( $this->returnCallback( function( $value ) {
				return 'TS' . $value . 'TS';
			} ) );

		$store = $this->newWatchedItemStore( $this->getMockLoadBalancer( $mockDb ) );

		$this->assertEquals( 7, $store->countVisitingWatchers( $titleValue, '111' ) );
	}

	public function testCountVisitingWatchersMultiple() {
		$titleValuesWithThresholds = [
			[ new TitleValue( 0, 'SomeDbKey' ), '111' ],
			[ new TitleValue( 0, 'OtherDbKey' ), '111' ],
			[ new TitleValue( 1, 'AnotherDbKey' ), '123' ],
		];

		$dbResult = [
			$this->getFakeRow( [ 'wl_title' => 'SomeDbKey', 'wl_namespace' => 0, 'watchers' => 100 ] ),
			$this->getFakeRow( [ 'wl_title' => 'OtherDbKey', 'wl_namespace' => 0, 'watchers' => 300 ] ),
			$this->getFakeRow( [ 'wl_title' => 'AnotherDbKey', 'wl_namespace' => 1, 'watchers' => 500 ] ),
		];
		$mockDb = $this->getMockDb();
		$mockDb->expects( $this->exactly( 2 * 3 ) )
			->method( 'addQuotes' )
			->will( $this->returnCallback( function( $value ) {
				return "'$value'";
			} ) );
		$mockDb->expects( $this->exactly( 3 ) )
			->method( 'timestamp' )
			->will( $this->returnCallback( function( $value ) {
				return 'TS' . $value . 'TS';
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
			"(((wl_title = 'AnotherDbKey') AND (".
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

		$store = $this->newWatchedItemStore( $this->getMockLoadBalancer( $mockDb ) );

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
			$this->getFakeRow( [ 'wl_title' => 'SomeDbKey', 'wl_namespace' => 0, 'watchers' => 100 ] ),
			$this->getFakeRow( [ 'wl_title' => 'OtherDbKey', 'wl_namespace' => 0, 'watchers' => 300 ] ),
			$this->getFakeRow( [ 'wl_title' => 'AnotherDbKey', 'wl_namespace' => 1, 'watchers' => 500 ] ),
			$this->getFakeRow(
				[ 'wl_title' => 'SomeNotExisitingDbKey', 'wl_namespace' => 0, 'watchers' => 100 ]
			),
			$this->getFakeRow(
				[ 'wl_title' => 'OtherNotExisitingDbKey', 'wl_namespace' => 0, 'watchers' => 200 ]
			),
		];
		$mockDb = $this->getMockDb();
		$mockDb->expects( $this->exactly( 2 * 3 ) )
			->method( 'addQuotes' )
			->will( $this->returnCallback( function( $value ) {
				return "'$value'";
			} ) );
		$mockDb->expects( $this->exactly( 3 ) )
			->method( 'timestamp' )
			->will( $this->returnCallback( function( $value ) {
				return 'TS' . $value . 'TS';
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
			"(((wl_title = 'AnotherDbKey') AND (".
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

		$store = $this->newWatchedItemStore( $this->getMockLoadBalancer( $mockDb ) );

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

		$store = $this->newWatchedItemStore( $this->getMockLoadBalancer( $mockDb ) );

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
		$user = $this->getMockNonAnonUserWithId( 1 );

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
			->will( $this->returnValue( 9 ) );

		$store = $this->newWatchedItemStore( $this->getMockLoadBalancer( $mockDb ) );

		$this->assertEquals( 9, $store->countUnreadNotifications( $user ) );
	}

	/**
	 * @dataProvider provideIntWithDbUnsafeVersion
	 */
	public function testCountUnreadNotifications_withUnreadLimit_overLimit( $limit ) {
		$user = $this->getMockNonAnonUserWithId( 1 );

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
			->will( $this->returnValue( 50 ) );

		$store = $this->newWatchedItemStore( $this->getMockLoadBalancer( $mockDb ) );

		$this->assertSame(
			true,
			$store->countUnreadNotifications( $user, $limit )
		);
	}

	/**
	 * @dataProvider provideIntWithDbUnsafeVersion
	 */
	public function testCountUnreadNotifications_withUnreadLimit_underLimit( $limit ) {
		$user = $this->getMockNonAnonUserWithId( 1 );

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
			->will( $this->returnValue( 9 ) );

		$store = $this->newWatchedItemStore( $this->getMockLoadBalancer( $mockDb ) );

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

		$store = $this->newWatchedItemStore( $this->getMockLoadBalancer( $mockDb ) );

		$store->duplicateEntry(
			Title::newFromText( 'Old_Title' ),
			Title::newFromText( 'New_Title' )
		);
	}

	public function testDuplicateEntry_somethingToDuplicate() {
		$fakeRows = [
			$this->getFakeRow( [ 'wl_user' => 1, 'wl_notificationtimestamp' => '20151212010101' ] ),
			$this->getFakeRow( [ 'wl_user' => 2, 'wl_notificationtimestamp' => null ] ),
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

		$store = $this->newWatchedItemStore( $this->getMockLoadBalancer( $mockDb ) );

		$store->duplicateEntry(
			Title::newFromText( 'Old_Title' ),
			Title::newFromText( 'New_Title' )
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

		$store = $this->newWatchedItemStore( $this->getMockLoadBalancer( $mockDb ) );

		$store->duplicateAllAssociatedEntries(
			Title::newFromText( 'Old_Title' ),
			Title::newFromText( 'New_Title' )
		);
	}

	public function provideLinkTargetPairs() {
		return [
			[ Title::newFromText( 'Old_Title' ), Title::newFromText( 'New_Title' ) ],
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
			$this->getFakeRow( [ 'wl_user' => 1, 'wl_notificationtimestamp' => '20151212010101' ] ),
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

		$store = $this->newWatchedItemStore( $this->getMockLoadBalancer( $mockDb ) );

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

		$store = $this->newWatchedItemStore( $this->getMockLoadBalancer( $mockDb ) );

		$store->addWatch(
			$this->getMockNonAnonUserWithId( 1 ),
			Title::newFromText( 'Some_Page' )
		);
	}

	public function testAddWatch_anonymousUser() {
		$mockDb = $this->getMockDb();
		$mockDb->expects( $this->never() )
			->method( 'insert' );

		$store = $this->newWatchedItemStore(
			$this->getMockLoadBalancer( $mockDb )
		);

		$store->addWatch(
			$this->getAnonUser(),
			Title::newFromText( 'Some_Page' )
		);
	}

	public function testAddWatchBatchForUser_readOnlyDBReturnsFalse() {
		$store = $this->newWatchedItemStore(
			$this->getMockLoadBalancer( $this->getMockDb(), null, 'Some Reason' )
		);

		$this->assertFalse(
			$store->addWatchBatchForUser(
				$this->getMockNonAnonUserWithId( 1 ),
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

		$store = $this->newWatchedItemStore( $this->getMockLoadBalancer( $mockDb ) );

		$mockUser = $this->getMockNonAnonUserWithId( 1 );

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

		$store = $this->newWatchedItemStore( $this->getMockLoadBalancer( $mockDb ) );

		$this->assertFalse(
			$store->addWatchBatchForUser(
				$this->getAnonUser(),
				[ new TitleValue( 0, 'Other_Page' ) ]
			)
		);
	}

	public function testAddWatchBatchReturnsTrue_whenGivenEmptyList() {
		$user = $this->getMockNonAnonUserWithId( 1 );
		$mockDb = $this->getMockDb();
		$mockDb->expects( $this->never() )
			->method( 'insert' );

		$store = $this->newWatchedItemStore( $this->getMockLoadBalancer( $mockDb ) );

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

		$store = $this->newWatchedItemStore( $this->getMockLoadBalancer( $mockDb ) );

		$watchedItem = $store->loadWatchedItem(
			$this->getMockNonAnonUserWithId( 1 ),
			new TitleValue( 0, 'SomeDbKey' )
		);
		$this->assertInstanceOf( 'WatchedItem', $watchedItem );
		$this->assertEquals( 1, $watchedItem->getUser()->getId() );
		$this->assertEquals( 'SomeDbKey', $watchedItem->getLinkTarget()->getDBkey() );
		$this->assertEquals( 0, $watchedItem->getLinkTarget()->getNamespace() );
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

		$store = $this->newWatchedItemStore( $this->getMockLoadBalancer( $mockDb ) );

		$this->assertFalse(
			$store->loadWatchedItem(
				$this->getMockNonAnonUserWithId( 1 ),
				new TitleValue( 0, 'SomeDbKey' )
			)
		);
	}

	public function testLoadWatchedItem_anonymousUser() {
		$mockDb = $this->getMockDb();
		$mockDb->expects( $this->never() )
			->method( 'selectRow' );

		$store = $this->newWatchedItemStore( $this->getMockLoadBalancer( $mockDb ) );

		$this->assertFalse(
			$store->loadWatchedItem(
				$this->getAnonUser(),
				new TitleValue( 0, 'SomeDbKey' )
			)
		);
	}

	public function testRemoveWatch_existingItem() {
		$mockDb = $this->getMockDb();
		$mockDb->expects( $this->once() )
			->method( 'delete' )
			->with(
				'watchlist',
				[
					'wl_user' => 1,
					'wl_namespace' => 0,
					'wl_title' => 'SomeDbKey',
				]
			);
		$mockDb->expects( $this->once() )
			->method( 'affectedRows' )
			->will( $this->returnValue( 1 ) );

		$store = $this->newWatchedItemStore( $this->getMockLoadBalancer( $mockDb ) );

		$this->assertTrue(
			$store->removeWatch(
				$this->getMockNonAnonUserWithId( 1 ),
				new TitleValue( 0, 'SomeDbKey' )
			)
		);
	}

	public function testRemoveWatch_noItem() {
		$mockDb = $this->getMockDb();
		$mockDb->expects( $this->once() )
			->method( 'delete' )
			->with(
				'watchlist',
				[
					'wl_user' => 1,
					'wl_namespace' => 0,
					'wl_title' => 'SomeDbKey',
				]
			);
		$mockDb->expects( $this->once() )
			->method( 'affectedRows' )
			->will( $this->returnValue( 0 ) );

		$store = $this->newWatchedItemStore( $this->getMockLoadBalancer( $mockDb ) );

		$this->assertFalse(
			$store->removeWatch(
				$this->getMockNonAnonUserWithId( 1 ),
				new TitleValue( 0, 'SomeDbKey' )
			)
		);
	}

	public function testRemoveWatch_anonymousUser() {
		$mockDb = $this->getMockDb();
		$mockDb->expects( $this->never() )
			->method( 'delete' );

		$store = $this->newWatchedItemStore( $this->getMockLoadBalancer( $mockDb ) );

		$this->assertFalse(
			$store->removeWatch(
				$this->getAnonUser(),
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

		$store = $this->newWatchedItemStore( $this->getMockLoadBalancer( $mockDb ) );

		$watchedItem = $store->getWatchedItem(
			$this->getMockNonAnonUserWithId( 1 ),
			new TitleValue( 0, 'SomeDbKey' )
		);
		$this->assertInstanceOf( 'WatchedItem', $watchedItem );
		$this->assertEquals( 1, $watchedItem->getUser()->getId() );
		$this->assertEquals( 'SomeDbKey', $watchedItem->getLinkTarget()->getDBkey() );
		$this->assertEquals( 0, $watchedItem->getLinkTarget()->getNamespace() );
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

		$store = $this->newWatchedItemStore( $this->getMockLoadBalancer( $mockDb ) );

		$this->assertFalse(
			$store->getWatchedItem(
				$this->getMockNonAnonUserWithId( 1 ),
				new TitleValue( 0, 'SomeDbKey' )
			)
		);
	}

	public function testGetWatchedItem_anonymousUser() {
		$mockDb = $this->getMockDb();
		$mockDb->expects( $this->never() )
			->method( 'selectRow' );

		$store = $this->newWatchedItemStore( $this->getMockLoadBalancer( $mockDb ) );

		$this->assertFalse(
			$store->getWatchedItem(
				$this->getAnonUser(),
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

		$store = $this->newWatchedItemStore( $this->getMockLoadBalancer( $mockDb ) );
		$user = $this->getMockNonAnonUserWithId( 1 );

		$watchedItems = $store->getWatchedItemsForUser( $user );

		$this->assertInternalType( 'array', $watchedItems );
		$this->assertCount( 2, $watchedItems );
		foreach ( $watchedItems as $watchedItem ) {
			$this->assertInstanceOf( 'WatchedItem', $watchedItem );
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
			[ false, DB_SLAVE ],
			[ true, DB_MASTER ],
		];
	}

	/**
	 * @dataProvider provideDbTypes
	 */
	public function testGetWatchedItemsForUser_optionsAndEmptyResult( $forWrite, $dbType ) {
		$mockDb = $this->getMockDb();
		$mockLoadBalancer = $this->getMockLoadBalancer( $mockDb, $dbType );
		$user = $this->getMockNonAnonUserWithId( 1 );

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

		$store = $this->newWatchedItemStore( $mockLoadBalancer );

		$watchedItems = $store->getWatchedItemsForUser(
			$user,
			[ 'forWrite' => $forWrite, 'sort' => WatchedItemStore::SORT_ASC ]
		);
		$this->assertEquals( [], $watchedItems );
	}

	public function testGetWatchedItemsForUser_badSortOptionThrowsException() {
		$store = $this->newWatchedItemStore( $this->getMockLoadBalancer( $this->getMockDb() ) );

		$this->setExpectedException( 'InvalidArgumentException' );
		$store->getWatchedItemsForUser(
			$this->getMockNonAnonUserWithId( 1 ),
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

		$store = $this->newWatchedItemStore( $this->getMockLoadBalancer( $mockDb ) );

		$this->assertTrue(
			$store->isWatched(
				$this->getMockNonAnonUserWithId( 1 ),
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

		$store = $this->newWatchedItemStore( $this->getMockLoadBalancer( $mockDb ) );

		$this->assertFalse(
			$store->isWatched(
				$this->getMockNonAnonUserWithId( 1 ),
				new TitleValue( 0, 'SomeDbKey' )
			)
		);
	}

	public function testIsWatchedItem_anonymousUser() {
		$mockDb = $this->getMockDb();
		$mockDb->expects( $this->never() )
			->method( 'selectRow' );

		$store = $this->newWatchedItemStore( $this->getMockLoadBalancer( $mockDb ) );

		$this->assertFalse(
			$store->isWatched(
				$this->getAnonUser(),
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
				'wl_namespace' => 0,
				'wl_title' => 'SomeDbKey',
				'wl_notificationtimestamp' => '20151212010101',
			] ),
			$this->getFakeRow(
				[
					'wl_namespace' => 1,
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

		$store = $this->newWatchedItemStore( $this->getMockLoadBalancer( $mockDb ) );

		$this->assertEquals(
			[
				0 => [ 'SomeDbKey' => '20151212010101', ],
				1 => [ 'AnotherDbKey' => null, ],
			],
			$store->getNotificationTimestampsBatch( $this->getMockNonAnonUserWithId( 1 ), $targets )
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

		$store = $this->newWatchedItemStore( $this->getMockLoadBalancer( $mockDb ) );

		$this->assertEquals(
			[
				0 => [ 'OtherDbKey' => false, ],
			],
			$store->getNotificationTimestampsBatch( $this->getMockNonAnonUserWithId( 1 ), $targets )
		);
	}

	public function testGetNotificationTimestampsBatch_anonymousUser() {
		$targets = [
			new TitleValue( 0, 'SomeDbKey' ),
			new TitleValue( 1, 'AnotherDbKey' ),
		];

		$mockDb = $this->getMockDb();
		$mockDb->expects( $this->never() )->method( $this->anything() );

		$store = $this->newWatchedItemStore( $this->getMockLoadBalancer( $mockDb ) );

		$this->assertEquals(
			[
				0 => [ 'SomeDbKey' => false, ],
				1 => [ 'AnotherDbKey' => false, ],
			],
			$store->getNotificationTimestampsBatch( $this->getAnonUser(), $targets )
		);
	}

	public function testResetNotificationTimestamp_anonymousUser() {
		$mockDb = $this->getMockDb();
		$mockDb->expects( $this->never() )
			->method( 'selectRow' );

		$store = $this->newWatchedItemStore( $this->getMockLoadBalancer( $mockDb ) );

		$this->assertFalse(
			$store->resetNotificationTimestamp(
				$this->getAnonUser(),
				Title::newFromText( 'SomeDbKey' )
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

		$store = $this->newWatchedItemStore( $this->getMockLoadBalancer( $mockDb ) );

		$this->assertFalse(
			$store->resetNotificationTimestamp(
				$this->getMockNonAnonUserWithId( 1 ),
				Title::newFromText( 'SomeDbKey' )
			)
		);
	}

	public function testResetNotificationTimestamp_item() {
		$user = $this->getMockNonAnonUserWithId( 1 );
		$title = Title::newFromText( 'SomeDbKey' );

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

		$store = $this->newWatchedItemStore( $this->getMockLoadBalancer( $mockDb ) );

		// Note: This does not actually assert the job is correct
		$callableCallCounter = 0;
		$mockCallback = function( $callable ) use ( &$callableCallCounter ) {
			$callableCallCounter++;
			$this->assertInternalType( 'callable', $callable );
		};
		$scopedOverride = $store->overrideDeferredUpdatesAddCallableUpdateCallback( $mockCallback );

		$this->assertTrue(
			$store->resetNotificationTimestamp(
				$user,
				$title
			)
		);
		$this->assertEquals( 1, $callableCallCounter );

		ScopedCallback::consume( $scopedOverride );
	}

	public function testResetNotificationTimestamp_noItemForced() {
		$user = $this->getMockNonAnonUserWithId( 1 );
		$title = Title::newFromText( 'SomeDbKey' );

		$mockDb = $this->getMockDb();
		$mockDb->expects( $this->never() )
			->method( 'selectRow' );

		$mockDb->expects( $this->never() )
			->method( 'get' );
		$mockDb->expects( $this->never() )
			->method( 'set' );
		$mockDb->expects( $this->never() )
			->method( 'delete' );

		$store = $this->newWatchedItemStore( $this->getMockLoadBalancer( $mockDb ) );

		// Note: This does not actually assert the job is correct
		$callableCallCounter = 0;
		$mockCallback = function( $callable ) use ( &$callableCallCounter ) {
			$callableCallCounter++;
			$this->assertInternalType( 'callable', $callable );
		};
		$scopedOverride = $store->overrideDeferredUpdatesAddCallableUpdateCallback( $mockCallback );

		$this->assertTrue(
			$store->resetNotificationTimestamp(
				$user,
				$title,
				'force'
			)
		);
		$this->assertEquals( 1, $callableCallCounter );

		ScopedCallback::consume( $scopedOverride );
	}

	/**
	 * @param $text
	 * @param int $ns
	 *
	 * @return PHPUnit_Framework_MockObject_MockObject|Title
	 */
	private function getMockTitle( $text, $ns = 0 ) {
		$title = $this->getMock( Title::class );
		$title->expects( $this->any() )
			->method( 'getText' )
			->will( $this->returnValue( str_replace( '_', ' ', $text ) ) );
		$title->expects( $this->any() )
			->method( 'getDbKey' )
			->will( $this->returnValue( str_replace( '_', ' ', $text ) ) );
		$title->expects( $this->any() )
			->method( 'getNamespace' )
			->will( $this->returnValue( $ns ) );
		return $title;
	}

	private function verifyCallbackJob(
		$callback,
		LinkTarget $expectedTitle,
		$expectedUserId,
		callable $notificationTimestampCondition
	) {
		$this->assertInternalType( 'callable', $callback );

		$callbackReflector = new ReflectionFunction( $callback );
		$vars = $callbackReflector->getStaticVariables();
		$this->assertArrayHasKey( 'job', $vars );
		$this->assertInstanceOf( ActivityUpdateJob::class, $vars['job'] );

		/** @var ActivityUpdateJob $job */
		$job = $vars['job'];
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
		$user = $this->getMockNonAnonUserWithId( 1 );
		$oldid = 22;
		$title = $this->getMockTitle( 'SomeTitle' );
		$title->expects( $this->once() )
			->method( 'getNextRevisionID' )
			->with( $oldid )
			->will( $this->returnValue( false ) );

		$mockDb = $this->getMockDb();
		$mockDb->expects( $this->never() )
			->method( 'selectRow' );

		$mockDb->expects( $this->never() )
			->method( 'get' );
		$mockDb->expects( $this->never() )
			->method( 'set' );
		$mockDb->expects( $this->never() )
			->method( 'delete' );

		$store = $this->newWatchedItemStore( $this->getMockLoadBalancer( $mockDb ) );

		$callableCallCounter = 0;
		$scopedOverride = $store->overrideDeferredUpdatesAddCallableUpdateCallback(
			function( $callable ) use ( &$callableCallCounter, $title, $user ) {
				$callableCallCounter++;
				$this->verifyCallbackJob(
					$callable,
					$title,
					$user->getId(),
					function( $time ) {
						return $time === null;
					}
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
		$this->assertEquals( 1, $callableCallCounter );

		ScopedCallback::consume( $scopedOverride );
	}

	public function testResetNotificationTimestamp_oldidSpecifiedNotLatestRevisionForced() {
		$user = $this->getMockNonAnonUserWithId( 1 );
		$oldid = 22;
		$title = $this->getMockTitle( 'SomeDbKey' );
		$title->expects( $this->once() )
			->method( 'getNextRevisionID' )
			->with( $oldid )
			->will( $this->returnValue( 33 ) );

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

		$mockDb->expects( $this->never() )
			->method( 'get' );
		$mockDb->expects( $this->never() )
			->method( 'set' );
		$mockDb->expects( $this->never() )
			->method( 'delete' );

		$store = $this->newWatchedItemStore( $this->getMockLoadBalancer( $mockDb ) );

		$addUpdateCallCounter = 0;
		$scopedOverrideDeferred = $store->overrideDeferredUpdatesAddCallableUpdateCallback(
			function( $callable ) use ( &$addUpdateCallCounter, $title, $user ) {
				$addUpdateCallCounter++;
				$this->verifyCallbackJob(
					$callable,
					$title,
					$user->getId(),
					function( $time ) {
						return $time !== null && $time > '20151212010101';
					}
				);
			}
		);

		$getTimestampCallCounter = 0;
		$scopedOverrideRevision = $store->overrideRevisionGetTimestampFromIdCallback(
			function( $titleParam, $oldidParam ) use ( &$getTimestampCallCounter, $title, $oldid ) {
				$getTimestampCallCounter++;
				$this->assertEquals( $title, $titleParam );
				$this->assertEquals( $oldid, $oldidParam );
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
		$this->assertEquals( 1, $addUpdateCallCounter );
		$this->assertEquals( 1, $getTimestampCallCounter );

		ScopedCallback::consume( $scopedOverrideDeferred );
		ScopedCallback::consume( $scopedOverrideRevision );
	}

	public function testResetNotificationTimestamp_notWatchedPageForced() {
		$user = $this->getMockNonAnonUserWithId( 1 );
		$oldid = 22;
		$title = $this->getMockTitle( 'SomeDbKey' );
		$title->expects( $this->once() )
			->method( 'getNextRevisionID' )
			->with( $oldid )
			->will( $this->returnValue( 33 ) );

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

		$mockDb->expects( $this->never() )
			->method( 'get' );
		$mockDb->expects( $this->never() )
			->method( 'set' );
		$mockDb->expects( $this->never() )
			->method( 'delete' );

		$store = $this->newWatchedItemStore( $this->getMockLoadBalancer( $mockDb ) );

		$callableCallCounter = 0;
		$scopedOverride = $store->overrideDeferredUpdatesAddCallableUpdateCallback(
			function( $callable ) use ( &$callableCallCounter, $title, $user ) {
				$callableCallCounter++;
				$this->verifyCallbackJob(
					$callable,
					$title,
					$user->getId(),
					function( $time ) {
						return $time === null;
					}
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
		$this->assertEquals( 1, $callableCallCounter );

		ScopedCallback::consume( $scopedOverride );
	}

	public function testResetNotificationTimestamp_futureNotificationTimestampForced() {
		$user = $this->getMockNonAnonUserWithId( 1 );
		$oldid = 22;
		$title = $this->getMockTitle( 'SomeDbKey' );
		$title->expects( $this->once() )
			->method( 'getNextRevisionID' )
			->with( $oldid )
			->will( $this->returnValue( 33 ) );

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

		$mockDb->expects( $this->never() )
			->method( 'get' );
		$mockDb->expects( $this->never() )
			->method( 'set' );
		$mockDb->expects( $this->never() )
			->method( 'delete' );

		$store = $this->newWatchedItemStore( $this->getMockLoadBalancer( $mockDb ) );

		$addUpdateCallCounter = 0;
		$scopedOverrideDeferred = $store->overrideDeferredUpdatesAddCallableUpdateCallback(
			function( $callable ) use ( &$addUpdateCallCounter, $title, $user ) {
				$addUpdateCallCounter++;
				$this->verifyCallbackJob(
					$callable,
					$title,
					$user->getId(),
					function( $time ) {
						return $time === '30151212010101';
					}
				);
			}
		);

		$getTimestampCallCounter = 0;
		$scopedOverrideRevision = $store->overrideRevisionGetTimestampFromIdCallback(
			function( $titleParam, $oldidParam ) use ( &$getTimestampCallCounter, $title, $oldid ) {
				$getTimestampCallCounter++;
				$this->assertEquals( $title, $titleParam );
				$this->assertEquals( $oldid, $oldidParam );
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
		$this->assertEquals( 1, $addUpdateCallCounter );
		$this->assertEquals( 1, $getTimestampCallCounter );

		ScopedCallback::consume( $scopedOverrideDeferred );
		ScopedCallback::consume( $scopedOverrideRevision );
	}

	public function testResetNotificationTimestamp_futureNotificationTimestampNotForced() {
		$user = $this->getMockNonAnonUserWithId( 1 );
		$oldid = 22;
		$title = $this->getMockTitle( 'SomeDbKey' );
		$title->expects( $this->once() )
			->method( 'getNextRevisionID' )
			->with( $oldid )
			->will( $this->returnValue( 33 ) );

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

		$mockDb->expects( $this->never() )
			->method( 'get' );
		$mockDb->expects( $this->never() )
			->method( 'set' );
		$mockDb->expects( $this->never() )
			->method( 'delete' );

		$store = $this->newWatchedItemStore( $this->getMockLoadBalancer( $mockDb ) );

		$addUpdateCallCounter = 0;
		$scopedOverrideDeferred = $store->overrideDeferredUpdatesAddCallableUpdateCallback(
			function( $callable ) use ( &$addUpdateCallCounter, $title, $user ) {
				$addUpdateCallCounter++;
				$this->verifyCallbackJob(
					$callable,
					$title,
					$user->getId(),
					function( $time ) {
						return $time === false;
					}
				);
			}
		);

		$getTimestampCallCounter = 0;
		$scopedOverrideRevision = $store->overrideRevisionGetTimestampFromIdCallback(
			function( $titleParam, $oldidParam ) use ( &$getTimestampCallCounter, $title, $oldid ) {
				$getTimestampCallCounter++;
				$this->assertEquals( $title, $titleParam );
				$this->assertEquals( $oldid, $oldidParam );
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
		$this->assertEquals( 1, $addUpdateCallCounter );
		$this->assertEquals( 1, $getTimestampCallCounter );

		ScopedCallback::consume( $scopedOverrideDeferred );
		ScopedCallback::consume( $scopedOverrideRevision );
	}

	public function testSetNotificationTimestampsForUser_anonUser() {
		$store = $this->newWatchedItemStore( $this->getMockLoadBalancer( $this->getMockDb() ) );
		$this->assertFalse( $store->setNotificationTimestampsForUser( $this->getAnonUser(), '' ) );
	}

	public function testSetNotificationTimestampsForUser_allRows() {
		$user = $this->getMockNonAnonUserWithId( 1 );
		$timestamp = '20100101010101';

		$mockDb = $this->getMockDb();
		$mockDb->expects( $this->once() )
			->method( 'update' )
			->with(
				'watchlist',
				[ 'wl_notificationtimestamp' => 'TS' . $timestamp . 'TS' ],
				[ 'wl_user' => 1 ]
			)
			->will( $this->returnValue( true ) );
		$mockDb->expects( $this->exactly( 1 ) )
			->method( 'timestamp' )
			->will( $this->returnCallback( function( $value ) {
				return 'TS' . $value . 'TS';
			} ) );

		$store = $this->newWatchedItemStore( $this->getMockLoadBalancer( $mockDb ) );

		$this->assertTrue(
			$store->setNotificationTimestampsForUser( $user, $timestamp )
		);
	}

	public function testSetNotificationTimestampsForUser_specificTargets() {
		$user = $this->getMockNonAnonUserWithId( 1 );
		$timestamp = '20100101010101';
		$targets = [ new TitleValue( 0, 'Foo' ), new TitleValue( 0, 'Bar' ) ];

		$mockDb = $this->getMockDb();
		$mockDb->expects( $this->once() )
			->method( 'update' )
			->with(
				'watchlist',
				[ 'wl_notificationtimestamp' => 'TS' . $timestamp . 'TS' ],
				[ 'wl_user' => 1, 0 => 'makeWhereFrom2d return value' ]
			)
			->will( $this->returnValue( true ) );
		$mockDb->expects( $this->exactly( 1 ) )
			->method( 'timestamp' )
			->will( $this->returnCallback( function( $value ) {
				return 'TS' . $value . 'TS';
			} ) );
		$mockDb->expects( $this->once() )
			->method( 'makeWhereFrom2d' )
			->with(
				[ [ 'Foo' => 1, 'Bar' => 1 ] ],
				$this->isType( 'string' ),
				$this->isType( 'string' )
			)
			->will( $this->returnValue( 'makeWhereFrom2d return value' ) );

		$store = $this->newWatchedItemStore( $this->getMockLoadBalancer( $mockDb ) );

		$this->assertTrue(
			$store->setNotificationTimestampsForUser( $user, $timestamp, $targets )
		);
	}

	public function testUpdateNotificationTimestamp_watchersExist() {
		$mockDb = $this->getMockDb();
		$mockDb->expects( $this->once() )
			->method( 'select' )
			->with(
				[ 'watchlist' ],
				[ 'wl_user' ],
				[
					'wl_user != 1',
					'wl_namespace' => 0,
					'wl_title' => 'SomeDbKey',
					'wl_notificationtimestamp IS NULL'
				]
			)
			->will(
				$this->returnValue( [
					$this->getFakeRow( [ 'wl_user' => '2' ] ),
					$this->getFakeRow( [ 'wl_user' => '3' ] )
				] )
			);
		$mockDb->expects( $this->once() )
			->method( 'onTransactionIdle' )
			->with( $this->isType( 'callable' ) )
			->will( $this->returnCallback( function( $callable ) {
				$callable();
			} ) );
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

		$store = $this->newWatchedItemStore( $this->getMockLoadBalancer( $mockDb ) );

		$this->assertEquals(
			[ 2, 3 ],
			$store->updateNotificationTimestamp(
				$this->getMockNonAnonUserWithId( 1 ),
				new TitleValue( 0, 'SomeDbKey' ),
				'20151212010101'
			)
		);
	}

	public function testUpdateNotificationTimestamp_noWatchers() {
		$mockDb = $this->getMockDb();
		$mockDb->expects( $this->once() )
			->method( 'select' )
			->with(
				[ 'watchlist' ],
				[ 'wl_user' ],
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
			->method( 'onTransactionIdle' );
		$mockDb->expects( $this->never() )
			->method( 'update' );

		$store = $this->newWatchedItemStore( $this->getMockLoadBalancer( $mockDb ) );

		$watchers = $store->updateNotificationTimestamp(
			$this->getMockNonAnonUserWithId( 1 ),
			new TitleValue( 0, 'SomeDbKey' ),
			'20151212010101'
		);
		$this->assertInternalType( 'array', $watchers );
		$this->assertEmpty( $watchers );
	}

}
