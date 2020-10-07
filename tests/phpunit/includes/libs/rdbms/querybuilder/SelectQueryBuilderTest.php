<?php

use Wikimedia\Rdbms\IResultWrapper;
use Wikimedia\Rdbms\SelectQueryBuilder;

/**
 * @covers \Wikimedia\Rdbms\SelectQueryBuilder
 * @covers \Wikimedia\Rdbms\JoinGroup
 * @covers \Wikimedia\Rdbms\BuilderSubquery
 */
class SelectQueryBuilderTest extends PHPUnit\Framework\TestCase {
	/** @var DatabaseTestHelper */
	private $db;

	/** @var SelectQueryBuilder */
	private $sqb;

	protected function setUp() : void {
		$this->db = new DatabaseTestHelper( __CLASS__ . '::' . $this->getName() );
		$this->sqb = $this->db->newSelectQueryBuilder();
	}

	private function assertSQL( $expected ) {
		$actual = $this->sqb->getSQL();
		$actual = preg_replace( '/ +/', ' ', $actual );
		$actual = preg_replace( '/ +$/', '', $actual );
		$this->assertEquals( $expected, $actual );
	}

	public function testNoTable() {
		$this->sqb
			->select( '1' );
		$this->assertSQL( 'SELECT 1' );
	}

	public function testCondsEtc() {
		$this->sqb
			->table( 'a' )
			->where( '1' )
			->andWhere( '2' )
			->conds( '3' )
			->field( 'f' );
		$this->assertSQL( 'SELECT f FROM a WHERE (1) AND (2) AND (3)' );
	}

	public function testTableAlias() {
		$this->sqb
			->table( 't', 'a' )
			->field( 'f' );
		$this->assertSQL( 'SELECT f FROM t a' );
	}

	public function testTableIndex() {
		$this->sqb
			->table( 't', null )
			->useIndex( 'i' )
			->field( 'f' );
		$this->assertSQL( 'SELECT f FROM t FORCE INDEX (i)' );
	}

	public function testTableAliasIndex() {
		$this->sqb
			->table( 't', 'a' )
			->useIndex( 'i' )
			->field( 'f' );
		$this->assertSQL( 'SELECT f FROM t a FORCE INDEX (i)' );
	}

	public function testIgnoreIndex() {
		$this->sqb
			->table( 't' )
			->ignoreIndex( 'i' )
			->field( 'f' );
		$this->assertSQL( 'SELECT f FROM t IGNORE INDEX (i)' );
	}

	public function testSubquery() {
		$this->sqb
			->table(
				$this->sqb->newSubquery()
					->field( 'f' )
					->from( 't' )
					->useIndex( 'i' ),
				'sq'
			)
			->field( 'sq.f' );
		$this->assertSQL( 'SELECT sq.f FROM (SELECT f FROM t FORCE INDEX (i) ) sq' );
	}

	public function testTablesFields() {
		$this->sqb
			->tables( [ 'a' => 'b', 'c' ] )
			->useIndex( 'ic' )
			->useIndex( [ 'a' => 'ia' ] )
			->fields( [ 'a', 'b' ] );
		$this->assertSQL( 'SELECT a,b FROM b a FORCE INDEX (ia),c FORCE INDEX (ic)' );
	}

	public function testRawTables() {
		$this->sqb
			->select( 'f' )
			->rawTables( [ 'a' => [ 't1', 't2' ] ] );
		$this->assertSQL( "SELECT f FROM (t1,t2 )" );
	}

	public function testJoin() {
		$this->sqb
			->table( 'a' )
			->join( 'b', 'b', 'aa=bb' )
			->field( '*' );
		$this->assertSQL( 'SELECT * FROM a JOIN b ON ((aa=bb))' );
	}

	public function testLeftJoin() {
		$this->sqb
			->table( 'a' )
			->leftJoin( 'b', 'b', 'aa=bb' )
			->fields( '*' );
		$this->assertSQL( 'SELECT * FROM a LEFT JOIN b ON ((aa=bb))' );
	}

	public function testAutoAliasedJoin() {
		$this->sqb
			->table( 'a' )
			->join( 'b' )
			->field( '*' );
		$this->assertSQL( 'SELECT * FROM a JOIN b' );
	}

	public function testAutoAliasedLeftJoin() {
		$this->sqb
			->table( 'a' )
			->leftJoin( 'b', null, 'aa=bb' )
			->field( '*' );
		$this->assertSQL( 'SELECT * FROM a LEFT JOIN b ON ((aa=bb))' );
	}

