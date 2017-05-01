<?php

use Wikimedia\Rdbms\IDatabase;

/**
 * @group Database
 * @group Database
 */
class DatabaseTest extends MediaWikiTestCase {
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

	/**
	 * @covers Database::dropTable
	 */
	public function testAddQuotesNull() {
		$check = "NULL";
		if ( $this->db->getType() === 'sqlite' || $this->db->getType() === 'oracle' ) {
			$check = "''";
		}
		$this->assertEquals( $check, $this->db->addQuotes( null ) );
	}

	public function testAddQuotesInt() {
		# returning just "1234" should be ok too, though...
		# maybe
		$this->assertEquals(
			"'1234'",
			$this->db->addQuotes( 1234 ) );
	}

	public function testAddQuotesFloat() {
		# returning just "1234.5678" would be ok too, though
		$this->assertEquals(
			"'1234.5678'",
			$this->db->addQuotes( 1234.5678 ) );
	}

	public function testAddQuotesString() {
		$this->assertEquals(
			"'string'",
			$this->db->addQuotes( 'string' ) );
	}

	public function testAddQuotesStringQuote() {
		$check = "'string''s cause trouble'";
		if ( $this->db->getType() === 'mysql' ) {
			$check = "'string\'s cause trouble'";
		}
		$this->assertEquals(
			$check,
			$this->db->addQuotes( "string's cause trouble" ) );
	}

	private function getSharedTableName( $table, $database, $prefix, $format = 'quoted' ) {
		global $wgSharedDB, $wgSharedTables, $wgSharedPrefix, $wgSharedSchema;

		$this->db->setTableAliases( [
			$table => [
				'dbname' => $database,
				'schema' => null,
				'prefix' => $prefix
			]
		] );

		$ret = $this->db->tableName( $table, $format );

		$this->db->setTableAliases( array_fill_keys(
			$wgSharedDB ? $wgSharedTables : [],
			[
				'dbname' => $wgSharedDB,
				'schema' => $wgSharedSchema,
				'prefix' => $wgSharedPrefix
			]
		) );

		return $ret;
	}

	private function prefixAndQuote( $table, $database = null, $prefix = null, $format = 'quoted' ) {
		if ( $this->db->getType() === 'sqlite' || $format !== 'quoted' ) {
			$quote = '';
		} elseif ( $this->db->getType() === 'mysql' ) {
			$quote = '`';
		} elseif ( $this->db->getType() === 'oracle' ) {
			$quote = '/*Q*/';
		} else {
			$quote = '"';
		}

		if ( $database !== null ) {
			if ( $this->db->getType() === 'oracle' ) {
				$database = $quote . $database . '.';
			} else {
				$database = $quote . $database . $quote . '.';
			}
		}

		if ( $prefix === null ) {
			$prefix = $this->dbPrefix();
		}

		if ( $this->db->getType() === 'oracle' ) {
			return strtoupper( $database . $quote . $prefix . $table );
		} else {
			return $database . $quote . $prefix . $table . $quote;
		}
	}

	public function testTableNameLocal() {
		$this->assertEquals(
			$this->prefixAndQuote( 'tablename' ),
			$this->db->tableName( 'tablename' )
		);
	}

	public function testTableNameRawLocal() {
		$this->assertEquals(
			$this->prefixAndQuote( 'tablename', null, null, 'raw' ),
			$this->db->tableName( 'tablename', 'raw' )
		);
	}

	public function testTableNameShared() {
		$this->assertEquals(
			$this->prefixAndQuote( 'tablename', 'sharedatabase', 'sh_' ),
			$this->getSharedTableName( 'tablename', 'sharedatabase', 'sh_' )
		);

		$this->assertEquals(
			$this->prefixAndQuote( 'tablename', 'sharedatabase', null ),
			$this->getSharedTableName( 'tablename', 'sharedatabase', null )
		);
	}

	public function testTableNameRawShared() {
		$this->assertEquals(
			$this->prefixAndQuote( 'tablename', 'sharedatabase', 'sh_', 'raw' ),
			$this->getSharedTableName( 'tablename', 'sharedatabase', 'sh_', 'raw' )
		);

		$this->assertEquals(
			$this->prefixAndQuote( 'tablename', 'sharedatabase', null, 'raw' ),
			$this->getSharedTableName( 'tablename', 'sharedatabase', null, 'raw' )
		);
	}

