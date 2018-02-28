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

		$ei = SiteStats::edits(); // trigger load
		$pi = SiteStats::pages();
		$ui = SiteStats::users();
		$fi = SiteStats::images();
		$ai = SiteStats::articles();

		$dbw->begin( __METHOD__ ); // block opportunistic updates

		$update = SiteStatsUpdate::factory( [ 'pages' => 2, 'images' => 1, 'edits' => 2 ] );
		$this->assertEquals( 0, DeferredUpdates::pendingUpdatesCount() );
		$update->doUpdate();
		$this->assertEquals( 1, DeferredUpdates::pendingUpdatesCount() );

		// Still the same
		SiteStats::unload();
		$this->assertEquals( $pi, SiteStats::pages(), 'page count' );
		$this->assertEquals( $ei, SiteStats::edits(), 'edit count' );
		$this->assertEquals( $ui, SiteStats::users(), 'user count' );
		$this->assertEquals( $fi, SiteStats::images(), 'file count' );
		$this->assertEquals( $ai, SiteStats::articles(), 'article count' );
		$this->assertEquals( 1, DeferredUpdates::pendingUpdatesCount() );

		$dbw->commit( __METHOD__ );

		$this->assertEquals( 1, DeferredUpdates::pendingUpdatesCount() );
		DeferredUpdates::doUpdates();
		$this->assertEquals( 0, DeferredUpdates::pendingUpdatesCount() );

		SiteStats::unload();
		$this->assertEquals( $pi + 2, SiteStats::pages(), 'page count' );
		$this->assertEquals( $ei + 2, SiteStats::edits(), 'edit count' );
		$this->assertEquals( $ui, SiteStats::users(), 'user count' );
		$this->assertEquals( $fi + 1, SiteStats::images(), 'file count' );
		$this->assertEquals( $ai, SiteStats::articles(), 'article count' );

		$statsInit = new SiteStatsInit();
		$statsInit->refresh();
	}
}
