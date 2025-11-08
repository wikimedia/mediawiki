<?php

namespace Wikimedia\Tests\Rdbms;

use DatabaseTestHelper;
use LogicException;
use MediaWikiUnitTestCase;
use Wikimedia\Rdbms\IResultWrapper;
use Wikimedia\Rdbms\SelectQueryBuilder;
use Wikimedia\Rdbms\Subquery;
use Wikimedia\TestingAccessWrapper;

/**
 * @covers \Wikimedia\Rdbms\SelectQueryBuilder
 * @covers \Wikimedia\Rdbms\JoinGroup
 */
class SelectQueryBuilderTest extends MediaWikiUnitTestCase {

	private DatabaseTestHelper $db;
	private SelectQueryBuilder $sqb;

	private function assertSQL( $expected ) {
		$actual = $this->sqb->getSQL();
		$actual = preg_replace( '/ +/', ' ', $actual );
		$actual = rtrim( $actual, " " );
		$this->assertEquals( $expected, $actual );
	}

	public function testNoTable() {
		$this->db = new DatabaseTestHelper( __METHOD__ );
		$this->sqb = $this->db->newSelectQueryBuilder();
		$this->sqb
			->select( '1' );
		$this->assertSQL( 'SELECT 1' );
	}

	public function testCondsEtc() {
		$this->db = new DatabaseTestHelper( __METHOD__ );
		$this->sqb = $this->db->newSelectQueryBuilder();
		$this->sqb
			->table( 'a' )
			->where( '1' )
			->andWhere( '2' )
			->conds( '3' )
			->field( 'f' );
		$this->assertSQL( 'SELECT f FROM a WHERE (1) AND (2) AND (3)' );
	}

	public function testConflictingConds() {
		$this->db = new DatabaseTestHelper( __METHOD__ );
		$this->sqb = $this->db->newSelectQueryBuilder();
		// T288882: the empty set is the right answer
		$this->sqb
			->select( '1' )
			->from( 'a' )
			->where( [ 'k' => 'v1' ] )
			->andWhere( [ 'k' => 'v2' ] );
		$this->assertSQL( 'SELECT 1 FROM a WHERE k = \'v1\' AND (k = \'v2\')' );
	}

	public function testTableAlias() {
		$this->db = new DatabaseTestHelper( __METHOD__ );
		$this->sqb = $this->db->newSelectQueryBuilder();
		$this->sqb
			->table( 't', 'a' )
			->field( 'f' );
		$this->assertSQL( 'SELECT f FROM t a' );
	}

	public function testTableIndex() {
		$this->db = new DatabaseTestHelper( __METHOD__ );
		$this->sqb = $this->db->newSelectQueryBuilder();
		$this->sqb
			->table( 't', null )
			->useIndex( 'i' )
			->field( 'f' );
		$this->assertSQL( 'SELECT f FROM t FORCE INDEX (i)' );
	}

	public function testTableAliasIndex() {
		$this->db = new DatabaseTestHelper( __METHOD__ );
		$this->sqb = $this->db->newSelectQueryBuilder();
		$this->sqb
			->table( 't', 'a' )
			->useIndex( 'i' )
			->field( 'f' );
		$this->assertSQL( 'SELECT f FROM t a FORCE INDEX (i)' );
	}

	public function testIgnoreIndex() {
		$this->db = new DatabaseTestHelper( __METHOD__ );
		$this->sqb = $this->db->newSelectQueryBuilder();
		$this->sqb
			->table( 't' )
			->ignoreIndex( 'i' )
			->field( 'f' );
		$this->assertSQL( 'SELECT f FROM t IGNORE INDEX (i)' );
	}

