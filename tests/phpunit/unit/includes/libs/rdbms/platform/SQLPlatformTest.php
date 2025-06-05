<?php

namespace Wikimedia\Tests\Rdbms;

use Exception;
use InvalidArgumentException;
use MediaWiki\Tests\Unit\Libs\Rdbms\AddQuoterMock;
use MediaWiki\Tests\Unit\Libs\Rdbms\SQLPlatformTestHelper;
use MediaWikiCoversValidator;
use MediaWikiTestCaseTrait;
use PHPUnit\Framework\TestCase;
use Psr\Log\LogLevel;
use TestLogger;
use Wikimedia\Rdbms\DBLanguageError;
use Wikimedia\Rdbms\Expression;
use Wikimedia\Rdbms\LikeMatch;

/**
 * @covers \Wikimedia\Rdbms\Platform\SQLPlatform
 * @covers \Wikimedia\Rdbms\Database
 */
class SQLPlatformTest extends TestCase {

	use MediaWikiCoversValidator;
	use MediaWikiTestCaseTrait;

	private SQLPlatformTestHelper $platform;

	protected function setUp(): void {
		parent::setUp();
		$this->platform = new SQLPlatformTestHelper( new AddQuoterMock(), new TestLogger() );
	}

	/**
	 * @dataProvider provideGreatest
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
	 * @dataProvider provideBuildComparison
	 */
	public function testBuildComparison( string $op, array $conds, string $sqlText ) {
		$this->assertEquals(
			$sqlText,
			$this->platform->buildComparison( $op, $conds )
		);
	}

	public static function provideBuildComparison() {
		return [
			"Simple '>'" => [
				'>',
				[ 'a' => 1 ],
				'a > 1',
			],
			"Simple '>='" => [
				'>=',
				[ 'a' => 1 ],
				'a >= 1',
			],
			"Simple '<'" => [
				'<',
				[ 'a' => 1 ],
				'a < 1',
			],
			"Simple '<='" => [
				'<=',
				[ 'a' => 1 ],
				'a <= 1',
			],
			"Complex '>'" => [
				'>',
				[ 'a' => 1, 'b' => 2, 'c' => 3 ],
				'a > 1 OR (a = 1 AND (b > 2 OR (b = 2 AND (c > 3))))',
			],
			"Complex '>='" => [
				'>=',
				[ 'a' => 1, 'b' => 2, 'c' => 3 ],
				'a > 1 OR (a = 1 AND (b > 2 OR (b = 2 AND (c >= 3))))',
			],
			"Complex '<'" => [
				'<',
				[ 'a' => 1, 'b' => 2, 'c' => 3 ],
				'a < 1 OR (a = 1 AND (b < 2 OR (b = 2 AND (c < 3))))',
			],
			"Complex '<='" => [
				'<=',
				[ 'a' => 1, 'b' => 2, 'c' => 3 ],
				'a < 1 OR (a = 1 AND (b < 2 OR (b = 2 AND (c <= 3))))',
			],
			"Quoting: fields are SQL identifiers, values are values" => [
				// Note that the quoting here doesn't match any real database because
				// SQLPlatformTestHelper overrides it
				'>',
				[ '`quoted\'as"field' => '`quoted\'as"value' ],
				'`quoted\'as"field > \'`quoted\\\'as"value\'',
			],
		];
	}

	/** @dataProvider provideBuildComparisonInvalid */
	public function testBuildComparisonInvalid( string $op, array $conds ): void {
		$this->expectException( InvalidArgumentException::class );
		$this->platform->buildComparison( $op, $conds );
	}

	public static function provideBuildComparisonInvalid(): iterable {
		yield 'unknown op' => [ '<=>', [ 'a' => 1 ] ];
		yield 'empty conds' => [ '<', [] ];
		yield 'non-associative conds' => [ '<', [ 'a', 1 ] ]; // instead of 'a' => 1
	}

	/**
	 * @dataProvider provideBuildLike
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
	 * @dataProvider provideSelect
	 */
	public function testSelect( $sql, $sqlText ) {
		$actual = $this->platform->selectSQLText(
			$sql['tables'],
			$sql['fields'],
			$sql['conds'] ?? [],
			__METHOD__,
			$sql['options'] ?? [],
			$sql['join_conds'] ?? []
		);
		$this->assertSame( $sqlText, trim( $actual, ' ' ) );
	}