	public function testTableNameForeign() {
		$this->assertEquals(
			$this->prefixAndQuote( 'tablename', 'databasename', '' ),
			$this->db->tableName( 'databasename.tablename' )
		);
	}

	public function testTableNameRawForeign() {
		$this->assertEquals(
			$this->prefixAndQuote( 'tablename', 'databasename', '', 'raw' ),
			$this->db->tableName( 'databasename.tablename', 'raw' )
		);
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

	public function testTransactionIdle() {
		$db = $this->db;

		$db->setFlag( DBO_TRX );
		$called = false;
		$flagSet = null;
		$db->onTransactionIdle(
			function () use ( $db, &$flagSet, &$called ) {
				$called = true;
				$flagSet = $db->getFlag( DBO_TRX );
			},
			__METHOD__
		);
		$this->assertFalse( $flagSet, 'DBO_TRX off in callback' );
		$this->assertTrue( $db->getFlag( DBO_TRX ), 'DBO_TRX restored to default' );
		$this->assertTrue( $called, 'Callback reached' );

		$db->clearFlag( DBO_TRX );
		$flagSet = null;
		$db->onTransactionIdle(
			function () use ( $db, &$flagSet ) {
				$flagSet = $db->getFlag( DBO_TRX );
			},
			__METHOD__
		);
		$this->assertFalse( $flagSet, 'DBO_TRX off in callback' );
		$this->assertFalse( $db->getFlag( DBO_TRX ), 'DBO_TRX restored to default' );

		$db->clearFlag( DBO_TRX );
		$db->onTransactionIdle(
			function () use ( $db ) {
				$db->setFlag( DBO_TRX );
			},
			__METHOD__
		);
		$this->assertFalse( $db->getFlag( DBO_TRX ), 'DBO_TRX restored to default' );
	}

	public function testTransactionResolution() {
		$db = $this->db;

		$db->clearFlag( DBO_TRX );
		$db->begin( __METHOD__ );
		$called = false;
		$db->onTransactionResolution( function () use ( $db, &$called ) {
			$called = true;
			$db->setFlag( DBO_TRX );
		} );
		$db->commit( __METHOD__ );
		$this->assertFalse( $db->getFlag( DBO_TRX ), 'DBO_TRX restored to default' );
		$this->assertTrue( $called, 'Callback reached' );

		$db->clearFlag( DBO_TRX );
		$db->begin( __METHOD__ );
		$called = false;
		$db->onTransactionResolution( function () use ( $db, &$called ) {
			$called = true;
			$db->setFlag( DBO_TRX );
		} );
		$db->rollback( __METHOD__, IDatabase::FLUSHING_ALL_PEERS );
		$this->assertFalse( $db->getFlag( DBO_TRX ), 'DBO_TRX restored to default' );
		$this->assertTrue( $called, 'Callback reached' );
	}

	/**
	 * @covers Database::setTransactionListener()
	 */
	public function testTransactionListener() {
		$db = $this->db;

		$db->setTransactionListener( 'ping', function () use ( $db, &$called ) {
			$called = true;
		} );

		$called = false;
		$db->begin( __METHOD__ );
		$db->commit( __METHOD__ );
		$this->assertTrue( $called, 'Callback reached' );

		$called = false;
		$db->begin( __METHOD__ );
		$db->commit( __METHOD__ );
		$this->assertTrue( $called, 'Callback still reached' );

		$called = false;
		$db->begin( __METHOD__ );
		$db->rollback( __METHOD__ );
		$this->assertTrue( $called, 'Callback reached' );

		$db->setTransactionListener( 'ping', null );
		$called = false;
		$db->begin( __METHOD__ );
		$db->commit( __METHOD__ );
		$this->assertFalse( $called, 'Callback not reached' );
	}

	/**
	 * @covers Database::flushSnapshot()
	 */
	public function testFlushSnapshot() {
		$db = $this->db;

		$db->flushSnapshot( __METHOD__ ); // ok
		$db->flushSnapshot( __METHOD__ ); // ok

		$db->setFlag( DBO_TRX, $db::REMEMBER_PRIOR );
		$db->query( 'SELECT 1', __METHOD__ );
		$this->assertTrue( (bool)$db->trxLevel(), "Transaction started." );
		$db->flushSnapshot( __METHOD__ ); // ok
		$db->restoreFlags( $db::RESTORE_PRIOR );

		$this->assertFalse( (bool)$db->trxLevel(), "Transaction cleared." );
	}

	public function testGetScopedLock() {
		$db = $this->db;

		$db->setFlag( DBO_TRX );
		try {
			$this->badLockingMethodImplicit( $db );
		} catch ( RunTimeException $e ) {
			$this->assertTrue( $db->trxLevel() > 0, "Transaction not committed." );
		}
		$db->clearFlag( DBO_TRX );
		$db->rollback( __METHOD__, IDatabase::FLUSHING_ALL_PEERS );
		$this->assertTrue( $db->lockIsFree( 'meow', __METHOD__ ) );

		try {
			$this->badLockingMethodExplicit( $db );
		} catch ( RunTimeException $e ) {
			$this->assertTrue( $db->trxLevel() > 0, "Transaction not committed." );
		}
		$db->rollback( __METHOD__, IDatabase::FLUSHING_ALL_PEERS );
		$this->assertTrue( $db->lockIsFree( 'meow', __METHOD__ ) );
	}

	private function badLockingMethodImplicit( IDatabase $db ) {
		$lock = $db->getScopedLockAndFlush( 'meow', __METHOD__, 1 );
		$db->query( "SELECT 1" ); // trigger DBO_TRX
		throw new RunTimeException( "Uh oh!" );
	}

	private function badLockingMethodExplicit( IDatabase $db ) {
		$lock = $db->getScopedLockAndFlush( 'meow', __METHOD__, 1 );
		$db->begin( __METHOD__ );
		throw new RunTimeException( "Uh oh!" );
	}

	/**
	 * @covers Database::getFlag(
	 * @covers Database::setFlag()
	 * @covers Database::restoreFlags()
	 */
	public function testFlagSetting() {
		$db = $this->db;
		$origTrx = $db->getFlag( DBO_TRX );
		$origSsl = $db->getFlag( DBO_SSL );

		$origTrx
			? $db->clearFlag( DBO_TRX, $db::REMEMBER_PRIOR )
			: $db->setFlag( DBO_TRX, $db::REMEMBER_PRIOR );
		$this->assertEquals( !$origTrx, $db->getFlag( DBO_TRX ) );

		$origSsl
			? $db->clearFlag( DBO_SSL, $db::REMEMBER_PRIOR )
			: $db->setFlag( DBO_SSL, $db::REMEMBER_PRIOR );
		$this->assertEquals( !$origSsl, $db->getFlag( DBO_SSL ) );

		$db->restoreFlags( $db::RESTORE_INITIAL );
		$this->assertEquals( $origTrx, $db->getFlag( DBO_TRX ) );
		$this->assertEquals( $origSsl, $db->getFlag( DBO_SSL ) );

		$origTrx
			? $db->clearFlag( DBO_TRX, $db::REMEMBER_PRIOR )
			: $db->setFlag( DBO_TRX, $db::REMEMBER_PRIOR );
		$origSsl
			? $db->clearFlag( DBO_SSL, $db::REMEMBER_PRIOR )
			: $db->setFlag( DBO_SSL, $db::REMEMBER_PRIOR );

		$db->restoreFlags();
		$this->assertEquals( $origSsl, $db->getFlag( DBO_SSL ) );
		$this->assertEquals( !$origTrx, $db->getFlag( DBO_TRX ) );

		$db->restoreFlags();
		$this->assertEquals( $origSsl, $db->getFlag( DBO_SSL ) );
		$this->assertEquals( $origTrx, $db->getFlag( DBO_TRX ) );
	}

	/**
	 * @covers Database::tablePrefix()
	 * @covers Database::dbSchema()
	 */
	public function testMutators() {
		$old = $this->db->tablePrefix();
		$this->assertType( 'string', $old, 'Prefix is string' );
		$this->assertEquals( $old, $this->db->tablePrefix(), "Prefix unchanged" );
		$this->assertEquals( $old, $this->db->tablePrefix( 'xxx' ) );
		$this->assertEquals( 'xxx', $this->db->tablePrefix(), "Prefix set" );
		$this->db->tablePrefix( $old );
		$this->assertNotEquals( 'xxx', $this->db->tablePrefix() );

		$old = $this->db->dbSchema();
		$this->assertType( 'string', $old, 'Schema is string' );
		$this->assertEquals( $old, $this->db->dbSchema(), "Schema unchanged" );
		$this->assertEquals( $old, $this->db->dbSchema( 'xxx' ) );
		$this->assertEquals( 'xxx', $this->db->dbSchema(), "Schema set" );
		$this->db->dbSchema( $old );
		$this->assertNotEquals( 'xxx', $this->db->dbSchema() );
	}
}
