<?php

use Wikimedia\TestingAccessWrapper;

/**
 * @group Database
 */
class SiteStatsUpdateTest extends MediaWikiTestCase {
	/**
	 * @covers SiteStatsUpdate::factory
	 * @covers SiteStatsUpdate::merge
	 */
	public function testFactoryAndMerge() {
		$update1 = SiteStatsUpdate::factory( [ 'pages' => 1, 'users' => 2 ] );
		$update2 = SiteStatsUpdate::factory( [ 'users' => 1, 'images' => 1 ] );

		$update1->merge( $update2 );
		$wrapped = TestingAccessWrapper::newFromObject( $update1 );

		$this->assertEquals( 1, $wrapped->pages );
		$this->assertEquals( 3, $wrapped->users );
		$this->assertEquals( 1, $wrapped->images );
		$this->assertEquals( 0, $wrapped->edits );
		$this->assertEquals( 0, $wrapped->articles );
	}

	/**
	 * @covers SiteStatsUpdate::doUpdate()
	 * @covers SiteStatsInit::refresh()
	 */
	public function testDoUpdate() {
		$this->setMwGlobals( 'wgSiteStatsAsyncFactor', false );
		$this->setMwGlobals( 'wgCommandLineMode', false ); // disable opportunistic updates

		$dbw = wfGetDB( DB_MASTER );
		$statsInit = new SiteStatsInit( $dbw );
		$statsInit->refresh();

		SiteStats::edits(); // trigger load

		// Basic test page exists
		$this->assertEquals( 1, SiteStats::pages(), 'page count' );
		$this->assertEquals( 1, SiteStats::edits(), 'edit count' );
		$this->assertEquals( 1, SiteStats::users(), 'user count' );
		$this->assertEquals( 0, SiteStats::images(), 'file count' );

		$dbw->begin( __METHOD__ ); // block opportunistic updates

		$update = SiteStatsUpdate::factory( [ 'pages' => 1, 'images' => 1, 'edits' => 1 ] );
		$this->assertEquals( 0, DeferredUpdates::pendingUpdatesCount() );
		$update->doUpdate();
		$this->assertEquals( 1, DeferredUpdates::pendingUpdatesCount() );

		// Still the same
		SiteStats::unload();
		$this->assertEquals( 1, SiteStats::pages(), 'page count' );
		$this->assertEquals( 1, SiteStats::edits(), 'edit count' );
		$this->assertEquals( 1, SiteStats::users(), 'user count' );
		$this->assertEquals( 0, SiteStats::images(), 'file count' );
		$this->assertEquals( 1, DeferredUpdates::pendingUpdatesCount() );

		$dbw->commit( __METHOD__ );

		$this->assertEquals( 1, DeferredUpdates::pendingUpdatesCount() );
		DeferredUpdates::doUpdates();
		$this->assertEquals( 0, DeferredUpdates::pendingUpdatesCount() );

		SiteStats::unload();
		$this->assertEquals( 2, SiteStats::pages(), 'page count' );
		$this->assertEquals( 2, SiteStats::edits(), 'edit count' );
		$this->assertEquals( 1, SiteStats::users(), 'user count' );
		$this->assertEquals( 1, SiteStats::images(), 'file count' );
	}
}
