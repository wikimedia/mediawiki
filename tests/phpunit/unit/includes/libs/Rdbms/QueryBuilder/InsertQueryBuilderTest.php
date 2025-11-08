<?php

namespace Wikimedia\Tests\Rdbms;

use DatabaseTestHelper;
use MediaWikiCoversValidator;
use PHPUnit\Framework\TestCase;
use Wikimedia\Rdbms\InsertQueryBuilder;

/**
 * @covers \Wikimedia\Rdbms\InsertQueryBuilder
 */
class InsertQueryBuilderTest extends TestCase {
	use MediaWikiCoversValidator;

	private DatabaseTestHelper $db;
	private InsertQueryBuilder $iqb;

	private function assertSQL( $expected, $fname ) {
		$this->iqb->caller( $fname )->execute();
		$actual = $this->db->getLastSqls();
		$actual = preg_replace( '/ +/', ' ', $actual );
		$actual = rtrim( $actual, " " );
		$this->assertEquals( $expected, $actual );
	}

	public function testSimpleInsert() {
		$this->db = new DatabaseTestHelper( __METHOD__ );
		$this->iqb = $this->db->newInsertQueryBuilder();
		$this->iqb
			->insertInto( 'a' )
			->row( [ 'f' => 'g', 'd' => 'l' ] );
		$this->assertSQL( "INSERT INTO a (f,d) VALUES ('g','l')", __METHOD__ );
	}

	public function testIgnore() {
		$this->db = new DatabaseTestHelper( __METHOD__ );
		$this->iqb = $this->db->newInsertQueryBuilder();
		$this->iqb
			->insertInto( 'a' )
			->ignore()
			->row( [ 'f' => 'g', 'd' => 'l' ] );
		$this->assertSQL( "INSERT IGNORE INTO a (f,d) VALUES ('g','l')", __METHOD__ );
	}

	public function testUpsert() {
		$this->db = new DatabaseTestHelper( __METHOD__ );
		$this->iqb = $this->db->newInsertQueryBuilder();
		$this->iqb
			->insertInto( 'a' )
			->row( [ 'f' => 'g', 'd' => 'l' ] )
			->onDuplicateKeyUpdate()
			->uniqueIndexFields( [ 'd' ] )
			->set( [ 'f' => 'm' ] );
		$this->assertSQL(
			"BEGIN; UPDATE a SET f = 'm' WHERE (d = 'l'); INSERT INTO a (f,d) VALUES ('g','l'); COMMIT",
			__METHOD__
		);
	}

	public function testUpsertWithStringKey() {
		$this->db = new DatabaseTestHelper( __METHOD__ );
		$this->iqb = $this->db->newInsertQueryBuilder();
		$this->iqb
			->insertInto( 'a' )
			->row( [ 'f' => 'g', 'd' => 'l' ] )
			->onDuplicateKeyUpdate()
			->uniqueIndexFields( 'd' )
			->set( [ 'f' => 'm' ] );
		$this->assertSQL(
			"BEGIN; UPDATE a SET f = 'm' WHERE (d = 'l'); INSERT INTO a (f,d) VALUES ('g','l'); COMMIT",
			__METHOD__
		);
	}

	public function testOption() {
		$this->db = new DatabaseTestHelper( __METHOD__ );
		$this->iqb = $this->db->newInsertQueryBuilder();
		$this->iqb
			->insertInto( 't' )
			->row( [ 'f' => 'g' ] )
			->option( 'IGNORE' );
		$this->assertSQL( "INSERT IGNORE INTO t (f) VALUES ('g')", __METHOD__ );
	}

	public function testOptions() {
		$this->db = new DatabaseTestHelper( __METHOD__ );
		$this->iqb = $this->db->newInsertQueryBuilder();
		$this->iqb
			->insertInto( 't' )
			->row( [ 'f' => 'g' ] )
			->options( [ 'IGNORE' ] );
		$this->assertSQL( "INSERT IGNORE INTO t (f) VALUES ('g')", __METHOD__ );
	}

	public function testExecute() {
		$this->db = new DatabaseTestHelper( __METHOD__ );
		$this->iqb = $this->db->newInsertQueryBuilder();
		$this->iqb->insertInto( 't' )->rows( [ 'a' => 'b' ] )->caller( __METHOD__ );
		$this->iqb->execute();
		$this->assertEquals( "INSERT INTO t (a) VALUES ('b')", $this->db->getLastSqls() );
	}

	public function testGetQueryInfo() {
		$this->db = new DatabaseTestHelper( __METHOD__ );
		$this->iqb = $this->db->newInsertQueryBuilder();
		$this->iqb
			->insertInto( 't' )
			->ignore()
			->row( [ 'a' => 'b', 'd' => 'l' ] )
			->caller( 'foo' );
		$this->assertEquals(
			[
				'table' => 't',
				'rows' => [ [ 'a' => 'b', 'd' => 'l' ] ],
				'options' => [ 'IGNORE' ],
				'upsert' => false,
				'set' => [],
				'uniqueIndexFields' => [],
				'caller' => 'foo',
			],
			$this->iqb->getQueryInfo() );
	}

	public function testGetQueryInfoUpsert() {
		$this->db = new DatabaseTestHelper( __METHOD__ );
		$this->iqb = $this->db->newInsertQueryBuilder();
		$this->iqb
			->insertInto( 't' )
			->row( [ 'f' => 'g', 'd' => 'l' ] )
			->onDuplicateKeyUpdate()
			->uniqueIndexFields( [ 'd' ] )
			->set( [ 'f' => 'm' ] )
			->caller( 'foo' );
		$this->assertEquals(
			[
				'table' => 't',
				'rows' => [ [ 'f' => 'g', 'd' => 'l' ] ],
				'upsert' => true,
				'set' => [ 'f' => 'm' ],
				'uniqueIndexFields' => [ 'd' ],
				'options' => [],
				'caller' => 'foo'
			],
			$this->iqb->getQueryInfo() );
	}

	public function testQueryInfo() {
		$this->db = new DatabaseTestHelper( __METHOD__ );
		$this->iqb = $this->db->newInsertQueryBuilder();
		$this->iqb->queryInfo(
			[
				'table' => 't',
				'rows' => [ [ 'f' => 'g', 'd' => 'l' ] ],
				'options' => [ 'IGNORE' ],
			]
		);
		$this->assertSQL( "INSERT IGNORE INTO t (f,d) VALUES ('g','l')", __METHOD__ );
	}

	public function testQueryInfoUpsert() {
		$this->db = new DatabaseTestHelper( __METHOD__ );
		$this->iqb = $this->db->newInsertQueryBuilder();
		$this->iqb->queryInfo(
			[
				'table' => 't',
				'rows' => [ [ 'f' => 'g', 'd' => 'l' ] ],
				'upsert' => true,
				'set' => [ 'f' => 'm' ],
				'uniqueIndexFields' => [ 'd' ],
			]
		);
		$this->assertSQL(
			"BEGIN; UPDATE t SET f = 'm' WHERE (d = 'l'); INSERT INTO t (f,d) VALUES ('g','l'); COMMIT",
			__METHOD__
		);
	}
}
