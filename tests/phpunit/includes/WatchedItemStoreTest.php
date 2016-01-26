<?php

/**
 * @author Addshore
 *
 * @covers WatchedItemStore
 */
class WatchedItemStoreTest extends PHPUnit_Framework_TestCase {

	/**
	 * @return PHPUnit_Framework_MockObject_MockObject|IDatabase
	 */
	private function getMockDb() {
		return $this->getMock( IDatabase::class );
	}

	/**
	 * @return PHPUnit_Framework_MockObject_MockObject|LoadBalancer
	 */
	private function getMockLoadBalancer( $mockDb ) {
		$mock = $this->getMockBuilder( LoadBalancer::class )
			->disableOriginalConstructor()
			->getMock();
		$mock->expects( $this->any() )
			->method( 'getConnection' )
			->will( $this->returnValue( $mockDb ) );
		return $mock;
	}

	/**
	 * @return PHPUnit_Framework_MockObject_MockObject|Config
	 */
	private function getMockConfig() {
		return $this->getMock( 'Config' );
	}

	/**
	 * @return PHPUnit_Framework_MockObject_MockObject|HashBagOStuff
	 */
	private function getMockCache() {
		return $this->getMockBuilder( HashBagOStuff::class )
			->disableOriginalConstructor()
			->getMock();
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

		$store = new WatchedItemStore(
			$this->getMockLoadbalancer( $mockDb ),
			new HashBagOStuff( [ 'maxKeys' => 100 ] ),
			$this->getMockConfig()
		);

		$this->assertEquals( 7, $store->countWatchers( $titleValue ) );
	}

