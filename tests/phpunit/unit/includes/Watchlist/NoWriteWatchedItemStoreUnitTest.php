<?php

use MediaWiki\Page\PageIdentityValue;
use MediaWiki\Page\PageReferenceValue;
use MediaWiki\Title\TitleValue;
use MediaWiki\User\UserIdentityValue;
use MediaWiki\Watchlist\NoWriteWatchedItemStore;
use MediaWiki\Watchlist\WatchedItemStoreInterface;
use Wikimedia\Rdbms\DBReadOnlyError;

/**
 * @author Addshore
 *
 * @covers \MediaWiki\Watchlist\NoWriteWatchedItemStore
 */
class NoWriteWatchedItemStoreUnitTest extends \MediaWikiUnitTestCase {

	private function getNoWriteStoreForErrors(): NoWriteWatchedItemStore {
		// NoWriteWatchedItemStore where the inner actual store should never be called,
		// because we are testing the methods that throw exceptions instead
		// We could do a fancy constrant for never having a method that matches the
		// specific list, but since we don't use this for the cases that we have the
		// inner actual store do anything, it should never be used
		$innerService = $this->createNoOpAbstractMock( WatchedItemStoreInterface::class );
		return new NoWriteWatchedItemStore( $innerService );
	}

	/**
	 * @param string $method
	 * @param mixed $result
	 * @return NoWriteWatchedItemStore
	 */
	private function getNoWriteStoreForProxyCall( string $method, $result ): NoWriteWatchedItemStore {
		// NoWriteWatchedItemStore where the inner actual store is used a single time
		// for a method call
		$innerService = $this->createNoOpAbstractMock(
			WatchedItemStoreInterface::class,
			[ $method ]
		);
		$innerService->expects( $this->once() )->method( $method )->willReturn( $result );
		return new NoWriteWatchedItemStore( $innerService );
	}

	public function testAddWatch() {
		$noWriteService = $this->getNoWriteStoreForErrors();

		$this->expectException( DBReadOnlyError::class );
		$noWriteService->addWatch(
			new UserIdentityValue( 1, 'MockUser' ),
			PageReferenceValue::localReference( 0, 'Foo' )
		);
	}

	public function testAddWatchBatchForUser() {
		$noWriteService = $this->getNoWriteStoreForErrors();

		$this->expectException( DBReadOnlyError::class );
		$noWriteService->addWatchBatchForUser( new UserIdentityValue( 1, 'MockUser' ), [] );
	}

	public function testRemoveWatch() {
		$noWriteService = $this->getNoWriteStoreForErrors();

		$this->expectException( DBReadOnlyError::class );
		$noWriteService->removeWatch(
			new UserIdentityValue( 1, 'MockUser' ),
			PageReferenceValue::localReference( 0, 'Foo' )
		);
	}

	public function testSetNotificationTimestampsForUser() {
		$noWriteService = $this->getNoWriteStoreForErrors();

		$this->expectException( DBReadOnlyError::class );
		$noWriteService->setNotificationTimestampsForUser(
			new UserIdentityValue( 1, 'MockUser' ),
			'timestamp',
			[]
		);
	}

	public function testUpdateNotificationTimestamp() {
		$noWriteService = $this->getNoWriteStoreForErrors();

		$this->expectException( DBReadOnlyError::class );
		$noWriteService->updateNotificationTimestamp(
			new UserIdentityValue( 1, 'MockUser' ),
			PageReferenceValue::localReference( 0, 'Foo' ),
			'timestamp'
		);
	}

	public function testResetNotificationTimestamp() {
		$noWriteService = $this->getNoWriteStoreForErrors();

		$this->expectException( DBReadOnlyError::class );
		$noWriteService->resetNotificationTimestamp(
			new UserIdentityValue( 1, 'MockUser' ),
			PageIdentityValue::localIdentity( 1, 0, 'Foo' )
		);
	}

	public function testCountWatchedItems() {
		$noWriteService = $this->getNoWriteStoreForProxyCall( 'countWatchedItems', __METHOD__ );

		$return = $noWriteService->countWatchedItems(
			new UserIdentityValue( 1, 'MockUser' )
		);
		$this->assertEquals( __METHOD__, $return );
	}

