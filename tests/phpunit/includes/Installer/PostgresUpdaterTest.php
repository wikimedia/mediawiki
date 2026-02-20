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
				'methodArgument' => [ 'old_table', 'new_table', 'ignored.sql' ],
				'expectedAlterMessage' => 'Renaming table old_table to new_table',
			],
			'::renameIndex' => [
				'methodName' => 'renameIndex',
				'methodArgument' => [ 'test_table', 'old_index', 'new_index' ],
				'expectedAlterMessage' => 'Renaming index old_index to new_index in table test_table',
			],
			'::dropPgField' => [
				'methodName' => 'dropPgField',
				'methodArgument' => [ 'test_table', 'old_field' ],
				'expectedAlterMessage' => "Dropping column 'test_table.old_field'",
			],
			'::addPgField' => [
				'methodName' => 'addPgField',
				'methodArgument' => [ 'test_table', 'new_field', 'ignored' ],
				'expectedAlterMessage' => "Adding column 'test_table.new_field'",
			],
			'::changeField' => [
				'methodName' => 'changeField',
				'methodArgument' => [ 'test_table', 'field', 'type', 'ignored' ],
				'expectedAlterMessage' => "Changing column type of 'test_table.field' to 'type'",
			],
			'::changeFieldPurgeTable' => [
				'methodName' => 'changeFieldPurgeTable',
				'methodArgument' => [ 'test_table', 'field', 'type', 'ignored' ],
				'expectedAlterMessage' => "Changing column type of 'test_table.field' to 'type'",
			],
			'::setDefault' => [
				'methodName' => 'setDefault',
				'methodArgument' => [ 'test_table', 'field', 'ignored' ],
				'expectedAlterMessage' => "Changing 'test_table.field' default value",
			],
			'::dropDefault' => [
				'methodName' => 'dropDefault',
				'methodArgument' => [ 'test_table', 'field' ],
				'expectedAlterMessage' => "Dropping 'test_table.field' default value",
			],
			'::changeNullableField for change to nullable' => [
				'methodName' => 'changeNullableField',
				'methodArgument' => [ 'test_table', 'field', 'NULL' ],
				'expectedAlterMessage' => "Changing field 'test_table.field' to allow NULLs",
			],
			'::changeNullableField for change to not nullable' => [
				'methodName' => 'changeNullableField',
				'methodArgument' => [ 'test_table', 'field', 'NOT NULL' ],
				'expectedAlterMessage' => "Changing field 'test_table.field' to not allow NULLs",
			],
			'::addPgIndex' => [
				'methodName' => 'addPgIndex',
				'methodArgument' => [ 'test_table', 'index', 'type' ],
				'expectedAlterMessage' => "Creating index 'index' on table 'test_table' type",
			],
			'::addPgExtIndex' => [
				'methodName' => 'addPgExtIndex',
				'methodArgument' => [ 'test_table', 'index', 'ignored' ],
				'expectedAlterMessage' => "Creating index 'index' on table 'test_table'",
			],
			'::dropFkey' => [
				'methodName' => 'dropFkey',
				'methodArgument' => [ 'test_table', 'index' ],
				'expectedAlterMessage' => "Dropping foreign key constraint on 'test_table.index'",
			],
			'::changeFkeyDeferrable' => [
				'methodName' => 'changeFkeyDeferrable',
				'methodArgument' => [ 'test_table', 'index', 'ignored' ],
				'expectedAlterMessage' => "Altering column 'test_table.index' to be DEFERRABLE INITIALLY DEFERRED",
			],
			'::dropPgIndex' => [
				'methodName' => 'dropPgIndex',
				'methodArgument' => [ 'test_table', 'index' ],
				'expectedAlterMessage' => "Dropping obsolete index 'index'",
			],
			'::checkIndex' => [
				'methodName' => 'checkIndex',
				'methodArgument' => [ 'test_table', 'ignored', 'ignored' ],
				'expectedAlterMessage' => "Checking if index 'test_table' is up to date",
			],
			'::changePrimaryKey' => [
				'methodName' => 'changePrimaryKey',
				'methodArgument' => [ 'test_table', 'ignored' ],
				'expectedAlterMessage' => 'Changing primary key on test_table',
			],
			'::dropConstraint' => [
				'methodName' => 'dropConstraint',
				'methodArgument' => [ 'test_table', 'field', 'ignored' ],
				'expectedAlterMessage' => "Dropping constraint on test_table for field",
			],
		];
	}
}