	public static function provideSelect() {
		return [
			[
				[
					'tables' => 'table',
					'fields' => [ 'field', 'alias' => 'field2' ],
					'conds' => [ 'alias' => 'text' ],
				],
				"SELECT  field,field2 AS alias  " .
				"FROM table    " .
				"WHERE alias = 'text'"
			],
			[
				[
					'tables' => 'table',
					'fields' => [ 'field', 'alias' => 'field2' ],
					'conds' => 'alias = \'text\'',
				],
				"SELECT  field,field2 AS alias  " .
				"FROM table    " .
				"WHERE alias = 'text'"
			],
			[
				[
					'tables' => 'table',
					'fields' => [ 'field', 'alias' => 'field2' ],
					'conds' => new Expression( 'alias', '=', 'text' ),
				],
				"SELECT  field,field2 AS alias  " .
				"FROM table    " .
				"WHERE alias = 'text'"
			],
			[
				[
					'tables' => 'table',
					'fields' => [ 'field', 'alias' => 'field2' ],
					'conds' => [ new Expression( 'alias', '=', 'text' ) ],
				],
				"SELECT  field,field2 AS alias  " .
				"FROM table    " .
				"WHERE (alias = 'text')"
			],
			[
				[
					'tables' => 'table',
					'fields' => [ 'field', 'alias' => 'field2' ],
					'conds' => ( new Expression( 'alias', '>', 'text' ) )->or( 'alias', '<', 'ext' ),
				],
				"SELECT  field,field2 AS alias  " .
				"FROM table    " .
				"WHERE (alias > 'text' OR alias < 'ext')"
			],
			[
				[
					'tables' => 'table',
					'fields' => [ 'field', 'alias' => 'field2' ],
					'conds' => [],
				],
				"SELECT  field,field2 AS alias  " .
				"FROM table"
			],
			[
				[
					'tables' => 'table',
					'fields' => [ 'field', 'alias' => 'field2' ],
					'conds' => '',
				],
				"SELECT  field,field2 AS alias  " .
				"FROM table"
			],
			[
				[
					'tables' => 'table',
					'fields' => [ 'field', 'alias' => 'field2' ],
					'conds' => '0', // T188314
				],
				"SELECT  field,field2 AS alias  " .
				"FROM table    " .
				"WHERE 0"
			],
			[
				[
					// Empty 'tables' is allowed
					'tables' => '',
					'fields' => [ 'SPECIAL_QUERY()' ],
				],
				"SELECT  SPECIAL_QUERY()"
			],
			[
				[
					'tables' => 'table',
					'fields' => [ 'field', 'alias' => 'field2' ],
					'conds' => [ 'alias' => 'text' ],
					'options' => [ 'LIMIT' => 1, 'ORDER BY' => 'field' ],
				],
				"SELECT  field,field2 AS alias  " .
				"FROM table    " .
				"WHERE alias = 'text'  " .
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
				"SELECT  tid,field,field2 AS alias,t2.id  " .
				"FROM table LEFT JOIN table2 t2 ON ((tid = t2.id))   " .
				"WHERE alias = 'text'  " .
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
				"SELECT  tid,field,field2 AS alias,t2.id  " .
				"FROM table LEFT JOIN table2 t2 ON ((tid = t2.id))   " .
				"WHERE alias = 'text'  " .
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
				"SELECT  tid,field,field2 AS alias,t2.id  " .
				"FROM table LEFT JOIN table2 t2 ON ((tid = t2.id))   " .
				"WHERE alias = 'text'  " .
				"GROUP BY field,field2 HAVING (COUNT(*) > 1) AND field = 1 " .
				"LIMIT 1"
			],
			[
				[
					'tables' => [ 'table' ],
					'fields' => [ 'alias' => 'field' ],
					'conds' => [ 'alias' => [ 1, 2, 3, 4 ] ],
				],
				"SELECT  field AS alias  " .
				"FROM table    " .
				"WHERE alias IN (1,2,3,4)"
			],
			[
				[
					'tables' => 'table',
					'fields' => [ 'field' ],
					'options' => [ 'USE INDEX' => [ 'table' => 'X' ] ],
				],
				"SELECT  field  FROM table FORCE INDEX (X)"
			],
			[
				[
					'tables' => 'table',
					'fields' => [ 'field' ],
					'options' => [ 'IGNORE INDEX' => [ 'table' => 'X' ] ],
				],
				"SELECT  field  FROM table IGNORE INDEX (X)"
			],
			[
				[
					'tables' => 'table',
					'fields' => [ 'field' ],
					'options' => [ 'DISTINCT' ],
				],
				"SELECT DISTINCT field  FROM table"
			],
			[
				[
					'tables' => 'table',
					'fields' => [ 'field' ],
					'options' => [ 'LOCK IN SHARE MODE' ],
				],
				"SELECT  field  FROM table      LOCK IN SHARE MODE"
			],
			[
				[
					'tables' => 'table',
					'fields' => [ 'field' ],
					'options' => [ 'EXPLAIN' => true ],
				],
				'EXPLAIN SELECT  field  FROM table'
			],
			[
				[
					'tables' => 'table',
					'fields' => [ 'field' ],
					'options' => [ 'FOR UPDATE' ],
				],
				"SELECT  field  FROM table      FOR UPDATE"
			],
			[
				[
					'tables' => [],
					'fields' => [ 'field' ],
				],
				"SELECT  field"
			],
			[
				[
					'tables' => [ 'table' ],
					'fields' => [ 'field1', 'alias' => 'MAX(field2)' ],
					'options' => [
						'GROUP BY' => 'field1',
						'HAVING' => ( new Expression( 'field1', '>', 'text' ) )
							->and( 'alias', '>', 'text2' ),
					]
				],
				"SELECT  field1,MAX(field2) AS alias  " .
				"FROM table     " .
				"GROUP BY field1 HAVING (field1 > 'text' AND alias > 'text2')"
			],
			[
				[
					'tables' => [ 'table' ],
					'fields' => [ 'field1', 'alias' => 'MAX(field2)' ],
					'options' => [
						'GROUP BY' => 'field1',
						'HAVING' => [
							new Expression( 'field1', '>', 'text' ),
							new Expression( 'alias', '>', 'text2' )
						]
					]
				],
				"SELECT  field1,MAX(field2) AS alias  " .
				"FROM table     " .
				"GROUP BY field1 HAVING (field1 > 'text') AND (alias > 'text2')"
			],
		];
	}

