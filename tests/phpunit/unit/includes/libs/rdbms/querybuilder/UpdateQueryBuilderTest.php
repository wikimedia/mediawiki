<?php

namespace Wikimedia\Tests\Rdbms;

use DatabaseTestHelper;
use MediaWikiCoversValidator;
use PHPUnit\Framework\TestCase;
use Wikimedia\Rdbms\Platform\ISQLPlatform;
use Wikimedia\Rdbms\UpdateQueryBuilder;

/**
 * @covers \Wikimedia\Rdbms\UpdateQueryBuilder
 */
class UpdateQueryBuilderTest extends TestCase {
	use MediaWikiCoversValidator;

	private DatabaseTestHelper $db;
	private UpdateQueryBuilder $uqb;

	private function assertSQL( $expected, $fname ) {
		$this->uqb->caller( $fname )->execute();
		$actual = $this->db->getLastSqls();
		$actual = preg_replace( '/ +/', ' ', $actual );
		$actual = rtrim( $actual, " " );
		$this->assertEquals( $expected, $actual );
	}

	public function testSet() {
		$this->db = new DatabaseTestHelper( __METHOD__ );
		$this->uqb = $this->db->newUpdateQueryBuilder();
		$this->uqb
			->table( 'a' )
			->set( [ 'f' => 'g' ] )
			->andSet( [ 'd' => 'l' ] )
			->where( '1' )
			->andWhere( '2' )
			->conds( '3' );
		$this->assertSQL( "UPDATE a SET f = 'g',d = 'l' WHERE (1) AND (2) AND (3)", __METHOD__ );
	}

	public function testConflictingSet() {
		$this->db = new DatabaseTestHelper( __METHOD__ );
		$this->uqb = $this->db->newUpdateQueryBuilder();
		// T288882: the empty set is the right answer
		$this->uqb
			->update( 't' )
			->set( [ 'f' => 'g' ] )
			->andSet( [ 'f' => 'l' ] )
			->where( [ 'k' => 'v1' ] );
		$this->assertSQL( "UPDATE t SET f = 'l' WHERE k = 'v1'", __METHOD__ );
	}

	public function testCondsEtc() {
		$this->db = new DatabaseTestHelper( __METHOD__ );
		$this->uqb = $this->db->newUpdateQueryBuilder();
		$this->uqb
			->table( 'a' )
			->set( 'f' )
			->where( '1' )
			->andWhere( '2' )
			->conds( '3' );
		$this->assertSQL( 'UPDATE a SET f WHERE (1) AND (2) AND (3)', __METHOD__ );
	}

	public function testConflictingConds() {
		$this->db = new DatabaseTestHelper( __METHOD__ );
		$this->uqb = $this->db->newUpdateQueryBuilder();
		// T288882: the empty set is the right answer
		$this->uqb
			->update( '1' )
			->set( 'a' )
			->where( [ 'k' => 'v1' ] )
			->andWhere( [ 'k' => 'v2' ] );
		$this->assertSQL( 'UPDATE 1 SET a WHERE k = \'v1\' AND (k = \'v2\')', __METHOD__ );
	}

	public function testCondsAllRows() {
		$this->db = new DatabaseTestHelper( __METHOD__ );
		$this->uqb = $this->db->newUpdateQueryBuilder();
		$this->uqb
			->update( '1' )
			->set( 'a' )
			->where( ISQLPlatform::ALL_ROWS );
		$this->assertSQL( 'UPDATE 1 SET a', __METHOD__ );
	}

	public function testIgnore() {
		$this->db = new DatabaseTestHelper( __METHOD__ );
		$this->uqb = $this->db->newUpdateQueryBuilder();
		$this->uqb
			->update( 'f' )
			->set( 't' )
			->where( 'c' )
			->ignore();
		$this->assertSQL( 'UPDATE IGNORE f SET t WHERE (c)', __METHOD__ );
	}

	public function testOption() {
		$this->db = new DatabaseTestHelper( __METHOD__ );
		$this->uqb = $this->db->newUpdateQueryBuilder();
		$this->uqb
			->update( 't' )
			->set( 'f' )
			->where( 'c' )
			->option( 'IGNORE' );
		$this->assertSQL( 'UPDATE IGNORE t SET f WHERE (c)', __METHOD__ );
	}

	public function testOptions() {
		$this->db = new DatabaseTestHelper( __METHOD__ );
		$this->uqb = $this->db->newUpdateQueryBuilder();
		$this->uqb
			->update( 't' )
			->set( 'f' )
			->where( 'c' )
			->options( [ 'IGNORE' ] );
		$this->assertSQL( 'UPDATE IGNORE t SET f WHERE (c)', __METHOD__ );
	}

	public function testExecute() {
		$this->db = new DatabaseTestHelper( __METHOD__ );
		$this->uqb = $this->db->newUpdateQueryBuilder();
		$this->uqb->update( 't' )->set( 'f' )->where( 'c' )->caller( __METHOD__ );
		$this->uqb->execute();
		$this->assertEquals( 'UPDATE t SET f WHERE (c)', $this->db->getLastSqls() );
	}

	public function testGetQueryInfo() {
		$this->db = new DatabaseTestHelper( __METHOD__ );
		$this->uqb = $this->db->newUpdateQueryBuilder();
		$this->uqb
			->update( 't' )
			->ignore()
			->set( [ 'f' => 'g' ] )
			->andSet( [ 'd' => 'l' ] )
			->where( [ 'a' => 'b' ] )
			->caller( 'foo' );
		$this->assertEquals(
			[
				'table' => 't',
				'set' => [ 'f' => 'g', 'd' => 'l' ],
				'conds' => [ 'a' => 'b' ],
				'options' => [ 'IGNORE' ],
				'caller' => 'foo',
			],
			$this->uqb->getQueryInfo() );
	}

	public function testQueryInfo() {
		$this->db = new DatabaseTestHelper( __METHOD__ );
		$this->uqb = $this->db->newUpdateQueryBuilder();
		$this->uqb->queryInfo(
			[
				'table' => 't',
				'set' => [ 'f' => 'g' ],
				'conds' => [ 'a' => 'b' ],
				'options' => [ 'IGNORE' ],
			]
		);
		$this->assertSQL( "UPDATE IGNORE t SET f = 'g' WHERE a = 'b'", __METHOD__ );
	}
}
