<?php

namespace MediaWiki\Tests\Maintenance;

use MediaWiki\WikiMap\WikiMap;
use RunBatchedQuery;
use Wikimedia\Rdbms\Platform\SQLPlatform;

/**
 * @covers \RunBatchedQuery
 * @group Database
 * @author Dreamy Jazz
 */
class RunBatchedQueryTest extends MaintenanceBaseTestCase {

	protected function getMaintenanceClass() {
		return RunBatchedQuery::class;
	}

	private function commonTestExecute( array $options, array $expectedRowsAfterCall ) {
		// Insert some testing data that will be modified by the script
		$this->getDb()->newInsertQueryBuilder()
			->insertInto( 'updatelog' )
			->rows( [
				[ 'ul_key' => 'testing', 'ul_value' => 'abc' ],
				[ 'ul_key' => 'testingabc', 'ul_value' => '1234' ],
				[ 'ul_key' => 'abc', 'ul_value' => '1234' ],
				[ 'ul_key' => 'testing1', 'ul_value' => 'test1' ],
				[ 'ul_key' => 'testing2', 'ul_value' => 'test2' ],
				[ 'ul_key' => 'testing3', 'ul_value' => 'test3' ],
			] )
			->caller( __METHOD__ )
			->execute();
		// Run the script
		$options = array_merge( $options, [ 'table' => 'updatelog', 'key' => 'ul_key' ] );
		foreach ( $options as $name => $value ) {
			$this->maintenance->setOption( $name, $value );
		}
		$this->maintenance->execute();
		// Check that the script performed the expected operations
		$this->newSelectQueryBuilder()
			->select( [ 'ul_key', 'ul_value' ] )
			->from( 'updatelog' )
			->caller( __METHOD__ )
			->assertResultSet( $expectedRowsAfterCall );
	}

	public function testExecuteWhenAllRowsChanged() {
		$this->commonTestExecute(
			[
				'set' => $this->getDb()->makeList( [ 'ul_value' => 'abc' ], SQLPlatform::LIST_SET ),
				'batch-size' => 2,
			],
			[
				[ 'abc', 'abc' ],
				[ 'testing', 'abc' ],
				[ 'testing1', 'abc' ],
				[ 'testing2', 'abc' ],
				[ 'testing3', 'abc' ],
				[ 'testingabc', 'abc' ],
			]
		);
	}

	public function testExecuteWhenWhereConditionsAdded() {
		$this->commonTestExecute(
			[
				'set' => $this->getDb()->makeList( [ 'ul_value' => 'abcdef' ], SQLPlatform::LIST_SET ),
				'where' => $this->getDb()->makeList( [ 'ul_key' => 'testing' ], SQLPlatform::LIST_AND ),
				'db' => WikiMap::getCurrentWikiDbDomain(),
			],
			[
				[ 'abc', '1234' ],
				[ 'testing', 'abcdef' ],
				[ 'testing1', 'test1' ],
				[ 'testing2', 'test2' ],
				[ 'testing3', 'test3' ],
				[ 'testingabc', '1234' ],
			]
		);
	}
}