	/**
	 * @dataProvider provideUpdate
	 */
	public function testUpdate( $sql, $sqlText, $clean ) {
		$actual = $this->platform->updateSqlText(
			$sql['table'],
			$sql['values'],
			$sql['conds'],
			$sql['options'] ?? []
		);
		$this->assertSame( $sqlText, $actual->getSQL() );
		$this->assertSame( $clean, $actual->getCleanedSql() );
	}

	public static function provideUpdate() {
		return [
			[
				[
					'table' => 'table',
					'values' => [ 'field' => 'text', 'field2' => 'text2' ],
					'conds' => [ 'alias' => 'text' ],
				],
				"UPDATE  table SET field = 'text',field2 = 'text2' WHERE alias = 'text'",
				"UPDATE  table SET field = '?',field2 = '?' WHERE alias = '?'",
			],
			[
				[
					'table' => 'table',
					'values' => [ 'field' => 'text', 'field2' => 'text2' ],
					'conds' => 'alias = \'text\'',
				],
				"UPDATE  table SET field = 'text',field2 = 'text2' WHERE alias = 'text'",
				"UPDATE  table SET field = '?',field2 = '?' WHERE ?",
			],
			[
				[
					'table' => 'table',
					'values' => [ 'field = other', 'field2' => 'text2' ],
					'conds' => [ 'id' => '1' ],
				],
				"UPDATE  table SET field = other,field2 = 'text2' WHERE id = '1'",
				"UPDATE  table SET ?,field2 = '?' WHERE id = '?'",
			],
			[
				[
					'table' => 'table',
					'values' => [ 'field = other', 'field2' => 'text2' ],
					'conds' => '*',
				],
				"UPDATE  table SET field = other,field2 = 'text2'",
				"UPDATE  table SET ?,field2 = '?'",
			],
			[
				[
					'table' => 'table',
					'values' => [ 'field = other', 'field2' => 'text2' ],
					'conds' => [ '*' ],
				],
				"UPDATE  table SET field = other,field2 = 'text2'",
				"UPDATE  table SET ?,field2 = '?'",
			],
		];
	}

