<?php

require_once( 'PHPUnit.php' );
require_once( '../includes/Defines.php' );
require_once( '../includes/Database.php' );
require_once( '../includes/GlobalFunctions.php' );

class DatabaseTest extends PHPUnit_TestCase {
	var $db;
	
	function DatabaseTest( $name ) {
		$this->PHPUnit_TestCase( $name );
	}
	
	function setUp() {
		$this->db =& new Database();
	}
	
	function tearDown() {
		unset( $this->db );
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
		$this->assertEquals(
			"SELECT * FROM cur WHERE cur_namespace='4' AND cur_title='Snicker\'s_paradox'",
			$sql);
	}
	
	function testFillPreparedBang() {
		$sql = $this->db->fillPrepared(
			'SELECT user_id FROM ! WHERE user_name=?',
			array( '"user"', "Slash's Dot" ) );
		$this->assertEquals(
			"SELECT user_id FROM \"user\" WHERE user_name='Slash\'s Dot'",
			$sql);
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

?>