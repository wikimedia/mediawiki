<?php

use MediaWiki\Tests\Unit\Libs\Rdbms\AddQuoterMock;
use MediaWiki\Tests\Unit\Libs\Rdbms\SQLPlatformTestHelper;
use Wikimedia\Rdbms\Database;
use Wikimedia\Rdbms\DBError;
use Wikimedia\Rdbms\DBTransactionError;
use Wikimedia\Rdbms\DBTransactionStateError;
use Wikimedia\Rdbms\DBUnexpectedError;
use Wikimedia\Rdbms\IDatabase;
use Wikimedia\Rdbms\LikeMatch;
use Wikimedia\Rdbms\TransactionManager;
use Wikimedia\TestingAccessWrapper;

/**
 * Test the parts of the Database abstract class that deal
 * with creating SQL text.
 */
class DatabaseSQLTest extends PHPUnit\Framework\TestCase {

	use MediaWikiCoversValidator;
	use MediaWikiTestCaseTrait;

	/** @var DatabaseTestHelper|Database */
	private $database;

	/** @var \Wikimedia\Rdbms\Platform\SQLPlatform */
	private $platform;

	protected function setUp(): void {
		parent::setUp();
		$this->database = new DatabaseTestHelper( __CLASS__, [ 'cliMode' => true ] );
		$this->platform = new SQLPlatformTestHelper( new AddQuoterMock() );
		MWDebug::clearDeprecationFilters();
		MWDebug::clearLog();
	}

	protected function assertLastSql( $sqlText ) {
		$this->assertEquals(
			$sqlText,
			$this->database->getLastSqls()
		);
	}

	protected function assertLastSqlDb( $sqlText, DatabaseTestHelper $db ) {
		$this->assertEquals( $sqlText, $db->getLastSqls() );
	}

	/**
	 * @dataProvider provideQueryMulti
	 * @covers Wikimedia\Rdbms\Database::queryMulti
	 */
	public function testQueryMulti( array $sqls, string $summarySql, array $resTriples ) {
		$lastSql = null;
		reset( $sqls );
		foreach ( $resTriples as [ $res, $errno, $error ] ) {
			$this->database->forceNextResult( $res, $errno, $error );
			if ( $lastSql !== null && $errno ) {
				$lastSql = current( $sqls );
			}
			next( $sqls );
		}
		$lastSql = $lastSql ?? end( $sqls );
		$this->database->queryMulti( $sqls, __METHOD__, 0, $summarySql );
		$this->assertLastSql( implode( '; ', $sqls ) );
	}

	public static function provideQueryMulti() {
		return [
			[
				[
					'SELECT 1 AS v',
					'UPDATE page SET page_size=0 WHERE page_id=42',
					'DELETE FROM page WHERE page_id=999',
					'SELECT page_id FROM page LIMIT 3'
				],
				'COMPOSITE page QUERY',
				[
					[ [ [ 'v' => 1 ] ], 0, '' ],
					[ true, 0, '' ],
					[ true, 0, '' ],
					[ [ [ 'page_id' => 42 ], [ 'page_id' => 1 ], [ 'page_id' => 11 ] ], 0, '' ]
				]
			]
		];
	}

	/**
	 * @dataProvider provideSelect
	 * @covers Wikimedia\Rdbms\Database::select
	 * @covers Wikimedia\Rdbms\Platform\SQLPlatform::selectSQLText
	 * @covers Wikimedia\Rdbms\Platform\SQLPlatform::tableNamesWithIndexClauseOrJOIN
	 * @covers Wikimedia\Rdbms\Platform\SQLPlatform::useIndexClause
	 * @covers Wikimedia\Rdbms\Platform\SQLPlatform::ignoreIndexClause
	 * @covers Wikimedia\Rdbms\Platform\SQLPlatform::makeSelectOptions
	 * @covers Wikimedia\Rdbms\Platform\SQLPlatform::makeOrderBy
	 * @covers Wikimedia\Rdbms\Platform\SQLPlatform::makeGroupByWithHaving
	 * @covers Wikimedia\Rdbms\Platform\SQLPlatform::selectFieldsOrOptionsAggregate
	 * @covers Wikimedia\Rdbms\Platform\SQLPlatform::selectOptionsIncludeLocking
	 */
	public function testSelect( $sql, $sqlText ) {
		$this->database->select(
			$sql['tables'],
			$sql['fields'],
			$sql['conds'] ?? [],
			__METHOD__,
			$sql['options'] ?? [],
			$sql['join_conds'] ?? []
		);
		$this->assertLastSql( $sqlText );
	}

	public static function provideSelect() {
		return [
			[
				[
					'tables' => 'table',
					'fields' => [ 'field', 'alias' => 'field2' ],
					'conds' => [ 'alias' => 'text' ],
				],
				"SELECT field,field2 AS alias " .
					"FROM table " .
					"WHERE alias = 'text'"
			],
			[
				[
					'tables' => 'table',
					'fields' => [ 'field', 'alias' => 'field2' ],
					'conds' => 'alias = \'text\'',
				],
				"SELECT field,field2 AS alias " .
				"FROM table " .
				"WHERE alias = 'text'"
			],
			[
				[
					'tables' => 'table',
					'fields' => [ 'field', 'alias' => 'field2' ],
					'conds' => [],
				],
				"SELECT field,field2 AS alias " .
				"FROM table"
			],
			[
				[
					'tables' => 'table',
					'fields' => [ 'field', 'alias' => 'field2' ],
					'conds' => '',
				],
				"SELECT field,field2 AS alias " .
				"FROM table"
			],
			[
				[
					'tables' => 'table',
					'fields' => [ 'field', 'alias' => 'field2' ],
					'conds' => '0', // T188314
				],
				"SELECT field,field2 AS alias " .
				"FROM table " .
				"WHERE 0"
			],
			[
				[
					// 'tables' with space prepended indicates pre-escaped table name
					'tables' => ' table LEFT JOIN table2',
					'fields' => [ 'field' ],
					'conds' => [ 'field' => 'text' ],
				],
				"SELECT field FROM  table LEFT JOIN table2 WHERE field = 'text'"
			],
			[
				[
					// Empty 'tables' is allowed
					'tables' => '',
					'fields' => [ 'SPECIAL_QUERY()' ],
				],
				"SELECT SPECIAL_QUERY()"
			],
			[
				[
					'tables' => 'table',
					'fields' => [ 'field', 'alias' => 'field2' ],
					'conds' => [ 'alias' => 'text' ],
					'options' => [ 'LIMIT' => 1, 'ORDER BY' => 'field' ],
				],
				"SELECT field,field2 AS alias " .
					"FROM table " .
					"WHERE alias = 'text' " .
					"ORDER BY field " .
					"LIMIT 1"
			],
			[
				[
					'tables' => [ 'table', 't2' => 'table2' ],
					'fields' => [ 'tid', 'field', 'alias' => 'field2', 't2.id' ],
					'conds' => [ 'alias' => 'text' ],
					'options' => [ 'LIMIT' => 1, 'ORDER BY' => 'field' ],
					'join_conds' => [ 't2' => [
						'LEFT JOIN', 'tid = t2.id'
					] ],
				],
				"SELECT tid,field,field2 AS alias,t2.id " .
					"FROM table LEFT JOIN table2 t2 ON ((tid = t2.id)) " .
					"WHERE alias = 'text' " .
					"ORDER BY field " .
					"LIMIT 1"
			],
			[
				[
					'tables' => [ 'table', 't2' => 'table2' ],
					'fields' => [ 'tid', 'field', 'alias' => 'field2', 't2.id' ],
					'conds' => [ 'alias' => 'text' ],
					'options' => [ 'LIMIT' => 1, 'GROUP BY' => 'field', 'HAVING' => 'COUNT(*) > 1' ],
					'join_conds' => [ 't2' => [
						'LEFT JOIN', 'tid = t2.id'
					] ],
				],
				"SELECT tid,field,field2 AS alias,t2.id " .
					"FROM table LEFT JOIN table2 t2 ON ((tid = t2.id)) " .
					"WHERE alias = 'text' " .
					"GROUP BY field HAVING COUNT(*) > 1 " .
					"LIMIT 1"
			],
			[
				[
					'tables' => [ 'table', 't2' => 'table2' ],
					'fields' => [ 'tid', 'field', 'alias' => 'field2', 't2.id' ],
					'conds' => [ 'alias' => 'text' ],
					'options' => [
						'LIMIT' => 1,
						'GROUP BY' => [ 'field', 'field2' ],
						'HAVING' => [ 'COUNT(*) > 1', 'field' => 1 ]
					],
					'join_conds' => [ 't2' => [
						'LEFT JOIN', 'tid = t2.id'
					] ],
				],
				"SELECT tid,field,field2 AS alias,t2.id " .
					"FROM table LEFT JOIN table2 t2 ON ((tid = t2.id)) " .
					"WHERE alias = 'text' " .
					"GROUP BY field,field2 HAVING (COUNT(*) > 1) AND field = 1 " .
					"LIMIT 1"
			],
			[
				[
					'tables' => [ 'table' ],
					'fields' => [ 'alias' => 'field' ],
					'conds' => [ 'alias' => [ 1, 2, 3, 4 ] ],
				],
				"SELECT field AS alias " .
					"FROM table " .
					"WHERE alias IN (1,2,3,4)"
			],
			[
				[
					'tables' => 'table',
					'fields' => [ 'field' ],
					'options' => [ 'USE INDEX' => [ 'table' => 'X' ] ],
				],
				"SELECT field FROM table FORCE INDEX (X)"
			],
			[
				[
					'tables' => 'table',
					'fields' => [ 'field' ],
					'options' => [ 'IGNORE INDEX' => [ 'table' => 'X' ] ],
				],
				"SELECT field FROM table IGNORE INDEX (X)"
			],
			[
				[
					'tables' => 'table',
					'fields' => [ 'field' ],
					'options' => [ 'DISTINCT' ],
				],
				"SELECT DISTINCT field FROM table"
			],
			[
				[
					'tables' => 'table',
					'fields' => [ 'field' ],
					'options' => [ 'LOCK IN SHARE MODE' ],
				],
				"SELECT field FROM table      LOCK IN SHARE MODE"
			],
			[
				[
					'tables' => 'table',
					'fields' => [ 'field' ],
					'options' => [ 'EXPLAIN' => true ],
				],
				'EXPLAIN SELECT field FROM table'
			],
			[
				[
					'tables' => 'table',
					'fields' => [ 'field' ],
					'options' => [ 'FOR UPDATE' ],
				],
				"SELECT field FROM table      FOR UPDATE"
			],
			[
				[
					'tables' => [],
					'fields' => [ 'field' ],
				],
				"SELECT field"
			],
		];
	}

	/**
	 * @dataProvider provideLockForUpdate
	 * @covers Wikimedia\Rdbms\Database::lockForUpdate
	 */
	public function testLockForUpdate( $sql, $sqlText ) {
		$this->database->startAtomic( __METHOD__ );
		$this->database->lockForUpdate(
			$sql['tables'],
			$sql['conds'] ?? [],
			__METHOD__,
			$sql['options'] ?? [],
			$sql['join_conds'] ?? []
		);
		$this->database->endAtomic( __METHOD__ );

		$this->assertLastSql( "BEGIN; $sqlText; COMMIT" );
	}

	public static function provideLockForUpdate() {
		return [
			[
				[
					'tables' => [ 'table' ],
					'conds' => [ 'field' => [ 1, 2, 3, 4 ] ],
				],
				"SELECT COUNT(*) AS rowcount FROM " .
				"(SELECT 1 FROM table WHERE field IN (1,2,3,4)    " .
				"FOR UPDATE) tmp_count"
			],
			[
				[
					'tables' => [ 'table', 't2' => 'table2' ],
					'conds' => [ 'field' => 'text' ],
					'options' => [ 'LIMIT' => 1, 'ORDER BY' => 'field' ],
					'join_conds' => [ 't2' => [
						'LEFT JOIN', 'tid = t2.id'
					] ],
				],
				"SELECT COUNT(*) AS rowcount FROM " .
				"(SELECT 1 FROM table LEFT JOIN table2 t2 ON ((tid = t2.id)) " .
				"WHERE field = 'text' ORDER BY field LIMIT 1   FOR UPDATE) tmp_count"
			],
			[
				[
					'tables' => 'table',
				],
				"SELECT COUNT(*) AS rowcount FROM " .
				"(SELECT 1 FROM table      FOR UPDATE) tmp_count"
			],
		];
	}

	/**
	 * @covers Wikimedia\Rdbms\Subquery
	 * @dataProvider provideSelectRowCount
	 * @param array $sql
	 * @param string $sqlText
	 */
	public function testSelectRowCount( $sql, $sqlText ) {
		$this->database->selectRowCount(
			$sql['tables'],
			$sql['field'],
			$sql['conds'] ?? [],
			__METHOD__,
			$sql['options'] ?? [],
			$sql['join_conds'] ?? []
		);
		$this->assertLastSql( $sqlText );
	}

