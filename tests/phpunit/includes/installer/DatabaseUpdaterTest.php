<?php

namespace MediaWiki\Tests\Installer;

use MediaWiki\Installer\DatabaseUpdater;
use MediaWikiIntegrationTestCase;

/**
 * @covers \MediaWiki\Installer\DatabaseUpdater
 * @group Database
 */
class DatabaseUpdaterTest extends MediaWikiIntegrationTestCase {
	/** @dataProvider provideUpdateRowExists */
	public function testUpdateRowExists( $key, $expectedReturnValue ) {
		// Add a testing row to the updatelog table to test lookup
		$this->getDb()->newInsertQueryBuilder()
			->insertInto( 'updatelog' )
			->row( [ 'ul_key' => 'test', 'ul_value' => null ] )
			->caller( __METHOD__ )
			->execute();
		// Call the method under test
		$objectUnderTest = DatabaseUpdater::newForDB(
			$this->getServiceContainer()->getDBLoadBalancer()->getMaintenanceConnectionRef( DB_PRIMARY )
		);
		$this->assertSame( $expectedReturnValue, $objectUnderTest->updateRowExists( $key ) );
	}

	public static function provideUpdateRowExists() {
		return [
			'Key is present in updatelog table' => [ 'test', true ],
			'Key is not present in updatelog table' => [ 'testing', false ],
		];
	}

	/** @dataProvider provideInsertUpdateRow */
	public function testInsertUpdateRow( $key, $val ) {
		// Call the method under test
		$objectUnderTest = DatabaseUpdater::newForDB(
			$this->getServiceContainer()->getDBLoadBalancer()->getMaintenanceConnectionRef( DB_PRIMARY )
		);
		$objectUnderTest->insertUpdateRow( $key, $val );
		// Expect that the updatelog contains the expected row
		$this->newSelectQueryBuilder()
			->select( [ 'ul_key', 'ul_value' ] )
			->from( 'updatelog' )
			->caller( __METHOD__ )
			->assertRowValue( [ $key, $val ] );
	}

	public static function provideInsertUpdateRow() {
		return [
			'Value is not null' => [ 'test', 'test' ],
			'Value is null' => [ 'testing', null ],
		];
	}
}
