<?php
class SearchDbTest extends SearchEngineTest {
	var $db;

	function setUp() {
		$this->db = wfGetDB( DB_MASTER );
		if ( !$this->db  ) {
			$this->markTestIncomplete( "Can't find a database to test with." );
 		}

		$GLOBALS['wgContLang'] = new Language;
		$this->insertSearchData();

		$this->insertSearchData();
		$searchType = preg_replace( "/Database/", "Search",
								   get_class( $this->db ) );
		$this->search = new $searchType( $this->db );
	}

	function tearDown() {
		$this->removeSearchData();
		if ( !is_null( $this->db ) ) {
			wfGetLB()->closeConnecton( $this->db );
		}
		unset( $this->db );
		unset( $this->search );
	}
}