	public static function provideSelectRowCount() {
		return [
			[
				[
					'tables' => 'table',
					'field' => [ '*' ],
					'conds' => [ 'field' => 'text' ],
				],
				"SELECT COUNT(*) AS rowcount FROM " .
				"(SELECT 1 FROM table WHERE field = 'text'  ) tmp_count"
			],
			[
				[
					'tables' => 'table',
					'field' => [ 'column' ],
					'conds' => [ 'field' => 'text' ],
				],
				"SELECT COUNT(*) AS rowcount FROM " .
				"(SELECT 1 FROM table WHERE field = 'text' AND (column IS NOT NULL)  ) tmp_count"
			],
			[
				[
					'tables' => 'table',
					'field' => [ 'alias' => 'column' ],
					'conds' => [ 'field' => 'text' ],
				],
				"SELECT COUNT(*) AS rowcount FROM " .
				"(SELECT 1 FROM table WHERE field = 'text' AND (column IS NOT NULL)  ) tmp_count"
			],
			[
				[
					'tables' => 'table',
					'field' => [ 'alias' => 'column' ],
					'conds' => '',
				],
				"SELECT COUNT(*) AS rowcount FROM " .
				"(SELECT 1 FROM table WHERE (column IS NOT NULL)  ) tmp_count"
			],
			[
				[
					'tables' => 'table',
					'field' => [ 'alias' => 'column' ],
					'conds' => false,
				],
				"SELECT COUNT(*) AS rowcount FROM " .
				"(SELECT 1 FROM table WHERE (column IS NOT NULL)  ) tmp_count"
			],
			[
				[
					'tables' => 'table',
					'field' => [ 'alias' => 'column' ],
					'conds' => null,
				],
				"SELECT COUNT(*) AS rowcount FROM " .
				"(SELECT 1 FROM table WHERE (column IS NOT NULL)  ) tmp_count"
			],
			[
				[
					'tables' => 'table',
					'field' => [ 'alias' => 'column' ],
					'conds' => 1,
				],
				"SELECT COUNT(*) AS rowcount FROM " .
				"(SELECT 1 FROM table WHERE (1) AND (column IS NOT NULL)  ) tmp_count"
			],
			[
				[
					'tables' => 'table',
					'field' => [ 'alias' => 'column' ],
					'conds' => 0,
				],
				"SELECT COUNT(*) AS rowcount FROM " .
				"(SELECT 1 FROM table WHERE (0) AND (column IS NOT NULL)  ) tmp_count"
			],
		];
	}

	/**
	 * @dataProvider provideUpdate
	 * @covers Wikimedia\Rdbms\Database::update
	 * @covers Wikimedia\Rdbms\Platform\SQLPlatform::makeUpdateOptions
	 * @covers Wikimedia\Rdbms\Platform\SQLPlatform::makeUpdateOptionsArray
	 * @covers Wikimedia\Rdbms\Platform\SQLPlatform::assertConditionIsNotEmpty
	 */
	public function testUpdate( $sql, $sqlText ) {
		$this->hideDeprecated( 'Wikimedia\Rdbms\Platform\SQLPlatform::updateSqlText' );
		$this->database->update(
			$sql['table'],
			$sql['values'],
			$sql['conds'],
			__METHOD__,
			$sql['options'] ?? []
		);
		$this->assertLastSql( $sqlText );
	}

	public static function provideUpdate() {
		return [
			[
				[
					'table' => 'table',
					'values' => [ 'field' => 'text', 'field2' => 'text2' ],
					'conds' => [ 'alias' => 'text' ],
				],
				"UPDATE table " .
					"SET field = 'text'" .
					",field2 = 'text2' " .
					"WHERE alias = 'text'"
			],
			[
				[
					'table' => 'table',
					'values' => [ 'field' => 'text', 'field2' => 'text2' ],
					'conds' => 'alias = \'text\'',
				],
				"UPDATE table " .
					"SET field = 'text'" .
					",field2 = 'text2' " .
					"WHERE alias = 'text'"
			],
			[
				[
					'table' => 'table',
					'values' => [ 'field = other', 'field2' => 'text2' ],
					'conds' => [ 'id' => '1' ],
				],
				"UPDATE table " .
					"SET field = other" .
					",field2 = 'text2' " .
					"WHERE id = '1'"
			],
			[
				[
					'table' => 'table',
					'values' => [ 'field = other', 'field2' => 'text2' ],
					'conds' => '*',
				],
				"UPDATE table " .
					"SET field = other" .
					",field2 = 'text2'"
			],
			[
				[
					'table' => 'table',
					'values' => [ 'field' => 'text', 'field2' => 'text2' ],
					'conds' => null,
				],
				"UPDATE table " .
				"SET field = 'text'" .
				",field2 = 'text2'",
			],
			[
				[
					'table' => 'table',
					'values' => [ 'field = other', 'field2' => 'text2' ],
					'conds' => [],
				],
				"UPDATE table " .
				"SET field = other" .
				",field2 = 'text2'",
			],
			[
				[
					'table' => 'table',
					'values' => [ 'field = other', 'field2' => 'text2' ],
					'conds' => '',
				],
				"UPDATE table " .
				"SET field = other" .
				",field2 = 'text2'",
			]
		];
	}

	/**
	 * @dataProvider provideUpdateEmptyCondition
	 * @covers Wikimedia\Rdbms\Database::update
	 * @covers Wikimedia\Rdbms\Platform\SQLPlatform::makeUpdateOptions
	 * @covers Wikimedia\Rdbms\Platform\SQLPlatform::makeUpdateOptionsArray
	 * @covers Wikimedia\Rdbms\Platform\SQLPlatform::assertConditionIsNotEmpty
	 */
	public function testUpdateEmptyCondition( $sql ) {
		$this->expectDeprecation();
		$this->database->update(
			$sql['table'],
			$sql['values'],
			$sql['conds'],
			__METHOD__,
			[]
		);
	}

	public static function provideUpdateEmptyCondition() {
		return [
			[
				[
					'table' => 'table',
					'values' => [ 'field' => 'text', 'field2' => 'text2' ],
					'conds' => null,
				],
			],
			[
				[
					'table' => 'table',
					'values' => [ 'field = other', 'field2' => 'text2' ],
					'conds' => [],
				],
			],
			[
				[
					'table' => 'table',
					'values' => [ 'field = other', 'field2' => 'text2' ],
					'conds' => '',
				],
			]
		];
	}

	/**
	 * @dataProvider provideDelete
	 * @covers Wikimedia\Rdbms\Database::delete
	 * @covers Wikimedia\Rdbms\Platform\SQLPlatform::assertConditionIsNotEmpty
	 */
	public function testDelete( $sql, $sqlText ) {
		$this->database->delete(
			$sql['table'],
			$sql['conds'],
			__METHOD__
		);
		$this->assertLastSql( $sqlText );
	}

	public static function provideDelete() {
		return [
			[
				[
					'table' => 'table',
					'conds' => [ 'alias' => 'text' ],
				],
				"DELETE FROM table " .
					"WHERE alias = 'text'"
			],
			[
				[
					'table' => 'table',
					'conds' => 'alias = \'text\'',
				],
				"DELETE FROM table " .
					"WHERE alias = 'text'"
			],
			[
				[
					'table' => 'table',
					'conds' => '*',
				],
				"DELETE FROM table"
			],
		];
	}

	/**
	 * @dataProvider provideDeleteEmptyCondition
	 * @covers Wikimedia\Rdbms\Database::delete
	 * @covers Wikimedia\Rdbms\Platform\SQLPlatform::assertConditionIsNotEmpty
	 */
	public function testDeleteEmptyCondition( $sql ) {
		try {
			$this->database->delete(
				$sql['table'],
				$sql['conds'],
				__METHOD__
			);
			$this->fail( 'The Database::delete should raise exception' );
		} catch ( Exception $e ) {
			$this->assertStringContainsString( 'deleteSqlText called with empty conditions', $e->getMessage() );
		}
	}

	public static function provideDeleteEmptyCondition() {
		return [
			[
				[
					'table' => 'table',
					'conds' => null,
				],
			],
			[
				[
					'table' => 'table',
					'conds' => [],
				],
			],
			[
				[
					'table' => 'table',
					'conds' => '',
				],
			],
		];
	}

	/**
	 * @dataProvider provideUpsert
	 * @covers Wikimedia\Rdbms\Database::upsert
	 */
	public function testUpsert( $sql, $sqlText ) {
		$this->database->setNextQueryAffectedRowCounts( [ 0 ] );

		foreach ( $sql['set'] as $k => $v ) {
			$sql['set'][$k] = preg_replace_callback(
				'/\$EXCLUDED\((\w+)\)/',
				function ( $m ) {
					return $this->database->buildExcludedValue( $m[1] );
				},
				$v
			);
		}

		$this->database->upsert(
			$sql['table'],
			$sql['rows'],
			$sql['uniqueIndexes'],
			$sql['set'],
			__METHOD__
		);
		$this->assertLastSql( $sqlText );
	}

	public static function provideUpsert() {
		return [
			[
				[
					'table' => 'upsert_table',
					'rows' => [ 'id' => 'text', 'field1' => 'text2' ],
					'uniqueIndexes' => 'id',
					'set' => [ 'field2' => 'set' ],
				],
				"BEGIN; " .
				"UPDATE upsert_table SET field2 = 'set' " .
				"WHERE (id = 'text'); " .
				"INSERT INTO upsert_table (id,field1) VALUES ('text','text2'); " .
				"COMMIT"
			],
			[
				[
					'table' => 'upsert_table',
					'rows' => [ 'id' => 'text', 'field1' => 'text2' ],
					'uniqueIndexes' => [ [ 'id' ] ],
					'set' => [ 'field2' => 'set' ],
				],
				"BEGIN; " .
				"UPDATE upsert_table SET field2 = 'set' " .
				"WHERE (id = 'text'); " .
				"INSERT INTO upsert_table (id,field1) VALUES ('text','text2'); " .
				"COMMIT"
			],
			[
				[
					'table' => 'upsert_table2',
					'rows' => [ 'code' => 'text', 'name' => 'more', 'field3' => 'text2' ],
					'uniqueIndexes' => [ [ 'code', 'name' ] ],
					'set' => [ 'field2' => 'set' ],
				],
				"BEGIN; " .
				"UPDATE upsert_table2 SET field2 = 'set' " .
				"WHERE (code = 'text' AND name = 'more'); " .
				"INSERT INTO upsert_table2 (code,name,field3) VALUES ('text','more','text2'); " .
				"COMMIT"
			],
			[
				[
					'table' => 'upsert_table',
					'rows' => [
						[ 'id' => 1, 'field1' => 10, 'field2' => 1583464659, 'field3' => 1 ],
						[ 'id' => 6, 'field1' => 60, 'field2' => 1583464659, 'field3' => 1 ]
					],
					'uniqueIndexes' => 'id',
					'set' => [
						'field1 = field1 + $EXCLUDED(field1)',
						'field2 = COALESCE(field2,$EXCLUDED(field2))'
					],
				],
				"BEGIN; " .
				"WITH __VALS (__id,__field1,__field2,__field3) " .
				"AS (VALUES (1,10,1583464659,1)) " .
				"UPDATE upsert_table SET " .
				"field1 = field1 + (SELECT __field1 FROM __VALS)," .
				"field2 = COALESCE(field2,(SELECT __field2 FROM __VALS)) " .
				"WHERE (id = 1); " .
				"INSERT INTO upsert_table (id,field1,field2,field3) " .
				"VALUES (1,10,1583464659,1); " .
				"WITH __VALS (__id,__field1,__field2,__field3) " .
				"AS (VALUES (6,60,1583464659,1)) " .
				"UPDATE upsert_table SET " .
				"field1 = field1 + (SELECT __field1 FROM __VALS)," .
				"field2 = COALESCE(field2,(SELECT __field2 FROM __VALS)) " .
				"WHERE (id = 6); " .
				"INSERT INTO upsert_table (id,field1,field2,field3) " .
				"VALUES (6,60,1583464659,1); " .
				"COMMIT"
			],
		];
	}

	/**
	 * @dataProvider provideDeleteJoin
	 * @covers Wikimedia\Rdbms\Database::deleteJoin
	 */
	public function testDeleteJoin( $sql, $sqlText ) {
		$this->database->deleteJoin(
			$sql['delTable'],
			$sql['joinTable'],
			$sql['delVar'],
			$sql['joinVar'],
			$sql['conds'],
			__METHOD__
		);
		$this->assertLastSql( $sqlText );
	}

	public static function provideDeleteJoin() {
		return [
			[
				[
					'delTable' => 'table',
					'joinTable' => 'table_join',
					'delVar' => 'field',
					'joinVar' => 'field_join',
					'conds' => [ 'alias' => 'text' ],
				],
				"DELETE FROM table " .
					"WHERE field IN (" .
					"SELECT field_join FROM table_join WHERE alias = 'text'" .
					")"
			],
			[
				[
					'delTable' => 'table',
					'joinTable' => 'table_join',
					'delVar' => 'field',
					'joinVar' => 'field_join',
					'conds' => '*',
				],
				"DELETE FROM table " .
					"WHERE field IN (" .
					"SELECT field_join FROM table_join " .
					")"
			],
		];
	}

