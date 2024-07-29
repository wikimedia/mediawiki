<?php

namespace MediaWiki\Tests\Maintenance;

use MwSql;

/**
 * @covers \MwSql
 * @group Database
 * @author Dreamy Jazz
 */
class MwSqlTest extends MaintenanceBaseTestCase {

	protected function getMaintenanceClass() {
		return MwSql::class;
	}

	public function testExecuteForSelectQuery() {
		// Add a testing row to the updatelog table, which we will use the maintenance script to read back.
		$this->getDb()->newInsertQueryBuilder()
			->insertInto( 'updatelog' )
			->row( [ 'ul_key' => 'testing', 'ul_value' => 'testing-value-12345' ] )
			->caller( __METHOD__ )
			->execute();
		// Output JSON to make it easier to assert that the script worked.
		$this->maintenance->setOption( 'json', 1 );
		// Specify the query using the 'query' option to avoid needing to write to STDIN.
		$this->maintenance->setOption(
			'query',
			$this->newSelectQueryBuilder()
				->field( 'ul_value' )
				->from( 'updatelog' )
				->where( [ 'ul_key' => 'testing' ] )
				->caller( __METHOD__ )
				->getSQL()
		);
		$this->maintenance->execute();
		$this->assertArrayEquals(
			[ [ 'ul_value' => 'testing-value-12345' ] ],
			json_decode( $this->getActualOutputForAssertion(), true ),
			true,
			true,
			'JSON output was not as expected.'
		);
	}
}
