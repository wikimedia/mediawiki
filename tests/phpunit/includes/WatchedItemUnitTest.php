<?php
use MediaWiki\Linker\LinkTarget;

/**
 * @author Addshore
 *
 * @covers WatchedItem
 */
class WatchedItemUnitTest extends PHPUnit_Framework_TestCase {

	public function provideUserTitleTimestamp() {
		return [
			[ User::newFromId( 111 ), Title::newFromText( 'SomeTitle' ), null ],
			[ User::newFromId( 111 ), Title::newFromText( 'SomeTitle' ), '20150101010101' ],
			[ User::newFromId( 111 ), new TitleValue( 0, 'TVTitle', 'frag' ), '20150101010101' ],
		];
	}

	/**
	 * @return PHPUnit_Framework_MockObject_MockObject|WatchedItemStore
	 */
	private function getMockWatchedItemStore() {
		return $this->getMockBuilder( WatchedItemStore::class )
			->disableOriginalConstructor()
			->getMock();
	}

	/**
	 * @dataProvider provideUserTitleTimestamp
	 */
	public function testConstruction( $user, LinkTarget $linkTarget, $notifTimestamp ) {
		$item = new WatchedItem( $user, $linkTarget, $notifTimestamp );

		$this->assertSame( $user, $item->getUser() );
		$this->assertSame( $linkTarget, $item->getLinkTarget() );
		$this->assertSame( $notifTimestamp, $item->getNotificationTimestamp() );

		// The below tests the internal WatchedItem::getTitle method
		$this->assertInstanceOf( 'Title', $item->getTitle() );
		$this->assertSame( $linkTarget->getDBkey(), $item->getTitle()->getDBkey() );
		$this->assertSame( $linkTarget->getFragment(), $item->getTitle()->getFragment() );
		$this->assertSame( $linkTarget->getNamespace(), $item->getTitle()->getNamespace() );
		$this->assertSame( $linkTarget->getText(), $item->getTitle()->getText() );
	}

	/**
	 * @dataProvider provideUserTitleTimestamp
	 */
	public function testFromUserTitle( $user, $linkTarget, $timestamp ) {
		$store = $this->getMockWatchedItemStore();
		$store->expects( $this->once() )
			->method( 'loadWatchedItem' )
			->with( $user, $linkTarget )
			->will( $this->returnValue( new WatchedItem( $user, $linkTarget, $timestamp ) ) );
		$scopedOverride = WatchedItemStore::overrideDefaultInstance( $store );

		$item = WatchedItem::fromUserTitle( $user, $linkTarget, User::IGNORE_USER_RIGHTS );

		$this->assertEquals( $user, $item->getUser() );
		$this->assertEquals( $linkTarget, $item->getLinkTarget() );
		$this->assertEquals( $timestamp, $item->getNotificationTimestamp() );

		ScopedCallback::consume( $scopedOverride );
	}

	/**
	 * @dataProvider provideUserTitleTimestamp
	 */
	public function testResetNotificationTimestamp( $user, $linkTarget, $timestamp ) {
		$force = 'XXX';
		$oldid = 999;

		$store = $this->getMockWatchedItemStore();
		$store->expects( $this->once() )
			->method( 'resetNotificationTimestamp' )
			->with( $user, $this->isInstanceOf( Title::class ), $force, $oldid )
			->will( $this->returnCallback(
				function ( $user, Title $title, $force, $oldid ) use ( $linkTarget ) {
					/** @var LinkTarget $linkTarget */
					$this->assertInstanceOf( 'Title', $title );
					$this->assertSame( $linkTarget->getDBkey(), $title->getDBkey() );
					$this->assertSame( $linkTarget->getFragment(), $title->getFragment() );
					$this->assertSame( $linkTarget->getNamespace(), $title->getNamespace() );
					$this->assertSame( $linkTarget->getText(), $title->getText() );

					return true;
				}
			) );
		$scopedOverride = WatchedItemStore::overrideDefaultInstance( $store );

		$item = new WatchedItem( $user, $linkTarget, $timestamp );
		$item->resetNotificationTimestamp( $force, $oldid );

		ScopedCallback::consume( $scopedOverride );
	}

