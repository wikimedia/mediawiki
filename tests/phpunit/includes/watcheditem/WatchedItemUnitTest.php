<?php

use MediaWiki\User\UserIdentityValue;

/**
 * @covers WatchedItem
 */
class WatchedItemUnitTest extends MediaWikiTestCase {

	public function testIsExpired() {
		$user = new UserIdentityValue( 7, 'MockUser', 0 );
		$target = new TitleValue( 0, 'SomeDbKey' );

		$notExpired1 = new WatchedItem( $user, $target, null, '20500101000000' );
		$this->assertFalse( $notExpired1->isExpired() );

		$notExpired2 = new WatchedItem( $user, $target, null );
		$this->assertFalse( $notExpired2->isExpired() );

		$expired = new WatchedItem( $user, $target, null, '20010101000000' );
		$this->assertTrue( $expired->isExpired() );
	}

	public function testNormalizeExpiry() {
		$this->assertNull( WatchedItem::normalizeExpiry( null ) );
		$this->assertSame(
			'infinity',
			WatchedItem::normalizeExpiry( 'indefinite' )
		);
		$this->assertSame(
			'20500101000000',
			WatchedItem::normalizeExpiry( '2050-01-01 00:00' )
		);
	}
}
