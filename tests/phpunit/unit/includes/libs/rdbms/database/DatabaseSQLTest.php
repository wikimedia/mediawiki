<?php

namespace Wikimedia\Tests\Rdbms;

use DatabaseTestHelper;
use MediaWikiCoversValidator;
use MediaWikiTestCaseTrait;
use PHPUnit\Framework\TestCase;
use RuntimeException;
use Wikimedia\Rdbms\Database;
use Wikimedia\Rdbms\DBError;
use Wikimedia\Rdbms\DBTransactionError;
use Wikimedia\Rdbms\DBTransactionStateError;
use Wikimedia\Rdbms\DBUnexpectedError;
use Wikimedia\Rdbms\IDatabase;
use Wikimedia\Rdbms\Platform\SQLPlatform;
use Wikimedia\Rdbms\TransactionManager;
use Wikimedia\TestingAccessWrapper;

/**
 * Test the parts of the Database abstract class that deal
 * with creating SQL text.
 *
 * @covers \Wikimedia\Rdbms\Database
 * @covers \Wikimedia\Rdbms\Subquery
 * @covers \Wikimedia\Rdbms\Platform\SQLPlatform
 */
class DatabaseSQLTest extends TestCase {

	use MediaWikiCoversValidator;
	use MediaWikiTestCaseTrait;

	private DatabaseTestHelper $database;
	private SQLPlatform $platform;

	protected function setUp(): void {
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
	 * @dataProvider provideLockForUpdate
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
			[
				[
					'tables' => 'table',
					'field' => [ 'column' ],
					'conds' => 1,
					'options' => 'DISTINCT',
				],
				"SELECT COUNT(*) AS rowcount FROM " .
				"(SELECT DISTINCT column FROM table WHERE (1) AND (column IS NOT NULL)  ) tmp_count"
			],
		];
	}

	/**
	 * @dataProvider provideUpsert
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
	 * @dataProvider provideInsert
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
		];
	}

	public function testTransactionCommit() {
		$this->database->begin( __METHOD__ );
		$this->database->commit( __METHOD__ );
		$this->assertLastSql( 'BEGIN; COMMIT' );
	}

	public function testTransactionRollback() {
		$this->database->begin( __METHOD__ );
		$this->database->rollback( __METHOD__ );
		$this->assertLastSql( 'BEGIN; ROLLBACK' );
	}

	public function testDropTable() {
		$this->database->setExistingTables( [ 'table' ] );
		$this->database->dropTable( 'table', __METHOD__ );
		$this->assertLastSql( 'DROP TABLE table CASCADE' );
	}

	public function testDropNonExistingTable() {
		$this->assertFalse(
			$this->database->dropTable( 'non_existing', __METHOD__ )
		);
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

		$this->database->query( "DROP TEMPORARY TABLE IF EXISTS `tmp_table_4`, `tmp_table_5`", __METHOD__ );
	}

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
		$pcCallback = function () use ( $fname ) {
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
	}

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

	public function testTransactionErrorState1() {
		$wrapper = TestingAccessWrapper::newFromObject( $this->database );

		$this->database->begin( __METHOD__ );
		$wrapper->transactionManager->setTransactionError( new DBUnexpectedError( null, 'error' ) );
		$this->expectException( DBTransactionStateError::class );
		$this->database->delete( 'x', [ 'field' => 3 ], __METHOD__ );
	}

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
		} catch ( DBTransactionStateError ) {
		}
		$this->database->rollback( __METHOD__, Database::FLUSHING_INTERNAL );
		// phpcs:ignore
		$this->assertLastSql( 'BEGIN; DELETE FROM x WHERE field = 1; DELETE FROM error WHERE 1; ROLLBACK' );
	}

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

	public function testPrematureClose2() {
		$fname = __METHOD__;
		$this->database->startAtomic( __METHOD__ );
		$this->database->onTransactionCommitOrIdle( function () use ( $fname ) {
			$this->database->query( 'SELECT 1', $fname );
		} );
		$this->database->delete( 'x', [ 'field' => 3 ], __METHOD__ );
		$this->database->close();

		$this->assertFalse( $this->database->isOpen() );
		$this->assertLastSql( 'BEGIN; DELETE FROM x WHERE field = 3; ROLLBACK' );
		$this->assertSame( 0, $this->database->trxLevel() );
	}

	public function testPrematureClose3() {
		$this->database->setFlag( IDatabase::DBO_TRX );
		$this->database->delete( 'x', [ 'field' => 3 ], __METHOD__ );
		$this->assertSame( 1, $this->database->trxLevel() );
		$this->database->close();

		$this->assertFalse( $this->database->isOpen() );
		$this->assertLastSql( 'BEGIN; DELETE FROM x WHERE field = 3; ROLLBACK' );
		$this->assertSame( 0, $this->database->trxLevel() );
	}

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
}
