<?php

use Wikimedia\Rdbms\Database;
use Wikimedia\Rdbms\DatabasePostgres;
use Wikimedia\Rdbms\DBQueryError;
use Wikimedia\Rdbms\IDatabase;
use Wikimedia\ScopedCallback;
use Wikimedia\TestingAccessWrapper;

/**
 * @group Database
 */
class DatabasePostgresTest extends MediaWikiIntegrationTestCase {

	protected function setUp(): void {
		parent::setUp();
		if ( !$this->db instanceof DatabasePostgres ) {
			$this->markTestSkipped( 'Not PostgreSQL' );
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
	 * @covers Wikimedia\Rdbms\DatabasePostgres::insert
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
	 * @covers Wikimedia\Rdbms\DatabasePostgres::insert
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
	 * @covers Wikimedia\Rdbms\DatabasePostgres::doInsertSelectNative
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
	 * @covers Wikimedia\Rdbms\DatabasePostgres::doInsertSelectNative
	 */
	public function testInsertSelectIgnoreNew() {
		if ( $this->db->getServerVersion() < 9.5 ) {
			$this->markTestSkipped( 'PostgreSQL version is ' . $this->db->getServerVersion() );
		}

		$this->doTestInsertSelectIgnore();
	}

	/**
	 * @covers \Wikimedia\Rdbms\DatabasePostgres::getAttributes
	 */
	public function testAttributes() {
		$dbFactory = $this->getServiceContainer()->getDatabaseFactory();
		$this->assertTrue(
			$dbFactory->attributesFromType( 'postgres' )[Database::ATTR_SCHEMAS_AS_TABLE_GROUPS]
		);
	}

	/**
	 * @covers \Wikimedia\Rdbms\Database::insert()
	 * @covers \Wikimedia\Rdbms\Database::insertId()
	 */
	public function testInsertIdAfterInsert() {
		$dTable = $this->createDestTable();

		$rows = [ [ 'k' => 'Luca', 'v' => mt_rand( 1, 100 ), 't' => time() ] ];

		$this->db->insert( $dTable, $rows, __METHOD__ );
		$this->assertSame( 1, $this->db->affectedRows() );
		$this->assertSame( 1, $this->db->insertId() );

		$this->assertNWhereKEqualsLuca( 1, $dTable );
		$this->assertSame( 1, $this->db->affectedRows() );

		$this->dropDestTable();
	}

	/**
	 * @covers \Wikimedia\Rdbms\Database::insert()
	 * @covers \Wikimedia\Rdbms\Database::insertId()
	 */
	public function testInsertIdAfterInsertIgnore() {
		$dTable = $this->createDestTable();

		$rows = [ [ 'k' => 'Luca', 'v' => mt_rand( 1, 100 ), 't' => time() ] ];

		$this->db->insert( $dTable, $rows, __METHOD__, 'IGNORE' );
		$this->assertSame( 1, $this->db->affectedRows() );
		$this->assertSame( 1, $this->db->insertId() );
		$this->assertNWhereKEqualsLuca( 1, $dTable );

		$this->db->insert( $dTable, $rows, __METHOD__, 'IGNORE' );
		$this->assertSame( 0, $this->db->affectedRows() );
		$this->assertSame( 0, $this->db->insertId() );

		$this->assertNWhereKEqualsLuca( 1, $dTable );
		$this->assertSame( 1, $this->db->affectedRows() );

		$this->dropDestTable();
	}

	/**
	 * @covers \Wikimedia\Rdbms\Database::replace()
	 * @covers \Wikimedia\Rdbms\Database::insertId()
	 */
	public function testInsertIdAfterReplace() {
		$dTable = $this->createDestTable();

		$rows = [ [ 'k' => 'Luca', 'v' => mt_rand( 1, 100 ), 't' => time() ] ];

		$this->db->replace( $dTable, 'k', $rows, __METHOD__ );
		$this->assertSame( 1, $this->db->affectedRows() );
		$this->assertSame( 1, $this->db->insertId() );
		$this->assertNWhereKEqualsLuca( 1, $dTable );

		$this->db->replace( $dTable, 'k', $rows, __METHOD__ );
		$this->assertSame( 1, $this->db->affectedRows() );
		$this->assertSame( 2, $this->db->insertId() );

		$this->assertNWhereKEqualsLuca( 2, $dTable );
		$this->assertSame( 1, $this->db->affectedRows() );

		$this->dropDestTable();
	}

	/**
	 * @covers \Wikimedia\Rdbms\Database::upsert()
	 * @covers \Wikimedia\Rdbms\Database::insertId()
	 */
	public function testInsertIdAfterUpsert() {
		$dTable = $this->createDestTable();

		$rows = [ [ 'k' => 'Luca', 'v' => mt_rand( 1, 100 ), 't' => time() ] ];
		$set = [
			'v = ' . $this->db->buildExcludedValue( 'v' ),
			't = ' . $this->db->buildExcludedValue( 't' ) . ' + 1'
		];

		$this->db->upsert( $dTable, $rows, 'k', $set, __METHOD__ );
		$this->assertSame( 1, $this->db->affectedRows() );
		$this->assertSame( 1, $this->db->insertId() );
		$this->assertNWhereKEqualsLuca( 1, $dTable );

		$this->db->upsert( $dTable, $rows, 'k', $set, __METHOD__ );
		$this->assertSame( 1, $this->db->affectedRows() );
		$this->assertSame( 1, $this->db->insertId() );

		$this->assertNWhereKEqualsLuca( 1, $dTable );
		$this->assertSame( 1, $this->db->affectedRows() );

		$this->dropDestTable();
	}

	/**
	 * @covers \Wikimedia\Rdbms\Database::insertSelect()
	 * @covers \Wikimedia\Rdbms\Database::insertId()
	 */
	public function testInsertIdAfterInsertSelect() {
		$sTable = $this->createSourceTable();
		$dTable = $this->createDestTable();

		$rows = [ [ 'sk' => 'Luca', 'sv' => mt_rand( 1, 100 ), 'st' => time() ] ];
		$this->db->insert( $sTable, $rows, __METHOD__, 'IGNORE' );
		$this->assertSame( 1, $this->db->affectedRows() );
		$this->assertSame( 1, $this->db->insertId() );
		$this->assertSame( 1, (int)$this->db->newSelectQueryBuilder()
			->select( 'sn' )
			->from( $sTable )
			->where( [ 'sk' => 'Luca' ] )
			->fetchField() );

		$this->db->insertSelect(
			$dTable,
			$sTable,
			[ 'k' => 'sk', 'v' => 'sv', 't' => 'st' ],
			[ 'sk' => 'Luca' ],
			__METHOD__,
			'IGNORE'
		);
		$this->assertSame( 1, $this->db->affectedRows() );
		$this->assertSame( 1, $this->db->insertId() );

		$this->assertNWhereKEqualsLuca( 1, $dTable );
		$this->assertSame( 1, $this->db->affectedRows() );

		$this->dropSourceTable();
		$this->dropDestTable();
	}

	/**
	 * @covers \Wikimedia\Rdbms\Database::insertSelect()
	 * @covers \Wikimedia\Rdbms\Database::insertId()
	 */
	public function testInsertIdAfterInsertSelectIgnore() {
		$sTable = $this->createSourceTable();
		$dTable = $this->createDestTable();

		$rows = [ [ 'sk' => 'Luca', 'sv' => mt_rand( 1, 100 ), 'st' => time() ] ];
		$this->db->insert( $sTable, $rows, __METHOD__, 'IGNORE' );
		$this->assertSame( 1, $this->db->affectedRows() );
		$this->assertSame( 1, $this->db->insertId() );
		$this->assertSame( 1, (int)$this->db->newSelectQueryBuilder()
			->select( 'sn' )
			->from( $sTable )
			->where( [ 'sk' => 'Luca' ] )
			->fetchField() );

		$this->db->insertSelect(
			$dTable,
			$sTable,
			[ 'k' => 'sk', 'v' => 'sv', 't' => 'st' ],
			[ 'sk' => 'Luca' ],
			__METHOD__,
			'IGNORE'
		);
		$this->assertSame( 1, $this->db->affectedRows() );
		$this->assertSame( 1, $this->db->insertId() );
		$this->assertNWhereKEqualsLuca( 1, $dTable );

		$this->db->insertSelect(
			$dTable,
			$sTable,
			[ 'k' => 'sk', 'v' => 'sv', 't' => 'st' ],
			[ 'sk' => 'Luca' ],
			__METHOD__,
			'IGNORE'
		);
		$this->assertSame( 0, $this->db->affectedRows() );
		$this->assertSame( 0, $this->db->insertId() );

		$this->assertNWhereKEqualsLuca( 1, $dTable );
		$this->assertSame( 1, $this->db->affectedRows() );

		$this->dropSourceTable();
		$this->dropDestTable();
	}

	private function assertNWhereKEqualsLuca( $expected, $table ) {
		$this->assertSame( $expected, (int)$this->db->newSelectQueryBuilder()
			->select( 'n' )
			->from( $table )
			->where( [ 'k' => 'Luca' ] )
			->fetchField() );
	}

	private function createSourceTable() {
		$prefix = self::dbPrefix();

		$this->db->query( "DROP TABLE IF EXISTS {$prefix}tmp_src_tbl" );
		$this->db->query(
			"CREATE TEMPORARY TABLE {$prefix}tmp_src_tbl (" .
			"sn serial not null, " .
			"sk text unique, " .
			"sv integer, " .
			"st integer, " .
			"PRIMARY KEY(sn)" .
			")"
		);

		return "tmp_src_tbl";
	}

	private function createDestTable() {
		$prefix = self::dbPrefix();

		$this->db->query( "DROP TABLE IF EXISTS {$prefix}tmp_dst_tbl" );
		$this->db->query(
			"CREATE TEMPORARY TABLE {$prefix}tmp_dst_tbl (" .
				"n serial not null, " .
				"k text unique, " .
				"v integer, " .
				"t integer, " .
				"PRIMARY KEY(n)" .
			")"
		);

		return "tmp_dst_tbl";
	}

	private function dropSourceTable() {
		$prefix = self::dbPrefix();

		$this->db->query( "DROP TABLE IF EXISTS {$prefix}tmp_src_tbl" );
	}

	private function dropDestTable() {
		$prefix = self::dbPrefix();

		$this->db->query( "DROP TABLE IF EXISTS {$prefix}tmp_dst_tbl" );
	}
}
