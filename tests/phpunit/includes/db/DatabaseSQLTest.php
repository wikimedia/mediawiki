<?php

/**
 * Test the abstract database layer
 * This is a non DBMS depending test.
 */
class DatabaseSQLTest extends MediaWikiTestCase {

	/**
	 * @var DatabaseTestHelper
	 */
	private $database;

	protected function setUp() {
		parent::setUp();
		$this->database = new DatabaseTestHelper( __CLASS__ );
	}

	protected function assertLastSql( $sqlText ) {
		$this->assertEquals(
			$this->database->getLastSqls(),
			$sqlText
		);
	}

	/**
	 * @dataProvider provideSelect
	 * @covers DatabaseBase::select
	 */
	public function testSelect( $sql, $sqlText ) {
		$this->database->select(
			$sql['tables'],
			$sql['fields'],
			isset( $sql['conds'] ) ? $sql['conds'] : array(),
			__METHOD__,
			isset( $sql['options'] ) ? $sql['options'] : array(),
			isset( $sql['join_conds'] ) ? $sql['join_conds'] : array()
		);
		$this->assertLastSql( $sqlText );
	}

	public static function provideSelect() {
		return array(
			array(
				array(
					'tables' => 'table',
					'fields' => array( 'field', 'alias' => 'field2' ),
					'conds' => array( 'alias' => 'text' ),
				),
				"SELECT field,field2 AS alias " .
					"FROM table " .
					"WHERE alias = 'text'"
			),
			array(
				array(
					'tables' => 'table',
					'fields' => array( 'field', 'alias' => 'field2' ),
					'conds' => array( 'alias' => 'text' ),
					'options' => array( 'LIMIT' => 1, 'ORDER BY' => 'field' ),
				),
				"SELECT field,field2 AS alias " .
					"FROM table " .
					"WHERE alias = 'text' " .
					"ORDER BY field " .
					"LIMIT 1"
			),
			array(
				array(
					'tables' => array( 'table', 't2' => 'table2' ),
					'fields' => array( 'tid', 'field', 'alias' => 'field2', 't2.id' ),
					'conds' => array( 'alias' => 'text' ),
					'options' => array( 'LIMIT' => 1, 'ORDER BY' => 'field' ),
					'join_conds' => array( 't2' => array(
						'LEFT JOIN', 'tid = t2.id'
					) ),
				),
				"SELECT tid,field,field2 AS alias,t2.id " .
					"FROM table LEFT JOIN table2 t2 ON ((tid = t2.id)) " .
					"WHERE alias = 'text' " .
					"ORDER BY field " .
					"LIMIT 1"
			),
			array(
				array(
					'tables' => array( 'table', 't2' => 'table2' ),
					'fields' => array( 'tid', 'field', 'alias' => 'field2', 't2.id' ),
					'conds' => array( 'alias' => 'text' ),
					'options' => array( 'LIMIT' => 1, 'GROUP BY' => 'field', 'HAVING' => 'COUNT(*) > 1' ),
					'join_conds' => array( 't2' => array(
						'LEFT JOIN', 'tid = t2.id'
					) ),
				),
				"SELECT tid,field,field2 AS alias,t2.id " .
					"FROM table LEFT JOIN table2 t2 ON ((tid = t2.id)) " .
					"WHERE alias = 'text' " .
					"GROUP BY field HAVING COUNT(*) > 1 " .
					"LIMIT 1"
			),
			array(
				array(
					'tables' => array( 'table', 't2' => 'table2' ),
					'fields' => array( 'tid', 'field', 'alias' => 'field2', 't2.id' ),
					'conds' => array( 'alias' => 'text' ),
					'options' => array( 'LIMIT' => 1, 'GROUP BY' => array( 'field', 'field2' ), 'HAVING' => array( 'COUNT(*) > 1', 'field' => 1 ) ),
					'join_conds' => array( 't2' => array(
						'LEFT JOIN', 'tid = t2.id'
					) ),
				),
				"SELECT tid,field,field2 AS alias,t2.id " .
					"FROM table LEFT JOIN table2 t2 ON ((tid = t2.id)) " .
					"WHERE alias = 'text' " .
					"GROUP BY field,field2 HAVING (COUNT(*) > 1) AND field = '1' " .
					"LIMIT 1"
			),
			array(
				array(
					'tables' => array( 'table' ),
					'fields' => array( 'alias' => 'field' ),
					'conds' => array( 'alias' => array( 1, 2, 3, 4 ) ),
				),
				"SELECT field AS alias " .
					"FROM table " .
					"WHERE alias IN ('1','2','3','4')"
			),
		);
	}

