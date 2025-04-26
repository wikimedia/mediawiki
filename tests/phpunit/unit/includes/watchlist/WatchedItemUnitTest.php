<?php

use MediaWiki\Message\Message;
use MediaWiki\Title\TitleValue;
use MediaWiki\User\UserIdentityValue;
use MediaWiki\Watchlist\WatchedItem;
use Wikimedia\Timestamp\ConvertibleTimestamp;

/**
 * @covers \MediaWiki\Watchlist\WatchedItem
 */
class WatchedItemUnitTest extends MediaWikiUnitTestCase {

	public function testIsExpired() {
		$user = new UserIdentityValue( 7, 'MockUser' );
		$target = new TitleValue( 0, 'SomeDbKey' );

		$notExpired1 = new WatchedItem( $user, $target, null, '20500101000000' );
		$this->assertFalse( $notExpired1->isExpired() );

		$notExpired2 = new WatchedItem( $user, $target, null );
		$this->assertFalse( $notExpired2->isExpired() );

		$expired = new WatchedItem( $user, $target, null, '20010101000000' );
		$this->assertTrue( $expired->isExpired() );
	}

	public function testgetExpiryInDays() {
		$user = new UserIdentityValue( 7, 'MockUser' );
		$target = new TitleValue( 0, 'SomeDbKey' );

		// Fake current time to be 2020-05-27T00:00:00Z
		ConvertibleTimestamp::setFakeTime( '20200527000000' );

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
		$this->assertSame( null, $days );
	}

	public function testgetExpiryInDaysText() {
		$user = new UserIdentityValue( 7, 'MockUser' );
		$target = new TitleValue( 0, 'SomeDbKey' );

		$messageLocalizer = $this->createMock( MessageLocalizer::class );
		$messageLocalizer->method( 'msg' )->willReturnCallback(
			function ( $key ) {
				$msg = $this->createMock( Message::class );
				$msg->expects( $this->once() )
					->method( 'numParams' )
					->with(
						$this->callback(
							static function ( $value ) use ( $key, $msg ) {
								$msg->method( "text" )->willReturn( $key . '-' . $value );
								return true;
							} ) )
					->willReturnSelf();
				return $msg;
			}
		);

		// Fake current time to be 2020-05-27T00:00:00Z
		ConvertibleTimestamp::setFakeTime( '20200527000000' );

		// Adding a watched item with an expiry of a month from the frozen time
		$watchedItemMonth = new WatchedItem( $user, $target, null, '20200627000000' );
		$daysRemainingMonthText = $watchedItemMonth->getExpiryInDaysText( $messageLocalizer );
		$this->assertSame( 'watchlist-expiring-days-full-text-31', $daysRemainingMonthText );

		// Adding a watched item with an expiry of 7 days from the frozen time
		$watchedItemWeek = new WatchedItem( $user, $target, null, '20200603000000' );
		$daysRemainingWeekText = $watchedItemWeek->getExpiryInDaysText( $messageLocalizer );
		$this->assertSame( 'watchlist-expiring-days-full-text-7', $daysRemainingWeekText );

		// Show a watched item with an expiry of 7 days from the frozen time in a dropdown option
		$daysRemainingWeekText = $watchedItemWeek->getExpiryInDaysText( $messageLocalizer, true );
		$this->assertSame( 'watchlist-expiry-days-left-7', $daysRemainingWeekText );

		// Adding a watched item with an expiry of 1 day from the frozen time
		$watchedItemDay = new WatchedItem( $user, $target, null, '20200528000000' );
		$daysRemainingDayText = $watchedItemDay->getExpiryInDaysText( $messageLocalizer );
		$this->assertSame( 'watchlist-expiring-days-full-text-1', $daysRemainingDayText );

		// Adding a watched item with an expiry in less than 1 day from the frozen time
		$messageLocalizer = $this->createMock( MessageLocalizer::class );
		$messageLocalizer->method( 'msg' )->willReturnCallback(
			function ( $key, ...$params ) {
				// Right now we aren't worrying about the message formatting,
				// just testing if the correct parameter is there by adding it
				// to the end. Use MediaWikiTestCaseTrait::getMockMessage
				return $this->getMockMessage(
					$params ? ( $key . '-' . $params[0][0] ) : $key
				);
			}
		);
		$watchedItemSoon = new WatchedItem( $user, $target, null, '20200527010001' );
		$daysRemainingSoonText = $watchedItemSoon->getExpiryInDaysText( $messageLocalizer );
		$this->assertSame( 'watchlist-expiring-hours-full-text', $daysRemainingSoonText );

		// Text is for a watchlist expiry dropdown
		$daysRemainingSoonText = $watchedItemSoon->getExpiryInDaysText( $messageLocalizer, true );
		$this->assertSame( 'watchlist-expiry-hours-left', $daysRemainingSoonText );

		// Adding a watched item with no expiry
		$permamentlyWatchedItem = new WatchedItem( $user, $target, null, null );
		$daysText = $permamentlyWatchedItem->getExpiryInDaysText( $messageLocalizer );
		$this->assertSame( '', $daysText );
	}

}
