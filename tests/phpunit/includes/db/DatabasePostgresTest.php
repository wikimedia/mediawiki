<?php

use Wikimedia\Rdbms\IDatabase;
use Wikimedia\Rdbms\DatabasePostgres;
use Wikimedia\ScopedCallback;
use Wikimedia\TestingAccessWrapper;

/**
 * @group Database
 */
class DatabasePostgresTest extends MediaWikiTestCase {

	private function doTestInsertIgnore() {
		$fname = __METHOD__;
		$reset = new ScopedCallback( function () use ( $fname ) {
			if ( $this->db->explicitTrxActive() ) {
				$this->db->rollback( $fname );
			}
			$this->db->query( 'DROP TABLE IF EXISTS ' . $this->db->tableName( 'foo' ), $fname );
		} );

		$this->db->query(
			"CREATE TEMPORARY TABLE {$this->db->tableName( 'foo' )} (i INTEGER NOT NULL PRIMARY KEY)",
			__METHOD__
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
		$this->db->startAtomic( __METHOD__, IDatabase::ATOMIC_CANCELABLE );
		try {
			$this->db->insert(
				'foo', [ [ 'i' => 7 ], [ 'i' => null ] ], __METHOD__, [ 'IGNORE' ]
			);
			$this->db->endAtomic( __METHOD__ );
			$this->fail( 'Expected exception not thrown' );
		} catch ( DBQueryError $e ) {
			$this->assertSame( 0, $this->db->affectedRows() );
			$this->db->cancelAtomic( __METHOD__ );
		}
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

	private function doTestInsertSelectIgnore() {
		$fname = __METHOD__;
		$reset = new ScopedCallback( function () use ( $fname ) {
			if ( $this->db->explicitTrxActive() ) {
				$this->db->rollback( $fname );
			}
			$this->db->query( 'DROP TABLE IF EXISTS ' . $this->db->tableName( 'foo' ), $fname );
			$this->db->query( 'DROP TABLE IF EXISTS ' . $this->db->tableName( 'bar' ), $fname );
		} );

		$this->db->query(
			"CREATE TEMPORARY TABLE {$this->db->tableName( 'foo' )} (i INTEGER)",
			__METHOD__
		);
		$this->db->query(
			"CREATE TEMPORARY TABLE {$this->db->tableName( 'bar' )} (i INTEGER NOT NULL PRIMARY KEY)",
			__METHOD__
		);
		$this->db->insert( 'bar', [ [ 'i' => 1 ], [ 'i' => 2 ] ], __METHOD__ );

		// Normal INSERT IGNORE
		$this->db->begin( __METHOD__ );
		$this->db->insert( 'foo', [ [ 'i' => 3 ], [ 'i' => 2 ], [ 'i' => 5 ] ], __METHOD__ );
		$this->db->insertSelect( 'bar', 'foo', [ 'i' => 'i' ], [], __METHOD__, [ 'IGNORE' ] );
		$this->assertSame( 2, $this->db->affectedRows() );
		$this->assertSame(
			[ '1', '2', '3', '5' ],
			$this->db->selectFieldValues( 'bar', 'i', [], __METHOD__, [ 'ORDER BY' => 'i' ] )
		);
		$this->db->rollback( __METHOD__ );

		// INSERT IGNORE doesn't ignore stuff like NOT NULL violations
		$this->db->begin( __METHOD__ );
		$this->db->insert( 'foo', [ [ 'i' => 7 ], [ 'i' => null ] ], __METHOD__ );
		$this->db->startAtomic( __METHOD__, IDatabase::ATOMIC_CANCELABLE );
		try {
			$this->db->insertSelect( 'bar', 'foo', [ 'i' => 'i' ], [], __METHOD__, [ 'IGNORE' ] );
			$this->db->endAtomic( __METHOD__ );
			$this->fail( 'Expected exception not thrown' );
		} catch ( DBQueryError $e ) {
			$this->assertSame( 0, $this->db->affectedRows() );
			$this->db->cancelAtomic( __METHOD__ );
		}
		$this->assertSame(
			[ '1', '2' ],
			$this->db->selectFieldValues( 'bar', 'i', [], __METHOD__, [ 'ORDER BY' => 'i' ] )
		);
		$this->db->rollback( __METHOD__ );
	}

	/**
	 * @covers Wikimedia\Rdbms\DatabasePostgres::nativeInsertSelect
	 */
	public function testInsertSelectIgnoreOld() {
		if ( !$this->db instanceof DatabasePostgres ) {
			$this->markTestSkipped( 'Not PostgreSQL' );
		}
		if ( $this->db->getServerVersion() < 9.5 ) {
			$this->doTestInsertSelectIgnore();
		} else {
			// Hack version to make it take the old code path
			$w = TestingAccessWrapper::newFromObject( $this->db );
			$oldVer = $w->numericVersion;
			$w->numericVersion = 9.4;
			try {
				$this->doTestInsertSelectIgnore();
			} finally {
				$w->numericVersion = $oldVer;
			}
		}
	}

	/**
	 * @covers Wikimedia\Rdbms\DatabasePostgres::nativeInsertSelect
	 */
	public function testInsertSelectIgnoreNew() {
		if ( !$this->db instanceof DatabasePostgres ) {
			$this->markTestSkipped( 'Not PostgreSQL' );
		}
		if ( $this->db->getServerVersion() < 9.5 ) {
			$this->markTestSkipped( 'PostgreSQL version is ' . $this->db->getServerVersion() );
		}

		$this->doTestInsertSelectIgnore();
	}

}