	/**
	 * @dataProvider provideUpdate
	 * @covers DatabaseBase::update
	 */
	public function testUpdate( $sql, $sqlText ) {
		$this->database->update(
			$sql['table'],
			$sql['values'],
			$sql['conds'],
			__METHOD__,
			isset( $sql['options'] ) ? $sql['options'] : array()
		);
		$this->assertLastSql( $sqlText );
	}

	public static function provideUpdate() {
		return array(
			array(
				array(
					'table' => 'table',
					'values' => array( 'field' => 'text', 'field2' => 'text2' ),
					'conds' => array( 'alias' => 'text' ),
				),
				"UPDATE table " .
					"SET field = 'text'" .
					",field2 = 'text2' " .
					"WHERE alias = 'text'"
			),
			array(
				array(
					'table' => 'table',
					'values' => array( 'field = other', 'field2' => 'text2' ),
					'conds' => array( 'id' => '1' ),
				),
				"UPDATE table " .
					"SET field = other" .
					",field2 = 'text2' " .
					"WHERE id = '1'"
			),
			array(
				array(
					'table' => 'table',
					'values' => array( 'field = other', 'field2' => 'text2' ),
					'conds' => '*',
				),
				"UPDATE table " .
					"SET field = other" .
					",field2 = 'text2'"
			),
		);
	}

	/**
	 * @dataProvider provideDelete
	 * @covers DatabaseBase::delete
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
		return array(
			array(
				array(
					'table' => 'table',
					'conds' => array( 'alias' => 'text' ),
				),
				"DELETE FROM table " .
					"WHERE alias = 'text'"
			),
			array(
				array(
					'table' => 'table',
					'conds' => '*',
				),
				"DELETE FROM table"
			),
		);
	}

	/**
	 * @dataProvider provideUpsert
	 * @covers DatabaseBase::upsert
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
		return array(
			array(
				array(
					'table' => 'upsert_table',
					'rows' => array( 'field' => 'text', 'field2' => 'text2' ),
					'uniqueIndexes' => array( 'field' ),
					'set' => array( 'field' => 'set' ),
				),
				"BEGIN; " .
					"UPDATE upsert_table " .
					"SET field = 'set' " .
					"WHERE ((field = 'text')); " .
					"INSERT IGNORE INTO upsert_table " .
					"(field,field2) " .
					"VALUES ('text','text2'); " .
					"COMMIT"
			),
		);
	}

	/**
	 * @dataProvider provideDeleteJoin
	 * @covers DatabaseBase::deleteJoin
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
		return array(
			array(
				array(
					'delTable' => 'table',
					'joinTable' => 'table_join',
					'delVar' => 'field',
					'joinVar' => 'field_join',
					'conds' => array( 'alias' => 'text' ),
				),
				"DELETE FROM table " .
					"WHERE field IN (" .
					"SELECT field_join FROM table_join WHERE alias = 'text'" .
					")"
			),
			array(
				array(
					'delTable' => 'table',
					'joinTable' => 'table_join',
					'delVar' => 'field',
					'joinVar' => 'field_join',
					'conds' => '*',
				),
				"DELETE FROM table " .
					"WHERE field IN (" .
					"SELECT field_join FROM table_join " .
					")"
			),
		);
	}

	/**
	 * @dataProvider provideInsert
	 * @covers DatabaseBase::insert
	 */
	public function testInsert( $sql, $sqlText ) {
		$this->database->insert(
			$sql['table'],
			$sql['rows'],
			__METHOD__,
			isset( $sql['options'] ) ? $sql['options'] : array()
		);
		$this->assertLastSql( $sqlText );
	}

	public static function provideInsert() {
		return array(
			array(
				array(
					'table' => 'table',
					'rows' => array( 'field' => 'text', 'field2' => 2 ),
				),
				"INSERT INTO table " .
					"(field,field2) " .
					"VALUES ('text','2')"
			),
			array(
				array(
					'table' => 'table',
					'rows' => array( 'field' => 'text', 'field2' => 2 ),
					'options' => 'IGNORE',
				),
				"INSERT IGNORE INTO table " .
					"(field,field2) " .
					"VALUES ('text','2')"
			),
			array(
				array(
					'table' => 'table',
					'rows' => array(
						array( 'field' => 'text', 'field2' => 2 ),
						array( 'field' => 'multi', 'field2' => 3 ),
					),
					'options' => 'IGNORE',
				),
				"INSERT IGNORE INTO table " .
					"(field,field2) " .
					"VALUES " .
					"('text','2')," .
					"('multi','3')"
			),
		);
	}

