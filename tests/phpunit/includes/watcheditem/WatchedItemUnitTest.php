<?php

use MediaWiki\User\UserIdentityValue;
use Wikimedia\Timestamp\ConvertibleTimestamp;

/**
 * @covers WatchedItem
 */
class WatchedItemUnitTest extends MediaWikiIntegrationTestCase {

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

	public function testgetExpiryInDays() {
		$user = new UserIdentityValue( 7, 'MockUser', 0 );
		$target = new TitleValue( 0, 'SomeDbKey' );

		// Fake current time to be 2020-05-27T00:00:00Z
		$fakeTime = ConvertibleTimestamp::setFakeTime( '20200527000000' );

		// Adding a watched item with an expiry of a month from the frozen time
		$watchedItemMonth = new WatchedItem( $user, $target, null, '20200627000000' );
		$daysRemainingMonth = $watchedItemMonth->getExpiryInDays();
		$this->assertSame( 31, $daysRemainingMonth );

		// Adding a watched item with an expiry of 7 days from the frozen time
		$watchedItemWeek = new WatchedItem( $user, $target, null, '20200603000000' );
		$daysRemainingWeek = $watchedItemWeek->getExpiryInDays();
		$this->assertSame( 7, $daysRemainingWeek );

		// Adding a watched item with an expiry of 1 day from the frozen time
		$watchedItemDay = new WatchedItem( $user, $target, null, '20200528000000' );
		$daysRemainingDay = $watchedItemDay->getExpiryInDays();
		$this->assertSame( 1, $daysRemainingDay );

		// Adding a watched item with an expiry in less than 1 day from the frozen time
		$watchedItemSoon = new WatchedItem( $user, $target, null, '20200527010001' );
		$daysRemainingSoon = $watchedItemSoon->getExpiryInDays();
		$this->assertSame( 0, $daysRemainingSoon );

		// Adding a watched item with no expiry
		$permamentlyWatchedItem = new WatchedItem( $user, $target, null, null );
		$days = $permamentlyWatchedItem->getExpiryInDays();
		$this->assertSame( 0, $days );
	}

}