	public function testAddWatch() {
		$title = Title::newFromText( 'SomeTitle' );
		$timestamp = null;
		$checkRights = 0;

		/** @var User|PHPUnit_Framework_MockObject_MockObject $user */
		$user = $this->getMock( User::class );
		$user->expects( $this->once() )
			->method( 'addWatch' )
			->with( $title, $checkRights );

		$item = new WatchedItem( $user, $title, $timestamp, $checkRights );
		$this->assertTrue( $item->addWatch() );
	}

	public function testRemoveWatch() {
		$title = Title::newFromText( 'SomeTitle' );
		$timestamp = null;
		$checkRights = 0;

		/** @var User|PHPUnit_Framework_MockObject_MockObject $user */
		$user = $this->getMock( User::class );
		$user->expects( $this->once() )
			->method( 'removeWatch' )
			->with( $title, $checkRights );

		$item = new WatchedItem( $user, $title, $timestamp, $checkRights );
		$this->assertTrue( $item->removeWatch() );
	}

	public function provideBooleans() {
		return [
			[ true ],
			[ false ],
		];
	}

	/**
	 * @dataProvider provideBooleans
	 */
	public function testIsWatched( $returnValue ) {
		$title = Title::newFromText( 'SomeTitle' );
		$timestamp = null;
		$checkRights = 0;

		/** @var User|PHPUnit_Framework_MockObject_MockObject $user */
		$user = $this->getMock( User::class );
		$user->expects( $this->once() )
			->method( 'isWatched' )
			->with( $title, $checkRights )
			->will( $this->returnValue( $returnValue ) );

		$item = new WatchedItem( $user, $title, $timestamp, $checkRights );
		$this->assertEquals( $returnValue, $item->isWatched() );
	}

	public function testDuplicateEntries() {
		$oldTitle = Title::newFromText( 'OldTitle' );
		$newTitle = Title::newFromText( 'NewTitle' );

		$store = $this->getMockWatchedItemStore();
		$store->expects( $this->once() )
			->method( 'duplicateAllAssociatedEntries' )
			->with( $oldTitle, $newTitle );
		$scopedOverride = WatchedItemStore::overrideDefaultInstance( $store );

		WatchedItem::duplicateEntries( $oldTitle, $newTitle );

		ScopedCallback::consume( $scopedOverride );
	}

	public function testBatchAddWatch() {
		$itemOne = new WatchedItem( User::newFromId( 1 ), new TitleValue( 0, 'Title1' ), null );
		$itemTwo = new WatchedItem(
			User::newFromId( 3 ),
			Title::newFromText( 'Title2' ),
			'20150101010101'
		);

		$store = $this->getMockWatchedItemStore();
		$store->expects( $this->exactly( 2 ) )
			->method( 'addWatchBatchForUser' );
		$store->expects( $this->at( 0 ) )
			->method( 'addWatchBatchForUser' )
			->with(
				$itemOne->getUser(),
				[
					$itemOne->getTitle()->getSubjectPage(),
					$itemOne->getTitle()->getTalkPage(),
				]
			);
		$store->expects( $this->at( 1 ) )
			->method( 'addWatchBatchForUser' )
			->with(
				$itemTwo->getUser(),
				[
					$itemTwo->getTitle()->getSubjectPage(),
					$itemTwo->getTitle()->getTalkPage(),
				]
			);
		$scopedOverride = WatchedItemStore::overrideDefaultInstance( $store );

		WatchedItem::batchAddWatch( [ $itemOne, $itemTwo ] );

		ScopedCallback::consume( $scopedOverride );
	}

}