	/**
	 * @dataProvider provideInsertSelect
	 * @covers DatabaseBase::insertSelect
	 */
	public function testInsertSelect( $sql, $sqlText ) {
		$this->database->insertSelect(
			$sql['destTable'],
			$sql['srcTable'],
			$sql['varMap'],
			$sql['conds'],
			__METHOD__,
			isset( $sql['insertOptions'] ) ? $sql['insertOptions'] : array(),
			isset( $sql['selectOptions'] ) ? $sql['selectOptions'] : array()
		);
		$this->assertLastSql( $sqlText );
	}

	public static function provideInsertSelect() {
		return array(
			array(
				array(
					'destTable' => 'insert_table',
					'srcTable' => 'select_table',
					'varMap' => array( 'field_insert' => 'field_select', 'field' => 'field2' ),
					'conds' => '*',
				),
				"INSERT INTO insert_table " .
					"(field_insert,field) " .
					"SELECT field_select,field2 " .
					"FROM select_table"
			),
			array(
				array(
					'destTable' => 'insert_table',
					'srcTable' => 'select_table',
					'varMap' => array( 'field_insert' => 'field_select', 'field' => 'field2' ),
					'conds' => array( 'field' => 2 ),
				),
				"INSERT INTO insert_table " .
					"(field_insert,field) " .
					"SELECT field_select,field2 " .
					"FROM select_table " .
					"WHERE field = '2'"
			),
			array(
				array(
					'destTable' => 'insert_table',
					'srcTable' => 'select_table',
					'varMap' => array( 'field_insert' => 'field_select', 'field' => 'field2' ),
					'conds' => array( 'field' => 2 ),
					'insertOptions' => 'IGNORE',
					'selectOptions' => array( 'ORDER BY' => 'field' ),
				),
				"INSERT IGNORE INTO insert_table " .
					"(field_insert,field) " .
					"SELECT field_select,field2 " .
					"FROM select_table " .
					"WHERE field = '2' " .
					"ORDER BY field"
			),
		);
	}

	/**
	 * @dataProvider provideReplace
	 * @covers DatabaseBase::replace
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
		return array(
			array(
				array(
					'table' => 'replace_table',
					'uniqueIndexes' => array( 'field' ),
					'rows' => array( 'field' => 'text', 'field2' => 'text2' ),
				),
				"DELETE FROM replace_table " .
					"WHERE ( field='text' ); " .
					"INSERT INTO replace_table " .
					"(field,field2) " .
					"VALUES ('text','text2')"
			),
			array(
				array(
					'table' => 'module_deps',
					'uniqueIndexes' => array( array( 'md_module', 'md_skin' ) ),
					'rows' => array(
						'md_module' => 'module',
						'md_skin' => 'skin',
						'md_deps' => 'deps',
					),
				),
				"DELETE FROM module_deps " .
					"WHERE ( md_module='module' AND md_skin='skin' ); " .
					"INSERT INTO module_deps " .
					"(md_module,md_skin,md_deps) " .
					"VALUES ('module','skin','deps')"
			),
			array(
				array(
					'table' => 'module_deps',
					'uniqueIndexes' => array( array( 'md_module', 'md_skin' ) ),
					'rows' => array(
						array(
							'md_module' => 'module',
							'md_skin' => 'skin',
							'md_deps' => 'deps',
						), array(
							'md_module' => 'module2',
							'md_skin' => 'skin2',
							'md_deps' => 'deps2',
						),
					),
				),
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
			),
			array(
				array(
					'table' => 'module_deps',
					'uniqueIndexes' => array( 'md_module', 'md_skin' ),
					'rows' => array(
						array(
							'md_module' => 'module',
							'md_skin' => 'skin',
							'md_deps' => 'deps',
						), array(
							'md_module' => 'module2',
							'md_skin' => 'skin2',
							'md_deps' => 'deps2',
						),
					),
				),
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
			),
			array(
				array(
					'table' => 'module_deps',
					'uniqueIndexes' => array(),
					'rows' => array(
						'md_module' => 'module',
						'md_skin' => 'skin',
						'md_deps' => 'deps',
					),
				),
				"INSERT INTO module_deps " .
					"(md_module,md_skin,md_deps) " .
					"VALUES ('module','skin','deps')"
			),
		);
	}

	/**
	 * @dataProvider provideNativeReplace
	 * @covers DatabaseBase::nativeReplace
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
		return array(
			array(
				array(
					'table' => 'replace_table',
					'rows' => array( 'field' => 'text', 'field2' => 'text2' ),
				),
				"REPLACE INTO replace_table " .
					"(field,field2) " .
					"VALUES ('text','text2')"
			),
		);
	}

	/**
	 * @dataProvider provideConditional
	 * @covers DatabaseBase::conditional
	 */
	public function testConditional( $sql, $sqlText ) {
		$this->assertEquals( trim( $this->database->conditional(
			$sql['conds'],
			$sql['true'],
			$sql['false']
		) ), $sqlText );
	}

