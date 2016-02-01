<?php

/**
 * @author Addshore
 *
 * @covers WatchedItem
 */
class WatchedItemTest extends PHPUnit_Framework_TestCase {

	public function provideTestConstruction() {
		return [
			[ User::newFromId( 111 ), Title::newFromText( 'SomeTitle' ), null ],
			[ User::newFromId( 111 ), Title::newFromText( 'SomeTitle' ), '20150101010101' ],
		];
	}

	/**
	 * @dataProvider provideTestConstruction
	 */
	public function testConstruction( $user, $title, $notifTimestamp ) {
		$item = new WatchedItem( $user, $title, $notifTimestamp );

		$this->assertSame( $user, $item->getUser() );
		$this->assertSame( $title, $item->getLinkTarget() );
		$this->assertSame( $notifTimestamp, $item->getNotificationTimestamp() );
	}

	public function testGetTitle() {
		// TODO implement me
		$this->markTestIncomplete( 'Not yet implemented' );
	}

	public function testFromUserTitle() {
		// TODO implement me
		$this->markTestIncomplete( 'Not yet implemented' );
	}

	public function testResetNotificationTimestamp() {
		// TODO implement me
		$this->markTestIncomplete( 'Not yet implemented' );
	}

	public function testBatchAddWatch() {
		// TODO implement me
		$this->markTestIncomplete( 'Not yet implemented' );
	}

	public function testAddWatch() {
		// TODO implement me
		$this->markTestIncomplete( 'Not yet implemented' );
	}

	public function testRemoveWatch() {
		// TODO implement me
		$this->markTestIncomplete( 'Not yet implemented' );
	}

	public function testIsWatched() {
		// TODO implement me
		$this->markTestIncomplete( 'Not yet implemented' );
	}

	public function testDuplicateEntries() {
		// TODO implement me
		$this->markTestIncomplete( 'Not yet implemented' );
	}

}
