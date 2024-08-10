<?php

namespace MediaWiki\Tests\Maintenance;

use CleanupWatchlist;
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
			],
			'Invalid user ID with dry run' => [ null, 0, true, 4 ],
			'Invalid title' => [ ':::', null, false, 2 ],
			'Invalid user ID' => [ null, 0, false, 2 ],
			'No invalid data' => [ null, null, false, 4 ],
		];
	}

	/** @dataProvider provideCleanup */
	public function testCleanup( $title, $userId, $dryRun, $expectedRowCountAfterExecution ) {
		$existingTestPage = $this->getExistingTestPage();
		// Add a test watchlist entry which will not be broken
		$this->getServiceContainer()->getWatchlistManager()
			->addWatch( $this->getMutableTestUser()->getAuthority(), $existingTestPage );
		// Add another watchlist entry and then break it.
		$testUser1 = $this->getMutableTestUser()->getAuthority();
		$this->getServiceContainer()->getWatchlistManager()
			->addWatch( $testUser1, $existingTestPage );
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
		// Run the maintenance script and check that the table has been fixed if this is not a dry run
		if ( !$dryRun ) {
			$this->maintenance->setOption( 'fix', 1 );
		}
		$this->maintenance->execute();
		$this->newSelectQueryBuilder()
			->select( 'COUNT(*)' )
			->from( 'watchlist' )
			->assertFieldValue( $expectedRowCountAfterExecution );
	}
}
