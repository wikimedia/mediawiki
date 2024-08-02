<?php

use MediaWiki\MainConfigNames;
use MediaWiki\Tests\Maintenance\MaintenanceBaseTestCase;
use Wikimedia\Timestamp\ConvertibleTimestamp;

/**
 * @covers \PurgeExpiredWatchlistItems
 * @group Database
 * @author Dreamy Jazz
 */
class PurgeExpiredWatchlistItemsTest extends MaintenanceBaseTestCase {
	public function getMaintenanceClass() {
		return PurgeExpiredWatchlistItems::class;
	}

	public function testExecuteWhenWatchlistExpiryDisabled() {
		$this->overrideConfigValue( MainConfigNames::WatchlistExpiry, false );
		$this->maintenance->execute();
		$this->expectOutputRegex( '/Watchlist expiry is not enabled.*/' );
	}

	public function testExecuteWhenNoItemsInWatchedItemStore() {
		$this->overrideConfigValue( MainConfigNames::WatchlistExpiry, true );
		$this->maintenance->execute();
		$this->expectOutputRegex( '/0 expired watchlist entries found.*/' );
	}

	/** @dataProvider provideExecuteWhenItemsExpired */
	public function testExecuteWhenItemsExpired( $batchSize, $expiredWatchlistCount, $nonExpiredWatchlistCount ) {
		$this->overrideConfigValue( MainConfigNames::WatchlistExpiry, true );
		$testPage = $this->getExistingTestPage();
		$watchedItemStore = $this->getServiceContainer()->getWatchedItemStore();
		// Add some items that will be expired once we run the maintenance script.
		ConvertibleTimestamp::setFakeTime( '20230405060708' );
		for ( $i = 0; $i < $expiredWatchlistCount; $i++ ) {
			$watchedItemStore->addWatch( $this->getMutableTestUser()->getUserIdentity(), $testPage, '1 month' );
		}
		// Add some items that will not be expired.
		for ( $i = 0; $i < $nonExpiredWatchlistCount; $i++ ) {
			$watchedItemStore->addWatch( $this->getMutableTestUser()->getUserIdentity(), $testPage, '1 year' );
		}
		// Set the fake time to 2 months in advance of the previous take time
		ConvertibleTimestamp::setFakeTime( '20230605060708' );
		// Run the maintenance script
		$this->maintenance->setOption( 'batch-size', $batchSize );
		$this->maintenance->execute();
		$this->expectOutputRegex(
			"/$expiredWatchlistCount expired watchlist entries found.\nAll expired entries purged.\n/"
		);
		$this->assertSame(
			$nonExpiredWatchlistCount,
			$watchedItemStore->countWatchers( $testPage ),
			'The number of watched items after the maintenance script was not as expected.'
		);
	}

	public static function provideExecuteWhenItemsExpired() {
		return [
			'All items expired' => [ 3, 7, 0 ],
			'Some items expired' => [ 2, 3, 3 ],
		];
	}
}