	public function testCountWatchers() {
		$noWriteService = $this->getNoWriteStoreForProxyCall( 'countWatchers', __METHOD__ );

		$return = $noWriteService->countWatchers(
			PageReferenceValue::localReference( 0, 'Foo' )
		);
		$this->assertEquals( __METHOD__, $return );
	}

	public function testCountVisitingWatchers() {
		$noWriteService = $this->getNoWriteStoreForProxyCall( 'countVisitingWatchers', __METHOD__ );

		$return = $noWriteService->countVisitingWatchers(
			PageReferenceValue::localReference( 0, 'Foo' ),
			9
		);
		$this->assertEquals( __METHOD__, $return );
	}

	public function testCountWatchersMultiple() {
		$noWriteService = $this->getNoWriteStoreForProxyCall( 'countWatchersMultiple', __METHOD__ );

		$return = $noWriteService->countWatchersMultiple(
			[ PageReferenceValue::localReference( 0, 'Foo' ) ],
			[]
		);
		$this->assertEquals( __METHOD__, $return );
	}

	public function testCountVisitingWatchersMultiple() {
		$noWriteService = $this->getNoWriteStoreForProxyCall( 'countVisitingWatchersMultiple', __METHOD__ );

		$return = $noWriteService->countVisitingWatchersMultiple(
			[ [ PageReferenceValue::localReference( 0, 'Foo' ), 99 ] ],
			11
		);
		$this->assertEquals( __METHOD__, $return );
	}

	public function testGetWatchedItem() {
		$noWriteService = $this->getNoWriteStoreForProxyCall( 'getWatchedItem', __METHOD__ );

		$return = $noWriteService->getWatchedItem(
			new UserIdentityValue( 1, 'MockUser' ),
			PageReferenceValue::localReference( 0, 'Foo' )
		);
		$this->assertEquals( __METHOD__, $return );
	}

	public function testLoadWatchedItem() {
		$noWriteService = $this->getNoWriteStoreForProxyCall( 'loadWatchedItem', __METHOD__ );

		$return = $noWriteService->loadWatchedItem(
			new UserIdentityValue( 1, 'MockUser' ),
			PageReferenceValue::localReference( 0, 'Foo' )
		);
		$this->assertEquals( __METHOD__, $return );
	}

	public function testGetWatchedItemsForUser() {
		$noWriteService = $this->getNoWriteStoreForProxyCall( 'getWatchedItemsForUser', __METHOD__ );

		$return = $noWriteService->getWatchedItemsForUser(
			new UserIdentityValue( 1, 'MockUser' ),
			[]
		);
		$this->assertEquals( __METHOD__, $return );
	}

	public function testIsWatched() {
		$noWriteService = $this->getNoWriteStoreForProxyCall( 'isWatched', __METHOD__ );

		$return = $noWriteService->isWatched(
			new UserIdentityValue( 1, 'MockUser' ),
			PageReferenceValue::localReference( 0, 'Foo' )
		);
		$this->assertEquals( __METHOD__, $return );
	}

	public function testGetNotificationTimestampsBatch() {
		$noWriteService = $this->getNoWriteStoreForProxyCall( 'getNotificationTimestampsBatch', __METHOD__ );

		$return = $noWriteService->getNotificationTimestampsBatch(
			new UserIdentityValue( 1, 'MockUser' ),
			[ new TitleValue( 0, 'Foo' ) ]
		);
		$this->assertEquals( __METHOD__, $return );
	}

	public function testCountUnreadNotifications() {
		$noWriteService = $this->getNoWriteStoreForProxyCall( 'countUnreadNotifications', __METHOD__ );

		$return = $noWriteService->countUnreadNotifications(
			new UserIdentityValue( 1, 'MockUser' ),
			88
		);
		$this->assertEquals( __METHOD__, $return );
	}

	public function testDuplicateAllAssociatedEntries() {
		$noWriteService = $this->getNoWriteStoreForErrors();

		$this->expectException( DBReadOnlyError::class );
		$noWriteService->duplicateAllAssociatedEntries(
			PageReferenceValue::localReference( 0, 'Foo' ),
			PageReferenceValue::localReference( 0, 'Bar' )
		);
	}

}
