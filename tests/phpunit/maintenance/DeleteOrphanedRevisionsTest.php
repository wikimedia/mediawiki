<?php

namespace MediaWiki\Tests\Maintenance;

use DeleteOrphanedRevisions;
use MediaWiki\Tests\Unit\Permissions\MockAuthorityTrait;
use MediaWiki\Tests\User\TempUser\TempUserTestTrait;

/**
 * @covers \DeleteOrphanedRevisions
 * @group Database
 * @author Dreamy Jazz
 */
class DeleteOrphanedRevisionsTest extends MaintenanceBaseTestCase {

	use TempUserTestTrait;
	use MockAuthorityTrait;

	protected function getMaintenanceClass() {
		return DeleteOrphanedRevisions::class;
	}

	public function testExecuteForNoFoundRevisions() {
		// Get revisions which are not orphaned, so that we know the script won't attempt to delete them.
		$this->editPage( $this->getExistingTestPage(), 'testing1234' );
		$this->maintenance->execute();
		$this->expectOutputRegex( "/Checking for orphaned revisions.*found 0.\n$/" );
	}

	public function testExecuteForOrphanedRevisions() {
		$this->disableAutoCreateTempUser();
		$testTitle = $this->getExistingTestPage();
		// Get revisions which are not orphaned, so that we know the script won't attempt to delete them.
		$this->editPage( $testTitle, 'testing1234' );
		// Get revisions which are orphaned, one which has rev_page as 0 and the other which has a rev_page but
		// the page ID does not exist.
		$firstOrphanedRevId = $this->editPage( $testTitle, 'testing123456' )->getNewRevision()->getId();
		$secondOrphanedRevId = $this->editPage(
			$testTitle, 'testing1234567', '', NS_MAIN, $this->mockAnonUltimateAuthority()
		)->getNewRevision()->getId();
		$this->getDb()->newUpdateQueryBuilder()
			->update( 'revision' )
			->set( [ 'rev_page' => 123456 ] )
			->where( [ 'rev_id' => $secondOrphanedRevId ] )
			->execute();
		$this->getDb()->newUpdateQueryBuilder()
			->update( 'revision' )
			->set( [ 'rev_page' => 0 ] )
			->where( [ 'rev_id' => $firstOrphanedRevId ] )
			->execute();
		// Check that a row exists for the second rev ID in ip_changes, as this is necessary for the assertion
		// further down to check that the row was actually deleted.
		$this->newSelectQueryBuilder()
			->select( 'COUNT(*)' )
			->from( 'ip_changes' )
			->where( [ 'ipc_rev_id' => $secondOrphanedRevId ] )
			->assertFieldValue( 1 );
		// Run the maintenance script
		$this->maintenance->execute();
		// Check that the orphaned revisions were actually deleted.
		$this->expectOutputRegex( "/Checking for orphaned revisions.*found 2.\nDeleting.*done/" );
		$this->newSelectQueryBuilder()
			->select( 'COUNT(*)' )
			->from( 'revision' )
			->where( [ 'rev_id' => [ $firstOrphanedRevId, $secondOrphanedRevId ] ] )
			->assertFieldValue( 0 );
		$this->newSelectQueryBuilder()
			->select( 'COUNT(*)' )
			->from( 'ip_changes' )
			->where( [ 'ipc_rev_id' => $secondOrphanedRevId ] )
			->assertFieldValue( 0 );
	}
}