	public function testCountWatchersMultiple() {
		$titleValues = [
			new TitleValue( 0, 'SomeDbKey' ),
			new TitleValue( 0, 'OtherDbKey' ),
			new TitleValue( 1, 'AnotherDbKey' ),
		];

		$mockDb = $this->getMockDb();
		$mockConfig = $this->getMockConfig();

		$dbResult = [
			$this->getFakeRow( [ 'wl_title' => 'SomeDbKey', 'wl_namespace' => 0, 'count' => 100 ] ),
			$this->getFakeRow( [ 'wl_title' => 'OtherDbKey', 'wl_namespace' => 0, 'count' => 300 ] ),
			$this->getFakeRow( [ 'wl_title' => 'AnotherDbKey', 'wl_namespace' => 1, 'count' => 500 ]
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
				[ 'wl_title', 'wl_namespace', 'count' => 'COUNT(*)' ],
				[ 'makeWhereFrom2d return value' ],
				$this->isType( 'string' ),
				[
					'GROUP BY' => [ 'wl_namespace', 'wl_title' ],
					'HAVING' => 'COUNT(*) >= 60',
				]
			)
			->will(
				$this->returnValue( $dbResult )
			);
		$mockConfig->expects( $this->exactly( 1 ) )
			->method( 'get' )
			->with( 'UnwatchedPageThreshold' )
			->will( $this->returnValue( 60 ) );

		$store = new WatchedItemStore(
			$this->getMockLoadbalancer( $mockDb ),
			new HashBagOStuff( [ 'maxKeys' => 100 ] ),
			$mockConfig
		);

		$expected = [
			0 => [ 'SomeDbKey' => 100, 'OtherDbKey' => 300 ],
			1 => [ 'AnotherDbKey' => 500 ],
		];
		$this->assertEquals( $expected, $store->countWatchersMultiple( $titleValues ) );
	}

	public function testDuplicateEntry_nothingToDuplicate() {
		$mockDb = $this->getMockDb();
		$mockDb->expects( $this->once() )
			->method( 'select' )
			->will( $this->returnValue( new FakeResultWrapper( [] ) ) );

		$store = new WatchedItemStore(
			$this->getMockLoadBalancer( $mockDb ),
			new HashBagOStuff( [ 'maxKeys' => 100 ] ),
			$this->getMockConfig()
		);

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

		$store = new WatchedItemStore(
			$this->getMockLoadBalancer( $mockDb ),
			new HashBagOStuff( [ 'maxKeys' => 100 ] ),
			$this->getMockConfig()
		);

		$store->duplicateEntry(
			Title::newFromText( 'Old_Title' ),
			Title::newFromText( 'New_Title' )
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

		$store = new WatchedItemStore(
			$this->getMockLoadBalancer( $mockDb ),
			$mockCache,
			$this->getMockConfig()
		);

		$store->addWatch(
			$this->getMockNonAnonUserWithId( 1 ),
			Title::newFromText( 'Some_Page' )
		);
	}

	public function testAddWatch_anonymousUser() {
		$mockDb = $this->getMockDb();
		$mockDb->expects( $this->never() )
			->method( 'insert' );

		$mockCache = $this->getMockCache();
		$mockCache->expects( $this->never() )
			->method( 'delete' );

		$store = new WatchedItemStore(
			$this->getMockLoadBalancer( $mockDb ),
			$mockCache,
			$this->getMockConfig()
		);

		$store->addWatch(
			$this->getAnonUser(),
			Title::newFromText( 'Some_Page' )
		);
	}

	public function testAddWatchBatch_nonAnonymousUser() {
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

		$mockCache = $this->getMockCache();
		$mockCache->expects( $this->exactly( 2 ) )
			->method( 'delete' );
		$mockCache->expects( $this->at( 0 ) )
			->method( 'delete' )
			->with( '0:Some_Page:1' );
		$mockCache->expects( $this->at( 1 ) )
			->method( 'delete' )
			->with( '1:Some_Page:1' );

		$store = new WatchedItemStore(
			$this->getMockLoadBalancer( $mockDb ),
			$mockCache,
			$this->getMockConfig()
		);

		$mockUser = $this->getMockNonAnonUserWithId( 1 );

		$this->assertTrue(
			$store->addWatchBatch(
				[
					[ $mockUser, new TitleValue( 0, 'Some_Page' ) ],
					[ $mockUser, new TitleValue( 1, 'Some_Page' ) ],
				]
			)
		);
	}

	public function testAddWatchBatch_anonymousUserCombinationsAreSkipped() {
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

		$store = new WatchedItemStore(
			$this->getMockLoadBalancer( $mockDb ),
			$mockCache,
			$this->getMockConfig()
		);

		$this->assertTrue(
			$store->addWatchBatch(
				[
					[ $this->getMockNonAnonUserWithId( 1 ), new TitleValue( 0, 'Some_Page' ) ],
					[ $this->getAnonUser(), new TitleValue( 0, 'Other_Page' ) ],
				]
			)
		);
	}

	public function testAddWatchBatchReturnsFalse_whenOnlyGivenAnonymousUserCombinations() {
		$mockDb = $this->getMockDb();
		$mockDb->expects( $this->never() )
			->method( 'insert' );

		$mockCache = $this->getMockCache();
		$mockCache->expects( $this->never() )
			->method( 'delete' );

		$store = new WatchedItemStore(
			$this->getMockLoadBalancer( $mockDb ),
			$mockCache,
			$this->getMockConfig()
		);

		$anonUser = $this->getAnonUser();
		$this->assertFalse(
			$store->addWatchBatch(
				[
					[ $anonUser, new TitleValue( 0, 'Some_Page' ) ],
					[ $anonUser, new TitleValue( 1, 'Other_Page' ) ],
				]
			)
		);
	}

	public function testAddWatchBatchReturnsFalse_whenGivenEmptyList() {
		$mockDb = $this->getMockDb();
		$mockDb->expects( $this->never() )
			->method( 'insert' );

		$mockCache = $this->getMockCache();
		$mockCache->expects( $this->never() )
			->method( 'delete' );

		$store = new WatchedItemStore(
			$this->getMockLoadBalancer( $mockDb ),
			$mockCache,
			$this->getMockConfig()
		);

		$this->assertFalse(
			$store->addWatchBatch( [] )
		);
	}

	public function testLoadWatchedItem_existingItem() {
		$mockDb = $this->getMockDb();
		$mockDb->expects( $this->once() )
			->method( 'selectRow' )
			->will( $this->returnValue(
				$this->getFakeRow( [ 'wl_notificationtimestamp' => '20151212010101' ] )
			) );

		$mockCache = $this->getMockCache();
		$mockCache->expects( $this->once() )
			->method( 'set' )
			->with(
				'0:SomeDbKey:1'
			);

		$store = new WatchedItemStore(
			$this->getMockLoadBalancer( $mockDb ),
			$mockCache,
			$this->getMockConfig()
		);

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
			->will( $this->returnValue( [] ) );

		$mockCache = $this->getMockCache();
		$mockCache->expects( $this->never() )
			->method( 'delete' );

		$store = new WatchedItemStore(
			$this->getMockLoadBalancer( $mockDb ),
			$mockCache,
			$this->getMockConfig()
		);

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

		$mockCache = $this->getMockCache();
		$mockCache->expects( $this->never() )
			->method( 'delete' );

		$store = new WatchedItemStore(
			$this->getMockLoadBalancer( $mockDb ),
			$mockCache,
			$this->getMockConfig()
		);

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

		$mockCache = $this->getMockCache();
		$mockCache->expects( $this->once() )
			->method( 'delete' )
			->with( '0:SomeDbKey:1' );

		$store = new WatchedItemStore(
			$this->getMockLoadBalancer( $mockDb ),
			$mockCache,
			$this->getMockConfig()
		);

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

		$mockCache = $this->getMockCache();
		$mockCache->expects( $this->once() )
			->method( 'delete' )
			->with( '0:SomeDbKey:1' );

		$store = new WatchedItemStore(
			$this->getMockLoadBalancer( $mockDb ),
			$mockCache,
			$this->getMockConfig()
		);

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

		$mockCache = $this->getMockCache();
		$mockCache->expects( $this->never() )
			->method( 'delete' );

		$store = new WatchedItemStore(
			$this->getMockLoadBalancer( $mockDb ),
			$mockCache,
			$this->getMockConfig()
		);

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
			->will( $this->returnValue(
				$this->getFakeRow( [ 'wl_notificationtimestamp' => '20151212010101' ] )
			) );

		$mockCache = $this->getMockCache();
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

		$store = new WatchedItemStore(
			$this->getMockLoadBalancer( $mockDb ),
			$mockCache,
			$this->getMockConfig()
		);

		$watchedItem = $store->getWatchedItem(
			$this->getMockNonAnonUserWithId( 1 ),
			new TitleValue( 0, 'SomeDbKey' )
		);
		$this->assertInstanceOf( 'WatchedItem', $watchedItem );
		$this->assertEquals( 1, $watchedItem->getUser()->getId() );
		$this->assertEquals( 'SomeDbKey', $watchedItem->getLinkTarget()->getDBkey() );
		$this->assertEquals( 0, $watchedItem->getLinkTarget()->getNamespace() );
	}

	public function testGetWatchedItem_cachedItem() {
		$mockDb = $this->getMockDb();
		$mockDb->expects( $this->never() )
			->method( 'selectRow' );

		$mockUser = $this->getMockNonAnonUserWithId( 1 );
		$linkTarget = new TitleValue( 0, 'SomeDbKey' );
		$cachedItem = new WatchedItem( $mockUser, $linkTarget, '20151212010101' );

		$mockCache = $this->getMockCache();
		$mockCache->expects( $this->once() )
			->method( 'get' )
			->with(
				'0:SomeDbKey:1'
			)
			->will( $this->returnValue( $cachedItem ) );
		$mockCache->expects( $this->never() )
			->method( 'set' );

		$store = new WatchedItemStore(
			$this->getMockLoadBalancer( $mockDb ),
			$mockCache,
			$this->getMockConfig()
		);

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
			->will( $this->returnValue( [] ) );

		$mockCache = $this->getMockCache();
		$mockCache->expects( $this->never() )
			->method( 'set' );

		$store = new WatchedItemStore(
			$this->getMockLoadBalancer( $mockDb ),
			$mockCache,
			$this->getMockConfig()
		);

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

		$mockCache = $this->getMockCache();
		$mockCache->expects( $this->never() )
			->method( 'set' );

		$store = new WatchedItemStore(
			$this->getMockLoadBalancer( $mockDb ),
			$mockCache,
			$this->getMockConfig()
		);

		$this->assertFalse(
			$store->getWatchedItem(
				$this->getAnonUser(),
				new TitleValue( 0, 'SomeDbKey' )
			)
		);
	}

	public function testIsWatchedItem_existingItem() {
		$mockDb = $this->getMockDb();
		$mockDb->expects( $this->once() )
			->method( 'selectRow' )
			->will( $this->returnValue(
				$this->getFakeRow( [ 'wl_notificationtimestamp' => '20151212010101' ] )
			) );

		$mockCache = $this->getMockCache();
		$mockCache->expects( $this->once() )
			->method( 'set' )
			->with(
				'0:SomeDbKey:1'
			);

		$store = new WatchedItemStore(
			$this->getMockLoadBalancer( $mockDb ),
			$mockCache,
			$this->getMockConfig()
		);

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
			->will( $this->returnValue( [] ) );

		$mockCache = $this->getMockCache();
		$mockCache->expects( $this->never() )
			->method( 'set' );

		$store = new WatchedItemStore(
			$this->getMockLoadBalancer( $mockDb ),
			$mockCache,
			$this->getMockConfig()
		);

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

		$mockCache = $this->getMockCache();
		$mockCache->expects( $this->never() )
			->method( 'set' );

		$store = new WatchedItemStore(
			$this->getMockLoadBalancer( $mockDb ),
			$mockCache,
			$this->getMockConfig()
		);

		$this->assertFalse(
			$store->isWatched(
				$this->getAnonUser(),
				new TitleValue( 0, 'SomeDbKey' )
			)
		);
	}

}
