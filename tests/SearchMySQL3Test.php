<?php

require_once( 'SearchEngineTest.php' );
require_once( '../includes/SearchMySQL3.php' );

class SearchMySQL3Test extends SearchEngine_TestCase {
	var $db;
	
	function SearchMySQL3Test( $name ) {
		$this->PHPUnit_TestCase( $name );
	}
	
	function setUp() {
		$GLOBALS['wgContLang'] = new LanguageUtf8;
		$this->db =& buildTestDatabase(
			'mysql3',
			array( 'page', 'revision', 'text', 'searchindex' ) );
		if( $this->db ) {
			$this->insertSearchData();
		}
		$this->search =& new SearchMySQL3( $this->db );
	}
	
	function tearDown() {
		if( !is_null( $this->db ) ) {
			$this->db->close();
		}
		unset( $this->db );
		unset( $this->search );
	}

}

?>