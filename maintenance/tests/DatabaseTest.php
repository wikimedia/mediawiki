<?php

class DatabaseTest extends PHPUnit_Framework_TestCase {
	var $db;

	function setUp() {
		$this->db = wfGetDB( DB_SLAVE );
	}

	function testAddQuotesNull() {
		$check = "NULL";
		if ( $this->db->getType() === 'sqlite' ) {
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

	function testFillPreparedEmpty() {
		$sql = $this->db->fillPrepared(
			'SELECT * FROM interwiki', array() );
		$this->assertEquals(
			"SELECT * FROM interwiki",
			$sql);
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
			$sql);
	}

}


