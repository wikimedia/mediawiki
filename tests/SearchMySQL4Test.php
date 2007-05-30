<?php
require_once( 'SearchEngineTest.php' );

class SearchMySQL4Test extends SearchEngine_TestCase {
	var $db;

	function SearchMySQL4Test( $name ) {
		$this->PHPUnit_TestCase( $name );
	}

	function setUp() {
		$GLOBALS['wgContLang'] = new Language;
		$this->db =& buildTestDatabase(
			'mysql4',
			array( 'page', 'revision', 'text', 'searchindex' ) );
		if( $this->db ) {
			$this->insertSearchData();
		}
		$this->search = new SearchMySQL4( $this->db );
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