	public function testSubquery() {
		$this->db = new DatabaseTestHelper( __METHOD__ );
		$this->sqb = $this->db->newSelectQueryBuilder();
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

	public function testSubqueryAsObject() {
		$this->db = new DatabaseTestHelper( __METHOD__ );
		$this->sqb = $this->db->newSelectQueryBuilder();
		$this->sqb
			->table(
				new Subquery( $this->sqb->newSubquery()
					->field( 'f' )
					->from( 'ta' )
					->useIndex( 'i' )
				->getSQL() ),
				'sq'
			)
			->field( 'sq.f' );
		$this->assertSQL( 'SELECT sq.f FROM (SELECT f FROM ta FORCE INDEX (i) ) sq' );
	}

	public function testTablesFields() {
		$this->db = new DatabaseTestHelper( __METHOD__ );
		$this->sqb = $this->db->newSelectQueryBuilder();
		$this->sqb
			->tables( [ 'a' => 'b', 'c' ] )
			->useIndex( 'ic' )
			->useIndex( [ 'a' => 'ia' ] )
			->fields( [ 'a', 'b' ] );
		$this->assertSQL( 'SELECT a,b FROM b a FORCE INDEX (ia),c FORCE INDEX (ic)' );
	}

	public function testFieldsRepeating() {
		$this->db = new DatabaseTestHelper( __METHOD__ );
		$this->sqb = $this->db->newSelectQueryBuilder();
		$this->sqb
			->table( 't' )
			->field( 'f' )
			->field( 'f' );
		$this->assertSQL( 'SELECT f FROM t' );
	}

	public function testFieldsRepeatingAlias() {
		$this->db = new DatabaseTestHelper( __METHOD__ );
		$this->sqb = $this->db->newSelectQueryBuilder();
		$this->sqb
			->table( 't' )
			->field( 'f', 'f' )
			->field( 'f', 'f' );
		$this->assertSQL( 'SELECT f FROM t' );
	}

	public function testFieldsConflictingAlias() {
		$this->db = new DatabaseTestHelper( __METHOD__ );
		$this->sqb = $this->db->newSelectQueryBuilder();
		$this->expectException( LogicException::class );
		$this->sqb
			->table( 't' )
			->field( 'x', 'f' )
			->field( 'y', 'f' );
	}

	public function testRawTables() {
		$this->db = new DatabaseTestHelper( __METHOD__ );
		$this->sqb = $this->db->newSelectQueryBuilder();
		$this->sqb
			->select( 'f' )
			->rawTables( [ 'a' => [ 't1', 't2' ] ] );
		$this->assertSQL( "SELECT f FROM (t1,t2 )" );
	}

	public function testJoin() {
		$this->db = new DatabaseTestHelper( __METHOD__ );
		$this->sqb = $this->db->newSelectQueryBuilder();
		$this->sqb
			->table( 'a' )
			->join( 'b', 'b', 'aa=bb' )
			->field( '*' );
		$this->assertSQL( 'SELECT * FROM a JOIN b ON ((aa=bb))' );
	}

	public function testLeftJoin() {
		$this->db = new DatabaseTestHelper( __METHOD__ );
		$this->sqb = $this->db->newSelectQueryBuilder();
		$this->sqb
			->table( 'a' )
			->leftJoin( 'b', 'b', 'aa=bb' )
			->fields( '*' );
		$this->assertSQL( 'SELECT * FROM a LEFT JOIN b ON ((aa=bb))' );
	}

	public function testStraightJoin() {
		$this->db = new DatabaseTestHelper( __METHOD__ );
		$this->sqb = $this->db->newSelectQueryBuilder();
		$this->sqb
			->table( 'a' )
			->straightJoin( 'b', 'b', 'aa=bb' )
			->fields( '*' );
		$this->assertSQL( 'SELECT * FROM a JOIN b ON ((aa=bb))' );
	}

	public function testAutoAliasedJoin() {
		$this->db = new DatabaseTestHelper( __METHOD__ );
		$this->sqb = $this->db->newSelectQueryBuilder();
		$this->sqb
			->table( 'a' )
			->join( 'b' )
			->field( '*' );
		$this->assertSQL( 'SELECT * FROM a JOIN b' );
	}

	public function testAutoAliasedLeftJoin() {
		$this->db = new DatabaseTestHelper( __METHOD__ );
		$this->sqb = $this->db->newSelectQueryBuilder();
		$this->sqb
			->table( 'a' )
			->leftJoin( 'b', null, 'aa=bb' )
			->field( '*' );
		$this->assertSQL( 'SELECT * FROM a LEFT JOIN b ON ((aa=bb))' );
	}

	public function testLeftJoinGroup() {
		$this->db = new DatabaseTestHelper( __METHOD__ );
		$this->sqb = $this->db->newSelectQueryBuilder();
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
		$this->db = new DatabaseTestHelper( __METHOD__ );
		$this->sqb = $this->db->newSelectQueryBuilder();
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
		$this->db = new DatabaseTestHelper( __METHOD__ );
		$this->sqb = $this->db->newSelectQueryBuilder();
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
		$this->db = new DatabaseTestHelper( __METHOD__ );
		$this->sqb = $this->db->newSelectQueryBuilder();
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
		$this->db = new DatabaseTestHelper( __METHOD__ );
		$this->sqb = $this->db->newSelectQueryBuilder();
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
		$this->db = new DatabaseTestHelper( __METHOD__ );
		$this->sqb = $this->db->newSelectQueryBuilder();
		$this->sqb
			->table( $this->sqb->newJoinGroup()->table( 'a' ) )
			->field( 'f' );
		$this->assertSQL( 'SELECT f FROM a' );
	}

	public function testSubqueryInJoinGroup() {
		$this->db = new DatabaseTestHelper( __METHOD__ );
		$this->sqb = $this->db->newSelectQueryBuilder();
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
		$this->db = new DatabaseTestHelper( __METHOD__ );
		$this->sqb = $this->db->newSelectQueryBuilder();
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
		$this->db = new DatabaseTestHelper( __METHOD__ );
		$this->sqb = $this->db->newSelectQueryBuilder();
		$this->expectException( LogicException::class );
		$this->sqb->join( 'a', 'a', [] );
	}

	public function testJoinToEmptyInJoinGroup() {
		$this->db = new DatabaseTestHelper( __METHOD__ );
		$this->sqb = $this->db->newSelectQueryBuilder();
		$this->expectException( LogicException::class );
		$this->sqb->newJoinGroup()->join( 'a', 'a', [] );
	}

	public function testConflictingAlias() {
		$this->db = new DatabaseTestHelper( __METHOD__ );
		$this->sqb = $this->db->newSelectQueryBuilder();
		$this->expectException( LogicException::class );
		$this->sqb
			->table( 'a' )
			->join( 'b' )
			->join( 'b' );
	}

	public function testConflictingAliasInGroup() {
		$this->db = new DatabaseTestHelper( __METHOD__ );
		$this->sqb = $this->db->newSelectQueryBuilder();
		$this->expectException( LogicException::class );
		$this->sqb->newJoinGroup()
			->table( 'a' )
			->join( 'b' )
			->join( 'b' );
	}

	public function testJoinConds() {
		$this->db = new DatabaseTestHelper( __METHOD__ );
		$this->sqb = $this->db->newSelectQueryBuilder();
		$this->sqb
			->field( '*' )
			->tables( [ 'a', 'b' => 'b' ] )
			->joinConds( [ 'b' => [ 'LEFT JOIN', 'aa=bb' ] ] );
		$this->assertSQL( 'SELECT * FROM a LEFT JOIN b ON ((aa=bb))' );
	}

	public function testJoinSubquery() {
		$this->db = new DatabaseTestHelper( __METHOD__ );
		$this->sqb = $this->db->newSelectQueryBuilder();
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
		$this->db = new DatabaseTestHelper( __METHOD__ );
		$this->sqb = $this->db->newSelectQueryBuilder();
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
		$this->db = new DatabaseTestHelper( __METHOD__ );
		$this->sqb = $this->db->newSelectQueryBuilder();
		$this->sqb
			->select( 'f' )
			->from( 't' )
			->offset( 1 )
			->limit( 2 );
		$this->assertSQL( 'SELECT f FROM t LIMIT 1,2' );
	}

	public function testLockInShareMode() {
		$this->db = new DatabaseTestHelper( __METHOD__ );
		$this->sqb = $this->db->newSelectQueryBuilder();
		$this->sqb
			->select( 'f' )
			->from( 't' )
			->lockInShareMode();
		$this->assertSQL( 'SELECT f FROM t LOCK IN SHARE MODE' );
	}

	public function testForUpdate() {
		$this->db = new DatabaseTestHelper( __METHOD__ );
		$this->sqb = $this->db->newSelectQueryBuilder();
		$this->sqb
			->select( 'f' )
			->from( 't' )
			->forUpdate();
		$this->assertSQL( 'SELECT f FROM t FOR UPDATE' );
	}

	public function testDistinct() {
		$this->db = new DatabaseTestHelper( __METHOD__ );
		$this->sqb = $this->db->newSelectQueryBuilder();
		$this->sqb
			->select( 'f' )
			->from( 't' )
			->distinct();
		$this->assertSQL( 'SELECT DISTINCT f FROM t' );
	}

	public function testGroupBy() {
		$this->db = new DatabaseTestHelper( __METHOD__ );
		$this->sqb = $this->db->newSelectQueryBuilder();
		$this->sqb
			->select( 'f' )
			->from( 't' )
			->groupBy( [ '1', '2' ] );
		$this->assertSQL( 'SELECT f FROM t GROUP BY 1,2' );
	}

	public function testHaving() {
		$this->db = new DatabaseTestHelper( __METHOD__ );
		$this->sqb = $this->db->newSelectQueryBuilder();
		$this->sqb
			->select( 'f' )
			->from( 't' )
			->having( [ 'a' => 1 ] );
		$this->assertSQL( 'SELECT f FROM t HAVING a = 1' );
	}

	public function testHavingWithSingleExpression() {
		$this->db = new DatabaseTestHelper( __METHOD__ );
		$this->sqb = $this->db->newSelectQueryBuilder();
		$this->sqb
			->select( 'f' )
			->from( 't' )
			->having( $this->db->expr( 'a', '=', 1 ) );
		$this->assertSQL( 'SELECT f FROM t HAVING (a = 1)' );
	}

	public function testHavingWithArrayOfExpressions() {
		$this->db = new DatabaseTestHelper( __METHOD__ );
		$this->sqb = $this->db->newSelectQueryBuilder();
		$this->sqb
			->select( 'f' )
			->from( 't' )
			->having( [
				$this->db->expr( 'a', '=', 1 ),
				$this->db->expr( 'b', '>', 2 )
			] );
		$this->assertSQL( 'SELECT f FROM t HAVING (a = 1) AND (b > 2)' );
	}

	public function testOrderBy1() {
		$this->db = new DatabaseTestHelper( __METHOD__ );
		$this->sqb = $this->db->newSelectQueryBuilder();
		$this->sqb
			->select( [ 'a', 'b', 'c' ] )
			->from( 't' )
			->orderBy( 'a' )
			->orderBy( 'b', 'DESC' )
			->orderBy( 'c' );
		$this->assertSQL( 'SELECT a,b,c FROM t ORDER BY a,b DESC,c' );
	}

	public function testOrderBy2() {
		$this->db = new DatabaseTestHelper( __METHOD__ );
		$this->sqb = $this->db->newSelectQueryBuilder();
		$this->sqb
			->select( [ 'a', 'b', 'c' ] )
			->from( 't' )
			->orderBy( [ 'a', 'b', 'c' ] );
		$this->assertSQL( 'SELECT a,b,c FROM t ORDER BY a,b,c' );
	}

	public function testOrderBy3() {
		$this->db = new DatabaseTestHelper( __METHOD__ );
		$this->sqb = $this->db->newSelectQueryBuilder();
		$this->sqb
			->select( [ 'a', 'b', 'c' ] )
			->from( 't' )
			->orderBy( [ 'a', 'b', 'c' ], 'DESC' );
		$this->assertSQL( 'SELECT a,b,c FROM t ORDER BY a DESC,b DESC,c DESC' );
	}

	public function testOrderBy4() {
		$this->db = new DatabaseTestHelper( __METHOD__ );
		$this->sqb = $this->db->newSelectQueryBuilder();
		$this->sqb
			->select( [ 'a', 'b', 'c' ] )
			->from( 't' )
			->orderBy( 'a' )
			->orderBy( [ 'b', 'c' ] );
		$this->assertSQL( 'SELECT a,b,c FROM t ORDER BY a,b,c' );
	}

	public function testOrderBy5() {
		$this->db = new DatabaseTestHelper( __METHOD__ );
		$this->sqb = $this->db->newSelectQueryBuilder();
		$this->sqb
			->select( [ 'a', 'b', 'c' ] )
			->from( 't' )
			->option( 'ORDER BY', 'a' )
			->orderBy( [ 'b', 'c' ] );
		$this->assertSQL( 'SELECT a,b,c FROM t ORDER BY a,b,c' );
	}

	public function testExplain() {
		$this->db = new DatabaseTestHelper( __METHOD__ );
		$this->sqb = $this->db->newSelectQueryBuilder();
		$this->sqb
			->explain()
			->select( '*' )
			->from( 't' );
		$this->assertSQL( 'EXPLAIN SELECT * FROM t' );
	}

	public function testStraightJoinOption() {
		$this->db = new DatabaseTestHelper( __METHOD__ );
		$this->sqb = $this->db->newSelectQueryBuilder();
		$this->sqb
			->straightJoinOption()
			->select( '1' )
			->from( 't' );
		$this->assertSQL( 'SELECT /*! STRAIGHT_JOIN */ 1 FROM t' );
	}

	public function testBigResult() {
		$this->db = new DatabaseTestHelper( __METHOD__ );
		$this->sqb = $this->db->newSelectQueryBuilder();
		$this->sqb
			->bigResult()
			->select( '1' )
			->from( 't' );
		$this->assertSQL( 'SELECT SQL_BIG_RESULT 1 FROM t' );
	}

	public function testSmallResult() {
		$this->db = new DatabaseTestHelper( __METHOD__ );
		$this->sqb = $this->db->newSelectQueryBuilder();
		$this->sqb
			->smallResult()
			->select( '1' )
			->from( 't' );
		$this->assertSQL( 'SELECT SQL_SMALL_RESULT 1 FROM t' );
	}

	public function testCalcFoundRows() {
		$this->db = new DatabaseTestHelper( __METHOD__ );
		$this->sqb = $this->db->newSelectQueryBuilder();
		$this->sqb
			->calcFoundRows()
			->select( '1' )
			->from( 't' );
		$this->assertSQL( 'SELECT SQL_CALC_FOUND_ROWS 1 FROM t' );
	}

	public function testOption() {
		$this->db = new DatabaseTestHelper( __METHOD__ );
		$this->sqb = $this->db->newSelectQueryBuilder();
		$this->sqb
			->select( 'f' )
			->from( 't' )
			->option( 'ORDER BY', 'a' );
		$this->assertSQL( 'SELECT f FROM t ORDER BY a' );
	}

	public function testOptions() {
		$this->db = new DatabaseTestHelper( __METHOD__ );
		$this->sqb = $this->db->newSelectQueryBuilder();
		$this->sqb
			->select( '1' )
			->options( [ 'ORDER BY' => '1', 'GROUP BY' => '2' ] );
		$this->assertSQL( 'SELECT 1 GROUP BY 2 ORDER BY 1' );
	}

	public function testFetchResultSet() {
		$this->db = new DatabaseTestHelper( __METHOD__ );
		$this->sqb = $this->db->newSelectQueryBuilder();
		$this->sqb->select( '1' )->caller( __METHOD__ );
		$res = $this->sqb->fetchResultSet();
		$this->assertEquals( 'SELECT 1', $this->db->getLastSqls() );
		$this->assertInstanceOf( IResultWrapper::class, $res );
	}

	public function testFetchField() {
		$this->db = new DatabaseTestHelper( __METHOD__ );
		$this->sqb = $this->db->newSelectQueryBuilder();
		$this->sqb->select( '1' )->caller( __METHOD__ );
		$this->sqb->fetchField();
		$this->assertEquals( 'SELECT 1 LIMIT 1', $this->db->getLastSqls() );
	}

	public function testFetchFieldValues() {
		$this->db = new DatabaseTestHelper( __METHOD__ );
		$this->sqb = $this->db->newSelectQueryBuilder();
		$this->sqb->select( '1' )->caller( __METHOD__ );
		$res = $this->sqb->fetchFieldValues();
		$this->assertEquals( 'SELECT 1 AS value', $this->db->getLastSqls() );
		$this->assertIsArray( $res );
	}

	public function testFetchRow() {
		$this->db = new DatabaseTestHelper( __METHOD__ );
		$this->sqb = $this->db->newSelectQueryBuilder();
		$this->sqb->select( '1' )->caller( __METHOD__ );
		$this->sqb->fetchRow();
		$this->assertEquals( 'SELECT 1 LIMIT 1', $this->db->getLastSqls() );
	}

	public function testFetchRowCount() {
		$this->db = new DatabaseTestHelper( __METHOD__ );
		$this->sqb = $this->db->newSelectQueryBuilder();
		$this->sqb->table( 't' )->caller( __METHOD__ );
		$this->sqb->fetchRowCount();
		$this->assertEquals( 'SELECT COUNT(*) AS rowcount FROM (SELECT 1 FROM t     ) tmp_count',
			$this->db->getLastSqls() );
	}

	public function testFetchRowCountWithField() {
		$this->db = new DatabaseTestHelper( __METHOD__ );
		$this->sqb = $this->db->newSelectQueryBuilder();
		$this->sqb->table( 't' )->field( 'f' )->caller( __METHOD__ );
		$this->sqb->fetchRowCount();
		$this->assertEquals( 'SELECT COUNT(*) AS rowcount FROM (SELECT 1 FROM t WHERE (f IS NOT NULL)  ) tmp_count',
			$this->db->getLastSqls() );
	}

	public function testEstimateRowCount() {
		$this->db = new DatabaseTestHelper( __METHOD__ );
		$this->sqb = $this->db->newSelectQueryBuilder();
		$this->sqb
			->table( 't' )
			->conds( [ 'a' => 'b' ] )
			->caller( __METHOD__ );
		$this->sqb->estimateRowCount();
		$this->assertEquals( 'SELECT COUNT(*) AS rowcount FROM t WHERE a = \'b\'',
			$this->db->getLastSqls() );
	}

	public function testBuildGroupConcatField() {
		$this->db = new DatabaseTestHelper( __METHOD__ );
		$this->sqb = $this->db->newSelectQueryBuilder();
		$this->sqb
			->select( 'f' )
			->from( 't' )
			->caller( __METHOD__ );
		$res = $this->sqb->buildGroupConcatField( '|' );
		$this->assertEquals( '(SELECT  GROUP_CONCAT(f SEPARATOR \'|\')  FROM t     )',
			$res );
	}

	public function testGetSQL() {
		$this->db = new DatabaseTestHelper( __METHOD__ );
		$this->sqb = $this->db->newSelectQueryBuilder();
		$this->sqb
			->select( 'f' )
			->from( 't' )
			->caller( __METHOD__ );
		$res = $this->sqb->getSQL();
		$this->assertEquals( 'SELECT  f  FROM t     ', $res );
	}

	public function testGetQueryInfo() {
		$this->db = new DatabaseTestHelper( __METHOD__ );
		$this->sqb = $this->db->newSelectQueryBuilder();
		$this->sqb
			->select( 'f' )
			->from( 't' )
			->conds( [ 'a' => 'b' ] )
			->join( 'u', 'u', 'tt=uu' )
			->limit( 1 )
			->caller( 'foo' );
		$this->assertEquals(
			[
				'tables' => [ 't', 'u' => 'u' ],
				'fields' => [ 'f' ],
				'conds' => [ 'a' => 'b' ],
				'options' => [ 'LIMIT' => 1 ],
				'join_conds' => [ 'u' => [ 'JOIN', 'tt=uu' ] ],
				'caller' => 'foo',
			],
			$this->sqb->getQueryInfo() );
	}

	public function testQueryInfo() {
		$this->db = new DatabaseTestHelper( __METHOD__ );
		$this->sqb = $this->db->newSelectQueryBuilder();
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

	public function testGetQueryInfoJoins() {
		$this->db = new DatabaseTestHelper( __METHOD__ );
		$this->sqb = $this->db->newSelectQueryBuilder();
		$this->sqb
			->select( 'f' )
			->from( 't' )
			->conds( [ 'a' => 'b' ] )
			->join( 'u', 'u', 'tt=uu' )
			->limit( 1 )
			->caller( 'foo' );
		$this->assertEquals(
			[
				'tables' => [ 't', 'u' => 'u' ],
				'fields' => [ 'f' ],
				'conds' => [ 'a' => 'b' ],
				'options' => [ 'LIMIT' => 1 ],
				'joins' => [ 'u' => [ 'JOIN', 'tt=uu' ] ],
				'caller' => 'foo',
			],
			$this->sqb->getQueryInfo( 'joins' ) );
	}

	public function testQueryInfoJoins() {
		$this->db = new DatabaseTestHelper( __METHOD__ );
		$this->sqb = $this->db->newSelectQueryBuilder();
		$this->sqb->queryInfo(
			[
				'tables' => [ 't', 'u' => 'u' ],
				'fields' => [ 'f' ],
				'conds' => [ 'a' => 'b' ],
				'options' => [ 'LIMIT' => 1 ],
				'joins' => [ 'u' => [ 'JOIN', 'tt=uu' ] ]
			]
		);
		$this->assertSQL( "SELECT f FROM t JOIN u ON ((tt=uu)) WHERE a = 'b' LIMIT 1" );
	}

	public function testQueryInfoMerge() {
		$this->db = new DatabaseTestHelper( __METHOD__ );
		$this->sqb = $this->db->newSelectQueryBuilder();
		$this->sqb
			->select( [ 'a', 'b' => 'c' ] )
			->from( 't' )
			->where( [ 'a' => '1' ] )
			->orderBy( 'a' )
			->queryInfo( [
				'tables' => [ 'u' => 'u' ],
				'fields' => [ 'd' ],
				'conds' => [ 'a' => '2' ],
				'options' => [ 'LIMIT' => 1 ],
				'joins' => [ 'u' => [ 'JOIN', 'tt=uu' ] ]
			] );
		$this->assertSQL( "SELECT a,c AS b,d FROM t JOIN u ON ((tt=uu)) WHERE a = '1' AND (a = '2') ORDER BY a LIMIT 1" );
	}

	public function testMerge() {
		$this->db = new DatabaseTestHelper( __METHOD__ );
		$this->sqb = $this->db->newSelectQueryBuilder();
		$this->sqb
			->select( [ 'a', 'b' => 'c' ] )
			->from( 't' )
			->where( [ 'a' => '1' ] )
			->orderBy( 'a' )
			->merge(
				( new SelectQueryBuilder( $this->db ) )
					->select( 'd' )
					->from( 'u' )
					->join( 'v', 'v', 'uu=vv' )
					->where( [ 'a' => '2' ] )
					->limit( 1 )
			);
		$this->assertSQL( "SELECT a,c AS b,d FROM t,u JOIN v ON ((uu=vv)) WHERE a = '1' AND (a = '2') ORDER BY a LIMIT 1" );
	}

	public function testMergeCaller() {
		$this->db = new DatabaseTestHelper( __METHOD__ );
		$this->sqb = $this->db->newSelectQueryBuilder();
		$tsqb = TestingAccessWrapper::newFromObject( $this->sqb );
		$this->sqb->caller( 'A' );
		$this->assertSame( 'A', $tsqb->caller );

		// Merging a builder which has the default caller of __CLASS__
		// should not overwrite an explicit caller in the destination.
		$this->sqb->merge( new SelectQueryBuilder( $this->db ) );
		$this->assertSame( 'A', $tsqb->caller );

		// However, by analogy with option merging, an explicitly set caller
		// should be copied into the merge destination even when the destination
		// already had a caller.
		$this->sqb->merge(
			( new SelectQueryBuilder( $this->db ) )
				->caller( 'B' )
		);
		$this->assertSame( 'B', $tsqb->caller );
	}

	public function testAcquireRowLocks() {
		$this->db = new DatabaseTestHelper( __METHOD__ );
		$this->sqb = $this->db->newSelectQueryBuilder();
		$this->sqb
			->table( 't' )
			->conds( [ 'a' => 'b' ] )
			->forUpdate()
			->caller( __METHOD__ )
			->acquireRowLocks();
		$this->assertEquals( 'SELECT 1 FROM t WHERE a = \'b\'   FOR UPDATE',
			$this->db->getLastSqls() );
	}

	public function testClearFields() {
		$this->db = new DatabaseTestHelper( __METHOD__ );
		$this->sqb = $this->db->newSelectQueryBuilder();
		$this->sqb
			->fields( [ 'a', 'b' ] )
			->clearFields()
			->fields( [ 'c', 'd' ] );
		$this->assertSQL( "SELECT c,d" );
	}
}
