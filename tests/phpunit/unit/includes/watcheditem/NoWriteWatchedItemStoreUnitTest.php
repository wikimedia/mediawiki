<?php

use MediaWiki\User\UserIdentityValue;
use PHPUnit\Framework\MockObject\MockObject;

/**
 * @author Addshore
 *
 * @covers NoWriteWatchedItemStore
 */
class NoWriteWatchedItemStoreUnitTest extends \MediaWikiUnitTestCase {

	public function testAddWatch() {
		/** @var WatchedItemStoreInterface|MockObject $innerService */
		$innerService = $this->getMockForAbstractClass( WatchedItemStoreInterface::class );
		$innerService->expects( $this->never() )->method( 'addWatch' );
		$noWriteService = new NoWriteWatchedItemStore( $innerService );

		$this->expectException( DBReadOnlyError::class );
		$noWriteService->addWatch(
			new UserIdentityValue( 1, 'MockUser', 0 ), new TitleValue( 0, 'Foo' ) );
	}

	public function testAddWatchBatchForUser() {
		/** @var WatchedItemStoreInterface|MockObject $innerService */
		$innerService = $this->getMockForAbstractClass( WatchedItemStoreInterface::class );
		$innerService->expects( $this->never() )->method( 'addWatchBatchForUser' );
		$noWriteService = new NoWriteWatchedItemStore( $innerService );

		$this->expectException( DBReadOnlyError::class );
		$noWriteService->addWatchBatchForUser( new UserIdentityValue( 1, 'MockUser', 0 ), [] );
	}

	public function testRemoveWatch() {
		/** @var WatchedItemStoreInterface|MockObject $innerService */
		$innerService = $this->getMockForAbstractClass( WatchedItemStoreInterface::class );
		$innerService->expects( $this->never() )->method( 'removeWatch' );
		$noWriteService = new NoWriteWatchedItemStore( $innerService );

		$this->expectException( DBReadOnlyError::class );
		$noWriteService->removeWatch(
			new UserIdentityValue( 1, 'MockUser', 0 ), new TitleValue( 0, 'Foo' ) );
	}

	public function testSetNotificationTimestampsForUser() {
		/** @var WatchedItemStoreInterface|MockObject $innerService */
		$innerService = $this->getMockForAbstractClass( WatchedItemStoreInterface::class );
		$innerService->expects( $this->never() )->method( 'setNotificationTimestampsForUser' );
		$noWriteService = new NoWriteWatchedItemStore( $innerService );

		$this->expectException( DBReadOnlyError::class );
		$noWriteService->setNotificationTimestampsForUser(
			new UserIdentityValue( 1, 'MockUser', 0 ),
			'timestamp',
			[]
		);
	}

	public function testUpdateNotificationTimestamp() {
		/** @var WatchedItemStoreInterface|MockObject $innerService */
		$innerService = $this->getMockForAbstractClass( WatchedItemStoreInterface::class );
		$innerService->expects( $this->never() )->method( 'updateNotificationTimestamp' );
		$noWriteService = new NoWriteWatchedItemStore( $innerService );

		$this->expectException( DBReadOnlyError::class );
		$noWriteService->updateNotificationTimestamp(
			new UserIdentityValue( 1, 'MockUser', 0 ),
			new TitleValue( 0, 'Foo' ),
			'timestamp'
		);
	}

	public function testResetNotificationTimestamp() {
		/** @var WatchedItemStoreInterface|MockObject $innerService */
		$innerService = $this->getMockForAbstractClass( WatchedItemStoreInterface::class );
		$innerService->expects( $this->never() )->method( 'resetNotificationTimestamp' );
		$noWriteService = new NoWriteWatchedItemStore( $innerService );

		$this->expectException( DBReadOnlyError::class );
		$noWriteService->resetNotificationTimestamp(
			new UserIdentityValue( 1, 'MockUser', 0 ),
			new TitleValue( 0, 'Foo' )
		);
	}

	public function testCountWatchedItems() {
		/** @var WatchedItemStoreInterface|MockObject $innerService */
		$innerService = $this->getMockForAbstractClass( WatchedItemStoreInterface::class );
		$innerService->expects( $this->once() )->method( 'countWatchedItems' )->willReturn( __METHOD__ );
		$noWriteService = new NoWriteWatchedItemStore( $innerService );

		$return = $noWriteService->countWatchedItems(
			new UserIdentityValue( 1, 'MockUser', 0 )
		);
		$this->assertEquals( __METHOD__, $return );
	}