	/**
	 * @dataProvider provideInsert
	 * @covers Wikimedia\Rdbms\Database::insert
	 */
	public function testInsert( $sql, $sqlText ) {
		$this->database->insert(
			$sql['table'],
			$sql['rows'],
			__METHOD__,
			$sql['options'] ?? []
		);
		$this->assertLastSql( $sqlText );
	}

	public static function provideInsert() {
		return [
			[
				[
					'table' => 'table',
					'rows' => [ 'field' => 'text', 'field2' => 2 ],
				],
				"INSERT INTO table " .
					"(field,field2) " .
					"VALUES ('text',2)"
			],
			[
				[
					'table' => 'table',
					'rows' => [ 'field' => 'text', 'field2' => 2 ],
					'options' => 'IGNORE',
				],
				"INSERT IGNORE INTO table " .
					"(field,field2) " .
					"VALUES ('text',2)"
			],
			[
				[
					'table' => 'table',
					'rows' => [
						[ 'field' => 'text', 'field2' => 2 ],
						[ 'field' => 'multi', 'field2' => 3 ],
					],
					'options' => 'IGNORE',
				],
				"INSERT IGNORE INTO table " .
					"(field,field2) " .
					"VALUES " .
					"('text',2)," .
					"('multi',3)"
			],
		];
	}

	/**
	 * @dataProvider provideInsertSelect
	 * @covers Wikimedia\Rdbms\Database::insertSelect
	 * @covers Wikimedia\Rdbms\Database::doInsertSelectNative
	 */
	public function testInsertSelect( $sql, $sqlTextNative, $sqlSelect, $sqlInsert ) {
		$this->database->insertSelect(
			$sql['destTable'],
			$sql['srcTable'],
			$sql['varMap'],
			$sql['conds'],
			__METHOD__,
			$sql['insertOptions'] ?? [],
			$sql['selectOptions'] ?? [],
			$sql['selectJoinConds'] ?? []
		);
		$this->assertLastSql( $sqlTextNative );

		$dbWeb = new DatabaseTestHelper( __CLASS__, [ 'cliMode' => false ] );
		$dbWeb->forceNextResult( [
			array_flip( array_keys( $sql['varMap'] ) )
		] );
		$dbWeb->insertSelect(
			$sql['destTable'],
			$sql['srcTable'],
			$sql['varMap'],
			$sql['conds'],
			__METHOD__,
			$sql['insertOptions'] ?? [],
			$sql['selectOptions'] ?? [],
			$sql['selectJoinConds'] ?? []
		);
		$this->assertLastSqlDb( implode( '; ', [ $sqlSelect, 'BEGIN', $sqlInsert, 'COMMIT' ] ), $dbWeb );
	}

	public static function provideInsertSelect() {
		return [
			[
				[
					'destTable' => 'insert_table',
					'srcTable' => 'select_table',
					'varMap' => [ 'field_insert' => 'field_select', 'field' => 'field2' ],
					'conds' => '*',
				],
				"INSERT INTO insert_table " .
					"(field_insert,field) " .
					"SELECT field_select,field2 " .
					"FROM select_table",
				"SELECT field_select AS field_insert,field2 AS field " .
				"FROM select_table      FOR UPDATE",
				"INSERT INTO insert_table (field_insert,field) VALUES (0,1)"
			],
			[
				[
					'destTable' => 'insert_table',
					'srcTable' => 'select_table',
					'varMap' => [ 'field_insert' => 'field_select', 'field' => 'field2' ],
					'conds' => [ 'field' => 2 ],
				],
				"INSERT INTO insert_table " .
					"(field_insert,field) " .
					"SELECT field_select,field2 " .
					"FROM select_table " .
					"WHERE field = 2",
				"SELECT field_select AS field_insert,field2 AS field FROM " .
				"select_table WHERE field = 2   FOR UPDATE",
				"INSERT INTO insert_table (field_insert,field) VALUES (0,1)"
			],
			[
				[
					'destTable' => 'insert_table',
					'srcTable' => 'select_table',
					'varMap' => [ 'field_insert' => 'field_select', 'field' => 'field2' ],
					'conds' => [ 'field' => 2 ],
					'insertOptions' => 'IGNORE',
					'selectOptions' => [ 'ORDER BY' => 'field' ],
				],
				"INSERT IGNORE INTO insert_table " .
					"(field_insert,field) " .
					"SELECT field_select,field2 " .
					"FROM select_table " .
					"WHERE field = 2 " .
					"ORDER BY field",
				"SELECT field_select AS field_insert,field2 AS field " .
				"FROM select_table WHERE field = 2 ORDER BY field  FOR UPDATE",
				"INSERT IGNORE INTO insert_table (field_insert,field) VALUES (0,1)"
			],
			[
				[
					'destTable' => 'insert_table',
					'srcTable' => [ 'select_table1', 'select_table2' ],
					'varMap' => [ 'field_insert' => 'field_select', 'field' => 'field2' ],
					'conds' => [ 'field' => 2 ],
					'insertOptions' => [ 'NO_AUTO_COLUMNS' ],
					'selectOptions' => [ 'ORDER BY' => 'field', 'FORCE INDEX' => [ 'select_table1' => 'index1' ] ],
					'selectJoinConds' => [
						'select_table2' => [ 'LEFT JOIN', [ 'select_table1.foo = select_table2.bar' ] ],
					],
				],
				"INSERT INTO insert_table " .
					"(field_insert,field) " .
					"SELECT field_select,field2 " .
					"FROM select_table1 LEFT JOIN select_table2 ON ((select_table1.foo = select_table2.bar)) " .
					"WHERE field = 2 " .
					"ORDER BY field",
				"SELECT field_select AS field_insert,field2 AS field " .
				"FROM select_table1 LEFT JOIN select_table2 ON ((select_table1.foo = select_table2.bar)) " .
				"WHERE field = 2 ORDER BY field  FOR UPDATE",
				"INSERT INTO insert_table (field_insert,field) VALUES (0,1)"
			],
		];
	}

	/**
	 * @covers Wikimedia\Rdbms\Database::insertSelect
	 * @covers Wikimedia\Rdbms\Database::doInsertSelectNative
	 */
	public function testInsertSelectBatching() {
		$dbWeb = new DatabaseTestHelper( __CLASS__, [ 'cliMode' => false ] );
		$rows = [];
		for ( $i = 0; $i <= 25000; $i++ ) {
			$rows[] = [ 'field' => $i ];
		}
		$dbWeb->forceNextResult( $rows );
		$dbWeb->insertSelect(
			'insert_table',
			'select_table',
			[ 'field' => 'field2' ],
			'*',
			__METHOD__
		);
		$this->assertLastSqlDb( implode( '; ', [
			'SELECT field2 AS field FROM select_table      FOR UPDATE',
			'BEGIN',
			"INSERT INTO insert_table (field) VALUES (" . implode( "),(", range( 0, 9999 ) ) . ")",
			"INSERT INTO insert_table (field) VALUES (" . implode( "),(", range( 10000, 19999 ) ) . ")",
			"INSERT INTO insert_table (field) VALUES (" . implode( "),(", range( 20000, 25000 ) ) . ")",
			'COMMIT'
		] ), $dbWeb );
	}

	/**
	 * @dataProvider provideReplace
	 * @covers Wikimedia\Rdbms\Database::replace
	 */
	public function testReplace( $sql, $sqlText ) {
		$this->database->replace(
			$sql['table'],
			$sql['uniqueIndexes'],
			$sql['rows'],
			__METHOD__
		);
		$this->assertLastSql( $sqlText );
	}

	public static function provideReplace() {
		return [
			[
				[
					'table' => 'replace_table',
					'uniqueIndexes' => [ 'field' ],
					'rows' => [ 'field' => 'text', 'field2' => 'text2' ],
				],
				"BEGIN; DELETE FROM replace_table " .
					"WHERE (field = 'text'); " .
					"INSERT INTO replace_table " .
					"(field,field2) " .
					"VALUES ('text','text2'); COMMIT"
			],
			[
				[
					'table' => 'module_deps',
					'uniqueIndexes' => [ [ 'md_module', 'md_skin' ] ],
					'rows' => [
						'md_module' => 'module',
						'md_skin' => 'skin',
						'md_deps' => 'deps',
					],
				],
				"BEGIN; DELETE FROM module_deps " .
					"WHERE (md_module = 'module' AND md_skin = 'skin'); " .
					"INSERT INTO module_deps " .
					"(md_module,md_skin,md_deps) " .
					"VALUES ('module','skin','deps'); COMMIT"
			],
			[
				[
					'table' => 'module_deps',
					'uniqueIndexes' => [ [ 'md_module', 'md_skin' ] ],
					'rows' => [
						[
							'md_module' => 'module',
							'md_skin' => 'skin',
							'md_deps' => 'deps',
						], [
							'md_module' => 'module2',
							'md_skin' => 'skin2',
							'md_deps' => 'deps2',
						],
					],
				],
				"BEGIN; DELETE FROM module_deps " .
					"WHERE (md_module = 'module' AND md_skin = 'skin'); " .
					"INSERT INTO module_deps " .
					"(md_module,md_skin,md_deps) " .
					"VALUES ('module','skin','deps'); " .
					"DELETE FROM module_deps " .
					"WHERE (md_module = 'module2' AND md_skin = 'skin2'); " .
					"INSERT INTO module_deps " .
					"(md_module,md_skin,md_deps) " .
					"VALUES ('module2','skin2','deps2'); COMMIT"
			],
			[
				[
					'table' => 'module_deps',
					'uniqueIndexes' => [],
					'rows' => [
						'md_module' => 'module',
						'md_skin' => 'skin',
						'md_deps' => 'deps',
					],
				],
				"INSERT INTO module_deps " .
					"(md_module,md_skin,md_deps) " .
					"VALUES ('module','skin','deps')"
			],
		];
	}

	/**
	 * @dataProvider provideConditional
	 * @covers Wikimedia\Rdbms\Database::conditional
	 */
	public function testConditional( $sql, $sqlText ) {
		$this->assertEquals( $this->database->conditional(
			$sql['conds'],
			$sql['true'],
			$sql['false']
		), $sqlText );
	}

	public static function provideConditional() {
		return [
			[
				[
					'conds' => [ 'field' => 'text' ],
					'true' => 1,
					'false' => 'NULL',
				],
				"(CASE WHEN field = 'text' THEN 1 ELSE NULL END)"
			],
			[
				[
					'conds' => [ 'field' => 'text', 'field2' => 'anothertext' ],
					'true' => 1,
					'false' => 'NULL',
				],
				"(CASE WHEN field = 'text' AND field2 = 'anothertext' THEN 1 ELSE NULL END)"
			],
			[
				[
					'conds' => 'field=1',
					'true' => 1,
					'false' => 'NULL',
				],
				"(CASE WHEN field=1 THEN 1 ELSE NULL END)"
			],
		];
	}

	/**
	 * @dataProvider provideBuildConcat
	 * @covers Wikimedia\Rdbms\Database::buildConcat
	 */
	public function testBuildConcat( $stringList, $sqlText ) {
		$this->assertEquals( trim( $this->database->buildConcat(
			$stringList
		) ), $sqlText );
	}

	public static function provideBuildConcat() {
		return [
			[
				[ 'field', 'field2' ],
				"CONCAT(field,field2)"
			],
			[
				[ "'test'", 'field2' ],
				"CONCAT('test',field2)"
			],
		];
	}

	/**
	 * @dataProvider provideGreatest
	 * @covers Wikimedia\Rdbms\Database::buildGreatest
	 */
	public function testBuildGreatest( $fields, $values, $sqlText ) {
		$this->assertEquals(
			$sqlText,
			trim( $this->platform->buildGreatest( $fields, $values ) )
		);
	}

	public static function provideGreatest() {
		return [
			[
				'field',
				'value',
				"GREATEST(field,'value')"
			],
			[
				[ 'field' ],
				[ 'value' ],
				"GREATEST(field,'value')"
			],
			[
				[ 'field', 'field2' ],
				[ 'value', 'value2' ],
				"GREATEST(field,field2,'value','value2')"
			],
			[
				[ 'field', 'b' => 'field2 + 1' ],
				[ 'value', 'value2' ],
				"GREATEST(field,field2 + 1,'value','value2')"
			],
		];
	}

	/**
	 * @dataProvider provideLeast
	 * @covers Wikimedia\Rdbms\Database::buildLeast
	 */
	public function testBuildLeast( $fields, $values, $sqlText ) {
		$this->assertEquals(
			$sqlText,
			trim( $this->platform->buildLeast( $fields, $values ) )
		);
	}

	public static function provideLeast() {
		return [
			[
				'field',
				'value',
				"LEAST(field,'value')"
			],
			[
				[ 'field' ],
				[ 'value' ],
				"LEAST(field,'value')"
			],
			[
				[ 'field', 'field2' ],
				[ 'value', 'value2' ],
				"LEAST(field,field2,'value','value2')"
			],
			[
				[ 'field', 'b' => 'field2 + 1' ],
				[ 'value', 'value2' ],
				"LEAST(field,field2 + 1,'value','value2')"
			],
		];
	}

