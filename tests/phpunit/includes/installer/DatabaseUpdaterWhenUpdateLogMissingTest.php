<?php

namespace MediaWiki\Tests\Installer;

use MediaWiki\Installer\DatabaseUpdater;
use MediaWikiIntegrationTestCase;
use Wikimedia\Rdbms\IMaintainableDatabase;

/**
 * @covers \MediaWiki\Installer\DatabaseUpdater
 * @group Database
 */
class DatabaseUpdaterWhenUpdateLogMissingTest extends MediaWikiIntegrationTestCase {
	public function testUpdateRowExistsWhenUpdateLogTableMissing() {
		// Check that updatelog is actually dropped, otherwise the test will still pass but not test what we want
		// it to test.
		$dbw = $this->getServiceContainer()->getDBLoadBalancer()->getMaintenanceConnectionRef( DB_PRIMARY );
		$this->assertFalse( $dbw->tableExists( 'updatelog' ) );
		// Call the method under test.
		$objectUnderTest = DatabaseUpdater::newForDB( $dbw );
		$this->assertSame( false, $objectUnderTest->updateRowExists( 'test' ) );
	}

	public function testInsertUpdateRowWhenUpdateLogTableMissing() {
		// Call the method under test
		$dbw = $this->getServiceContainer()->getDBLoadBalancer()->getMaintenanceConnectionRef( DB_PRIMARY );
		$objectUnderTest = DatabaseUpdater::newForDB( $dbw );
		$objectUnderTest->insertUpdateRow( 'test' );
		// Check that updatelog still does not exist after calling the method under test
		$this->assertFalse( $dbw->tableExists( 'updatelog' ) );
	}

	protected function getSchemaOverrides( IMaintainableDatabase $db ) {
		return [
			'drop' => [ 'updatelog' ],
			'scripts' => [ __DIR__ . '/patches/drop-table-updatelog.sql' ]
		];
	}
}
