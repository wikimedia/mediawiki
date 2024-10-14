<?php

namespace MediaWiki\Tests\Maintenance;

use PatchSql;

/**
 * @covers \PatchSql
 * @group Database
 * @author Dreamy Jazz
 */
class PatchSqlTest extends MaintenanceBaseTestCase {

	protected function getMaintenanceClass() {
		return PatchSql::class;
	}

	public function testExecute() {
		// Create a SQL file with an insert query
		$testFilename = $this->getNewTempFile();
		$testFile = fopen( $testFilename, 'w' );
		fwrite( $testFile, "INSERT INTO /*_*/updatelog (ul_key, ul_value) VALUES ('testing1234', 'testing');\n" );
		fclose( $testFile );
		// Pass the file to the maintenance script and run it
		$this->maintenance->setArg( 'patch-name', $testFilename );
		$this->maintenance->execute();
		// Check that the insert query worked
		$this->newSelectQueryBuilder()
			->select( [ 'ul_key', 'ul_value' ] )
			->from( 'updatelog' )
			->caller( __METHOD__ )
			->assertRowValue( [ 'testing1234', 'testing' ] );
	}
}