	/**
	 * @dataProvider provideBuildLike
	 * @covers Wikimedia\Rdbms\Database::buildLike
	 * @covers Wikimedia\Rdbms\Platform\SQLPlatform::escapeLikeInternal
	 */
	public function testBuildLike( $array, $sqlText ) {
		$this->assertEquals( trim( $this->platform->buildLike(
			$array
		) ), $sqlText );
	}

	public static function provideBuildLike() {
		return [
			[
				'text',
				"LIKE 'text' ESCAPE '`'"
			],
			[
				[ 'text', new LikeMatch( '%' ) ],
				"LIKE 'text%' ESCAPE '`'"
			],
			[
				[ 'text', new LikeMatch( '%' ), 'text2' ],
				"LIKE 'text%text2' ESCAPE '`'"
			],
			[
				[ 'text', new LikeMatch( '_' ) ],
				"LIKE 'text_' ESCAPE '`'"
			],
			[
				'more_text',
				"LIKE 'more`_text' ESCAPE '`'"
			],
			[
				[ 'C:\\Windows\\', new LikeMatch( '%' ) ],
				"LIKE 'C:\\Windows\\%' ESCAPE '`'"
			],
			[
				[ 'accent`_test`', new LikeMatch( '%' ) ],
				"LIKE 'accent```_test``%' ESCAPE '`'"
			],
		];
	}

	/**
	 * @dataProvider provideUnionQueries
	 * @covers Wikimedia\Rdbms\Database::unionQueries
	 */
	public function testUnionQueries( $sql, $sqlText ) {
		$this->assertEquals( trim( $this->database->unionQueries(
			$sql['sqls'],
			$sql['all']
		) ), $sqlText );
	}

	public static function provideUnionQueries() {
		return [
			[
				[
					'sqls' => [ 'RAW SQL', 'RAW2SQL' ],
					'all' => true,
				],
				"(RAW SQL) UNION ALL (RAW2SQL)"
			],
			[
				[
					'sqls' => [ 'RAW SQL', 'RAW2SQL' ],
					'all' => false,
				],
				"(RAW SQL) UNION (RAW2SQL)"
			],
			[
				[
					'sqls' => [ 'RAW SQL', 'RAW2SQL', 'RAW3SQL' ],
					'all' => false,
				],
				"(RAW SQL) UNION (RAW2SQL) UNION (RAW3SQL)"
			],
		];
	}

	/**
	 * @dataProvider provideUnionConditionPermutations
	 * @covers Wikimedia\Rdbms\Database::unionConditionPermutations
	 */
	public function testUnionConditionPermutations( $params, $expect ) {
		if ( isset( $params['unionSupportsOrderAndLimit'] ) ) {
			$this->platform->setUnionSupportsOrderAndLimit( $params['unionSupportsOrderAndLimit'] );
		}

		$sql = trim( $this->platform->unionConditionPermutations(
			$params['table'],
			$params['vars'],
			$params['permute_conds'],
			$params['extra_conds'] ?? '',
			'FNAME',
			$params['options'] ?? [],
			$params['join_conds'] ?? []
		) );
		$this->assertEquals( $expect, $sql );
	}

	public static function provideUnionConditionPermutations() {
		return [
			[
				[
					'table' => [ 'table1', 'table2' ],
					'vars' => [ 'field1', 'alias' => 'field2' ],
					'permute_conds' => [
						'field3' => [ 1, 2, 3 ],
						'duplicates' => [ 4, 5, 4 ],
						'empty' => [],
						'single' => [ 0 ],
					],
					'extra_conds' => 'table2.bar > 23',
					'options' => [
						'ORDER BY' => [ 'field1', 'alias' ],
						'INNER ORDER BY' => [ 'field1', 'field2' ],
						'LIMIT' => 100,
					],
					'join_conds' => [
						'table2' => [ 'JOIN', 'table1.foo_id = table2.foo_id' ],
					],
				],
				"(SELECT  field1,field2 AS alias  FROM table1 JOIN table2 ON ((table1.foo_id = table2.foo_id))   WHERE field3 = 1 AND duplicates = 4 AND single = 0 AND (table2.bar > 23)  ORDER BY field1,field2 LIMIT 100  ) UNION ALL " .
				"(SELECT  field1,field2 AS alias  FROM table1 JOIN table2 ON ((table1.foo_id = table2.foo_id))   WHERE field3 = 1 AND duplicates = 5 AND single = 0 AND (table2.bar > 23)  ORDER BY field1,field2 LIMIT 100  ) UNION ALL " .
				"(SELECT  field1,field2 AS alias  FROM table1 JOIN table2 ON ((table1.foo_id = table2.foo_id))   WHERE field3 = 2 AND duplicates = 4 AND single = 0 AND (table2.bar > 23)  ORDER BY field1,field2 LIMIT 100  ) UNION ALL " .
				"(SELECT  field1,field2 AS alias  FROM table1 JOIN table2 ON ((table1.foo_id = table2.foo_id))   WHERE field3 = 2 AND duplicates = 5 AND single = 0 AND (table2.bar > 23)  ORDER BY field1,field2 LIMIT 100  ) UNION ALL " .
				"(SELECT  field1,field2 AS alias  FROM table1 JOIN table2 ON ((table1.foo_id = table2.foo_id))   WHERE field3 = 3 AND duplicates = 4 AND single = 0 AND (table2.bar > 23)  ORDER BY field1,field2 LIMIT 100  ) UNION ALL " .
				"(SELECT  field1,field2 AS alias  FROM table1 JOIN table2 ON ((table1.foo_id = table2.foo_id))   WHERE field3 = 3 AND duplicates = 5 AND single = 0 AND (table2.bar > 23)  ORDER BY field1,field2 LIMIT 100  ) " .
				"ORDER BY field1,alias LIMIT 100"
			],
			[
				[
					'table' => 'foo',
					'vars' => [ 'foo_id' ],
					'permute_conds' => [
						'bar' => [ 1, 2, 3 ],
					],
					'extra_conds' => [ 'baz' => null ],
					'options' => [
						'NOTALL',
						'ORDER BY' => [ 'foo_id' ],
						'LIMIT' => 25,
					],
				],
				"(SELECT  foo_id  FROM foo    WHERE bar = 1 AND baz IS NULL  ORDER BY foo_id LIMIT 25  ) UNION " .
				"(SELECT  foo_id  FROM foo    WHERE bar = 2 AND baz IS NULL  ORDER BY foo_id LIMIT 25  ) UNION " .
				"(SELECT  foo_id  FROM foo    WHERE bar = 3 AND baz IS NULL  ORDER BY foo_id LIMIT 25  ) " .
				"ORDER BY foo_id LIMIT 25"
			],
			[
				[
					'table' => 'foo',
					'vars' => [ 'foo_id' ],
					'permute_conds' => [
						'bar' => [ 1, 2, 3 ],
					],
					'extra_conds' => [ 'baz' => null ],
					'options' => [
						'NOTALL' => true,
						'ORDER BY' => [ 'foo_id' ],
						'LIMIT' => 25,
					],
					'unionSupportsOrderAndLimit' => false,
				],
				"(SELECT  foo_id  FROM foo    WHERE bar = 1 AND baz IS NULL  ) UNION " .
				"(SELECT  foo_id  FROM foo    WHERE bar = 2 AND baz IS NULL  ) UNION " .
				"(SELECT  foo_id  FROM foo    WHERE bar = 3 AND baz IS NULL  ) " .
				"ORDER BY foo_id LIMIT 25"
			],
			[
				[
					'table' => 'foo',
					'vars' => [ 'foo_id' ],
					'permute_conds' => [],
					'extra_conds' => [ 'baz' => null ],
					'options' => [
						'ORDER BY' => [ 'foo_id' ],
						'LIMIT' => 25,
					],
				],
				"SELECT  foo_id  FROM foo    WHERE baz IS NULL  ORDER BY foo_id LIMIT 25"
			],
			[
				[
					'table' => 'foo',
					'vars' => [ 'foo_id' ],
					'permute_conds' => [
						'bar' => [],
					],
					'extra_conds' => [ 'baz' => null ],
					'options' => [
						'ORDER BY' => [ 'foo_id' ],
						'LIMIT' => 25,
					],
				],
				"SELECT  foo_id  FROM foo    WHERE baz IS NULL  ORDER BY foo_id LIMIT 25"
			],
			[
				[
					'table' => 'foo',
					'vars' => [ 'foo_id' ],
					'permute_conds' => [
						'bar' => [ 1 ],
					],
					'options' => [
						'ORDER BY' => [ 'foo_id' ],
						'LIMIT' => 25,
						'OFFSET' => 150,
					],
				],
				"SELECT  foo_id  FROM foo    WHERE bar = 1  ORDER BY foo_id LIMIT 150,25"
			],
			[
				[
					'table' => 'foo',
					'vars' => [ 'foo_id' ],
					'permute_conds' => [],
					'extra_conds' => [ 'baz' => null ],
					'options' => [
						'ORDER BY' => [ 'foo_id' ],
						'LIMIT' => 25,
						'OFFSET' => 150,
						'INNER ORDER BY' => [ 'bar_id' ],
					],
				],
				"(SELECT  foo_id  FROM foo    WHERE baz IS NULL  ORDER BY bar_id LIMIT 175  ) ORDER BY foo_id LIMIT 150,25"
			],
			[
				[
					'table' => 'foo',
					'vars' => [ 'foo_id' ],
					'permute_conds' => [],
					'extra_conds' => [ 'baz' => null ],
					'options' => [
						'ORDER BY' => [ 'foo_id' ],
						'LIMIT' => 25,
						'OFFSET' => 150,
						'INNER ORDER BY' => [ 'bar_id' ],
					],
					'unionSupportsOrderAndLimit' => false,
				],
				"SELECT  foo_id  FROM foo    WHERE baz IS NULL  ORDER BY foo_id LIMIT 150,25"
			],
		];
		// phpcs:enable
	}

	/**
	 * @covers Wikimedia\Rdbms\Database::commit
	 * @covers Wikimedia\Rdbms\Database::doCommit
	 */
	public function testTransactionCommit() {
		$this->database->begin( __METHOD__ );
		$this->database->commit( __METHOD__ );
		$this->assertLastSql( 'BEGIN; COMMIT' );
	}

	/**
	 * @covers Wikimedia\Rdbms\Database::rollback
	 * @covers Wikimedia\Rdbms\Platform\SQLPlatform::rollbackSqlText
	 */
	public function testTransactionRollback() {
		$this->database->begin( __METHOD__ );
		$this->database->rollback( __METHOD__ );
		$this->assertLastSql( 'BEGIN; ROLLBACK' );
	}

	/**
	 * @covers Wikimedia\Rdbms\Database::dropTable
	 */
	public function testDropTable() {
		$this->database->setExistingTables( [ 'table' ] );
		$this->database->dropTable( 'table', __METHOD__ );
		$this->assertLastSql( 'DROP TABLE table CASCADE' );
	}

	/**
	 * @covers Wikimedia\Rdbms\Database::dropTable
	 */
	public function testDropNonExistingTable() {
		$this->assertFalse(
			$this->database->dropTable( 'non_existing', __METHOD__ )
		);
	}

	/**
	 * @dataProvider provideMakeList
	 * @covers Wikimedia\Rdbms\Database::makeList
	 */
	public function testMakeList( $list, $mode, $sqlText ) {
		$this->assertEquals( trim( $this->database->makeList(
			$list, $mode
		) ), $sqlText );
	}

	public static function provideMakeList() {
		return [
			[
				[ 'value', 'value2' ],
				LIST_COMMA,
				"'value','value2'"
			],
			[
				[ 'field', 'field2' ],
				LIST_NAMES,
				"field,field2"
			],
			[
				[ 'field' => 'value', 'field2' => 'value2' ],
				LIST_AND,
				"field = 'value' AND field2 = 'value2'"
			],
			[
				[ 'field' => null, "field2 != 'value2'" ],
				LIST_AND,
				"field IS NULL AND (field2 != 'value2')"
			],
			[
				[ 'field' => [ 'value', null, 'value2' ], 'field2' => 'value2' ],
				LIST_AND,
				"(field IN ('value','value2')  OR field IS NULL) AND field2 = 'value2'"
			],
			[
				[ 'field' => [ null ], 'field2' => null ],
				LIST_AND,
				"field IS NULL AND field2 IS NULL"
			],
			[
				[ 'field' => 'value', 'field2' => 'value2' ],
				LIST_OR,
				"field = 'value' OR field2 = 'value2'"
			],
			[
				[ 'field' => 'value', 'field2' => null ],
				LIST_OR,
				"field = 'value' OR field2 IS NULL"
			],
			[
				[ 'field' => [ 'value', 'value2' ], 'field2' => [ 'value' ] ],
				LIST_OR,
				"field IN ('value','value2')  OR field2 = 'value'"
			],
			[
				[ 'field' => [ null, 'value', null, 'value2' ], "field2 != 'value2'" ],
				LIST_OR,
				"(field IN ('value','value2')  OR field IS NULL) OR (field2 != 'value2')"
			],
			[
				[ 'field' => 'value', 'field2' => 'value2' ],
				LIST_SET,
				"field = 'value',field2 = 'value2'"
			],
			[
				[ 'field' => 'value', 'field2' => null ],
				LIST_SET,
				"field = 'value',field2 = NULL"
			],
			[
				[ 'field' => 'value', "field2 != 'value2'" ],
				LIST_SET,
				"field = 'value',field2 != 'value2'"
			],
		];
	}

