<?php

/**
 * Test the abstract database layer
 * This is a non DBMS depending test.
 */
class DatabaseSQLTest extends MediaWikiTestCase {
	/** @var DatabaseTestHelper */
	private $database;

	protected function setUp() {
		parent::setUp();
		$this->database = new DatabaseTestHelper( __CLASS__, [ 'cliMode' => true ] );
	}

	protected function assertLastSql( $sqlText ) {
		$this->assertEquals(
			$this->database->getLastSqls(),
			$sqlText
		);
	}

	protected function assertLastSqlDb( $sqlText, $db ) {
		$this->assertEquals( $db->getLastSqls(), $sqlText );
	}

	/**
	 * @dataProvider provideSelect
	 * @covers Database::select
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
		];
	}

	/**
	 * @dataProvider provideUpdate
	 * @covers Database::update
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
	 * @covers Database::delete
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
	 * @covers Database::upsert
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
	 * @covers Database::deleteJoin
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
	 * @covers Database::insert
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
	 * @covers Database::insertSelect
	 */
	public function testInsertSelect( $sql, $sqlTextNative, $sqlSelect, $sqlInsert ) {
		$this->database->insertSelect(
			$sql['destTable'],
			$sql['srcTable'],
			$sql['varMap'],
			$sql['conds'],
			__METHOD__,
			isset( $sql['insertOptions'] ) ? $sql['insertOptions'] : [],
			isset( $sql['selectOptions'] ) ? $sql['selectOptions'] : []
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
			isset( $sql['selectOptions'] ) ? $sql['selectOptions'] : []
		);
		$this->assertLastSqlDb( implode( '; ', [ $sqlSelect, $sqlInsert ] ), $dbWeb );
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
		];
	}

	/**
	 * @dataProvider provideReplace
	 * @covers Database::replace
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
				"DELETE FROM replace_table " .
					"WHERE ( field='text' ); " .
					"INSERT INTO replace_table " .
					"(field,field2) " .
					"VALUES ('text','text2')"
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
				"DELETE FROM module_deps " .
					"WHERE ( md_module='module' AND md_skin='skin' ); " .
					"INSERT INTO module_deps " .
					"(md_module,md_skin,md_deps) " .
					"VALUES ('module','skin','deps')"
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
				"DELETE FROM module_deps " .
					"WHERE ( md_module='module' AND md_skin='skin' ); " .
					"INSERT INTO module_deps " .
					"(md_module,md_skin,md_deps) " .
					"VALUES ('module','skin','deps'); " .
					"DELETE FROM module_deps " .
					"WHERE ( md_module='module2' AND md_skin='skin2' ); " .
					"INSERT INTO module_deps " .
					"(md_module,md_skin,md_deps) " .
					"VALUES ('module2','skin2','deps2')"
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
				"DELETE FROM module_deps " .
					"WHERE ( md_module='module' ) OR ( md_skin='skin' ); " .
					"INSERT INTO module_deps " .
					"(md_module,md_skin,md_deps) " .
					"VALUES ('module','skin','deps'); " .
					"DELETE FROM module_deps " .
					"WHERE ( md_module='module2' ) OR ( md_skin='skin2' ); " .
					"INSERT INTO module_deps " .
					"(md_module,md_skin,md_deps) " .
					"VALUES ('module2','skin2','deps2')"
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
	 * @dataProvider provideNativeReplace
	 * @covers Database::nativeReplace
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
	 * @covers Database::conditional
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
	 * @covers Database::buildConcat
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
	 * @covers Database::buildLike
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
				"LIKE 'text'"
			],
			[
				[ 'text', new LikeMatch( '%' ) ],
				"LIKE 'text%'"
			],
			[
				[ 'text', new LikeMatch( '%' ), 'text2' ],
				"LIKE 'text%text2'"
			],
			[
				[ 'text', new LikeMatch( '_' ) ],
				"LIKE 'text_'"
			],
		];
	}

	/**
	 * @dataProvider provideUnionQueries
	 * @covers Database::unionQueries
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
	 * @covers Database::commit
	 */
	public function testTransactionCommit() {
		$this->database->begin( __METHOD__ );
		$this->database->commit( __METHOD__ );
		$this->assertLastSql( 'BEGIN; COMMIT' );
	}

	/**
	 * @covers Database::rollback
	 */
	public function testTransactionRollback() {
		$this->database->begin( __METHOD__ );
		$this->database->rollback( __METHOD__ );
		$this->assertLastSql( 'BEGIN; ROLLBACK' );
	}

	/**
	 * @covers Database::dropTable
	 */
	public function testDropTable() {
		$this->database->setExistingTables( [ 'table' ] );
		$this->database->dropTable( 'table', __METHOD__ );
		$this->assertLastSql( 'DROP TABLE table CASCADE' );
	}

	/**
	 * @covers Database::dropTable
	 */
	public function testDropNonExistingTable() {
		$this->assertFalse(
			$this->database->dropTable( 'non_existing', __METHOD__ )
		);
	}

	/**
	 * @dataProvider provideMakeList
	 * @covers Database::makeList
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
}
