<?php

use Wikimedia\Rdbms\DatabasePostgres;
use Wikimedia\ScopedCallback;
use Wikimedia\TestingAccessWrapper;

/**
 * @group Database
 */
class DatabasePostgresTest extends MediaWikiTestCase {

	private function doTestInsertIgnore() {
		$reset = new ScopedCallback( function () {
			if ( $this->db->explicitTrxActive() ) {
				$this->db->rollback( __METHOD__ );
			}
			$this->db->query( 'DROP TABLE IF EXISTS ' . $this->db->tableName( 'foo' ) );
		} );

		$this->db->query(
			"CREATE TEMPORARY TABLE {$this->db->tableName( 'foo' )} (i INTEGER NOT NULL PRIMARY KEY)"
		);
		$this->db->insert( 'foo', [ [ 'i' => 1 ], [ 'i' => 2 ] ], __METHOD__ );

		// Normal INSERT IGNORE
		$this->db->begin( __METHOD__ );
		$this->db->insert(
			'foo', [ [ 'i' => 3 ], [ 'i' => 2 ], [ 'i' => 5 ] ], __METHOD__, [ 'IGNORE' ]
		);
		$this->assertSame( 2, $this->db->affectedRows() );
		$this->assertSame(
			[ '1', '2', '3', '5' ],
			$this->db->selectFieldValues( 'foo', 'i', [], __METHOD__, [ 'ORDER BY' => 'i' ] )
		);
		$this->db->rollback( __METHOD__ );

		// INSERT IGNORE doesn't ignore stuff like NOT NULL violations
		$this->db->begin( __METHOD__ );
		try {
			$this->db->insert(
				'foo', [ [ 'i' => 7 ], [ 'i' => null ] ], __METHOD__, [ 'IGNORE' ]
			);
			$this->fail( 'Expected exception not thrown' );
		} catch ( DBQueryError $e ) {
		}
		$this->assertSame( 0, $this->db->affectedRows() );
		$this->assertSame(
			[ '1', '2' ],
			$this->db->selectFieldValues( 'foo', 'i', [], __METHOD__, [ 'ORDER BY' => 'i' ] )
		);
		$this->db->rollback( __METHOD__ );
	}

	/**
	 * @covers Wikimedia\Rdbms\DatabasePostgres::insert
	 */
	public function testInsertIgnoreOld() {
		if ( !$this->db instanceof DatabasePostgres ) {
			$this->markTestSkipped( 'Not PostgreSQL' );
		}
		if ( $this->db->getServerVersion() < 9.5 ) {
			$this->doTestInsertIgnore();
		} else {
			// Hack version to make it take the old code path
			$w = TestingAccessWrapper::newFromObject( $this->db );
			$oldVer = $w->numericVersion;
			$w->numericVersion = 9.4;
			try {
				$this->doTestInsertIgnore();
			} finally {
				$w->numericVersion = $oldVer;
			}
		}
	}

	/**
	 * @covers Wikimedia\Rdbms\DatabasePostgres::insert
	 */
	public function testInsertIgnoreNew() {
		if ( !$this->db instanceof DatabasePostgres ) {
			$this->markTestSkipped( 'Not PostgreSQL' );
		}
		if ( $this->db->getServerVersion() < 9.5 ) {
			$this->markTestSkipped( 'PostgreSQL version is ' . $this->db->getServerVersion() );
		}

		$this->doTestInsertIgnore();
	}

}