	public function testLeftJoinGroup() {
		$this->sqb
			->table( 'a' )
			->field( 'f' )
			->leftJoin(
				$this->sqb->newJoinGroup()
					->table( 'b' )
					->leftJoin( 'c', 'c', 'bb=cc' ),
				null,
				'bb=aa'
			);
		$this->assertSQL( 'SELECT f FROM a LEFT JOIN (b LEFT JOIN c ON ((bb=cc))) ON ((bb=aa))' );
	}

	public function testInnerJoinGroup() {
		$this->sqb
			->table( 'a' )
			->field( 'f' )
			->join(
				$this->sqb->newJoinGroup()
					->table( 'b' )
					->join( 'c', 'c', 'bb=cc' ),
				null,
				'bb=aa'
			);
		$this->assertSQL( 'SELECT f FROM a JOIN (b JOIN c ON ((bb=cc))) ON ((bb=aa))' );
	}

	public function testDoubleJoinGroup() {
		$this->sqb
			->table( 'a' )
			->field( 'f' )
			->join(
				$this->sqb->newJoinGroup()
					->table( 'b' )
					->join(
						$this->sqb->newJoinGroup()
							->table( 'c' )
							->join( 'd', 'd', [] ),
						null, [] ),
				null,
				[]
			);
		$this->assertSQL( 'SELECT f FROM a JOIN (b JOIN (c JOIN d))' );
	}

	public function testInitialJoinGroup() {
		$this->sqb
			->table(
				$this->sqb->newJoinGroup()
					->table( 'a' )
					->table( 'b' )
			)
			->field( 'f' );
		$this->assertSQL( 'SELECT f FROM (a,b )' );
	}

	public function testInitialDoubleJoinGroup() {
		$this->sqb
			->table( $this->sqb->newJoinGroup()
				->table(
					$this->sqb->newJoinGroup()
						->table( 'a' )
						->table( 'b' )
				)
				->table(
					$this->sqb->newJoinGroup()
						->table( 'c' )
						->table( 'd' )
				)
			)
			->field( 'f' );
		$this->assertSQL( 'SELECT f FROM ((a,b ),(c,d ) )' );
	}

	public function testDegenerateJoinGroup() {
		$this->sqb
			->table( $this->sqb->newJoinGroup()->table( 'a' ) )
			->field( 'f' );
		$this->assertSQL( 'SELECT f FROM a' );
	}

	public function testSubqueryInJoinGroup() {
		$this->sqb
			->field( 'f' )
			->table(
				$this->sqb->newJoinGroup()
					->table( 'a' )
					->join( $this->sqb->newSubquery()->select( '1' ) )
			);
		$this->assertSQL( 'SELECT f FROM (a,(SELECT 1 ) sqb1_0 )' );
	}

	public function testConditionsOnGroup() {
		$this->sqb
			->field( 'f' )
			->table( 'a' )
			->join(
				$this->sqb->newJoinGroup()
					->table( 'b' )
					->table( 'c' ),
				null,
				'aa=bb' );
		$this->assertSQL( 'SELECT f FROM a JOIN (b,c ) ON ((aa=bb))' );
	}

	public function testJoinToEmpty() {
		$this->expectException( LogicException::class );
		$this->sqb->join( 'a', 'a', [] );
	}

	public function testJoinToEmptyInJoinGroup() {
		$this->expectException( LogicException::class );
		$this->sqb->newJoinGroup()->join( 'a', 'a', [] );
	}

	public function testConflictingAlias() {
		$this->expectException( LogicException::class );
		$this->sqb
			->table( 'a' )
			->join( 'b' )
			->join( 'b' );
	}

	public function testConflictingAliasInGroup() {
		$this->expectException( LogicException::class );
		$this->sqb->newJoinGroup()
			->table( 'a' )
			->join( 'b' )
			->join( 'b' );
	}

	public function testJoinConds() {
		$this->sqb
			->field( '*' )
			->tables( [ 'a', 'b' => 'b' ] )
			->joinConds( [ 'b' => [ 'LEFT JOIN', 'aa=bb' ] ] );
		$this->assertSQL( 'SELECT * FROM a LEFT JOIN b ON ((aa=bb))' );
	}

	public function testJoinSubquery() {
		$this->sqb
			->select( 'sq.a' )
			->from( 't1' )
			->join(
				$this->sqb->newSubquery()->select( 'a' )->from( 't2' ),
				'sq',
				[]
			);
		$this->assertSQL( 'SELECT sq.a FROM t1 JOIN (SELECT a FROM t2 ) sq' );
	}

	public function testLeftJoinSubquery() {
		$this->sqb
			->select( 'sq.a' )
			->from( 't1' )
			->leftJoin(
				$this->sqb->newSubquery()->select( 'a' )->from( 't2' ),
				'sq',
				[ 'aa' => null ]
			);
		$this->assertSQL( 'SELECT sq.a FROM t1 LEFT JOIN (SELECT a FROM t2 ) sq ON (aa IS NULL)' );
	}

