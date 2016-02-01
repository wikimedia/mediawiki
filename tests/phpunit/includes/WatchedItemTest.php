<?php

/**
 * @author Addshore
 *
 * @covers WatchedItem
 */
class WatchedItemTest extends PHPUnit_Framework_TestCase {

	public function provideTestConstruction() {
		return array(
			array( User::newFromId( 111 ), Title::newFromText( 'SomeTitle' ), false, false ),
			array( User::newFromId( 111 ), Title::newFromText( 'SomeTitle' ), '20150101010101', true ),
		);
	}

	/**
	 * @dataProvider provideTestConstruction
	 */
	public function testConstruction( $user, $title, $notifTimestamp, $isWatched ) {
		$item = new WatchedItem( $user, $title, $notifTimestamp, $isWatched );

		$this->assertSame( $user, $item->getUser() );
		$this->assertSame( $title, $item->getTitle() );
		$this->assertSame( $notifTimestamp, $item->getNotificationTimestamp() );
		$this->assertSame( $isWatched, $item->isWatched() );
	}

}