	/**
	 * @dataProvider provideFactorConds
	 * @covers Wikimedia\Rdbms\Database::factorConds
	 */
	public function testFactorConds( $input, $expected ) {
		if ( $expected === 'invalid' ) {
			$this->expectException( InvalidArgumentException::class );
		}
		$this->assertSame( $expected, $this->database->factorConds( $input ) );
	}

	public static function provideFactorConds() {
		return [
			[
				[],
				'invalid'
			],
			[
				[ [] ],
				'invalid'
			],
			[
				[ [ 'a' => 1 ] ],
				"a = 1"
			],
			[
				[ [ 'a' => null ] ],
				"a IS NULL"
			],
			[
				[ [ 'a' => 1 ], [ 'b' => 2 ] ],
				'a = 1 OR b = 2'
			],
			[
				[ [ 'a' => 1 ], [ 'a' => 2 ] ],
				'a IN (1,2) '
			],
			[
				[ [ 'a' => 1, 'b' => 2 ], [ 'a' => 1, 'b' => 3 ] ],
				'(a = 1 AND b IN (2,3) )'
			],
			[
				[ [ 'a' => 1, 'b' => 2 ], [ 'a' => 1, 'b' => 3 ], [ 'c' => 4 ] ],
				'(a = 1 AND b IN (2,3) ) OR c = 4'
			],
			[
				[ [ 'a' => null, 'b' => 2 ], [ 'a' => null, 'b' => 3 ] ],
				'(a IS NULL AND b IN (2,3) )'
			],
			[
				[ [ 'a' => null, 'b' => 2 ], [ 'a' => 1, 'b' => 3 ] ],
				'((a = 1 AND b = 3) OR (a IS NULL AND b = 2))'
			],
			[
				[ [ 'a' => 1, 'b' => 2 ], [ 'a' => 2, 'b' => 2 ] ],
				'((a = 1 AND b = 2) OR (a = 2 AND b = 2))'
			],
			[
				[
					[ 'a' => 1, 'b' => 1, 'c' => 1 ],
					[ 'a' => 1, 'b' => 1, 'c' => 2 ],
					[ 'a' => 1, 'b' => 2, 'c' => 1 ],
					[ 'a' => 1, 'b' => 2, 'c' => 2 ],
					[ 'a' => 2, 'b' => 1, 'c' => 1 ],
					[ 'a' => 2, 'b' => 1, 'c' => 2 ],
					[ 'a' => 2, 'b' => 2, 'c' => 1 ],
					[ 'a' => 2, 'b' => 2, 'c' => 2 ],
				],
				'((a = 1 AND ((b = 1 AND c IN (1,2) ) OR (b = 2 AND c IN (1,2) ))) OR ' .
				'(a = 2 AND ((b = 1 AND c IN (1,2) ) OR (b = 2 AND c IN (1,2) ))))'
			]
		];
	}

	/**
	 * @covers Wikimedia\Rdbms\Database::getTempTableWrites
	 */
	public function testSessionTempTables() {
		$temp1 = $this->database->tableName( 'tmp_table_1' );
		$temp2 = $this->database->tableName( 'tmp_table_2' );
		$temp3 = $this->database->tableName( 'tmp_table_3' );

		$this->database->query( "CREATE TEMPORARY TABLE $temp1 LIKE orig_tbl", __METHOD__ );
		$this->database->query( "CREATE TEMPORARY TABLE $temp2 LIKE orig_tbl", __METHOD__ );
		$this->database->query( "CREATE TEMPORARY TABLE $temp3 LIKE orig_tbl", __METHOD__ );

		$this->assertTrue( $this->database->tableExists( "tmp_table_1", __METHOD__ ) );
		$this->assertTrue( $this->database->tableExists( "tmp_table_2", __METHOD__ ) );
		$this->assertTrue( $this->database->tableExists( "tmp_table_3", __METHOD__ ) );

		$this->database->dropTable( 'tmp_table_1', __METHOD__ );
		$this->database->dropTable( 'tmp_table_2', __METHOD__ );
		$this->database->dropTable( 'tmp_table_3', __METHOD__ );

		$this->assertFalse( $this->database->tableExists( "tmp_table_1", __METHOD__ ) );
		$this->assertFalse( $this->database->tableExists( "tmp_table_2", __METHOD__ ) );
		$this->assertFalse( $this->database->tableExists( "tmp_table_3", __METHOD__ ) );

		$this->database->query( "CREATE TEMPORARY TABLE tmp_table_1 LIKE orig_tbl", __METHOD__ );
		$this->database->query( "CREATE TEMPORARY TABLE 'tmp_table_2' LIKE orig_tbl", __METHOD__ );
		$this->database->query( "CREATE TEMPORARY TABLE `tmp_table_3` LIKE orig_tbl", __METHOD__ );

		$this->assertTrue( $this->database->tableExists( "tmp_table_1", __METHOD__ ) );
		$this->assertTrue( $this->database->tableExists( "tmp_table_2", __METHOD__ ) );
		$this->assertTrue( $this->database->tableExists( "tmp_table_3", __METHOD__ ) );

		$this->database->query( "DROP TEMPORARY TABLE tmp_table_1 LIKE orig_tbl", __METHOD__ );
		$this->database->query( "DROP TEMPORARY TABLE 'tmp_table_2' LIKE orig_tbl", __METHOD__ );
		$this->database->query( "DROP TABLE `tmp_table_3` LIKE orig_tbl", __METHOD__ );

		$this->assertFalse( $this->database->tableExists( "tmp_table_1", __METHOD__ ) );
		$this->assertFalse( $this->database->tableExists( "tmp_table_2", __METHOD__ ) );
		$this->assertFalse( $this->database->tableExists( "tmp_table_3", __METHOD__ ) );

		$this->database->query( "DROP TEMPORARY TABLE IF EXISTS `tmp_table_4`,  `tmp_table_5`", __METHOD__ );
	}

	public function provideBuildSubstring() {
		yield [ 'someField', 1, 2, 'SUBSTRING(someField FROM 1 FOR 2)' ];
		yield [ 'someField', 1, null, 'SUBSTRING(someField FROM 1)' ];
	}

	/**
	 * @covers Wikimedia\Rdbms\Database::buildSubstring
	 * @dataProvider provideBuildSubstring
	 */
	public function testBuildSubstring( $input, $start, $length, $expected ) {
		$output = $this->database->buildSubstring( $input, $start, $length );
		$this->assertSame( $expected, $output );
	}

	public function provideBuildSubstring_invalidParams() {
		yield [ -1, 1 ];
		yield [ 1, -1 ];
		yield [ 1, 'foo' ];
		yield [ 'foo', 1 ];
		yield [ null, 1 ];
		yield [ 0, 1 ];
	}

	/**
	 * @covers Wikimedia\Rdbms\Database::buildSubstring
	 * @covers Wikimedia\Rdbms\Platform\SQLPlatform::assertBuildSubstringParams
	 * @dataProvider provideBuildSubstring_invalidParams
	 */
	public function testBuildSubstring_invalidParams( $start, $length ) {
		$this->expectException( InvalidArgumentException::class );
		$this->database->buildSubstring( 'foo', $start, $length );
	}

	/**
	 * @covers \Wikimedia\Rdbms\Database::buildIntegerCast
	 */
	public function testBuildIntegerCast() {
		$output = $this->database->buildIntegerCast( 'fieldName' );
		$this->assertSame( 'CAST( fieldName AS INTEGER )', $output );
	}

