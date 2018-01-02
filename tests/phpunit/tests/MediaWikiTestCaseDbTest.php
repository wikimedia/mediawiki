<?php
use Wikimedia\Rdbms\DBError;
use Wikimedia\Rdbms\IDatabase;

/**
 * @covers MediaWikiTestCase
 *
 * @group Database
 */
class MediaWikiTestCaseDbTest extends MediaWikiTestCase {

	public function testDbPrefix() {
		global $wgDBprefix;

		// $wgDBprefix is overwritten for unit tests!
		$this->assertNotSame( '', $wgDBprefix, '$wgDBprefix' );
		$this->assertSame( $wgDBprefix, $this->dbPrefix() );
		$this->assertSame( $this->dbPrefix(), $this->db->tablePrefix() );
	}

	public function testMasterConnection() {
		$dbw = wfGetDB( DB_MASTER );
		$this->assertSame( $this->db, $dbw );
		$this->assertWriteAllowed( $dbw );
	}

	public function testReplicaConnection() {
		$dbr = wfGetDB( DB_REPLICA );
		$this->assertNotSame( $this->db, $dbr );
		$this->assertWriteForbidden( $dbr );
	}

	private function assertWriteForbidden( IDatabase $db ) {
		try {
			$db->delete( 'user', [ 'user_id' => 57634126 ], 'TEST' );
			$this->fail( 'Write operation should have failed!' );
		} catch ( DBError $ex ) {
			// check that the exception message contains "Write operation"
			$constriant = new PHPUnit_Framework_Constraint_StringContains( 'Write operation' );

			if ( !$constriant->evaluate( $ex->getMessage(), '', true ) ) {
				// re-throw original error, to preserve stack trace
				throw $ex;
			}
		} finally {
			$db->rollback( __METHOD__, 'flush' );
		}
	}

	private function assertWriteAllowed( IDatabase $db ) {
		try {
			$this->assertNotSame( false, $db->delete( 'user', [ 'user_id' => 57634126 ] ) );
		} finally {
			$db->rollback( __METHOD__, 'flush' );
		}
	}

}
