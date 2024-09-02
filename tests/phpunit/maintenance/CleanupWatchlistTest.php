<?php

namespace MediaWiki\Tests\Maintenance;

use CleanupWatchlist;
use MediaWiki\MainConfigNames;
use MediaWiki\Tests\Unit\Permissions\MockAuthorityTrait;

/**
 * @covers \CleanupWatchlist
 * @group Database
 */
class CleanupWatchlistTest extends MaintenanceBaseTestCase {
	use MockAuthorityTrait;

	protected function getMaintenanceClass() {
		return CleanupWatchlist::class;
	}

	public static function provideCleanup() {
		return [
			'Invalid title with dry run' => [
				// The value of wl_title for the second watch. null if this should remain valid
				':::',
				// The value of wl_user for the second watch. null if this should remain valid
				null,
				// Whether this is a dry run (i.e. no rows should actually be deleted
				true,
				// The expected row count in the watchlist table after the script has run
				4,
				// The expected row count in the watchlist_expiry table after the script has run
				2,
			],
			'Invalid user ID with dry run' => [ null, 0, true, 4, 2 ],
			'Invalid title' => [ ':::', null, false, 2, 0 ],
			'Invalid user ID' => [ null, 0, false, 2, 0 ],
			'No invalid data' => [ null, null, false, 4, 2 ],
		];
	}

	/** @dataProvider provideCleanup */
	public function testCleanup(
		$title, $userId, $dryRun, $expectedWatchlistRowCountAfterExecution, $expectedWatchlistExpiryRowCountAfterExecution
	) {
		$this->overrideConfigValue( MainConfigNames::WatchlistExpiry, true );
		$existingTestPage = $this->getExistingTestPage();
		// Add a test watchlist entry which will not be broken
		$this->getServiceContainer()->getWatchlistManager()
			->addWatch( $this->getMutableTestUser()->getAuthority(), $existingTestPage );
		// Add another watchlist entry and then break it.
		$testUser1 = $this->getMutableTestUser()->getAuthority();
		$this->getServiceContainer()->getWatchlistManager()
			->addWatch( $testUser1, $existingTestPage, '1 week' );
		$this->getDb()->newUpdateQueryBuilder()
			->update( 'watchlist' )
			->set( [
				'wl_title' => $title ?? $existingTestPage->getDBkey(),
				'wl_user' => $userId ?? $testUser1->getUser()->getId()
			] )
			->where( [
				'wl_user' => $testUser1->getUser()->getId()
			] )
			->caller( __METHOD__ )
			->execute();
		// Check that watchlist_expiry is correctly set up for the test.
		$this->newSelectQueryBuilder()
			->select( 'COUNT(*)' )
			->from( 'watchlist_expiry' )
			->assertFieldValue( 2 );
		// Run the maintenance script and check that the table has been fixed if this is not a dry run
		if ( !$dryRun ) {
			$this->maintenance->setOption( 'fix', 1 );
		}
		$this->maintenance->execute();
		// Check that the DB is as expected after running the script.
		$this->newSelectQueryBuilder()
			->select( 'COUNT(*)' )
			->from( 'watchlist' )
			->assertFieldValue( $expectedWatchlistRowCountAfterExecution );
		$this->newSelectQueryBuilder()
			->select( 'COUNT(*)' )
			->from( 'watchlist_expiry' )
			->assertFieldValue( $expectedWatchlistExpiryRowCountAfterExecution );
	}
}
