<?php

use Wikimedia\Rdbms\IDatabase;
use Wikimedia\Rdbms\Database;

/**
 * @group Database
 */
class DatabaseIntegrationTest extends MediaWikiTestCase {
	/**
	 * @var Database
	 */
	protected $db;

	private $functionTest = false;

	protected function setUp() {
		parent::setUp();
		$this->db = wfGetDB( DB_MASTER );
	}

	protected function tearDown() {
		parent::tearDown();
		if ( $this->functionTest ) {
			$this->dropFunctions();
			$this->functionTest = false;
		}
		$this->db->restoreFlags( IDatabase::RESTORE_INITIAL );
	}

	public function testStoredFunctions() {
		if ( !in_array( wfGetDB( DB_MASTER )->getType(), [ 'mysql', 'postgres' ] ) ) {
			$this->markTestSkipped( 'MySQL or Postgres required' );
		}
		global $IP;
		$this->dropFunctions();
		$this->functionTest = true;
		$this->assertTrue(
			$this->db->sourceFile( "$IP/tests/phpunit/data/db/{$this->db->getType()}/functions.sql" )
		);
		$res = $this->db->query( 'SELECT mw_test_function() AS test', __METHOD__ );
		$this->assertEquals( 42, $res->fetchObject()->test );
	}

	private function dropFunctions() {
		$this->db->query( 'DROP FUNCTION IF EXISTS mw_test_function'
			. ( $this->db->getType() == 'postgres' ? '()' : '' )
		);
	}

	public function testUnknownTableCorruptsResults() {
		$res = $this->db->select( 'page', '*', [ 'page_id' => 1 ] );
		$this->assertFalse( $this->db->tableExists( 'foobarbaz' ) );
		$this->assertInternalType( 'int', $res->numRows() );
	}
}
