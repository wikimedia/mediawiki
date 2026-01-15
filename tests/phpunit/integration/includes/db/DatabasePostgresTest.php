<?php

use Psr\Log\NullLogger;
use Wikimedia\Rdbms\Database;
use Wikimedia\Rdbms\DBConnRef;
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
		if ( $this->getDb()->getType() !== 'postgres' ) {
			$this->markTestSkipped( 'Not PostgreSQL' );
		}
	}

	public function addDBDataOnce() {
		if ( $this->getDb()->getType() === 'postgres' ) {
			$this->createSourceTable();
			$this->createDestTable();
		}
	}

	/**
	 * Temporarily suppress query error logging
	 *
	 * @param IDatabase $db
	 * @return ScopedCallback
	 */
	private function scopedDisableErrorLog( IDatabase $db ) {
		if ( $db instanceof DBConnRef ) {
			$db->ensureConnection();
			$db = TestingAccessWrapper::newFromObject( $db )->conn;
		}
		$oldLogger = TestingAccessWrapper::newFromObject( $db )->logger;
		$db->setLogger( new NullLogger );
		return new ScopedCallback( static function () use ( $db, $oldLogger ) {
			$db->setLogger( $oldLogger );
		} );
	}

	public function testInsertIgnore() {
		$fname = __METHOD__;
		$reset = new ScopedCallback( function () use ( $fname ) {
			if ( $this->getDb()->explicitTrxActive() ) {
				$this->getDb()->rollback( $fname );
			}
			$this->getDb()->query( 'DROP TABLE IF EXISTS ' . $this->getDb()->tableName( 'foo' ), $fname );
		} );

		$this->getDb()->query(
			"CREATE TEMPORARY TABLE {$this->getDb()->tableName( 'foo' )} (i INTEGER NOT NULL PRIMARY KEY)",
			__METHOD__
		);
		$this->getDb()->insert( 'foo', [ [ 'i' => 1 ], [ 'i' => 2 ] ], __METHOD__ );

		// Normal INSERT IGNORE
		$this->getDb()->begin( __METHOD__ );
		$this->getDb()->insert(
			'foo', [ [ 'i' => 3 ], [ 'i' => 2 ], [ 'i' => 5 ] ], __METHOD__, [ 'IGNORE' ]
		);
		$this->assertSame( 2, $this->getDb()->affectedRows() );
		$this->assertSame(
			[ '1', '2', '3', '5' ],
			$this->getDb()->newSelectQueryBuilder()
				->select( 'i' )
				->from( 'foo' )
				->orderBy( 'i' )
				->caller( __METHOD__ )->fetchFieldValues()
		);
		$this->getDb()->rollback( __METHOD__ );

		// INSERT IGNORE doesn't ignore stuff like NOT NULL violations
		$this->getDb()->begin( __METHOD__ );
		$this->getDb()->startAtomic( __METHOD__, IDatabase::ATOMIC_CANCELABLE );
		// Suppress dberror.log, which Quibble requires to be zero size after the test exits
		$scope = $this->scopedDisableErrorLog( $this->getDb() );
		try {
			$this->getDb()->insert(
				'foo', [ [ 'i' => 7 ], [ 'i' => null ] ], __METHOD__, [ 'IGNORE' ]
			);
			$this->getDb()->endAtomic( __METHOD__ );
			$this->fail( 'Expected exception not thrown' );
		} catch ( DBQueryError ) {
			$this->assertSame( 0, $this->getDb()->affectedRows() );
			$this->getDb()->cancelAtomic( __METHOD__ );
		}
		ScopedCallback::consume( $scope );
		$this->assertSame(
			[ '1', '2' ],
			$this->getDb()->newSelectQueryBuilder()
				->select( 'i' )
				->from( 'foo' )
				->orderBy( 'i' )
				->caller( __METHOD__ )->fetchFieldValues()
		);
		$this->getDb()->rollback( __METHOD__ );
	}

	private function testInsertSelectIgnore() {
		$fname = __METHOD__;
		$reset = new ScopedCallback( function () use ( $fname ) {
			if ( $this->getDb()->explicitTrxActive() ) {
				$this->getDb()->rollback( $fname );
			}
			$this->getDb()->query( 'DROP TABLE IF EXISTS ' . $this->getDb()->tableName( 'foo' ), $fname );
			$this->getDb()->query( 'DROP TABLE IF EXISTS ' . $this->getDb()->tableName( 'bar' ), $fname );
		} );

		$this->getDb()->query(
			"CREATE TEMPORARY TABLE {$this->getDb()->tableName( 'foo' )} (i INTEGER)",
			__METHOD__
		);
		$this->getDb()->query(
			"CREATE TEMPORARY TABLE {$this->getDb()->tableName( 'bar' )} (i INTEGER NOT NULL PRIMARY KEY)",
			__METHOD__
		);
		$this->getDb()->insert( 'bar', [ [ 'i' => 1 ], [ 'i' => 2 ] ], __METHOD__ );

		// Normal INSERT IGNORE
		$this->getDb()->begin( __METHOD__ );
		$this->getDb()->insert( 'foo', [ [ 'i' => 3 ], [ 'i' => 2 ], [ 'i' => 5 ] ], __METHOD__ );
		$this->getDb()->insertSelect( 'bar', 'foo', [ 'i' => 'i' ], [], __METHOD__, [ 'IGNORE' ] );
		$this->assertSame( 2, $this->getDb()->affectedRows() );
		$this->assertSame(
			[ '1', '2', '3', '5' ],
			$this->getDb()->newSelectQueryBuilder()
				->select( 'i' )
				->from( 'bar' )
				->orderBy( 'i' )
				->caller( __METHOD__ )->fetchFieldValues()
		);
		$this->getDb()->rollback( __METHOD__ );

		// INSERT IGNORE doesn't ignore stuff like NOT NULL violations
		$this->getDb()->begin( __METHOD__ );
		$this->getDb()->insert( 'foo', [ [ 'i' => 7 ], [ 'i' => null ] ], __METHOD__ );
		$this->getDb()->startAtomic( __METHOD__, IDatabase::ATOMIC_CANCELABLE );
		try {
			$this->getDb()->insertSelect( 'bar', 'foo', [ 'i' => 'i' ], [], __METHOD__, [ 'IGNORE' ] );
			$this->getDb()->endAtomic( __METHOD__ );
			$this->fail( 'Expected exception not thrown' );
		} catch ( DBQueryError ) {
			$this->assertSame( 0, $this->getDb()->affectedRows() );
			$this->getDb()->cancelAtomic( __METHOD__ );
		}
		$this->assertSame(
			[ '1', '2' ],
			$this->getDb()->newSelectQueryBuilder()
				->select( 'i' )
				->from( 'bar' )
				->orderBy( 'i' )
				->caller( __METHOD__ )->fetchFieldValues()
		);
		$this->getDb()->rollback( __METHOD__ );
	}

	public function testAttributes() {
		$dbFactory = $this->getServiceContainer()->getDatabaseFactory();
		$this->assertTrue(
			$dbFactory->attributesFromType( 'postgres' )[Database::ATTR_SCHEMAS_AS_TABLE_GROUPS]
		);
	}

	public function testInsertIdAfterInsert() {
		$rows = [ [ 'k' => 'Luca', 'v' => mt_rand( 1, 100 ), 't' => time() ] ];

		$this->getDb()->insert( self::DST_TABLE, $rows, __METHOD__ );
		$this->assertSame( 1, $this->getDb()->affectedRows() );
		$this->assertSame( 1, $this->getDb()->insertId() );

		$this->assertNWhereKEqualsLuca( 1, self::DST_TABLE );
		$this->assertSame( 1, $this->getDb()->affectedRows() );
	}

	public function testInsertIdAfterInsertIgnore() {
		$rows = [ [ 'k' => 'Luca', 'v' => mt_rand( 1, 100 ), 't' => time() ] ];

		$this->getDb()->insert( self::DST_TABLE, $rows, __METHOD__, 'IGNORE' );
		$this->assertSame( 1, $this->getDb()->affectedRows() );
		$this->assertSame( 1, $this->getDb()->insertId() );
		$this->assertNWhereKEqualsLuca( 1, self::DST_TABLE );

		$this->getDb()->insert( self::DST_TABLE, $rows, __METHOD__, 'IGNORE' );
		$this->assertSame( 0, $this->getDb()->affectedRows() );
		$this->assertSame( 0, $this->getDb()->insertId() );

		$this->assertNWhereKEqualsLuca( 1, self::DST_TABLE );
		$this->assertSame( 1, $this->getDb()->affectedRows() );
	}

	public function testInsertIdAfterReplace() {
		$rows = [ [ 'k' => 'Luca', 'v' => mt_rand( 1, 100 ), 't' => time() ] ];

		$this->getDb()->replace( self::DST_TABLE, 'k', $rows, __METHOD__ );
		$this->assertSame( 1, $this->getDb()->affectedRows() );
		$this->assertSame( 1, $this->getDb()->insertId() );
		$this->assertNWhereKEqualsLuca( 1, self::DST_TABLE );

		$this->getDb()->replace( self::DST_TABLE, 'k', $rows, __METHOD__ );
		$this->assertSame( 1, $this->getDb()->affectedRows() );
		$this->assertSame( 2, $this->getDb()->insertId() );

		$this->assertNWhereKEqualsLuca( 2, self::DST_TABLE );
		$this->assertSame( 1, $this->getDb()->affectedRows() );
	}

	public function testInsertIdAfterUpsert() {
		$rows = [ [ 'k' => 'Luca', 'v' => mt_rand( 1, 100 ), 't' => time() ] ];
		$set = [
			'v = ' . $this->getDb()->buildExcludedValue( 'v' ),
			't = ' . $this->getDb()->buildExcludedValue( 't' ) . ' + 1'
		];

		$this->getDb()->upsert( self::DST_TABLE, $rows, 'k', $set, __METHOD__ );
		$this->assertSame( 1, $this->getDb()->affectedRows() );
		$this->assertSame( 1, $this->getDb()->insertId() );
		$this->assertNWhereKEqualsLuca( 1, self::DST_TABLE );

		$this->getDb()->upsert( self::DST_TABLE, $rows, 'k', $set, __METHOD__ );
		$this->assertSame( 1, $this->getDb()->affectedRows() );
		$this->assertSame( 1, $this->getDb()->insertId() );

		$this->assertNWhereKEqualsLuca( 1, self::DST_TABLE );
		$this->assertSame( 1, $this->getDb()->affectedRows() );
	}

	public function testInsertIdAfterInsertSelect() {
		$rows = [ [ 'sk' => 'Luca', 'sv' => mt_rand( 1, 100 ), 'st' => time() ] ];
		$this->getDb()->insert( self::SRC_TABLE, $rows, __METHOD__, 'IGNORE' );
		$this->assertSame( 1, $this->getDb()->affectedRows() );
		$this->assertSame( 1, $this->getDb()->insertId() );
		$this->assertSame( 1, (int)$this->getDb()->newSelectQueryBuilder()
			->select( 'sn' )
			->from( self::SRC_TABLE )
			->where( [ 'sk' => 'Luca' ] )
			->fetchField() );

		$this->getDb()->insertSelect(
			self::DST_TABLE,
			self::SRC_TABLE,
			[ 'k' => 'sk', 'v' => 'sv', 't' => 'st' ],
			[ 'sk' => 'Luca' ],
			__METHOD__,
			'IGNORE'
		);
		$this->assertSame( 1, $this->getDb()->affectedRows() );
		$this->assertSame( 1, $this->getDb()->insertId() );

		$this->assertNWhereKEqualsLuca( 1, self::DST_TABLE );
		$this->assertSame( 1, $this->getDb()->affectedRows() );
	}

	public function testInsertIdAfterInsertSelectIgnore() {
		$rows = [ [ 'sk' => 'Luca', 'sv' => mt_rand( 1, 100 ), 'st' => time() ] ];
		$this->getDb()->insert( self::SRC_TABLE, $rows, __METHOD__, 'IGNORE' );
		$this->assertSame( 1, $this->getDb()->affectedRows() );
		$this->assertSame( 1, $this->getDb()->insertId() );
		$this->assertSame( 1, (int)$this->getDb()->newSelectQueryBuilder()
			->select( 'sn' )
			->from( self::SRC_TABLE )
			->where( [ 'sk' => 'Luca' ] )
			->fetchField() );

		$this->getDb()->insertSelect(
			self::DST_TABLE,
			self::SRC_TABLE,
			[ 'k' => 'sk', 'v' => 'sv', 't' => 'st' ],
			[ 'sk' => 'Luca' ],
			__METHOD__,
			'IGNORE'
		);
		$this->assertSame( 1, $this->getDb()->affectedRows() );
		$this->assertSame( 1, $this->getDb()->insertId() );
		$this->assertNWhereKEqualsLuca( 1, self::DST_TABLE );

		$this->getDb()->insertSelect(
			self::DST_TABLE,
			self::SRC_TABLE,
			[ 'k' => 'sk', 'v' => 'sv', 't' => 'st' ],
			[ 'sk' => 'Luca' ],
			__METHOD__,
			'IGNORE'
		);
		$this->assertSame( 0, $this->getDb()->affectedRows() );
		$this->assertSame( 0, $this->getDb()->insertId() );

		$this->assertNWhereKEqualsLuca( 1, self::DST_TABLE );
		$this->assertSame( 1, $this->getDb()->affectedRows() );
	}

	public function testFieldAndIndexInfo() {
		$db = $this->getDb();
		$prefixedTable = $db->tableName( 'tmp_schema_tbl', 'raw' );
		$db->query(
			"CREATE TEMPORARY TABLE $prefixedTable (" .
			"n serial not null, " .
			"k text not null, " .
			"v integer, " .
			"t integer, " .
			"PRIMARY KEY(n)" .
			")"
		);
		$db->query( "CREATE UNIQUE INDEX k ON $prefixedTable (k)" );
		$db->query( "CREATE INDEX t ON $prefixedTable (t)" );

		$this->assertTrue( $db->fieldExists( 'tmp_schema_tbl', 'n' ) );
		$this->assertTrue( $db->fieldExists( 'tmp_schema_tbl', 'k' ) );
		$this->assertTrue( $db->fieldExists( 'tmp_schema_tbl', 'v' ) );
		$this->assertTrue( $db->fieldExists( 'tmp_schema_tbl', 't' ) );
		$this->assertFalse( $db->fieldExists( 'tmp_schema_tbl', 'x' ) );

		$this->assertTrue( $db->indexExists( 'tmp_schema_tbl', 'k' ) );
		$this->assertTrue( $db->indexExists( 'tmp_schema_tbl', 't' ) );
		$this->assertFalse( $db->indexExists( 'tmp_schema_tbl', 'x' ) );
		$this->assertFalse( $db->indexExists( 'tmp_schema_tbl', 'PRIMARY' ) );
		$this->assertTrue( $db->indexExists( 'tmp_schema_tbl', "{$prefixedTable}_pkey" ) );

		$this->assertTrue( $db->indexUnique( 'tmp_schema_tbl', 'k' ) );
		$this->assertFalse( $db->indexUnique( 'tmp_schema_tbl', 't' ) );
		$this->assertNull( $db->indexUnique( 'tmp_schema_tbl', 'x' ) );
		$this->assertNull( $db->indexUnique( 'tmp_schema_tbl', 'PRIMARY' ) );
		$this->assertTrue( $db->indexUnique( 'tmp_schema_tbl', "{$prefixedTable}_pkey" ) );
	}

	private function assertNWhereKEqualsLuca( $expected, $table ) {
		$this->assertSame( $expected, (int)$this->getDb()->newSelectQueryBuilder()
			->select( 'n' )
			->from( $table )
			->where( [ 'k' => 'Luca' ] )
			->fetchField() );
	}

	private function createSourceTable() {
		$encTable = $this->getDb()->tableName( 'tmp_src_tbl' );

		$this->getDb()->query( "DROP TABLE IF EXISTS $encTable" );
		$this->getDb()->query(
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
		$encTable = $this->getDb()->tableName( 'tmp_dst_tbl' );

		$this->getDb()->query( "DROP TABLE IF EXISTS $encTable" );
		$this->getDb()->query(
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