	/**
	 * @covers \Wikimedia\Rdbms\Platform\SQLPlatform::savepointSqlText
	 * @covers \Wikimedia\Rdbms\Platform\SQLPlatform::releaseSavepointSqlText
	 * @covers \Wikimedia\Rdbms\Platform\SQLPlatform::rollbackToSavepointSqlText
	 * @covers \Wikimedia\Rdbms\Database::startAtomic
	 * @covers \Wikimedia\Rdbms\Database::endAtomic
	 * @covers \Wikimedia\Rdbms\Database::cancelAtomic
	 * @covers \Wikimedia\Rdbms\Database::doAtomicSection
	 */
	public function testAtomicSections() {
		$this->database->startAtomic( __METHOD__ );
		$this->database->endAtomic( __METHOD__ );
		$this->assertLastSql( 'BEGIN; COMMIT' );

		$this->database->startAtomic( __METHOD__, IDatabase::ATOMIC_CANCELABLE );
		$this->database->cancelAtomic( __METHOD__ );
		$this->assertLastSql( 'BEGIN; ROLLBACK' );

		$this->database->begin( __METHOD__ );
		$this->database->startAtomic( __METHOD__ );
		$this->database->endAtomic( __METHOD__ );
		$this->database->commit( __METHOD__ );
		$this->assertLastSql( 'BEGIN; COMMIT' );

		$this->database->begin( __METHOD__ );
		$this->database->startAtomic( __METHOD__, IDatabase::ATOMIC_CANCELABLE );
		$this->database->endAtomic( __METHOD__ );
		$this->database->commit( __METHOD__ );
		$this->assertLastSql( 'BEGIN; SAVEPOINT wikimedia_rdbms_atomic1; RELEASE SAVEPOINT wikimedia_rdbms_atomic1; COMMIT' );

		$this->database->begin( __METHOD__ );
		$this->database->startAtomic( __METHOD__, IDatabase::ATOMIC_CANCELABLE );
		$this->database->cancelAtomic( __METHOD__ );
		$this->database->commit( __METHOD__ );
		$this->assertLastSql( 'BEGIN; SAVEPOINT wikimedia_rdbms_atomic1; ROLLBACK TO SAVEPOINT wikimedia_rdbms_atomic1; COMMIT' );

		$this->database->startAtomic( __METHOD__, IDatabase::ATOMIC_CANCELABLE );
		$this->database->startAtomic( __METHOD__, IDatabase::ATOMIC_CANCELABLE );
		$this->database->cancelAtomic( __METHOD__ );
		$this->database->endAtomic( __METHOD__ );
		$this->assertLastSql( 'BEGIN; SAVEPOINT wikimedia_rdbms_atomic1; ROLLBACK TO SAVEPOINT wikimedia_rdbms_atomic1; COMMIT' );

		$noOpCallack = static function () {
		};

		$this->database->doAtomicSection( __METHOD__, $noOpCallack, IDatabase::ATOMIC_CANCELABLE );
		$this->assertLastSql( 'BEGIN; COMMIT' );

		$this->database->doAtomicSection( __METHOD__, $noOpCallack );
		$this->assertLastSql( 'BEGIN; COMMIT' );

		$this->database->begin( __METHOD__ );
		$this->database->doAtomicSection( __METHOD__, $noOpCallack, IDatabase::ATOMIC_CANCELABLE );
		$this->database->rollback( __METHOD__ );
		$this->assertLastSql( 'BEGIN; SAVEPOINT wikimedia_rdbms_atomic1; RELEASE SAVEPOINT wikimedia_rdbms_atomic1; ROLLBACK' );

		$fname = __METHOD__;
		$triggerMap = [
			'-' => '-',
			IDatabase::TRIGGER_COMMIT => 'tCommit',
			IDatabase::TRIGGER_ROLLBACK => 'tRollback',
			IDatabase::TRIGGER_CANCEL => 'tCancel',
		];
		$pcCallback = function ( IDatabase $db ) use ( $fname ) {
			$this->database->query( "SELECT 0", $fname );
		};
		$callback1 = function ( $trigger = '-' ) use ( $fname, $triggerMap ) {
			$this->database->query( "SELECT 1, {$triggerMap[$trigger]} AS t", $fname );
		};
		$callback2 = function ( $trigger = '-' ) use ( $fname, $triggerMap ) {
			$this->database->query( "SELECT 2, {$triggerMap[$trigger]} AS t", $fname );
		};
		$callback3 = function ( $trigger = '-' ) use ( $fname, $triggerMap ) {
			$this->database->query( "SELECT 3, {$triggerMap[$trigger]} AS t", $fname );
		};

		$this->database->startAtomic( __METHOD__, IDatabase::ATOMIC_CANCELABLE );
		$this->database->onTransactionPreCommitOrIdle( $pcCallback, __METHOD__ );
		$this->database->cancelAtomic( __METHOD__ );
		$this->assertLastSql( 'BEGIN; ROLLBACK' );

		$this->database->startAtomic( __METHOD__, IDatabase::ATOMIC_CANCELABLE );
		$this->database->onTransactionCommitOrIdle( $callback1, __METHOD__ );
		$this->database->cancelAtomic( __METHOD__ );
		$this->assertLastSql( 'BEGIN; ROLLBACK' );

		$this->database->startAtomic( __METHOD__, IDatabase::ATOMIC_CANCELABLE );
		$this->database->onTransactionResolution( $callback1, __METHOD__ );
		$this->database->cancelAtomic( __METHOD__ );
		$this->assertLastSql( 'BEGIN; ROLLBACK; SELECT 1, tRollback AS t' );

		$this->database->startAtomic( __METHOD__, IDatabase::ATOMIC_CANCELABLE );
		$this->database->onAtomicSectionCancel( $callback1, __METHOD__ );
		$this->database->cancelAtomic( __METHOD__ );
		$this->assertLastSql( 'BEGIN; ROLLBACK; SELECT 1, tRollback AS t' );

		$this->database->startAtomic( __METHOD__ . '_outer' );
		$this->database->onTransactionPreCommitOrIdle( $pcCallback, __METHOD__ );
		$this->database->startAtomic( __METHOD__, IDatabase::ATOMIC_CANCELABLE );
		$this->database->onTransactionPreCommitOrIdle( $pcCallback, __METHOD__ );
		$this->database->cancelAtomic( __METHOD__ );
		$this->database->onTransactionPreCommitOrIdle( $pcCallback, __METHOD__ );
		$this->database->endAtomic( __METHOD__ . '_outer' );
		$this->assertLastSql( implode( "; ", [
			'BEGIN',
			'SAVEPOINT wikimedia_rdbms_atomic1',
			'ROLLBACK TO SAVEPOINT wikimedia_rdbms_atomic1',
			'SELECT 0',
			'SELECT 0',
			'COMMIT'
		] ) );

		$this->database->startAtomic( __METHOD__ . '_outer' );
		$this->database->onTransactionCommitOrIdle( $callback1, __METHOD__ );
		$this->database->startAtomic( __METHOD__, IDatabase::ATOMIC_CANCELABLE );
		$this->database->onTransactionCommitOrIdle( $callback2, __METHOD__ );
		$this->database->cancelAtomic( __METHOD__ );
		$this->database->onTransactionCommitOrIdle( $callback3, __METHOD__ );
		$this->database->endAtomic( __METHOD__ . '_outer' );
		$this->assertLastSql( implode( "; ", [
			'BEGIN',
			'SAVEPOINT wikimedia_rdbms_atomic1',
			'ROLLBACK TO SAVEPOINT wikimedia_rdbms_atomic1',
			'COMMIT',
			'SELECT 1, tCommit AS t',
			'SELECT 3, tCommit AS t'
		] ) );

		$this->database->startAtomic( __METHOD__ . '_outer' );
		$this->database->onTransactionResolution( $callback1, __METHOD__ );
		$this->database->startAtomic( __METHOD__, IDatabase::ATOMIC_CANCELABLE );
		$this->database->onTransactionResolution( $callback2, __METHOD__ );
		$this->database->cancelAtomic( __METHOD__ );
		$this->database->onTransactionResolution( $callback3, __METHOD__ );
		$this->database->endAtomic( __METHOD__ . '_outer' );
		$this->assertLastSql( implode( "; ", [
			'BEGIN',
			'SAVEPOINT wikimedia_rdbms_atomic1',
			'ROLLBACK TO SAVEPOINT wikimedia_rdbms_atomic1',
			'COMMIT',
			'SELECT 1, tCommit AS t',
			'SELECT 2, tRollback AS t',
			'SELECT 3, tCommit AS t'
		] ) );

		$this->database->startAtomic( __METHOD__ . '_outer' );
		$this->database->onAtomicSectionCancel( $callback1, __METHOD__ );
		$this->database->startAtomic( __METHOD__, IDatabase::ATOMIC_CANCELABLE );
		$this->database->onAtomicSectionCancel( $callback2, __METHOD__ );
		$this->database->cancelAtomic( __METHOD__ );
		$this->database->onAtomicSectionCancel( $callback3, __METHOD__ );
		$this->database->endAtomic( __METHOD__ . '_outer' );
		$this->assertLastSql( implode( "; ", [
			'BEGIN',
			'SAVEPOINT wikimedia_rdbms_atomic1',
			'ROLLBACK TO SAVEPOINT wikimedia_rdbms_atomic1',
			'SELECT 2, tCancel AS t',
			'COMMIT',
		] ) );

		$makeCallback = function ( $id ) use ( $fname, $triggerMap ) {
			return function ( $trigger = '-' ) use ( $id, $fname, $triggerMap ) {
				$this->database->query( "SELECT $id, {$triggerMap[$trigger]} AS t", $fname );
			};
		};

		$this->database->startAtomic( __METHOD__ . '_outer' );
		$this->database->startAtomic( __METHOD__, IDatabase::ATOMIC_CANCELABLE );
		$this->database->onTransactionResolution( $makeCallback( 1 ), __METHOD__ );
		$this->database->cancelAtomic( __METHOD__ );
		$this->database->endAtomic( __METHOD__ . '_outer' );
		$this->assertLastSql( implode( "; ", [
			'BEGIN',
			'SAVEPOINT wikimedia_rdbms_atomic1',
			'ROLLBACK TO SAVEPOINT wikimedia_rdbms_atomic1',
			'COMMIT',
			'SELECT 1, tRollback AS t'
		] ) );

		$this->database->startAtomic( __METHOD__ . '_level1', IDatabase::ATOMIC_CANCELABLE );
		$this->database->onTransactionResolution( $makeCallback( 1 ), __METHOD__ );
		$this->database->startAtomic( __METHOD__ . '_level2' );
		$this->database->startAtomic( __METHOD__ . '_level3', IDatabase::ATOMIC_CANCELABLE );
		$this->database->startAtomic( __METHOD__, IDatabase::ATOMIC_CANCELABLE );
		$this->database->onTransactionResolution( $makeCallback( 2 ), __METHOD__ );
		$this->database->endAtomic( __METHOD__ );
		$this->database->onTransactionResolution( $makeCallback( 3 ), __METHOD__ );
		$this->database->cancelAtomic( __METHOD__ . '_level3' );
		$this->database->endAtomic( __METHOD__ . '_level2' );
		$this->database->onTransactionResolution( $makeCallback( 4 ), __METHOD__ );
		$this->database->endAtomic( __METHOD__ . '_level1' );
		$this->assertLastSql( implode( "; ", [
			'BEGIN',
			'SAVEPOINT wikimedia_rdbms_atomic1',
			'SAVEPOINT wikimedia_rdbms_atomic2',
			'RELEASE SAVEPOINT wikimedia_rdbms_atomic2',
			'ROLLBACK TO SAVEPOINT wikimedia_rdbms_atomic1',
			'COMMIT; SELECT 1, tCommit AS t',
			'SELECT 2, tRollback AS t',
			'SELECT 3, tRollback AS t',
			'SELECT 4, tCommit AS t'
		] ) );

		$this->database->startAtomic( __METHOD__ . '_level1', IDatabase::ATOMIC_CANCELABLE );
		$this->database->onAtomicSectionCancel( $makeCallback( 1 ), __METHOD__ );
		$this->database->startAtomic( __METHOD__ . '_level2' );
		$this->database->startAtomic( __METHOD__ . '_level3', IDatabase::ATOMIC_CANCELABLE );
		$this->database->startAtomic( __METHOD__, IDatabase::ATOMIC_CANCELABLE );
		$this->database->onAtomicSectionCancel( $makeCallback( 2 ), __METHOD__ );
		$this->database->endAtomic( __METHOD__ );
		$this->database->onAtomicSectionCancel( $makeCallback( 3 ), __METHOD__ );
		$this->database->cancelAtomic( __METHOD__ . '_level3' );
		$this->database->endAtomic( __METHOD__ . '_level2' );
		$this->database->onAtomicSectionCancel( $makeCallback( 4 ), __METHOD__ );
		$this->database->endAtomic( __METHOD__ . '_level1' );
		$this->assertLastSql( implode( "; ", [
			'BEGIN',
			'SAVEPOINT wikimedia_rdbms_atomic1',
			'SAVEPOINT wikimedia_rdbms_atomic2',
			'RELEASE SAVEPOINT wikimedia_rdbms_atomic2',
			'ROLLBACK TO SAVEPOINT wikimedia_rdbms_atomic1',
			'SELECT 2, tCancel AS t',
			'SELECT 3, tCancel AS t',
			'COMMIT',
		] ) );
	}

	/**
	 * @covers \Wikimedia\Rdbms\Platform\SQLPlatform::savepointSqlText
	 * @covers \Wikimedia\Rdbms\Platform\SQLPlatform::releaseSavepointSqlText
	 * @covers \Wikimedia\Rdbms\Platform\SQLPlatform::rollbackToSavepointSqlText
	 * @covers \Wikimedia\Rdbms\Database::startAtomic
	 * @covers \Wikimedia\Rdbms\Database::endAtomic
	 * @covers \Wikimedia\Rdbms\Database::cancelAtomic
	 * @covers \Wikimedia\Rdbms\Database::doAtomicSection
	 */
	public function testAtomicSectionsRecovery() {
		$this->database->begin( __METHOD__ );
		try {
			$this->database->doAtomicSection(
				__METHOD__,
				function () {
					$this->database->startAtomic( 'inner_func1' );
					$this->database->startAtomic( 'inner_func2' );

					throw new RuntimeException( 'Test exception' );
				},
				IDatabase::ATOMIC_CANCELABLE
			);
			$this->fail( 'Expected exception not thrown' );
		} catch ( RuntimeException $ex ) {
			$this->assertSame( 'Test exception', $ex->getMessage() );
		}
		$this->database->commit( __METHOD__ );
		$this->assertLastSql( 'BEGIN; SAVEPOINT wikimedia_rdbms_atomic1; ROLLBACK TO SAVEPOINT wikimedia_rdbms_atomic1; COMMIT' );

		$this->database->begin( __METHOD__ );
		try {
			$this->database->doAtomicSection(
				__METHOD__,
				static function () {
					throw new RuntimeException( 'Test exception' );
				}
			);
			$this->fail( 'Test exception not thrown' );
		} catch ( RuntimeException $ex ) {
			$this->assertSame( 'Test exception', $ex->getMessage() );
		}
		try {
			$this->database->commit( __METHOD__ );
			$this->fail( 'Test exception not thrown' );
		} catch ( DBTransactionError $ex ) {
			$this->assertSame(
				'Cannot execute query from ' . __METHOD__ . ' while transaction status is ERROR',
				$ex->getMessage()
			);
		}
		$this->database->rollback( __METHOD__ );
		$this->assertLastSql( 'BEGIN; ROLLBACK' );
	}

