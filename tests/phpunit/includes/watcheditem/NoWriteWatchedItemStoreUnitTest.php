<?php

/**
 * @author Addshore
 *
 * @covers NoWriteWatchedItemStore
 */
class NoWriteWatchedItemStoreUnitTest extends MediaWikiTestCase {

	public function testAddWatch() {
		/** @var WatchedItemStoreInterface|PHPUnit_Framework_MockObject_MockObject $innerService */
		$innerService = $this->getMockForAbstractClass( WatchedItemStoreInterface::class );
		$innerService->expects( $this->never() )->method( 'addWatch' );
		$noWriteService = new NoWriteWatchedItemStore( $innerService );

		$this->setExpectedException( DBReadOnlyError::class );
		$noWriteService->addWatch( $this->getTestSysop()->getUser(), new TitleValue( 0, 'Foo' ) );
	}

	public function testAddWatchBatchForUser() {
		/** @var WatchedItemStoreInterface|PHPUnit_Framework_MockObject_MockObject $innerService */
		$innerService = $this->getMockForAbstractClass( WatchedItemStoreInterface::class );
		$innerService->expects( $this->never() )->method( 'addWatchBatchForUser' );
		$noWriteService = new NoWriteWatchedItemStore( $innerService );

		$this->setExpectedException( DBReadOnlyError::class );
		$noWriteService->addWatchBatchForUser( $this->getTestSysop()->getUser(), [] );
	}

	public function testRemoveWatch() {
		/** @var WatchedItemStoreInterface|PHPUnit_Framework_MockObject_MockObject $innerService */
		$innerService = $this->getMockForAbstractClass( WatchedItemStoreInterface::class );
		$innerService->expects( $this->never() )->method( 'removeWatch' );
		$noWriteService = new NoWriteWatchedItemStore( $innerService );

		$this->setExpectedException( DBReadOnlyError::class );
		$noWriteService->removeWatch( $this->getTestSysop()->getUser(), new TitleValue( 0, 'Foo' ) );
	}

	public function testSetNotificationTimestampsForUser() {
		/** @var WatchedItemStoreInterface|PHPUnit_Framework_MockObject_MockObject $innerService */
		$innerService = $this->getMockForAbstractClass( WatchedItemStoreInterface::class );
		$innerService->expects( $this->never() )->method( 'setNotificationTimestampsForUser' );
		$noWriteService = new NoWriteWatchedItemStore( $innerService );

		$this->setExpectedException( DBReadOnlyError::class );
		$noWriteService->setNotificationTimestampsForUser(
			$this->getTestSysop()->getUser(),
			'timestamp',
			[]
		);
	}

	public function testUpdateNotificationTimestamp() {
		/** @var WatchedItemStoreInterface|PHPUnit_Framework_MockObject_MockObject $innerService */
		$innerService = $this->getMockForAbstractClass( WatchedItemStoreInterface::class );
		$innerService->expects( $this->never() )->method( 'updateNotificationTimestamp' );
		$noWriteService = new NoWriteWatchedItemStore( $innerService );

		$this->setExpectedException( DBReadOnlyError::class );
		$noWriteService->updateNotificationTimestamp(
			$this->getTestSysop()->getUser(),
			new TitleValue( 0, 'Foo' ),
			'timestamp'
		);
	}

	public function testResetNotificationTimestamp() {
		/** @var WatchedItemStoreInterface|PHPUnit_Framework_MockObject_MockObject $innerService */
		$innerService = $this->getMockForAbstractClass( WatchedItemStoreInterface::class );
		$innerService->expects( $this->never() )->method( 'resetNotificationTimestamp' );
		$noWriteService = new NoWriteWatchedItemStore( $innerService );

		$this->setExpectedException( DBReadOnlyError::class );
		$noWriteService->resetNotificationTimestamp(
			$this->getTestSysop()->getUser(),
			Title::newFromText( 'Foo' )
		);
	}

	public function testCountWatchedItems() {
		/** @var WatchedItemStoreInterface|PHPUnit_Framework_MockObject_MockObject $innerService */
		$innerService = $this->getMockForAbstractClass( WatchedItemStoreInterface::class );
		$innerService->expects( $this->once() )->method( 'countWatchedItems' )->willReturn( __METHOD__ );
		$noWriteService = new NoWriteWatchedItemStore( $innerService );

		$return = $noWriteService->countWatchedItems(
			$this->getTestSysop()->getUser()
		);
		$this->assertEquals( __METHOD__, $return );
	}

	public function testCountWatchers() {
		/** @var WatchedItemStoreInterface|PHPUnit_Framework_MockObject_MockObject $innerService */
		$innerService = $this->getMockForAbstractClass( WatchedItemStoreInterface::class );
		$innerService->expects( $this->once() )->method( 'countWatchers' )->willReturn( __METHOD__ );
		$noWriteService = new NoWriteWatchedItemStore( $innerService );

		$return = $noWriteService->countWatchers(
			new TitleValue( 0, 'Foo' )
		);
		$this->assertEquals( __METHOD__, $return );
	}

	public function testCountVisitingWatchers() {
		/** @var WatchedItemStoreInterface|PHPUnit_Framework_MockObject_MockObject $innerService */
		$innerService = $this->getMockForAbstractClass( WatchedItemStoreInterface::class );
		$innerService->expects( $this->once() )
			->method( 'countVisitingWatchers' )
			->willReturn( __METHOD__ );
		$noWriteService = new NoWriteWatchedItemStore( $innerService );

		$return = $noWriteService->countVisitingWatchers(
			new TitleValue( 0, 'Foo' ),
			9
		);
		$this->assertEquals( __METHOD__, $return );
	}

