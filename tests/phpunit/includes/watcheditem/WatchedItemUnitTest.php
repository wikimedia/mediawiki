<?php
use MediaWiki\Linker\LinkTarget;

/**
 * @author Addshore
 *
 * @covers WatchedItem
 */
class WatchedItemUnitTest extends MediaWikiTestCase {

	/**
	 * @param int $id
	 *
	 * @return PHPUnit_Framework_MockObject_MockObject|User
	 */
	private function getMockUser( $id ) {
		$user = $this->createMock( User::class );
		$user->expects( $this->any() )
			->method( 'getId' )
			->will( $this->returnValue( $id ) );
		$user->expects( $this->any() )
			->method( 'isAllowed' )
			->will( $this->returnValue( true ) );
		return $user;
	}

	public function provideUserTitleTimestamp() {
		$user = $this->getMockUser( 111 );
		return [
			[ $user, Title::newFromText( 'SomeTitle' ), null ],
			[ $user, Title::newFromText( 'SomeTitle' ), '20150101010101' ],
			[ $user, new TitleValue( 0, 'TVTitle', 'frag' ), '20150101010101' ],
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
		$this->setService( 'WatchedItemStore', $store );

		$item = WatchedItem::fromUserTitle( $user, $linkTarget, User::IGNORE_USER_RIGHTS );

		$this->assertEquals( $user, $item->getUser() );
		$this->assertEquals( $linkTarget, $item->getLinkTarget() );
		$this->assertEquals( $timestamp, $item->getNotificationTimestamp() );
	}

	public function testAddWatch() {
		$title = Title::newFromText( 'SomeTitle' );
		$timestamp = null;
		$checkRights = 0;

		/** @var User|PHPUnit_Framework_MockObject_MockObject $user */
		$user = $this->createMock( User::class );
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
		$user = $this->createMock( User::class );
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
		$user = $this->createMock( User::class );
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
		$this->setService( 'WatchedItemStore', $store );

		WatchedItem::duplicateEntries( $oldTitle, $newTitle );
	}

}