	/**
	 * @covers \Wikimedia\Rdbms\Platform\SQLPlatform::savepointSqlText
	 * @covers \Wikimedia\Rdbms\Platform\SQLPlatform::releaseSavepointSqlText
	 * @covers \Wikimedia\Rdbms\Platform\SQLPlatform::rollbackToSavepointSqlText
	 * @covers \Wikimedia\Rdbms\Database::startAtomic
	 * @covers \Wikimedia\Rdbms\Database::endAtomic
	 * @covers \Wikimedia\Rdbms\Database::cancelAtomic
	 * @covers \Wikimedia\Rdbms\Database::doAtomicSection
	 */
	public function testAtomicSectionsCallbackCancellation() {
		$fname = __METHOD__;
		$callback1Called = null;
		$callback1 = function ( $trigger = '-' ) use ( $fname, &$callback1Called ) {
			$callback1Called = $trigger;
			$this->database->query( "SELECT 1", $fname );
		};
		$callback2Called = null;
		$callback2 = function ( $trigger = '-' ) use ( $fname, &$callback2Called ) {
			$callback2Called = $trigger;
			$this->database->query( "SELECT 2", $fname );
		};
		$callback3Called = null;
		$callback3 = function ( $trigger = '-' ) use ( $fname, &$callback3Called ) {
			$callback3Called = $trigger;
			$this->database->query( "SELECT 3", $fname );
		};
		$callback4Called = 0;
		$callback4 = function () use ( $fname, &$callback4Called ) {
			$callback4Called++;
			$this->database->query( "SELECT 4", $fname );
		};
		$callback5Called = 0;
		$callback5 = function () use ( $fname, &$callback5Called ) {
			$callback5Called++;
			$this->database->query( "SELECT 5", $fname );
		};

		$this->database->startAtomic( __METHOD__ . '_outer' );
		$this->database->startAtomic( __METHOD__, IDatabase::ATOMIC_CANCELABLE );
		$this->database->startAtomic( __METHOD__ . '_inner' );
		$this->database->onTransactionCommitOrIdle( $callback1, __METHOD__ );
		$this->database->onTransactionPreCommitOrIdle( $callback2, __METHOD__ );
		$this->database->onTransactionResolution( $callback3, __METHOD__ );
		$this->database->onAtomicSectionCancel( $callback4, __METHOD__ );
		$this->database->endAtomic( __METHOD__ . '_inner' );
		$this->database->cancelAtomic( __METHOD__ );
		$this->database->endAtomic( __METHOD__ . '_outer' );
		$this->assertNull( $callback1Called );
		$this->assertNull( $callback2Called );
		$this->assertEquals( IDatabase::TRIGGER_ROLLBACK, $callback3Called );
		$this->assertSame( 1, $callback4Called );
		$this->assertLastSql( 'BEGIN; SAVEPOINT wikimedia_rdbms_atomic1; ROLLBACK TO SAVEPOINT wikimedia_rdbms_atomic1; SELECT 4; COMMIT; SELECT 3' );

		$callback1Called = null;
		$callback2Called = null;
		$callback3Called = null;
		$callback4Called = 0;
		$this->database->startAtomic( __METHOD__ . '_outer' );
		$this->database->startAtomic( __METHOD__, IDatabase::ATOMIC_CANCELABLE );
		$this->database->startAtomic( __METHOD__ . '_inner', IDatabase::ATOMIC_CANCELABLE );
		$this->database->onTransactionCommitOrIdle( $callback1, __METHOD__ );
		$this->database->onTransactionPreCommitOrIdle( $callback2, __METHOD__ );
		$this->database->onTransactionResolution( $callback3, __METHOD__ );
		$this->database->onAtomicSectionCancel( $callback4, __METHOD__ );
		$this->database->endAtomic( __METHOD__ . '_inner' );
		$this->database->cancelAtomic( __METHOD__ );
		$this->database->endAtomic( __METHOD__ . '_outer' );
		$this->assertNull( $callback1Called );
		$this->assertNull( $callback2Called );
		$this->assertEquals( IDatabase::TRIGGER_ROLLBACK, $callback3Called );
		$this->assertSame( 1, $callback4Called );
		$this->assertLastSql( 'BEGIN; SAVEPOINT wikimedia_rdbms_atomic1; SAVEPOINT wikimedia_rdbms_atomic2; RELEASE SAVEPOINT wikimedia_rdbms_atomic2; ROLLBACK TO SAVEPOINT wikimedia_rdbms_atomic1; SELECT 4; COMMIT; SELECT 3' );

		$callback1Called = null;
		$callback2Called = null;
		$callback3Called = null;
		$callback4Called = 0;
		$this->database->startAtomic( __METHOD__ . '_outer' );
		$atomicId = $this->database->startAtomic( __METHOD__, IDatabase::ATOMIC_CANCELABLE );
		$this->database->startAtomic( __METHOD__ . '_inner' );
		$this->database->onTransactionCommitOrIdle( $callback1, __METHOD__ );
		$this->database->onTransactionPreCommitOrIdle( $callback2, __METHOD__ );
		$this->database->onTransactionResolution( $callback3, __METHOD__ );
		$this->database->onAtomicSectionCancel( $callback4, __METHOD__ );
		$this->database->cancelAtomic( __METHOD__, $atomicId );
		$this->database->endAtomic( __METHOD__ . '_outer' );
		$this->assertNull( $callback1Called );
		$this->assertNull( $callback2Called );
		$this->assertEquals( IDatabase::TRIGGER_ROLLBACK, $callback3Called );
		$this->assertSame( 1, $callback4Called );

		$callback1Called = null;
		$callback2Called = null;
		$callback3Called = null;
		$callback4Called = 0;
		$this->database->startAtomic( __METHOD__ . '_outer' );
		$atomicId = $this->database->startAtomic( __METHOD__, IDatabase::ATOMIC_CANCELABLE );
		$this->database->startAtomic( __METHOD__ . '_inner' );
		$this->database->onTransactionCommitOrIdle( $callback1, __METHOD__ );
		$this->database->onTransactionPreCommitOrIdle( $callback2, __METHOD__ );
		$this->database->onTransactionResolution( $callback3, __METHOD__ );
		$this->database->onAtomicSectionCancel( $callback4, __METHOD__ );
		try {
			$this->database->cancelAtomic( __METHOD__ . '_X', $atomicId );
		} catch ( DBUnexpectedError $e ) {
			$m = __METHOD__;
			$this->assertSame(
				"Invalid atomic section ended (got {$m}_X but expected {$m})",
				$e->getMessage()
			);
		}
		$this->database->cancelAtomic( __METHOD__ );
		$this->database->endAtomic( __METHOD__ . '_outer' );
		$this->assertNull( $callback1Called );
		$this->assertNull( $callback2Called );
		$this->assertEquals( IDatabase::TRIGGER_ROLLBACK, $callback3Called );
		$this->assertSame( 1, $callback4Called );

		$callback4Called = 0;
		$callback5Called = 0;
		$this->database->getLastSqls(); // flush
		$this->database->startAtomic( __METHOD__ . '_outer' );
		$this->database->startAtomic( __METHOD__, IDatabase::ATOMIC_CANCELABLE );
		$this->database->onAtomicSectionCancel( $callback5, __METHOD__ );
		$this->database->startAtomic( __METHOD__ . '_inner', IDatabase::ATOMIC_CANCELABLE );
		$this->database->onAtomicSectionCancel( $callback4, __METHOD__ );
		$this->database->cancelAtomic( __METHOD__ . '_inner' );
		$this->database->cancelAtomic( __METHOD__ );
		$this->database->endAtomic( __METHOD__ . '_outer' );
		$this->assertLastSql( 'BEGIN; SAVEPOINT wikimedia_rdbms_atomic1; SAVEPOINT wikimedia_rdbms_atomic2; ROLLBACK TO SAVEPOINT wikimedia_rdbms_atomic2; SELECT 4; ROLLBACK TO SAVEPOINT wikimedia_rdbms_atomic1; SELECT 5; COMMIT' );
		$this->assertSame( 1, $callback4Called );
		$this->assertSame( 1, $callback5Called );

		$callback4Called = 0;
		$callback5Called = 0;
		$this->database->startAtomic( __METHOD__ . '_outer' );
		$this->database->startAtomic( __METHOD__, IDatabase::ATOMIC_CANCELABLE );
		$this->database->onAtomicSectionCancel( $callback5, __METHOD__ );
		$this->database->startAtomic( __METHOD__ . '_inner', IDatabase::ATOMIC_CANCELABLE );
		$this->database->onAtomicSectionCancel( $callback4, __METHOD__ );
		$this->database->endAtomic( __METHOD__ . '_inner' );
		$this->database->cancelAtomic( __METHOD__ );
		$this->database->endAtomic( __METHOD__ . '_outer' );
		$this->assertLastSql( 'BEGIN; SAVEPOINT wikimedia_rdbms_atomic1; SAVEPOINT wikimedia_rdbms_atomic2; RELEASE SAVEPOINT wikimedia_rdbms_atomic2; ROLLBACK TO SAVEPOINT wikimedia_rdbms_atomic1; SELECT 5; SELECT 4; COMMIT' );
		$this->assertSame( 1, $callback4Called );
		$this->assertSame( 1, $callback5Called );

		$callback4Called = 0;
		$callback5Called = 0;
		$this->database->startAtomic( __METHOD__ . '_outer' );
		$sectionId = $this->database->startAtomic( __METHOD__, IDatabase::ATOMIC_CANCELABLE );
		$this->database->onAtomicSectionCancel( $callback5, __METHOD__ );
		$this->database->startAtomic( __METHOD__ . '_inner', IDatabase::ATOMIC_CANCELABLE );
		$this->database->onAtomicSectionCancel( $callback4, __METHOD__ );
		$this->database->cancelAtomic( __METHOD__, $sectionId );
		$this->database->endAtomic( __METHOD__ . '_outer' );
		$this->assertLastSql( 'BEGIN; SAVEPOINT wikimedia_rdbms_atomic1; SAVEPOINT wikimedia_rdbms_atomic2; ROLLBACK TO SAVEPOINT wikimedia_rdbms_atomic1; SELECT 5; SELECT 4; COMMIT' );
		$this->assertSame( 1, $callback4Called );
		$this->assertSame( 1, $callback5Called );

		$wrapper = TestingAccessWrapper::newFromObject( $this->database );
		$callback1Called = null;
		$callback2Called = null;
		$callback3Called = null;
		$callback4Called = 0;
		$this->database->startAtomic( __METHOD__ . '_outer' );
		$this->database->startAtomic( __METHOD__, IDatabase::ATOMIC_CANCELABLE );
		$this->database->startAtomic( __METHOD__ . '_inner' );
		$this->database->onTransactionCommitOrIdle( $callback1, __METHOD__ );
		$this->database->onTransactionPreCommitOrIdle( $callback2, __METHOD__ );
		$this->database->onTransactionResolution( $callback3, __METHOD__ );
		$this->database->onAtomicSectionCancel( $callback4, __METHOD__ );
		$wrapper->transactionManager->setTransactionError( new DBUnexpectedError( null, 'error' ) );
		$this->database->cancelAtomic( __METHOD__ . '_inner' );
		$this->database->cancelAtomic( __METHOD__ );
		$this->database->endAtomic( __METHOD__ . '_outer' );
		$this->assertNull( $callback1Called );
		$this->assertNull( $callback2Called );
		$this->assertEquals( IDatabase::TRIGGER_ROLLBACK, $callback3Called );
		$this->assertSame( 1, $callback4Called );
	}

	/**
	 * @covers \Wikimedia\Rdbms\Platform\SQLPlatform::savepointSqlText
	 * @covers \Wikimedia\Rdbms\Platform\SQLPlatform::releaseSavepointSqlText
	 * @covers \Wikimedia\Rdbms\Platform\SQLPlatform::rollbackToSavepointSqlText
	 * @covers \Wikimedia\Rdbms\Database::startAtomic
	 * @covers \Wikimedia\Rdbms\Database::endAtomic
	 * @covers \Wikimedia\Rdbms\Database::cancelAtomic
	 * @covers \Wikimedia\Rdbms\Database::doAtomicSection
	 */
	public function testAtomicSectionsTrxRound() {
		$this->database->setFlag( IDatabase::DBO_TRX );
		$this->database->startAtomic( __METHOD__, IDatabase::ATOMIC_CANCELABLE );
		$this->database->query( 'SELECT 1', __METHOD__ );
		$this->database->endAtomic( __METHOD__ );
		$this->database->commit( __METHOD__, IDatabase::FLUSHING_ALL_PEERS );
		$this->assertLastSql( 'BEGIN; SAVEPOINT wikimedia_rdbms_atomic1; SELECT 1; RELEASE SAVEPOINT wikimedia_rdbms_atomic1; COMMIT' );
	}

	public static function provideAtomicSectionMethodsForErrors() {
		return [
			[ 'endAtomic' ],
			[ 'cancelAtomic' ],
		];
	}

	/**
	 * @dataProvider provideAtomicSectionMethodsForErrors
	 * @covers \Wikimedia\Rdbms\Database::endAtomic
	 * @covers \Wikimedia\Rdbms\Database::cancelAtomic
	 */
	public function testNoAtomicSection( $method ) {
		try {
			$this->database->$method( __METHOD__ );
			$this->fail( 'Expected exception not thrown' );
		} catch ( DBUnexpectedError $ex ) {
			$this->assertSame(
				'No atomic section is open (got ' . __METHOD__ . ')',
				$ex->getMessage()
			);
		}
	}

	/**
	 * @dataProvider provideAtomicSectionMethodsForErrors
	 * @covers \Wikimedia\Rdbms\Database::endAtomic
	 * @covers \Wikimedia\Rdbms\Database::cancelAtomic
	 */
	public function testInvalidAtomicSectionEnded( $method ) {
		$this->database->startAtomic( __METHOD__ . 'X' );
		try {
			$this->database->$method( __METHOD__ );
			$this->fail( 'Expected exception not thrown' );
		} catch ( DBUnexpectedError $ex ) {
			$this->assertSame(
				'Invalid atomic section ended (got ' . __METHOD__ . ' but expected ' .
					__METHOD__ . 'X)',
				$ex->getMessage()
			);
		}
	}

	/**
	 * @covers \Wikimedia\Rdbms\Database::cancelAtomic
	 */
	public function testUncancellableAtomicSection() {
		$this->database->startAtomic( __METHOD__ );
		try {
			$this->database->cancelAtomic( __METHOD__ );
			$this->database->select( 'test', '1', [], __METHOD__ );
			$this->fail( 'Expected exception not thrown' );
		} catch ( DBTransactionError $ex ) {
			$this->assertSame(
				'Cannot execute query from ' . __METHOD__ . ' while transaction status is ERROR',
				$ex->getMessage()
			);
		}
	}

	/**
	 * @covers \Wikimedia\Rdbms\Database::onAtomicSectionCancel
	 */
	public function testNoAtomicSectionForCallback() {
		try {
			$this->database->onAtomicSectionCancel( static function () {
			}, __METHOD__ );
			$this->fail( 'Expected exception not thrown' );
		} catch ( DBUnexpectedError $ex ) {
			$this->assertSame(
				'No atomic section is open (got ' . __METHOD__ . ')',
				$ex->getMessage()
			);
		}
	}