	public function testOffsetLimit() {
		$this->sqb
			->select( 'f' )
			->from( 't' )
			->offset( 1 )
			->limit( 2 );
		$this->assertSQL( 'SELECT f FROM t LIMIT 1,2' );
	}

	public function testLockInShareMode() {
		$this->sqb
			->select( 'f' )
			->from( 't' )
			->lockInShareMode();
		$this->assertSQL( 'SELECT f FROM t LOCK IN SHARE MODE' );
	}

	public function testForUpdate() {
		$this->sqb
			->select( 'f' )
			->from( 't' )
			->forUpdate();
		$this->assertSQL( 'SELECT f FROM t FOR UPDATE' );
	}

	public function testDistinct() {
		$this->sqb
			->select( 'f' )
			->from( 't' )
			->distinct();
		$this->assertSQL( 'SELECT DISTINCT f FROM t' );
	}

	public function testGroupBy() {
		$this->sqb
			->select( 'f' )
			->from( 't' )
			->groupBy( [ '1', '2' ] );
		$this->assertSQL( 'SELECT f FROM t GROUP BY 1,2' );
	}

	public function testHaving() {
		$this->sqb
			->select( 'f' )
			->from( 't' )
			->having( [ 'a' => 1 ] );
		$this->assertSQL( 'SELECT f FROM t HAVING a = 1' );
	}

	public function testOrderBy1() {
		$this->sqb
			->select( [ 'a', 'b', 'c' ] )
			->from( 't' )
			->orderBy( 'a' )
			->orderBy( 'b', 'DESC' )
			->orderBy( 'c' );
		$this->assertSQL( 'SELECT a,b,c FROM t ORDER BY a,b DESC,c' );
	}

	public function testOrderBy2() {
		$this->sqb
			->select( [ 'a', 'b', 'c' ] )
			->from( 't' )
			->orderBy( [ 'a', 'b', 'c' ] );
		$this->assertSQL( 'SELECT a,b,c FROM t ORDER BY a,b,c' );
	}

	public function testOrderBy3() {
		$this->sqb
			->select( [ 'a', 'b', 'c' ] )
			->from( 't' )
			->orderBy( [ 'a', 'b', 'c' ], 'DESC' );
		$this->assertSQL( 'SELECT a,b,c FROM t ORDER BY a DESC,b DESC,c DESC' );
	}

	public function testOrderBy4() {
		$this->sqb
			->select( [ 'a', 'b', 'c' ] )
			->from( 't' )
			->orderBy( 'a' )
			->orderBy( [ 'b', 'c' ] );
		$this->assertSQL( 'SELECT a,b,c FROM t ORDER BY a,b,c' );
	}

	public function testOrderBy5() {
		$this->sqb
			->select( [ 'a', 'b', 'c' ] )
			->from( 't' )
			->option( 'ORDER BY', 'a' )
			->orderBy( [ 'b', 'c' ] );
		$this->assertSQL( 'SELECT a,b,c FROM t ORDER BY a,b,c' );
	}

	public function testExplain() {
		$this->sqb
			->explain()
			->select( '*' )
			->from( 't' );
		$this->assertSQL( 'EXPLAIN SELECT * FROM t' );
	}

	public function testStraightJoin() {
		$this->sqb
			->straightJoin()
			->select( '1' )
			->from( 't' );
		$this->assertSQL( 'SELECT /*! STRAIGHT_JOIN */ 1 FROM t' );
	}

	public function testBigResult() {
		$this->sqb
			->bigResult()
			->select( '1' )
			->from( 't' );
		$this->assertSQL( 'SELECT SQL_BIG_RESULT 1 FROM t' );
	}

	public function testSmallResult() {
		$this->sqb
			->smallResult()
			->select( '1' )
			->from( 't' );
		$this->assertSQL( 'SELECT SQL_SMALL_RESULT 1 FROM t' );
	}

	public function testCalcFoundRows() {
		$this->sqb
			->calcFoundRows()
			->select( '1' )
			->from( 't' );
		$this->assertSQL( 'SELECT SQL_CALC_FOUND_ROWS 1 FROM t' );
	}

	public function testOption() {
		$this->sqb
			->select( 'f' )
			->from( 't' )
			->option( 'ORDER BY', 'a' );
		$this->assertSQL( 'SELECT f FROM t ORDER BY a' );
	}

	public function testOptions() {
		$this->sqb
			->select( '1' )
			->options( [ 'ORDER BY' => '1', 'GROUP BY' => '2' ] );
		$this->assertSQL( 'SELECT 1 GROUP BY 2 ORDER BY 1' );
	}

