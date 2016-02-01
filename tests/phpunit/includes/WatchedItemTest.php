<?php

/**
 * @author Addshore
 *
 * @covers WatchedItem
 */
class WatchedItemTest extends PHPUnit_Framework_TestCase {

	public function provideTestConstruction() {
		return array(
			array( User::newFromId( 111 ), Title::newFromText( 'SomeTitle' ), null ),
			array( User::newFromId( 111 ), Title::newFromText( 'SomeTitle' ), '20150101010101' ),
		);
	}

	/**
	 * @dataProvider provideTestConstruction
	 */
	public function testConstruction( $user, $title, $notifTimestamp ) {
		$item = new WatchedItem( $user, $title, $notifTimestamp );

		$this->assertSame( $user, $item->getUser() );
		$this->assertSame( $title, $item->getTitle() );
		$this->assertSame( $notifTimestamp, $item->getNotificationTimestamp() );
	}

}
