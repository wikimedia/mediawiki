<?php

use Wikimedia\Rdbms\DeleteQueryBuilder;
use Wikimedia\Rdbms\Platform\ISQLPlatform;

/**
 * @covers \Wikimedia\Rdbms\DeleteQueryBuilder
 */
class DeleteQueryBuilderTest extends PHPUnit\Framework\TestCase {
	use MediaWikiCoversValidator;

	/** @var DatabaseTestHelper */
	private $db;

	/** @var DeleteQueryBuilder */
	private $dqb;

	protected function setUp(): void {
		$this->db = new DatabaseTestHelper( __CLASS__ . '::' . $this->getName() );
		$this->dqb = $this->db->newDeleteQueryBuilder();
	}

	private function assertSQL( $expected, $fname ) {
		$this->dqb->caller( $fname )->execute();
		$actual = $this->db->getLastSqls();
		$actual = preg_replace( '/ +/', ' ', $actual );
		$actual = rtrim( $actual, " " );
		$this->assertEquals( $expected, $actual );
	}

	public function testCondsEtc() {
		$this->dqb
			->table( 'a' )
			->where( '1' )
			->andWhere( '2' )
			->conds( '3' );
		$this->assertSQL( 'DELETE FROM a WHERE (1) AND (2) AND (3)', __METHOD__ );
	}

	public function testConflictingConds() {
		$this->dqb
			->deleteFrom( '1' )
			->where( [ 'k' => 'v1' ] )
			->andWhere( [ 'k' => 'v2' ] );
		$this->assertSQL( 'DELETE FROM 1 WHERE k = \'v1\' AND (k = \'v2\')', __METHOD__ );
	}

	public function testCondsAllRows() {
		$this->dqb
			->table( 'a' )
			->where( ISQLPlatform::ALL_ROWS );
		$this->assertSQL( 'DELETE FROM a', __METHOD__ );
	}

	public function testExecute() {
		$this->dqb->deleteFrom( 't' )->where( 'c' )->caller( __METHOD__ );
		$this->dqb->execute();
		$this->assertEquals( 'DELETE FROM t WHERE (c)', $this->db->getLastSqls() );
	}

	public function testGetQueryInfo() {
		$this->dqb
			->deleteFrom( 't' )
			->where( [ 'a' => 'b' ] )
			->caller( 'foo' );
		$this->assertEquals(
			[
				'table' => 't' ,
				'conds' => [ 'a' => 'b' ],
				'caller' => 'foo',
			],
			$this->dqb->getQueryInfo() );
	}

	public function testQueryInfo() {
		$this->dqb->queryInfo(
			[
				'table' => 't',
				'conds' => [ 'a' => 'b' ],
			]
		);
		$this->assertSQL( "DELETE FROM t WHERE a = 'b'", __METHOD__ );
	}
}