	/**
	 * @dataProvider provideInsert
	 */
	public function testInsert( $sql, $sqlText, $clean ) {
		$this->hideDeprecated( 'Wikimedia\Rdbms\Platform\SQLPlatform::insertSqlText' );
		$actual = $this->platform->dispatchingInsertSqlText(
			$sql['table'],
			$sql['rows'],
			$sql['options']
		);
		$this->assertSame( $sqlText, $actual->getSQL() );
		$this->assertSame( $clean, $actual->getCleanedSql() );
	}

	public static function provideInsert() {
		return [
			[
				[
					'table' => 'table',
					'rows' => [ [ 'field' => 'text', 'field2' => 'text2' ] ],
					'options' => [],
				],
				"INSERT INTO table (field,field2) VALUES ('text','text2')",
				"INSERT INTO table (field,field2) VALUES '?'",
			],
			[
				[
					'table' => 'table',
					'rows' => [ [ 'field' => 'text', 'field2' => 'text2' ] ],
					'options' => [ 'IGNORE' ],
				],
				"INSERT IGNORE INTO table (field,field2) VALUES ('text','text2')",
				"INSERT IGNORE INTO table (field,field2) VALUES '?'",
			],
			[
				[
					'table' => 'table',
					'rows' => [
						[ 'field' => 'text', 'field2' => 'text2' ],
						[ 'field' => 'text', 'field2' => 'text2' ],
					],
					'options' => [],
				],
				"INSERT INTO table (field,field2) VALUES ('text','text2'),('text','text2')",
				"INSERT INTO table (field,field2) VALUES '?'",
			],
			[
				[
					'table' => 'table',
					'rows' => [ [ 'field' => 'text', 'field2' => 'text2' ] ],
					'options' => [ 'IGNORE' ],
				],
				"INSERT IGNORE INTO table (field,field2) VALUES ('text','text2')",
				"INSERT IGNORE INTO table (field,field2) VALUES '?'",
			],
		];
	}

