<?php

use Wikimedia\Rdbms\IDatabase;
use Wikimedia\Rdbms\LikeMatch;
use Wikimedia\Rdbms\Database;

/**
 * Test the parts of the Database abstract class that deal
 * with creating SQL text.
 */
class DatabaseSQLTest extends PHPUnit\Framework\TestCase {

	use MediaWikiCoversValidator;

	/** @var DatabaseTestHelper|Database */
	private $database;

	protected function setUp() {
		parent::setUp();
		$this->database = new DatabaseTestHelper( __CLASS__, [ 'cliMode' => true ] );
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
	 * @dataProvider provideSelect
	 * @covers Wikimedia\Rdbms\Database::select
	 * @covers Wikimedia\Rdbms\Database::selectSQLText
	 * @covers Wikimedia\Rdbms\Database::tableNamesWithIndexClauseOrJOIN
	 * @covers Wikimedia\Rdbms\Database::useIndexClause
	 * @covers Wikimedia\Rdbms\Database::ignoreIndexClause
	 * @covers Wikimedia\Rdbms\Database::makeSelectOptions
	 * @covers Wikimedia\Rdbms\Database::makeOrderBy
	 * @covers Wikimedia\Rdbms\Database::makeGroupByWithHaving
	 */
	public function testSelect( $sql, $sqlText ) {
		$this->database->select(
			$sql['tables'],
			$sql['fields'],
			isset( $sql['conds'] ) ? $sql['conds'] : [],
			__METHOD__,
			isset( $sql['options'] ) ? $sql['options'] : [],
			isset( $sql['join_conds'] ) ? $sql['join_conds'] : []
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
					"GROUP BY field,field2 HAVING (COUNT(*) > 1) AND field = '1' " .
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
					"WHERE alias IN ('1','2','3','4')"
			],
			[
				[
					'tables' => 'table',
					'fields' => [ 'field' ],
					'options' => [ 'USE INDEX' => [ 'table' => 'X' ] ],
				],
				// No-op by default
				"SELECT field FROM table"
			],
			[
				[
					'tables' => 'table',
					'fields' => [ 'field' ],
					'options' => [ 'IGNORE INDEX' => [ 'table' => 'X' ] ],
				],
				// No-op by default
				"SELECT field FROM table"
			],
			[
				[
					'tables' => 'table',
					'fields' => [ 'field' ],
					'options' => [ 'DISTINCT', 'LOCK IN SHARE MODE' ],
				],
				"SELECT DISTINCT field FROM table      LOCK IN SHARE MODE"
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
		];
	}

	/**
	 * @covers Wikimedia\Rdbms\Subquery
	 * @dataProvider provideSelectRowCount
	 * @param $sql
	 * @param $sqlText
	 */
	public function testSelectRowCount( $sql, $sqlText ) {
		$this->database->selectRowCount(
			$sql['tables'],
			$sql['field'],
			isset( $sql['conds'] ) ? $sql['conds'] : [],
			__METHOD__,
			isset( $sql['options'] ) ? $sql['options'] : [],
			isset( $sql['join_conds'] ) ? $sql['join_conds'] : []
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
					'conds' => '1',
				],
				"SELECT COUNT(*) AS rowcount FROM " .
				"(SELECT 1 FROM table WHERE (1) AND (column IS NOT NULL)  ) tmp_count"
			],
			[
				[
					'tables' => 'table',
					'field' => [ 'alias' => 'column' ],
					'conds' => '0',
				],
				"SELECT COUNT(*) AS rowcount FROM " .
				"(SELECT 1 FROM table WHERE (0) AND (column IS NOT NULL)  ) tmp_count"
			],
		];
	}

	/**
	 * @dataProvider provideUpdate
	 * @covers Wikimedia\Rdbms\Database::update
	 * @covers Wikimedia\Rdbms\Database::makeUpdateOptions
	 * @covers Wikimedia\Rdbms\Database::makeUpdateOptionsArray
	 */
	public function testUpdate( $sql, $sqlText ) {
		$this->database->update(
			$sql['table'],
			$sql['values'],
			$sql['conds'],
			__METHOD__,
			isset( $sql['options'] ) ? $sql['options'] : []
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
		];
	}

	/**
	 * @dataProvider provideDelete
	 * @covers Wikimedia\Rdbms\Database::delete
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
					'conds' => '*',
				],
				"DELETE FROM table"
			],
		];
	}

	/**
	 * @dataProvider provideUpsert
	 * @covers Wikimedia\Rdbms\Database::upsert
	 */
	public function testUpsert( $sql, $sqlText ) {
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
					'rows' => [ 'field' => 'text', 'field2' => 'text2' ],
					'uniqueIndexes' => [ 'field' ],
					'set' => [ 'field' => 'set' ],
				],
				"BEGIN; " .
					"UPDATE upsert_table " .
					"SET field = 'set' " .
					"WHERE ((field = 'text')); " .
					"INSERT IGNORE INTO upsert_table " .
					"(field,field2) " .
					"VALUES ('text','text2'); " .
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
	 * @covers Wikimedia\Rdbms\Database::makeInsertOptions
	 */
	public function testInsert( $sql, $sqlText ) {
		$this->database->insert(
			$sql['table'],
			$sql['rows'],
			__METHOD__,
			isset( $sql['options'] ) ? $sql['options'] : []
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
					"VALUES ('text','2')"
			],
			[
				[
					'table' => 'table',
					'rows' => [ 'field' => 'text', 'field2' => 2 ],
					'options' => 'IGNORE',
				],
				"INSERT IGNORE INTO table " .
					"(field,field2) " .
					"VALUES ('text','2')"
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
					"('text','2')," .
					"('multi','3')"
			],
		];
	}

	/**
	 * @dataProvider provideInsertSelect
	 * @covers Wikimedia\Rdbms\Database::insertSelect
	 * @covers Wikimedia\Rdbms\Database::nativeInsertSelect
	 */
	public function testInsertSelect( $sql, $sqlTextNative, $sqlSelect, $sqlInsert ) {
		$this->database->insertSelect(
			$sql['destTable'],
			$sql['srcTable'],
			$sql['varMap'],
			$sql['conds'],
			__METHOD__,
			isset( $sql['insertOptions'] ) ? $sql['insertOptions'] : [],
			isset( $sql['selectOptions'] ) ? $sql['selectOptions'] : [],
			isset( $sql['selectJoinConds'] ) ? $sql['selectJoinConds'] : []
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
			isset( $sql['insertOptions'] ) ? $sql['insertOptions'] : [],
			isset( $sql['selectOptions'] ) ? $sql['selectOptions'] : [],
			isset( $sql['selectJoinConds'] ) ? $sql['selectJoinConds'] : []
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
					"FROM select_table WHERE *",
				"SELECT field_select AS field_insert,field2 AS field " .
				"FROM select_table WHERE *   FOR UPDATE",
				"INSERT INTO insert_table (field_insert,field) VALUES ('0','1')"
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
					"WHERE field = '2'",
				"SELECT field_select AS field_insert,field2 AS field FROM " .
				"select_table WHERE field = '2'   FOR UPDATE",
				"INSERT INTO insert_table (field_insert,field) VALUES ('0','1')"
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
					"WHERE field = '2' " .
					"ORDER BY field",
				"SELECT field_select AS field_insert,field2 AS field " .
				"FROM select_table WHERE field = '2' ORDER BY field  FOR UPDATE",
				"INSERT IGNORE INTO insert_table (field_insert,field) VALUES ('0','1')"
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
					"WHERE field = '2' " .
					"ORDER BY field",
				"SELECT field_select AS field_insert,field2 AS field " .
				"FROM select_table1 LEFT JOIN select_table2 ON ((select_table1.foo = select_table2.bar)) " .
				"WHERE field = '2' ORDER BY field  FOR UPDATE",
				"INSERT INTO insert_table (field_insert,field) VALUES ('0','1')"
			],
		];
	}

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
			'SELECT field2 AS field FROM select_table WHERE *   FOR UPDATE',
			'BEGIN',
			"INSERT INTO insert_table (field) VALUES ('" . implode( "'),('", range( 0, 9999 ) ) . "')",
			"INSERT INTO insert_table (field) VALUES ('" . implode( "'),('", range( 10000, 19999 ) ) . "')",
			"INSERT INTO insert_table (field) VALUES ('" . implode( "'),('", range( 20000, 25000 ) ) . "')",
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
					'uniqueIndexes' => [ 'md_module', 'md_skin' ],
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
					"WHERE (md_module = 'module') OR (md_skin = 'skin'); " .
					"INSERT INTO module_deps " .
					"(md_module,md_skin,md_deps) " .
					"VALUES ('module','skin','deps'); " .
					"DELETE FROM module_deps " .
					"WHERE (md_module = 'module2') OR (md_skin = 'skin2'); " .
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
				"BEGIN; INSERT INTO module_deps " .
					"(md_module,md_skin,md_deps) " .
					"VALUES ('module','skin','deps'); COMMIT"
			],
		];
	}

	/**
	 * @dataProvider provideNativeReplace
	 * @covers Wikimedia\Rdbms\Database::nativeReplace
	 */
	public function testNativeReplace( $sql, $sqlText ) {
		$this->database->nativeReplace(
			$sql['table'],
			$sql['rows'],
			__METHOD__
		);
		$this->assertLastSql( $sqlText );
	}

	public static function provideNativeReplace() {
		return [
			[
				[
					'table' => 'replace_table',
					'rows' => [ 'field' => 'text', 'field2' => 'text2' ],
				],
				"REPLACE INTO replace_table " .
					"(field,field2) " .
					"VALUES ('text','text2')"
			],
		];
	}

	/**
	 * @dataProvider provideConditional
	 * @covers Wikimedia\Rdbms\Database::conditional
	 */
	public function testConditional( $sql, $sqlText ) {
		$this->assertEquals( trim( $this->database->conditional(
			$sql['conds'],
			$sql['true'],
			$sql['false']
		) ), $sqlText );
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
	 * @dataProvider provideBuildLike
	 * @covers Wikimedia\Rdbms\Database::buildLike
	 * @covers Wikimedia\Rdbms\Database::escapeLikeInternal
	 */
	public function testBuildLike( $array, $sqlText ) {
		$this->assertEquals( trim( $this->database->buildLike(
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
			$this->database->setUnionSupportsOrderAndLimit( $params['unionSupportsOrderAndLimit'] );
		}

		$sql = trim( $this->database->unionConditionPermutations(
			$params['table'],
			$params['vars'],
			$params['permute_conds'],
			isset( $params['extra_conds'] ) ? $params['extra_conds'] : '',
			'FNAME',
			isset( $params['options'] ) ? $params['options'] : [],
			isset( $params['join_conds'] ) ? $params['join_conds'] : []
		) );
		$this->assertEquals( $expect, $sql );
	}

	public static function provideUnionConditionPermutations() {
		// phpcs:disable Generic.Files.LineLength
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
				"(SELECT  field1,field2 AS alias  FROM table1 JOIN table2 ON ((table1.foo_id = table2.foo_id))   WHERE field3 = '1' AND duplicates = '4' AND single = '0' AND (table2.bar > 23)  ORDER BY field1,field2 LIMIT 100  ) UNION ALL " .
				"(SELECT  field1,field2 AS alias  FROM table1 JOIN table2 ON ((table1.foo_id = table2.foo_id))   WHERE field3 = '1' AND duplicates = '5' AND single = '0' AND (table2.bar > 23)  ORDER BY field1,field2 LIMIT 100  ) UNION ALL " .
				"(SELECT  field1,field2 AS alias  FROM table1 JOIN table2 ON ((table1.foo_id = table2.foo_id))   WHERE field3 = '2' AND duplicates = '4' AND single = '0' AND (table2.bar > 23)  ORDER BY field1,field2 LIMIT 100  ) UNION ALL " .
				"(SELECT  field1,field2 AS alias  FROM table1 JOIN table2 ON ((table1.foo_id = table2.foo_id))   WHERE field3 = '2' AND duplicates = '5' AND single = '0' AND (table2.bar > 23)  ORDER BY field1,field2 LIMIT 100  ) UNION ALL " .
				"(SELECT  field1,field2 AS alias  FROM table1 JOIN table2 ON ((table1.foo_id = table2.foo_id))   WHERE field3 = '3' AND duplicates = '4' AND single = '0' AND (table2.bar > 23)  ORDER BY field1,field2 LIMIT 100  ) UNION ALL " .
				"(SELECT  field1,field2 AS alias  FROM table1 JOIN table2 ON ((table1.foo_id = table2.foo_id))   WHERE field3 = '3' AND duplicates = '5' AND single = '0' AND (table2.bar > 23)  ORDER BY field1,field2 LIMIT 100  ) " .
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
				"(SELECT  foo_id  FROM foo    WHERE bar = '1' AND baz IS NULL  ORDER BY foo_id LIMIT 25  ) UNION " .
				"(SELECT  foo_id  FROM foo    WHERE bar = '2' AND baz IS NULL  ORDER BY foo_id LIMIT 25  ) UNION " .
				"(SELECT  foo_id  FROM foo    WHERE bar = '3' AND baz IS NULL  ORDER BY foo_id LIMIT 25  ) " .
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
				"(SELECT  foo_id  FROM foo    WHERE bar = '1' AND baz IS NULL  ) UNION " .
				"(SELECT  foo_id  FROM foo    WHERE bar = '2' AND baz IS NULL  ) UNION " .
				"(SELECT  foo_id  FROM foo    WHERE bar = '3' AND baz IS NULL  ) " .
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
				"SELECT  foo_id  FROM foo    WHERE bar = '1'  ORDER BY foo_id LIMIT 150,25"
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
	 * @covers Wikimedia\Rdbms\Database::doRollback
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
	 * @covers Wikimedia\Rdbms\Database::registerTempTableOperation
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
	 * @covers Wikimedia\Rdbms\Database::assertBuildSubstringParams
	 * @dataProvider provideBuildSubstring_invalidParams
	 */
	public function testBuildSubstring_invalidParams( $start, $length ) {
		$this->setExpectedException( InvalidArgumentException::class );
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
	 * @covers \Wikimedia\Rdbms\Database::doSavepoint
	 * @covers \Wikimedia\Rdbms\Database::doReleaseSavepoint
	 * @covers \Wikimedia\Rdbms\Database::doRollbackToSavepoint
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
		// phpcs:ignore Generic.Files.LineLength
		$this->assertLastSql( 'BEGIN; SAVEPOINT wikimedia_rdbms_atomic1; RELEASE SAVEPOINT wikimedia_rdbms_atomic1; COMMIT' );

		$this->database->begin( __METHOD__ );
		$this->database->startAtomic( __METHOD__, IDatabase::ATOMIC_CANCELABLE );
		$this->database->cancelAtomic( __METHOD__ );
		$this->database->commit( __METHOD__ );
		// phpcs:ignore Generic.Files.LineLength
		$this->assertLastSql( 'BEGIN; SAVEPOINT wikimedia_rdbms_atomic1; ROLLBACK TO SAVEPOINT wikimedia_rdbms_atomic1; COMMIT' );

		$this->database->startAtomic( __METHOD__, IDatabase::ATOMIC_CANCELABLE );
		$this->database->startAtomic( __METHOD__, IDatabase::ATOMIC_CANCELABLE );
		$this->database->cancelAtomic( __METHOD__ );
		$this->database->endAtomic( __METHOD__ );
		// phpcs:ignore Generic.Files.LineLength
		$this->assertLastSql( 'BEGIN; SAVEPOINT wikimedia_rdbms_atomic1; ROLLBACK TO SAVEPOINT wikimedia_rdbms_atomic1; COMMIT' );

		$this->database->doAtomicSection( __METHOD__, function () {
		} );
		$this->assertLastSql( 'BEGIN; COMMIT' );

		$this->database->begin( __METHOD__ );
		$this->database->doAtomicSection( __METHOD__, function () {
		} );
		$this->database->rollback( __METHOD__ );
		// phpcs:ignore Generic.Files.LineLength
		$this->assertLastSql( 'BEGIN; SAVEPOINT wikimedia_rdbms_atomic1; RELEASE SAVEPOINT wikimedia_rdbms_atomic1; ROLLBACK' );

		$this->database->begin( __METHOD__ );
		try {
			$this->database->doAtomicSection( __METHOD__, function () {
				throw new RuntimeException( 'Test exception' );
			} );
			$this->fail( 'Expected exception not thrown' );
		} catch ( RuntimeException $ex ) {
			$this->assertSame( 'Test exception', $ex->getMessage() );
		}
		$this->database->commit( __METHOD__ );
		// phpcs:ignore Generic.Files.LineLength
		$this->assertLastSql( 'BEGIN; SAVEPOINT wikimedia_rdbms_atomic1; ROLLBACK TO SAVEPOINT wikimedia_rdbms_atomic1; COMMIT' );
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
				'No atomic transaction is open (got ' . __METHOD__ . ').',
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
				'Invalid atomic section ended (got ' . __METHOD__ . ').',
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
			$this->fail( 'Expected exception not thrown' );
		} catch ( DBUnexpectedError $ex ) {
			$this->assertSame(
				'Uncancelable atomic section canceled (got ' . __METHOD__ . ').',
				$ex->getMessage()
			);
		}
	}

}
