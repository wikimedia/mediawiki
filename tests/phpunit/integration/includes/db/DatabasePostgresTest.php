<?php

use Wikimedia\Rdbms\Database;
use Wikimedia\Rdbms\DatabasePostgres;
use Wikimedia\Rdbms\DBQueryError;
use Wikimedia\Rdbms\IDatabase;
use Wikimedia\ScopedCallback;
use Wikimedia\TestingAccessWrapper;

/**
 * @covers \Wikimedia\Rdbms\Database
 * @covers \Wikimedia\Rdbms\DatabasePostgres
 * @covers \Wikimedia\Rdbms\Platform\PostgresPlatform
 * @group Database
 */
class DatabasePostgresTest extends MediaWikiIntegrationTestCase {
	private const SRC_TABLE = 'tmp_src_tbl';
	private const DST_TABLE = 'tmp_dst_tbl';

	protected function setUp(): void {
		parent::setUp();
		if ( !$this->db instanceof DatabasePostgres ) {
			$this->markTestSkipped( 'Not PostgreSQL' );
		}
	}

	public function addDBDataOnce() {
		if ( $this->db instanceof DatabasePostgres ) {
			$this->createSourceTable();
			$this->createDestTable();
		}
	}

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
			$this->db->newSelectQueryBuilder()
				->select( 'i' )
				->from( 'foo' )
				->orderBy( 'i' )
				->caller( __METHOD__ )->fetchFieldValues()
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
			$this->db->newSelectQueryBuilder()
				->select( 'i' )
				->from( 'foo' )
				->orderBy( 'i' )
				->caller( __METHOD__ )->fetchFieldValues()
		);
		$this->db->rollback( __METHOD__ );
	}

	/**
	 * FIXME: See https://phabricator.wikimedia.org/T259084.
	 * @group Broken
	 */
	public function testInsertIgnoreOld() {
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
	 * FIXME: See https://phabricator.wikimedia.org/T259084.
	 * @group Broken
	 */
	public function testInsertIgnoreNew() {
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
			$this->db->newSelectQueryBuilder()
				->select( 'i' )
				->from( 'bar' )
				->orderBy( 'i' )
				->caller( __METHOD__ )->fetchFieldValues()
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
			$this->db->newSelectQueryBuilder()
				->select( 'i' )
				->from( 'bar' )
				->orderBy( 'i' )
				->caller( __METHOD__ )->fetchFieldValues()
		);
		$this->db->rollback( __METHOD__ );
	}

	/**
	 * FIXME: See https://phabricator.wikimedia.org/T259084.
	 * @group Broken
	 */
	public function testInsertSelectIgnoreOld() {
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
	 * FIXME: See https://phabricator.wikimedia.org/T259084.
	 * @group Broken
	 */
	public function testInsertSelectIgnoreNew() {
		if ( $this->db->getServerVersion() < 9.5 ) {
			$this->markTestSkipped( 'PostgreSQL version is ' . $this->db->getServerVersion() );
		}

		$this->doTestInsertSelectIgnore();
	}

	public function testAttributes() {
		$dbFactory = $this->getServiceContainer()->getDatabaseFactory();
		$this->assertTrue(
			$dbFactory->attributesFromType( 'postgres' )[Database::ATTR_SCHEMAS_AS_TABLE_GROUPS]
		);
	}

	public function testInsertIdAfterInsert() {
		$rows = [ [ 'k' => 'Luca', 'v' => mt_rand( 1, 100 ), 't' => time() ] ];

		$this->db->insert( self::DST_TABLE, $rows, __METHOD__ );
		$this->assertSame( 1, $this->db->affectedRows() );
		$this->assertSame( 1, $this->db->insertId() );

		$this->assertNWhereKEqualsLuca( 1, self::DST_TABLE );
		$this->assertSame( 1, $this->db->affectedRows() );
	}

	public function testInsertIdAfterInsertIgnore() {
		$rows = [ [ 'k' => 'Luca', 'v' => mt_rand( 1, 100 ), 't' => time() ] ];

		$this->db->insert( self::DST_TABLE, $rows, __METHOD__, 'IGNORE' );
		$this->assertSame( 1, $this->db->affectedRows() );
		$this->assertSame( 1, $this->db->insertId() );
		$this->assertNWhereKEqualsLuca( 1, self::DST_TABLE );

		$this->db->insert( self::DST_TABLE, $rows, __METHOD__, 'IGNORE' );
		$this->assertSame( 0, $this->db->affectedRows() );
		$this->assertSame( 0, $this->db->insertId() );

		$this->assertNWhereKEqualsLuca( 1, self::DST_TABLE );
		$this->assertSame( 1, $this->db->affectedRows() );
	}

	public function testInsertIdAfterReplace() {
		$rows = [ [ 'k' => 'Luca', 'v' => mt_rand( 1, 100 ), 't' => time() ] ];

		$this->db->replace( self::DST_TABLE, 'k', $rows, __METHOD__ );
		$this->assertSame( 1, $this->db->affectedRows() );
		$this->assertSame( 1, $this->db->insertId() );
		$this->assertNWhereKEqualsLuca( 1, self::DST_TABLE );

		$this->db->replace( self::DST_TABLE, 'k', $rows, __METHOD__ );
		$this->assertSame( 1, $this->db->affectedRows() );
		$this->assertSame( 2, $this->db->insertId() );

		$this->assertNWhereKEqualsLuca( 2, self::DST_TABLE );
		$this->assertSame( 1, $this->db->affectedRows() );
	}

	public function testInsertIdAfterUpsert() {
		$rows = [ [ 'k' => 'Luca', 'v' => mt_rand( 1, 100 ), 't' => time() ] ];
		$set = [
			'v = ' . $this->db->buildExcludedValue( 'v' ),
			't = ' . $this->db->buildExcludedValue( 't' ) . ' + 1'
		];

		$this->db->upsert( self::DST_TABLE, $rows, 'k', $set, __METHOD__ );
		$this->assertSame( 1, $this->db->affectedRows() );
		$this->assertSame( 1, $this->db->insertId() );
		$this->assertNWhereKEqualsLuca( 1, self::DST_TABLE );

		$this->db->upsert( self::DST_TABLE, $rows, 'k', $set, __METHOD__ );
		$this->assertSame( 1, $this->db->affectedRows() );
		$this->assertSame( 1, $this->db->insertId() );

		$this->assertNWhereKEqualsLuca( 1, self::DST_TABLE );
		$this->assertSame( 1, $this->db->affectedRows() );
	}

	public function testInsertIdAfterInsertSelect() {
		$rows = [ [ 'sk' => 'Luca', 'sv' => mt_rand( 1, 100 ), 'st' => time() ] ];
		$this->db->insert( self::SRC_TABLE, $rows, __METHOD__, 'IGNORE' );
		$this->assertSame( 1, $this->db->affectedRows() );
		$this->assertSame( 1, $this->db->insertId() );
		$this->assertSame( 1, (int)$this->db->newSelectQueryBuilder()
			->select( 'sn' )
			->from( self::SRC_TABLE )
			->where( [ 'sk' => 'Luca' ] )
			->fetchField() );

		$this->db->insertSelect(
			self::DST_TABLE,
			self::SRC_TABLE,
			[ 'k' => 'sk', 'v' => 'sv', 't' => 'st' ],
			[ 'sk' => 'Luca' ],
			__METHOD__,
			'IGNORE'
		);
		$this->assertSame( 1, $this->db->affectedRows() );
		$this->assertSame( 1, $this->db->insertId() );

		$this->assertNWhereKEqualsLuca( 1, self::DST_TABLE );
		$this->assertSame( 1, $this->db->affectedRows() );
	}

	public function testInsertIdAfterInsertSelectIgnore() {
		$rows = [ [ 'sk' => 'Luca', 'sv' => mt_rand( 1, 100 ), 'st' => time() ] ];
		$this->db->insert( self::SRC_TABLE, $rows, __METHOD__, 'IGNORE' );
		$this->assertSame( 1, $this->db->affectedRows() );
		$this->assertSame( 1, $this->db->insertId() );
		$this->assertSame( 1, (int)$this->db->newSelectQueryBuilder()
			->select( 'sn' )
			->from( self::SRC_TABLE )
			->where( [ 'sk' => 'Luca' ] )
			->fetchField() );

		$this->db->insertSelect(
			self::DST_TABLE,
			self::SRC_TABLE,
			[ 'k' => 'sk', 'v' => 'sv', 't' => 'st' ],
			[ 'sk' => 'Luca' ],
			__METHOD__,
			'IGNORE'
		);
		$this->assertSame( 1, $this->db->affectedRows() );
		$this->assertSame( 1, $this->db->insertId() );
		$this->assertNWhereKEqualsLuca( 1, self::DST_TABLE );

		$this->db->insertSelect(
			self::DST_TABLE,
			self::SRC_TABLE,
			[ 'k' => 'sk', 'v' => 'sv', 't' => 'st' ],
			[ 'sk' => 'Luca' ],
			__METHOD__,
			'IGNORE'
		);
		$this->assertSame( 0, $this->db->affectedRows() );
		$this->assertSame( 0, $this->db->insertId() );

		$this->assertNWhereKEqualsLuca( 1, self::DST_TABLE );
		$this->assertSame( 1, $this->db->affectedRows() );
	}

	public function testFieldAndIndexInfo() {
		global $wgDBname;

		$this->db->selectDomain( $wgDBname );
		$this->db->query(
			"CREATE TEMPORARY TABLE tmp_schema_tbl (" .
			"n serial not null, " .
			"k text not null, " .
			"v integer, " .
			"t integer, " .
			"PRIMARY KEY(n)" .
			")"
		);
		$this->db->query( "CREATE UNIQUE INDEX k ON tmp_schema_tbl (k)" );
		$this->db->query( "CREATE INDEX t ON tmp_schema_tbl (t)" );

		$this->assertTrue( $this->db->fieldExists( 'tmp_schema_tbl', 'n' ) );
		$this->assertTrue( $this->db->fieldExists( 'tmp_schema_tbl', 'k' ) );
		$this->assertTrue( $this->db->fieldExists( 'tmp_schema_tbl', 'v' ) );
		$this->assertTrue( $this->db->fieldExists( 'tmp_schema_tbl', 't' ) );
		$this->assertFalse( $this->db->fieldExists( 'tmp_schema_tbl', 'x' ) );

		$this->assertTrue( $this->db->indexExists( 'tmp_schema_tbl', 'k' ) );
		$this->assertTrue( $this->db->indexExists( 'tmp_schema_tbl', 't' ) );
		$this->assertFalse( $this->db->indexExists( 'tmp_schema_tbl', 'x' ) );
		$this->assertFalse( $this->db->indexExists( 'tmp_schema_tbl', 'PRIMARY' ) );
		$this->assertTrue( $this->db->indexExists( 'tmp_schema_tbl', 'tmp_schema_tbl_pkey' ) );

		$this->assertTrue( $this->db->indexUnique( 'tmp_schema_tbl', 'k' ) );
		$this->assertFalse( $this->db->indexUnique( 'tmp_schema_tbl', 't' ) );
		$this->assertNull( $this->db->indexUnique( 'tmp_schema_tbl', 'x' ) );
		$this->assertNull( $this->db->indexUnique( 'tmp_schema_tbl', 'PRIMARY' ) );
		$this->assertTrue( $this->db->indexExists( 'tmp_schema_tbl', 'tmp_schema_tbl_pkey' ) );
	}

	private function assertNWhereKEqualsLuca( $expected, $table ) {
		$this->assertSame( $expected, (int)$this->db->newSelectQueryBuilder()
			->select( 'n' )
			->from( $table )
			->where( [ 'k' => 'Luca' ] )
			->fetchField() );
	}

	private function createSourceTable() {
		$encTable = $this->db->tableName( 'tmp_src_tbl' );

		$this->db->query( "DROP TABLE IF EXISTS $encTable" );
		$this->db->query(
			"CREATE TEMPORARY TABLE $encTable (" .
			"sn serial not null, " .
			"sk text unique not null, " .
			"sv integer, " .
			"st integer, " .
			"PRIMARY KEY(sn)" .
			")"
		);
	}

	private function createDestTable() {
		$encTable = $this->db->tableName( 'tmp_dst_tbl' );

		$this->db->query( "DROP TABLE IF EXISTS $encTable" );
		$this->db->query(
			"CREATE TEMPORARY TABLE $encTable (" .
			"n serial not null, " .
			"k text unique not null, " .
			"v integer, " .
			"t integer, " .
			"PRIMARY KEY(n)" .
			")"
		);
	}
}