	/**
	 * @covers \Wikimedia\Rdbms\Database::assertQueryIsCurrentlyAllowed
	 */
	public function testTransactionErrorState1() {
		$wrapper = TestingAccessWrapper::newFromObject( $this->database );

		$this->database->begin( __METHOD__ );
		$wrapper->transactionManager->setTransactionError( new DBUnexpectedError( null, 'error' ) );
		$this->expectException( \Wikimedia\Rdbms\DBTransactionStateError::class );
		$this->database->delete( 'x', [ 'field' => 3 ], __METHOD__ );
	}

	/**
	 * @covers \Wikimedia\Rdbms\Database::query
	 */
	public function testTransactionErrorState2() {
		$wrapper = TestingAccessWrapper::newFromObject( $this->database );

		// TODO: This really needs a better place
		$this->database->startAtomic( __METHOD__ );
		$wrapper->transactionManager->setTransactionError( new DBUnexpectedError( null, 'error' ) );
		$this->database->rollback( __METHOD__ );
		$this->assertSame( 0, $this->database->trxLevel() );
		$this->assertEquals( TransactionManager::STATUS_TRX_NONE, $wrapper->trxStatus() );
		$this->assertLastSql( 'BEGIN; ROLLBACK' );

		$this->database->startAtomic( __METHOD__ );
		$this->assertEquals( TransactionManager::STATUS_TRX_OK, $wrapper->trxStatus() );
		$this->database->delete( 'x', [ 'field' => 1 ], __METHOD__ );
		$this->database->endAtomic( __METHOD__ );
		$this->assertEquals( TransactionManager::STATUS_TRX_NONE, $wrapper->trxStatus() );
		$this->assertLastSql( 'BEGIN; DELETE FROM x WHERE field = 1; COMMIT' );
		$this->assertSame( 0, $this->database->trxLevel(), 'Use after rollback()' );

		$this->database->begin( __METHOD__ );
		$this->database->startAtomic( __METHOD__, Database::ATOMIC_CANCELABLE );
		$this->database->update( 'y', [ 'a' => 1 ], [ 'field' => 1 ], __METHOD__ );
		$wrapper->transactionManager->setTransactionError( new DBUnexpectedError( null, 'error' ) );
		$this->database->cancelAtomic( __METHOD__ );
		$this->assertEquals( TransactionManager::STATUS_TRX_OK, $wrapper->trxStatus() );
		$this->database->startAtomic( __METHOD__ );
		$this->database->delete( 'y', [ 'field' => 1 ], __METHOD__ );
		$this->database->endAtomic( __METHOD__ );
		$this->database->commit( __METHOD__ );
		$this->assertLastSql( 'BEGIN; SAVEPOINT wikimedia_rdbms_atomic1; UPDATE y SET a = 1 WHERE field = 1; ROLLBACK TO SAVEPOINT wikimedia_rdbms_atomic1; DELETE FROM y WHERE field = 1; COMMIT' );
		$this->assertSame( 0, $this->database->trxLevel(), 'Use after rollback()' );

		// Next transaction
		$this->database->startAtomic( __METHOD__ );
		$this->assertEquals( TransactionManager::STATUS_TRX_OK, $wrapper->trxStatus() );
		$this->database->delete( 'x', [ 'field' => 3 ], __METHOD__ );
		$this->database->endAtomic( __METHOD__ );
		$this->assertEquals( TransactionManager::STATUS_TRX_NONE, $wrapper->trxStatus() );
		$this->assertLastSql( 'BEGIN; DELETE FROM x WHERE field = 3; COMMIT' );
		$this->assertSame( 0, $this->database->trxLevel() );
	}

	/**
	 * @covers \Wikimedia\Rdbms\Database::query
	 */
	public function testImplicitTransactionRollback() {
		$doError = function () {
			$this->database->forceNextResult( false, 666, 'Evilness' );
			try {
				$this->database->delete( 'error', '1', __CLASS__ . '::SomeCaller' );
				$this->fail( 'Expected exception not thrown' );
			} catch ( DBError $e ) {
				$this->assertSame( 666, $e->errno );
			}
		};

		$this->database->setFlag( Database::DBO_TRX );

		// Implicit transaction does not get silently rolled back
		$this->database->begin( __METHOD__, Database::TRANSACTION_INTERNAL );
		$doError();
		try {
			$this->database->delete( 'x', [ 'field' => 1 ], __METHOD__ );
			$this->fail( 'Expected exception not thrown' );
		} catch ( DBTransactionError $e ) {
			$this->assertEquals(
				'Cannot execute query from ' . __METHOD__ . ' while transaction status is ERROR',
				$e->getMessage()
			);
		}
		try {
			$this->database->commit( __METHOD__, Database::FLUSHING_INTERNAL );
			$this->fail( 'Expected exception not thrown' );
		} catch ( DBTransactionError $e ) {
			$this->assertEquals(
				'Cannot execute query from ' . __METHOD__ . ' while transaction status is ERROR',
				$e->getMessage()
			);
		}
		$this->database->rollback( __METHOD__, Database::FLUSHING_INTERNAL );
		$this->assertLastSql( 'BEGIN; DELETE FROM error WHERE 1; ROLLBACK' );

		// Likewise if there were prior writes
		$this->database->begin( __METHOD__, Database::TRANSACTION_INTERNAL );
		$this->database->delete( 'x', [ 'field' => 1 ], __METHOD__ );
		$doError();
		try {
			$this->database->delete( 'x', [ 'field' => 1 ], __METHOD__ );
			$this->fail( 'Expected exception not thrown' );
		} catch ( DBTransactionStateError $e ) {
		}
		$this->database->rollback( __METHOD__, Database::FLUSHING_INTERNAL );
		// phpcs:ignore
		$this->assertLastSql( 'BEGIN; DELETE FROM x WHERE field = 1; DELETE FROM error WHERE 1; ROLLBACK' );
	}

	/**
	 * @covers \Wikimedia\Rdbms\Database::query
	 */
	public function testTransactionStatementRollbackIgnoring() {
		$wrapper = TestingAccessWrapper::newFromObject( $this->database );
		$warning = [];
		$wrapper->deprecationLogger = static function ( $msg ) use ( &$warning ) {
			$warning[] = $msg;
		};

		$doError = function () {
			$this->database->forceNextResult(
				false,
				666,
				'Evilness',
				[ 'isKnownStatementRollbackError' => true ]
			);
			try {
				$this->database->delete( 'error', '1', __CLASS__ . '::SomeCaller' );
				$this->fail( 'Expected exception not thrown' );
			} catch ( DBError $e ) {
				$this->assertSame( 666, $e->errno );
			}
		};
		$expectWarning = 'Caller from ' . __METHOD__ .
			' ignored an error originally raised from ' . __CLASS__ . '::SomeCaller: [666] Evilness';

		// Rollback doesn't raise a warning
		$warning = [];
		$this->database->startAtomic( __METHOD__ );
		$doError();
		$this->database->rollback( __METHOD__ );
		$this->database->delete( 'x', [ 'field' => 1 ], __METHOD__ );
		$this->assertSame( [], $warning );
		// phpcs:ignore
		$this->assertLastSql( 'BEGIN; DELETE FROM error WHERE 1; ROLLBACK; DELETE FROM x WHERE field = 1' );

		// cancelAtomic() doesn't raise a warning
		$warning = [];
		$this->database->begin( __METHOD__ );
		$this->database->startAtomic( __METHOD__, Database::ATOMIC_CANCELABLE );
		$doError();
		$this->database->cancelAtomic( __METHOD__ );
		$this->database->delete( 'x', [ 'field' => 1 ], __METHOD__ );
		$this->database->commit( __METHOD__ );
		$this->assertSame( [], $warning );
		// phpcs:ignore
		$this->assertLastSql( 'BEGIN; SAVEPOINT wikimedia_rdbms_atomic1; DELETE FROM error WHERE 1; ROLLBACK TO SAVEPOINT wikimedia_rdbms_atomic1; DELETE FROM x WHERE field = 1; COMMIT' );

		// Commit does raise a warning
		$warning = [];
		$this->database->begin( __METHOD__ );
		$doError();
		$this->database->commit( __METHOD__ );
		$this->assertSame( [ $expectWarning ], $warning );
		$this->assertLastSql( 'BEGIN; DELETE FROM error WHERE 1; COMMIT' );

		// Deprecation only gets raised once
		$warning = [];
		$this->database->begin( __METHOD__ );
		$doError();
		$this->database->delete( 'x', [ 'field' => 1 ], __METHOD__ );
		$this->database->commit( __METHOD__ );
		$this->assertSame( [ $expectWarning ], $warning );
		// phpcs:ignore
		$this->assertLastSql( 'BEGIN; DELETE FROM error WHERE 1; DELETE FROM x WHERE field = 1; COMMIT' );
	}

	/**
	 * @covers \Wikimedia\Rdbms\Database::close
	 */
	public function testPrematureClose1() {
		$fname = __METHOD__;
		$this->database->begin( __METHOD__ );
		$this->database->onTransactionCommitOrIdle( function () use ( $fname ) {
			$this->database->query( 'SELECT 1', $fname );
		} );
		$this->database->onTransactionResolution( function () use ( $fname ) {
			$this->database->query( 'SELECT 2', $fname );
		} );
		$this->database->delete( 'x', [ 'field' => 3 ], __METHOD__ );
		$this->database->close();

		$this->assertFalse( $this->database->isOpen() );
		$this->assertLastSql( 'BEGIN; DELETE FROM x WHERE field = 3; ROLLBACK; SELECT 2' );
		$this->assertSame( 0, $this->database->trxLevel() );
	}

	/**
	 * @covers \Wikimedia\Rdbms\Database::close
	 */
	public function testPrematureClose2() {
		$fname = __METHOD__;
		$this->database->startAtomic( __METHOD__ );
		$this->database->onTransactionCommitOrIdle( function () use ( $fname ) {
			$this->database->query( 'SELECT 1', $fname );
		} );
		$this->database->onAtomicSectionCancel( function () use ( $fname ) {
			$this->database->query( 'SELECT 2', $fname );
		} );
		$this->database->delete( 'x', [ 'field' => 3 ], __METHOD__ );
		$this->database->close();

		$this->assertFalse( $this->database->isOpen() );
		$this->assertLastSql( 'BEGIN; DELETE FROM x WHERE field = 3; ROLLBACK; SELECT 2' );
		$this->assertSame( 0, $this->database->trxLevel() );
	}

	/**
	 * @covers \Wikimedia\Rdbms\Database::close
	 */
	public function testPrematureClose3() {
		$this->database->setFlag( IDatabase::DBO_TRX );
		$this->database->delete( 'x', [ 'field' => 3 ], __METHOD__ );
		$this->assertSame( 1, $this->database->trxLevel() );
		$this->database->close();

		$this->assertFalse( $this->database->isOpen() );
		$this->assertLastSql( 'BEGIN; DELETE FROM x WHERE field = 3; ROLLBACK' );
		$this->assertSame( 0, $this->database->trxLevel() );
	}

	/**
	 * @covers \Wikimedia\Rdbms\Database::close
	 */
	public function testPrematureClose4() {
		$this->database->setFlag( IDatabase::DBO_TRX );
		$this->database->query( 'SELECT 1', __METHOD__ );
		$this->assertSame( 1, $this->database->trxLevel() );
		$this->database->close();
		$this->database->clearFlag( IDatabase::DBO_TRX );

		$this->assertFalse( $this->database->isOpen() );
		$this->assertLastSql( 'BEGIN; SELECT 1; ROLLBACK' );
		$this->assertSame( 0, $this->database->trxLevel() );
	}

	/**
	 * @covers Wikimedia\Rdbms\Database::selectFieldValues()
	 */
	public function testSelectFieldValues() {
		$this->database->forceNextResult( [
			(object)[ 'value' => 'row1' ],
			(object)[ 'value' => 'row2' ],
			(object)[ 'value' => 'row3' ],
		] );

		$this->assertSame(
			[ 'row1', 'row2', 'row3' ],
			$this->database->selectFieldValues( 'table', 'table.field', 'conds', __METHOD__ )
		);
		$this->assertLastSql( 'SELECT table.field AS value FROM table WHERE conds' );
	}

	/**
	 * @covers Wikimedia\Rdbms\Platform\SQLPlatform::isWriteQuery
	 * @param string $query
	 * @param bool $res
	 * @dataProvider provideIsWriteQuery
	 */
	public function testIsWriteQuery( string $query, bool $res ) {
		$this->assertSame( $res, $this->platform->isWriteQuery( $query, 0 ) );
	}

	/**
	 * Provider for testIsWriteQuery
	 * @return array
	 */
	public function provideIsWriteQuery(): array {
		return [
			[ 'SELECT foo', false ],
			[ '  SELECT foo FROM bar', false ],
			[ 'BEGIN', false ],
			[ 'SHOW EXPLAIN FOR 12;', false ],
			[ 'USE foobar', false ],
			[ '(SELECT 1)', false ],
			[ 'INSERT INTO foo', true ],
			[ 'TRUNCATE bar', true ],
			[ 'DELETE FROM baz', true ],
			[ 'CREATE TABLE foobar', true ]
		];
	}
}
