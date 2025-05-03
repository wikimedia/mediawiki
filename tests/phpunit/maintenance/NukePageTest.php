<?php

namespace MediaWiki\Tests\Maintenance;

use NukePage;

/**
 * @covers \NukePage
 * @group Database
 * @group Maintenance
 */
class NukePageTest extends MaintenanceBaseTestCase {

	public function getMaintenanceClass() {
		return NukePage::class;
	}

	public function testExecuteWhenPageDoesNotExist() {
		$this->expectOutputRegex( '/Searching for "Non-existing-test-page"[\s\S]*not found in database/' );
		$this->maintenance->setArg( 'title', 'Non-existing-test-page' );
		$this->maintenance->execute();
	}

	public function testExecuteWhenPageExistsButInDryRun() {
		$page = $this->getExistingTestPage();
		$pageTitle = $page->getTitle()->getPrefixedText();
		$revId = $page->getRevisionRecord()->getId();

		$this->maintenance->setArg( 'title', $pageTitle );
		$this->maintenance->execute();

		// Check that the script has found the page but does not delete it.
		$actualOutput = $this->getActualOutputForAssertion();
		$this->assertStringContainsString( "Searching for \"$pageTitle\"", $actualOutput );
		$this->assertStringContainsString(
			"found \"$pageTitle\" with ID {$page->getId()}", $actualOutput
		);
		$this->assertStringContainsString( "Searching for revisions...found 1", $actualOutput );
		$this->assertStringNotContainsString( "Deleting page record...", $actualOutput );

		$page->clear();
		$this->assertTrue( $page->exists() );
		$this->newSelectQueryBuilder()
			->select( 'COUNT(*)' )
			->from( 'revision' )
			->where( [ 'rev_id' => $revId ] )
			->caller( __METHOD__ )
			->assertFieldValue( 1 );
	}

	public function testExecuteWhenPageExistsAndDeleting() {
		$page = $this->getExistingTestPage();
		$pageTitle = $page->getTitle()->getPrefixedText();
		$revId = $page->getRevisionRecord()->getId();

		$this->maintenance->setOption( 'delete', 1 );
		$this->maintenance->setArg( 'title', $pageTitle );
		$this->maintenance->execute();

		// Assert that the script wiped the page from the DB
		$actualOutput = $this->getActualOutputForAssertion();
		$this->assertStringContainsString( "Searching for \"$pageTitle\"", $actualOutput );
		$this->assertStringContainsString(
			"found \"$pageTitle\" with ID {$page->getId()}", $actualOutput
		);
		$this->assertStringContainsString( 'Deleting page record...done', $actualOutput );
		$this->assertStringContainsString( 'Cleaning up recent changes...done', $actualOutput );
		$this->assertStringContainsString( 'Deleting revisions...done', $actualOutput );
		$this->assertStringContainsString( 'Updating site stats...done', $actualOutput );

		$page->clear();
		$this->assertFalse( $page->exists() );
		$this->newSelectQueryBuilder()
			->select( 'COUNT(*)' )
			->from( 'revision' )
			->where( [ 'rev_id' => $revId ] )
			->caller( __METHOD__ )
			->assertFieldValue( 0 );
		$this->newSelectQueryBuilder()
			->select( 'COUNT(*)' )
			->from( 'recentchanges' )
			->where( [ 'rc_this_oldid' => $revId ] )
			->caller( __METHOD__ )
			->assertFieldValue( 0 );
		$this->newSelectQueryBuilder()
			->select( [ 'ss_total_pages', 'ss_total_edits' ] )
			->from( 'site_stats' )
			->caller( __METHOD__ )
			->assertRowValue( [ 0, 0 ] );
	}
}