	public function testCountWatchers() {
		/** @var WatchedItemStoreInterface|MockObject $innerService */
		$innerService = $this->getMockForAbstractClass( WatchedItemStoreInterface::class );
		$innerService->expects( $this->once() )->method( 'countWatchers' )->willReturn( __METHOD__ );
		$noWriteService = new NoWriteWatchedItemStore( $innerService );

		$return = $noWriteService->countWatchers(
			new TitleValue( 0, 'Foo' )
		);
		$this->assertEquals( __METHOD__, $return );
	}

	public function testCountVisitingWatchers() {
		/** @var WatchedItemStoreInterface|MockObject $innerService */
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
		/** @var WatchedItemStoreInterface|MockObject $innerService */
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
		/** @var WatchedItemStoreInterface|MockObject $innerService */
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
		/** @var WatchedItemStoreInterface|MockObject $innerService */
		$innerService = $this->getMockForAbstractClass( WatchedItemStoreInterface::class );
		$innerService->expects( $this->once() )->method( 'getWatchedItem' )->willReturn( __METHOD__ );
		$noWriteService = new NoWriteWatchedItemStore( $innerService );

		$return = $noWriteService->getWatchedItem(
			new UserIdentityValue( 1, 'MockUser', 0 ),
			new TitleValue( 0, 'Foo' )
		);
		$this->assertEquals( __METHOD__, $return );
	}

	public function testLoadWatchedItem() {
		/** @var WatchedItemStoreInterface|MockObject $innerService */
		$innerService = $this->getMockForAbstractClass( WatchedItemStoreInterface::class );
		$innerService->expects( $this->once() )->method( 'loadWatchedItem' )->willReturn( __METHOD__ );
		$noWriteService = new NoWriteWatchedItemStore( $innerService );

		$return = $noWriteService->loadWatchedItem(
			new UserIdentityValue( 1, 'MockUser', 0 ),
			new TitleValue( 0, 'Foo' )
		);
		$this->assertEquals( __METHOD__, $return );
	}

	public function testGetWatchedItemsForUser() {
		/** @var WatchedItemStoreInterface|MockObject $innerService */
		$innerService = $this->getMockForAbstractClass( WatchedItemStoreInterface::class );
		$innerService->expects( $this->once() )
			->method( 'getWatchedItemsForUser' )
			->willReturn( __METHOD__ );
		$noWriteService = new NoWriteWatchedItemStore( $innerService );

		$return = $noWriteService->getWatchedItemsForUser(
			new UserIdentityValue( 1, 'MockUser', 0 ),
			[]
		);
		$this->assertEquals( __METHOD__, $return );
	}

	public function testIsWatched() {
		/** @var WatchedItemStoreInterface|MockObject $innerService */
		$innerService = $this->getMockForAbstractClass( WatchedItemStoreInterface::class );
		$innerService->expects( $this->once() )->method( 'isWatched' )->willReturn( __METHOD__ );
		$noWriteService = new NoWriteWatchedItemStore( $innerService );

		$return = $noWriteService->isWatched(
			new UserIdentityValue( 1, 'MockUser', 0 ),
			new TitleValue( 0, 'Foo' )
		);
		$this->assertEquals( __METHOD__, $return );
	}

	public function testGetNotificationTimestampsBatch() {
		/** @var WatchedItemStoreInterface|MockObject $innerService */
		$innerService = $this->getMockForAbstractClass( WatchedItemStoreInterface::class );
		$innerService->expects( $this->once() )
			->method( 'getNotificationTimestampsBatch' )
			->willReturn( __METHOD__ );
		$noWriteService = new NoWriteWatchedItemStore( $innerService );

		$return = $noWriteService->getNotificationTimestampsBatch(
			new UserIdentityValue( 1, 'MockUser', 0 ),
			[ new TitleValue( 0, 'Foo' ) ]
		);
		$this->assertEquals( __METHOD__, $return );
	}

	public function testCountUnreadNotifications() {
		/** @var WatchedItemStoreInterface|MockObject $innerService */
		$innerService = $this->getMockForAbstractClass( WatchedItemStoreInterface::class );
		$innerService->expects( $this->once() )
			->method( 'countUnreadNotifications' )
			->willReturn( __METHOD__ );
		$noWriteService = new NoWriteWatchedItemStore( $innerService );

		$return = $noWriteService->countUnreadNotifications(
			new UserIdentityValue( 1, 'MockUser', 0 ),
			88
		);
		$this->assertEquals( __METHOD__, $return );
	}

	public function testDuplicateAllAssociatedEntries() {
		/** @var WatchedItemStoreInterface|MockObject $innerService */
		$innerService = $this->getMockForAbstractClass( WatchedItemStoreInterface::class );
		$noWriteService = new NoWriteWatchedItemStore( $innerService );

		$this->expectException( DBReadOnlyError::class );
		$noWriteService->duplicateAllAssociatedEntries(
			new TitleValue( 0, 'Foo' ),
			new TitleValue( 0, 'Bar' )
		);
	}

}