	public function testFetchResultSet() {
		$this->sqb->select( '1' )->caller( __METHOD__ );
		$res = $this->sqb->fetchResultSet();
		$this->assertEquals( 'SELECT 1', $this->db->getLastSqls() );
		$this->assertInstanceOf( IResultWrapper::class, $res );
	}

	public function testFetchField() {
		$this->sqb->select( '1' )->caller( __METHOD__ );
		$this->sqb->fetchField();
		$this->assertEquals( 'SELECT 1 LIMIT 1', $this->db->getLastSqls() );
	}

	public function testFetchFieldValues() {
		$this->sqb->select( '1' )->caller( __METHOD__ );
		$res = $this->sqb->fetchFieldValues();
		$this->assertEquals( 'SELECT 1 AS value', $this->db->getLastSqls() );
		$this->assertIsArray( $res );
	}

	public function testFetchRow() {
		$this->sqb->select( '1' )->caller( __METHOD__ );
		$this->sqb->fetchRow();
		$this->assertEquals( 'SELECT 1 LIMIT 1', $this->db->getLastSqls() );
	}

	public function testFetchRowCount() {
		$this->sqb->table( 't' )->caller( __METHOD__ );
		$this->sqb->fetchRowCount();
		$this->assertEquals( 'SELECT COUNT(*) AS rowcount FROM (SELECT 1 FROM t     ) tmp_count',
			$this->db->getLastSqls() );
	}

	public function testFetchRowCountWithField() {
		$this->sqb->table( 't' )->field( 'f' )->caller( __METHOD__ );
		$this->sqb->fetchRowCount();
		// phpcs:ignore Generic.Files.LineLength.TooLong
		$this->assertEquals( 'SELECT COUNT(*) AS rowcount FROM (SELECT 1 FROM t WHERE (f IS NOT NULL)  ) tmp_count',
			$this->db->getLastSqls() );
	}

	public function testEstimateRowCount() {
		$this->sqb
			->table( 't' )
			->conds( [ 'a' => 'b' ] )
			->caller( __METHOD__ );
		$this->sqb->estimateRowCount();
		$this->assertEquals( 'SELECT COUNT(*) AS rowcount FROM t WHERE a = \'b\'',
			$this->db->getLastSqls() );
	}

	public function testLockForUpdate() {
		$this->sqb
			->table( 't' )
			->conds( [ 'a' => 'b' ] )
			->caller( __METHOD__ );
		$this->db->begin( __METHOD__ );
		$this->sqb->lockForUpdate();
		$this->db->rollback( __METHOD__ );
		// phpcs:ignore Generic.Files.LineLength.TooLong
		$this->assertEquals( 'BEGIN; SELECT COUNT(*) AS rowcount FROM (SELECT 1 FROM t WHERE a = \'b\'   FOR UPDATE) tmp_count; ROLLBACK',
			$this->db->getLastSqls() );
	}

	public function testBuildGroupConcatField() {
		$this->sqb
			->select( 'f' )
			->from( 't' )
			->caller( __METHOD__ );
		$res = $this->sqb->buildGroupConcatField( '|' );
		$this->assertEquals( '(SELECT  GROUP_CONCAT(f SEPARATOR \'|\')  FROM t     )',
			$res );
	}

	public function testGetSQL() {
		$this->sqb
			->select( 'f' )
			->from( 't' )
			->caller( __METHOD__ );
		$res = $this->sqb->getSQL();
		$this->assertEquals( 'SELECT  f  FROM t     ', $res );
	}

	public function testGetQueryInfo() {
		$this->sqb
			->select( 'f' )
			->from( 't' )
			->conds( [ 'a' => 'b' ] )
			->join( 'u', 'u', 'tt=uu' )
			->limit( 1 );
		$this->assertEquals(
			[
				'tables' => [ 't', 'u' => 'u' ],
				'fields' => [ 'f' ],
				'conds' => [ 'a' => 'b' ],
				'options' => [ 'LIMIT' => 1 ],
				'join_conds' => [ 'u' => [ 'JOIN', 'tt=uu' ] ]
			],
			$this->sqb->getQueryInfo() );
	}

	public function testQueryInfo() {
		$this->sqb->queryInfo(
			[
				'tables' => [ 't', 'u' => 'u' ],
				'fields' => [ 'f' ],
				'conds' => [ 'a' => 'b' ],
				'options' => [ 'LIMIT' => 1 ],
				'join_conds' => [ 'u' => [ 'JOIN', 'tt=uu' ] ]
			]
		);
		$this->assertSQL( "SELECT f FROM t JOIN u ON ((tt=uu)) WHERE a = 'b' LIMIT 1" );
	}
}
