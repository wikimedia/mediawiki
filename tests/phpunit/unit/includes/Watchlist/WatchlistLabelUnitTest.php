<?php

use MediaWiki\User\UserIdentityValue;
use MediaWiki\Watchlist\WatchlistLabel;

/**
 * @group Watchlist
 * @covers \MediaWiki\Watchlist\WatchlistLabel
 */
class WatchlistLabelUnitTest extends MediaWikiUnitTestCase {

	/**
	 * Whitespace should be trimmed from the label name.
	 */
	public function testNameIsTrimmed() {
		$user = new UserIdentityValue( 42, 'Doug' );
		$label = new WatchlistLabel( $user, ' Test ' );
		$this->assertSame( 'Test', $label->getName() );
	}

	/**
	 * If a WatchlistLabel has an ID then it can not be changed.
	 */
	public function testIdCanNotBeChanged() {
		$user = new UserIdentityValue( 42, 'Doug' );

		// No ID set.
		$label1 = new WatchlistLabel( $user, 'Lorem' );
		$label1->setId( 7 );

		// ID set in constructor.
		$label2 = new WatchlistLabel( $user, 'Lorem', 4 );
		$this->expectExceptionMessage( 'WatchlistLabel ID can not be changed' );
		$label2->setId( 7 );
	}
}
