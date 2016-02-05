<?php

/**
 * @author Addshore
 *
 * @covers WatchedItemStore
 */
class WatchedItemStoreUnitTest extends PHPUnit_Framework_TestCase {

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
		$mock->expects( $this->any() )
			->method( 'getReadOnlyReason' )
			->will( $this->returnValue( false ) );
		return $mock;
	}

	/**
	 * @return PHPUnit_Framework_MockObject_MockObject|BagOStuff
	 */
	private function getMockCache() {
		$mock = $this->getMockBuilder( BagOStuff::class )
			->disableOriginalConstructor()
			->getMock();
		$mock->expects( $this->any() )
			->method( 'makeKey' )
			->will( $this->returnCallback( function() {
				return implode( ':', func_get_args() );
			} ) );
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

	public function testGetDefaultInstance() {
		$instanceOne = WatchedItemStore::getDefaultInstance();
		$instanceTwo = WatchedItemStore::getDefaultInstance();
		$this->assertSame( $instanceOne, $instanceTwo );
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

		$store = new WatchedItemStore(
			$this->getMockLoadBalancer( $mockDb ),
			new HashBagOStuff( [ 'maxKeys' => 100 ] )
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

		$store = new WatchedItemStore(
			$this->getMockLoadBalancer( $mockDb ),
			new HashBagOStuff( [ 'maxKeys' => 100 ] )
		);

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

		$store = new WatchedItemStore(
			$this->getMockLoadBalancer( $mockDb ),
			new HashBagOStuff( [ 'maxKeys' => 100 ] )
		);

		$store->duplicateAllAssociatedEntries(
			Title::newFromText( 'Old_Title' ),
			Title::newFromText( 'New_Title' )
		);
	}

	public function testDuplicateAllAssociatedEntries_somethingToDuplicate() {
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
					'wl_namespace' => 1,
					'wl_title' => 'Old_Title',
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
						'wl_namespace' => 1,
						'wl_title' => 'New_Title',
						'wl_notificationtimestamp' => '20151212010101',
					],
				],
				$this->isType( 'string' )
			);

		$store = new WatchedItemStore(
			$this->getMockLoadBalancer( $mockDb ),
			new HashBagOStuff( [ 'maxKeys' => 100 ] )
		);

		$store->duplicateAllAssociatedEntries(
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
			$mockCache
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
			$mockCache
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
		$mockCache->expects( $this->at( 1 ) )
			->method( 'delete' )
			->with( '0:Some_Page:1' );
		$mockCache->expects( $this->at( 3 ) )
			->method( 'delete' )
			->with( '1:Some_Page:1' );

		$store = new WatchedItemStore(
			$this->getMockLoadBalancer( $mockDb ),
			$mockCache
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
			$mockCache
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
			$mockCache
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
			$mockCache
		);

		$this->assertFalse(
			$store->addWatchBatch( [] )
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

		$store = new WatchedItemStore(
			$this->getMockLoadBalancer( $mockDb ),
			$mockCache
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
		$mockCache->expects( $this->never() )
			->method( 'delete' );

		$store = new WatchedItemStore(
			$this->getMockLoadBalancer( $mockDb ),
			$mockCache
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
			$mockCache
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
			$mockCache
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
			$mockCache
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
			$mockCache
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
			$mockCache
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
			$mockCache
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
		$mockCache->expects( $this->never() )
			->method( 'set' );

		$store = new WatchedItemStore(
			$this->getMockLoadBalancer( $mockDb ),
			$mockCache
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
			$mockCache
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

		$store = new WatchedItemStore(
			$this->getMockLoadBalancer( $mockDb ),
			$mockCache
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
		$mockCache->expects( $this->never() )
			->method( 'set' );

		$store = new WatchedItemStore(
			$this->getMockLoadBalancer( $mockDb ),
			$mockCache
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
			$mockCache
		);

		$this->assertFalse(
			$store->isWatched(
				$this->getAnonUser(),
				new TitleValue( 0, 'SomeDbKey' )
			)
		);
	}

	public function testResetNotificationTimestamp_anonymousUser() {
		$mockDb = $this->getMockDb();
		$mockDb->expects( $this->never() )
			->method( 'selectRow' );

		$mockCache = $this->getMockCache();
		$mockCache->expects( $this->never() )
			->method( 'set' );

		$store = new WatchedItemStore(
			$this->getMockLoadBalancer( $mockDb ),
			$mockCache
		);

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

		$mockCache = $this->getMockCache();
		$mockCache->expects( $this->never() )
			->method( 'set' );

		$store = new WatchedItemStore(
			$this->getMockLoadBalancer( $mockDb ),
			$mockCache
		);

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

		$mockCache = $this->getMockCache();
		$mockCache->expects( $this->once() )
			->method( 'set' )
			->with(
				'0:SomeDbKey:1',
				$this->isInstanceOf( WatchedItem::class )
			);
		$mockCache->expects( $this->once() )
			->method( 'delete' )
			->with( '0:SomeDbKey:1' );

		$store = new WatchedItemStore(
			$this->getMockLoadBalancer( $mockDb ),
			$mockCache
		);

		// Note: This does not actually assert the job is correct
		$callableCallCounter = 0;
		$mockCallback = function( $callable ) use ( &$callableCallCounter ) {
			$callableCallCounter++;
			$this->assertInternalType( 'callable', $callable );
		};
		$store->overrideDeferredUpdatesAddCallableUpdateCallback( $mockCallback );

		$this->assertTrue(
			$store->resetNotificationTimestamp(
				$user,
				$title
			)
		);
		$this->assertEquals( 1, $callableCallCounter );
	}

	public function testResetNotificationTimestamp_noItemForced() {
		$user = $this->getMockNonAnonUserWithId( 1 );
		$title = Title::newFromText( 'SomeDbKey' );

		$mockDb = $this->getMockDb();
		$mockDb->expects( $this->never() )
			->method( 'selectRow' );

		$mockCache = $this->getMockCache();
		$mockDb->expects( $this->never() )
			->method( 'set' );
		$mockDb->expects( $this->never() )
			->method( 'delete' );

		$store = new WatchedItemStore(
			$this->getMockLoadBalancer( $mockDb ),
			$mockCache
		);

		// Note: This does not actually assert the job is correct
		$callableCallCounter = 0;
		$mockCallback = function( $callable ) use ( &$callableCallCounter ) {
			$callableCallCounter++;
			$this->assertInternalType( 'callable', $callable );
		};
		$store->overrideDeferredUpdatesAddCallableUpdateCallback( $mockCallback );

		$this->assertTrue(
			$store->resetNotificationTimestamp(
				$user,
				$title,
				'force'
			)
		);
		$this->assertEquals( 1, $callableCallCounter );
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

		$mockCache = $this->getMockCache();
		$mockDb->expects( $this->never() )
			->method( 'set' );
		$mockDb->expects( $this->never() )
			->method( 'delete' );

		$store = new WatchedItemStore(
			$this->getMockLoadBalancer( $mockDb ),
			$mockCache
		);

		// Note: This does not actually assert the job is correct
		$callableCallCounter = 0;
		$store->overrideDeferredUpdatesAddCallableUpdateCallback(
			function( $callable ) use ( &$callableCallCounter ) {
				$callableCallCounter++;
				$this->assertInternalType( 'callable', $callable );
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

		$mockCache = $this->getMockCache();
		$mockDb->expects( $this->never() )
			->method( 'set' );
		$mockDb->expects( $this->never() )
			->method( 'delete' );

		$store = new WatchedItemStore(
			$this->getMockLoadBalancer( $mockDb ),
			$mockCache
		);

		// Note: This does not actually assert the job is correct
		$addUpdateCallCounter = 0;
		$store->overrideDeferredUpdatesAddCallableUpdateCallback(
			function( $callable ) use ( &$addUpdateCallCounter ) {
				$addUpdateCallCounter++;
				$this->assertInternalType( 'callable', $callable );
			}
		);

		$getTimestampCallCounter = 0;
		$store->overrideRevisionGetTimestampFromIdCallback(
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

		$store = new WatchedItemStore(
			$this->getMockLoadBalancer( $mockDb ),
			new HashBagOStuff( [ 'maxKeys' => 100 ] )
		);

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

		$store = new WatchedItemStore(
			$this->getMockLoadBalancer( $mockDb ),
			new HashBagOStuff( [ 'maxKeys' => 100 ] )
		);

		$watchers = $store->updateNotificationTimestamp(
			$this->getMockNonAnonUserWithId( 1 ),
			new TitleValue( 0, 'SomeDbKey' ),
			'20151212010101'
		);
		$this->assertInternalType( 'array', $watchers );
		$this->assertEmpty( $watchers );
	}

}
