<?php

namespace MediaWiki\Tests\Installer;

use MediaWiki\Installer\PostgresUpdater;
use MediaWikiIntegrationTestCase;
use Wikimedia\Rdbms\IMaintainableDatabase;
use Wikimedia\TestingAccessWrapper;

/**
 * @covers \MediaWiki\Installer\PostgresUpdater
 * @group Database
 */
class PostgresUpdaterTest extends MediaWikiIntegrationTestCase {

	/** @dataProvider provideAlterCommandsSkipWhenSchemaAltersNotAllowed */
	public function testAlterCommandsSkipWhenSchemaAltersNotAllowed(
		string $methodName, array $methodArguments, string $expectedAlterMessage
	) {
		$mockDb = $this->createMock( IMaintainableDatabase::class );
		$mockDb->method( 'getType' )
			->willReturn( 'postgres' );
		$objectUnderTest = PostgresUpdater::newForDB( $mockDb );
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
			'::renameTable' => [
				'methodName' => 'renameTable',
				'methodArguments' => [ 'old_table', 'new_table', 'ignored.sql' ],
				'expectedAlterMessage' => 'Renaming table old_table to new_table',
			],
			'::renameIndex' => [
				'methodName' => 'renameIndex',
				'methodArguments' => [ 'test_table', 'old_index', 'new_index' ],
				'expectedAlterMessage' => 'Renaming index old_index to new_index in table test_table',
			],
			'::dropPgField' => [
				'methodName' => 'dropPgField',
				'methodArguments' => [ 'test_table', 'old_field' ],
				'expectedAlterMessage' => "Dropping column 'test_table.old_field'",
			],
			'::addPgField' => [
				'methodName' => 'addPgField',
				'methodArguments' => [ 'test_table', 'new_field', 'ignored' ],
				'expectedAlterMessage' => "Adding column 'test_table.new_field'",
			],
			'::changeField' => [
				'methodName' => 'changeField',
				'methodArguments' => [ 'test_table', 'field', 'type', 'ignored' ],
				'expectedAlterMessage' => "Changing column type of 'test_table.field' to 'type'",
			],
			'::changeFieldPurgeTable' => [
				'methodName' => 'changeFieldPurgeTable',
				'methodArguments' => [ 'test_table', 'field', 'type', 'ignored' ],
				'expectedAlterMessage' => "Changing column type of 'test_table.field' to 'type'",
			],
			'::setDefault' => [
				'methodName' => 'setDefault',
				'methodArguments' => [ 'test_table', 'field', 'ignored' ],
				'expectedAlterMessage' => "Changing 'test_table.field' default value",
			],
			'::dropDefault' => [
				'methodName' => 'dropDefault',
				'methodArguments' => [ 'test_table', 'field' ],
				'expectedAlterMessage' => "Dropping 'test_table.field' default value",
			],
			'::changeNullableField for change to nullable' => [
				'methodName' => 'changeNullableField',
				'methodArguments' => [ 'test_table', 'field', 'NULL' ],
				'expectedAlterMessage' => "Changing field 'test_table.field' to allow NULLs",
			],
			'::changeNullableField for change to not nullable' => [
				'methodName' => 'changeNullableField',
				'methodArguments' => [ 'test_table', 'field', 'NOT NULL' ],
				'expectedAlterMessage' => "Changing field 'test_table.field' to not allow NULLs",
			],
			'::addPgIndex' => [
				'methodName' => 'addPgIndex',
				'methodArguments' => [ 'test_table', 'index', 'type' ],
				'expectedAlterMessage' => "Creating index 'index' on table 'test_table' type",
			],
			'::addPgExtIndex' => [
				'methodName' => 'addPgExtIndex',
				'methodArguments' => [ 'test_table', 'index', 'ignored' ],
				'expectedAlterMessage' => "Creating index 'index' on table 'test_table'",
			],
			'::dropFkey' => [
				'methodName' => 'dropFkey',
				'methodArguments' => [ 'test_table', 'index' ],
				'expectedAlterMessage' => "Dropping foreign key constraint on 'test_table.index'",
			],
			'::changeFkeyDeferrable' => [
				'methodName' => 'changeFkeyDeferrable',
				'methodArguments' => [ 'test_table', 'index', 'ignored' ],
				'expectedAlterMessage' => "Altering column 'test_table.index' to be DEFERRABLE INITIALLY DEFERRED",
			],
			'::dropPgIndex' => [
				'methodName' => 'dropPgIndex',
				'methodArguments' => [ 'test_table', 'index' ],
				'expectedAlterMessage' => "Dropping obsolete index 'index'",
			],
			'::checkIndex' => [
				'methodName' => 'checkIndex',
				'methodArguments' => [ 'test_table', 'ignored', 'ignored' ],
				'expectedAlterMessage' => "Checking if index 'test_table' is up to date",
			],
			'::changePrimaryKey' => [
				'methodName' => 'changePrimaryKey',
				'methodArguments' => [ 'test_table', 'ignored' ],
				'expectedAlterMessage' => 'Changing primary key on test_table',
			],
			'::dropConstraint' => [
				'methodName' => 'dropConstraint',
				'methodArguments' => [ 'test_table', 'field', 'ignored' ],
				'expectedAlterMessage' => "Dropping constraint on test_table for field",
			],
		];
	}
}
