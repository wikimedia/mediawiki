<?php

namespace MediaWiki\Tests\Installer;

use MediaWiki\Installer\DatabaseUpdater;
use MediaWikiIntegrationTestCase;
use Wikimedia\TestingAccessWrapper;

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

	/** @dataProvider provideDoUpdatesSetsSkipSchemaFlags */
	public function testDoUpdatesSetsSkipSchemaFlags(
		$updatesToPerformArgument, $expectedSchemaFlagValue, $expectedSchemaAlterFlagValue
	): void {
		$objectUnderTest = DatabaseUpdater::newForDB(
			$this->getServiceContainer()->getDBLoadBalancer()->getMaintenanceConnectionRef( DB_PRIMARY )
		);
		$objectUnderTest->doUpdates( $updatesToPerformArgument );

		$objectUnderTest = TestingAccessWrapper::newFromObject( $objectUnderTest );
		$this->assertSame(
			$expectedSchemaFlagValue,
			$objectUnderTest->skipSchema,
			'The skip schema flag was not as expected'
		);
		$this->assertSame(
			$expectedSchemaAlterFlagValue,
			$objectUnderTest->skipSchemaAlters,
			'The skip schema alters flag was not as expected'
		);
	}

	public static function provideDoUpdatesSetsSkipSchemaFlags(): array {
		return [
			'Skips all schema updates' => [ [ 'noschema' ], true, true ],
			'Skips just schema alter updates' => [ [ 'noschema-alters' ], false, true ],
			'Skips no schema updates' => [ [], false, false ],
		];
	}

	/** @dataProvider provideAlterCommandsSkipWhenSchemaAltersNotAllowed */
	public function testAlterCommandsSkipWhenSchemaAltersNotAllowed(
		string $methodName, array $methodArguments, string $expectedAlterMessage
	) {
		$objectUnderTest = DatabaseUpdater::newForDB(
			$this->getServiceContainer()->getDBLoadBalancer()->getMaintenanceConnectionRef( DB_PRIMARY )
		);
		$objectUnderTest->doUpdates( [ 'noschema-alters' ] );

		$objectUnderTest = TestingAccessWrapper::newFromObject( $objectUnderTest );
		$objectUnderTest->$methodName( ...$methodArguments );

		$actualOutput = $this->getActualOutputForAssertion();
		$this->assertStringContainsString(
			"...skipping schema change ($expectedAlterMessage)",
			$actualOutput,
			'The add field change should have been skipped as it is an ALTER SQL command'
		);
	}

	public static function provideAlterCommandsSkipWhenSchemaAltersNotAllowed(): array {
		return [
			'::addField' => [
				'methodName' => 'addField',
				'methodArguments' => [ 'test_table', 'test_field', 'ignored.sql' ],
				'expectedAlterMessage' => 'Adding test_field field to table test_table',
			],
			'::addIndex' => [
				'methodName' => 'addIndex',
				'methodArguments' => [ 'test_table', 'test_index', 'ignored.sql' ],
				'expectedAlterMessage' => 'Adding index test_index to table test_table',
			],
			'::dropField' => [
				'methodName' => 'dropField',
				'methodArguments' => [ 'test_table', 'test_field', 'ignored.sql' ],
				'expectedAlterMessage' => 'Dropping test_field from table test_table',
			],
			'::dropIndex' => [
				'methodName' => 'dropIndex',
				'methodArguments' => [ 'test_table', 'test_index', 'ignored.sql' ],
				'expectedAlterMessage' => 'Dropping test_index index from table test_table',
			],
			'::renameIndex' => [
				'methodName' => 'renameIndex',
				'methodArguments' => [ 'test_table', 'old_index', 'new_index', false, 'ignored.sql' ],
				'expectedAlterMessage' => 'Renaming index old_index to new_index in table test_table',
			],
			'::modifyPrimaryKey' => [
				'methodName' => 'modifyPrimaryKey',
				'methodArguments' => [ 'test_table', [ 'ignored' ], 'ignored.sql' ],
				'expectedAlterMessage' => 'Modifying primary key on table test_table',
			],
			'::modifyTable' => [
				'methodName' => 'modifyTable',
				'methodArguments' => [ 'test_table', 'patch.sql' ],
				'expectedAlterMessage' => 'Modifying table test_table with patch patch.sql',
			],
			'::modifyTableIfFieldNotExists' => [
				'methodName' => 'modifyTableIfFieldNotExists',
				'methodArguments' => [ 'test_table', 'test_field', 'patch.sql' ],
				'expectedAlterMessage' => 'Modifying table test_table with patch patch.sql',
			],
			'::modifyFieldIfNullable' => [
				'methodName' => 'modifyFieldIfNullable',
				'methodArguments' => [ 'test_table', 'test_field', 'ignored.sql' ],
				'expectedAlterMessage' => 'Modifying test_field field of table test_table',
			],
		];
	}
}
