<?php

namespace Wikimedia\Tests\Rdbms;

use DatabaseTestHelper;
use Wikimedia\Rdbms\IResultWrapper;
use Wikimedia\Rdbms\UnionQueryBuilder;

/**
 * @covers \Wikimedia\Rdbms\UnionQueryBuilder
 */
class UnionQueryBuilderTest extends \MediaWikiUnitTestCase {
	private DatabaseTestHelper $db;
	private UnionQueryBuilder $uqb;

	private function assertSql( $expected ) {
		$actual = $this->uqb->getSQL();
		$actual = preg_replace( '/ +/', ' ', $actual );
		$actual = rtrim( $actual, " " );
		$this->assertEquals( $expected, $actual );
	}

	private function assertLastSql( $expected ) {
		$actual = $this->db->getLastSqls();
		$actual = preg_replace( '/ +/', ' ', $actual );
		$actual = rtrim( $actual, " " );
		$this->assertEquals( $expected, $actual );
	}

	public function testGetSql() {
		$this->db = new DatabaseTestHelper( __METHOD__ );
		$this->uqb = $this->db->newUnionQueryBuilder();
		$this->uqb
			->add( $this->db->newSelectQueryBuilder()
				->select( 'f' )
				->from( 't1' )
			)
			->add( $this->db->newSelectQueryBuilder()
				->select( 'f' )
				->from( 't2' )
			)
			->orderBy( 'f', UnionQueryBuilder::SORT_DESC )
			->limit( 10 )
			->offset( 20 )
			->caller( __METHOD__ );
		$this->assertSql( '(SELECT f FROM t1 ) UNION (SELECT f FROM t2 ) ' .
			'ORDER BY f DESC LIMIT 20,10' );
	}

	public function testFetchResultSet() {
		$this->db = new DatabaseTestHelper( __METHOD__ );
		$this->uqb = $this->db->newUnionQueryBuilder();
		$this->uqb
			->add( $this->db->newSelectQueryBuilder()
				->select( 'f' )
				->from( 't1' )
			)
			->add( $this->db->newSelectQueryBuilder()
				->select( 'f' )
				->from( 't2' )
			)
			->caller( __METHOD__ );
		$res = $this->uqb->fetchResultSet();
		$this->assertInstanceOf( IResultWrapper::class, $res );
		$this->assertLastSql( '(SELECT f FROM t1 ) UNION (SELECT f FROM t2 )' );
	}

	public function testFetchField() {
		$this->db = new DatabaseTestHelper( __METHOD__ );
		$this->uqb = $this->db->newUnionQueryBuilder();
		$this->uqb
			->add( $this->db->newSelectQueryBuilder()
				->select( 'f' )
				->from( 't1' )
			)
			->add( $this->db->newSelectQueryBuilder()
				->select( 'f' )
				->from( 't2' )
			)
			->caller( __METHOD__ );
		$f = $this->uqb->fetchField();
		$this->assertFalse( $f );
		$this->assertLastSql( '(SELECT f FROM t1 ) UNION (SELECT f FROM t2 ) LIMIT 1' );
	}

	public function testFetchRow() {
		$this->db = new DatabaseTestHelper( __METHOD__ );
		$this->uqb = $this->db->newUnionQueryBuilder();
		$this->uqb
			->add( $this->db->newSelectQueryBuilder()
				->select( 'f' )
				->from( 't1' )
			)
			->add( $this->db->newSelectQueryBuilder()
				->select( 'f' )
				->from( 't2' )
			)
			->caller( __METHOD__ );
		$row = $this->uqb->fetchRow();
		$this->assertFalse( $row );
		$this->assertLastSql( '(SELECT f FROM t1 ) UNION (SELECT f FROM t2 ) LIMIT 1' );
	}
}