	public static function provideConditional() {
		return array(
			array(
				array(
					'conds' => array( 'field' => 'text' ),
					'true' => 1,
					'false' => 'NULL',
				),
				"(CASE WHEN field = 'text' THEN 1 ELSE NULL END)"
			),
			array(
				array(
					'conds' => array( 'field' => 'text', 'field2' => 'anothertext' ),
					'true' => 1,
					'false' => 'NULL',
				),
				"(CASE WHEN field = 'text' AND field2 = 'anothertext' THEN 1 ELSE NULL END)"
			),
			array(
				array(
					'conds' => 'field=1',
					'true' => 1,
					'false' => 'NULL',
				),
				"(CASE WHEN field=1 THEN 1 ELSE NULL END)"
			),
		);
	}

	/**
	 * @dataProvider provideBuildConcat
	 * @covers DatabaseBase::buildConcat
	 */
	public function testBuildConcat( $stringList, $sqlText ) {
		$this->assertEquals( trim( $this->database->buildConcat(
			$stringList
		) ), $sqlText );
	}

	public static function provideBuildConcat() {
		return array(
			array(
				array( 'field', 'field2' ),
				"CONCAT(field,field2)"
			),
			array(
				array( "'test'", 'field2' ),
				"CONCAT('test',field2)"
			),
		);
	}

	/**
	 * @dataProvider provideBuildLike
	 * @covers DatabaseBase::buildLike
	 */
	public function testBuildLike( $array, $sqlText ) {
		$this->assertEquals( trim( $this->database->buildLike(
			$array
		) ), $sqlText );
	}

	public static function provideBuildLike() {
		return array(
			array(
				'text',
				"LIKE 'text'"
			),
			array(
				array( 'text', new LikeMatch( '%' ) ),
				"LIKE 'text%'"
			),
			array(
				array( 'text', new LikeMatch( '%' ), 'text2' ),
				"LIKE 'text%text2'"
			),
			array(
				array( 'text', new LikeMatch( '_' ) ),
				"LIKE 'text_'"
			),
		);
	}

	/**
	 * @dataProvider provideUnionQueries
	 * @covers DatabaseBase::unionQueries
	 */
	public function testUnionQueries( $sql, $sqlText ) {
		$this->assertEquals( trim( $this->database->unionQueries(
			$sql['sqls'],
			$sql['all']
		) ), $sqlText );
	}

	public static function provideUnionQueries() {
		return array(
			array(
				array(
					'sqls' => array( 'RAW SQL', 'RAW2SQL' ),
					'all' => true,
				),
				"(RAW SQL) UNION ALL (RAW2SQL)"
			),
			array(
				array(
					'sqls' => array( 'RAW SQL', 'RAW2SQL' ),
					'all' => false,
				),
				"(RAW SQL) UNION (RAW2SQL)"
			),
			array(
				array(
					'sqls' => array( 'RAW SQL', 'RAW2SQL', 'RAW3SQL' ),
					'all' => false,
				),
				"(RAW SQL) UNION (RAW2SQL) UNION (RAW3SQL)"
			),
		);
	}

	/**
	 * @covers DatabaseBase::commit
	 */
	public function testTransactionCommit() {
		$this->database->begin( __METHOD__ );
		$this->database->commit( __METHOD__ );
		$this->assertLastSql( 'BEGIN; COMMIT' );
	}

	/**
	 * @covers DatabaseBase::rollback
	 */
	public function testTransactionRollback() {
		$this->database->begin( __METHOD__ );
		$this->database->rollback( __METHOD__ );
		$this->assertLastSql( 'BEGIN; ROLLBACK' );
	}

	/**
	 * @covers DatabaseBase::dropTable
	 */
	public function testDropTable() {
		$this->database->setExistingTables( array( 'table' ) );
		$this->database->dropTable( 'table', __METHOD__ );
		$this->assertLastSql( 'DROP TABLE table' );
	}

	/**
	 * @covers DatabaseBase::dropTable
	 */
	public function testDropNonExistingTable() {
		$this->assertFalse(
			$this->database->dropTable( 'non_existing', __METHOD__ )
		);
	}
}
