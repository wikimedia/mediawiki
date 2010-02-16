<?php
require_once( 'SearchEngineTest.php' );

class SearchMySQLTest extends SearchEngineTest {
	var $db;

	function setUp() {
		$GLOBALS['wgContLang'] = new Language;
		$this->db = $this->buildTestDatabase(
			array( 'page', 'revision', 'text', 'searchindex', 'user' ) );
		if( $this->db ) {
			$this->insertSearchData();
		}
		$this->search = new SearchMySQL( $this->db );
	}

	function tearDown() {
		if( !is_null( $this->db ) ) {
			wfGetLB()->closeConnecton( $this->db );
		}
		unset( $this->db );
		unset( $this->search );
	}
}


