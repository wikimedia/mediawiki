<?php

use MediaWiki\MainConfigNames;
use MediaWiki\RecentChanges\RecentChange;
use MediaWiki\RecentChanges\RecentChangesUpdateJob;
use Wikimedia\Timestamp\ConvertibleTimestamp;

/**
 * @group Database
 * @covers \MediaWiki\RecentChanges\RecentChangesUpdateJob
 * @author Dreamy Jazz
 */
class RecentChangesUpdateJobTest extends MediaWikiIntegrationTestCase {

	private function addTestingExpiredRows() {
		// Make three testing edits, which will trigger a recentchanges insert. Two of the edits will be made
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
		ConvertibleTimestamp::setFakeTime( '20230705060708' );
		$this->editPage( $testPage, 'testing12345', '', NS_MAIN, $testUser );
		ConvertibleTimestamp::setFakeTime( '20240405060708' );
		$this->editPage( $testPage, 'testing123456', '', NS_MAIN, $testUser );
		// Verify that the recentchanges table row count is as expected for the test
		$this->newSelectQueryBuilder()
			->field( 'COUNT(*)' )
			->table( 'recentchanges' )
			->assertFieldValue( 3 );
	}

	public function testNewPurgeJob() {
		$this->addTestingExpiredRows();
		// Set the time as one day beyond the last test edit
		ConvertibleTimestamp::setFakeTime( '20240406060708' );
		// Fix wgRCMaxAge for the test, in case the default value changes.
		$this->overrideConfigValue( MainConfigNames::RCMaxAge, 90 * 24 * 3600 );
		$hookRunAtLeastOnce = false;
		$this->setTemporaryHook( 'RecentChangesPurgeRows', function ( $rows ) use ( &$hookRunAtLeastOnce ) {
			// Check that the first row has the expected columns. Checking just the first row should be fine
			// as the value of $rows should come from ::fetchResultSet which returns the same columns for each
			// returned row.
			$rowAsArray = (array)$rows[0];
			// To get the expected fields, use the value of the items in the 'fields' array. The exception to this
			// is where the key is a string, when it should be used instead (as this is an alias).
			$recentChangeQueryFields = RecentChange::getQueryInfo()['fields'];
			$expectedFields = [];
			foreach ( $recentChangeQueryFields as $key => $value ) {
				if ( is_string( $key ) ) {
					$expectedFields[] = $key;
				} else {
					$expectedFields[] = $value;
				}
			}
			$this->assertArrayEquals(
				$expectedFields,
				array_keys( $rowAsArray ),
				false,
				true,
				'Columns in the provided $row are not as expected'
			);
			$hookRunAtLeastOnce = true;
		} );
		// Call the code we are testing
		$objectUnderTest = RecentChangesUpdateJob::newPurgeJob();
		$this->assertInstanceOf( RecentChangesUpdateJob::class, $objectUnderTest );
		$objectUnderTest->run();
		// Verify that only the edit made a day ago is now in the recentchanges table
		$this->newSelectQueryBuilder()
			->field( 'rc_timestamp' )
			->table( 'recentchanges' )
			->assertFieldValue( $this->getDb()->timestamp( '20240405060708' ) );
		// Verify that the lock placed to do the purge is no longer active.
		$this->assertTrue( $this->getDb()->lockIsFree(
			$this->getDb()->getDomainID() . ':recentchanges-prune', __METHOD__
		) );
		// Check that the RecentChangesPurgeRows hook was run at least once
		$this->assertTrue( $hookRunAtLeastOnce, 'RecentChangesPurgeRows hook was not run' );
	}

	/** @dataProvider provideInvalidTypes */
	public function testWhenTypeForInvalidType( $type ) {
		$this->expectException( InvalidArgumentException::class );
		$objectUnderTest = new RecentChangesUpdateJob( $this->getExistingTestPage()->getTitle(), [ 'type' => $type ] );
		$objectUnderTest->run();
	}

	public static function provideInvalidTypes() {
		return [
			'Type is null' => [ null ],
			'Type is a unrecognised string' => [ 'unknown-type' ],
		];
	}
}
