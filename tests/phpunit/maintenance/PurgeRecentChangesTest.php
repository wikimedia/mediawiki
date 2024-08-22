<?php

namespace MediaWiki\Tests\Maintenance;

use MediaWiki\MainConfigNames;
use PurgeRecentChanges;
use Wikimedia\Timestamp\ConvertibleTimestamp;

/**
 * @covers \PurgeRecentChanges
 * @group Database
 * @author Dreamy Jazz
 */
class PurgeRecentChangesTest extends MaintenanceBaseTestCase {

	protected function getMaintenanceClass() {
		return PurgeRecentChanges::class;
	}

	public function addDBData() {
		// Make two testing edits, which will trigger a recentchanges insert. One of the edits will be made
		// over wgRCMaxAge seconds ago while the other will be made a day ago
		$testPage = $this->getExistingTestPage();
		$testUser = $this->getTestUser()->getAuthority();
		// So that only our two testing edits are present, and nothing from creating the test page or test user
		$this->truncateTable( 'recentchanges' );
		// Fix wgRCMaxAge at a high value to ensure that the recentchanges entries we are creating are not purged
		// by later testing edits.
		$this->overrideConfigValue( MainConfigNames::RCMaxAge, 24 * 3600 * 1000 );
		ConvertibleTimestamp::setFakeTime( '20230405060708' );
		$this->editPage( $testPage, 'testing1234', '', NS_MAIN, $testUser );
		ConvertibleTimestamp::setFakeTime( '20240405060708' );
		$this->editPage( $testPage, 'testing12345', '', NS_MAIN, $testUser );
		// Verify that the recentchanges table row count is as expected for the test
		$this->newSelectQueryBuilder()
			->field( 'COUNT(*)' )
			->table( 'recentchanges' )
			->assertFieldValue( 2 );
	}

	public function testExecute() {
		// Set the time as one day beyond the last testing edit made in ::addDBData
		ConvertibleTimestamp::setFakeTime( '20240406060708' );
		// Fix wgRCMaxAge for the test, in case the default value changes.
		$this->overrideConfigValue( MainConfigNames::RCMaxAge, 90 * 24 * 3600 );
		// Run the maintenance script
		$this->maintenance->execute();
		// Verify that only the edit made a day ago is now in the recentchanges table
		$this->newSelectQueryBuilder()
			->field( 'rc_timestamp' )
			->table( 'recentchanges' )
			->assertFieldValue( $this->getDb()->timestamp( '20240405060708' ) );
	}
}
