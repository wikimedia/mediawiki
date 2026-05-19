<?php

namespace MediaWiki\Tests\Maintenance;

use CleanupWatchlistLabelMember;
use MediaWiki\MainConfigNames;
use MediaWiki\Page\PageReferenceValue;
use MediaWiki\Watchlist\WatchlistLabel;

/**
 * @covers \CleanupWatchlistLabelMember
 * @group Database
 */
class CleanupWatchlistLabelMemberTest extends MaintenanceBaseTestCase {

	protected function getMaintenanceClass() {
		return CleanupWatchlistLabelMember::class;
	}

	public function testCleanup(): void {
		$this->overrideConfigValue( MainConfigNames::EnableWatchlistLabels, true );
		$user = $this->getTestUser()->getUser();
		$itemStore = $this->getServiceContainer()->getWatchedItemStore();
		$labelStore = $this->getServiceContainer()->getWatchlistLabelStore();

		// Watch 4 pages.
		$pageSubject1 = PageReferenceValue::localReference( NS_HELP, 'Test page' );
		$pageTalk1 = PageReferenceValue::localReference( NS_HELP_TALK, 'Test page' );
		$pageSubject2 = PageReferenceValue::localReference( NS_HELP, 'Test page 2' );
		$pageTalk2 = PageReferenceValue::localReference( NS_HELP_TALK, 'Test page 2' );
		$itemStore->addWatchBatchForUser( $user, [ $pageTalk1, $pageSubject1, $pageSubject2, $pageTalk2 ] );

		// Label 3 of them.
		$label = new WatchlistLabel( $user, 'Test label' );
		$labelStore->save( $label );
		$itemStore->addLabels( $user, [ $pageTalk1, $pageSubject1, $pageSubject2 ], [ $label ] );

		// Test that the missing page's label is added.
		$this->assertSame( 3, $this->watchlistLabelMemberCount() );
		// Force the run; another test in the same phpunit process may have
		// already logged this updater (e.g. DatabaseSqliteUpgradeTest), which
		// would otherwise short-circuit doDBUpdates()
		$this->maintenance->setForce();
		$this->maintenance->execute();
		$this->assertSame( 4, $this->watchlistLabelMemberCount() );
	}

	private function watchlistLabelMemberCount(): int {
		return $this->getDb()->newSelectQueryBuilder()
			->field( 'COUNT(*)' )
			->table( 'watchlist_label_member' )
			->caller( __METHOD__ )
			->fetchField();
	}
}