	/**
	 * @dataProvider provideUpdateEmptyCondition
	 */
	public function testUpdateEmptyCondition( $sql ) {
		$this->expectException( DBLanguageError::class );
		$this->expectExceptionMessage( 'called with empty conditions' );

		$this->platform->updateSqlText(
			$sql['table'],
			$sql['values'],
			$sql['conds'],
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
	 */
	public function testDelete( $sql, $sqlText, $cleanedSql ) {
		$res = $this->platform->deleteSqlText(
			$sql['table'],
			$sql['conds'],
			'Foo::bar()'
		);
		$this->assertSame( $sqlText, $res->getSQL() );
		$this->assertSame( $cleanedSql, $res->getCleanedSql() );
	}

	public static function provideDelete() {
		return [
			[
				[
					'table' => 'table',
					'conds' => [ 'alias' => 'text' ],
				],
				"DELETE FROM table WHERE alias = 'text'",
				"DELETE FROM table WHERE alias = '?'"
			],
			[
				[
					'table' => 'table',
					'conds' => 'alias = \'text\'',
				],
				"DELETE FROM table WHERE alias = 'text'",
				"DELETE FROM table WHERE ?"
			],
			[
				[
					'table' => 'table',
					'conds' => '*',
				],
				"DELETE FROM table",
				"DELETE FROM table",
			],
			[
				[
					'table' => 'table',
					'conds' => [ '*' ],
				],
				"DELETE FROM table",
				"DELETE FROM table",
			],
		];
	}

	/**
	 * @dataProvider provideDeleteEmptyCondition
	 */
	public function testDeleteEmptyCondition( $sql ) {
		try {
			$this->platform->deleteSqlText(
				$sql['table'],
				$sql['conds']
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
	 * @dataProvider provideDeleteJoin
	 */
	public function testDeleteJoin( $sql, $sqlText ) {
		$actual = $this->platform->deleteJoinSqlText(
			$sql['delTable'],
			$sql['joinTable'],
			$sql['delVar'],
			$sql['joinVar'],
			$sql['conds']
		);
		$this->assertSame( $sqlText, $actual );
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
	 * @dataProvider provideConditional
	 */
	public function testConditional( $sql, $sqlText ) {
		$this->assertEquals( $this->platform->conditional(
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
	 */
	public function testBuildConcat( $stringList, $sqlText ) {
		$this->assertEquals( trim( $this->platform->buildConcat(
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
	 * @dataProvider provideUnionQueries
	 */
	public function testUnionQueries( $sql, $sqlText ) {
		$this->assertEquals( trim( $this->platform->unionQueries(
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
	 * @dataProvider provideMakeList
	 */
	public function testMakeList( $list, $mode, $sqlText ) {
		$this->assertEquals( trim( $this->platform->makeList(
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
	 * @dataProvider provideMakeListInvalid
	 */
	public function testMakeListInvalid( $list, $mode, $exception ) {
		$this->expectException( $exception );
		$this->platform->makeList( $list, $mode );
	}

	public static function provideMakeListInvalid() {
		yield 'missing key for array value' => [
			[ [ 1, 2, 3 ] ],
			LIST_AND,
			InvalidArgumentException::class,
		];
		yield 'empty array value' => [
			[ 'x' => [] ],
			LIST_AND,
			InvalidArgumentException::class,
		];
		yield 'unexpected key for IExpression value' => [
			[ 'x' => new Expression( 'x', '=', 1 ) ],
			LIST_AND,
			InvalidArgumentException::class,
		];
		yield 'unexpected IExpression for UPDATE … SET' => [
			[ 'x' => new Expression( 'x', '=', 1 ) ],
			LIST_SET,
			InvalidArgumentException::class,
		];
		yield 'nested array in array value' => [
			[ 'x' => [ 1, 2, [ 3 ] ] ],
			LIST_AND,
			InvalidArgumentException::class,
		];
	}

	/**
	 * @dataProvider provideMakeListWarning
	 */
	public function testMakeListWarning( $list, $mode, $warning, $context ) {
		$logger = new TestLogger( true, null, true );
		$platform = new SQLPlatformTestHelper( new AddQuoterMock(), $logger );

		$platform->makeList( $list, $mode );

		$logs = $logger->getBuffer();
		$this->assertCount( 1, $logs );
		$this->assertSame( LogLevel::WARNING, $logs[0][0] );
		$this->assertSame( $warning, $logs[0][1] );
		$this->assertArrayContains( $context, $logs[0][2] );
	}

	public static function provideMakeListWarning() {
		yield 'associative keys in WHERE array value' => [
			[ 'x' => [ 'a' => 1, 'b' => 2 ] ],
			LIST_AND,
			'Wikimedia\Rdbms\Platform\SQLPlatform::makeList: array key {key} in list of values ignored',
			[ 'key' => 'a' ]
		];
		yield 'associative keys in WHERE array value (single)' => [
			[ 'x' => [ 'b' => 1 ] ],
			LIST_AND,
			'Wikimedia\Rdbms\Platform\SQLPlatform::makeList: array key {key} in list of values ignored',
			[ 'key' => 'b' ]
		];
		yield 'associative keys in comma list' => [
			[ 'a' => 1, 'b' => 2 ],
			LIST_COMMA,
			'Wikimedia\Rdbms\Platform\SQLPlatform::makeList: array key {key} in list of values ignored',
			[ 'key' => 'a' ]
		];
		yield 'associative keys in comma list (single)' => [
			[ 'b' => 1 ],
			LIST_COMMA,
			'Wikimedia\Rdbms\Platform\SQLPlatform::makeList: array key {key} in list of values ignored',
			[ 'key' => 'b' ]
		];
		yield 'associative keys in field list' => [
			[ 'a' => 'x', 'b' => 'y' ],
			LIST_NAMES,
			'Wikimedia\Rdbms\Platform\SQLPlatform::makeList: array key {key} in list of fields ignored',
			[ 'key' => 'a' ]
		];
		yield 'associative keys in field list (single)' => [
			[ 'b' => 'y' ],
			LIST_NAMES,
			'Wikimedia\Rdbms\Platform\SQLPlatform::makeList: array key {key} in list of fields ignored',
			[ 'key' => 'b' ]
		];
	}

	/**
	 * @dataProvider provideFactorConds
	 */
	public function testFactorConds( $input, $expected ) {
		if ( $expected === 'invalid' ) {
			$this->expectException( InvalidArgumentException::class );
		}
		$this->assertSame( $expected, $this->platform->factorConds( $input ) );
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

	public static function provideBuildSubstring() {
		yield [ 'someField', 1, 2, 'SUBSTRING(someField FROM 1 FOR 2)' ];
		yield [ 'someField', 1, null, 'SUBSTRING(someField FROM 1)' ];
	}

	/**
	 * @dataProvider provideBuildSubstring
	 */
	public function testBuildSubstring( $input, $start, $length, $expected ) {
		$output = $this->platform->buildSubstring( $input, $start, $length );
		$this->assertSame( $expected, $output );
	}

	public static function provideBuildSubstring_invalidParams() {
		yield [ -1, 1 ];
		yield [ 1, -1 ];
		yield [ 1, 'foo' ];
		yield [ 'foo', 1 ];
		yield [ null, 1 ];
		yield [ 0, 1 ];
	}

	/**
	 * @dataProvider provideBuildSubstring_invalidParams
	 */
	public function testBuildSubstring_invalidParams( $start, $length ) {
		$this->expectException( InvalidArgumentException::class );
		$this->platform->buildSubstring( 'foo', $start, $length );
	}

	public function testBuildIntegerCast() {
		$output = $this->platform->buildIntegerCast( 'fieldName' );
		$this->assertSame( 'CAST( fieldName AS INTEGER )', $output );
	}

	public static function provideMakeWhereFrom2d() {
		yield [
			[ 2 => [ 'Foo' => true, 'Bar' => true ], 4 => [ 'Quux' => true ] ],
			"(ns = 2 AND title IN ('Foo','Bar') ) OR (ns = 4 AND title = 'Quux')",
		];
		yield [
			[ 2 => [ 'Foo' => true, 'Bar' => true ], 4 => [] ],
			"(ns = 2 AND title IN ('Foo','Bar') )",
		];
		yield [
			[ 2 => [ 'Foo' => true ], 4 => [] ],
			"(ns = 2 AND title = 'Foo')",
		];
		yield [
			[ 2 => [ 'Foo' => true ] ],
			"(ns = 2 AND title = 'Foo')",
		];
		yield [
			[ 'x' => [ 42 ] ],
			"(ns = 'x' AND title = '0')",
		];
	}

	public static function provideMakeWhereFrom2dInvalid() {
		yield [
			[],
			[ 2 => [], 4 => [] ],
		];
	}

	/**
	 * @dataProvider provideMakeWhereFrom2d
	 */
	public function testMakeWhereFrom2d( $data, $expected ) {
		$this->assertSame(
			$expected,
			$this->platform->makeWhereFrom2d( $data, 'ns', 'title' )
		);
	}

	/**
	 * @dataProvider provideMakeWhereFrom2dInvalid
	 */
	public function testMakeWhereFrom2dInvalid( $data ) {
		$this->expectException( InvalidArgumentException::class );
		$this->platform->makeWhereFrom2d( $data, 'ns', 'title' );
	}

	public static function provideQualifiedTableComponents() {
		yield [
			'table',
			[ 'table' ],
		];
		yield [
			'database.table',
			[ 'database', 'table' ],
		];
		yield [
			'database.schema.table',
			[ 'database', 'schema', 'table' ],
		];
		yield [
			'"database"."schema"."table"',
			[ 'database', 'schema', 'table' ],
		];
		yield [
			'"database".schema."table"',
			[ 'database', 'schema', 'table' ],
		];
	}

	/**
	 * @dataProvider provideQualifiedTableComponents
	 */
	public function testQualifiedTableComponents( $tableName, $expectedComponents ) {
		$this->assertSame(
			$expectedComponents,
			$this->platform->qualifiedTableComponents( $tableName )
		);
	}
}
