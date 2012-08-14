<?php

/**
 * @group Database
 * @group DatabaseBase
 */
class DatabaseTest extends MediaWikiTestCase {
	var $db, $functionTest = false;

	function setUp() {
		$this->db = wfGetDB( DB_MASTER );
	}

	function tearDown() {
		if ( $this->functionTest ) {
			$this->dropFunctions();
			$this->functionTest = false;
		}
	}

	function testAddQuotesNull() {
		$check = "NULL";
		if ( $this->db->getType() === 'sqlite' || $this->db->getType() === 'oracle' ) {
			$check = "''";
		}
		$this->assertEquals( $check, $this->db->addQuotes( null ) );
	}

	function testAddQuotesInt() {
		# returning just "1234" should be ok too, though...
		# maybe
		$this->assertEquals(
			"'1234'",
			$this->db->addQuotes( 1234 ) );
	}

	function testAddQuotesFloat() {
		# returning just "1234.5678" would be ok too, though
		$this->assertEquals(
			"'1234.5678'",
			$this->db->addQuotes( 1234.5678 ) );
	}

	function testAddQuotesString() {
		$this->assertEquals(
			"'string'",
			$this->db->addQuotes( 'string' ) );
	}

	function testAddQuotesStringQuote() {
		$check = "'string''s cause trouble'";
		if ( $this->db->getType() === 'mysql' ) {
			$check = "'string\'s cause trouble'";
		}
		$this->assertEquals(
			$check,
			$this->db->addQuotes( "string's cause trouble" ) );
	}

	private function getSharedTableName( $table, $database, $prefix, $format = 'quoted' ) {
		global $wgSharedDB, $wgSharedTables, $wgSharedPrefix;

		$oldName = $wgSharedDB;
		$oldTables = $wgSharedTables;
		$oldPrefix = $wgSharedPrefix;

		$wgSharedDB = $database;
		$wgSharedTables = array( $table );
		$wgSharedPrefix = $prefix;

		$ret = $this->db->tableName( $table, $format );

		$wgSharedDB = $oldName;
		$wgSharedTables = $oldTables;
		$wgSharedPrefix = $oldPrefix;

		return $ret;
	}

	private function prefixAndQuote( $table, $database = null, $prefix = null, $format = 'quoted' ) {
		if ( $this->db->getType() === 'sqlite' || $format !== 'quoted' ) {
			$quote = '';
		} elseif ( $this->db->getType() === 'mysql' ) {
			$quote = '`';
		} else {
			$quote = '"';
		}

		if ( $database !== null ) {
			$database = $quote . $database . $quote . '.';
		}

		if ( $prefix === null ) {
			$prefix = $this->dbPrefix();
		}

		return $database . $quote . $prefix . $table . $quote;
	}

	function testTableNameLocal() {
		$this->assertEquals(
			$this->prefixAndQuote( 'tablename' ),
			$this->db->tableName( 'tablename' )
		);
	}

	function testTableNameRawLocal() {
		$this->assertEquals(
			$this->prefixAndQuote( 'tablename', null, null, 'raw' ),
			$this->db->tableName( 'tablename', 'raw' )
		);
	}

	function testTableNameShared() {
		$this->assertEquals(
			$this->prefixAndQuote( 'tablename', 'sharedatabase', 'sh_' ),
			$this->getSharedTableName( 'tablename', 'sharedatabase', 'sh_' )
		);

		$this->assertEquals(
			$this->prefixAndQuote( 'tablename', 'sharedatabase', null ),
			$this->getSharedTableName( 'tablename', 'sharedatabase', null )
		);
	}

	function testTableNameRawShared() {
		$this->assertEquals(
			$this->prefixAndQuote( 'tablename', 'sharedatabase', 'sh_', 'raw' ),
			$this->getSharedTableName( 'tablename', 'sharedatabase', 'sh_', 'raw' )
		);

		$this->assertEquals(
			$this->prefixAndQuote( 'tablename', 'sharedatabase', null, 'raw' ),
			$this->getSharedTableName( 'tablename', 'sharedatabase', null, 'raw' )
		);
	}

	function testTableNameForeign() {
		$this->assertEquals(
			$this->prefixAndQuote( 'tablename', 'databasename', '' ),
			$this->db->tableName( 'databasename.tablename' )
		);
	}

	function testTableNameRawForeign() {
		$this->assertEquals(
			$this->prefixAndQuote( 'tablename', 'databasename', '', 'raw' ),
			$this->db->tableName( 'databasename.tablename', 'raw' )
		);
	}

	function testFillPreparedEmpty() {
		$sql = $this->db->fillPrepared(
			'SELECT * FROM interwiki', array() );
		$this->assertEquals(
			"SELECT * FROM interwiki",
			$sql );
	}

	function testFillPreparedQuestion() {
		$sql = $this->db->fillPrepared(
			'SELECT * FROM cur WHERE cur_namespace=? AND cur_title=?',
			array( 4, "Snicker's_paradox" ) );

		$check = "SELECT * FROM cur WHERE cur_namespace='4' AND cur_title='Snicker''s_paradox'";
		if ( $this->db->getType() === 'mysql' ) {
			$check = "SELECT * FROM cur WHERE cur_namespace='4' AND cur_title='Snicker\'s_paradox'";
		}
		$this->assertEquals( $check, $sql );
	}

	function testFillPreparedBang() {
		$sql = $this->db->fillPrepared(
			'SELECT user_id FROM ! WHERE user_name=?',
			array( '"user"', "Slash's Dot" ) );

		$check = "SELECT user_id FROM \"user\" WHERE user_name='Slash''s Dot'";
		if ( $this->db->getType() === 'mysql' ) {
			$check = "SELECT user_id FROM \"user\" WHERE user_name='Slash\'s Dot'";
		}
		$this->assertEquals( $check, $sql );
	}

	function testFillPreparedRaw() {
		$sql = $this->db->fillPrepared(
			"SELECT * FROM cur WHERE cur_title='This_\\&_that,_WTF\\?\\!'",
			array( '"user"', "Slash's Dot" ) );
		$this->assertEquals(
			"SELECT * FROM cur WHERE cur_title='This_&_that,_WTF?!'",
			$sql );
	}

	/**
	 * @group Broken
	 */
	function testStoredFunctions() {
		if ( !in_array( wfGetDB( DB_MASTER )->getType(), array( 'mysql', 'postgres' ) ) ) {
			$this->markTestSkipped( 'MySQL or Postgres required' );
		}
		global $IP;
		$this->dropFunctions();
		$this->functionTest = true;
		$this->assertTrue( $this->db->sourceFile( "$IP/tests/phpunit/data/db/{$this->db->getType()}/functions.sql" ) );
		$res = $this->db->query( 'SELECT mw_test_function() AS test', __METHOD__ );
		$this->assertEquals( 42, $res->fetchObject()->test );
	}

	private function dropFunctions() {
		$this->db->query( 'DROP FUNCTION IF EXISTS mw_test_function'
			. ( $this->db->getType() == 'postgres'  ? '()' : '' )
		);
	}
}