	public function testCountWatchersMultiple() {
		/** @var WatchedItemStoreInterface|PHPUnit_Framework_MockObject_MockObject $innerService */
		$innerService = $this->getMockForAbstractClass( WatchedItemStoreInterface::class );
		$innerService->expects( $this->once() )
			->method( 'countVisitingWatchersMultiple' )
			->willReturn( __METHOD__ );
		$noWriteService = new NoWriteWatchedItemStore( $innerService );

		$return = $noWriteService->countWatchersMultiple(
			[ new TitleValue( 0, 'Foo' ) ],
			[]
		);
		$this->assertEquals( __METHOD__, $return );
	}

	public function testCountVisitingWatchersMultiple() {
		/** @var WatchedItemStoreInterface|PHPUnit_Framework_MockObject_MockObject $innerService */
		$innerService = $this->getMockForAbstractClass( WatchedItemStoreInterface::class );
		$innerService->expects( $this->once() )
			->method( 'countVisitingWatchersMultiple' )
			->willReturn( __METHOD__ );
		$noWriteService = new NoWriteWatchedItemStore( $innerService );

		$return = $noWriteService->countVisitingWatchersMultiple(
			[ [ new TitleValue( 0, 'Foo' ), 99 ] ],
			11
		);
		$this->assertEquals( __METHOD__, $return );
	}

	public function testGetWatchedItem() {
		/** @var WatchedItemStoreInterface|PHPUnit_Framework_MockObject_MockObject $innerService */
		$innerService = $this->getMockForAbstractClass( WatchedItemStoreInterface::class );
		$innerService->expects( $this->once() )->method( 'getWatchedItem' )->willReturn( __METHOD__ );
		$noWriteService = new NoWriteWatchedItemStore( $innerService );

		$return = $noWriteService->getWatchedItem(
			$this->getTestSysop()->getUser(),
			new TitleValue( 0, 'Foo' )
		);
		$this->assertEquals( __METHOD__, $return );
	}

	public function testLoadWatchedItem() {
		/** @var WatchedItemStoreInterface|PHPUnit_Framework_MockObject_MockObject $innerService */
		$innerService = $this->getMockForAbstractClass( WatchedItemStoreInterface::class );
		$innerService->expects( $this->once() )->method( 'loadWatchedItem' )->willReturn( __METHOD__ );
		$noWriteService = new NoWriteWatchedItemStore( $innerService );

		$return = $noWriteService->loadWatchedItem(
			$this->getTestSysop()->getUser(),
			new TitleValue( 0, 'Foo' )
		);
		$this->assertEquals( __METHOD__, $return );
	}

	public function testGetWatchedItemsForUser() {
		/** @var WatchedItemStoreInterface|PHPUnit_Framework_MockObject_MockObject $innerService */
		$innerService = $this->getMockForAbstractClass( WatchedItemStoreInterface::class );
		$innerService->expects( $this->once() )
			->method( 'getWatchedItemsForUser' )
			->willReturn( __METHOD__ );
		$noWriteService = new NoWriteWatchedItemStore( $innerService );

		$return = $noWriteService->getWatchedItemsForUser(
			$this->getTestSysop()->getUser(),
			[]
		);
		$this->assertEquals( __METHOD__, $return );
	}

	public function testIsWatched() {
		/** @var WatchedItemStoreInterface|PHPUnit_Framework_MockObject_MockObject $innerService */
		$innerService = $this->getMockForAbstractClass( WatchedItemStoreInterface::class );
		$innerService->expects( $this->once() )->method( 'isWatched' )->willReturn( __METHOD__ );
		$noWriteService = new NoWriteWatchedItemStore( $innerService );

		$return = $noWriteService->isWatched(
			$this->getTestSysop()->getUser(),
			new TitleValue( 0, 'Foo' )
		);
		$this->assertEquals( __METHOD__, $return );
	}

	public function testGetNotificationTimestampsBatch() {
		/** @var WatchedItemStoreInterface|PHPUnit_Framework_MockObject_MockObject $innerService */
		$innerService = $this->getMockForAbstractClass( WatchedItemStoreInterface::class );
		$innerService->expects( $this->once() )
			->method( 'getNotificationTimestampsBatch' )
			->willReturn( __METHOD__ );
		$noWriteService = new NoWriteWatchedItemStore( $innerService );

		$return = $noWriteService->getNotificationTimestampsBatch(
			$this->getTestSysop()->getUser(),
			[ new TitleValue( 0, 'Foo' ) ]
		);
		$this->assertEquals( __METHOD__, $return );
	}

	public function testCountUnreadNotifications() {
		/** @var WatchedItemStoreInterface|PHPUnit_Framework_MockObject_MockObject $innerService */
		$innerService = $this->getMockForAbstractClass( WatchedItemStoreInterface::class );
		$innerService->expects( $this->once() )
			->method( 'countUnreadNotifications' )
			->willReturn( __METHOD__ );
		$noWriteService = new NoWriteWatchedItemStore( $innerService );

		$return = $noWriteService->countUnreadNotifications(
			$this->getTestSysop()->getUser(),
			88
		);
		$this->assertEquals( __METHOD__, $return );
	}

	public function testDuplicateAllAssociatedEntries() {
		/** @var WatchedItemStoreInterface|PHPUnit_Framework_MockObject_MockObject $innerService */
		$innerService = $this->getMockForAbstractClass( WatchedItemStoreInterface::class );
		$noWriteService = new NoWriteWatchedItemStore( $innerService );

		$this->setExpectedException( DBReadOnlyError::class );
		$noWriteService->duplicateAllAssociatedEntries(
			new TitleValue( 0, 'Foo' ),
			new TitleValue( 0, 'Bar' )
		);
	}

}
